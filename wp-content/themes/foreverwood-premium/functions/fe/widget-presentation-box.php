<?php
/**
 * Plugin Name: ForeverWood Box-Presentation Widget
 * Description: Displays a box with your custom image and text.
 * Author: Tomas Toman	
 * Version: 1.0
*/

add_action('widgets_init', function() { register_widget("foreverwood_presentation_box"); });
class foreverwood_presentation_box extends WP_Widget {
	function __construct() {
		$widget_ops = array('classname' => 'homepage-presentation-box', 'description' => __('Displays a box with your custom image and text.', 'foreverwood') );
		$control_ops = array('width' => 200, 'height' => 400);
		parent::__construct('presentationbox', __('ForeverWood Box-Presentation', 'foreverwood'), $widget_ops, $control_ops);
	}
	function form($instance) {
		// outputs the options form on admin
    if ( $instance ) {
			$image = esc_attr( $instance[ 'image' ] );
		}
		else {
			$image = __( '', 'foreverwood' );
		}
    
    if ( $instance ) {
			$title = esc_attr( $instance[ 'title' ] );
		}
		else {
			$title = __( '', 'foreverwood' );
		} 

	  if ( $instance ) {
			$subtitle = esc_attr( $instance[ 'subtitle' ] );
		}
		else {
			$subtitle = __( '', 'foreverwood' );
		} 

		if ( $instance ) {
			$content = esc_attr( $instance[ 'content' ] );
		}
		else {
			$content = __( '', 'foreverwood' );
    } ?>
<!-- Image -->
<p>
	<label for="<?php echo $this->get_field_id('image'); ?>">
		<?php _e('Image:', 'foreverwood'); ?>
	</label>
	<input class="widefat" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('image'); ?>" type="text" value="<?php echo $image; ?>" />
<p style="font-size: 10px; color: #999; margin: 0; padding: 0px;">
	<?php _e('Insert here the full URL address of your image.', 'foreverwood'); ?>
</p>
</p>
<!-- Title -->
<p>
	<label for="<?php echo $this->get_field_id('title'); ?>">
		<?php _e('Title:', 'foreverwood'); ?>
	</label>
	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
</p>
<!-- Subtitle -->
<p>
	<label for="<?php echo $this->get_field_id('subtitle'); ?>">
		<?php _e('Subtitle:', 'foreverwood'); ?>
	</label>
  <input class="widefat" id="<?php echo $this->get_field_id('subtitle'); ?>" name="<?php echo $this->get_field_name('subtitle'); ?>" type="text" value="<?php echo $subtitle; ?>" />
</p> 
<!-- Content -->
<p>
	<label for="<?php echo $this->get_field_id('content'); ?>">
		<?php _e('Text:', 'foreverwood'); ?>
	</label>
  <textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('content'); ?>" name="<?php echo $this->get_field_name('content'); ?>"><?php echo $content; ?></textarea>
</p> 
<?php } 

function update($new_instance, $old_instance) {
		// processes widget options to be saved
		$instance = $old_instance;
    $instance['image'] = sanitize_text_field($new_instance['image']);
    $instance['title'] = sanitize_text_field($new_instance['title']);
		$instance['subtitle'] = sanitize_text_field($new_instance['subtitle']);
		$instance['content'] = $new_instance['content'];
	return $instance;
	}

function widget($args, $instance) {
		// outputs the subtitle of the widget
		 extract( $args );
      $image = apply_filters('widget_image', $instance['image']);
      $title = apply_filters('widget_title', $instance['title']);
			$subtitle = apply_filters('widget_subtitle', $instance['subtitle']);
			$content = apply_filters('widget_content', $instance['content']); ?>
<?php echo $before_widget; ?>
      <div class="presentation-box">
<?php if ( $image != '' ) { ?>
        <img src="<?php echo $image; ?>" alt="<?php echo $title; ?>" />
<?php } ?>
<?php if ( $title != '' || $subtitle != '' ) { ?>
        <div class="presentation-headline-wrapper">
          <p class="presentation-box-headline"><?php echo $title; ?></p>
<?php if ( $subtitle != '' ) { ?>
          <p class="presentation-box-subtitle"><?php echo $subtitle; ?></p>
<?php } ?>
        </div>
<?php } ?>
<?php if ( $content != '' ) { ?>
        <div class="presentation-content-wrapper">
          <p><?php echo $content; ?></p>
        </div>
<?php } ?>
      </div>
<?php echo $after_widget; ?>
<?php
	}
}
?>