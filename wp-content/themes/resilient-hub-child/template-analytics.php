<?php
/**
 * Template Name: Analytics Dashboard
 *
 * A front-end analytics panel for administrators and editors
 * to track page views, unique visits, and detailed download metrics.
 *
 * @package ResilientHub
 */

// Gate check: administrators and editors only
if ( ! is_user_logged_in() ) {
	wp_safe_redirect( add_query_arg( 'redirect_to', esc_url( home_url( '/analytics-dashboard/' ) ), home_url( '/portal-entry/' ) ) );
	exit;
}

if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'publish_posts' ) ) {
	wp_safe_redirect( home_url( '/' ) );
	exit;
}

global $wpdb;

// Time range parameter
$days = isset( $_GET['rp_days'] ) ? absint( $_GET['rp_days'] ) : 30;

// Base date constraints
$views_where = "WHERE 1=1";
$downloads_where = "WHERE 1=1";
if ( $days > 0 ) {
	$views_where .= $wpdb->prepare( " AND created_at >= DATE_SUB(NOW(), INTERVAL %d DAY)", $days );
	$downloads_where .= $wpdb->prepare( " AND d.created_at >= DATE_SUB(NOW(), INTERVAL %d DAY)", $days );
}

// -------------------------------------------------------------
// 1. KPI Counts
// -------------------------------------------------------------
$unique_visits = $wpdb->get_var( "SELECT COUNT(DISTINCT ip_address) FROM {$wpdb->prefix}rp_analytics_views $views_where" );
$total_views   = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}rp_analytics_views $views_where" );
$total_downloads = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}rp_analytics_downloads d $downloads_where" );

// -------------------------------------------------------------
// 2. Chart Timeline Data
// -------------------------------------------------------------
$chart_days = $days > 0 ? $days : 90; // Default to 90 days for all-time timeline
$views_by_day = $wpdb->get_results( $wpdb->prepare( "
	SELECT DATE(created_at) as date, COUNT(*) as count, COUNT(DISTINCT ip_address) as unique_ips
	FROM {$wpdb->prefix}rp_analytics_views
	WHERE created_at >= DATE_SUB(NOW(), INTERVAL %d DAY)
	GROUP BY DATE(created_at)
	ORDER BY DATE(created_at) ASC
", $chart_days ) );

$downloads_by_day = $wpdb->get_results( $wpdb->prepare( "
	SELECT DATE(created_at) as date, COUNT(*) as count
	FROM {$wpdb->prefix}rp_analytics_downloads
	WHERE created_at >= DATE_SUB(NOW(), INTERVAL %d DAY)
	GROUP BY DATE(created_at)
	ORDER BY DATE(created_at) ASC
", $chart_days ) );

// Prepare datasets for JavaScript
$labels = array();
$views_series = array();
$visits_series = array();
$downloads_series = array();

for ( $i = $chart_days - 1; $i >= 0; $i-- ) {
	$date_str = date( 'Y-m-d', strtotime( "-$i days" ) );
	$labels[] = date( 'M d', strtotime( $date_str ) );
	
	// Map views & visits
	$view_count = 0;
	$visit_count = 0;
	foreach ( $views_by_day as $v ) {
		if ( $v->date === $date_str ) {
			$view_count = (int) $v->count;
			$visit_count = (int) $v->unique_ips;
			break;
		}
	}
	$views_series[] = $view_count;
	$visits_series[] = $visit_count;

	// Map downloads
	$download_count = 0;
	foreach ( $downloads_by_day as $d ) {
		if ( $d->date === $date_str ) {
			$download_count = (int) $d->count;
			break;
		}
	}
	$downloads_series[] = $download_count;
}

// -------------------------------------------------------------
// 3. Top Content & Resources Tables
// -------------------------------------------------------------
$popular_downloads = $wpdb->get_results( "
	SELECT d.post_id, COUNT(*) as count, p.post_title, p.post_type
	FROM {$wpdb->prefix}rp_analytics_downloads d
	LEFT JOIN {$wpdb->posts} p ON d.post_id = p.ID
	" . str_replace( 'd.', '', $downloads_where ) . "
	GROUP BY d.post_id
	ORDER BY count DESC
	LIMIT 10
" );

$popular_pages = $wpdb->get_results( "
	SELECT v.post_id, COUNT(*) as count, p.post_title, p.post_type
	FROM {$wpdb->prefix}rp_analytics_views v
	LEFT JOIN {$wpdb->posts} p ON v.post_id = p.ID
	" . str_replace( 'v.', '', $views_where ) . "
	GROUP BY v.post_id
	ORDER BY count DESC
	LIMIT 10
" );

// -------------------------------------------------------------
// 4. Download Audit Log Query
// -------------------------------------------------------------
$search_log = isset( $_GET['rp_search_log'] ) ? sanitize_text_field( wp_unslash( $_GET['rp_search_log'] ) ) : '';
$log_where = $downloads_where;

if ( $search_log ) {
	// Search in user display name, user email, post title or IP
	$search_users = get_users( array(
		'search'         => '*' . $search_log . '*',
		'search_columns' => array( 'user_login', 'user_email', 'display_name' ),
		'fields'         => 'ID',
	) );
	
	$user_ids_in = ! empty( $search_users ) ? implode( ',', array_map( 'absint', $search_users ) ) : '0';
	
	$log_where .= $wpdb->prepare( "
		AND (
			d.ip_address LIKE %s 
			OR p.post_title LIKE %s
			OR d.user_id IN ($user_ids_in)
		)
	", '%' . $wpdb->esc_like( $search_log ) . '%', '%' . $wpdb->esc_like( $search_log ) . '%' );
}

$download_logs = $wpdb->get_results( "
	SELECT d.id, d.post_id, d.user_id, d.ip_address, d.user_agent, d.created_at, p.post_title, p.post_type
	FROM {$wpdb->prefix}rp_analytics_downloads d
	LEFT JOIN {$wpdb->posts} p ON d.post_id = p.ID
	$log_where
	ORDER BY d.created_at DESC
	LIMIT 100
" );

get_header();
?>

<style>
/* Glassmorphic Analytics Dashboard Styling */
@keyframes spin {
	0% { transform: rotate(0deg); }
	100% { transform: rotate(360deg); }
}
.spin {
	display: inline-block;
	animation: spin 1s linear infinite;
}
/* Print/Export PDF layout overrides */
.rp-exporting .rp-analytics-grid {
	grid-template-columns: 1fr 1fr 1fr !important;
}
.rp-exporting .rp-analytics-tables-row {
	display: block !important;
}
.rp-exporting .rp-analytics-table-card {
	width: 100% !important;
	margin-bottom: 30px !important;
	page-break-inside: avoid !important;
	break-inside: avoid !important;
}
.rp-exporting .rp-chart-container,
.rp-exporting .rp-analytics-card,
.rp-exporting tr {
	page-break-inside: avoid !important;
	break-inside: avoid !important;
}
.rp-analytics-grid {
	display: grid;
	grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
	gap: 24px;
	margin-bottom: 32px;
}
.rp-analytics-card {
	background: #ffffff;
	border: 1px solid #e5e7eb;
	border-radius: 12px;
	padding: 24px;
	box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
	transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.rp-analytics-card:hover {
	transform: translateY(-2px);
	box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.04);
}
.rp-card-header {
	display: flex;
	justify-content: space-between;
	align-items: center;
	margin-bottom: 12px;
}
.rp-card-title {
	font-size: 14px;
	font-weight: 600;
	color: #6b7280;
	text-transform: uppercase;
	letter-spacing: 0.05em;
}
.rp-card-icon {
	width: 36px;
	height: 36px;
	border-radius: 8px;
	display: flex;
	align-items: center;
	justify-content: center;
}
.rp-icon-visits { background: #fffbeb; color: #d97706; }
.rp-icon-views { background: #eff6ff; color: #2563eb; }
.rp-icon-downloads { background: #f0fdf4; color: #16a34a; }

.rp-card-value {
	font-size: 32px;
	font-weight: 700;
	color: #111827;
}

.rp-chart-container {
	background: #ffffff;
	border: 1px solid #e5e7eb;
	border-radius: 12px;
	padding: 24px;
	box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
	margin-bottom: 32px;
}
.rp-chart-header {
	display: flex;
	justify-content: space-between;
	align-items: center;
	margin-bottom: 24px;
	flex-wrap: wrap;
	gap: 16px;
}
.rp-chart-title {
	font-size: 18px;
	font-weight: 700;
	color: #1f2937;
	margin: 0;
}

.rp-analytics-tables-row {
	display: grid;
	grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
	gap: 24px;
	margin-bottom: 32px;
}

.rp-analytics-table-card {
	background: #ffffff;
	border: 1px solid #e5e7eb;
	border-radius: 12px;
	padding: 24px;
	box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
}

.rp-analytics-table-title {
	font-size: 16px;
	font-weight: 700;
	color: #1f2937;
	margin: 0 0 16px 0;
	border-bottom: 1px solid #f3f4f6;
	padding-bottom: 12px;
}

.rp-analytics-controls-bar {
	display: flex;
	justify-content: space-between;
	align-items: center;
	margin-bottom: 24px;
	background: #f9fafb;
	padding: 16px 24px;
	border-radius: 12px;
	border: 1px solid #e5e7eb;
	flex-wrap: wrap;
	gap: 16px;
}

.rp-analytics-search-form {
	display: flex;
	gap: 8px;
	flex-grow: 1;
	max-width: 450px;
}

.rp-analytics-search-form input {
	border: 1px solid #d1d5db;
	border-radius: 6px;
	padding: 8px 12px;
	font-size: 14px;
	width: 100%;
	outline: none;
}

.rp-analytics-search-form input:focus {
	border-color: #2563eb;
	box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
}

.rp-log-badge {
	font-size: 10px;
	font-weight: 600;
	padding: 2px 6px;
	border-radius: 4px;
	text-transform: uppercase;
}
.rp-log-badge-accord { background: #f0fdf4; color: #16a34a; }
.rp-log-badge-partner { background: #eff6ff; color: #2563eb; }
.rp-log-badge-sitrep { background: #fffbeb; color: #d97706; }
.rp-log-badge-page { background: #f3f4f6; color: #4b5563; }

.rp-log-agent {
	max-width: 180px;
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
	display: block;
	font-size: 12px;
	color: #6b7280;
}

@media (max-width: 768px) {
	.rp-analytics-tables-row {
		grid-template-columns: 1fr;
	}
	.rp-analytics-controls-bar {
		flex-direction: column;
		align-items: stretch;
	}
	.rp-analytics-search-form {
		max-width: 100%;
	}
}
</style>

<main id="primary" class="rp-moderation-dashboard-main">
	<section class="rp-dashboard-hero">
		<div class="rp-page-shell">
			<p class="rp-eyebrow"><?php esc_html_e( 'Admin Panel', 'resilient-hub' ); ?></p>
			<h1 class="rp-page-title"><?php esc_html_e( 'Analytics Dashboard', 'resilient-hub' ); ?></h1>
		</div>
	</section>
	
	<div class="rp-dashboard-body">
		<div class="rp-page-shell">
			
			<!-- Controls & Filter Toolbar -->
			<div class="rp-analytics-controls-bar" data-html2canvas-ignore="true">
				<form method="get" class="rp-analytics-search-form">
					<input type="hidden" name="rp_days" value="<?php echo esc_attr( $days ); ?>">
					<input type="search" name="rp_search_log" value="<?php echo esc_attr( $search_log ); ?>" placeholder="<?php esc_attr_e( 'Search download logs by resource title, user name, email, or IP...', 'resilient-hub' ); ?>">
					<button class="rp-button" type="submit"><?php esc_html_e( 'Search', 'resilient-hub' ); ?></button>
				</form>
				
				<div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
					<form method="get" style="display: flex; align-items: center; gap: 8px; margin: 0;">
						<?php if ( $search_log ) : ?>
							<input type="hidden" name="rp_search_log" value="<?php echo esc_attr( $search_log ); ?>">
						<?php endif; ?>
						<label for="rp_days" style="font-size: 14px; font-weight: 600; color: #4b5563;"><?php esc_html_e( 'Range:', 'resilient-hub' ); ?></label>
						<select id="rp_days" name="rp_days" onchange="this.form.submit()" style="border: 1px solid #d1d5db; border-radius: 6px; padding: 8px 12px; font-size: 14px; background: #fff; min-width: 180px;">
							<option value="7" <?php selected( $days, 7 ); ?>><?php esc_html_e( 'Last 7 Days', 'resilient-hub' ); ?></option>
							<option value="30" <?php selected( $days, 30 ); ?>><?php esc_html_e( 'Last 30 Days', 'resilient-hub' ); ?></option>
							<option value="90" <?php selected( $days, 90 ); ?>><?php esc_html_e( 'Last 90 Days', 'resilient-hub' ); ?></option>
							<option value="0" <?php selected( $days, 0 ); ?>><?php esc_html_e( 'All Time', 'resilient-hub' ); ?></option>
						</select>
					</form>

					<div style="display: flex; gap: 8px;">
						<button id="rp-export-pdf" class="rp-button" style="background: #ef4444; color: #fff; display: inline-flex; align-items: center; gap: 6px; font-weight: 600; padding: 8px 16px; border-radius: 6px; border: none; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#dc2626'" onmouseout="this.style.background='#ef4444'">
							<span class="dashicons dashicons-pdf" style="font-size: 18px; width: 18px; height: 18px; line-height: 1; margin-top: 3px;"></span> <?php esc_html_e( 'Export PDF', 'resilient-hub' ); ?>
						</button>
						<button id="rp-export-png" class="rp-button" style="background: #2563eb; color: #fff; display: inline-flex; align-items: center; gap: 6px; font-weight: 600; padding: 8px 16px; border-radius: 6px; border: none; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#1d4ed8'" onmouseout="this.style.background='#2563eb'">
							<span class="dashicons dashicons-format-image" style="font-size: 18px; width: 18px; height: 18px; line-height: 1; margin-top: 3px;"></span> <?php esc_html_e( 'Export PNG', 'resilient-hub' ); ?>
						</button>
					</div>
				</div>
			</div>

			<!-- Summary Cards Grid -->
			<div class="rp-analytics-grid">
				<!-- Card 1: Unique Visits -->
				<div class="rp-analytics-card">
					<div class="rp-card-header">
						<span class="rp-card-title"><?php esc_html_e( 'Unique Visits', 'resilient-hub' ); ?></span>
						<div class="rp-card-icon rp-icon-visits">
							<span class="dashicons dashicons-admin-users" style="font-size: 20px; width: 20px; height: 20px;"></span>
						</div>
					</div>
					<div class="rp-card-value"><?php echo number_format( absint( $unique_visits ) ); ?></div>
				</div>

				<!-- Card 2: Page Views -->
				<div class="rp-analytics-card">
					<div class="rp-card-header">
						<span class="rp-card-title"><?php esc_html_e( 'Page Views', 'resilient-hub' ); ?></span>
						<div class="rp-card-icon rp-icon-views">
							<span class="dashicons dashicons-visibility" style="font-size: 20px; width: 20px; height: 20px;"></span>
						</div>
					</div>
					<div class="rp-card-value"><?php echo number_format( absint( $total_views ) ); ?></div>
				</div>

				<!-- Card 3: Downloads -->
				<div class="rp-analytics-card">
					<div class="rp-card-header">
						<span class="rp-card-title"><?php esc_html_e( 'Resource Downloads', 'resilient-hub' ); ?></span>
						<div class="rp-card-icon rp-icon-downloads">
							<span class="dashicons dashicons-download" style="font-size: 20px; width: 20px; height: 20px;"></span>
						</div>
					</div>
					<div class="rp-card-value"><?php echo number_format( absint( $total_downloads ) ); ?></div>
				</div>
			</div>

			<!-- Dynamic Visual Chart -->
			<div class="rp-chart-container">
				<div class="rp-chart-header">
					<h3 class="rp-chart-title">
						<?php 
						if ( $days === 7 ) {
							esc_html_e( 'Activity Timeline - Last 7 Days', 'resilient-hub' );
						} elseif ( $days === 90 ) {
							esc_html_e( 'Activity Timeline - Last 90 Days', 'resilient-hub' );
						} elseif ( $days === 0 ) {
							esc_html_e( 'Activity Timeline - Last 90 Days (All-Time Range)', 'resilient-hub' );
						} else {
							esc_html_e( 'Activity Timeline - Last 30 Days', 'resilient-hub' );
						}
						?>
					</h3>
				</div>
				<div style="position: relative; height: 320px; width: 100%;">
					<canvas id="rpAnalyticsChart"></canvas>
				</div>
			</div>

			<!-- Split Grid for Top Content tables -->
			<div class="rp-analytics-tables-row">
				<!-- Top Downloaded Resources -->
				<div class="rp-analytics-table-card">
					<h3 class="rp-analytics-table-title"><?php esc_html_e( 'Top Downloaded Resources', 'resilient-hub' ); ?></h3>
					<div class="rp-table-responsive">
						<table class="rp-moderation-table" style="font-size: 13px;">
							<thead>
								<tr>
									<th><?php esc_html_e( 'Resource Title', 'resilient-hub' ); ?></th>
									<th><?php esc_html_e( 'Type', 'resilient-hub' ); ?></th>
									<th style="text-align: right;"><?php esc_html_e( 'Downloads', 'resilient-hub' ); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php if ( ! empty( $popular_downloads ) ) : ?>
									<?php foreach ( $popular_downloads as $pd ) : 
										$title = $pd->post_title ? $pd->post_title : sprintf( __( 'Deleted Resource (ID: %d)', 'resilient-hub' ), $pd->post_id );
										?>
										<tr>
											<td>
												<?php if ( $pd->post_title ) : ?>
													<strong><a href="<?php echo esc_url( get_permalink( $pd->post_id ) ); ?>"><?php echo esc_html( $title ); ?></a></strong>
												<?php else : ?>
													<span style="color: #9ca3af; font-style: italic;"><?php echo esc_html( $title ); ?></span>
												<?php endif; ?>
											</td>
											<td>
												<span class="rp-log-badge <?php echo 'accord_library' === $pd->post_type ? 'rp-log-badge-accord' : 'rp-log-badge-partner'; ?>">
													<?php echo 'accord_library' === $pd->post_type ? esc_html__( 'ACCORD Library', 'resilient-hub' ) : esc_html__( 'Partner Resource', 'resilient-hub' ); ?>
												</span>
											</td>
											<td style="text-align: right; font-weight: 700; color: #16a34a;"><?php echo number_format( $pd->count ); ?></td>
										</tr>
									<?php endforeach; ?>
								<?php else : ?>
									<tr>
										<td colspan="3" style="text-align: center; color: #9ca3af;"><?php esc_html_e( 'No downloads logged yet.', 'resilient-hub' ); ?></td>
									</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				</div>

				<!-- Top Viewed Pages -->
				<div class="rp-analytics-table-card">
					<h3 class="rp-analytics-table-title"><?php esc_html_e( 'Top Viewed Content', 'resilient-hub' ); ?></h3>
					<div class="rp-table-responsive">
						<table class="rp-moderation-table" style="font-size: 13px;">
							<thead>
								<tr>
									<th><?php esc_html_e( 'Page / Post Title', 'resilient-hub' ); ?></th>
									<th><?php esc_html_e( 'Type', 'resilient-hub' ); ?></th>
									<th style="text-align: right;"><?php esc_html_e( 'Views', 'resilient-hub' ); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php if ( ! empty( $popular_pages ) ) : ?>
									<?php foreach ( $popular_pages as $pp ) : 
										if ( $pp->post_id === 0 ) {
											$title = __( 'Portal Homepage', 'resilient-hub' );
											$type = 'page';
										} elseif ( $pp->post_id === -1 ) {
											$title = __( 'Resources Catalog / Archive', 'resilient-hub' );
											$type = 'archive';
										} else {
											$title = $pp->post_title ? $pp->post_title : sprintf( __( 'Deleted Content (ID: %d)', 'resilient-hub' ), $pp->post_id );
											$type = $pp->post_type;
										}
										?>
										<tr>
											<td>
												<?php if ( $pp->post_id > 0 && $pp->post_title ) : ?>
													<strong><a href="<?php echo esc_url( get_permalink( $pp->post_id ) ); ?>"><?php echo esc_html( $title ); ?></a></strong>
												<?php else : ?>
													<strong><?php echo esc_html( $title ); ?></strong>
												<?php endif; ?>
											</td>
											<td>
												<span class="rp-log-badge <?php 
													if ( 'accord_library' === $type ) echo 'rp-log-badge-accord';
													elseif ( 'partner_resources' === $type ) echo 'rp-log-badge-partner';
													elseif ( 'rp_sitrep' === $type ) echo 'rp_sitrep' === $type ? 'rp-log-badge-sitrep' : '';
													else echo 'rp-log-badge-page';
												?>">
													<?php 
													if ( 'accord_library' === $type ) esc_html_e( 'ACCORD Library', 'resilient-hub' );
													elseif ( 'partner_resources' === $type ) esc_html_e( 'Partner Resource', 'resilient-hub' );
													elseif ( 'rp_sitrep' === $type ) esc_html_e( 'SitRep', 'resilient-hub' );
													elseif ( 'archive' === $type ) esc_html_e( 'Archive', 'resilient-hub' );
													else esc_html_e( 'Page/Post', 'resilient-hub' );
													?>
												</span>
											</td>
											<td style="text-align: right; font-weight: 700; color: #2563eb;"><?php echo number_format( $pp->count ); ?></td>
										</tr>
									<?php endforeach; ?>
								<?php else : ?>
									<tr>
										<td colspan="3" style="text-align: center; color: #9ca3af;"><?php esc_html_e( 'No views logged yet.', 'resilient-hub' ); ?></td>
									</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>

			<!-- Full Width Detailed Audit Log Table -->
			<div class="rp-analytics-table-card" style="margin-bottom: 40px;">
				<h3 class="rp-analytics-table-title">
					<?php 
					if ( $search_log ) {
						printf( esc_html__( 'Search Results: Download Audit Trail (Last 100 Events Matching "%s")', 'resilient-hub' ), esc_html( $search_log ) );
					} else {
						esc_html_e( 'Download Audit Trail (Last 100 Events)', 'resilient-hub' );
					}
					?>
				</h3>
				<div class="rp-table-responsive">
					<table class="rp-moderation-table" style="font-size: 13px;">
						<thead>
							<tr>
								<th><?php esc_html_e( 'User (Who)', 'resilient-hub' ); ?></th>
								<th><?php esc_html_e( 'Downloaded Resource (What)', 'resilient-hub' ); ?></th>
								<th><?php esc_html_e( 'IP Address', 'resilient-hub' ); ?></th>
								<th><?php esc_html_e( 'Timestamp (When)', 'resilient-hub' ); ?></th>
								<th><?php esc_html_e( 'Browser / Device', 'resilient-hub' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php if ( ! empty( $download_logs ) ) : ?>
								<?php foreach ( $download_logs as $log ) : 
									// Load user info
									if ( $log->user_id ) {
										$user_data = get_userdata( $log->user_id );
										$user_display = $user_data ? sprintf( '%s (@%s)', $user_data->display_name, $user_data->user_login ) : __( 'Deleted User', 'resilient-hub' );
										$user_email = $user_data ? $user_data->user_email : '';
									} else {
										$user_display = __( 'Guest User', 'resilient-hub' );
										$user_email = __( 'Anonymous', 'resilient-hub' );
									}

									$res_title = $log->post_title ? $log->post_title : sprintf( __( 'Deleted Resource (ID: %d)', 'resilient-hub' ), $log->post_id );
									?>
									<tr>
										<td>
											<div style="display: flex; flex-direction: column;">
												<strong><?php echo esc_html( $user_display ); ?></strong>
												<span style="font-size: 11px; color: #6b7280;"><?php echo esc_html( $user_email ); ?></span>
											</div>
										</td>
										<td>
											<div style="display: flex; flex-direction: column;">
												<?php if ( $log->post_title ) : ?>
													<strong><a href="<?php echo esc_url( get_permalink( $log->post_id ) ); ?>"><?php echo esc_html( $res_title ); ?></a></strong>
												<?php else : ?>
													<span style="color: #9ca3af; font-style: italic;"><?php echo esc_html( $res_title ); ?></span>
												<?php endif; ?>
												<span style="align-self: flex-start; margin-top: 4px;" class="rp-log-badge <?php echo 'accord_library' === $log->post_type ? 'rp-log-badge-accord' : 'rp-log-badge-partner'; ?>">
													<?php echo 'accord_library' === $log->post_type ? esc_html__( 'ACCORD Library', 'resilient-hub' ) : esc_html__( 'Partner Resource', 'resilient-hub' ); ?>
												</span>
											</div>
										</td>
										<td><code><?php echo esc_html( $log->ip_address ); ?></code></td>
										<td><?php echo esc_html( date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $log->created_at ) ) ); ?></td>
										<td>
											<span class="rp-log-agent" title="<?php echo esc_attr( $log->user_agent ); ?>">
												<?php echo esc_html( $log->user_agent ? $log->user_agent : __( 'Unknown', 'resilient-hub' ) ); ?>
											</span>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php else : ?>
								<tr>
									<td colspan="5" style="text-align: center; color: #9ca3af; padding: 24px;"><?php esc_html_e( 'No downloads logged matching your criteria.', 'resilient-hub' ); ?></td>
								</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>

		</div>
	</div>
</main>

<!-- Load Chart.js CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<!-- Load html2canvas CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<!-- Load html2pdf.js CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
	var ctx = document.getElementById('rpAnalyticsChart').getContext('2d');
	
	var labels = <?php echo json_encode( $labels ); ?>;
	var viewsData = <?php echo json_encode( $views_series ); ?>;
	var visitsData = <?php echo json_encode( $visits_series ); ?>;
	var downloadsData = <?php echo json_encode( $downloads_series ); ?>;

	var rpChart = new Chart(ctx, {
		type: 'line',
		data: {
			labels: labels,
			datasets: [
				{
					label: 'Page Views',
					data: viewsData,
					borderColor: '#2563eb',
					backgroundColor: 'rgba(37, 99, 235, 0.05)',
					borderWidth: 3,
					tension: 0.3,
					fill: true,
					pointBackgroundColor: '#2563eb',
					pointHoverRadius: 6
				},
				{
					label: 'Unique Visits',
					data: visitsData,
					borderColor: '#d97706',
					backgroundColor: 'transparent',
					borderWidth: 2,
					tension: 0.3,
					borderDash: [5, 5],
					pointBackgroundColor: '#d97706',
					pointHoverRadius: 5
				},
				{
					label: 'Downloads',
					data: downloadsData,
					borderColor: '#16a34a',
					backgroundColor: 'rgba(22, 163, 74, 0.05)',
					borderWidth: 3,
					tension: 0.3,
					fill: true,
					pointBackgroundColor: '#16a34a',
					pointHoverRadius: 6
				}
			]
		},
		options: {
			responsive: true,
			maintainAspectRatio: false,
			plugins: {
				legend: {
					position: 'top',
					labels: {
						font: {
							family: 'Inter, system-ui, sans-serif',
							weight: '600',
							size: 12
						},
						boxWidth: 16,
						padding: 20
					}
				},
				tooltip: {
					padding: 12,
					backgroundColor: 'rgba(17, 24, 39, 0.95)',
					titleFont: {
						family: 'Inter, system-ui, sans-serif',
						size: 13,
						weight: 'bold'
					},
					bodyFont: {
						family: 'Inter, system-ui, sans-serif',
						size: 12
					},
					cornerRadius: 8
				}
			},
			scales: {
				y: {
					beginAtZero: true,
					grid: {
						color: '#f3f4f6'
					},
					ticks: {
						font: {
							family: 'Inter, system-ui, sans-serif',
							size: 11
						},
						precision: 0
					}
				},
				x: {
					grid: {
						display: false
					},
					ticks: {
						font: {
							family: 'Inter, system-ui, sans-serif',
							size: 11
						},
						maxTicksLimit: 15
					}
				}
			}
		}
	});

	// PDF Export Handler
	document.getElementById('rp-export-pdf').addEventListener('click', function(e) {
		e.preventDefault();
		
		var btn = this;
		var originalText = btn.innerHTML;
		btn.innerHTML = '<span class="dashicons dashicons-update spin" style="font-size: 18px; width: 18px; height: 18px; line-height: 1; margin-top: 3px;"></span> <?php esc_html_e( 'Generating...', 'resilient-hub' ); ?>';
		btn.disabled = true;

		var element = document.getElementById('primary');
		element.classList.add('rp-exporting');
		
		var opt = {
			margin:       [0.4, 0.4, 0.4, 0.4],
			filename:     'Resilience_Hub_Analytics_' + new Date().toISOString().slice(0,10) + '.pdf',
			image:        { type: 'jpeg', quality: 0.98 },
			html2canvas:  { 
				scale: 2, 
				useCORS: true, 
				letterRendering: true,
				logging: false,
				windowWidth: 1200
			},
			jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' },
			pagebreak:    { mode: ['avoid-all', 'css'] }
		};

		html2pdf().set(opt).from(element).save().then(function() {
			element.classList.remove('rp-exporting');
			btn.innerHTML = originalText;
			btn.disabled = false;
		}).catch(function(err) {
			console.error(err);
			element.classList.remove('rp-exporting');
			btn.innerHTML = originalText;
			btn.disabled = false;
		});
	});

	// PNG Export Handler
	document.getElementById('rp-export-png').addEventListener('click', function(e) {
		e.preventDefault();
		
		var btn = this;
		var originalText = btn.innerHTML;
		btn.innerHTML = '<span class="dashicons dashicons-update spin" style="font-size: 18px; width: 18px; height: 18px; line-height: 1; margin-top: 3px;"></span> <?php esc_html_e( 'Generating...', 'resilient-hub' ); ?>';
		btn.disabled = true;

		var element = document.getElementById('primary');
		element.classList.add('rp-exporting');
		
		html2canvas(element, {
			scale: 2,
			useCORS: true,
			logging: false,
			windowWidth: 1200,
			backgroundColor: '#f4f8f6'
		}).then(function(canvas) {
			var link = document.createElement('a');
			link.download = 'Resilience_Hub_Analytics_' + new Date().toISOString().slice(0,10) + '.png';
			link.href = canvas.toDataURL('image/png');
			link.click();
			
			element.classList.remove('rp-exporting');
			btn.innerHTML = originalText;
			btn.disabled = false;
		}).catch(function(err) {
			console.error(err);
			element.classList.remove('rp-exporting');
			btn.innerHTML = originalText;
			btn.disabled = false;
		});
	});
});
</script>

<?php
get_footer();
