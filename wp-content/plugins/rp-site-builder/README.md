# Resilient Philippines Site Builder

Custom WordPress plugin prototype for editing page layouts, reusable components, and optional header/footer settings from the WordPress dashboard.

## Current Status

This plugin is a developed prototype. It features a custom YOOtheme-style page builder with a premium dark mode user interface, a live WYSIWYG editor inside the preview iframe, visual layout structure, responsive canvas outlines, inline text editing directly on the frontend page layout, page content import mapping, reusable site components with recursion prevention, and native drag-and-drop section reordering. The plugin is fully tracked in Git.

## Plugin Files

- `rp-site-builder.php`  
  Main plugin file. Registers admin menus, custom post type, settings, page meta, AJAX save endpoint, shortcodes, and front-end rendering.

- `assets/admin.js`  
  Classic page editor metabox controls for editing builder JSON through form fields.

- `assets/admin.css`  
  Styles for the classic page editor metabox and basic Site Builder dashboard.

- `assets/visual-builder.js`  
  Custom Visual Builder interface. Handles canvas rendering, section selection, inline edits, import logic, templates, device preview toggles, media picker, and AJAX saving.

- `assets/visual-builder.css`  
  WordPress admin styles for the Visual Builder interface.

- `assets/frontend.css`  
  Front-end section styles used by pages rendered through the builder.

## Dashboard Menus

After activation, the plugin adds:

- `Site Builder`  
  Lists recent pages and lets admins create draft builder pages.

- `Site Builder > Visual Builder`  
  Opens a custom visual editing canvas for a selected page.

- `Site Builder > Components`  
  Uses the custom `rpsb_component` post type for reusable content components.

- `Site Builder > Header & Footer`  
  Provides opt-in custom header/footer settings.

## Stored Data

Builder layouts are stored on WordPress pages as post meta:

- `_rpsb_enabled`  
  `1` when the builder should replace normal page content.

- `_rpsb_layout`  
  JSON array of section objects.

When a layout is saved from Visual Builder, the plugin enables `_rpsb_enabled` and replaces the page content with:

```html
<!-- Built with Resilient Philippines Site Builder -->
```

## Supported Section Types

Current section types:

- `hero`
- `text`
- `image`
- `image_text`
- `cards`
- `cta`
- `shortcode`
- `html`
- `component`

## Visual Builder Capabilities

- **Premium Dark Mode UI**: A dark themed sidebar, topbar, and inspector designed like YOOtheme Pro.
- **Drag-and-Drop Reordering**: Drag and drop sections in the layout structure panel to instantly sort them.
- **Canvas Hover & Select Overlays**: Sections show custom border outlines and floating type labels (e.g. "Hero", "Cards") on hover.
- **Click-to-Edit Tab Focus**: Clicking any section on the canvas selects it and automatically shifts the sidebar to the "Edit" settings panel.
- **Full Live WYSIWYG Editing**: Switch to "Live Page" mode and edit text, headers, and body fields inline directly inside the live page preview iframe, with real-time state syncing to the builder and save modules.
- Select a page to edit.
- Add sections from the sidebar.
- Insert basic landing/content templates.
- Select sections on the canvas.
- Edit text inline for supported fields.
- Edit section settings in the inspector.
- Reorder, duplicate, and remove sections.
- Choose images through the WordPress media picker.
- Toggle desktop/tablet/mobile preview widths.
- Toggle between Builder canvas and Live Page iframe.
- Import existing page content into builder sections.
- Save layout by AJAX.
- Prevent overwriting raw page content: prompts the user with a confirmation warning before writing the Site Builder layout if the page has existing custom Gutenberg/Classic text.

## Import Behavior

The importer tries to convert existing WordPress page content into builder sections.

It currently handles:

- Large heading/intro as a `hero`.
- Paragraphs as `text` sections.
- Standalone images as `image` sections.
- Inline CSS grids as `cards`.
- Unsupported leftover HTML as an `html` section.

This is best-effort only. It cannot reliably convert arbitrary theme templates, PHP-rendered sections, shortcodes, or complex inline HTML into clean editable components without explicit mappings.

## Header/Footer Behavior

Header and footer replacement is opt-in.

Settings are stored in the `rpsb_options` option. If enabled:

- The plugin hides the theme header/footer with inline CSS.
- The plugin renders its own simplified header/footer.

By default, activation does not replace the live site header or footer.

## Known Limitations

- The Visual Builder is not a full page builder framework.
- Existing theme templates are not automatically componentized.
- Importing old pages is lossy and structure-dependent.
- Layout controls are limited compared to mature builders.
- No drag-and-drop sorting yet.
- No revision UI beyond normal WordPress post revisions/meta history.
- No role/capability model beyond standard page editing permissions.
- No reusable global section library beyond `rpsb_component`.
- No responsive per-section overrides.
- No frontend inline editing.
- No dedicated block schema migration/versioning.

## Important Operational Note

`duplicator` was deactivated during troubleshooting because it caused `/wp-admin/` to hang in this local environment. Re-enabling it may reintroduce admin timeouts.

## Recommended Next Steps

For production, choose one direction:

1. Build on WordPress-native blocks/Gutenberg and create custom ACCORD blocks.
2. Use ACF Blocks or a mature builder foundation, then add site-specific components.
3. Continue this custom builder only if the scope is narrowed to a fixed set of ACCORD page sections.

Recommended improvements if continuing this plugin:

- Define a stable section schema and migration strategy.
- Replace string-based `cards` data with structured card arrays.
- Add dedicated import mappings for each active page template.
- Add preview parity tests for front-end and admin canvas.
- Add nonce/capability tests for AJAX endpoints.
- Add export/import for reusable templates.

## Validation Performed

During development, the following checks were run repeatedly:

- PHP syntax check on `rp-site-builder.php`.
- Node syntax check on admin JavaScript files.
- WP-CLI plugin status check.
- Basic homepage request check.
- Component recursion tests to verify protection blocks memory limits.
- Save validation and prompt verification when layout is enabled over raw content.
- Native drag-and-drop ordering verification in the sidebar structure panel.
- Dark mode theme color and styling verification.
- Bi-directional iframe synchronization and WYSIWYG inline editing verification.

