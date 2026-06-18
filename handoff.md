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
