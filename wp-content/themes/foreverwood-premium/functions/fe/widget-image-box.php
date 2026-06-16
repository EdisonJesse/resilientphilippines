<?php
/**
 * Plugin Name: ForeverWood Box-Image Widget
 * Description: Displays a full screen box with your custom background image, text and link.
 * Author: Tomas Toman	
 * Version: 1.0
*/

add_action('widgets_init', function() { register_widget("foreverwood_image_box"); });
class foreverwood_image_box extends WP_Widget {
	function __construct() {
		$widget_ops = array('classname' => 'homepage-image-box', 'description' => __('Displays a full screen box with your custom background image, text and link.', 'foreverwood') );
		$control_ops = array('width' => 200, 'height' => 400);
		parent::__construct('imagebox', __('ForeverWood Box-Image', 'foreverwood'), $widget_ops, $control_ops);
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
			$height = esc_attr( $instance[ 'height' ] );
		}
		else {
			$height = __( '450', 'foreverwood' );
		}
    
    if ( $instance ) {
			$scrolling = esc_attr( $instance[ 'scrolling' ] );
		}
		else {
			$scrolling = __( '', 'foreverwood' );
		}
    
    if ( $instance ) {
			$size = esc_attr( $instance[ 'size' ] );
		}
		else {
			$size = __( '', 'foreverwood' );
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
			$link_url = esc_attr( $instance[ 'link_url' ] );
		}
		else {
			$link_url = __( '', 'foreverwood' );
    }
    
    if ( $instance ) {
			$link_text = esc_attr( $instance[ 'link_text' ] );
		}
		else {
			$link_text = __( '', 'foreverwood' );
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
<!-- Height -->
<p>
	<label for="<?php echo $this->get_field_id('height'); ?>">
		<?php _e('Height:', 'foreverwood'); ?>
	</label>
	<input class="widefat" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo $height; ?>" />
<p style="font-size: 10px; color: #999; margin: 0; padding: 0px;">
	<?php _e('Set here the height of the image box (in pixels).', 'foreverwood'); ?>
</p>
</p>
<!-- Background size -->
<p>
	<label for="<?php echo $this->get_field_id('size'); ?>">
		<?php _e('Background image size:', 'foreverwood'); ?>
	</label>
  <select class="widefat" id="<?php echo $this->get_field_id('size'); ?>" name="<?php echo $this->get_field_name('size'); ?>">
   <option value="auto"><?php _e('Auto', 'foreverwood'); ?></option>
   <option value="cover"><?php _e('Cover', 'foreverwood'); ?></option>
 </select>
</p>
<!-- Scrolling -->
<p>
	<label for="<?php echo $this->get_field_id('scrolling'); ?>">
		<?php _e('Scrolling:', 'foreverwood'); ?>
	</label>
  <select class="widefat" id="<?php echo $this->get_field_id('scrolling'); ?>" name="<?php echo $this->get_field_name('scrolling'); ?>">
   <option value="fixed"><?php _e('Fixed', 'foreverwood'); ?></option>
   <option value="scroll"><?php _e('Scrolled', 'foreverwood'); ?></option>
 </select>
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
<!-- Button URL -->
<p>
	<label for="<?php echo $this->get_field_id('link_url'); ?>">
		<?php _e('Button link:', 'foreverwood'); ?>
	</label>
	<input class="widefat" id="<?php echo $this->get_field_id('link_url'); ?>" name="<?php echo $this->get_field_name('link_url'); ?>" type="text" value="<?php echo $link_url; ?>" />
<p style="font-size: 10px; color: #999; margin: 0; padding: 0px;">
	<?php _e('Insert here the full URL address of your custom link.', 'foreverwood'); ?>
</p>
</p>
<!-- Button text -->
<p>
	<label for="<?php echo $this->get_field_id('link_text'); ?>">
		<?php _e('Button text:', 'foreverwood'); ?>
	</label>
	<input class="widefat" id="<?php echo $this->get_field_id('link_text'); ?>" name="<?php echo $this->get_field_name('link_text'); ?>" type="text" value="<?php echo $link_text; ?>" />
<p style="font-size: 10px; color: #999; margin: 0; padding: 0px;">
	<?php _e('Insert here a text which will be displayed in the button.', 'foreverwood'); ?>
</p>
</p>
<?php } 

function update($new_instance, $old_instance) {
		// processes widget options to be saved
		$instance = $old_instance;
    $instance['image'] = sanitize_text_field($new_instance['image']);
    $instance['height'] = sanitize_text_field($new_instance['height']);
    $instance['size'] = $new_instance['size'];
    $instance['scrolling'] = $new_instance['scrolling'];
    $instance['title'] = sanitize_text_field($new_instance['title']);
		$instance['subtitle'] = sanitize_text_field($new_instance['subtitle']);
		$instance['link_url'] = sanitize_text_field($new_instance['link_url']);
    $instance['link_text'] = sanitize_text_field($new_instance['link_text']);
	return $instance;
	}

function widget($args, $instance) {
		// outputs the subtitle of the widget
		 extract( $args );
      $image = apply_filters('widget_image', $instance['image']);
      $height = apply_filters('widget_height', $instance['height']);
      $size = apply_filters('widget_size', $instance['size']);
      $scrolling = apply_filters('widget_scrolling', $instance['scrolling']);
      $title = apply_filters('widget_title', $instance['title']);
			$subtitle = apply_filters('widget_subtitle', $instance['subtitle']);
			$link_url = apply_filters('widget_link_url', $instance['link_url']);
      $link_text = apply_filters('widget_link_text', $instance['link_text']); ?>
<?php echo $before_widget; ?>
  <div class="box-image" style="background-attachment: <?php echo $scrolling; ?>; background-image: url(<?php echo $image; ?>); background-size: <?php echo $size; ?>; height: <?php echo $height; ?>px;">
    <div class="box-image-text-wrapper">
      <div class="box-image-text">
<?php if ( $title != ''  ) { ?>
        <p class="box-image-headline"><?php echo $title; ?></p>
<?php } ?>
<?php if ( $subtitle != ''  ) { ?>
        <p class="box-image-subtitle"><?php echo $subtitle; ?></p>
<?php } ?>
<?php if ( $link_url != '' || $link_text != '' ) { ?>
        <p class="box-image-link-wrapper"><a class="box-image-link" href="<?php echo $link_url; ?>"><?php echo $link_text; ?></a></p>
<?php } ?>
      </div>
    </div>
  </div>
<?php echo $after_widget; ?>
<?php
	}
}
?>