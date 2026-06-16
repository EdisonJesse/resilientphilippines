<?php
/**
 * Template Name: Submit Partner Resource
 *
 * @package ResilientHub
 */

get_header();
?>
<main id="primary">
	<section class="rp-page-hero">
		<div class="rp-page-shell">
			<p class="rp-eyebrow"><?php esc_html_e( 'Partner contribution', 'resilient-hub' ); ?></p>
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

				if ( false === strpos( $page_content, '[rp_partner_upload_form' ) ) {
					echo do_shortcode( '[rp_partner_upload_form]' );
				}
				?>
			</div>
		</div>
	</section>
</main>
<?php
get_footer();
