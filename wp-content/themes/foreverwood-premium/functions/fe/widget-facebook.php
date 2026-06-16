<?php
/**
 * Plugin Name: ForeverWood Facebook Like Box Widget
 * Description: Displays the Facebook Like Box.
 * Author: Tomas Toman	
 * Version: 1.1
*/

add_action('widgets_init', function() { register_widget("foreverwood_facebook"); });
class foreverwood_facebook extends WP_Widget {
	function __construct() {
		$widget_ops = array('classname' => 'facebook-like-box', 'description' => __('Displays Facebook Like Box.', 'foreverwood') );
		$control_ops = array('width' => 200, 'height' => 400);
		parent::__construct('foreverwoodhead', __('ForeverWood Facebook Like Box Widget', 'foreverwood'), $widget_ops, $control_ops);
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
			$likeurl = esc_attr( $instance[ 'likeurl' ] );
		}
		else {
			$likeurl = __( '', 'foreverwood' );
		} 

		if ( $instance ) {
			$showfaces = esc_attr( $instance[ 'showfaces' ] );
		}
		else {
			$showfaces = __( '', 'foreverwood' );
    }
      
    if ( $instance ) {
			$showstream = esc_attr( $instance[ 'showstream' ] );
		}
		else {
			$showstream = __( '', 'foreverwood' );       
		} 
    
		if ( $instance ) {
			$boxheight = esc_attr( $instance[ 'boxheight' ] );
		}
		else {
			$boxheight = __( '', 'foreverwood' );
		}
    
		if ( $instance ) {
			$boxwidth = esc_attr( $instance[ 'boxwidth' ] );
		}
		else {
			$boxwidth = __( '300', 'foreverwood' );
		} ?>
<!-- Title -->
<p>
	<label for="<?php echo $this->get_field_id('title'); ?>">
		<?php _e('Title:', 'foreverwood'); ?>
	</label>
	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
</p>
<!-- Page URL -->
<p>
	<label for="<?php echo $this->get_field_id('likeurl'); ?>">
		<?php _e('Facebook Page URL:', 'foreverwood'); ?>
	</label>
	<input class="widefat" id="<?php echo $this->get_field_id('likeurl'); ?>" name="<?php echo $this->get_field_name('likeurl'); ?>" type="text" value="<?php echo $likeurl; ?>" />
<p style="font-size: 10px; color: #999; margin: 0; padding: 0px;">
	<?php _e('Insert here the full URL of your Facebook Page that will be liked (example: http://www.facebook.com/FacebookDevelopers).', 'foreverwood'); ?>
</p>
</p>
<!-- Height -->
<p>
	<label for="<?php echo $this->get_field_id('boxheight'); ?>">
		<?php _e('Like Box Height: (optional)', 'foreverwood'); ?>
	</label>
	<input class="widefat" id="<?php echo $this->get_field_id('boxheight'); ?>" name="<?php echo $this->get_field_name('boxheight'); ?>" type="text" value="<?php echo $boxheight; ?>" />
<p style="font-size: 10px; color: #999; margin: 0; padding: 0px;">
	<?php _e('Insert here the height of Like Box. The value is in pixels, but in the field please write ONLY NUMBERS (without symbol "px")!', 'foreverwood'); ?>
</p>
</p>
<!-- Width -->
<p>
	<label for="<?php echo $this->get_field_id('boxwidth'); ?>">
		<?php _e('Like Box Width: (optional)', 'foreverwood'); ?>
	</label>
	<input class="widefat" id="<?php echo $this->get_field_id('boxwidth'); ?>" name="<?php echo $this->get_field_name('boxwidth'); ?>" type="text" value="<?php echo $boxwidth; ?>" />
<p style="font-size: 10px; color: #999; margin: 0; padding: 0px;">
	<?php _e('Insert here the width of Like Box. The value is in pixels, but in the field please write ONLY NUMBERS (without symbol "px") between 180 - 500!', 'foreverwood'); ?>
</p>
</p>
<!-- Show Faces -->
<p>
	<label for="<?php echo $this->get_field_id('showfaces'); ?>">
		<?php _e('Show Faces:', 'foreverwood'); ?>
	</label>
  <select class="widefat" id="<?php echo $this->get_field_id('showfaces'); ?>" name="<?php echo $this->get_field_name('showfaces'); ?>">
   <option value="true">display</option>
   <option value="false">hide</option>
 </select>
<p style="font-size: 10px; color: #999; margin: 0; padding: 0px;">
	<?php _e('Specify whether to display profile photos of people who like the page.', 'foreverwood'); ?>
</p>
</p>
<!-- Show Stream -->
<p>
	<label for="<?php echo $this->get_field_id('showstream'); ?>">
		<?php _e('Show Stream:', 'foreverwood'); ?>
	</label>
  <select class="widefat" id="<?php echo $this->get_field_id('showstream'); ?>" name="<?php echo $this->get_field_name('showstream'); ?>">
   <option value="true">display</option>
   <option value="false">hide</option>
 </select>
<p style="font-size: 10px; color: #999; margin: 0; padding: 0px;">
	<?php _e('Specify whether to display a stream of the latest posts by the Page.', 'foreverwood'); ?>
</p>
</p>
<?php 

	} 

function update($new_instance, $old_instance) {
		// processes widget options to be saved
		$instance = $old_instance;
    $instance['title'] = sanitize_text_field($new_instance['title']);
		$instance['likeurl'] = sanitize_text_field($new_instance['likeurl']);
		$instance['showfaces'] = $new_instance['showfaces'];
    $instance['showstream'] = $new_instance['showstream'];
		$instance['boxheight'] = $new_instance['boxheight'];
    $instance['boxwidth'] = $new_instance['boxwidth'];
	return $instance;
	}

function widget($args, $instance) {
		// outputs the content of the widget
		 extract( $args );
      $title = apply_filters('widget_title', $instance['title']);
			$likeurl = apply_filters('widget_likeurl', $instance['likeurl']);
			$showfaces = apply_filters('widget_showfaces', $instance['showfaces']);
      $showstream = apply_filters('widget_showstream', $instance['showstream']);
			$boxheight = apply_filters('widget_boxheight', $instance['boxheight']);
      $boxwidth = apply_filters('widget_boxwidth', $instance['boxwidth']); ?>
<?php if ($title=="") $title = "We are on Facebook";
if ($boxwidth=="") $boxwidth = "300"; ?>
<?php echo $before_widget; ?>
<?php if ( $title )
		echo $before_title . $title . $after_title;
    echo "<div class='fb-like-box-wrapper'><div class='fb-like-box' data-href='$likeurl' data-height='$boxheight' data-width='$boxwidth' data-colorscheme='light' data-show-faces='$showfaces' data-header='false' data-stream='$showstream' data-show-border='false'></div></div>";
	 	echo $after_widget; ?>
<?php
	}
}
?>