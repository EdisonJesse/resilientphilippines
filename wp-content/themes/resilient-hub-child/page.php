<?php
/**
 * Clean default page template.
 *
 * @package ResilientHub
 */

get_header();
?>
<main id="primary">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<section class="rp-page-hero">
			<div class="rp-page-shell">
				<p class="rp-eyebrow"><?php esc_html_e( 'Resilient Philippines', 'resilient-hub' ); ?></p>
				<h1 class="rp-page-title"><?php the_title(); ?></h1>
			</div>
		</section>
		<section class="rp-page-content">
			<div class="rp-page-shell">
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
			</div>
		</section>
		<?php
	endwhile;
	?>
</main>
<?php
get_footer();
