<?php
/**
 * Template for displaying single opportunity postings.
 *
 * @package ResilientHub
 */

get_header();
?>
<main id="primary" class="rp-single-main rp-opportunity-single-main">
	<?php
	while ( have_posts() ) :
		the_post();
		$type = function_exists( 'rp_opportunities_get_type' ) ? rp_opportunities_get_type( get_the_ID() ) : 'job';
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'rp-single-article rp-opportunity-single-article' ); ?>>
			<header class="rp-single-hero rp-opportunity-single-hero">
				<div class="rp-page-shell">
					<p class="rp-eyebrow"><?php echo esc_html( 'itb' === $type ? __( 'Invitation to Bid', 'resilient-hub' ) : __( 'Job Ad', 'resilient-hub' ) ); ?></p>
					<h1 class="rp-single-title"><?php the_title(); ?></h1>
				</div>
			</header>

			<section class="rp-single-body rp-opportunity-single-body">
				<div class="rp-page-shell">
					<div class="entry-content rp-single-content rp-opportunity-single-content">
						<?php the_content(); ?>
					</div>
				</div>
			</section>
		</article>
		<?php
	endwhile;
	?>
</main>
<?php
get_footer();
