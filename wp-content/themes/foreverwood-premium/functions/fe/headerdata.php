<?php
/**
 * Headerdata of Theme options.
 * @package ForeverWood
 * @since ForeverWood 1.0.0
*/  
global $foreverwood_options;
foreach ($foreverwood_options as $value) {
	if (isset($value['id']) && get_option( $value['id'] ) === FALSE && isset($value['std'])) {
		${$value['id']} = $value['std'];
	}
	elseif (isset($value['id'])) { ${$value['id']} = get_option( $value['id'] ); }
}

// additional js and css
if(	!is_admin()){
function foreverwood_fonts_include () {
// Google Fonts
$bodyfont = get_theme_mod('foreverwood_body_google_fonts', get_option('foreverwood_body_google_fonts', 'default'));
$headingfont = get_theme_mod('foreverwood_headings_google_fonts', get_option('foreverwood_headings_google_fonts', 'default'));
$headlinefont = get_theme_mod('foreverwood_headline_google_fonts', get_option('foreverwood_headline_google_fonts', 'default'));
$headlineboxfont = get_theme_mod('foreverwood_headline_box_google_fonts', get_option('foreverwood_headline_box_google_fonts', 'default'));
$postentryfont = get_theme_mod('foreverwood_postentry_google_fonts', get_option('foreverwood_postentry_google_fonts', 'default'));
$sidebarfont = get_theme_mod('foreverwood_sidebar_google_fonts', get_option('foreverwood_sidebar_google_fonts', 'default'));
$menufont = get_theme_mod('foreverwood_menu_google_fonts', get_option('foreverwood_menu_google_fonts', 'default'));

$fonturl = "//fonts.googleapis.com/css?family=";
$character_set = "&amp;subset=" . get_theme_mod('foreverwood_character_set', get_option('foreverwood_character_set', 'latin'));

$bodyfonturl = $fonturl.$bodyfont.$character_set;
$headingfonturl = $fonturl.$headingfont.$character_set;
$headlinefonturl = $fonturl.$headlinefont.$character_set;
$headlineboxfonturl = $fonturl.$headlineboxfont.$character_set;
$postentryfonturl = $fonturl.$postentryfont.$character_set;
$sidebarfonturl = $fonturl.$sidebarfont.$character_set;
$menufonturl = $fonturl.$menufont.$character_set;
	// Google Fonts
     if ($bodyfont != 'default' && $bodyfont != ''){
      wp_enqueue_style('foreverwood-google-font1', $bodyfonturl); 
		 }
     if ($headingfont != 'default' && $headingfont != ''){
      wp_enqueue_style('foreverwood-google-font2', $headingfonturl);
		 }
     if ($headlinefont != 'default' && $headlinefont != ''){
      wp_enqueue_style('foreverwood-google-font4', $headlinefonturl); 
		 }
     if ($postentryfont != 'default' && $postentryfont != ''){
      wp_enqueue_style('foreverwood-google-font5', $postentryfonturl); 
		 }
     if ($sidebarfont != 'default' && $sidebarfont != ''){
      wp_enqueue_style('foreverwood-google-font6', $sidebarfonturl);
		 }
     if ($menufont != 'default' && $menufont != ''){
      wp_enqueue_style('foreverwood-google-font8', $menufonturl);
		 }
     if ($headlineboxfont != 'default' && $headlineboxfont != ''){
      wp_enqueue_style('foreverwood-google-font10', $headlineboxfonturl); 
		 }
}
add_action( 'wp_enqueue_scripts', 'foreverwood_fonts_include' );
}

// additional js and css
function foreverwood_css_include () {
    if (get_option('foreverwood_layout_width') == 'Boxed' ){
			wp_enqueue_style('foreverwood-boxed-layout', get_template_directory_uri().'/css/boxed-layout.css');
		}

		if (get_option('foreverwood_css') == 'Blue' ){
			wp_enqueue_style('foreverwood-style-blue', get_template_directory_uri().'/css/colors/blue.css');
		}

		if (get_option('foreverwood_css') == 'Green' ){
			wp_enqueue_style('foreverwood-style-green', get_template_directory_uri().'/css/colors/green.css');
		}

		if (get_option('foreverwood_css') == 'Pink' ){
			wp_enqueue_style('foreverwood-style-pink', get_template_directory_uri().'/css/colors/pink.css');
		}
    
    if (get_option('foreverwood_css') == 'Purple' ){
			wp_enqueue_style('foreverwood-style-purple', get_template_directory_uri().'/css/colors/purple.css');
		}

		if (get_option('foreverwood_css') == 'Red' ){
			wp_enqueue_style('foreverwood-style-red', get_template_directory_uri().'/css/colors/red.css');
		}
    
    if (get_option('foreverwood_css') == 'Turquoise' ){
			wp_enqueue_style('foreverwood-style-turquoise', get_template_directory_uri().'/css/colors/turquoise.css');
		}
}
add_action( 'wp_enqueue_scripts', 'foreverwood_css_include' );

//social scripts
if(	!is_admin() && ($foreverwood_share_buttons_page == '' || $foreverwood_share_buttons_page == 'Display' || $foreverwood_share_buttons_post == '' || $foreverwood_share_buttons_post == 'Display' || $foreverwood_share_buttons_post_entry == '' || $foreverwood_share_buttons_post_entry == 'Display' || $foreverwood_share_buttons_products == '' || $foreverwood_share_buttons_products == 'Display') ) {

function foreverwood_scripts_social() {
$share_buttons_language = get_option('foreverwood_share_buttons_language') . '/all.js#xfbml=1';
$share_buttons_language_url = "//connect.facebook.net/";
$share_buttons_language_full_url = $share_buttons_language_url.$share_buttons_language;
	// Register all javascript files
	wp_enqueue_script('foreverwood_js_google', '//apis.google.com/js/plusone.js', '', '', 'footer');
  if ( get_option('foreverwood_share_buttons_language') != '' ) {
	wp_enqueue_script('foreverwood_js_facebook', $share_buttons_language_full_url, '', '', 'footer'); } else {
  wp_enqueue_script('foreverwood_js_facebook', '//connect.facebook.net/en_US/all.js#xfbml=1', '', '', 'footer'); }
	wp_enqueue_script('foreverwood_js_twitter', '//platform.twitter.com/widgets.js', '', '', 'footer');
	}   
add_action('wp_enqueue_scripts', 'foreverwood_scripts_social');	
}	

// Display Sidebar on Posts/Pages
function foreverwood_display_sidebar() {
    $display_sidebar = get_option('foreverwood_display_sidebar'); 
		if ($display_sidebar == 'Hide') { ?>
		<?php _e('.single .container #main-content, .page .container #main-content, .error404 .container #main-content { width: 100%; }'); ?>
<?php } 
}

// Display Sidebar on Archives
function foreverwood_display_sidebar_archives() {
    $display_sidebar_archives = get_option('foreverwood_display_sidebar_archives'); 
		if ($display_sidebar_archives == 'Hide') { ?>
		<?php _e('.blog .container #main-content, .archive .container #main-content, .search .container #main-content { width: 100%; } .archive #sidebar { display: none; }'); ?>
<?php } 
}

// Header Layout - Wide
function foreverwood_get_header_layout() {
    $header_layout = get_option('foreverwood_header_layout'); 
		if ($header_layout == 'Wide') { ?>
		<?php _e('#wrapper-header .site-title { text-align: left; } #wrapper-header .header-logo { margin-left: 0; } .rtl #wrapper-header .site-title { text-align: right; } @media screen and (max-width: 990px) { html #wrapper #wrapper-header .header-content .site-title, html #wrapper #wrapper-header .header-content .header-logo { margin-bottom: 0 !important; } }'); ?>
<?php } 
}

// Title Box width
function foreverwood_get_page_title_width() {
    $page_title_width = get_option('foreverwood_page_title_width'); 
    $header_layout = get_option('foreverwood_header_layout');
		if ($page_title_width != '' && $header_layout == 'Wide') { ?>
		<?php _e('#wrapper #wrapper-header .title-box { width: '); ?><?php echo $page_title_width ?><?php _e(';}'); ?>
<?php } 
}

// Menu Box width
function foreverwood_get_header_menu_width() {
    $header_menu_width = get_option('foreverwood_header_menu_width'); 
    $header_layout = get_option('foreverwood_header_layout');
		if ($header_menu_width != '' && $header_layout == 'Wide') { ?>
		<?php _e('#wrapper #wrapper-header .menu-box { width: '); ?><?php echo $header_menu_width ?><?php _e(';}'); ?>
<?php } 
}

// Number of Columns in the Grid
function foreverwood_grid_columns() {
    $grid_columns = get_option('foreverwood_grid_columns'); 
		if ($grid_columns == '4') { ?>
		<?php _e('body .grid-entry, body #main-content .js-masonry .sticky { width: 25%; }'); }
    elseif ($grid_columns == '2') { ?>
    <?php _e('body .grid-entry, body #main-content .js-masonry .sticky { width: 50%; }'); ?>
<?php } 
}

// Featured Images Hover Effect
function foreverwood_featured_image_hover() {
    $featured_image_hover = get_option('foreverwood_featured_image_hover'); 
		if ($featured_image_hover == 'Fade') { ?>
		<?php _e('#wrapper .post-entry .attachment-post-thumbnail, #wrapper .post-entry .attachment-square-thumb, #wrapper .grid-entry .attachment-post-thumbnail { -webkit-transition: all 1s ease; -moz-transition: all 1s ease; -o-transition: all 1s ease; -ms-transition: all 1s ease; transition: all 1s ease; } #wrapper .post-entry .attachment-post-thumbnail:hover, #wrapper .post-entry .attachment-square-thumb:hover, #wrapper .grid-entry .attachment-post-thumbnail:hover { opacity: 0.8; filter: alpha(opacity=80); }'); }
    elseif ($featured_image_hover == 'Tilt') { ?>
    <?php _e('#wrapper .post-entry .attachment-post-thumbnail, #wrapper .post-entry .attachment-square-thumb, #wrapper .grid-entry .attachment-post-thumbnail { -webkit-transition: all 1s ease; -moz-transition: all 1s ease; -o-transition: all 1s ease; -ms-transition: all 1s ease; transition: all 1s ease; overflow: hidden; } #wrapper .post-entry .attachment-post-thumbnail:hover, #wrapper .post-entry .attachment-square-thumb:hover, #wrapper .grid-entry .attachment-post-thumbnail:hover { -webkit-transform: rotate(2deg); -moz-transform: rotate(2deg); -o-transform: rotate(2deg); -ms-transform: rotate(2deg); transform: rotate(2deg); }'); }
    elseif ($featured_image_hover == 'Focus') { ?>
    <?php _e('#wrapper .post-entry .attachment-post-thumbnail, #wrapper .post-entry .attachment-square-thumb, #wrapper .grid-entry .attachment-post-thumbnail { -webkit-transition: all 1s ease; -moz-transition: all 1s ease; -o-transition: all 1s ease; -ms-transition: all 1s ease; transition: all 1s ease; overflow: hidden; } #wrapper .post-entry .attachment-post-thumbnail:hover, #wrapper .post-entry .attachment-square-thumb:hover, #wrapper .grid-entry .attachment-post-thumbnail:hover { border-radius: 50%; } '); }
    elseif ($featured_image_hover == 'Shadow') { ?>
    <?php _e('#wrapper .post-entry .attachment-post-thumbnail, #wrapper .post-entry .attachment-square-thumb, #wrapper .grid-entry .attachment-post-thumbnail { -webkit-transition: all 1s ease; -moz-transition: all 1s ease; -o-transition: all 1s ease; -ms-transition: all 1s ease; transition: all 1s ease; } #wrapper .post-entry .attachment-post-thumbnail:hover, #wrapper .post-entry .attachment-square-thumb:hover, #wrapper .grid-entry .attachment-post-thumbnail:hover { -webkit-box-shadow: 0 0 5px #333333; -moz-box-shadow: 0 0 5px #333333; box-shadow: 0 0 5px #333333; }'); ?>
<?php } 
}

// Display Meta Box - post entries styling
function foreverwood_display_meta_post_entry() {
    $display_meta_post_entry = get_option('foreverwood_display_meta_post_entry'); 
		if ($display_meta_post_entry == 'Hide') { ?>
		<?php _e('body #main-content .post-entry .post-entry-headline { margin-bottom: 10px; } .post-entry .video-thumbnail { margin-top: 15px; } .grid-entry .video-thumbnail { margin-top: 10px; }'); ?>
<?php } 
}

// Social Networks Links background color
function foreverwood_get_top_menu_background_color() {
    $top_menu_background_color = get_theme_mod('foreverwood_top_menu_background_color', get_option('foreverwood_top_menu_background_color', '')); 
		if ($top_menu_background_color != '#' && $top_menu_background_color != '') { ?>
		<?php _e('#wrapper .top-navigation-wrapper { background-color: '); ?><?php echo $top_menu_background_color ?><?php _e('; }'); ?>
<?php } 
}

// Social Networks Links border-bottom color
function foreverwood_get_top_menu_border_color() {
    $top_menu_border_color = get_theme_mod('foreverwood_top_menu_border_color', get_option('foreverwood_top_menu_border_color', '')); 
		if ($top_menu_border_color != '#' && $top_menu_border_color != '') { ?>
		<?php _e('#wrapper .top-navigation-wrapper { border-color: '); ?><?php echo $top_menu_border_color ?><?php _e('; }'); ?>
<?php } 
}

// Social Networks Links color
function foreverwood_get_social_icons_color() {
    $social_icons_color = get_theme_mod('foreverwood_social_icons_color', get_option('foreverwood_social_icons_color', '')); 
		if ($social_icons_color != '#' && $social_icons_color != '') { ?>
		<?php _e('#wrapper .top-navigation a, #wrapper .top-navigation a:visited { color: '); ?><?php echo $social_icons_color ?><?php _e('; }'); ?>
<?php } 
}

// Social Networks Links hover color
function foreverwood_get_social_icons_hover_color() {
    $social_icons_hover_color = get_theme_mod('foreverwood_social_icons_hover_color', get_option('foreverwood_social_icons_hover_color', '')); 
		if ($social_icons_hover_color != '#' && $social_icons_hover_color != '') { ?>
		<?php _e('#wrapper .top-navigation ul a:hover, #wrapper .top-navigation ul :hover > a { color: '); ?><?php echo $social_icons_hover_color ?><?php _e('; }'); ?>
<?php } 
}

// Main Menu drop-down submenus background color
function foreverwood_get_main_submenu_background_color() {
    $main_submenu_background_color = get_theme_mod('foreverwood_main_submenu_background_color', get_option('foreverwood_main_submenu_background_color', '')); 
		if ($main_submenu_background_color != '#' && $main_submenu_background_color != '') { ?>
		<?php _e('#wrapper .menu-box ul ul, #wrapper .menu-panel ul ul { background-color: '); ?><?php echo $main_submenu_background_color ?><?php _e('; }'); ?>
<?php } 
}

// Main Menu drop-down submenus text color
function foreverwood_get_main_submenu_text_color() {
    $main_submenu_text_color = get_theme_mod('foreverwood_main_submenu_text_color', get_option('foreverwood_main_submenu_text_color', '')); 
		if ($main_submenu_text_color != '#' && $main_submenu_text_color != '') { ?>
		<?php _e('#wrapper #wrapper-header .menu-box ul ul a, #wrapper #wrapper-header .menu-box ul ul a:visited, #wrapper #wrapper-header .menu-panel ul ul a, #wrapper #wrapper-header .menu-panel ul ul a:visited { color: '); ?><?php echo $main_submenu_text_color ?><?php _e(' !important; }'); ?>
<?php } 
}

// Main Menu drop-down submenus border color
function foreverwood_get_main_submenu_border_color() {
    $main_submenu_border_color = get_theme_mod('foreverwood_main_submenu_border_color', get_option('foreverwood_main_submenu_border_color', '')); 
		if ($main_submenu_border_color != '#' && $main_submenu_border_color != '') { ?>
		<?php _e('#wrapper #wrapper-header .menu-box ul ul, #wrapper #wrapper-header .menu-panel ul ul, #wrapper #wrapper-header .menu-box ul ul li, #wrapper #wrapper-header .menu-panel ul ul li { border-color: '); ?><?php echo $main_submenu_border_color ?><?php _e('; }'); ?>
<?php } 
}

// Main Menu drop-down submenus hover color
function foreverwood_get_main_submenu_hover_color() {
    $main_submenu_hover_color = get_theme_mod('foreverwood_main_submenu_hover_color', get_option('foreverwood_main_submenu_hover_color', '')); 
		if ($main_submenu_hover_color != '#' && $main_submenu_hover_color != '') { ?>
		<?php _e('html #wrapper #wrapper-header .menu-box ul ul a:hover, html #wrapper #wrapper-header .menu-box ul ul :hover > a, html #wrapper #wrapper-header .menu-panel ul ul a:hover, html #wrapper #wrapper-header .menu-panel ul ul :hover > a { background-color: '); ?><?php echo $main_submenu_hover_color ?><?php _e(' !important; }'); ?>
<?php } 
}

// Header Content background color
function foreverwood_get_header_content_background_color() {
    $header_content_background_color = get_theme_mod('foreverwood_header_content_background_color', get_option('foreverwood_header_content_background_color', '')); 
		if ($header_content_background_color != '#' && $header_content_background_color != '') { ?>
		<?php _e('#wrapper #wrapper-header .header-content-wrapper, #wrapper .menu-panel-wrapper { background-color: '); ?><?php echo $header_content_background_color ?><?php _e('; }'); ?>
<?php } 
}

// Header Content border color
function foreverwood_get_header_content_border_color() {
    $header_content_border_color = get_theme_mod('foreverwood_header_content_border_color', get_option('foreverwood_header_content_border_color', '')); 
		if ($header_content_border_color != '#' && $header_content_border_color != '') { ?>
		<?php _e('#wrapper #wrapper-header .header-content-wrapper { border-color: '); ?><?php echo $header_content_border_color ?><?php _e('; }'); ?>
<?php } 
}

// Main Content background color
function foreverwood_get_main_content_background_color() {
    $main_content_background_color = get_theme_mod('foreverwood_main_content_background_color', get_option('foreverwood_main_content_background_color', '')); 
		if ($main_content_background_color != '#' && $main_content_background_color != '') { ?>
		<?php _e('#wrapper #wrapper-content { background-color: '); ?><?php echo $main_content_background_color ?><?php _e('; }'); ?>
<?php } 
}

// Post Author’s Comments background color
function foreverwood_get_author_comments_background_color() {
    $author_comments_background_color = get_theme_mod('foreverwood_author_comments_background_color', get_option('foreverwood_author_comments_background_color', '')); 
		if ($author_comments_background_color != '#' && $author_comments_background_color != '') { ?>
		<?php _e('#wrapper #comments .bypostauthor { background-color: '); ?><?php echo $author_comments_background_color ?><?php _e('; }'); ?>
<?php } 
}

// Sticky Post background color
function foreverwood_get_sticky_post_background_color() {
    $sticky_post_background_color = get_theme_mod('foreverwood_sticky_post_background_color', get_option('foreverwood_sticky_post_background_color', '')); 
		if ($sticky_post_background_color != '#' && $sticky_post_background_color != '') { ?>
		<?php _e('#wrapper .container .sticky, #wrapper #main-content .js-masonry .sticky .grid-entry-inner { background-color: '); ?><?php echo $sticky_post_background_color ?><?php _e('; }'); ?>
<?php } 
}

// ForeverWood Posts-Thumbnail items hover color
function foreverwood_get_posts_thumbnail_background_color() {
    $posts_thumbnail_background_color = get_theme_mod('foreverwood_posts_thumbnail_background_color', get_option('foreverwood_posts_thumbnail_background_color', '')); 
		if ($posts_thumbnail_background_color != '#' && $posts_thumbnail_background_color != '') { ?>
		<?php _e('#wrapper .container .post-entry-thumbnail .thumbnail-hover { background-color: '); ?><?php echo $posts_thumbnail_background_color ?><?php _e('; }'); ?>
<?php } 
}

// Highlighted text background color
function foreverwood_get_highlight_background_color() {
    $highlight_background_color = get_theme_mod('foreverwood_highlight_background_color', get_option('foreverwood_highlight_background_color', '')); 
		if ($highlight_background_color != '#' && $highlight_background_color != '') { ?>
		<?php _e('#wrapper .container .highlight-text { background-color: '); ?><?php echo $highlight_background_color ?><?php _e('; }'); ?>
<?php } 
}

// Sidebar Widget Headlines background color
function foreverwood_get_sidebar_widget_background_color() {
    $sidebar_widget_background_color = get_theme_mod('foreverwood_sidebar_widget_background_color', get_option('foreverwood_sidebar_widget_background_color', '')); 
		if ($sidebar_widget_background_color != '#' && $sidebar_widget_background_color != '') { ?>
		<?php _e('#wrapper .container #sidebar .sidebar-widget .sidebar-headline { background-color: '); ?><?php echo $sidebar_widget_background_color ?><?php _e('; }'); ?>
<?php } 
}

// Footer background color
function foreverwood_get_footer_background_color() {
    $footer_background_color = get_theme_mod('foreverwood_footer_background_color', get_option('foreverwood_footer_background_color', '')); 
		if ($footer_background_color != '#' && $footer_background_color != '') { ?>
		<?php _e('html #wrapper #wrapper-footer { background-color: '); ?><?php echo $footer_background_color ?><?php _e('; }'); ?>
<?php } 
}

// Footer Widget Headlines border color
function foreverwood_get_footer_widget_border_color() {
    $footer_widget_border_color = get_theme_mod('foreverwood_footer_widget_border_color', get_option('foreverwood_footer_widget_border_color', '')); 
		if ($footer_widget_border_color != '#' && $footer_widget_border_color != '') { ?>
		<?php _e('#wrapper #wrapper-footer #footer .footer-widget .footer-headline { border-color: '); ?><?php echo $footer_widget_border_color ?><?php _e('; }'); ?>
<?php } 
}

// Footer Notices background color
function foreverwood_get_footer_notices_background_color() {
    $footer_notices_background_color = get_theme_mod('foreverwood_footer_notices_background_color', get_option('foreverwood_footer_notices_background_color', '')); 
		if ($footer_notices_background_color != '#' && $footer_notices_background_color != '') { ?>
		<?php _e('#wrapper #wrapper-footer .footer-signature { background-color: '); ?><?php echo $footer_notices_background_color ?><?php _e('; }'); ?>
<?php } 
}

// Searchform border color
function foreverwood_get_searchform_border_color() {
    $searchform_border_color = get_theme_mod('foreverwood_searchform_border_color', get_option('foreverwood_searchform_border_color', '')); 
		if ($searchform_border_color != '#' && $searchform_border_color != '') { ?>
		<?php _e('html #wrapper #searchform .searchform-wrapper #s { border-color: '); ?><?php echo $searchform_border_color ?><?php _e(' !important; }'); ?>
<?php } 
}

// Search Button background color
function foreverwood_get_searchform_button_background_color() {
    $searchform_button_background_color = get_theme_mod('foreverwood_searchform_button_background_color', get_option('foreverwood_searchform_button_background_color', '')); 
		if ($searchform_button_background_color != '#' && $searchform_button_background_color != '') { ?>
		<?php _e('#wrapper #searchform .searchform-wrapper .send { background-color: '); ?><?php echo $searchform_button_background_color ?><?php _e('; }'); ?>
<?php } 
}

// Search Button hover color
function foreverwood_get_searchform_button_hover_color() {
    $searchform_button_hover_color = get_theme_mod('foreverwood_searchform_button_hover_color', get_option('foreverwood_searchform_button_hover_color', '')); 
		if ($searchform_button_hover_color != '#' && $searchform_button_hover_color != '') { ?>
		<?php _e('#wrapper #searchform .searchform-wrapper .send:hover { background-color: '); ?><?php echo $searchform_button_hover_color ?><?php _e('; }'); ?>
<?php } 
}

// Form Fields background color
function foreverwood_get_form_fields_background_color() {
    $form_fields_background_color = get_theme_mod('foreverwood_form_fields_background_color', get_option('foreverwood_form_fields_background_color', '')); 
		if ($form_fields_background_color != '#' && $form_fields_background_color != '') { ?>
		<?php _e('#searchform .searchform-wrapper #s, #wrapper input[type="text"], #wrapper input[type="file"], #wrapper input[type="password"], #wrapper input[type="number"], #wrapper input[type="search"], #wrapper input[type="email"], #wrapper input[type="url"], #wrapper textarea, #wrapper select { background-color: '); ?><?php echo $form_fields_background_color ?><?php _e(' !important; }'); ?>
<?php } 
}

// Form Fields border color
function foreverwood_get_form_fields_border_color() {
    $form_fields_border_color = get_theme_mod('foreverwood_form_fields_border_color', get_option('foreverwood_form_fields_border_color', '')); 
		if ($form_fields_border_color != '#' && $form_fields_border_color != '') { ?>
		<?php _e('body #searchform .searchform-wrapper #s, #wrapper input[type="text"], #wrapper input[type="file"], #wrapper input[type="password"], #wrapper input[type="number"], #wrapper input[type="search"], #wrapper input[type="email"], #wrapper input[type="url"], #wrapper textarea, #wrapper select { border-color: '); ?><?php echo $form_fields_border_color ?><?php _e('; }'); ?>
<?php } 
}

// Buttons background color
function foreverwood_get_form_buttons_background_color() {
    $form_buttons_background_color = get_theme_mod('foreverwood_form_buttons_background_color', get_option('foreverwood_form_buttons_background_color', '')); 
		if ($form_buttons_background_color != '#' && $form_buttons_background_color != '') { ?>
		<?php _e('#wrapper input[type="submit"], #wrapper input[type="reset"], #wrapper .custom-button, html #wrapper .container .panel-row-style-foreverwood .origin-widget-button a, #wrapper .header-image .header-image-text .header-image-link, #wrapper .box-image .box-image-text .box-image-link, #wrapper .post-entry .read-more-button, #wrapper .grid-entry .read-more-button { background-color: '); ?><?php echo $form_buttons_background_color ?><?php _e(' !important; }'); ?>
<?php } 
}

// Buttons hover color
function foreverwood_get_form_buttons_hover_color() {
    $form_buttons_hover_color = get_theme_mod('foreverwood_form_buttons_hover_color', get_option('foreverwood_form_buttons_hover_color', '')); 
		if ($form_buttons_hover_color != '#' && $form_buttons_hover_color != '') { ?>
		<?php _e('#wrapper input[type="submit"]:hover, #wrapper input[type="reset"]:hover, #wrapper .custom-button:hover, html #wrapper .container .panel-row-style-foreverwood .origin-widget-button a:hover, #wrapper .header-image .header-image-text .header-image-link:hover, #wrapper .box-image .box-image-text .box-image-link:hover, #wrapper .post-entry .read-more-button:hover, #wrapper .grid-entry .read-more-button:hover { background-color: '); ?><?php echo $form_buttons_hover_color ?><?php _e(' !important; }'); ?>
<?php } 
}

// Table Headers background color
function foreverwood_get_table_headers_background_color() {
    $table_headers_background_color = get_theme_mod('foreverwood_table_headers_background_color', get_option('foreverwood_table_headers_background_color', '')); 
		if ($table_headers_background_color != '#' && $table_headers_background_color != '') { ?>
		<?php _e('#wrapper table th { background-color: '); ?><?php echo $table_headers_background_color ?><?php _e('; }'); ?>
<?php } 
}

// TEXT COLORS AND FONTS
// Body font
function foreverwood_get_body_font() {
    $bodyfont = get_theme_mod('foreverwood_body_google_fonts', get_option('foreverwood_body_google_fonts', 'default')); 
		if ($bodyfont != 'default' && $bodyfont != '') { ?>
		<?php _e('html body, #wrapper blockquote, #wrapper q, #wrapper .container #comments .comment, #wrapper .container #comments .comment time, #wrapper .container #commentform .form-allowed-tags, #wrapper .container #commentform p, #wrapper input, #wrapper button, #wrapper textarea, #wrapper select, #wrapper #main-content .post-meta { font-family: "'); ?><?php echo $bodyfont ?><?php _e('", Arial, Helvetica, sans-serif; }'); ?>
<?php } 
}

// Body color
function foreverwood_get_body_color() {
    $body_color = get_theme_mod('foreverwood_body_color', get_option('foreverwood_body_color', '')); 
		if ($body_color != '#' && $body_color != '') { ?>
		<?php _e('html body, #wrapper blockquote, #wrapper q, #wrapper .container #comments .comment, #wrapper .container #comments .comment time, #wrapper .container #commentform .form-allowed-tags, #wrapper .container #commentform p, #wrapper input, #wrapper button, #wrapper textarea, #wrapper select, #wrapper #main-content .post-meta { color: '); ?><?php echo $body_color ?><?php _e('; }'); ?>
<?php } 
}

// Main Content area font size
function foreverwood_get_body_google_fonts_size() {
    $body_google_fonts_size = get_theme_mod('foreverwood_body_google_fonts_size', get_option('foreverwood_body_google_fonts_size', '14')); 
		if ($body_google_fonts_size != '14' && $body_google_fonts_size != '') { ?>
		<?php _e('body p, body ul, body ol, body li, body dl, body address, body table { font-size: '); ?><?php echo $body_google_fonts_size ?><?php _e('px; }'); ?>
<?php } 
}

// Site title font
function foreverwood_get_headings_google_fonts() {
    $headingfont = get_theme_mod('foreverwood_headings_google_fonts', get_option('foreverwood_headings_google_fonts', 'default')); 
		if ($headingfont != 'default' && $headingfont != '') { ?>
		<?php _e('#wrapper #wrapper-header .site-title { font-family: "'); ?><?php echo $headingfont ?><?php _e('", Arial, Helvetica, sans-serif; }'); ?>
<?php } 
}

// Site title font size
function foreverwood_get_headings_google_fonts_size() {
    $headingfont_size = get_theme_mod('foreverwood_headings_google_fonts_size', get_option('foreverwood_headings_google_fonts_size', '48')); 
		if ($headingfont_size != '48' && $headingfont_size != '') { ?>
		<?php _e('#wrapper #wrapper-header .site-title { font-size: '); ?><?php echo $headingfont_size ?><?php _e('px; }'); ?>
<?php } 
}

// Site title color
function foreverwood_get_site_title_color() {
    $site_title_color = get_theme_mod('foreverwood_site_title_color', get_option('foreverwood_site_title_color', '')); 
		if ($site_title_color != '#' && $site_title_color != '') { ?>
		<?php _e('#wrapper #wrapper-header .site-title a { color: '); ?><?php echo $site_title_color ?><?php _e('; }'); ?>
<?php } 
}

// Site title hover color
function foreverwood_get_site_title_hover_color() {
    $site_title_hover_color = get_theme_mod('foreverwood_site_title_hover_color', get_option('foreverwood_site_title_hover_color', '')); 
		if ($site_title_hover_color != '#' && $site_title_hover_color != '') { ?>
		<?php _e('#wrapper #wrapper-header .site-title a:hover { color: '); ?><?php echo $site_title_hover_color ?><?php _e('; }'); ?>
<?php } 
}

// Homepage Header Image Headline color
function foreverwood_get_header_image_headline_color() {
    $header_image_headline_color = get_theme_mod('foreverwood_header_image_headline_color', get_option('foreverwood_header_image_headline_color', '')); 
		if ($header_image_headline_color != '#' && $header_image_headline_color != '') { ?>
		<?php _e('#wrapper #wrapper-header .header-image .header-image-text .header-image-headline { color: '); ?><?php echo $header_image_headline_color ?><?php _e('; }'); ?>
<?php } 
}

// Page/post main headlines color
function foreverwood_get_main_headlines_color() {
    $main_headlines_color = get_theme_mod('foreverwood_main_headlines_color', get_option('foreverwood_main_headlines_color', '')); 
		if ($main_headlines_color != '#' && $main_headlines_color != '') { ?>
		<?php _e('#wrapper .content-headline h1 { color: '); ?><?php echo $main_headlines_color ?><?php _e('; }'); ?>
<?php } 
}

// Page/post headlines font
function foreverwood_get_headlines_font() {
    $headlinefont = get_theme_mod('foreverwood_headline_google_fonts', get_option('foreverwood_headline_google_fonts', 'default')); 
    if ($headlinefont != 'default' && $headlinefont != '') { ?>
    <?php _e('#wrapper h1, #wrapper h2, #wrapper h3, #wrapper h4, #wrapper h5, #wrapper h6, #wrapper .container .navigation .section-heading, #wrapper #comments .entry-headline, #wrapper .info-box .info-box-headline, #wrapper .presentation-box .presentation-box-headline, #wrapper .presentation-box .presentation-box-subtitle, #wrapper .box-image .box-image-text .box-image-headline, #wrapper .header-image .header-image-text .header-image-headline { font-family: "'); ?><?php echo $headlinefont ?><?php _e('", Arial, Helvetica, sans-serif; }'); ?>
<?php } 
}

// Page/post headlines color
function foreverwood_get_headlines_color() {
    $headlines_color = get_theme_mod('foreverwood_headlines_color', get_option('foreverwood_headlines_color', '')); 
		if ($headlines_color != '#' && $headlines_color != '') { ?>
		<?php _e('#wrapper h1, #wrapper h2, #wrapper h3, #wrapper h4, #wrapper h5, #wrapper h6, #wrapper .container .navigation .section-heading, #wrapper #comments .entry-headline, #wrapper .info-box .info-box-headline, #wrapper .presentation-box .presentation-box-headline, #wrapper .presentation-box .presentation-box-subtitle, #wrapper .box-image .box-image-text .box-image-headline, #wrapper .header-image .header-image-text .header-image-headline { color: '); ?><?php echo $headlines_color ?><?php _e('; }'); ?>
<?php } 
}

// H1 Headlines font size
function foreverwood_get_headline_h1_size() {
    $headline_h1_size = get_theme_mod('foreverwood_headline_h1_size', get_option('foreverwood_headline_h1_size', '28')); 
		if ($headline_h1_size != '28' && $headline_h1_size != '') { ?>
		<?php _e('#wrapper h1 { font-size: '); ?><?php echo $headline_h1_size ?><?php _e('px; }'); ?>
<?php } 
}

// H2 Headlines font size
function foreverwood_get_headline_h2_size() {
    $headline_h2_size = get_theme_mod('foreverwood_headline_h2_size', get_option('foreverwood_headline_h2_size', '22')); 
		if ($headline_h2_size != '22' && $headline_h2_size != '') { ?>
		<?php _e('#wrapper h2, #wrapper #comments .entry-headline { font-size: '); ?><?php echo $headline_h2_size ?><?php _e('px; }'); ?>
<?php } 
}

// H3 Headlines font size
function foreverwood_get_headline_h3_size() {
    $headline_h3_size = get_theme_mod('foreverwood_headline_h3_size', get_option('foreverwood_headline_h3_size', '19')); 
		if ($headline_h3_size != '19' && $headline_h3_size != '') { ?>
		<?php _e('#wrapper h3 { font-size: '); ?><?php echo $headline_h3_size ?><?php _e('px; }'); ?>
<?php } 
}

// H4 Headlines font size
function foreverwood_get_headline_h4_size() {
    $headline_h4_size = get_theme_mod('foreverwood_headline_h4_size', get_option('foreverwood_headline_h4_size', '17')); 
		if ($headline_h4_size != '17' && $headline_h4_size != '') { ?>
		<?php _e('#wrapper h4 { font-size: '); ?><?php echo $headline_h4_size ?><?php _e('px; }'); ?>
<?php } 
}

// H5 Headlines font size
function foreverwood_get_headline_h5_size() {
    $headline_h5_size = get_theme_mod('foreverwood_headline_h5_size', get_option('foreverwood_headline_h5_size', '15')); 
		if ($headline_h5_size != '15' && $headline_h5_size != '') { ?>
		<?php _e('#wrapper h5 { font-size: '); ?><?php echo $headline_h5_size ?><?php _e('px; }'); ?>
<?php } 
}

// H6 Headlines font size
function foreverwood_get_headline_h6_size() {
    $headline_h6_size = get_theme_mod('foreverwood_headline_h6_size', get_option('foreverwood_headline_h6_size', '14')); 
		if ($headline_h6_size != '14' && $headline_h6_size != '') { ?>
		<?php _e('#wrapper h6 { font-size: '); ?><?php echo $headline_h6_size ?><?php _e('px; }'); ?>
<?php } 
}

// ForeverWood Posts Widgets headlines font
function foreverwood_get_headline_box_google_fonts() {
    $headline_box_google_fonts = get_theme_mod('foreverwood_headline_box_google_fonts', get_option('foreverwood_headline_box_google_fonts', 'default')); 
		if ($headline_box_google_fonts != 'default' && $headline_box_google_fonts != '') { ?>
		<?php _e('#wrapper .container #main-content section .entry-headline { font-family: "'); ?><?php echo $headline_box_google_fonts ?><?php _e('", Arial, Helvetica, sans-serif; }'); ?>
<?php } 
}

// ForeverWood Posts Widgets headlines color
function foreverwood_get_homepage_headlines_color() {
    $homepage_headlines_color = get_theme_mod('foreverwood_homepage_headlines_color', get_option('foreverwood_homepage_headlines_color', '')); 
		if ($homepage_headlines_color != '#' && $homepage_headlines_color != '') { ?>
		<?php _e('#wrapper .container #main-content section .entry-headline { color: '); ?><?php echo $homepage_headlines_color ?><?php _e('; }'); ?>
<?php } 
}

// ForeverWood Posts Widgets headlines font size
function foreverwood_get_headline_box_google_fonts_size() {
    $headline_box_google_fonts_size = get_theme_mod('foreverwood_headline_box_google_fonts_size', get_option('foreverwood_headline_box_google_fonts_size', '28')); 
		if ($headline_box_google_fonts_size != '28' && $headline_box_google_fonts_size != '') { ?>
		<?php _e('#wrapper .container #main-content section .entry-headline { font-size: '); ?><?php echo $headline_box_google_fonts_size ?><?php _e('px; }'); ?>
<?php } 
}

// Post entry font
function foreverwood_get_postentry_font() {
    $postentryfont = get_theme_mod('foreverwood_postentry_google_fonts', get_option('foreverwood_postentry_google_fonts', 'default')); 
		if ($postentryfont != 'default' && $postentryfont != '') { ?>
		<?php _e('#wrapper #main-content .post-entry .post-entry-headline, #wrapper #main-content .grid-entry .grid-entry-headline, #wrapper #main-content .slides li a, #wrapper #main-content .home-list-posts ul li a { font-family: "'); ?><?php echo $postentryfont ?><?php _e('", Arial, Helvetica, sans-serif; }'); ?>
<?php } 
}

// Post entry font size
function foreverwood_get_postentry_font_size() {
    $postentryfont_size = get_theme_mod('foreverwood_postentry_google_fonts_size', get_option('foreverwood_postentry_google_fonts_size', '23')); 
		if ($postentryfont_size != '23' && $postentryfont_size != '') { ?>
		<?php _e('#wrapper #main-content .post-entry .post-entry-headline, #wrapper #main-content .grid-entry .grid-entry-headline { font-size: '); ?><?php echo $postentryfont_size ?><?php _e('px; }'); ?>
<?php } 
}

// Post entry color
function foreverwood_get_postentry_color() {
    $post_entry_color = get_theme_mod('foreverwood_post_entry_color', get_option('foreverwood_post_entry_color', '')); 
		if ($post_entry_color != '#' && $post_entry_color != '') { ?>
		<?php _e('#wrapper #main-content .post-entry .post-entry-headline a, #wrapper #main-content .grid-entry .grid-entry-headline a, #wrapper #main-content .slides li a, #wrapper #main-content .home-list-posts ul li a { color: '); ?><?php echo $post_entry_color ?><?php _e('; }'); ?>
<?php } 
}  

// Post entry hover color
function foreverwood_get_postentry_hover_color() {
    $post_entry_hover_color = get_theme_mod('foreverwood_post_entry_hover_color', get_option('foreverwood_post_entry_hover_color', '')); 
		if ($post_entry_hover_color != '#' && $post_entry_hover_color != '') { ?>
		<?php _e('#wrapper #main-content .post-entry .post-entry-headline a:hover, #wrapper #main-content .grid-entry .grid-entry-headline a:hover, #wrapper #main-content .slides li a:hover, #wrapper #main-content .home-list-posts ul li a:hover { color: '); ?><?php echo $post_entry_hover_color ?><?php _e(' !important; }'); ?>
<?php } 
} 

// Sidebar and Footer widget headlines font
function foreverwood_get_sidebar_widget_font() {
    $sidebarfont = get_theme_mod('foreverwood_sidebar_google_fonts', get_option('foreverwood_sidebar_google_fonts', 'default')); 
    if ($sidebarfont != 'default' && $sidebarfont != '') { ?>
    <?php _e('#wrapper .container #sidebar .sidebar-widget .sidebar-headline, #wrapper .foreverwood_widget_content .tab_title a, #wrapper #wrapper-footer #footer .footer-widget .footer-headline { font-family: "'); ?><?php echo $sidebarfont ?><?php _e('", Arial, Helvetica, sans-serif; }'); ?>
<?php } 
}

// Sidebar widget headlines color
function foreverwood_get_sidebar_widget_color() {
    $sidebar_widget_color = get_theme_mod('foreverwood_sidebar_widget_color', get_option('foreverwood_sidebar_widget_color', '')); 
		if ($sidebar_widget_color != '#' && $sidebar_widget_color != '') { ?>
		<?php _e('#wrapper .container #sidebar .sidebar-widget .sidebar-headline, #wrapper .foreverwood_widget_content .tab_title a { color: '); ?><?php echo $sidebar_widget_color ?><?php _e('; }'); ?>
<?php } 
}

// Sidebar widget headlines font size
function foreverwood_get_sidebar_widget_font_size() {
    $sidebarfont_size = get_theme_mod('foreverwood_sidebar_google_fonts_size', get_option('foreverwood_sidebar_google_fonts_size', '19')); 
		if ($sidebarfont_size != '19' && $sidebarfont_size != '') { ?>
		<?php _e('#wrapper .container #sidebar .sidebar-widget .sidebar-headline { font-size: '); ?><?php echo $sidebarfont_size ?><?php _e('px; }'); ?>
<?php } 
}

// Sidebar Text color
function foreverwood_get_sidebar_text_color() {
    $sidebar_text_color = get_theme_mod('foreverwood_sidebar_text_color', get_option('foreverwood_sidebar_text_color', '')); 
		if ($sidebar_text_color != '#' && $sidebar_text_color != '') { ?>
		<?php _e('#wrapper .container #sidebar .sidebar-widget p, #wrapper .container #sidebar .sidebar-widget ul li, #wrapper .container #sidebar .sidebar-widget ol li, #wrapper .container #sidebar .sidebar-widget table { color: '); ?><?php echo $sidebar_text_color ?><?php _e('; }'); ?>
<?php } 
}

// Footer widget headlines color
function foreverwood_get_footer_widget_color() {
    $footer_widget_color = get_theme_mod('foreverwood_footer_widget_color', get_option('foreverwood_footer_widget_color', '')); 
		if ($footer_widget_color != '#' && $footer_widget_color != '') { ?>
		<?php _e('#wrapper #wrapper-footer #footer .footer-widget .footer-headline { color: '); ?><?php echo $footer_widget_color ?><?php _e('; }'); ?>
<?php } 
}

// Footer widget headlines font size
function foreverwood_get_footer_widget_font_size() {
    $footerfont_size = get_theme_mod('foreverwood_footer_google_fonts_size', get_option('foreverwood_footer_google_fonts_size', '19')); 
		if ($footerfont_size != '19' && $footerfont_size != '') { ?>
		<?php _e('#wrapper #wrapper-footer #footer .footer-widget .footer-headline { font-size: '); ?><?php echo $footerfont_size ?><?php _e('px; }'); ?>
<?php } 
}

// Footer Text color
function foreverwood_get_footer_text_color() {
    $footer_text_color = get_theme_mod('foreverwood_footer_text_color', get_option('foreverwood_footer_text_color', '')); 
		if ($footer_text_color != '#' && $footer_text_color != '') { ?>
		<?php _e('#wrapper #footer .footer-widget p, #wrapper #footer .footer-widget ul li, #wrapper #footer .footer-widget ol li, #wrapper #footer .footer-widget table { color: '); ?><?php echo $footer_text_color ?><?php _e('; }'); ?>
<?php } 
}

// Footer Notices Text color
function foreverwood_get_footer_notices_text_color() {
    $footer_notices_text_color = get_theme_mod('foreverwood_footer_notices_text_color', get_option('foreverwood_footer_notices_text_color', '')); 
		if ($footer_notices_text_color != '#' && $footer_notices_text_color != '') { ?>
		<?php _e('#wrapper .footer-signature .footer-signature-content, #wrapper .footer-signature .footer-signature-content p { color: '); ?><?php echo $footer_notices_text_color ?><?php _e('; }'); ?>
<?php } 
}

// Main content links color
function foreverwood_get_content_links_color() {
    $content_links_color = get_theme_mod('foreverwood_content_links_color', get_option('foreverwood_content_links_color', '')); 
		if ($content_links_color != '#' && $content_links_color != '') { ?>
		<?php _e('#wrapper #main-content a { color: '); ?><?php echo $content_links_color ?><?php _e('; }'); ?>
<?php } 
}

// Sidebar links color
function foreverwood_get_sidebar_links_color() {
    $sidebar_links_color = get_theme_mod('foreverwood_sidebar_links_color', get_option('foreverwood_sidebar_links_color', '')); 
		if ($sidebar_links_color != '#' && $sidebar_links_color != '') { ?>
		<?php _e('#wrapper .container #sidebar a { color: '); ?><?php echo $sidebar_links_color ?><?php _e('; }'); ?>
<?php } 
}

// Footer links color
function foreverwood_get_footer_links_color() {
    $footer_links_color = get_theme_mod('foreverwood_footer_links_color', get_option('foreverwood_footer_links_color', '')); 
		if ($footer_links_color != '#' && $footer_links_color != '') { ?>
		<?php _e('#wrapper #wrapper-footer #footer a { color: '); ?><?php echo $footer_links_color ?><?php _e('; }'); ?>
<?php } 
}

// Footer Notices links color
function foreverwood_get_footer_notices_links_color() {
    $footer_notices_links_color = get_theme_mod('foreverwood_footer_notices_links_color', get_option('foreverwood_footer_notices_links_color', '')); 
		if ($footer_notices_links_color != '#' && $footer_notices_links_color != '') { ?>
		<?php _e('#wrapper #wrapper-footer .footer-signature .footer-signature-content a { color: '); ?><?php echo $footer_notices_links_color ?><?php _e('; }'); ?>
<?php } 
}

// Main Header menu links color
function foreverwood_get_main_header_menu_color() {
    $main_header_menu_color = get_theme_mod('foreverwood_main_header_menu_color', get_option('foreverwood_main_header_menu_color', '')); 
		if ($main_header_menu_color != '#' && $main_header_menu_color != '') { ?>
		<?php _e('#wrapper #wrapper-header .menu-box a, #wrapper #wrapper-header .menu-box a:visited, #wrapper #wrapper-header .menu-panel a, #wrapper #wrapper-header .menu-panel a:visited { color: '); ?><?php echo $main_header_menu_color ?><?php _e('; }'); ?>
<?php } 
}

// Main Header menu links hover color
function foreverwood_get_main_header_menu_hover_color() {
    $main_header_menu_hover_color = get_theme_mod('foreverwood_main_header_menu_hover_color', get_option('foreverwood_main_header_menu_hover_color', '')); 
		if ($main_header_menu_hover_color != '#' && $main_header_menu_hover_color != '') { ?>
		<?php _e('#wrapper #wrapper-header .menu-box a:hover, #wrapper #wrapper-header .menu-box :hover > a, #wrapper #wrapper-header .menu-panel a:hover, #wrapper #wrapper-header .menu-panel :hover > a { color: '); ?><?php echo $main_header_menu_hover_color ?><?php _e('; }'); ?>
<?php } 
}

// Active Main Header menu links color
function foreverwood_get_active_main_header_menu_color() {
    $active_main_header_menu_color = get_theme_mod('foreverwood_active_main_header_menu_color', get_option('foreverwood_active_main_header_menu_color', ''));
    if ($active_main_header_menu_color != '#' && $active_main_header_menu_color != '') { ?>
		<?php _e('html #wrapper #wrapper-header .menu-box .current-menu-item > a, html #wrapper #wrapper-header .menu-box .current-menu-ancestor > a, html #wrapper #wrapper-header .menu-box .current_page_item > a, html #wrapper #wrapper-header .menu-box .current-page-ancestor > a, html #wrapper #wrapper-header .menu-panel .current-menu-item > a, html #wrapper #wrapper-header .menu-panel .current-menu-ancestor > a, html #wrapper #wrapper-header .menu-panel .current_page_item > a, html #wrapper #wrapper-header .menu-panel .current-page-ancestor > a { color: '); ?><?php echo $active_main_header_menu_color ?><?php _e('; }'); ?>
<?php } 
}

// Main Header menu font
function foreverwood_get_menu_font() {
    $menufont = get_theme_mod('foreverwood_menu_google_fonts', get_option('foreverwood_menu_google_fonts', 'default')); 
		if ($menufont != 'default' && $menufont != '') { ?>
		<?php _e('#wrapper #wrapper-header .menu-box ul li a, #wrapper #wrapper-header .menu-panel ul li a { font-family: "'); ?><?php echo $menufont ?><?php _e('", Arial, Helvetica, sans-serif; }'); ?>
<?php } 
}

// Main Header menu font size
function foreverwood_get_menu_font_size() {
    $menufont_size = get_theme_mod('foreverwood_menu_google_fonts_size', get_option('foreverwood_menu_google_fonts_size', '15')); 
		if ($menufont_size != '15' && $menufont_size != '') { ?>
		<?php _e('#wrapper #wrapper-header .menu-box ul li a, #wrapper #wrapper-header .menu-panel ul li a { font-size: '); ?><?php echo $menufont_size ?><?php _e('px; }'); ?>
<?php } 
}

// User defined CSS.
function foreverwood_get_own_css() {
    $own_css = get_option('foreverwood_own_css'); 
		if ($own_css != '') { ?>
		<?php echo esc_attr($own_css); ?>
<?php } 
}

// Display custom CSS.
function foreverwood_custom_styles() { ?>
<?php echo ("<style type='text/css'>"); ?>
<?php foreverwood_get_own_css(); ?>
<?php foreverwood_display_sidebar(); ?>
<?php foreverwood_display_sidebar_archives(); ?>
<?php foreverwood_get_header_layout(); ?>
<?php foreverwood_get_page_title_width(); ?>
<?php foreverwood_get_header_menu_width(); ?>
<?php foreverwood_grid_columns(); ?>
<?php foreverwood_featured_image_hover(); ?>
<?php foreverwood_display_meta_post_entry(); ?>
<?php foreverwood_get_top_menu_background_color(); ?>
<?php foreverwood_get_top_menu_border_color(); ?>
<?php foreverwood_get_social_icons_color(); ?>
<?php foreverwood_get_social_icons_hover_color(); ?>
<?php foreverwood_get_main_submenu_background_color(); ?>
<?php foreverwood_get_main_submenu_text_color(); ?>
<?php foreverwood_get_main_submenu_border_color(); ?>
<?php foreverwood_get_main_submenu_hover_color(); ?>
<?php foreverwood_get_header_content_background_color(); ?>
<?php foreverwood_get_header_content_border_color(); ?>
<?php foreverwood_get_main_content_background_color(); ?>
<?php foreverwood_get_author_comments_background_color(); ?>
<?php foreverwood_get_sticky_post_background_color(); ?>
<?php foreverwood_get_posts_thumbnail_background_color(); ?>
<?php foreverwood_get_highlight_background_color(); ?>
<?php foreverwood_get_sidebar_widget_background_color(); ?>
<?php foreverwood_get_footer_background_color(); ?>
<?php foreverwood_get_footer_widget_border_color(); ?>
<?php foreverwood_get_footer_notices_background_color(); ?>
<?php foreverwood_get_searchform_border_color(); ?>
<?php foreverwood_get_searchform_button_background_color(); ?>
<?php foreverwood_get_searchform_button_hover_color(); ?>
<?php foreverwood_get_form_fields_background_color(); ?>
<?php foreverwood_get_form_fields_border_color(); ?>
<?php foreverwood_get_form_buttons_background_color(); ?>
<?php foreverwood_get_form_buttons_hover_color(); ?>
<?php foreverwood_get_table_headers_background_color(); ?>
<?php foreverwood_get_body_font(); ?>
<?php foreverwood_get_body_color(); ?>
<?php foreverwood_get_body_google_fonts_size(); ?>
<?php foreverwood_get_headings_google_fonts(); ?>
<?php foreverwood_get_headings_google_fonts_size(); ?>
<?php foreverwood_get_site_title_color(); ?>
<?php foreverwood_get_site_title_hover_color(); ?>
<?php foreverwood_get_header_image_headline_color(); ?>
<?php foreverwood_get_main_headlines_color(); ?>
<?php foreverwood_get_headlines_font(); ?>
<?php foreverwood_get_headlines_color(); ?>
<?php foreverwood_get_headline_h1_size(); ?>
<?php foreverwood_get_headline_h2_size(); ?>
<?php foreverwood_get_headline_h3_size(); ?>
<?php foreverwood_get_headline_h4_size(); ?>
<?php foreverwood_get_headline_h5_size(); ?>
<?php foreverwood_get_headline_h6_size(); ?>
<?php foreverwood_get_headline_box_google_fonts(); ?>
<?php foreverwood_get_homepage_headlines_color(); ?>
<?php foreverwood_get_headline_box_google_fonts_size(); ?>
<?php foreverwood_get_postentry_font(); ?>
<?php foreverwood_get_postentry_font_size(); ?>
<?php foreverwood_get_postentry_color(); ?>
<?php foreverwood_get_postentry_hover_color(); ?>
<?php foreverwood_get_sidebar_widget_font(); ?>
<?php foreverwood_get_sidebar_widget_color(); ?>
<?php foreverwood_get_sidebar_widget_font_size(); ?>
<?php foreverwood_get_sidebar_text_color(); ?>
<?php foreverwood_get_footer_widget_color(); ?>
<?php foreverwood_get_footer_widget_font_size(); ?>
<?php foreverwood_get_footer_text_color(); ?>
<?php foreverwood_get_footer_notices_text_color(); ?>
<?php foreverwood_get_content_links_color(); ?>
<?php foreverwood_get_sidebar_links_color(); ?>
<?php foreverwood_get_footer_links_color(); ?>
<?php foreverwood_get_footer_notices_links_color(); ?>
<?php foreverwood_get_main_header_menu_color(); ?>
<?php foreverwood_get_main_header_menu_hover_color(); ?>
<?php foreverwood_get_active_main_header_menu_color(); ?>
<?php foreverwood_get_menu_font(); ?>
<?php foreverwood_get_menu_font_size(); ?>
<?php echo ("</style>"); ?>
<?php
} 
add_action('wp_enqueue_scripts', 'foreverwood_custom_styles');	?>