<?php
/**
 * Template Name: Submit Post or Story
 *
 * @package ResilientHub
 */

get_header();
?>
<main id="primary">
	<section class="rp-page-hero">
		<div class="rp-page-shell">
			<p class="rp-eyebrow"><?php esc_html_e( 'Contributor panel', 'resilient-hub' ); ?></p>
			<h1 class="rp-page-title"><?php the_title(); ?></h1>
		</div>
	</section>
	<section class="rp-page-content">
		<div class="rp-page-shell">
			<div class="entry-content">
				<?php
				$page_content = '';
				while ( have_posts() ) :
					the_post();
					$page_content = get_the_content();
					the_content();
				endwhile;

				if ( false === strpos( $page_content, '[rp_submit_post_form' ) ) {
					echo do_shortcode( '[rp_submit_post_form]' );
				}
				?>
			</div>
		</div>
	</section>
</main>
<?php
get_footer();
