<?php
/**
 * The archive template file.
 * @package ForeverWood
 * @since ForeverWood 1.0.0
*/
get_header(); ?>
<div id="wrapper-content">
<?php if ( have_posts() ) : ?>
  <div class="container">
  <div id="main-content">
    <div class="content-headline">
      <h1 class="entry-headline"><?php
					if ( is_day() ) :
						printf( __( 'Daily Archive: %s', 'foreverwood' ), '<span>' . get_the_date() . '</span>' );
					elseif ( is_month() ) :
						printf( __( 'Monthly Archive: %s', 'foreverwood' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'foreverwood' ) ) . '</span>' );
					elseif ( is_year() ) :
						printf( __( 'Yearly Archive: %s', 'foreverwood' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'foreverwood' ) ) . '</span>' );
					else :
						_e( 'Archive', 'foreverwood' );
					endif;
				?></h1>
<?php foreverwood_get_breadcrumb(); ?>
    </div>
    <div id="content"<?php if ($foreverwood_post_entry_format == 'Grid - Masonry') { ?> class="js-masonry"<?php } ?>> 
<?php while (have_posts()) : the_post(); ?>      
<?php if ($foreverwood_post_entry_format == 'Grid - Masonry') {
get_template_part( 'content', 'grid' ); } else {
get_template_part( 'content', 'archives' ); } ?>
<?php endwhile; endif; ?>
    </div> <!-- end of content -->
<?php foreverwood_content_nav( 'nav-below' ); ?>
  </div>
<?php if ($foreverwood_display_sidebar_archives != 'Hide') { ?>
<?php get_sidebar(); ?>
<?php } ?>
  </div>
</div>     <!-- end of wrapper-content -->
<?php get_footer(); ?>