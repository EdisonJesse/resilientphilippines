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
