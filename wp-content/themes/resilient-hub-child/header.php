<?php
/**
 * Modern site header.
 *
 * @package ResilientHub
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'resilient-hub' ); ?></a>
<div id="container-boxed">
<div id="container-boxed-inner">
<header class="rp-site-header" role="banner">
	<div class="rp-header-inner">
		<a class="rp-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<div class="rp-brand-logo-wrap">
				<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/accord-logo.png' ); ?>" alt="<?php esc_attr_e( 'ACCORD Resilience Hub Logo', 'resilient-hub' ); ?>" class="rp-brand-logo">
			</div>
		</a>

		<button class="rp-menu-toggle" type="button" aria-controls="rp-primary-navigation" aria-expanded="false">
			<span class="rp-menu-toggle-bars" aria-hidden="true"></span>
			<span class="screen-reader-text"><?php esc_html_e( 'Toggle navigation', 'resilient-hub' ); ?></span>
		</button>

		<nav id="rp-primary-navigation" class="rp-nav" aria-label="<?php esc_attr_e( 'Primary navigation', 'resilient-hub' ); ?>">
			<?php
			if ( has_nav_menu( 'main-navigation' ) ) {
				wp_nav_menu(
					array(
						'theme_location' => 'main-navigation',
						'container'      => false,
						'menu_id'        => 'rp-primary-menu',
						'fallback_cb'    => false,
					)
				);
			} else {
				?>
				<ul id="rp-primary-menu">
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'resilient-hub' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/resource-hub/' ) ); ?>"><?php esc_html_e( 'Resources', 'resilient-hub' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/opportunities/' ) ); ?>"><?php esc_html_e( 'Opportunities', 'resilient-hub' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/sitrep-dashboard/' ) ); ?>"><?php esc_html_e( 'Situation Reports', 'resilient-hub' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/tinig/' ) ); ?>"><?php esc_html_e( 'Tinig', 'resilient-hub' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/submit-resource/' ) ); ?>"><?php esc_html_e( 'Submit', 'resilient-hub' ); ?></a></li>
				</ul>
				<?php
			}
			?>
			<a class="rp-button rp-button-donate" href="<?php echo esc_url( home_url( '/donate/' ) ); ?>"><?php esc_html_e( 'Donate', 'resilient-hub' ); ?></a>
			<?php if ( is_user_logged_in() ) : 
				$current_user = wp_get_current_user();
				$first_name   = $current_user->first_name ? $current_user->first_name : $current_user->display_name;
				?>
				<div class="rp-user-menu-container">
					<ul class="rp-user-nav">
						<li class="menu-item-has-children">
							<a href="#" class="rp-user-welcome" onclick="event.preventDefault();">
								<?php printf( esc_html__( 'Welcome, %s', 'resilient-hub' ), esc_html( $first_name ) ); ?>
								<span class="rp-chevron" aria-hidden="true"></span>
							</a>
							<ul class="sub-menu">
							<li><a href="<?php echo esc_url( home_url( '/profile/' ) ); ?>"><?php esc_html_e( 'My Profile & Privacy', 'resilient-hub' ); ?></a></li>
							<li><a href="<?php echo esc_url( home_url( '/my-contributions/' ) ); ?>"><?php esc_html_e( 'My Contributions', 'resilient-hub' ); ?></a></li>
							<li><a href="<?php echo esc_url( home_url( '/submit-resource/' ) ); ?>"><?php esc_html_e( 'Submit a Resource', 'resilient-hub' ); ?></a></li>
							<?php if ( current_user_can( 'publish_posts' ) || current_user_can( 'manage_options' ) ) : ?>
								<li><a href="<?php echo esc_url( home_url( '/submit-post/' ) ); ?>"><?php esc_html_e( 'Submit a Post/Story', 'resilient-hub' ); ?></a></li>
							<?php endif; ?>
							<li><a href="<?php echo esc_url( home_url( '/submit-sitrep/' ) ); ?>"><?php esc_html_e( 'Submit a SitRep', 'resilient-hub' ); ?></a></li>
							<?php 
							$can_moderate = current_user_can( 'manage_options' ) || current_user_can( 'publish_posts' ) || current_user_can( 'publish_partner_resources' ) || current_user_can( 'publish_rp_sitreps' );
							$can_manage_tinig = current_user_can( 'manage_options' ) || current_user_can( 'manage_tinig_cases' );
							$can_manage_jobs = current_user_can( 'manage_options' ) || current_user_can( 'manage_job_applications' );
							$can_manage_bids = current_user_can( 'manage_options' ) || current_user_can( 'manage_bid_submissions' );
							$can_manage_opportunities = current_user_can( 'manage_options' ) || current_user_can( 'manage_opportunities' );
							$can_submit_jobs = $can_manage_opportunities || $can_manage_jobs;
							$can_submit_bids = $can_manage_opportunities || $can_manage_bids;
							$has_admin_links = $can_moderate || $can_manage_tinig || $can_manage_jobs || $can_manage_bids || $can_submit_jobs || $can_submit_bids;
							if ( $has_admin_links ) : ?>
								<li class="rp-dropdown-divider" aria-hidden="true"></li>
								<li class="rp-dropdown-section-label"><?php esc_html_e( 'Admin', 'resilient-hub' ); ?></li>
								<?php if ( $can_moderate ) : ?>
									<li><a href="<?php echo esc_url( home_url( '/moderation-dashboard/' ) ); ?>"><?php esc_html_e( 'Moderation Dashboard', 'resilient-hub' ); ?></a></li>
									<li><a href="<?php echo esc_url( home_url( '/analytics-dashboard/' ) ); ?>"><?php esc_html_e( 'Analytics Dashboard', 'resilient-hub' ); ?></a></li>
								<?php endif; ?>
								<?php if ( $can_manage_tinig ) : ?>
									<li><a href="<?php echo esc_url( home_url( '/tinig-dashboard/' ) ); ?>"><?php esc_html_e( 'Tinig Dashboard', 'resilient-hub' ); ?></a></li>
								<?php endif; ?>
								<?php if ( $can_manage_jobs ) : ?>
									<li><a href="<?php echo esc_url( home_url( '/job-applications-dashboard/' ) ); ?>"><?php esc_html_e( 'Job Applications Dashboard', 'resilient-hub' ); ?></a></li>
								<?php endif; ?>
								<?php if ( $can_manage_bids ) : ?>
									<li><a href="<?php echo esc_url( home_url( '/bid-submissions-dashboard/' ) ); ?>"><?php esc_html_e( 'Bid Submissions Dashboard', 'resilient-hub' ); ?></a></li>
								<?php endif; ?>
								<?php if ( $can_submit_jobs ) : ?>
									<li><a href="<?php echo esc_url( home_url( '/submit-job-opportunity/' ) ); ?>"><?php esc_html_e( 'Submit Job Posting', 'resilient-hub' ); ?></a></li>
								<?php endif; ?>
								<?php if ( $can_submit_bids ) : ?>
									<li><a href="<?php echo esc_url( home_url( '/submit-invitation-to-bid/' ) ); ?>"><?php esc_html_e( 'Submit ITB Posting', 'resilient-hub' ); ?></a></li>
								<?php endif; ?>
								<?php if ( current_user_can( 'manage_options' ) ) : ?>
									<li><a href="<?php echo esc_url( home_url( '/user-management/' ) ); ?>"><?php esc_html_e( 'User Management', 'resilient-hub' ); ?></a></li>
									<li><a href="<?php echo esc_url( admin_url() ); ?>"><?php esc_html_e( 'WP Admin', 'resilient-hub' ); ?></a></li>
								<?php endif; ?>
							<?php endif; ?>
							<li class="rp-dropdown-divider" aria-hidden="true"></li>
							<li><a href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>"><?php esc_html_e( 'Logout', 'resilient-hub' ); ?></a></li>
						</ul>
						</li>
					</ul>
				</div>
			<?php else : ?>
				<a class="rp-button rp-button-login" href="<?php echo esc_url( home_url( '/portal-entry/' ) ); ?>"><?php esc_html_e( 'Login / Register', 'resilient-hub' ); ?></a>
			<?php endif; ?>
		</nav>
	</div>
</header>
