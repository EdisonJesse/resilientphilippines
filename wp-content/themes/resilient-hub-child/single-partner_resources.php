<?php
/**
 * Template for displaying single Partner Resources.
 *
 * @package ResilientHub
 */

get_header();
?>

<main id="primary" class="rp-single-resource-main">
    <?php
    while ( have_posts() ) :
        the_post();

        // Get file details
        $file_id = get_post_meta( get_the_ID(), '_rp_resource_file_id', true );
        $file_path = $file_id ? get_attached_file( $file_id ) : '';
        $file_size = 0;
        $file_ext = '';
        if ( $file_path && is_readable( $file_path ) ) {
            $file_size = filesize( $file_path );
            $filetype = wp_check_filetype( basename( $file_path ) );
            if ( ! empty( $filetype['ext'] ) ) {
                $file_ext = strtoupper( $filetype['ext'] );
            }
        }
        $size_formatted = $file_size ? size_format( $file_size, 1 ) : '';

        $download_url = $file_id ? rp_resource_hub_download_url( get_the_ID() ) : '';
        $is_member    = rp_resource_hub_is_member_only( get_the_ID() );
        $can_download = ! $is_member || current_user_can( 'read_member_resources' );

        // Taxonomies
        $contributing_orgs = get_the_terms( get_the_ID(), 'contributing_org' );
        $hazard_types      = get_the_terms( get_the_ID(), 'hazard_type' );
        $resource_cats     = get_the_terms( get_the_ID(), 'resource_category' );
        $audiences         = get_the_terms( get_the_ID(), 'target_audience' );
        $takeaways         = get_post_meta( get_the_ID(), '_rp_key_takeaways', true );
        ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class( 'rp-resource-article rp-page-shell' ); ?>>
            
            <header class="rp-resource-header">
                <p class="rp-resource-eyebrow"><?php esc_html_e( 'Partner Resource', 'resilient-hub' ); ?></p>
                <h1 class="rp-resource-title"><?php the_title(); ?></h1>
            </header>

            <div class="rp-resource-grid-container">
                
                <!-- Main/Left Column -->
                <div class="rp-resource-col-main">
                    <div class="entry-content rp-resource-description">
                        <?php the_content(); ?>
                    </div>

                    <?php if ( ! empty( $takeaways ) ) : ?>
                        <div class="rp-resource-section rp-key-takeaways">
                            <h3 class="rp-section-title"><?php esc_html_e( 'Key Takeaways', 'resilient-hub' ); ?></h3>
                            <div class="rp-section-content">
                                <?php echo wp_kses_post( wpautop( $takeaways ) ); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ( ! is_wp_error( $audiences ) && ! empty( $audiences ) ) : ?>
                        <div class="rp-resource-section rp-target-audiences">
                            <h3 class="rp-section-title"><?php esc_html_e( 'Target Audience', 'resilient-hub' ); ?></h3>
                            <div class="rp-tags-list">
                                <?php foreach ( $audiences as $audience ) : ?>
                                    <span class="rp-tag rp-tag-audience"><?php echo esc_html( $audience->name ); ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Download Section -->
                    <div class="rp-resource-section rp-download-section">
                        <?php 
                        $is_web_app = get_post_meta( get_the_ID(), '_rp_is_web_app', true ) || has_term( 'Web Application', 'resource_format', get_the_ID() );
                        if ( $is_web_app && $can_download ) : 
                            $web_app_url = rp_resource_hub_get_web_app_url( get_the_ID() );
                            if ( $web_app_url ) :
                        ?>
                                <a class="rp-button-download-large" href="<?php echo esc_url( $web_app_url ); ?>" target="_blank">
                                    <span class="rp-btn-icon">🚀</span>
                                    <span class="rp-btn-text">
                                        <strong><?php esc_html_e( 'Launch', 'resilient-hub' ); ?></strong>
                                    </span>
                                </a>
                            <?php elseif ( $download_url ) : ?>
                                <a class="rp-button-download-large" href="<?php echo esc_url( $download_url ); ?>">
                                    <span class="rp-btn-icon">🚀</span>
                                    <span class="rp-btn-text">
                                        <strong><?php esc_html_e( 'Launch', 'resilient-hub' ); ?></strong>
                                    </span>
                                </a>
                            <?php else : ?>
                                <p class="rp-no-file"><?php esc_html_e( 'Web App is still processing or unavailable.', 'resilient-hub' ); ?></p>
                            <?php endif; ?>
                        <?php elseif ( $file_id ) : ?>
                            <?php if ( $can_download ) : ?>
                                <a class="rp-button-download-large" href="<?php echo esc_url( $download_url ); ?>">
                                    <span class="rp-btn-icon">📥</span>
                                    <span class="rp-btn-text">
                                        <strong><?php esc_html_e( 'Download Document', 'resilient-hub' ); ?></strong>
                                        <?php if ( $file_ext || $size_formatted ) : ?>
                                            <span class="rp-btn-meta">
                                                <?php echo esc_html( sprintf( '%s%s', $file_ext, $size_formatted ? ' - ' . $size_formatted : '' ) ); ?>
                                            </span>
                                        <?php endif; ?>
                                    </span>
                                </a>
                            <?php else : ?>
                                <div class="rp-download-locked">
                                    <span class="rp-lock-icon">🔒</span>
                                    <div class="rp-lock-text">
                                        <strong><?php esc_html_e( 'Member-Only Resource', 'resilient-hub' ); ?></strong>
                                        <p><?php esc_html_e( 'Please log in with an authorized account to access this document.', 'resilient-hub' ); ?></p>
                                    </div>
                                    <a class="rp-button" href="<?php echo esc_url( home_url( '/portal-entry/' ) ); ?>"><?php esc_html_e( 'Log In / Register', 'resilient-hub' ); ?></a>
                                </div>
                            <?php endif; ?>
                        <?php else : ?>
                            <p class="rp-no-file"><?php esc_html_e( 'No download file attached.', 'resilient-hub' ); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Sidebar/Right Column -->
                <aside class="rp-resource-col-sidebar">
                    <div class="rp-metadata-card">
                        <div class="rp-metadata-card-header">
                            <h4><?php esc_html_e( 'Resource Details', 'resilient-hub' ); ?></h4>
                        </div>
                        <div class="rp-metadata-card-body">
                            <div class="rp-meta-item">
                                <span class="rp-meta-label"><?php esc_html_e( 'Contributing Organization', 'resilient-hub' ); ?></span>
                                <span class="rp-meta-value">
                                    <?php
                                    if ( ! is_wp_error( $contributing_orgs ) && ! empty( $contributing_orgs ) ) {
                                        echo esc_html( implode( ', ', wp_list_pluck( $contributing_orgs, 'name' ) ) );
                                    } else {
                                        esc_html_e( 'N/A', 'resilient-hub' );
                                    }
                                    ?>
                                </span>
                            </div>

                            <div class="rp-meta-item">
                                <span class="rp-meta-label"><?php esc_html_e( 'Hazard Type', 'resilient-hub' ); ?></span>
                                <span class="rp-meta-value font-semibold">
                                    <?php
                                    if ( ! is_wp_error( $hazard_types ) && ! empty( $hazard_types ) ) {
                                        echo esc_html( implode( ', ', wp_list_pluck( $hazard_types, 'name' ) ) );
                                    } else {
                                        esc_html_e( 'N/A', 'resilient-hub' );
                                    }
                                    ?>
                                </span>
                            </div>

                            <div class="rp-meta-item">
                                <span class="rp-meta-label"><?php esc_html_e( 'Resource Category', 'resilient-hub' ); ?></span>
                                <span class="rp-meta-value">
                                    <?php
                                    if ( ! is_wp_error( $resource_cats ) && ! empty( $resource_cats ) ) {
                                        echo esc_html( implode( ', ', wp_list_pluck( $resource_cats, 'name' ) ) );
                                    } else {
                                        esc_html_e( 'N/A', 'resilient-hub' );
                                    }
                                    ?>
                                </span>
                            </div>

                            <div class="rp-meta-item">
                                <span class="rp-meta-label"><?php esc_html_e( 'Publication Date', 'resilient-hub' ); ?></span>
                                <span class="rp-meta-value"><?php echo esc_html( get_the_date() ); ?></span>
                            </div>
                        </div>
                    </div>
                </aside>

            </div>

            <!-- Related Resources Section -->
            <?php
            $term_ids = array();
            $taxonomies_to_check = array( 'resource_category', 'hazard_type', 'target_audience' );
            foreach ( $taxonomies_to_check as $tax ) {
                $terms = get_the_terms( get_the_ID(), $tax );
                if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
                    $term_ids[ $tax ] = wp_list_pluck( $terms, 'term_id' );
                }
            }

            $tax_query = array( 'relation' => 'OR' );
            foreach ( $term_ids as $tax => $ids ) {
                $tax_query[] = array(
                    'taxonomy' => $tax,
                    'field'    => 'term_id',
                    'terms'    => $ids,
                );
            }

            if ( ! current_user_can( 'read_member_resources' ) ) {
                $member_term = get_term_by( 'name', 'Member Only', 'resource_visibility' );
                if ( $member_term ) {
                    $tax_query[] = array(
                        'taxonomy' => 'resource_visibility',
                        'field'    => 'term_id',
                        'terms'    => array( absint( $member_term->term_id ) ),
                        'operator' => 'NOT IN',
                    );
                }
            }

            $related_args = array(
                'post_type'      => array( 'accord_library', 'partner_resources' ),
                'post_status'    => 'publish',
                'posts_per_page' => 3,
                'post__not_in'   => array( get_the_ID() ),
            );

            if ( count( $tax_query ) > 1 ) {
                $related_args['tax_query'] = $tax_query;
            }

            $related_query = new WP_Query( $related_args );

            if ( $related_query->post_count < 3 ) {
                $exclude_ids = array_merge( array( get_the_ID() ), wp_list_pluck( $related_query->posts, 'ID' ) );
                $needed = 3 - $related_query->post_count;
                
                $fallback_args = array(
                    'post_type'      => array( 'accord_library', 'partner_resources' ),
                    'post_status'    => 'publish',
                    'posts_per_page' => $needed,
                    'post__not_in'   => $exclude_ids,
                );
                
                if ( ! current_user_can( 'read_member_resources' ) ) {
                    $member_term = get_term_by( 'name', 'Member Only', 'resource_visibility' );
                    if ( $member_term ) {
                        $fallback_args['tax_query'] = array(
                            array(
                                'taxonomy' => 'resource_visibility',
                                'field'    => 'term_id',
                                'terms'    => array( absint( $member_term->term_id ) ),
                                'operator' => 'NOT IN',
                            )
                        );
                    }
                }
                
                $fallback_query = new WP_Query( $fallback_args );
                $combined_posts = array_merge( $related_query->posts, $fallback_query->posts );
                $related_query->posts = $combined_posts;
                $related_query->post_count = count( $combined_posts );
            }

            if ( $related_query->have_posts() ) :
                ?>
                <section class="rp-related-resources-section">
                    <h2 class="rp-related-title"><?php esc_html_e( 'Related Resources', 'resilient-hub' ); ?></h2>
                    <div class="rp-related-grid">
                        <?php
                        while ( $related_query->have_posts() ) :
                            $related_query->the_post();
                            $rel_file_id = absint( get_post_meta( get_the_ID(), '_rp_resource_file_id', true ) );
                            $rel_is_web_app = get_post_meta( get_the_ID(), '_rp_is_web_app', true ) || has_term( 'Web Application', 'resource_format', get_the_ID() );
                            $rel_download_url = $rel_file_id ? rp_resource_hub_download_url( get_the_ID() ) : '';
                            $rel_is_member = rp_resource_hub_is_member_only( get_the_ID() );
                            $rel_can_download = ! $rel_is_member || current_user_can( 'read_member_resources' );
                            ?>
                            <article class="rp-resource-card">
                                <p class="rp-resource-type">
                                    <?php echo esc_html( 'accord_library' === get_post_type() ? __( 'ACCORD Library', 'rp-resource-hub' ) : __( 'Partner Resource', 'rp-resource-hub' ) ); ?>
                                </p>
                                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <div class="rp-resource-meta"><?php echo esc_html( get_the_date() ); ?></div>
                                <?php the_excerpt(); ?>
                                <?php if ( $rel_is_web_app && $rel_can_download ) : ?>
                                    <?php 
                                    $rel_web_app_url = rp_resource_hub_get_web_app_url( get_the_ID() ); 
                                    $rel_btn_url = $rel_web_app_url ? $rel_web_app_url : $rel_download_url;
                                    $rel_target = $rel_web_app_url ? ' target="_blank"' : '';
                                    if ( $rel_btn_url ) :
                                    ?>
                                        <a class="rp-button rp-resource-download" href="<?php echo esc_url( $rel_btn_url ); ?>"<?php echo $rel_target; ?>><?php esc_html_e( 'Launch', 'rp-resource-hub' ); ?></a>
                                    <?php endif; ?>
                                <?php elseif ( $rel_download_url && $rel_can_download ) : ?>
                                    <a class="rp-button rp-resource-download" href="<?php echo esc_url( $rel_download_url ); ?>"><?php esc_html_e( 'Download', 'rp-resource-hub' ); ?></a>
                                <?php elseif ( $rel_is_member ) : ?>
                                    <span class="rp-resource-locked"><?php esc_html_e( 'Member-only', 'rp-resource-hub' ); ?></span>
                                <?php endif; ?>
                            </article>
                            <?php
                        endwhile;
                        wp_reset_postdata();
                        ?>
                    </div>
                </section>
            <?php endif; ?>

        </article>

        <?php
    endwhile;
    ?>
</main>

<?php
get_footer();
