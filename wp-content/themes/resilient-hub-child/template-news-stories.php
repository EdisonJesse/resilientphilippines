<?php
/**
 * Template Name: News & Stories Hub
 *
 * @package ResilientHub
 */

get_header();
?>
<main id="primary">
	<section class="rp-page-hero">
		<div class="rp-page-shell">
			<p class="rp-eyebrow"><?php esc_html_e( 'Updates & Stories', 'resilient-hub' ); ?></p>
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
					echo '<div class="entry-content" style="margin-bottom: 30px;">';
					the_content();
					echo '</div>';
				}
			endwhile;

			if ( false === strpos( $page_content, '[rp_news_catalog' ) ) {
				echo do_shortcode( '[rp_news_catalog limit="12"]' );
			}
			?>
		</div>
	</section>
</main>
<?php
get_footer();
