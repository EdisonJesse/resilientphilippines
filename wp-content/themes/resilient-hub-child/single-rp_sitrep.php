<?php
/**
 * Template for displaying single Situation Reports (SitReps).
 *
 * @package ResilientHub
 */

get_header();
?>

<main id="primary" class="rp-single-sitrep-main">
    <?php
    while ( have_posts() ) :
        the_post();
        $post_id = get_the_ID();

        // Get Incident details
        $incident_id = get_post_meta( $post_id, '_sitrep_incident_id', true );
        $incident_title = $incident_id ? get_the_title( $incident_id ) : __( 'General / Unassociated Incident', 'resilient-hub' );
        $incident_link  = $incident_id ? get_permalink( $incident_id ) : '';

        // Query location records from custom table
        global $wpdb;
        $table_name = $wpdb->prefix . 'rp_sitrep_locations';
        $locations = $wpdb->get_results( $wpdb->prepare(
            "SELECT * FROM $table_name WHERE sitrep_id = %d",
            $post_id
        ) );

        $total_barangays     = 0;
        $total_households    = 0;
        $total_individuals   = 0;
        $total_displaced_in  = 0;
        $total_displaced_out = 0;
        $total_displaced_tot = 0;
        $total_displaced_hh  = 0;

        foreach ( $locations as $loc ) {
            $total_barangays     += intval( $loc->affected_barangays );
            $total_households    += intval( $loc->households );
            $total_individuals   += intval( $loc->individuals );
            $total_displaced_in  += intval( $loc->displaced_inside );
            $total_displaced_out += intval( $loc->displaced_outside );
            $total_displaced_tot += intval( $loc->displaced_total );
            $total_displaced_hh  += intval( $loc->displaced_households );
        }

        // Get sectoral details
        $fsl     = get_post_meta( $post_id, '_sitrep_sectoral_fsl', true );
        $wash    = get_post_meta( $post_id, '_sitrep_sectoral_wash', true );
        $shelter = get_post_meta( $post_id, '_sitrep_sectoral_shelter', true );
        $other   = get_post_meta( $post_id, '_sitrep_sectoral_other', true );

        // Get attachment details if any
        $file_id = get_post_meta( $post_id, '_rp_resource_file_id', true );
        $download_url = $file_id ? rp_resource_hub_download_url( $post_id ) : '';

        // Hazard terms
        $hazard_types = get_the_terms( $post_id, 'hazard_type' );
        $hazard_list = ( ! is_wp_error( $hazard_types ) && ! empty( $hazard_types ) ) ? implode( ', ', wp_list_pluck( $hazard_types, 'name' ) ) : 'General';
        ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class( 'rp-sitrep-article rp-page-shell' ); ?> style="max-width: 1200px; margin: 40px auto; padding: 0 20px;">
            
            <header class="rp-sitrep-header" style="margin-bottom: 30px; border-bottom: 2px solid #e2e8f0; padding-bottom: 20px;">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px; flex-wrap: wrap;">
                    <span class="rp-tag" style="background: #ef4444; color: #fff; padding: 4px 10px; border-radius: 4px; font-weight: bold; font-size: 12px; text-transform: uppercase;">
                        <?php esc_html_e( 'Situation Report Submission', 'resilient-hub' ); ?>
                    </span>
                    <span class="rp-tag-hazard" style="background: #e2e8f0; color: #1e293b; padding: 4px 10px; border-radius: 4px; font-weight: 600; font-size: 12px;">
                        <?php echo esc_html( $hazard_list ); ?>
                    </span>
                    <?php if ( $incident_link ) : ?>
                        <span style="font-size: 14px; color: #64748b;">
                            <?php esc_html_e( 'Linked to incident:', 'resilient-hub' ); ?> 
                            <a href="<?php echo esc_url( $incident_link ); ?>" style="font-weight: 700; color: #ef4444; text-decoration: none;">
                                <?php echo esc_html( $incident_title ); ?> ➔
                            </a>
                        </span>
                    <?php endif; ?>
                </div>
                <h1 class="rp-sitrep-title" style="font-size: 2.25rem; color: #0f172a; margin: 0 0 10px 0; font-family: 'Outfit', sans-serif; font-weight: 700; line-height: 1.2;">
                    <?php the_title(); ?>
                </h1>
                <div class="rp-sitrep-meta" style="color: #64748b; font-size: 14px;">
                    <span><?php esc_html_e( 'Report Date:', 'resilient-hub' ); ?> <strong><?php echo esc_html( get_the_date() ); ?></strong></span>
                    <span style="margin: 0 10px;">•</span>
                    <span><?php esc_html_e( 'Submitted by:', 'resilient-hub' ); ?> <strong><?php the_author(); ?></strong></span>
                </div>
            </header>

            <div class="rp-sitrep-grid-container" style="display: grid; grid-template-columns: 2.2fr 0.8fr; gap: 40px;">
                
                <!-- Main Content -->
                <div class="rp-sitrep-col-main">
                    <!-- Impact Metrics Grid -->
                    <h2 style="font-size: 1.5rem; color: #1e293b; margin: 0 0 20px 0; font-weight: 600;">
                        <?php esc_html_e( 'Submission Aggregate Impact', 'resilient-hub' ); ?>
                    </h2>
                    
                    <div class="rp-metrics-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 40px;">
                        <div class="rp-metric-card" style="background: #f8fafc; border-left: 4px solid #ef4444; padding: 20px; border-radius: 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                            <div style="font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase; margin-bottom: 6px;">
                                <?php esc_html_e( 'Affected Individuals', 'resilient-hub' ); ?>
                            </div>
                            <div style="font-size: 26px; font-weight: 700; color: #ef4444; font-family: 'Outfit', sans-serif;">
                                <?php echo esc_html( number_format( $total_individuals ) ); ?>
                            </div>
                            <div style="font-size: 12px; color: #64748b; margin-top: 4px;">
                                <?php echo esc_html( number_format( $total_households ) ) . ' ' . __( 'households', 'resilient-hub' ); ?>
                            </div>
                        </div>

                        <div class="rp-metric-card" style="background: #f8fafc; border-left: 4px solid #10b981; padding: 20px; border-radius: 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                            <div style="font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase; margin-bottom: 6px;">
                                <?php esc_html_e( 'Affected Barangays', 'resilient-hub' ); ?>
                            </div>
                            <div style="font-size: 26px; font-weight: 700; color: #10b981; font-family: 'Outfit', sans-serif;">
                                <?php echo esc_html( number_format( $total_barangays ) ); ?>
                            </div>
                            <div style="font-size: 12px; color: #64748b; margin-top: 4px;">
                                <?php esc_html_e( 'reported across municipalities', 'resilient-hub' ); ?>
                            </div>
                        </div>

                        <div class="rp-metric-card" style="background: #f8fafc; border-left: 4px solid #f97316; padding: 20px; border-radius: 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                            <div style="font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase; margin-bottom: 6px;">
                                <?php esc_html_e( 'Total Displaced Persons', 'resilient-hub' ); ?>
                            </div>
                            <div style="font-size: 26px; font-weight: 700; color: #f97316; font-family: 'Outfit', sans-serif;">
                                <?php echo esc_html( number_format( $total_displaced_tot ) ); ?>
                            </div>
                            <div style="font-size: 12px; color: #64748b; margin-top: 4px;">
                                <?php echo esc_html( number_format( $total_displaced_hh ) ) . ' ' . __( 'households', 'resilient-hub' ); ?>
                            </div>
                        </div>

                        <div class="rp-metric-card" style="background: #f8fafc; border-left: 4px solid #3b82f6; padding: 20px; border-radius: 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                            <div style="font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase; margin-bottom: 6px;">
                                <?php esc_html_e( 'Displaced in / out EC', 'resilient-hub' ); ?>
                            </div>
                            <div style="font-size: 26px; font-weight: 700; color: #3b82f6; font-family: 'Outfit', sans-serif;">
                                <?php echo esc_html( number_format( $total_displaced_in ) ); ?>
                            </div>
                            <div style="font-size: 12px; color: #64748b; margin-top: 4px;">
                                <?php echo esc_html( number_format( $total_displaced_out ) ) . ' ' . __( 'outside centers', 'resilient-hub' ); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Location List Table -->
                    <div class="rp-sitrep-section" style="margin-bottom: 40px;">
                        <h3 style="font-size: 1.25rem; color: #1e293b; border-bottom: 2px solid #f1f5f9; padding-bottom: 8px; margin-bottom: 15px; font-weight: 600;">
                            <?php esc_html_e( 'Affected Locations Detail', 'resilient-hub' ); ?>
                        </h3>
                        
                        <?php if ( ! empty( $locations ) ) : ?>
                            <div style="overflow-x: auto;">
                                <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
                                    <thead>
                                        <tr style="border-bottom: 2px solid #cbd5e1; color: #475569; font-weight: bold; background-color: #f8fafc;">
                                            <th style="padding: 10px;"><?php esc_html_e( 'Region / Province', 'resilient-hub' ); ?></th>
                                            <th style="padding: 10px;"><?php esc_html_e( 'Municipality', 'resilient-hub' ); ?></th>
                                            <th style="padding: 10px; text-align: right;"><?php esc_html_e( 'Affected Barangays', 'resilient-hub' ); ?></th>
                                            <th style="padding: 10px; text-align: right;"><?php esc_html_e( 'Affected Indivs', 'resilient-hub' ); ?></th>
                                            <th style="padding: 10px; text-align: right;"><?php esc_html_e( 'Displaced Indivs', 'resilient-hub' ); ?></th>
                                            <th style="padding: 10px; text-align: right;"><?php esc_html_e( 'Mode', 'resilient-hub' ); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ( $locations as $loc ) : ?>
                                            <tr style="border-bottom: 1px solid #e2e8f0; color: #334155;">
                                                <td style="padding: 12px 10px;">
                                                    <span style="font-size: 11px; background: #e2e8f0; padding: 2px 6px; border-radius: 3px; font-weight: 600; color: #475569; margin-right: 5px;">
                                                        <?php echo esc_html( $loc->region ); ?>
                                                    </span>
                                                    <?php echo esc_html( $loc->province ); ?>
                                                </td>
                                                <td style="padding: 12px 10px; font-weight: 600;"><?php echo esc_html( $loc->municipality ); ?></td>
                                                <td style="padding: 12px 10px; text-align: right;"><?php echo esc_html( number_format( $loc->affected_barangays ) ); ?></td>
                                                <td style="padding: 12px 10px; text-align: right;"><?php echo esc_html( number_format( $loc->individuals ) ); ?></td>
                                                <td style="padding: 12px 10px; text-align: right; font-weight: 700; color: #f97316;"><?php echo esc_html( number_format( $loc->displaced_total ) ); ?></td>
                                                <td style="padding: 12px 10px; text-align: right;">
                                                    <span style="font-size: 11px; font-weight: bold; text-transform: uppercase; color: <?php echo 'update' === $loc->conflict_mode ? '#2563eb' : '#059669'; ?>">
                                                        <?php echo esc_html( $loc->conflict_mode ); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else : ?>
                            <p style="color: #64748b; font-size: 14px;"><?php esc_html_e( 'No locations reported in this submission.', 'resilient-hub' ); ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Situation Summary -->
                    <div class="rp-sitrep-section" style="margin-bottom: 40px;">
                        <h3 style="font-size: 1.25rem; color: #1e293b; border-bottom: 2px solid #f1f5f9; padding-bottom: 8px; margin-bottom: 15px; font-weight: 600;">
                            <?php esc_html_e( 'Overview / Situation Summary', 'resilient-hub' ); ?>
                        </h3>
                        <div class="entry-content" style="color: #334155; line-height: 1.6; font-size: 16px;">
                            <?php the_content(); ?>
                        </div>
                    </div>

                    <!-- Sectoral Situation Details Grid -->
                    <div class="rp-sitrep-section" style="margin-bottom: 40px;">
                        <h3 style="font-size: 1.25rem; color: #1e293b; border-bottom: 2px solid #f1f5f9; padding-bottom: 8px; margin-bottom: 15px; font-weight: 600;">
                            <?php esc_html_e( 'Sectoral Situation Details', 'resilient-hub' ); ?>
                        </h3>
                        
                        <div style="display: grid; grid-template-columns: 1fr; gap: 20px;">
                            <?php if ( ! empty( $fsl ) ) : ?>
                                <div style="background: #f8fafc; border-left: 4px solid #ef4444; padding: 15px; border-radius: 4px;">
                                    <strong style="display: block; color: #ef4444; margin-bottom: 4px; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;"><?php esc_html_e( 'Food Security & Livelihoods (FSL)', 'resilient-hub' ); ?></strong>
                                    <p style="margin: 0; font-size: 14px; color: #334155; line-height: 1.5;"><?php echo esc_html( $fsl ); ?></p>
                                </div>
                            <?php endif; ?>

                            <?php if ( ! empty( $wash ) ) : ?>
                                <div style="background: #f8fafc; border-left: 4px solid #3b82f6; padding: 15px; border-radius: 4px;">
                                    <strong style="display: block; color: #3b82f6; margin-bottom: 4px; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;"><?php esc_html_e( 'Water, Sanitation & Hygiene (WASH)', 'resilient-hub' ); ?></strong>
                                    <p style="margin: 0; font-size: 14px; color: #334155; line-height: 1.5;"><?php echo esc_html( $wash ); ?></p>
                                </div>
                            <?php endif; ?>

                            <?php if ( ! empty( $shelter ) ) : ?>
                                <div style="background: #f8fafc; border-left: 4px solid #10b981; padding: 15px; border-radius: 4px;">
                                    <strong style="display: block; color: #10b981; margin-bottom: 4px; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;"><?php esc_html_e( 'Emergency Shelter', 'resilient-hub' ); ?></strong>
                                    <p style="margin: 0; font-size: 14px; color: #334155; line-height: 1.5;"><?php echo esc_html( $shelter ); ?></p>
                                </div>
                            <?php endif; ?>

                            <?php if ( ! empty( $other ) ) : ?>
                                <div style="background: #f8fafc; border-left: 4px solid #64748b; padding: 15px; border-radius: 4px;">
                                    <strong style="display: block; color: #64748b; margin-bottom: 4px; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;"><?php esc_html_e( 'Other Sectors', 'resilient-hub' ); ?></strong>
                                    <p style="margin: 0; font-size: 14px; color: #334155; line-height: 1.5;"><?php echo esc_html( $other ); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Details -->
                <aside class="rp-sitrep-col-sidebar">
                    <div class="rp-metadata-card" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; margin-bottom: 30px;">
                        <div class="rp-metadata-card-header" style="background: #0f172a; color: #fff; padding: 15px 20px; font-weight: bold;">
                            <h4 style="margin: 0; font-size: 15px; color: #fff; text-transform: uppercase; letter-spacing: 0.5px;">
                                <?php esc_html_e( 'Submission details', 'resilient-hub' ); ?>
                            </h4>
                        </div>
                        <div class="rp-metadata-card-body" style="padding: 20px; font-size: 14px; color: #334155;">
                            <div style="margin-bottom: 15px;">
                                <span style="display: block; font-size: 11px; color: #64748b; text-transform: uppercase; font-weight: 600; margin-bottom: 4px;">
                                    <?php esc_html_e( 'Incident Name', 'resilient-hub' ); ?>
                                </span>
                                <span style="font-size: 15px; font-weight: bold; color: #1e293b;">
                                    <?php echo esc_html( $incident_title ); ?>
                                </span>
                            </div>

                            <div style="margin-bottom: 15px;">
                                <span style="display: block; font-size: 11px; color: #64748b; text-transform: uppercase; font-weight: 600; margin-bottom: 4px;">
                                    <?php esc_html_e( 'Source / Org', 'resilient-hub' ); ?>
                                </span>
                                <span>
                                    <?php 
                                    $orgs = get_the_terms( $post_id, 'contributing_org' );
                                    if ( ! is_wp_error( $orgs ) && ! empty( $orgs ) ) {
                                        echo esc_html( implode( ', ', wp_list_pluck( $orgs, 'name' ) ) );
                                    } else {
                                        echo esc_html( get_the_author() );
                                    }
                                    ?>
                                </span>
                            </div>

                            <div>
                                <span style="display: block; font-size: 11px; color: #64748b; text-transform: uppercase; font-weight: 600; margin-bottom: 4px;">
                                    <?php esc_html_e( 'Status', 'resilient-hub' ); ?>
                                </span>
                                <span style="font-weight: 600; color: <?php echo 'publish' === get_post_status( $post_id ) ? '#059669' : '#d97706'; ?>">
                                    <?php echo esc_html( ucfirst( get_post_status( $post_id ) ) ); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Download Section -->
                    <?php if ( $download_url ) : ?>
                        <div style="background: #f1f5f9; border-radius: 8px; padding: 20px; text-align: center; border: 1px dashed #cbd5e1;">
                            <h4 style="margin: 0 0 10px 0; font-size: 14px; color: #475569; font-weight: 600;">
                                <?php esc_html_e( 'Supporting Evidence', 'resilient-hub' ); ?>
                            </h4>
                            <p style="font-size: 12px; color: #64748b; margin: 0 0 15px 0;">
                                <?php esc_html_e( 'Download the files, reports or photos uploaded with this situation report.', 'resilient-hub' ); ?>
                            </p>
                            <a class="rp-button" href="<?php echo esc_url( $download_url ); ?>" style="display: block; padding: 10px 15px; font-weight: bold; background: #3b82f6; color: #fff; border-radius: 4px; text-decoration: none; text-align: center;">
                                <?php esc_html_e( 'Download File Attachment', 'resilient-hub' ); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </aside>

            </div>

        </article>

        <?php
    endwhile;
    ?>
</main>

<?php
get_footer();
