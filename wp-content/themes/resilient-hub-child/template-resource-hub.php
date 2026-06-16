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
			<p class="rp-eyebrow"><?php esc_html_e( 'Humanitarian Resource Hub', 'resilient-hub' ); ?></p>
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
				if ( trim( $page_content ) ) {
					echo '<div class="entry-content">';
					the_content();
					echo '</div>';
				}
			endwhile;

			if ( false === strpos( $page_content, '[rp_resource_catalog' ) ) {
				echo do_shortcode( '[rp_resource_catalog limit="12" filters="true"]' );
			}
			?>
		</div>
	</section>
</main>
<?php
get_footer();
