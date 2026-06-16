<?php
/**
 * Modern hub homepage.
 *
 * @package ResilientHub
 */

get_header();
$hero_url = rp_child_upload_url( '2020/07/A2C-Cagsao-mangroves-Jes-Aznar-6.jpg' );
?>
<main id="primary">
	<section class="rp-hero">
		<img src="<?php echo esc_url( $hero_url ); ?>" alt="<?php esc_attr_e( 'Mangrove restoration as community resilience work', 'resilient-hub' ); ?>" loading="eager">
		<div class="rp-section-inner rp-hero-content">
			<div class="rp-hero-copy">
				<p class="rp-eyebrow"><?php esc_html_e( 'Knowledge. Coordination. Community resilience.', 'resilient-hub' ); ?></p>
				<h1><?php esc_html_e( 'Resilience Hub', 'resilient-hub' ); ?></h1>
				<p><?php esc_html_e( 'A shared platform for ACCORD knowledge products, partner resources, practical tools, and learning materials for disaster risk reduction and humanitarian action.', 'resilient-hub' ); ?></p>
				<div class="rp-hero-actions">
					<a class="rp-button" href="<?php echo esc_url( home_url( '/resource-hub/' ) ); ?>"><?php esc_html_e( 'Explore Resources', 'resilient-hub' ); ?></a>
					<a class="rp-button rp-button-secondary" href="<?php echo esc_url( home_url( '/submit-resource/' ) ); ?>"><?php esc_html_e( 'Submit a Resource', 'resilient-hub' ); ?></a>
				</div>
			</div>
		</div>
	</section>

	<section class="rp-section rp-section-white">
		<div class="rp-section-inner">
			<div class="rp-section-header">
				<div>
					<p class="rp-eyebrow"><?php esc_html_e( 'Active projects', 'resilient-hub' ); ?></p>
					<h2><?php esc_html_e( 'Coordinated work for safer communities', 'resilient-hub' ); ?></h2>
					<p><?php esc_html_e( 'Priority program areas are presented as clear pathways into practical tools, partner learning, and field-tested approaches.', 'resilient-hub' ); ?></p>
				</div>
			</div>
			<div class="rp-card-grid">
				<article class="rp-card rp-project-card">
					<p class="rp-resource-type"><?php esc_html_e( 'Preparedness', 'resilient-hub' ); ?></p>
					<h3><?php esc_html_e( 'Community-Based DRRM', 'resilient-hub' ); ?></h3>
					<p><?php esc_html_e( 'Guides, training references, and local planning tools that help communities reduce disaster risk before crises escalate.', 'resilient-hub' ); ?></p>
				</article>
				<article class="rp-card rp-project-card">
					<p class="rp-resource-type"><?php esc_html_e( 'Learning', 'resilient-hub' ); ?></p>
					<h3><?php esc_html_e( 'Humanitarian Learning Library', 'resilient-hub' ); ?></h3>
					<p><?php esc_html_e( 'ACCORD knowledge products, manuals, case studies, and learning resources organized for fast field use.', 'resilient-hub' ); ?></p>
				</article>
				<article class="rp-card rp-project-card">
					<p class="rp-resource-type"><?php esc_html_e( 'Collaboration', 'resilient-hub' ); ?></p>
					<h3><?php esc_html_e( 'Partner Resource Repository', 'resilient-hub' ); ?></h3>
					<p><?php esc_html_e( 'A moderated shared repository for partner-submitted tools, reports, maps, and practical resources.', 'resilient-hub' ); ?></p>
				</article>
			</div>
		</div>
	</section>

	<section class="rp-section">
		<div class="rp-section-inner">
			<div class="rp-section-header">
				<div>
					<p class="rp-eyebrow"><?php esc_html_e( 'Latest stories', 'resilient-hub' ); ?></p>
					<h2><?php esc_html_e( 'Updates from resilience work across the country', 'resilient-hub' ); ?></h2>
				</div>
			</div>
			<div class="rp-card-grid">
				<?php
				$latest_posts = new WP_Query(
					array(
						'post_type'           => 'post',
						'post_status'         => 'publish',
						'posts_per_page'      => 3,
						'ignore_sticky_posts' => true,
						'no_found_rows'       => true,
					)
				);

				if ( $latest_posts->have_posts() ) :
					while ( $latest_posts->have_posts() ) :
						$latest_posts->the_post();
						?>
						<article class="rp-card rp-story-card">
							<?php if ( has_post_thumbnail() ) : ?>
								<a href="<?php the_permalink(); ?>" class="rp-card-image"><?php the_post_thumbnail( 'medium_large' ); ?></a>
							<?php endif; ?>
							<div class="rp-resource-meta"><?php echo esc_html( get_the_date() ); ?></div>
							<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<?php the_excerpt(); ?>
						</article>
						<?php
					endwhile;
					wp_reset_postdata();
				else :
					?>
					<article class="rp-card">
						<h3><?php esc_html_e( 'Stories will appear here after posts are published.', 'resilient-hub' ); ?></h3>
						<p><?php esc_html_e( 'Publish updates, field notes, or learning stories to populate this section automatically.', 'resilient-hub' ); ?></p>
					</article>
					<?php
				endif;
				?>
			</div>
		</div>
	</section>

	<section class="rp-section rp-section-white">
		<div class="rp-section-inner">
			<div class="rp-section-header">
				<div>
					<p class="rp-eyebrow"><?php esc_html_e( 'Key publications', 'resilient-hub' ); ?></p>
					<h2><?php esc_html_e( 'Field-ready resources, curated for quick access', 'resilient-hub' ); ?></h2>
					<p><?php esc_html_e( 'Browse by theme, hazard, audience, and contributing organization without heavy page-builder dependencies.', 'resilient-hub' ); ?></p>
				</div>
				<a class="rp-button rp-button-secondary" href="<?php echo esc_url( home_url( '/resource-hub/' ) ); ?>"><?php esc_html_e( 'Open Catalog', 'resilient-hub' ); ?></a>
			</div>
			<?php echo do_shortcode( '[rp_resource_catalog limit="6" filters="false"]' ); ?>
		</div>
	</section>
</main>
<?php
get_footer();
