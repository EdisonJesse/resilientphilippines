<?php
/**
 * Template Name: Situation Report Dashboard
 *
 * A front-end dashboard for visualizing aggregated situation reports metrics.
 *
 * @package ResilientHub
 */

get_header();

// Fetch all published SitReps for aggregation
global $wpdb;
$table_name = $wpdb->prefix . 'rp_sitrep_locations';

// Get new database metrics
$db_metrics = $wpdb->get_row( "
    SELECT 
        SUM(households) as total_households,
        SUM(individuals) as total_individuals,
        SUM(displaced_total) as total_displaced
    FROM $table_name 
    WHERE record_status = 'publish'
" );

$total_families   = intval( $db_metrics->total_households );
$total_displaced  = intval( $db_metrics->total_displaced );
$total_casualties = 0;
$total_houses     = 0;

$province_impacts = array();
$db_provinces = $wpdb->get_results( "
    SELECT province, SUM(households) as total_fams 
    FROM $table_name 
    WHERE record_status = 'publish' 
    GROUP BY province
" );
foreach ( $db_provinces as $dp ) {
    if ( ! empty( $dp->province ) ) {
        $province_impacts[ trim( $dp->province ) ] = intval( $dp->total_fams );
    }
}

$sitreps_query = new WP_Query( array(
    'post_type'      => 'rp_sitrep',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => 'date',
    'order'          => 'DESC',
) );

$recent_reports = array();

if ( $sitreps_query->have_posts() ) {
    while ( $sitreps_query->have_posts() ) {
        $sitreps_query->the_post();
        $pid = get_the_ID();

        // Check if this sitrep has location records in the custom table
        $has_db_locations = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $table_name WHERE sitrep_id = %d", $pid ) );

        $families  = intval( get_post_meta( $pid, '_sitrep_affected_families', true ) );
        $displaced = intval( get_post_meta( $pid, '_sitrep_displaced_persons', true ) );
        $cas       = intval( get_post_meta( $pid, '_sitrep_casualties', true ) );
        $h_total   = intval( get_post_meta( $pid, '_sitrep_houses_damaged_total', true ) );
        $h_partial = intval( get_post_meta( $pid, '_sitrep_houses_damaged_partial', true ) );
        $prov      = get_post_meta( $pid, '_sitrep_province', true );

        // If legacy (no database locations), add to totals
        if ( ! $has_db_locations ) {
            $total_families   += $families;
            $total_displaced  += $displaced;
            
            if ( ! empty( $prov ) ) {
                $prov_clean = trim( $prov );
                if ( ! isset( $province_impacts[ $prov_clean ] ) ) {
                    $province_impacts[ $prov_clean ] = 0;
                }
                $province_impacts[ $prov_clean ] += $families;
            }
        }

        // Casualties and houses are post meta only
        $total_casualties += $cas;
        $total_houses     += ( $h_total + $h_partial );

        // Determine family count and province string to show in recent reports list
        $display_families = 0;
        if ( $has_db_locations ) {
            $display_families = $wpdb->get_var( $wpdb->prepare( "SELECT SUM(households) FROM $table_name WHERE sitrep_id = %d AND record_status = 'publish'", $pid ) );
            $display_families = intval( $display_families );
            
            $sitrep_provs = $wpdb->get_col( $wpdb->prepare( "SELECT DISTINCT province FROM $table_name WHERE sitrep_id = %d AND record_status = 'publish'", $pid ) );
            $display_province = ! empty( $sitrep_provs ) ? implode( ', ', $sitrep_provs ) : __( 'Multi-Location', 'resilient-hub' );
        } else {
            $display_families = $families;
            $display_province = $prov;
        }

        if ( count( $recent_reports ) < 5 ) {
            $recent_reports[] = array(
                'id'        => $pid,
                'title'     => get_the_title(),
                'date'      => get_the_date(),
                'province'  => $display_province,
                'link'      => get_permalink(),
                'families'  => $display_families,
            );
        }
    }
    wp_reset_postdata();
}

// Sort provinces by impact size (descending)
arsort( $province_impacts );
$max_province_families = count( $province_impacts ) > 0 ? max( $province_impacts ) : 1;

// Fetch active incidents with their aggregated metrics
$incidents_query = new WP_Query( array(
    'post_type'      => 'rp_incident',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => 'title',
    'order'          => 'ASC',
) );

$incidents_data = array();
if ( $incidents_query->have_posts() ) {
    while ( $incidents_query->have_posts() ) {
        $incidents_query->the_post();
        $inc_id = get_the_ID();
        
        $inc_stats = $wpdb->get_row( $wpdb->prepare( "
            SELECT 
                COUNT(DISTINCT municipality) as munis_count,
                SUM(households) as total_households,
                SUM(individuals) as total_individuals
            FROM $table_name 
            WHERE incident_id = %d AND record_status = 'publish'
        ", $inc_id ) );
        
        $incidents_data[] = array(
            'id'          => $inc_id,
            'title'       => get_the_title(),
            'link'        => get_permalink(),
            'munis'       => intval( $inc_stats->munis_count ),
            'households'  => intval( $inc_stats->total_households ),
            'individuals' => intval( $inc_stats->total_individuals ),
            'excerpt'     => get_the_excerpt(),
        );
    }
    wp_reset_postdata();
}
?>

<main id="primary" class="rp-sitrep-dashboard-main">
    <section class="rp-page-hero" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); padding: 60px 0; color: #fff; margin-bottom: 40px;">
        <div class="rp-page-shell" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
            <p class="rp-eyebrow" style="color: #ef4444; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; font-size: 14px; margin-bottom: 8px;">
                <?php esc_html_e( 'Real-time Crisis Mapping', 'resilient-hub' ); ?>
            </p>
            <h1 class="rp-page-title" style="font-size: 2.75rem; color: #fff; font-family: 'Outfit', sans-serif; font-weight: 800; margin: 0 0 10px 0;">
                <?php esc_html_e( 'Situation Report Dashboard', 'resilient-hub' ); ?>
            </h1>
            <p style="color: #94a3b8; font-size: 16px; margin: 0; max-width: 600px;">
                <?php esc_html_e( 'Aggregated visual overview of disaster impact statistics from verified partner submissions.', 'resilient-hub' ); ?>
            </p>
        </div>
    </section>

    <section class="rp-page-content" style="max-width: 1200px; margin: 0 auto; padding: 0 20px 60px 0;">
        <div class="rp-page-shell" style="padding-left: 20px;">
            
            <!-- Dynamic Actions Panel -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
                <h2 style="font-size: 1.5rem; color: #0f172a; margin: 0; font-family: 'Outfit', sans-serif; font-weight: 700;">
                    <?php esc_html_e( 'Consolidated Disaster Impact Metrics', 'resilient-hub' ); ?>
                </h2>
				<div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;" data-html2canvas-ignore="true">
					<!-- Export Actions -->
					<div style="display: flex; gap: 8px;">
						<button id="rp-export-pdf" class="rp-button" style="background: #0f172a; border: 1px solid #e2e8f0; color: #fff; display: inline-flex; align-items: center; gap: 6px; font-weight: 600; padding: 10px 20px; border-radius: 6px; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#ef4444'; this.style.borderColor='#ef4444'" onmouseout="this.style.background='#0f172a'; this.style.borderColor='#e2e8f0'">
							<span class="dashicons dashicons-pdf" style="font-size: 18px; width: 18px; height: 18px; line-height: 1; margin-top: 3px;"></span> <?php esc_html_e( 'Export PDF', 'resilient-hub' ); ?>
						</button>
						<button id="rp-export-png" class="rp-button" style="background: #0f172a; border: 1px solid #e2e8f0; color: #fff; display: inline-flex; align-items: center; gap: 6px; font-weight: 600; padding: 10px 20px; border-radius: 6px; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#ef4444'; this.style.borderColor='#ef4444'" onmouseout="this.style.background='#0f172a'; this.style.borderColor='#e2e8f0'">
							<span class="dashicons dashicons-format-image" style="font-size: 18px; width: 18px; height: 18px; line-height: 1; margin-top: 3px;"></span> <?php esc_html_e( 'Export PNG', 'resilient-hub' ); ?>
						</button>
					</div>

					<?php if ( is_user_logged_in() && ( current_user_can( 'edit_rp_sitreps' ) || current_user_can( 'manage_options' ) ) ) : ?>
						<a href="<?php echo esc_url( home_url( '/submit-sitrep/' ) ); ?>" class="rp-button" style="background: #ef4444; color: #fff; font-weight: 600; padding: 10px 20px; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
							<span>✍️</span> <?php esc_html_e( 'Submit New SitRep', 'resilient-hub' ); ?>
						</a>
					<?php endif; ?>
				</div>
            </div>

            <!-- Aggregates Grid -->
            <div class="rp-dashboard-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 50px;">
                
                <!-- Stat Card: Affected Families -->
                <div class="rp-stat-card" style="background: #fff; border: 1px solid #e2e8f0; border-top: 4px solid #ef4444; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                    <div style="font-size: 12px; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
                        <?php esc_html_e( 'Total Affected Families', 'resilient-hub' ); ?>
                    </div>
                    <div style="font-size: 36px; font-weight: 800; color: #ef4444; font-family: 'Outfit', sans-serif; line-height: 1;">
                        <?php echo esc_html( number_format( $total_families ) ); ?>
                    </div>
                </div>

                <!-- Stat Card: Displaced Persons -->
                <div class="rp-stat-card" style="background: #fff; border: 1px solid #e2e8f0; border-top: 4px solid #f97316; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                    <div style="font-size: 12px; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
                        <?php esc_html_e( 'Total Displaced Persons', 'resilient-hub' ); ?>
                    </div>
                    <div style="font-size: 36px; font-weight: 800; color: #f97316; font-family: 'Outfit', sans-serif; line-height: 1;">
                        <?php echo esc_html( number_format( $total_displaced ) ); ?>
                    </div>
                </div>

                <!-- Stat Card: Casualties -->
                <div class="rp-stat-card" style="background: #fff; border: 1px solid #e2e8f0; border-top: 4px solid #3b82f6; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                    <div style="font-size: 12px; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
                        <?php esc_html_e( 'Total Casualties', 'resilient-hub' ); ?>
                    </div>
                    <div style="font-size: 36px; font-weight: 800; color: #3b82f6; font-family: 'Outfit', sans-serif; line-height: 1;">
                        <?php echo esc_html( number_format( $total_casualties ) ); ?>
                    </div>
                </div>

                <!-- Stat Card: Damaged Houses -->
                <div class="rp-stat-card" style="background: #fff; border: 1px solid #e2e8f0; border-top: 4px solid #10b981; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                    <div style="font-size: 12px; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
                        <?php esc_html_e( 'Total Damaged Houses', 'resilient-hub' ); ?>
                    </div>
                    <div style="font-size: 36px; font-weight: 800; color: #10b981; font-family: 'Outfit', sans-serif; line-height: 1;">
                        <?php echo esc_html( number_format( $total_houses ) ); ?>
                    </div>
                </div>
            </div>

            <!-- Active Crisis Incidents Section -->
            <div style="margin-bottom: 50px;">
                <h3 style="font-size: 1.3rem; color: #0f172a; margin: 0 0 20px 0; font-family: 'Outfit', sans-serif; font-weight: 700; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px;">
                    <?php esc_html_e( 'Active Crisis Incidents', 'resilient-hub' ); ?>
                </h3>
                <?php if ( ! empty( $incidents_data ) ) : ?>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                        <?php foreach ( $incidents_data as $inc ) : ?>
                            <div class="rp-incident-card" style="background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 25px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); display: flex; flex-direction: column; justify-content: space-between; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 15px -3px rgba(0,0,0,0.1)';" onmouseout="this.style.transform='none'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.05)';">
                                <div>
                                    <h4 style="margin: 0 0 10px 0; font-size: 16px; font-weight: 700; font-family: 'Outfit', sans-serif; color: #0f172a;">
                                        <?php echo esc_html( $inc['title'] ); ?>
                                    </h4>
                                    <p style="font-size: 13px; color: #64748b; margin: 0 0 15px 0; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        <?php echo esc_html( $inc['excerpt'] ? $inc['excerpt'] : __( 'Incident dashboard containing aggregated situation reports and maps.', 'resilient-hub' ) ); ?>
                                    </p>
                                </div>
                                <div>
                                    <div style="display: flex; gap: 10px; font-size: 11px; margin-bottom: 15px; flex-wrap: wrap;">
                                        <span style="background: #f1f5f9; color: #475569; padding: 4px 8px; border-radius: 4px; font-weight: 600;">
                                            📍 <?php echo sprintf( _n( '%d Municipality', '%d Municipalities', $inc['munis'], 'resilient-hub' ), $inc['munis'] ); ?>
                                        </span>
                                        <span style="background: #fef2f2; color: #ef4444; padding: 4px 8px; border-radius: 4px; font-weight: 600;">
                                            👨‍👩‍👧‍👦 <?php echo number_format( $inc['individuals'] ); ?> individuals
                                        </span>
                                    </div>
                                    <a href="<?php echo esc_url( $inc['link'] ); ?>" class="rp-button" style="display: block; text-align: center; background: #0f172a; color: #fff; text-decoration: none; font-size: 13px; font-weight: 600; padding: 8px 16px; border-radius: 6px; transition: background 0.2s;" onmouseover="this.style.background='#ef4444';" onmouseout="this.style.background='#0f172a';">
                                        <?php esc_html_e( 'View Incident Dashboard', 'resilient-hub' ); ?> →
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <p style="color: #64748b; font-size: 14px; margin: 0;">
                        <?php esc_html_e( 'No active crisis incidents found.', 'resilient-hub' ); ?>
                    </p>
                <?php endif; ?>
            </div>

            <!-- Two-Column Breakdown -->
            <div style="display: grid; grid-template-columns: 1.2fr 1.8fr; gap: 40px; align-items: start;">
                
                <!-- Left Side: Province Breakdown -->
                <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 30px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                    <h3 style="font-size: 1.2rem; color: #0f172a; margin: 0 0 20px 0; font-family: 'Outfit', sans-serif; font-weight: 700; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px;">
                        <?php esc_html_e( 'Impact by Province', 'resilient-hub' ); ?>
                    </h3>
                    
                    <?php if ( ! empty( $province_impacts ) ) : ?>
                        <div style="display: flex; flex-direction: column; gap: 20px;">
                            <?php foreach ( $province_impacts as $prov => $fam_count ) : ?>
                                <?php 
                                $pct = round( ($fam_count / $max_province_families) * 100 );
                                $pct = max( 4, $pct ); // Minimum visible width
                                ?>
                                <div>
                                    <div style="display: flex; justify-content: space-between; font-size: 14px; font-weight: 600; color: #334155; margin-bottom: 6px;">
                                        <span><?php echo esc_html( $prov ); ?></span>
                                        <span style="color: #64748b;"><?php echo esc_html( number_format( $fam_count ) ); ?> fams</span>
                                    </div>
                                    <div style="width: 100%; background: #e2e8f0; height: 10px; border-radius: 5px; overflow: hidden;">
                                        <div style="width: <?php echo esc_attr( $pct ); ?>%; background: #ef4444; height: 100%; border-radius: 5px;"></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else : ?>
                        <p style="color: #64748b; font-size: 14px; margin: 0;">
                            <?php esc_html_e( 'No impact data registered by province.', 'resilient-hub' ); ?>
                        </p>
                    <?php endif; ?>
                </div>

                <!-- Right Side: Recent Situation Reports -->
                <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 30px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                    <h3 style="font-size: 1.2rem; color: #0f172a; margin: 0 0 20px 0; font-family: 'Outfit', sans-serif; font-weight: 700; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px;">
                        <?php esc_html_e( 'Recent Situation Reports', 'resilient-hub' ); ?>
                    </h3>

                    <?php if ( ! empty( $recent_reports ) ) : ?>
                        <div style="display: flex; flex-direction: column; gap: 15px;">
                            <?php foreach ( $recent_reports as $rep ) : ?>
                                <article style="border: 1px solid #f1f5f9; border-radius: 6px; padding: 15px; display: flex; justify-content: space-between; align-items: center; gap: 15px; transition: box-shadow 0.2s;" onmouseover="this.style.boxShadow='0 4px 6px -1px rgba(0,0,0,0.05)'" onmouseout="this.style.boxShadow='none'">
                                    <div>
                                        <h4 style="margin: 0 0 4px 0; font-size: 15px; font-weight: 700; font-family: 'Outfit', sans-serif;">
                                            <a href="<?php echo esc_url( $rep['link'] ); ?>" style="color: #1e293b; text-decoration: none;">
                                                <?php echo esc_html( $rep['title'] ); ?>
                                            </a>
                                        </h4>
                                        <div style="font-size: 12px; color: #64748b; display: flex; align-items: center; gap: 8px;">
                                            <span>📍 <?php echo esc_html( $rep['province'] ); ?></span>
                                            <span>•</span>
                                            <span>📅 <?php echo esc_html( $rep['date'] ); ?></span>
                                        </div>
                                    </div>
                                    <div style="text-align: right; min-width: 100px;">
                                        <span style="font-size: 13px; font-weight: 700; color: #ef4444; background: #fef2f2; padding: 4px 8px; border-radius: 4px;">
                                            <?php echo esc_html( number_format( $rep['families'] ) ); ?> families
                                        </span>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    <?php else : ?>
                        <p style="color: #64748b; font-size: 14px; margin: 0;">
                            <?php esc_html_e( 'No verified situation reports have been published yet.', 'resilient-hub' ); ?>
                        </p>
                    <?php endif; ?>
                </div>

            </div>

        </div>
    </section>
</main>

<!-- Load html2pdf.js CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<style>
@keyframes spin {
	0% { transform: rotate(0deg); }
	100% { transform: rotate(360deg); }
}
.spin {
	display: inline-block;
	animation: spin 1s linear infinite;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
	// PDF Export Handler
	document.getElementById('rp-export-pdf').addEventListener('click', function(e) {
		e.preventDefault();
		
		var btn = this;
		var originalText = btn.innerHTML;
		btn.innerHTML = '<span class="dashicons dashicons-update spin" style="font-size: 18px; width: 18px; height: 18px; line-height: 1; margin-top: 3px;"></span> <?php esc_html_e( 'Generating...', 'resilient-hub' ); ?>';
		btn.disabled = true;

		var element = document.getElementById('primary');
		
		var opt = {
			margin:       [0.4, 0.4, 0.4, 0.4],
			filename:     'Resilience_Hub_SitRep_Dashboard_' + new Date().toISOString().slice(0,10) + '.pdf',
			image:        { type: 'jpeg', quality: 0.98 },
			html2canvas:  { 
				scale: 2, 
				useCORS: true, 
				letterRendering: true,
				logging: false,
				windowWidth: 1200
			},
			jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
		};

		html2pdf().set(opt).from(element).save().then(function() {
			btn.innerHTML = originalText;
			btn.disabled = false;
		}).catch(function(err) {
			console.error(err);
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
		
		html2canvas(element, {
			scale: 2,
			useCORS: true,
			logging: false,
			windowWidth: 1200,
			backgroundColor: '#f8fafc'
		}).then(function(canvas) {
			var link = document.createElement('a');
			link.download = 'Resilience_Hub_SitRep_Dashboard_' + new Date().toISOString().slice(0,10) + '.png';
			link.href = canvas.toDataURL('image/png');
			link.click();
			
			btn.innerHTML = originalText;
			btn.disabled = false;
		}).catch(function(err) {
			console.error(err);
			btn.innerHTML = originalText;
			btn.disabled = false;
		});
	});
});
</script>

<?php
get_footer();
