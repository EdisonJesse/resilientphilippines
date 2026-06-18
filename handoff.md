# Project Handoff: Resilient Philippines WordPress Portal

This document serves as a transition guide for continuing development on the Resilient Philippines project.

---

## 1. Environment & Setup

- **Local Workspace Path**: `c:\Users\ediso\Local Sites\resilientphilippines`
- **Local Website URL**: `http://resilientphilippines.local`
- **PHP CLI Executable**: `C:\Users\ediso\AppData\Roaming\Local\lightning-services\php-7.4.30+6\bin\win64\php.exe`
- **Local database port**: `10004`
  - *Gotcha*: When running CLI bootstrap test scripts, override the DB host to `127.0.0.1:10004` (instead of `localhost`) to prevent IPv6 DNS resolution delays on Windows.

---

## 2. Git Repository & Workflow

- **Git Root Directory**: `c:\Users\ediso\Local Sites\resilientphilippines\app\public`
  - *Note*: The `.git` repository folder is inside `app/public`, not the workspace root. All git commands must be run inside `c:\Users\ediso\Local Sites\resilientphilippines\app\public`.
- **Remote GitHub Repository**: `https://github.com/EdisonJesse/resilientphilippines.git`
- **Active Branch**: `main`
- **Standard Git Workflow**:
  ```powershell
  # Navigate to Git root
  cd "c:\Users\ediso\Local Sites\resilientphilippines\app\public"
  
  # Stage files
  git add <files>
  
  # Commit changes
  git commit -m "Commit message"
  
  # Push to GitHub
  git push origin main
  ```

---

## 3. Codebase Architecture

The custom functionality is divided between a core plugin and a child theme:

### Core Custom Plugin
Path: `app/public/wp-content/plugins/rp-resource-hub/`
- **`rp-resource-hub.php`**: Registers CPTs (`accord_library`, `partner_resources`, etc.), custom taxonomies (`resource_format`, `hazard_type`, etc.), database schema (for consent tracking and downloads), and registers shortcodes (`[rp_resource_catalog]`, `[rp_news_catalog]`, `[rp_submit_post_form]`, `[rp_my_contributions]`).

### Custom Child Theme
Path: `app/public/wp-content/themes/resilient-hub-child/`
- **`functions.php`**: Handles asset enqueuing, AJAX callbacks (for filtering, moderation approvals), auth gates protecting dashboard pages, and user redirection logic.
- **`style.css`**: Defines all portal design overrides, page styling, and UI buttons.
- **`header.php` / `footer.php`**: Defines the theme header (containing logo replacement and user dropdown welcome panels) and footer.
- **Template Files**:
  - `single-accord_library.php` & `single-partner_resources.php`: Single templates displaying resource details, related resources loops, and launch/download controls.
  - `template-news-stories.php`: Unified landing/catalog page for news and stories.
  - `template-submit-post.php`: Frontend editor form for submissions.
  - `template-moderation.php`: Dashboard for pending approvals.
  - `template-profile.php`: User GDPR rights page, consent logs, and JSON data exporter.
  - `template-analytics.php` & `template-sitrep-dashboard.php`: Data analytics/reporting views with PDF/PNG download controls.

---

## 4. Key Gotchas & Styling Rules

- **TinyMCE Button Contrast**: In `style.css`, a generic selector `.rp-upload-form button` style makes default TinyMCE editor visual buttons unreadable. Specific visual resets are placed at the bottom of `style.css` to restore visual text toolbar contrast.
- **Card Height Stability**: Grid cards (`.rp-resource-card` and `.rp-card`) use a fixed `min-height: 350px`, along with `-webkit-line-clamp` (clamping titles to 2 lines and description paragraphs to 3 lines) to maintain perfectly consistent grid rows. This prevents jumpy height resizing transitions when catalog filters are toggled.
- **Web Application Launching**: A resource format check `has_term( 'Web Application', 'resource_format' )` is utilized alongside the metadata field `_rp_is_web_app` to identify web applications, outputting a `Launch` button linking either to the extracted HTML index path or falling back to the package download path.

---

## 5. Latest Session Record - Split Opportunity Workflows

- **Date**: June 18, 2026
- **Purpose**: Correct the opportunities workflow so jobs and ITBs use separate public listing pages and separate authorized posting forms.
- **Changed files**:
  - `wp-content/plugins/rp-resource-hub/rp-resource-hub.php`
  - `wp-content/plugins/rp-resource-hub/includes/opportunities.php`
  - `wp-content/themes/resilient-hub-child/header.php`
  - `wp-content/themes/resilient-hub-child/footer.php`
  - `wp-content/themes/resilient-hub-child/style.css`
  - `handoff.md`
- **Implemented**:
  - Resource Hub plugin bumped to `1.10.2`.
  - Opportunities module bumped to `1.0.2`.
  - Added `/job-ads/` with `[rp_opportunities type="job"]`.
  - Added `/invitations-to-bid/` with `[rp_opportunities type="itb"]`.
  - Added `/submit-job-opportunity/` with `[rp_submit_job_opportunity]`.
  - Added `/submit-invitation-to-bid/` with `[rp_submit_itb_opportunity]`.
  - Job posting form now shows job-only fields.
  - ITB posting form now shows procurement-only fields.
  - User dropdown now shows `Submit Job Posting` and/or `Submit ITB Posting` based on role.
  - Removed the old combined `Submit Opportunity` dropdown link.
  - Footer Job Ads and Invitations to Bid links now point to dedicated pages.
  - Existing HR and Procurement dashboards continue to support two-layer review plus actions: status update, internal note, file links, CSV export, and optional successful/unsuccessful email notice.
- **Local verification**:
  - PHP lint passed for the changed PHP files.
  - `/job-ads/`, `/invitations-to-bid/`, `/submit-job-opportunity/`, and `/submit-invitation-to-bid/` returned `200`.
  - New pages were created in the database and version options updated.
  - Temporary HR user saw only the job posting form/link and could not access the ITB posting form.
  - Temporary Procurement user saw only the ITB posting form/link and could not access the job posting form.
  - Temporary dashboard records confirmed Layer 1 and Layer 2 action controls for both HR and Procurement.
  - Temporary test users, posts, applications, and bid submissions were removed.

---

## 6. Latest Session Record - HR and Procurement Roles

- **Date**: June 18, 2026
- **Purpose**: Add explicit department roles for opportunities workflows and confirm access to the related pages.
- **Changed files**:
  - `wp-content/plugins/rp-resource-hub/rp-resource-hub.php`
  - `wp-content/plugins/rp-resource-hub/includes/opportunities.php`
  - `handoff.md`
- **Implemented roles**:
  - `rp_hr_department`, label `ACCORD HR`
    - Capabilities: `read`, `upload_files`, `manage_job_applications`, `submit_job_opportunities`
    - Related pages: `/submit-job-opportunity/`, `/job-applications-dashboard/`
  - `rp_procurement_department`, label `ACCORD Procurement`
    - Capabilities: `read`, `upload_files`, `manage_bid_submissions`, `submit_itb_opportunities`
    - Related pages: `/submit-invitation-to-bid/`, `/bid-submissions-dashboard/`
- **Compatibility kept**:
  - Existing `rp_hr_reviewer` and `rp_procurement_reviewer` roles remain available.
  - Existing reviewer roles also receive the matching submit capability.
  - Administrators receive both new submit capabilities.
- **Local verification**:
  - PHP lint passed for the changed plugin files.
  - WordPress role registry contains both new roles and labels.
  - Plugin options updated:
    - `rp_opportunities_version = 1.0.3`
    - `rp_resource_hub_version = 1.10.3`
  - Temporary `rp_hr_department` user saw only `Submit Job Posting`, could access the job form/dashboard, and was denied the ITB form.
  - Temporary `rp_procurement_department` user saw only `Submit ITB Posting`, could access the ITB form/dashboard, and was denied the job form.
  - Temporary test users were removed.

---

## 7. Latest Session Record - User Management Role Dropdown

- **Date**: June 18, 2026
- **Purpose**: Make the new HR and Procurement roles visible and assignable on the front-end User Management page.
- **Changed files**:
  - `wp-content/themes/resilient-hub-child/template-user-management.php`
  - `wp-content/themes/resilient-hub-child/functions.php`
  - `wp-content/themes/resilient-hub-child/style.css`
  - `handoff.md`
- **What changed**:
  - Added these roles to the User Management filter and role-change dropdowns:
    - `ACCORD HR` (`rp_hr_department`)
    - `ACCORD Procurement` (`rp_procurement_department`)
    - `HR Reviewer` (`rp_hr_reviewer`)
    - `Procurement Reviewer` (`rp_procurement_reviewer`)
  - Added the same roles to the AJAX role-update allowlist.
  - Added role labels for AJAX success messages.
  - Added badge styling for HR and Procurement roles.
  - Bumped child theme version to `1.1.16`.
- **Local verification**:
  - PHP lint passed for `template-user-management.php` and `functions.php`.
  - Temporary admin user loaded `/user-management/`.
  - Confirmed all four HR/Procurement role options appeared in the page HTML.
  - AJAX role update successfully assigned `rp_hr_department` to a temporary target user.
  - Confirmed the saved DB role value was `a:1:{s:16:"rp_hr_department";b:1;}`.
  - Temporary test users were removed.

---

## 8. Latest Session Record - Simplified HR and Procurement Roles

- **Date**: June 18, 2026
- **Purpose**: Remove the HR/Procurement reviewer distinction. Only department roles should exist and be assignable.
- **Changed files**:
  - `wp-content/plugins/rp-resource-hub/rp-resource-hub.php`
  - `wp-content/plugins/rp-resource-hub/includes/opportunities.php`
  - `wp-content/themes/resilient-hub-child/functions.php`
  - `wp-content/themes/resilient-hub-child/template-user-management.php`
  - `wp-content/themes/resilient-hub-child/style.css`
  - `handoff.md`
- **What changed**:
  - Bumped Resource Hub plugin version to `1.10.4`.
  - Bumped Opportunities module version to `1.0.4`.
  - Bumped child theme version to `1.1.17`.
  - Kept only these department roles:
    - `ACCORD HR` (`rp_hr_department`)
    - `ACCORD Procurement` (`rp_procurement_department`)
  - Stopped creating `rp_hr_reviewer` and `rp_procurement_reviewer`.
  - Added migration from old reviewer roles to the matching department roles.
  - Removed old reviewer roles from WordPress after migration.
  - Removed reviewer roles from User Management dropdowns, AJAX allowlist, labels, and badge CSS.
- **Access model**:
  - ACCORD HR can post job ads, access the Job Applications Dashboard, and take action on job applications.
  - ACCORD Procurement can post invitations to bid, access the Bid Submissions Dashboard, and take action on bid submissions.
- **Local verification**:
  - PHP lint passed for changed plugin/theme PHP files.
  - Local role registry contains `rp_hr_department` and `rp_procurement_department`.
  - Local role registry no longer contains `rp_hr_reviewer` or `rp_procurement_reviewer`.
  - User Management shows only ACCORD HR and ACCORD Procurement for department roles.
  - AJAX role assignment succeeded for both ACCORD HR and ACCORD Procurement.
  - ACCORD HR assignment showed job posting access and no ITB posting access.
  - ACCORD Procurement assignment showed ITB posting/dashboard access and no job dashboard access.
  - Temporary test users were removed.

---

## 9. Latest Session Record - Immediate Opportunity Publishing

- **Date**: June 18, 2026
- **Purpose**: HR and Procurement should not need administrator approval when posting opportunities.
- **Changed files**:
  - `wp-content/plugins/rp-resource-hub/rp-resource-hub.php`
  - `wp-content/plugins/rp-resource-hub/includes/opportunities.php`
  - `wp-content/themes/resilient-hub-child/style.css`
  - `handoff.md`
- **What changed**:
  - Bumped Resource Hub plugin version to `1.10.5`.
  - Bumped Opportunities module version to `1.0.5`.
  - Bumped child theme version to `1.1.18`.
  - Front-end job/ITB posting submissions now create `rp_opportunity` posts with `publish` status instead of `pending`.
  - Submit page wording now says postings are created/published directly.
  - Submit button text changed from `Submit for Review` to `Publish Posting`.
  - Success notice now says `Your opportunity was published.`
- **Local verification**:
  - PHP lint passed for changed plugin files.
  - Local site returned `200`.
  - Temporary ACCORD HR user submitted a job posting through `/submit-job-opportunity/`; DB confirmed `post_status = publish`.
  - Temporary ACCORD Procurement user submitted an ITB through `/submit-invitation-to-bid/`; DB confirmed `post_status = publish`.
  - Version options updated:
    - `rp_opportunities_version = 1.0.5`
    - `rp_resource_hub_version = 1.10.5`
  - Temporary test posts and users were removed.

---

## 10. Latest Session Record - Publish Stuck Pending Opportunities

- **Date**: June 18, 2026
- **Purpose**: Fix legacy opportunity postings that were stuck in `pending` status after HR/Procurement approval was removed.
- **Changed files**:
  - `wp-content/plugins/rp-resource-hub/rp-resource-hub.php`
  - `wp-content/plugins/rp-resource-hub/includes/opportunities.php`
  - `handoff.md`
- **What changed**:
  - Bumped Resource Hub plugin version to `1.10.6`.
  - Bumped Opportunities module version to `1.0.6`.
  - Added `rp_opportunities_publish_pending_posts()`.
  - Activation and upgrade routines now publish any `rp_opportunity` post still stuck as `pending`.
- **Why**:
  - The HR/Procurement dashboards included pending opportunities, so staff could see them there.
  - The public `/opportunities/`, `/job-ads/`, and `/invitations-to-bid/` pages only list published postings.
  - The general Moderation Dashboard does not include `rp_opportunity`, because opportunities no longer require admin approval.
- **Local verification**:
  - PHP lint passed for changed plugin files.
  - Created temporary pending `rp_opportunity` job post.
  - Loaded `/job-ads/` to trigger upgrade.
  - Confirmed the temporary job appeared on `/job-ads/`.
  - Confirmed DB changed the post from `pending` to `publish`.
  - Version options updated:
    - `rp_opportunities_version = 1.0.6`
    - `rp_resource_hub_version = 1.10.6`
  - Removed temporary test post and revision rows.

---

## 11. Latest Session Record - Editable Opportunity Postings

- **Date**: June 18, 2026
- **Purpose**: Let HR and Procurement manage posting details from their dashboards and refine the Submit Job Posting form.
- **Changed files**:
  - `wp-content/plugins/rp-resource-hub/rp-resource-hub.php`
  - `wp-content/plugins/rp-resource-hub/includes/opportunities.php`
  - `wp-content/themes/resilient-hub-child/style.css`
  - `handoff.md`
- **What changed**:
  - Bumped Resource Hub plugin version to `1.10.7`.
  - Bumped Opportunities module version to `1.0.7`.
  - Bumped child theme version to `1.1.19`.
  - Job and ITB dashboard posting names now link to the public posting page.
  - Dashboard rows now include an `Edit Job Posting` or `Edit ITB Posting` action.
  - Added front-end edit forms for authorized HR/Procurement users.
  - Edit forms can update title, deadline, description, scope/deliverables, documents, and posting status override.
  - Posting status is controlled through the edit form:
    - `Automatic by deadline`
    - `Force Open`
    - `Force Closed`
  - Submit Job Posting form updates:
    - Removed Employment Type.
    - Removed Contact Email.
    - Deadline is now a date picker and stores the selected date as `23:59`.
    - Consultant duration only appears when Hiring Type is `Consultant`.
  - Public job details no longer show Employment Type.
- **Local verification**:
  - PHP lint passed for changed plugin files.
  - Temporary ACCORD HR user confirmed Submit Job Posting form changes.
  - Temporary HR job post stored deadline as `YYYY-MM-DD 23:59`, with empty employment/contact meta.
  - Job dashboard showed clickable posting title and edit action.
  - Job edit form saved title, deadline, consultant duration, and `Force Closed` status.
  - Temporary ACCORD Procurement user confirmed ITB dashboard clickable title and edit action.
  - Temporary test users and postings were removed.

---

## 12. Latest Session Record - Consultant Portfolio Toggle Visibility

- **Date**: June 18, 2026
- **Purpose**: Make `Require portfolio/proof of work for consultant applications` behave like `Duration of engagement`.
- **Changed files**:
  - `wp-content/plugins/rp-resource-hub/rp-resource-hub.php`
  - `wp-content/plugins/rp-resource-hub/includes/opportunities.php`
  - `handoff.md`
- **What changed**:
  - Bumped Resource Hub plugin version to `1.10.8`.
  - Bumped Opportunities module version to `1.0.8`.
  - Moved the portfolio/proof-of-work checkbox into the consultant-only section on Submit Job Posting.
  - Moved the same checkbox into the consultant-only section on Edit Job Posting.
- **Local verification**:
  - PHP lint passed for changed plugin files.
  - Temporary ACCORD HR user loaded `/submit-job-opportunity/`.
  - Confirmed `Duration of engagement` and `Require portfolio/proof of work` appear in the same consultant-only region.
  - Confirmed the portfolio label appears only once.
  - Temporary test user was removed.

---

## 13. Latest Session Record - Opportunity Posting Pages and Submission Dashboards

- **Date**: June 18, 2026
- **Purpose**: Polish public job/ITB posting pages, improve HR/Procurement dashboard review workflows, and fix ITB attachment upload failures.
- **Changed files**:
  - `wp-content/plugins/rp-resource-hub/rp-resource-hub.php`
  - `wp-content/plugins/rp-resource-hub/includes/opportunities.php`
  - `wp-content/themes/resilient-hub-child/style.css`
  - `wp-content/themes/resilient-hub-child/single-rp_opportunity.php`
  - `handoff.md`
- **What changed**:
  - Bumped Resource Hub plugin version to `1.10.9`.
  - Bumped Opportunities module version to `1.0.9`.
  - Bumped child theme version to `1.1.20`.
  - Added a dedicated single template for opportunity postings.
  - Public job and ITB posting pages no longer show author metadata or previous/next story navigation.
  - Posting body now appears in the same white boxed layout style as the generated job/ITB details and application areas.
  - Dashboard row action buttons are smaller and more consistent for `View Applications`, `View Submissions`, `Edit Job Posting`, and `Edit ITB Posting`.
  - Front-end edit forms now show existing Terms of Reference / posting document links when files are already attached.
  - `/submit-invitation-to-bid/` no longer asks for contact email, bid opening date, or clarification period.
  - ITB public details no longer display bid opening or clarification period fields.
  - Submission second-layer dashboards now include decoded applicant/supplier details and answers in the table.
  - Added submission filters for status, name/company, contact person where relevant, email, phone, and all stored form-answer fields.
  - Replaced the status-email checkbox with a manual `Send Status Email` button.
  - Status emails now use different body text based on the current status while still CC'ing the responsible department.
  - ITB submission upload handling now uses `wp_handle_upload()` plus explicit attachment creation, avoiding the previous critical error path from `media_handle_upload()`.
- **Local verification**:
  - PHP lint passed for changed plugin files and the new theme template.
  - Created temporary job and ITB postings for testing, then removed them.
  - Confirmed single posting pages do not contain author or previous/next story text.
  - Confirmed single posting pages render the new `rp-opportunity-single-content` white content box.
  - Confirmed `/submit-invitation-to-bid/` no longer renders contact email, bid opening date, or clarification period.
  - Submitted a temporary ITB with quotation, business permit, BIR 2303, and receipt sample files.
  - Confirmed the ITB submission completed without a critical error and stored attachment IDs.
  - Confirmed bid dashboard second layer shows supplier details, decoded message, filters, and `Send Status Email` as a button.
  - Confirmed job dashboard second layer includes filters for job application fields.
  - Confirmed front-end edit form shows the existing attached posting document link.
  - Confirmed `wp-content/debug.log` was not created during tests.
  - Removed temporary admin user, posts, submission, attachments, uploaded test files, and cookies.
