<?php
/**
 * Template Name: Full Screen (Page Builder)
 * The template file for creating full screen pages using the Page Builder.
 * @package ForeverWood
 * @since ForeverWood 1.0.0
*/
get_header(); ?>
<div id="wrapper-content">
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
  <div class="container"> 
  <div id="main-content">
    <div id="content"> 
      <div class="content-headline">
        <h1 class="entry-headline"><?php the_title(); ?></h1>
<?php foreverwood_get_breadcrumb(); ?>
      </div>
      <div class="entry-content">
<?php the_content(); ?>
      </div>
<?php endwhile; endif; ?>
      <div id="comments"></div>
    </div> <!-- end of content -->
  </div>
  </div>
</div>     <!-- end of wrapper-content -->
<?php get_footer(); ?>