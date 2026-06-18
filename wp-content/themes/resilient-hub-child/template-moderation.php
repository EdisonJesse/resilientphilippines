<?php
/**
 * Template Name: Moderation Dashboard
 *
 * A front-end moderation page for administrators and editors
 * to quickly review and approve pending submissions.
 *
 * @package ResilientHub
 */

// Gate check: User must be logged in and have administrative or editorial capabilities
if ( ! is_user_logged_in() ) {
    wp_safe_redirect( add_query_arg( 'redirect_to', esc_url( home_url( '/moderation-dashboard/' ) ), home_url( '/portal-entry/' ) ) );
    exit;
}

if ( ! rp_child_current_user_can_access_moderation() ) {
    wp_safe_redirect( home_url( '/' ) );
    exit;
}

get_header();
?>

<main id="primary" class="rp-moderation-dashboard-main">
    <section class="rp-dashboard-hero">
        <div class="rp-page-shell">
            <p class="rp-eyebrow"><?php esc_html_e( 'Admin Panel', 'resilient-hub' ); ?></p>
            <h1 class="rp-page-title"><?php esc_html_e( 'Moderation Dashboard', 'resilient-hub' ); ?></h1>
        </div>
    </section>
    <div class="rp-dashboard-body">
    <div class="rp-page-shell">
        <header class="rp-dashboard-header">
            <p class="rp-dashboard-subtitle"><?php esc_html_e( 'Review and approve submitted resources, reports, stories, and gallery photos.', 'resilient-hub' ); ?></p>
        </header>

        <div class="rp-moderation-container">
            <?php
            // Query pending partner resources, situation reports, accord library products, posts, and gallery photos
            $pending_query = new WP_Query( array(
                'post_type'      => array( 'partner_resources', 'rp_sitrep', 'accord_library', 'post', 'rp_gallery_photo' ),
                'post_status'    => 'pending',
                'posts_per_page' => -1, // Retrieve all pending
                'orderby'        => 'date',
                'order'          => 'ASC',
            ) );

            if ( $pending_query->have_posts() ) :
                ?>
                <div class="rp-table-responsive">
                    <table class="rp-moderation-table">
                        <thead>
                            <tr>
                                <th><?php esc_html_e( 'Title', 'resilient-hub' ); ?></th>
                                <th><?php esc_html_e( 'Type', 'resilient-hub' ); ?></th>
                                <th><?php esc_html_e( 'Submitting Contributor & Organization', 'resilient-hub' ); ?></th>
                                <th><?php esc_html_e( 'Submission Date', 'resilient-hub' ); ?></th>
                                <th class="rp-table-actions"><?php esc_html_e( 'Actions', 'resilient-hub' ); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ( $pending_query->have_posts() ) :
                                $pending_query->the_post();

                                // Get contributor display name
                                $author_name = get_the_author_meta( 'display_name' );
                                if ( ! $author_name ) {
                                    $author_name = get_the_author_meta( 'user_login' );
                                }

                                $post_id = get_the_ID();
                                $post_type = get_post_type();

                                // Get contributing organization terms
                                $orgs = get_the_terms( $post_id, 'contributing_org' );
                                $org_list = ( ! is_wp_error( $orgs ) && ! empty( $orgs ) ) ? implode( ', ', wp_list_pluck( $orgs, 'name' ) ) : '';

                                // Combine contributor & org
                                $submitting_info = $author_name;
                                if ( ! empty( $org_list ) ) {
                                    $submitting_info .= sprintf( ' (%s)', $org_list );
                                }

                                $can_moderate = rp_child_current_user_can_moderate_post( $post_id );
                                $edit_url     = current_user_can( 'edit_post', $post_id ) ? get_edit_post_link( $post_id, 'raw' ) : '';

                                // Nonces for AJAX moderation actions
                                $approve_nonce = wp_create_nonce( 'rp_approve_resource_' . $post_id );
                                $reject_nonce  = wp_create_nonce( 'rp_reject_resource_' . $post_id );
                                ?>
                                <tr id="rp-pending-row-<?php echo absint( $post_id ); ?>">
                                    <td>
                                        <div class="rp-moderation-title-wrapper">
                                            <?php if ( 'rp_gallery_photo' === $post_type && has_post_thumbnail( $post_id ) ) : ?>
                                                <div class="rp-moderation-thumb" style="margin-bottom:8px; max-width:140px;">
                                                    <?php echo get_the_post_thumbnail( $post_id, 'thumbnail', array( 'style' => 'width:100%; height:auto; border-radius:6px; display:block;' ) ); ?>
                                                </div>
                                            <?php endif; ?>
                                            <strong><?php the_title(); ?></strong>
                                            <div class="rp-moderation-links">
                                                <a href="<?php echo esc_url( get_preview_post_link( $post_id ) ); ?>" target="_blank"><?php esc_html_e( 'Preview details', 'resilient-hub' ); ?></a>
                                                <?php if ( 'rp_gallery_photo' === $post_type ) : ?>
                                                    <?php $sharepoint_url = get_post_meta( $post_id, '_rp_gallery_sharepoint_web_url', true ); ?>
                                                    <?php if ( $sharepoint_url ) : ?>
                                                        <span aria-hidden="true"> | </span><a href="<?php echo esc_url( $sharepoint_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'SharePoint original', 'resilient-hub' ); ?></a>
                                                    <?php endif; ?>
                                                    <div class="rp-gallery-review-meta" style="font-size:12px; color:var(--rp-color-muted); margin-top:6px;">
                                                        <?php
                                                        $gallery_meta = array_filter( array(
                                                            get_post_meta( $post_id, '_rp_gallery_location', true ),
                                                            get_post_meta( $post_id, '_rp_gallery_photo_date', true ),
                                                            get_post_meta( $post_id, '_rp_gallery_project_program', true ),
                                                        ) );
                                                        echo esc_html( implode( ' | ', $gallery_meta ) );
                                                        $gallery_tags = get_the_terms( $post_id, 'rp_gallery_tag' );
                                                        if ( ! is_wp_error( $gallery_tags ) && ! empty( $gallery_tags ) ) {
                                                            echo esc_html( ' | Tags: ' . implode( ', ', wp_list_pluck( $gallery_tags, 'name' ) ) );
                                                        }
                                                        ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="rp-moderation-badge rp-badge-<?php echo esc_attr( $post_type ); ?>" style="padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; display: inline-block; background: <?php
                                            if ( 'rp_sitrep' === $post_type ) {
                                                echo '#fef3c7; color: #d97706;';
                                            } elseif ( 'accord_library' === $post_type ) {
                                                echo '#f0fdf4; color: #16a34a;';
                                            } elseif ( 'post' === $post_type ) {
                                                echo '#f3e8ff; color: #7c3aed;';
                                            } elseif ( 'rp_gallery_photo' === $post_type ) {
                                                echo '#ecfeff; color: #0e7490;';
                                            } else {
                                                echo '#dbeafe; color: #2563eb;';
                                            }
                                        ?>">
                                            <?php 
                                                if ( 'rp_sitrep' === $post_type ) {
                                                    echo esc_html__( 'Situation Report', 'resilient-hub' );
                                                } elseif ( 'accord_library' === $post_type ) {
                                                    echo esc_html__( 'ACCORD Library', 'resilient-hub' );
                                                } elseif ( 'post' === $post_type ) {
                                                    echo esc_html__( 'Post / Story', 'resilient-hub' );
                                                } elseif ( 'rp_gallery_photo' === $post_type ) {
                                                    echo esc_html__( 'Gallery Photo', 'resilient-hub' );
                                                } else {
                                                    echo esc_html__( 'Partner Resource', 'resilient-hub' );
                                                }
                                            ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="rp-moderation-submitting-user"><?php echo esc_html( $submitting_info ); ?></span>
                                    </td>
                                    <td>
                                        <span class="rp-moderation-date"><?php echo esc_html( get_the_date() ); ?></span>
                                    </td>
                                    <td class="rp-table-actions">
                                        <?php if ( $can_moderate ) : ?>
                                            <div class="rp-moderation-actions">
                                                <?php if ( $edit_url ) : ?>
                                                    <a class="rp-button rp-button-secondary rp-edit-btn" href="<?php echo esc_url( $edit_url ); ?>"><?php esc_html_e( 'Edit', 'resilient-hub' ); ?></a>
                                                <?php endif; ?>
                                                <button class="rp-button rp-approve-btn" data-post-id="<?php echo absint( $post_id ); ?>" data-nonce="<?php echo esc_attr( $approve_nonce ); ?>">
                                                    <?php esc_html_e( 'Approve', 'resilient-hub' ); ?>
                                                </button>
                                                <button class="rp-button rp-reject-btn" data-post-id="<?php echo absint( $post_id ); ?>" data-nonce="<?php echo esc_attr( $reject_nonce ); ?>">
                                                    <?php esc_html_e( 'Reject', 'resilient-hub' ); ?>
                                                </button>
                                            </div>
                                        <?php else : ?>
                                            <span aria-label="<?php esc_attr_e( 'No actions available', 'resilient-hub' ); ?>">&mdash;</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php
                            endwhile;
                            wp_reset_postdata();
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php else : ?>
                <div class="rp-moderation-empty">
                    <p><?php esc_html_e( 'No pending submissions to review.', 'resilient-hub' ); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    </div>
</main>

<?php
get_footer();
