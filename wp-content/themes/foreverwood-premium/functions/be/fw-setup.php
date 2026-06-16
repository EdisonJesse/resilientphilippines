<?php
/**
 * Framework setup.
 * @package ForeverWood
 * @since ForeverWood 1.0.0
*/

function foreverwood_bar_menu() {
	global $wp_admin_bar;
	if ( !is_super_admin() || !is_admin_bar_showing() )
		return;
		$admin_dir = get_admin_url();
		
	$wp_admin_bar->add_menu( array(
	'id' => 'custom_menu',
	'title' => __( 'Theme Options', 'foreverwood' ),
	'href' => $admin_dir .'admin.php?page=fw-options.php',
	'meta' => array('title' => 'ForeverWood Setup', 'class' => 'foreverwoodpanel') ) );
}
add_action('admin_bar_menu', 'foreverwood_bar_menu', '1000');
?>