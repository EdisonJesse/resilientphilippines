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
