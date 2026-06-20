<?php
/**
 * Template Name: Resource Hub Catalog
 *
 * @package ResilientHub
 */

get_header();
?>
<main id="primary">
	<section class="rp-page-hero">
		<div class="rp-page-shell">
			<p class="rp-eyebrow"><?php esc_html_e( 'Resilience Hub', 'resilient-hub' ); ?></p>
			<h1 class="rp-page-title"><?php the_title(); ?></h1>
		</div>
	</section>
	<section class="rp-page-content">
		<div class="rp-page-shell">
			<?php
			$page_content = '';
			while ( have_posts() ) :
				the_post();
				$page_content = get_the_content();
			endwhile;

			$stripped_content = trim( wp_strip_all_tags( strip_shortcodes( $page_content ) ) );

			if ( empty( $stripped_content ) ) {
				?>
				<div class="rp-seo-intro entry-content" style="margin-bottom: 2.5rem; max-width: 800px; line-height: 1.6;">
					<h2 style="font-size: 1.5rem; margin-top: 0; color: #176b52;"><?php esc_html_e( 'Disaster Risk Reduction Resources in the Philippines', 'resilient-hub' ); ?></h2>
					<p><?php esc_html_e( 'The ACCORD Resource Hub is a curated directory of community-based disaster risk reduction and management (CBDRRM) tools, humanitarian learning materials, and partner resources. Since 2012, ACCORD and its partners have compiled practical guides, manuals, and reports to support local governments, civil society organizations, and communities in implementing effective disaster preparedness and resilience building.', 'resilient-hub' ); ?></p>
				</div>
				<?php
				echo do_shortcode( '[rp_resource_catalog limit="12" filters="true"]' );
			} else {
				echo '<div class="entry-content">';
				the_content();
				echo '</div>';

				if ( false === strpos( $page_content, '[rp_resource_catalog' ) ) {
					echo do_shortcode( '[rp_resource_catalog limit="12" filters="true"]' );
				}
			}
			?>
		</div>
	</section>
</main>
<?php
get_footer();
