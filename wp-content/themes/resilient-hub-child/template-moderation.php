<?php
/**
 * Template Name: Moderation Dashboard
 *
 * A front-end moderation page for administrators and editors
 * to quickly review and approve pending partner resource submissions.
 *
 * @package ResilientHub
 */

// Gate check: User must be logged in and have administrative or editorial capabilities
if ( ! is_user_logged_in() ) {
    wp_safe_redirect( add_query_arg( 'redirect_to', esc_url( home_url( '/moderation-dashboard/' ) ), home_url( '/portal-entry/' ) ) );
    exit;
}

if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'publish_posts' ) && ! current_user_can( 'publish_partner_resources' ) ) {
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
            <p class="rp-dashboard-subtitle"><?php esc_html_e( 'Review and approve resources submitted by partner organizations.', 'resilient-hub' ); ?></p>
        </header>

        <div class="rp-moderation-container">
            <?php
            // Query pending partner resources and situation reports
            $pending_query = new WP_Query( array(
                'post_type'      => array( 'partner_resources', 'rp_sitrep' ),
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

                                // Get contributing organization terms
                                $orgs = get_the_terms( get_the_ID(), 'contributing_org' );
                                $org_list = ( ! is_wp_error( $orgs ) && ! empty( $orgs ) ) ? implode( ', ', wp_list_pluck( $orgs, 'name' ) ) : '';

                                // Combine contributor & org
                                $submitting_info = $author_name;
                                if ( ! empty( $org_list ) ) {
                                    $submitting_info .= sprintf( ' (%s)', $org_list );
                                }

                                // Nonce for AJAX approval
                                $approve_nonce = wp_create_nonce( 'rp_approve_resource_' . get_the_ID() );
                                ?>
                                <tr id="rp-pending-row-<?php the_ID(); ?>">
                                    <td>
                                        <div class="rp-moderation-title-wrapper">
                                            <strong><?php the_title(); ?></strong>
                                            <div class="rp-moderation-links">
                                                <a href="<?php echo esc_url( get_preview_post_link( get_the_ID() ) ); ?>" target="_blank"><?php esc_html_e( 'Preview details', 'resilient-hub' ); ?></a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="rp-moderation-badge rp-badge-<?php echo esc_attr( get_post_type() ); ?>" style="padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; display: inline-block; background: <?php echo 'rp_sitrep' === get_post_type() ? '#fef3c7; color: #d97706;' : '#dbeafe; color: #2563eb;'; ?>">
                                            <?php echo 'rp_sitrep' === get_post_type() ? esc_html__( 'Situation Report', 'resilient-hub' ) : esc_html__( 'Partner Resource', 'resilient-hub' ); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="rp-moderation-submitting-user"><?php echo esc_html( $submitting_info ); ?></span>
                                    </td>
                                    <td>
                                        <span class="rp-moderation-date"><?php echo esc_html( get_the_date() ); ?></span>
                                    </td>
                                    <td class="rp-table-actions">
                                        <button class="rp-button rp-approve-btn" data-post-id="<?php the_ID(); ?>" data-nonce="<?php echo esc_attr( $approve_nonce ); ?>">
                                            <?php esc_html_e( 'Approve', 'resilient-hub' ); ?>
                                        </button>
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
                    <p><?php esc_html_e( 'No pending resources to review.', 'resilient-hub' ); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    </div>
</main>

<?php
get_footer();
