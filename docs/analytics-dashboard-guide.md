# Resilient Philippines Analytics Dashboard

## Purpose

The analytics dashboard is a self-hosted WordPress reporting tool for understanding how visitors discover, browse, search, and download resources from Resilient Philippines. It is available at `/analytics-dashboard/` to administrators and users who can publish posts.

The dashboard is intended for content planning, communications reporting, resource-library improvement, and basic security auditing. It is not a replacement for a full product-analytics platform or server security logs.

## Access and permissions

- Administrators with `manage_options` can view and export analytics.
- Editors and other users with `publish_posts` can view and export analytics.
- Logged-out users are redirected to the portal entry page.
- Other authenticated users are redirected to the homepage.
- CSV exports require the same capability check and a valid WordPress nonce.

## Metric definitions

### Unique Sessions

A session is a period of activity associated with the first-party `rp_analytics_session` cookie. The session has a fixed 30-minute lifetime. Analytics cookies are issued only when needed instead of being rewritten on every page response.

Older records created before session tracking use a legacy IP-based fallback. The Data Quality section reports how much of the selected period has a real session ID.

### Page Views

Each eligible frontend page request recorded by the Resource Hub plugin. Staff users who can publish posts, WordPress administration requests, AJAX requests, CLI requests, and visitors who declined analytics are excluded.

### Resource Downloads

Each tracked resource download handled by the Resource Hub plugin. Repeated downloads are counted as separate events.

### Human and Bot traffic

Human and bot classification uses user-agent markers. Human means the request was not identified as automated. It does not mean “organic search.” Acquisition channels are reported separately.

Automated clients can hide or falsify their user agent, so this classification is directional rather than authoritative.

### New and Returning

A visitor is new when no valid `rp_analytics_visitor` cookie exists. The visitor cookie lasts one year. Clearing cookies, changing browsers, or using another device creates a new visitor identity.

### View-to-Download Rate

`downloads / page views × 100` for each resource in the selected period. It is an event rate, not a unique-user conversion rate, and can exceed 100% when visitors download repeatedly or reach a download without a tracked detail-page view.

## Using the dashboard

### Date range

Choose 7, 30, 90 days, or All Time. KPI cards, charts, tables, search reports, and CSV output use the selected range.

For finite date ranges, each KPI shows the percentage change against the immediately preceding period of equal length. For example, Last 30 Days is compared with the 30 days before it. All Time has no period comparison.

### Traffic filter

- **All Traffic** includes human and detected bot activity.
- **Human Traffic** excludes detected automation.
- **Inorganic / Bot** includes detected automation and requests with an empty user agent.

Use Human Traffic for program and communications reporting. Use Bot traffic to assess crawler load and data contamination.

### Search and audit pagination

The audit search matches resource title, account name/email, or IP address. The table displays 25 events per page and preserves the active date, traffic, and search filters.

### Exports

- **CSV** exports every download event matching the active filters, not only the current audit page. It includes country, device, acquisition, campaign, session ID, timestamp, IP, and user agent.
- **PDF** captures the visible dashboard report.
- **PNG** captures the visible dashboard report as an image.

CSV files contain personal and technical identifiers. Store them only in approved locations and delete them when no longer required.

## Dashboard sections

### KPI cards

Shows sessions, views, and downloads with human/bot subtotals and previous-period change.

### Activity timeline

Daily sessions, views, and downloads. All Time uses a 90-day timeline while the other reports and totals remain all-time.

### Top downloaded and viewed content

Use these tables to identify high-demand resources and pages. High views with low downloads may indicate unclear calls to action, weak relevance, or a resource that is useful without downloading.

### Sessions by country

Country uses a trusted request header, checked in this order:

1. `CF-IPCountry` exposed to PHP as `HTTP_CF_IPCOUNTRY`
2. `X-Country-Code` exposed as `HTTP_X_COUNTRY_CODE`
3. `GEOIP_COUNTRY_CODE`
4. `ZZ` / Unknown when no supported header exists

No external geolocation API is called during a visit. This avoids latency and disclosure of visitor IPs to another service.

If the production site uses Cloudflare proxying, country data should populate automatically. If it does not, configure the web server or CDN to provide one of the supported headers. Prevent public clients from spoofing a custom country header by overwriting it at the trusted proxy.

Historical rows are not automatically geolocated and normally remain Unknown.

### Acquisition sources

UTM parameters take priority:

- `utm_source`
- `utm_medium`
- `utm_campaign`

Without UTM parameters, the tracker classifies the referrer as direct, organic search, social, referral, or internal. Acquisition values persist for the 30-minute session so a later download retains the session’s source.

Example campaign URL:

```text
https://resilientphilippines.com/resource-hub/?utm_source=facebook&utm_medium=social&utm_campaign=preparedness_month
```

Use lowercase, stable names with underscores. Maintain a shared campaign naming sheet to prevent variants such as `Facebook`, `facebook`, and `fb` from splitting reports.

### Device report

Devices are classified as desktop, mobile, tablet, bot, or unknown using the user agent. This is suitable for layout and content-format decisions but is not device fingerprinting.

### New versus returning sessions

Use this report to distinguish discovery from repeat use. Returning-session growth is a useful signal that practitioners are repeatedly relying on the portal.

### Resource performance

Compares page views and downloads for each resource. For finite date ranges, Download Trend compares downloads with the preceding equal-length period. Review high-view/low-rate resources for:

- unclear download buttons;
- missing or weak descriptions;
- access restrictions;
- broken files;
- content that does not match the traffic source.

### Resource search terms

Search events are recorded for resource-catalog searches containing at least two characters after an 800ms input pause or explicit form submission. Taxonomy changes and pagination do not create search events. Duplicate searches with the same term in the same session are suppressed for 10 minutes.

Prioritize terms with frequent zero-result searches. They identify missing content, terminology mismatches, spelling variants, or taxonomy improvements.

### Data quality

- **Country coverage**: percentage of view events with a recognized country code.
- **Session-ID coverage**: percentage with the new first-party session identifier.
- **Bot share**: percentage of views classified as automated.
- **Missing user agent**: requests that cannot be classified reliably.

Low coverage immediately after deployment is expected because existing rows do not contain the new metadata.

## Tracking architecture

The Resource Hub plugin owns collection and schema migration. The child theme owns dashboard presentation and CSV export.

### Database tables

- `wp_rp_analytics_views`: page-view events and acquisition/audience metadata.
- `wp_rp_analytics_downloads`: download events and acquisition/audience metadata.
- `wp_rp_analytics_searches`: resource search term, result count, session, country, and device.

The WordPress table prefix may differ from `wp_`.

### Event fields

View and download events include:

- post and optional WordPress user ID;
- IP address and user agent;
- first-party visitor and session IDs;
- new-visitor flag;
- country code and device type;
- referrer, source, medium, and campaign;
- WordPress-local timestamp.

Search events intentionally exclude IP address and user account details.

## Privacy and governance

- Analytics stops when `rp_cookie_consent=declined`.
- First-party analytics cookies are HTTP-only, SameSite=Lax, and Secure on HTTPS.
- Staff activity is excluded when the account can publish posts.
- IP addresses and user agents remain personal/technical identifiers and require an organizational retention decision.
- Define who may access CSV exports and where exports may be stored.
- Review the public privacy and cookie notices after changing analytics behavior.
- Establish an approved retention period before automating deletion or IP anonymization. The software does not currently delete historical analytics automatically because the appropriate retention period requires ACCORD policy approval.

## Recommended reporting routine

### Weekly

1. Select Last 7 Days and Human Traffic.
2. Review KPI changes and unusual declines.
3. Check zero-result searches.
4. Check top resources and download rates.
5. Review bot share and country coverage.

### Monthly

1. Select Last 30 Days and Human Traffic.
2. Export CSV for approved internal reporting when needed.
3. Compare campaign sources and returning sessions.
4. Identify content to promote, revise, archive, or create.
5. Record decisions alongside the dashboard figures; metrics without decisions have limited value.

## Troubleshooting

### Country is mostly Unknown

Confirm the live CDN or server supplies one of the supported country headers to PHP. Local development normally reports Unknown.

### Sources are mostly Direct

Use tagged campaign links, verify redirects preserve UTM parameters, and ensure privacy/security middleware does not remove the referrer header.

### New/returning and sessions show limited coverage

Old events have no session ID. Coverage rises as new activity is recorded after version 1.12.0 deployment.

### Search report is empty

Search events begin only after version 1.12.0. Test a two-character-or-longer query in the Resource Hub while logged out or using an account without publishing capability.

### Dashboard errors after deployment

Visit any WordPress page as an administrator to trigger the plugin upgrade, then verify the plugin option `rp_resource_hub_version` is `1.12.0` and that all three analytics tables contain the new columns.

### Figures differ from external analytics

Differences are expected due to consent choices, staff exclusion, bot rules, ad blockers, session definitions, time zones, caching, and external tools’ processing rules. Compare definitions before comparing totals.

## Known limitations

- Bot detection and device detection are user-agent heuristics.
- Country depends on trusted infrastructure headers.
- No Philippine province/region is inferred from IP because subnational IP geolocation is unreliable.
- Visitor identity is browser-specific and resets when cookies are cleared.
- Search-to-download attribution is not yet a dedicated funnel; search demand and resource downloads are reported separately.
- Tracking opt-outs are not counted because declined visitors are intentionally not logged.
- The dashboard uses WordPress database queries and is designed for the portal’s current scale. Large event volumes may eventually require rollup tables or a dedicated analytics store.

## Release verification checklist

1. Confirm plugin version `1.12.0` is active.
2. Confirm the view, download, and search tables exist and contain the new columns.
3. Visit the site logged out and verify a view receives visitor/session metadata.
4. Test one tagged campaign URL.
5. Test a resource search and download.
6. Confirm country behavior on production infrastructure.
7. Test All, Human, and Bot filters.
8. Test date comparisons, audit pagination, and CSV export.
9. Review mobile dashboard layout.
10. Confirm privacy and retention policies with the responsible ACCORD owner.
