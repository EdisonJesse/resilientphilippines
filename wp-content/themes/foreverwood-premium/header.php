<?php
/**
 * The header template file.
 * @package ForeverWood
 * @since ForeverWood 1.0.0
*/
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<?php global $foreverwood_options;
foreach ($foreverwood_options as $value) {
	if (isset($value['id']) && get_option( $value['id'] ) === FALSE && isset($value['std'])) {
		${$value['id']} = $value['std'];
	}
	elseif (isset($value['id'])) { ${$value['id']} = get_option( $value['id'] ); }
} ?>
  <meta charset="<?php bloginfo( 'charset' ); ?>" /> 
  <meta name="viewport" content="width=device-width" />  
<?php if ( ! function_exists( '_wp_render_title_tag' ) ) { ?><title><?php wp_title( '|', true, 'right' ); ?></title><?php } ?>
  <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">  
  <!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
	<![endif]-->
<?php if ($foreverwood_favicon_url != ''){ ?>
	<link rel="shortcut icon" href="<?php echo esc_url($foreverwood_favicon_url); ?>" />
<?php } ?>
<?php wp_head(); ?>
<?php if ($foreverwood_own_javascript_header != ''){ ?>
<?php echo stripslashes_deep($foreverwood_own_javascript_header); ?>
<?php } ?>   
</head>
 
<body <?php body_class(); ?> id="wrapper">
<div id="container-boxed"> 
<div id="container-boxed-inner">
<header id="wrapper-header">
<?php if ( !is_page_template('template-landing-page.php') ) { ?>
<?php if ( has_nav_menu( 'top-navigation' ) ) {  ?>
  <div class="top-navigation-wrapper">
    <div class="top-navigation">
<a href="https://resilientphilippines.com/purse/"><p class="don-image-link-wrapper" style="float:left">VOLUNTEER</p></a><a href="https://resilientphilippines.com/purse/" style="float:right;"><p class="vol-image-link-wrapper">DONATE NOW</p></a>
<?php wp_nav_menu( array( 'menu_id'=>'top-nav', 'theme_location'=>'top-navigation', 'link_before'=>'<i class="', 'link_after'=>'" aria-hidden="true"></i>' ) ); ?> 
    </div>
<?php }} ?>
  
  <div class="header-content-wrapper">
    <div class="header-content">
      <div class="title-box">
<?php if ( $foreverwood_logo_url == '' ) { ?>
        <p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></p>
<?php } else { ?>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img class="header-logo" src="<?php echo esc_url($foreverwood_logo_url); ?>" alt="<?php bloginfo( 'name' ); ?>" /></a>
<?php } ?>
      </div>
<?php if ( !is_page_template('template-landing-page.php') ) { ?>
<?php if ( $foreverwood_header_layout == 'Wide' ) { ?>
      <div class="menu-box">
<?php wp_nav_menu( array( 'menu_id'=>'nav', 'theme_location'=>'main-navigation' ) ); ?>
      </div>
<?php }} ?>
    </div>
  </div>
<?php if ( !is_page_template('template-landing-page.php') ) { ?>
<?php if ( $foreverwood_header_layout != 'Wide' ) { ?>
  <div class="menu-panel-container">
  <div class="menu-panel-wrapper">
    <div class="menu-panel">
<?php wp_nav_menu( array( 'menu_id'=>'main-nav', 'theme_location'=>'main-navigation' ) ); ?>
    </div>
  </div>
  </div>
<?php }} ?>

<?php if ( is_home() || is_front_page() ) { ?>
<?php if ( dynamic_sidebar( 'sidebar-6' ) ) : else : ?>
<?php if ( get_header_image() != '' && $foreverwood_display_header_image != 'Everywhere except Homepage' ) { ?>
  <div class="header-image">
    <img class="header-img" src="<?php header_image(); ?>" alt="<?php bloginfo( 'name' ); ?>" />
    <div class="header-image-container">
    <div class="header-image-text-wrapper">
      <div class="header-image-text">
<?php if ( $foreverwood_header_image_headline != '' ) { ?>
        <p class="header-image-headline"><?php echo stripslashes_deep($foreverwood_header_image_headline); ?></p>
<?php } if ( $foreverwood_header_image_link_url != '' && $foreverwood_header_image_link_text != '' ) { ?>
        <p class="header-image-link-wrapper"><a class="header-image-link" href="<?php echo esc_url($foreverwood_header_image_link_url); ?>"><?php echo esc_attr($foreverwood_header_image_link_text); ?></a></p>
<?php } ?>
      </div>
    </div>
    </div>
  </div>
<?php } ?>
<?php endif; ?>
<?php } else { ?>
<?php if ( get_header_image() != '' && $foreverwood_display_header_image != 'Only on Homepage' ) { ?>
  <div class="header-image">
    <img class="header-img" src="<?php header_image(); ?>" alt="<?php bloginfo( 'name' ); ?>" />
  </div>
<?php }} ?>
</header> <!-- end of wrapper-header -->