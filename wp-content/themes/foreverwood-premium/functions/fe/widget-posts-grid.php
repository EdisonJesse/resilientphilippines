<?php
/**
 * Plugin Name: ForeverWood Posts-Grid Widget
 * Description: Displays the latest posts from the selected category as a grid.
 * Author: Tomas Toman	
 * Version: 1.0
*/

add_action('widgets_init', function() { register_widget("foreverwood_homepage_grid"); });
class foreverwood_homepage_grid extends WP_Widget {
	function __construct() {
		$widget_ops = array('classname' => 'homepage-grid-posts', 'description' => __('Displays the latest posts from the selected category as a grid.', 'foreverwood') );
		$control_ops = array('width' => 200, 'height' => 400);
		parent::__construct('foreverwoodgrid', __('ForeverWood Posts-Grid', 'foreverwood'), $widget_ops, $control_ops);
	}
	function form($instance) {
		// outputs the options form on admin
    if ( $instance ) {
			$title = esc_attr( $instance[ 'title' ] );
		}
		else {
			$title = __( '', 'foreverwood' );
		} 

	  if ( $instance ) {
			$category = esc_attr( $instance[ 'category' ] );
		}
		else {
			$category = __( '', 'foreverwood' );
		} 

		if ( $instance ) {
			$numberposts = esc_attr( $instance[ 'numberposts' ] );
		}
		else {
			$numberposts = __( '5', 'foreverwood' );
    } ?>
<!-- Title -->
<p>
	<label for="<?php echo $this->get_field_id('title'); ?>">
		<?php _e('Title:', 'foreverwood'); ?>
	</label>
	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
</p>
<!-- Category -->
<p>
	<label for="<?php echo $this->get_field_id('category'); ?>">
		<?php _e('Category:', 'foreverwood'); ?>
	</label>
<?php wp_dropdown_categories( array(
    'name' => $this->get_field_name('category'),
    'id' => $this->get_field_id('category'),
    'class' => 'widefat',
    'selected' => $instance["category"],
    'show_option_none' => '- not selected -'
) ); ?>
<p style="font-size: 10px; color: #999; margin: 0; padding: 0px;">
	<?php _e('Select a category of posts.', 'foreverwood'); ?>
</p>
</p>
<!-- Number of posts -->
<p>
	<label for="<?php echo $this->get_field_id('numberposts'); ?>">
		<?php _e('Number of posts:', 'foreverwood'); ?>
	</label>
	<input class="widefat" id="<?php echo $this->get_field_id('numberposts'); ?>" name="<?php echo $this->get_field_name('numberposts'); ?>" type="text" value="<?php echo $numberposts; ?>" />
<p style="font-size: 10px; color: #999; margin: 0; padding: 0px;">
	<?php _e('Insert here the number of latest posts from the selected category which you want to display.', 'foreverwood'); ?>
</p>
</p>
<?php } 

function update($new_instance, $old_instance) {
		// processes widget options to be saved
		$instance = $old_instance;
    $instance['title'] = sanitize_text_field($new_instance['title']);
		$instance['category'] = $new_instance['category'];
		$instance['numberposts'] = sanitize_text_field($new_instance['numberposts']);
	return $instance;
	}

function widget($args, $instance) {
wp_enqueue_script( 'foreverwood-imagesloaded', get_template_directory_uri() . '/js/imagesloaded.js', array(), '3.1.8', true );
wp_enqueue_script( 'foreverwood-masonry', get_template_directory_uri() . '/js/masonry.js', array(), '3.1.5', true );
if ( !is_rtl() ) {
wp_enqueue_script( 'foreverwood-masonry-settings', get_template_directory_uri() . '/js/masonry-settings.js', array(), '1.0', true ); } else {
wp_enqueue_script( 'foreverwood-masonry-settings-rtl', get_template_directory_uri() . '/js/masonry-settings-rtl.js', array(), '1.0', true ); } 
		// outputs the content of the widget
		 extract( $args );
      $title = apply_filters('widget_title', $instance['title']);
			$category = apply_filters('widget_category', $instance['category']);
			$numberposts = apply_filters('widget_numberposts', $instance['numberposts']); ?>
<?php echo $before_widget; ?>
    <section class="home-grid-posts">
<?php $args1 = array(
  'cat' => $category,
  'showposts' => $numberposts,
	'post_type' => 'post',
	'post_status' => 'publish'
);
$my_query = new WP_Query( $args1 ); ?> 
                
      <h2 class="entry-headline"><?php echo $title; ?></h2>
      <div class="js-masonry">
<?php if ($my_query->have_posts()) : while ($my_query->have_posts()) : $my_query->the_post(); ?>            
<?php get_template_part( 'content', 'grid' ); ?>
<?php endwhile; endif; ?>
<?php wp_reset_query(); ?>
      </div>
    </section>
<?php echo $after_widget; ?>
<?php
	}
}
?>