<?php
/**
 * ForeverWood functions and definitions.
 * @package ForeverWood
 * @since ForeverWood 1.0.0
*/

/**
 * ForeverWood theme variables.
 *  
*/    
$foreverwood_themename = "ForeverWood Premium";			//Theme Name
$foreverwood_themever = "2.0.3";									//Theme version
$foreverwood_shortname = "foreverwood";							//Shortname 
$foreverwood_manualurl = get_template_directory_uri() . '/docs/documentation.html';	//Manual Url
// Set path to ForeverWood Framework and theme specific functions
$foreverwood_be_path = get_template_directory() . '/functions/be/';									//BackEnd Path
$foreverwood_fe_path = get_template_directory() . '/functions/fe/';									//FrontEnd Path 
$foreverwood_be_pathimages = get_template_directory_uri() . '/functions/be/images';		//BackEnd Path
$foreverwood_fe_pathimages = get_template_directory_uri() . '';	//FrontEnd Path
//Include Framework [BE] 
require_once ($foreverwood_be_path . 'fw-setup.php');		   // Init 
require_once ($foreverwood_be_path . 'fw-options.php');	 	 // Framework Init  
// Include Theme specific functionality [FE] 
require_once ($foreverwood_fe_path . 'headerdata.php');		 // Include css and js
require_once ($foreverwood_fe_path . 'library.php');	       // Include library, functions
require_once ($foreverwood_fe_path . 'widget-twitter.php'); // Twitter Widget
require_once ($foreverwood_fe_path . 'widget-facebook.php');// Facebook Widget
require_once ($foreverwood_fe_path . 'widget-info-box.php');// Box-Info Widget
require_once ($foreverwood_fe_path . 'widget-image-box.php');// Box-Image Widget
require_once ($foreverwood_fe_path . 'widget-presentation-box.php');// Box-Presentation Widget
require_once ($foreverwood_fe_path . 'widget-section-headline.php');// Section-Headline Widget
require_once ($foreverwood_fe_path . 'widget-posts-column.php');// Posts-Column Widget
require_once ($foreverwood_fe_path . 'widget-posts-default.php');// Posts-Default Widget
require_once ($foreverwood_fe_path . 'widget-posts-grid.php');// Posts-Grid Widget
require_once ($foreverwood_fe_path . 'widget-posts-list.php');// Posts-List Widget
require_once ($foreverwood_fe_path . 'widget-posts-slider.php');// Posts-Slider Widget
require_once ($foreverwood_fe_path . 'widget-posts-thumbnail.php');// Posts-Thumbnail Widget
require_once ($foreverwood_fe_path . 'google-map-shortcode.php');// Google Maps Shortcode
require_once ($foreverwood_fe_path . '/wp-display-header/wp-display-header.php');// Different Header Images for individual pages
require_once ($foreverwood_fe_path . '/wp-tab-widget/wp-tab-widget.php');// Tab Widget
// Include Theme Customization admin screen  
require_once (get_template_directory() . '/functions/customizer.php');

/**
 * ForeverWood theme basic setup.
 *  
*/
function foreverwood_setup() {
	// Makes ForeverWood available for translation.
	load_theme_textdomain( 'foreverwood', get_template_directory() . '/languages' );
  // This theme styles the visual editor to resemble the theme style.
  add_editor_style( 'editor-style.css' );
	// Adds RSS feed links to <head> for posts and comments.  
	add_theme_support( 'automatic-feed-links' );
	// This theme supports custom background color and image.
	$defaults = array(
	'default-color' => '', 
  'default-image' => '',
	'wp-head-callback'  => '_custom_background_cb',
	'admin-head-callback' => '',
	'admin-preview-callback' => '' );  
  add_theme_support( 'custom-background', $defaults );
	// This theme uses a custom image size for featured images, displayed on "standard" posts.
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 1170, 9999 );
  add_image_size( 'slider-thumb', 156, 117, true );
  add_image_size( 'square-thumb', 60, 60, true );
  add_image_size( 'thumbnail-thumb', 800, 600, true );
  // This theme uses a custom header background image.
  $args = array(
	'width' => 1800,
	'height' => 400,
	'flex-width' => true,
	'flex-height' => true,
	'header-text' => false,
	'random-default' => true,);
  add_theme_support( 'custom-header', $args );
  add_theme_support( 'post-formats', array( 'aside', 'audio', 'image', 'status', 'video' ) );
  add_theme_support( 'title-tag' );
  add_theme_support( 'woocommerce' );
  global $content_width;
  if ( ! isset( $content_width ) ) { $content_width = 840; }
}
add_action( 'after_setup_theme', 'foreverwood_setup' );

/**
 * Enqueues scripts and styles for front-end.
 *
*/
function foreverwood_scripts_styles() {
	global $wp_styles, $foreverwood_options;
  foreach ($foreverwood_options as $value) {
	if (isset($value['id']) && get_option( $value['id'] ) === FALSE && isset($value['std'])) {
		${$value['id']} = $value['std'];
	}
	elseif (isset($value['id'])) { ${$value['id']} = get_option( $value['id'] ); }
}   
	// Adds JavaScript
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' ); 
    if ( $foreverwood_post_entry_format == 'Grid - Masonry' ) {
    if ( is_home() || is_archive() || is_search() ) {   
    wp_enqueue_script( 'foreverwood-imagesloaded', get_template_directory_uri() . '/js/imagesloaded.js', array(), '3.1.8', true );
    wp_enqueue_script( 'foreverwood-masonry', get_template_directory_uri() . '/js/masonry.js', array(), '3.1.5', true );
    if ( !is_rtl() ) {
    wp_enqueue_script( 'foreverwood-masonry-settings', get_template_directory_uri() . '/js/masonry-settings.js', array(), '1.0', true ); } else {
    wp_enqueue_script( 'foreverwood-masonry-settings-rtl', get_template_directory_uri() . '/js/masonry-settings-rtl.js', array(), '1.0', true ); }}}
    wp_enqueue_script( 'foreverwood-flexslider', get_template_directory_uri() . '/js/flexslider.js', array( 'jquery' ), '2.2.0', true );
    wp_enqueue_script( 'foreverwood-flexslider-settings', get_template_directory_uri() . '/js/flexslider-settings.js', array( 'jquery' ), '2.2.0', true );
    wp_enqueue_script( 'foreverwood-placeholders', get_template_directory_uri() . '/js/placeholders.js', array(), '2.1.0', true );
	wp_enqueue_script( 'foreverwood-scroll-to-top', get_template_directory_uri() . '/js/scroll-to-top.js', array( 'jquery' ), '1.0', true );
    if ( has_nav_menu( 'main-navigation' ) && $foreverwood_header_layout != 'Wide' && $foreverwood_fixed_menu != 'Disable' && !is_page_template('template-landing-page.php') ) {
    wp_enqueue_script( 'foreverwood-menubox', get_template_directory_uri() . '/js/menubox.js', array(), '1.0', true ); }
    wp_enqueue_script( 'foreverwood-selectnav', get_template_directory_uri() . '/js/selectnav.js', array(), '0.1', true );
    wp_enqueue_script( 'foreverwood-responsive', get_template_directory_uri() . '/js/responsive.js', array(), '1.0', true );
	wp_enqueue_style( 'foreverwood-elegantfont', get_template_directory_uri() . '/css/elegantfont.css' );
	  wp_enqueue_style( 'foreverwood-style', get_stylesheet_uri() );   
    wp_enqueue_style( 'foreverwood-google-font-default', '//fonts.googleapis.com/css?family=PT+Sans&amp;subset=latin,latin-ext' );
    wp_enqueue_style( 'foreverwood-tab-widget', get_template_directory_uri() . '/functions/fe/wp-tab-widget/css/wp-tab-widget.css' );
    if ( class_exists( 'woocommerce' ) ) { wp_enqueue_style( 'foreverwood-woocommerce-custom', get_template_directory_uri() . '/css/woocommerce-custom.css' ); }
}
add_action( 'wp_enqueue_scripts', 'foreverwood_scripts_styles' );

/**
 * Search for the first image in a post.
 *  
*/
function foreverwood_catch_that_image() {
  global $post, $posts;
  $first_img = '';
  ob_start();
  ob_end_clean();
  $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
  $first_img = $matches[1][0]; 
  if(empty($first_img)) {
    $first_img = get_template_directory_uri() . '/images/post-type-image.jpg';
  }
  return $first_img;
}

/**
 * Search for the first audio in a post.
 *  
*/
function foreverwood_catch_that_audio() {
global $post, $posts;
if ( preg_match( '#\[audio\s*.*?\]#s', $post->post_content, $matches ) && preg_match('/"([^"]+)"/', $matches[0], $m) ) {
echo do_shortcode('[audio src="'.$m[1].'" ]'); }
}

/**
 * Adds a custom meta box for setting a Video Thumbnail.
 *
*/
function foreverwood_add_meta_box() {
	$screens = array( 'post' );
	foreach ( $screens as $screen ) {
		add_meta_box(
			'foreverwood_sectionid',
			__( 'Video Thumbnail', 'foreverwood' ),
			'foreverwood_meta_box_callback',
			$screen
		);
	}
}
add_action( 'add_meta_boxes', 'foreverwood_add_meta_box' );

function foreverwood_meta_box_callback( $post ) {
	wp_nonce_field( 'foreverwood_meta_box', 'foreverwood_meta_box_nonce' );
	$value = get_post_meta( $post->ID, '_my_meta_value_key', true );
	echo '<label for="foreverwood_new_field">';
	_e( 'Insert here the full URL address of the Video Thumbnail.', 'foreverwood' );
	echo '</label> ';
	echo '<input type="text" id="foreverwood_new_field" name="foreverwood_new_field" value="' . esc_attr( $value ) . '" size="25" />';
}

function foreverwood_save_meta_box_data( $post_id ) {
	if ( ! isset( $_POST['foreverwood_meta_box_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( $_POST['foreverwood_meta_box_nonce'], 'foreverwood_meta_box' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}
	} else {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}
	if ( ! isset( $_POST['foreverwood_new_field'] ) ) {
		return;
	}
	$my_data = sanitize_text_field( $_POST['foreverwood_new_field'] );
	update_post_meta( $post_id, '_my_meta_value_key', $my_data );
}
add_action( 'save_post', 'foreverwood_save_meta_box_data' );

/**
 * Backwards compatibility for older WordPress versions which do not support the Title Tag feature.
 *  
*/
if ( ! function_exists( '_wp_render_title_tag' ) ) {
function foreverwood_wp_title( $title, $sep ) {
	if ( is_feed() )
		return $title;
	$title .= get_bloginfo( 'name' );
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";
	return $title;
}
add_filter( 'wp_title', 'foreverwood_wp_title', 10, 2 );
}

/**
 * Register our menus.
 *
 */
function foreverwood_register_my_menus() {
  register_nav_menus(
    array(
      'main-navigation' => __( 'Main Header Menu', 'foreverwood' ),
      'top-navigation' => __( 'Social Networks Links', 'foreverwood' )
    )
  );
}
add_action( 'after_setup_theme', 'foreverwood_register_my_menus' );

/**
 * Register our sidebars and widgetized areas.
 *
*/
function foreverwood_widgets_init() {
  register_sidebar( array(
		'name' => __( 'Right Sidebar', 'foreverwood' ),
		'id' => 'sidebar-1',
		'description' => __( 'Right sidebar which appears on all posts and pages.', 'foreverwood' ),
		'before_widget' => '<div id="%1$s" class="sidebar-widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => ' <p class="sidebar-headline">',
		'after_title' => '</p>',
	) );
  register_sidebar( array(
		'name' => __( 'Footer left widget area', 'foreverwood' ),
		'id' => 'sidebar-2',
		'description' => __( 'Left column with widgets in footer.', 'foreverwood' ),
		'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<p class="footer-headline">',
		'after_title' => '</p>',
	) );
  register_sidebar( array(
		'name' => __( 'Footer middle widget area', 'foreverwood' ),
		'id' => 'sidebar-3',
		'description' => __( 'Middle column with widgets in footer.', 'foreverwood' ),
		'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<p class="footer-headline">',
		'after_title' => '</p>',
	) );
  register_sidebar( array(
		'name' => __( 'Footer right widget area', 'foreverwood' ),
		'id' => 'sidebar-4',
		'description' => __( 'Right column with widgets in footer.', 'foreverwood' ),
		'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<p class="footer-headline">',
		'after_title' => '</p>',
	) );
  register_sidebar( array(
		'name' => __( 'Footer notices', 'foreverwood' ),
		'id' => 'sidebar-5',
		'description' => __( 'The line for copyright and other notices below the footer widget areas. Insert here one Text widget. The "Title" field at this widget should stay empty.', 'foreverwood' ),
		'before_widget' => '<div class="footer-signature"><div class="footer-signature-content">',
		'after_widget' => '</div></div>',
		'before_title' => '',
		'after_title' => '',
	) );
  register_sidebar( array(
		'name' => __( 'Homepage slideshow', 'foreverwood' ),
		'id' => 'sidebar-6',
		'description' => __( 'The area for Cyclone Slider Widget which displays a slideshow on your homepage.', 'foreverwood' ),
		'before_widget' => '<div id="slideshow-wrapper">',
		'after_widget' => '</div>',
		'before_title' => '',
		'after_title' => '',
	) );
}
add_action( 'widgets_init', 'foreverwood_widgets_init' );

/**
 * Post excerpt settings.
 *
*/
function foreverwood_custom_excerpt_length( $length ) {
global $foreverwood_options; 
foreach ($foreverwood_options as $value) {
	if (isset($value['id']) && get_option( $value['id'] ) === FALSE && isset($value['std'])) {
		${$value['id']} = $value['std'];
	}
	elseif (isset($value['id'])) { ${$value['id']} = get_option( $value['id'] ); }
} 
if ($foreverwood_excerpt_length != '') {
return $foreverwood_excerpt_length;
} else { return 40; }
}
add_filter( 'excerpt_length', 'foreverwood_custom_excerpt_length', 20 );
function foreverwood_new_excerpt_more( $more ) {
global $post;
return '...<br /><a class="read-more-button" href="'. esc_url( get_permalink($post->ID) ) . '">' . __( 'Read more', 'foreverwood' ) . '</a>';}
add_filter( 'excerpt_more', 'foreverwood_new_excerpt_more' );

/**
 * Short title for Slider Posts.
 *
*/
function foreverwood_short_title() {
$title = get_the_title();
$limit = "20";
$pad = "...";
if( strlen( $title ) <= $limit) {
echo $title;
} else {
$title = substr( $title, 0, $limit ) . $pad;
echo $title;
}
}

/**
 * Outputs content for the Status post format.
 *
*/
function foreverwood_status_content() {
$status_content_full = get_the_content();
$status_content = strip_tags($status_content_full);
$limit = "140";
$pad = "...";
if( strlen( $status_content ) <= $limit) {
echo $status_content;
} else {
$status_content = substr( $status_content, 0, $limit ) . $pad;
echo $status_content;
}
}

if ( ! function_exists( 'foreverwood_content_nav' ) ) :
/**
 * Displays navigation to next/previous pages when applicable.
 *
*/
function foreverwood_content_nav( $html_id ) {
	global $wp_query;
	$html_id = esc_attr( $html_id );
	if ( $wp_query->max_num_pages > 1 ) : ?>
		<div id="<?php echo $html_id; ?>" class="navigation" role="navigation">
			<h2 class="navigation-headline section-heading"><?php _e( 'Post navigation', 'foreverwood' ); ?></h2>
      <div class="nav-wrapper">
        <p class="navigation-links">
<?php $big = 999999999;
echo paginate_links( array(
	'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
	'format' => '?paged=%#%',
	'current' => max( 1, get_query_var('paged') ),
  'prev_text' => __( '&larr; Previous', 'foreverwood' ),
	'next_text' => __( 'Next &rarr;', 'foreverwood' ),
	'total' => $wp_query->max_num_pages,
	'add_args' => false
) );
?>
        </p>
      </div>
		</div>
	<?php endif;
}
endif;

/**
 * Displays navigation to next/previous posts on single posts pages.
 *
*/
function foreverwood_prev_next($nav_id) { ?>
<?php $foreverwood_previous_post = get_adjacent_post( false, "", true );
$foreverwood_next_post = get_adjacent_post( false, "", false ); ?>
<div id="<?php echo $nav_id; ?>" class="navigation" role="navigation">
	<div class="nav-wrapper">
<?php if ( !empty($foreverwood_previous_post) ) { ?>
  <p class="nav-previous"><a href="<?php echo esc_url(get_permalink($foreverwood_previous_post->ID)); ?>" title="<?php echo esc_attr($foreverwood_previous_post->post_title); ?>"><?php _e( '&larr; Previous post', 'foreverwood' ); ?></a></p>
<?php } if ( !empty($foreverwood_next_post) ) { ?>
	<p class="nav-next"><a href="<?php echo esc_url(get_permalink($foreverwood_next_post->ID)); ?>" title="<?php echo esc_attr($foreverwood_next_post->post_title); ?>"><?php _e( 'Next post &rarr;', 'foreverwood' ); ?></a></p>
<?php } ?>
   </div>
</div>
<?php }

if ( ! function_exists( 'foreverwood_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
*/
function foreverwood_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		<p><?php _e( 'Pingback:', 'foreverwood' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)', 'foreverwood' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
		global $post;
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>" class="comment">
			<div class="comment-meta comment-author vcard">
				<?php
					echo get_avatar( $comment, 44 );
					printf( '<span><b class="fn">%1$s</b> %2$s</span>',
						get_comment_author_link(),
						( $comment->user_id === $post->post_author ) ? '<span>' . __( '(Post author)', 'foreverwood' ) . '</span>' : ''
					);
					printf( '<time datetime="%2$s">%3$s</time>',
						esc_url( get_comment_link( $comment->comment_ID ) ),
						get_comment_time( 'c' ),
						// translators: 1: date, 2: time
						sprintf( __( '%1$s at %2$s', 'foreverwood' ), get_comment_date(''), get_comment_time() )
					);
				?>
			</div><!-- .comment-meta -->

			<?php if ( '0' == $comment->comment_approved ) : ?>
				<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'foreverwood' ); ?></p>
			<?php endif; ?>

			<div class="comment-content comment">
				<?php comment_text(); ?>
			 <div class="reply">
			   <?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'foreverwood' ), 'after' => ' <span>&darr;</span>', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
			   <?php edit_comment_link( __( 'Edit', 'foreverwood' ), '<p class="edit-link">', '</p>' ); ?>
			</div><!-- .comment-content -->
		</div><!-- #comment-## -->
	<?php
		break;
	endswitch;
}
endif;

/**
 * Function for adding custom classes to the menu objects.
 *
*/
add_filter( 'wp_nav_menu_objects', 'foreverwood_filter_menu_class', 10, 2 );
function foreverwood_filter_menu_class( $objects, $args ) {

    $ids        = array();
    $parent_ids = array();
    $top_ids    = array();
    foreach ( $objects as $i => $object ) {

        if ( 0 == $object->menu_item_parent ) {
            $top_ids[$i] = $object;
            continue;
        }
 
        if ( ! in_array( $object->menu_item_parent, $ids ) ) {
            $objects[$i]->classes[] = 'first-menu-item';
            $ids[]          = $object->menu_item_parent;
        }
 
        if ( in_array( 'first-menu-item', $object->classes ) )
            continue;
 
        $parent_ids[$i] = $object->menu_item_parent;
    }
 
    $sanitized_parent_ids = array_unique( array_reverse( $parent_ids, true ) );
 
    foreach ( $sanitized_parent_ids as $i => $id )
        $objects[$i]->classes[] = 'last-menu-item';
 
    return $objects; 
}

/**
 * Include the TGM_Plugin_Activation class.
 *  
*/
if ( current_user_can ( 'install_plugins' ) ) {
require_once get_template_directory() . '/class-tgm-plugin-activation.php'; 
add_action( 'foreverwood_register', 'foreverwood_my_theme_register_required_plugins' );

function foreverwood_my_theme_register_required_plugins() {

$plugins = array(
		array(
			'name'     => 'Breadcrumb NavXT',
			'slug'     => 'breadcrumb-navxt',
			'required' => false,
		),
    array(
			'name'     => 'Custom Sidebars Manager',
			'slug'     => 'custom-sidebar',
			'source'   => get_template_directory_uri() . '/plugins/custom-sidebar.zip',
			'required' => false,
		),
    array(
			'name'     => 'Cyclone Slider 2',
			'slug'     => 'cyclone-slider',
			'source'   => get_template_directory_uri() . '/plugins/cyclone-slider-2.zip',
			'required' => false,
		),
    array(
			'name'     => 'Dagon Design Sitemap Generator',
			'slug'     => 'sitemap-generator',
			'source'   => get_template_directory_uri() . '/plugins/sitemap-generator.zip',
			'required' => false,
		),
    array(
			'name'     => 'Page Builder by SiteOrigin',
			'slug'     => 'siteorigin-panels',
			'source'   => get_template_directory_uri() . '/plugins/siteorigin-panels.zip',
			'required' => false,
		),
	);
 
 
$config = array(
		'domain'       => 'foreverwood',
    'menu'         => 'install-my-theme-plugins',
		'strings'      	 => array(
		'page_title'             => __( 'Install Recommended Plugins', 'foreverwood' ),
		'menu_title'             => __( 'Install Plugins', 'foreverwood' ),
		'instructions_install'   => __( 'The %1$s plugin is required for this theme. Click on the big blue button below to install and activate %1$s.', 'foreverwood' ),
		'instructions_activate'  => __( 'The %1$s is installed but currently inactive. Please go to the <a href="%2$s">plugin administration page</a> page to activate it.', 'foreverwood' ),
		'button'                 => __( 'Install %s Now', 'foreverwood' ),
		'installing'             => __( 'Installing Plugin: %s', 'foreverwood' ),
		'oops'                   => __( 'Something went wrong with the plugin API.', 'foreverwood' ), // */
		'notice_can_install'     => __( 'This theme requires the %1$s plugin. <a href="%2$s"><strong>Click here to begin the installation process</strong></a>. You may be asked for FTP credentials based on your server setup.', 'foreverwood' ),
		'notice_cannot_install'  => __( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'foreverwood' ),
		'notice_can_activate'    => __( 'This theme requires the %1$s plugin. That plugin is currently inactive, so please go to the <a href="%2$s">plugin administration page</a> to activate it.', 'foreverwood' ),
		'notice_cannot_activate' => __( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'foreverwood' ),
		'return'                 => __( 'Return to Recommended Plugins Installer', 'foreverwood' ),
),
); 
foreverwood_tgmpa( $plugins, $config ); 
}}

/**
 * Page Builder custom Row Styles.
 *  
*/
function foreverwood_panels_row_styles($styles) {
    $styles['foreverwood'] = __('ForeverWood', 'foreverwood');
    $styles['foreverwood-fullscreen'] = __('ForeverWood - full screen', 'foreverwood');
    return $styles;
}
add_filter('siteorigin_panels_row_styles', 'foreverwood_panels_row_styles');

/**
 * Custom Shortcodes.
 *
*/
function foreverwood_button_shortcode($atts, $content = null) {
   extract(shortcode_atts(array('link' => '#'), $atts));
   return '<a class="custom-button" href="'.$link.'">' . do_shortcode($content) . '</a>';
}
add_shortcode( 'button', 'foreverwood_button_shortcode' );
add_filter('widget_text', 'do_shortcode');

function foreverwood_highlight_shortcode($atts, $content = null) {
   return '<span class="highlight-text">' . do_shortcode($content) . '</span>';
}
add_shortcode( 'highlight', 'foreverwood_highlight_shortcode' );

function foreverwood_table_shortcode($atts) {
    extract( shortcode_atts( array(
        'cols' => 'none',
        'data' => 'none',
    ), $atts ) );
    $cols = explode(',',$cols);
    $data = explode(',',$data);
    $total = count($cols);
    $output = '<table><tr>';
    foreach($cols as $col):
        $output .= '<th>'.$col.'</th>';
    endforeach;
    $output .= '</tr><tr>';
    $counter = 1;
    foreach($data as $datum):
        $output .= '<td>'.$datum.'</td>';
        if($counter%$total==0):
            $output .= '</tr>';
        endif;
        $counter++;
    endforeach;
        $output .= '</table>';
    return $output;
}
add_shortcode( 'table', 'foreverwood_table_shortcode' );

function foreverwood_image_shortcode($atts, $content = null){
return '<img src="'.$atts['src'].'" alt="" />';
}
add_shortcode('image','foreverwood_image_shortcode');

/**
 * Shortcode for displaying lists of posts.
 *  
*/
add_shortcode( 'display-posts', 'foreverwood_display_posts_shortcode' );
function foreverwood_display_posts_shortcode( $atts ) {
	// Original Attributes, for filters
	$original_atts = $atts;
	// Pull in shortcode attributes and set defaults
	$atts = shortcode_atts( array(
		'author'              => '',
		'category'            => '',
		'date_format'         => '(n/j/Y)',
		'id'                  => false,
		'ignore_sticky_posts' => false,
		'image_size'          => false,
		'include_content'     => false,
		'include_date'        => false,
		'include_excerpt'     => false,
		'meta_key'            => '',
		'no_posts_message'    => '',
		'offset'              => 0,
		'order'               => 'DESC',
		'orderby'             => 'date',
		'post_parent'         => false,
		'post_status'         => 'publish',
		'post_type'           => 'post',
		'posts_per_page'      => '10',
		'tag'                 => '',
		'tax_operator'        => 'IN',
		'tax_term'            => false,
		'taxonomy'            => false,
		'wrapper'             => 'ul',
	), $atts );
	$author = sanitize_text_field( $atts['author'] );
	$category = sanitize_text_field( $atts['category'] );
	$date_format = sanitize_text_field( $atts['date_format'] );
	$id = $atts['id']; // Sanitized later as an array of integers
	$ignore_sticky_posts = (bool) $atts['ignore_sticky_posts'];
	$image_size = sanitize_key( $atts['image_size'] );
	$include_content = (bool)$atts['include_content'];
	$include_date = (bool)$atts['include_date'];
	$include_excerpt = (bool)$atts['include_excerpt'];
	$meta_key = sanitize_text_field( $atts['meta_key'] );
	$no_posts_message = sanitize_text_field( $atts['no_posts_message'] );
	$offset = intval( $atts['offset'] );
	$order = sanitize_key( $atts['order'] );
	$orderby = sanitize_key( $atts['orderby'] );
	$post_parent = $atts['post_parent']; // Validated later, after check for 'current'
	$post_status = $atts['post_status']; // Validated later as one of a few values
	$post_type = sanitize_text_field( $atts['post_type'] );
	$posts_per_page = intval( $atts['posts_per_page'] );
	$tag = sanitize_text_field( $atts['tag'] );
	$tax_operator = $atts['tax_operator']; // Validated later as one of a few values
	$tax_term = sanitize_text_field( $atts['tax_term'] );
	$taxonomy = sanitize_key( $atts['taxonomy'] );
	$wrapper = sanitize_text_field( $atts['wrapper'] );	
	// Set up initial query for post
	$args = array(
		'category_name'       => $category,
		'order'               => $order,
		'orderby'             => $orderby,
		'post_type'           => explode( ',', $post_type ),
		'posts_per_page'      => $posts_per_page,
		'tag'                 => $tag,
	);	
	// Ignore Sticky Posts
	if( $ignore_sticky_posts )
		$args['ignore_sticky_posts'] = true;	
	// Meta key (for ordering)
	if( !empty( $meta_key ) )
		$args['meta_key'] = $meta_key;	
	// If Post IDs
	if( $id ) {
		$posts_in = array_map( 'intval', explode( ',', $id ) );
		$args['post__in'] = $posts_in;
	}	
	// Post Author
	if( !empty( $author ) )
		$args['author_name'] = $author;		
	// Offset
	if( !empty( $offset ) )
		$args['offset'] = $offset;	
	// Post Status	
	$post_status = explode( ', ', $post_status );		
	$validated = array();
	$available = array( 'publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash', 'any' );
	foreach ( $post_status as $unvalidated )
		if ( in_array( $unvalidated, $available ) )
			$validated[] = $unvalidated;
	if( !empty( $validated ) )		
		$args['post_status'] = $validated;	
	// If taxonomy attributes, create a taxonomy query
	if ( !empty( $taxonomy ) && !empty( $tax_term ) ) {
		// Term string to array
		$tax_term = explode( ', ', $tax_term );		
		// Validate operator
		if( !in_array( $tax_operator, array( 'IN', 'NOT IN', 'AND' ) ) )
			$tax_operator = 'IN';					
		$tax_args = array(
			'tax_query' => array(
				array(
					'taxonomy' => $taxonomy,
					'field'    => 'slug',
					'terms'    => $tax_term,
					'operator' => $tax_operator
				)
			)
		);		
		// Check for multiple taxonomy queries
		$count = 2;
		$more_tax_queries = false;
		while( 
			isset( $original_atts['taxonomy_' . $count] ) && !empty( $original_atts['taxonomy_' . $count] ) && 
			isset( $original_atts['tax_' . $count . '_term'] ) && !empty( $original_atts['tax_' . $count . '_term'] ) 
		):		
			// Sanitize values
			$more_tax_queries = true;
			$taxonomy = sanitize_key( $original_atts['taxonomy_' . $count] );
	 		$terms = explode( ', ', sanitize_text_field( $original_atts['tax_' . $count . '_term'] ) );
	 		$tax_operator = isset( $original_atts['tax_' . $count . '_operator'] ) ? $original_atts['tax_' . $count . '_operator'] : 'IN';
	 		$tax_operator = in_array( $tax_operator, array( 'IN', 'NOT IN', 'AND' ) ) ? $tax_operator : 'IN';	 		
	 		$tax_args['tax_query'][] = array(
	 			'taxonomy' => $taxonomy,
	 			'field' => 'slug',
	 			'terms' => $terms,
	 			'operator' => $tax_operator
	 		);
			$count++;			
		endwhile;		
		if( $more_tax_queries ):
			$tax_relation = 'AND';
			if( isset( $original_atts['tax_relation'] ) && in_array( $original_atts['tax_relation'], array( 'AND', 'OR' ) ) )
				$tax_relation = $original_atts['tax_relation'];
			$args['tax_query']['relation'] = $tax_relation;
		endif;		
		$args = array_merge( $args, $tax_args );
	}	
	// If post parent attribute, set up parent
	if( $post_parent ) {
		if( 'current' == $post_parent ) {
			global $post;
			$post_parent = $post->ID;
		}
		$args['post_parent'] = intval( $post_parent );
	}	
	// Set up html elements used to wrap the posts. 
	// Default is ul/li, but can also be ol/li and div/div
	$wrapper_options = array( 'ul', 'ol', 'div' );
	if( ! in_array( $wrapper, $wrapper_options ) )
		$wrapper = 'ul';
	$inner_wrapper = 'div' == $wrapper ? 'div' : 'li';	
	$listing = new WP_Query( apply_filters( 'display_posts_shortcode_args', $args, $original_atts ) );
	if ( ! $listing->have_posts() )
		return apply_filters( 'display_posts_shortcode_no_results', wpautop( $no_posts_message ) );		
	$inner = '';
	while ( $listing->have_posts() ): $listing->the_post(); global $post;		
		$image = $date = $excerpt = $content = '';		
		$title = '<a class="title" href="' . apply_filters( 'the_permalink', get_permalink() ) . '">' . apply_filters( 'the_title', get_the_title() ) . '</a>';		
		if ( $image_size && has_post_thumbnail() )  
			$image = '<a class="image" href="' . get_permalink() . '">' . get_the_post_thumbnail( $post->ID, $image_size ) . '</a> ';			
		if ( $include_date ) 
			$date = ' <span class="date">' . get_the_date( $date_format ) . '</span>';		
		if ( $include_excerpt ) 
			$excerpt = ' <span class="excerpt-dash">-</span> <span class="excerpt">' . get_the_excerpt() . '</span>';			
		if( $include_content )
			$content = '<div class="content">' . apply_filters( 'the_content', get_the_content() ) . '</div>'; 		
		$class = array( 'listing-item' );
		$class = apply_filters( 'display_posts_shortcode_post_class', $class, $post, $listing );
		$output = '<' . $inner_wrapper . ' class="' . implode( ' ', $class ) . '">' . $image . $title . $date . $excerpt . $content . '</' . $inner_wrapper . '>';		
		// If post is set to private, only show to logged in users
		if( 'private' == get_post_status( $post->ID ) && !current_user_can( 'read_private_posts' ) )
			$output = '';		
		$inner .= apply_filters( 'display_posts_shortcode_output', $output, $original_atts, $image, $title, $date, $excerpt, $inner_wrapper, $content, $class );		
	endwhile; wp_reset_postdata();	
	$open = apply_filters( 'display_posts_shortcode_wrapper_open', '<' . $wrapper . ' class="display-posts-listing">', $original_atts );
	$close = apply_filters( 'display_posts_shortcode_wrapper_close', '</' . $wrapper . '>', $original_atts );
	$return = $open . $inner . $close;
	return $return;
}

/**
 * WooCommerce custom template modifications.
 *  
*/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
function foreverwood_woocommerce_modifications() {
  remove_action ( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 ); 
}  
add_action ( 'init', 'foreverwood_woocommerce_modifications' );
add_filter ( 'woocommerce_show_page_title', '__return_false' );
}

/**
 * Initialize the update checker.
 *  
*/
require 'theme-update-checker.php';
$example_update_checker = new ThemeUpdateChecker(
    'foreverwood-premium',
    'http://www.tomastoman.cz/updates/foreverwood.json'
); ?>