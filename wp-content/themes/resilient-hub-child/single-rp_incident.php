<?php
/**
 * Template for displaying Crisis Incident Dashboards.
 *
 * @package ResilientHub
 */

get_header();

$incident_id = get_the_ID();
global $wpdb;
$table_name = $wpdb->prefix . 'rp_sitrep_locations';

// Query all published location metrics for this incident
$locations = $wpdb->get_results( $wpdb->prepare(
    "SELECT * FROM $table_name WHERE incident_id = %d AND record_status = 'publish'",
    $incident_id
) );

$total_barangays            = 0;
$total_households           = 0;
$total_individuals          = 0;
$total_displaced_inside     = 0;
$total_displaced_outside    = 0;
$total_displaced_total      = 0;
$total_displaced_households = 0;

$municipality_breakdown = array();

foreach ( $locations as $loc ) {
    $total_barangays            += intval( $loc->affected_barangays );
    $total_households           += intval( $loc->households );
    $total_individuals          += intval( $loc->individuals );
    $total_displaced_inside     += intval( $loc->displaced_inside );
    $total_displaced_outside    += intval( $loc->displaced_outside );
    $total_displaced_total      += intval( $loc->displaced_total );
    $total_displaced_households += intval( $loc->displaced_households );

    $muni_key = trim( $loc->municipality );
    if ( ! isset( $municipality_breakdown[ $muni_key ] ) ) {
        $municipality_breakdown[ $muni_key ] = array(
            'barangays_count' => 0,
            'households'      => 0,
            'individuals'     => 0,
            'displaced_total' => 0,
            'details'         => array()
        );
    }
    $municipality_breakdown[ $muni_key ]['barangays_count'] += intval( $loc->affected_barangays );
    $municipality_breakdown[ $muni_key ]['households']      += intval( $loc->households );
    $municipality_breakdown[ $muni_key ]['individuals']     += intval( $loc->individuals );
    $municipality_breakdown[ $muni_key ]['displaced_total'] += intval( $loc->displaced_total );
    $municipality_breakdown[ $muni_key ]['details'][] = $loc;
}

// Fetch all published SitReps linked to this incident for sectoral descriptions
$sitreps_query = new WP_Query( array(
    'post_type'      => 'rp_sitrep',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'meta_query'     => array(
        array(
            'key'     => '_sitrep_incident_id',
            'value'   => $incident_id,
            'compare' => '=',
        )
    )
) );

$sectoral_data = array(
    'fsl'     => array(),
    'wash'    => array(),
    'shelter' => array(),
    'other'   => array(),
);

if ( $sitreps_query->have_posts() ) {
    while ( $sitreps_query->have_posts() ) {
        $sitreps_query->the_post();
        $sid = get_the_ID();
        $author_name = get_the_author();
        $date_str = get_the_date();
        $rep_link = get_permalink();

        $fsl     = get_post_meta( $sid, '_sitrep_sectoral_fsl', true );
        $wash    = get_post_meta( $sid, '_sitrep_sectoral_wash', true );
        $shelter = get_post_meta( $sid, '_sitrep_sectoral_shelter', true );
        $other   = get_post_meta( $sid, '_sitrep_sectoral_other', true );

        if ( ! empty( $fsl ) ) {
            $sectoral_data['fsl'][] = array( 'author' => $author_name, 'text' => $fsl, 'date' => $date_str, 'link' => $rep_link );
        }
        if ( ! empty( $wash ) ) {
            $sectoral_data['wash'][] = array( 'author' => $author_name, 'text' => $wash, 'date' => $date_str, 'link' => $rep_link );
        }
        if ( ! empty( $shelter ) ) {
            $sectoral_data['shelter'][] = array( 'author' => $author_name, 'text' => $shelter, 'date' => $date_str, 'link' => $rep_link );
        }
        if ( ! empty( $other ) ) {
            $sectoral_data['other'][] = array( 'author' => $author_name, 'text' => $other, 'date' => $date_str, 'link' => $rep_link );
        }
    }
    wp_reset_postdata();
}
?>

<main id="primary" class="rp-incident-dashboard-main">
    <!-- Incident Header / Hero -->
    <section class="rp-page-hero" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); padding: 60px 0; color: #fff; margin-bottom: 40px;">
        <div class="rp-page-shell" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
            <p class="rp-eyebrow" style="color: #ef4444; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; font-size: 14px; margin-bottom: 8px;">
                <?php esc_html_e( 'Crisis Incident Dashboard', 'resilient-hub' ); ?>
            </p>
            <h1 class="rp-page-title" style="font-size: 2.75rem; color: #fff; font-family: 'Outfit', sans-serif; font-weight: 800; margin: 0 0 10px 0;">
                <?php the_title(); ?>
            </h1>
            <div style="color: #94a3b8; font-size: 15px; display: flex; gap: 20px; align-items: center; flex-wrap: wrap;">
                <span>📅 Created: <strong><?php echo esc_html( get_the_date() ); ?></strong></span>
                <span>•</span>
                <span>📍 Scope: <strong><?php echo count( $municipality_breakdown ) . ' ' . __( 'Municipalities affected', 'resilient-hub' ); ?></strong></span>
            </div>
        </div>
    </section>

    <section class="rp-page-content" style="max-width: 1200px; margin: 0 auto; padding: 0 20px 60px 20px;">
        <!-- Dashboard Actions Panel -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
            <h2 style="font-size: 1.5rem; color: #0f172a; margin: 0; font-family: 'Outfit', sans-serif; font-weight: 700;">
                <?php esc_html_e( 'Incident Impact Aggregates', 'resilient-hub' ); ?>
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
            </div>
        </div>

        <!-- Aggregate Stat Cards -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 50px;">
            
            <div class="rp-stat-card" style="background: #fff; border: 1px solid #e2e8f0; border-left: 5px solid #ef4444; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                <div style="font-size: 12px; color: #64748b; font-weight: 700; text-transform: uppercase; margin-bottom: 6px;"><?php esc_html_e( 'Affected Individuals', 'resilient-hub' ); ?></div>
                <div style="font-size: 28px; font-weight: 800; color: #0f172a; font-family: 'Outfit', sans-serif;"><?php echo esc_html( number_format( $total_individuals ) ); ?></div>
                <div style="font-size: 13px; color: #64748b; margin-top: 4px;"><?php echo esc_html( number_format( $total_households ) ) . ' ' . __( 'households', 'resilient-hub' ); ?></div>
            </div>

            <div class="rp-stat-card" style="background: #fff; border: 1px solid #e2e8f0; border-left: 5px solid #f97316; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                <div style="font-size: 12px; color: #64748b; font-weight: 700; text-transform: uppercase; margin-bottom: 6px;"><?php esc_html_e( 'Total Displaced Persons', 'resilient-hub' ); ?></div>
                <div style="font-size: 28px; font-weight: 800; color: #f97316; font-family: 'Outfit', sans-serif;"><?php echo esc_html( number_format( $total_displaced_total ) ); ?></div>
                <div style="font-size: 13px; color: #64748b; margin-top: 4px;"><?php echo esc_html( number_format( $total_displaced_households ) ) . ' ' . __( 'households', 'resilient-hub' ); ?></div>
            </div>

            <div class="rp-stat-card" style="background: #fff; border: 1px solid #e2e8f0; border-left: 5px solid #3b82f6; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                <div style="font-size: 12px; color: #64748b; font-weight: 700; text-transform: uppercase; margin-bottom: 6px;"><?php esc_html_e( 'Displacement in ECs', 'resilient-hub' ); ?></div>
                <div style="font-size: 28px; font-weight: 800; color: #3b82f6; font-family: 'Outfit', sans-serif;"><?php echo esc_html( number_format( $total_displaced_inside ) ); ?></div>
                <div style="font-size: 13px; color: #64748b; margin-top: 4px;"><?php echo esc_html( number_format( $total_displaced_outside ) ) . ' ' . __( 'outside evacuation centers', 'resilient-hub' ); ?></div>
            </div>

            <div class="rp-stat-card" style="background: #fff; border: 1px solid #e2e8f0; border-left: 5px solid #10b981; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                <div style="font-size: 12px; color: #64748b; font-weight: 700; text-transform: uppercase; margin-bottom: 6px;"><?php esc_html_e( 'Scope of Impact', 'resilient-hub' ); ?></div>
                <div style="font-size: 28px; font-weight: 800; color: #10b981; font-family: 'Outfit', sans-serif;"><?php echo esc_html( $total_barangays ); ?></div>
                <div style="font-size: 13px; color: #64748b; margin-top: 4px;"><?php esc_html_e( 'Affected barangays reported', 'resilient-hub' ); ?></div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 40px; align-items: start;">
            
            <!-- Left Side: Geographical Detail Table -->
            <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 30px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                <h3 style="font-size: 1.3rem; color: #0f172a; margin: 0 0 20px 0; font-family: 'Outfit', sans-serif; font-weight: 700; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px;">
                    <?php esc_html_e( 'Geographical Breakdown', 'resilient-hub' ); ?>
                </h3>

                <?php if ( ! empty( $municipality_breakdown ) ) : ?>
                    <div style="display: flex; flex-direction: column; gap: 30px;">
                        <?php foreach ( $municipality_breakdown as $muni => $data ) : ?>
                            <div>
                                <div style="display: flex; justify-content: space-between; align-items: center; background: #f8fafc; padding: 12px 18px; border-radius: 6px; margin-bottom: 10px; border-left: 4px solid #475569;">
                                    <strong style="font-size: 16px; color: #1e293b;"><?php echo esc_html( $muni ); ?></strong>
                                    <span style="font-size: 13px; color: #64748b; font-weight: 600;">
                                        <?php echo number_format( $data['individuals'] ) . ' indivs | ' . $data['barangays_count'] . ' barangays'; ?>
                                    </span>
                                </div>
                                
                                <div style="overflow-x: auto; padding-left: 10px;">
                                    <table style="width: 100%; border-collapse: collapse; font-size: 13px; text-align: left;">
                                        <thead>
                                            <tr style="border-bottom: 1px solid #e2e8f0; color: #64748b; font-weight: 700;">
                                                <th style="padding: 8px;"><?php esc_html_e( 'Barangay', 'resilient-hub' ); ?></th>
                                                <th style="padding: 8px;"><?php esc_html_e( 'Affected Families', 'resilient-hub' ); ?></th>
                                                <th style="padding: 8px; text-align: right;"><?php esc_html_e( 'Displaced Indivs', 'resilient-hub' ); ?></th>
                                                <th style="padding: 8px; text-align: right;"><?php esc_html_e( 'In / Out EC', 'resilient-hub' ); ?></th>
                                                <th style="padding: 8px; color: #94a3b8;"><?php esc_html_e( 'Source', 'resilient-hub' ); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ( $data['details'] as $loc ) : ?>
                                                <tr style="border-bottom: 1px solid #f1f5f9; color: #334155;">
                                                    <td style="padding: 10px 8px; font-weight: 600; color: #0f172a;"><?php echo esc_html( $loc->barangay ); ?></td>
                                                    <td style="padding: 10px 8px;"><?php echo esc_html( number_format( $loc->households ) ); ?></td>
                                                    <td style="padding: 10px 8px; text-align: right; font-weight: 700; color: #f97316;"><?php echo esc_html( number_format( $loc->displaced_total ) ); ?></td>
                                                    <td style="padding: 10px 8px; text-align: right;"><?php echo esc_html( number_format( $loc->displaced_inside ) ) . ' / ' . esc_html( number_format( $loc->displaced_outside ) ); ?></td>
                                                    <td style="padding: 10px 8px; font-size: 11px; color: #64748b;"><?php echo esc_html( $loc->data_source ); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <p style="color: #64748b; font-size: 14px; margin: 0; text-align: center; padding: 30px 0;">
                        <?php esc_html_e( 'No verified location impact metrics registered yet.', 'resilient-hub' ); ?>
                    </p>
                <?php endif; ?>
            </div>

            <!-- Right Side: Sectoral Situation Summaries & Sources -->
            <div style="display: flex; flex-direction: column; gap: 30px;">
                
                <!-- Sectoral Information Accordion/Tabs -->
                <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                    <div style="background: #0f172a; color: #fff; padding: 15px 20px; font-weight: bold;">
                        <h4 style="margin: 0; font-size: 15px; color: #fff; text-transform: uppercase; letter-spacing: 0.5px;"><?php esc_html_e( 'Sectoral Situations', 'resilient-hub' ); ?></h4>
                    </div>
                    
                    <div style="padding: 20px;">
                        <!-- Tab Headers -->
                        <div class="rp-tabs-header" style="display: flex; border-bottom: 2px solid #e2e8f0; margin-bottom: 20px; font-size: 13px; font-weight: 700;" data-html2canvas-ignore="true">
                            <button class="rp-tab-btn active" onclick="switchTab(event, 'fsl')" style="flex: 1; border: none; background: none; padding: 10px 5px; cursor: pointer; color: #ef4444; border-bottom: 2px solid #ef4444; margin-bottom: -2px; font-weight: bold; text-align: center;">FSL</button>
                            <button class="rp-tab-btn" onclick="switchTab(event, 'wash')" style="flex: 1; border: none; background: none; padding: 10px 5px; cursor: pointer; color: #64748b; border-bottom: 2px solid transparent; margin-bottom: -2px; font-weight: bold; text-align: center;">WASH</button>
                            <button class="rp-tab-btn" onclick="switchTab(event, 'shelter')" style="flex: 1; border: none; background: none; padding: 10px 5px; cursor: pointer; color: #64748b; border-bottom: 2px solid transparent; margin-bottom: -2px; font-weight: bold; text-align: center;">SHELTER</button>
                            <button class="rp-tab-btn" onclick="switchTab(event, 'other')" style="flex: 1; border: none; background: none; padding: 10px 5px; cursor: pointer; color: #64748b; border-bottom: 2px solid transparent; margin-bottom: -2px; font-weight: bold; text-align: center;">OTHERS</button>
                        </div>

                        <!-- Tab Contents -->
                        <div id="rp-tab-fsl" class="rp-tab-content" data-sector-name="Food Security & Livelihoods (FSL)" style="display: block;">
                            <?php if ( ! empty( $sectoral_data['fsl'] ) ) : ?>
                                <?php foreach ( $sectoral_data['fsl'] as $item ) : ?>
                                    <div style="border-bottom: 1px dashed #e2e8f0; padding-bottom: 12px; margin-bottom: 12px;">
                                        <p style="margin: 0 0 6px 0; font-size: 14px; color: #334155; line-height: 1.5;"><?php echo esc_html( $item['text'] ); ?></p>
                                        <span style="font-size: 11px; color: #94a3b8;"><?php echo sprintf( __( 'Reported by %1$s on %2$s', 'resilient-hub' ), '<strong>' . esc_html($item['author']) . '</strong>', esc_html($item['date']) ); ?></span>
                                    </div>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <p style="color: #94a3b8; font-size: 13px; margin: 0; text-align: center;"><?php esc_html_e( 'No FSL details reported.', 'resilient-hub' ); ?></p>
                            <?php endif; ?>
                        </div>

                        <div id="rp-tab-wash" class="rp-tab-content" data-sector-name="Water, Sanitation & Hygiene (WASH)" style="display: none;">
                            <?php if ( ! empty( $sectoral_data['wash'] ) ) : ?>
                                <?php foreach ( $sectoral_data['wash'] as $item ) : ?>
                                    <div style="border-bottom: 1px dashed #e2e8f0; padding-bottom: 12px; margin-bottom: 12px;">
                                        <p style="margin: 0 0 6px 0; font-size: 14px; color: #334155; line-height: 1.5;"><?php echo esc_html( $item['text'] ); ?></p>
                                        <span style="font-size: 11px; color: #94a3b8;"><?php echo sprintf( __( 'Reported by %1$s on %2$s', 'resilient-hub' ), '<strong>' . esc_html($item['author']) . '</strong>', esc_html($item['date']) ); ?></span>
                                    </div>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <p style="color: #94a3b8; font-size: 13px; margin: 0; text-align: center;"><?php esc_html_e( 'No WASH details reported.', 'resilient-hub' ); ?></p>
                            <?php endif; ?>
                        </div>

                        <div id="rp-tab-shelter" class="rp-tab-content" data-sector-name="Shelter & Settlement" style="display: none;">
                            <?php if ( ! empty( $sectoral_data['shelter'] ) ) : ?>
                                <?php foreach ( $sectoral_data['shelter'] as $item ) : ?>
                                    <div style="border-bottom: 1px dashed #e2e8f0; padding-bottom: 12px; margin-bottom: 12px;">
                                        <p style="margin: 0 0 6px 0; font-size: 14px; color: #334155; line-height: 1.5;"><?php echo esc_html( $item['text'] ); ?></p>
                                        <span style="font-size: 11px; color: #94a3b8;"><?php echo sprintf( __( 'Reported by %1$s on %2$s', 'resilient-hub' ), '<strong>' . esc_html($item['author']) . '</strong>', esc_html($item['date']) ); ?></span>
                                    </div>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <p style="color: #94a3b8; font-size: 13px; margin: 0; text-align: center;"><?php esc_html_e( 'No Shelter details reported.', 'resilient-hub' ); ?></p>
                            <?php endif; ?>
                        </div>

                        <div id="rp-tab-other" class="rp-tab-content" data-sector-name="Other Sectors / Comments" style="display: none;">
                            <?php if ( ! empty( $sectoral_data['other'] ) ) : ?>
                                <?php foreach ( $sectoral_data['other'] as $item ) : ?>
                                    <div style="border-bottom: 1px dashed #e2e8f0; padding-bottom: 12px; margin-bottom: 12px;">
                                        <p style="margin: 0 0 6px 0; font-size: 14px; color: #334155; line-height: 1.5;"><?php echo esc_html( $item['text'] ); ?></p>
                                        <span style="font-size: 11px; color: #94a3b8;"><?php echo sprintf( __( 'Reported by %1$s on %2$s', 'resilient-hub' ), '<strong>' . esc_html($item['author']) . '</strong>', esc_html($item['date']) ); ?></span>
                                    </div>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <p style="color: #94a3b8; font-size: 13px; margin: 0; text-align: center;"><?php esc_html_e( 'No details reported for other sectors.', 'resilient-hub' ); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Incident Context / description -->
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 25px;">
                    <h4 style="margin: 0 0 10px 0; font-size: 14px; color: #475569; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                        <?php esc_html_e( 'About this incident', 'resilient-hub' ); ?>
                    </h4>
                    <div style="font-size: 14px; color: #334155; line-height: 1.6;">
                        <?php the_content(); ?>
                    </div>
                </div>

            </div>

        </div>

    </section>

    <script>
    function switchTab(evt, tabName) {
        // Hide all tab contents
        const tabContents = document.getElementsByClassName("rp-tab-content");
        for (let i = 0; i < tabContents.length; i++) {
            tabContents[i].style.display = "none";
        }

        // Deactivate all tab buttons
        const tabButtons = document.getElementsByClassName("rp-tab-btn");
        for (let i = 0; i < tabButtons.length; i++) {
            tabButtons[i].classList.remove("active");
            tabButtons[i].style.color = "#64748b";
            tabButtons[i].style.borderBottom = "2px solid transparent";
        }

        // Show the active tab contents, and style the clicked button
        document.getElementById("rp-tab-" + tabName).style.display = "block";
        evt.currentTarget.classList.add("active");
        evt.currentTarget.style.color = "#ef4444";
        evt.currentTarget.style.borderBottom = "2px solid #ef4444";
    }
    </script>
</main>

<!-- Load html2canvas CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
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

/* Print/Export style modifiers to stack tab sections sequentially */
.rp-exporting .rp-tab-content {
	display: block !important;
	border-top: 1px dashed #e2e8f0;
	padding-top: 15px;
	margin-top: 15px;
}
.rp-exporting .rp-tab-content::before {
	content: attr(data-sector-name);
	display: block;
	font-weight: 700;
	color: #ef4444;
	text-transform: uppercase;
	font-size: 13px;
	margin-bottom: 8px;
	letter-spacing: 0.5px;
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
		
		// Add exporting layout modifier
		element.classList.add('rp-exporting');
		
		var opt = {
			margin:       [0.4, 0.4, 0.4, 0.4],
			filename:     'Resilience_Hub_Incident_' + <?php echo json_encode( sanitize_title( get_the_title() ) ); ?> + '_' + new Date().toISOString().slice(0,10) + '.pdf',
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
			backgroundColor: '#f8fafc'
		}).then(function(canvas) {
			var link = document.createElement('a');
			link.download = 'Resilience_Hub_Incident_' + <?php echo json_encode( sanitize_title( get_the_title() ) ); ?> + '_' + new Date().toISOString().slice(0,10) + '.png';
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
