<?php
/**
 * Framework options.
 * @package ForeverWood
 * @since ForeverWood 1.0.0
*/   
      
$foreverwood_options = array (

array( "name" => $foreverwood_themename ." Options",
	"type" => "title"),

array( "type" => "open"),

// Start Tabs
array( "name" => "Start Tabs",
		"type" => "tabs-open",
		"icon" => "layout"),

	// Home
	array( "name" => "<i class='icon_house' aria-hidden='true'></i>Welcome",
			"id" => "tab_menu_0",
			"type" => "tab",
			"icon" => "layout",
			"class" => " selected first"),
	
	// General Settings
	array( "name" => "<i class='icon_key' aria-hidden='true'></i>General Settings",
			"id" => "tab_menu_1",
			"type" => "tab",
			"icon" => "layout",
			"class" => ""),
      
	// Header Settings
	array( "name" => "<i class='icon_cog' aria-hidden='true'></i>Header",
			"id" => "tab_menu_2",
			"type" => "tab",
			"icon" => "layout",
			"class" => ""),
      
	// Posts/Pages Settings
	array( "name" => "<i class='icon_documents' aria-hidden='true'></i>Posts/Pages",
			"id" => "tab_menu_3",
			"type" => "tab",
			"icon" => "layout",
			"class" => ""),
      
  // Homepage Settings
	array( "name" => "<i class='icon_tags' aria-hidden='true'></i>Post Entries",
			"id" => "tab_menu_4",
			"type" => "tab",
			"icon" => "layout",
			"class" => ""),
		
	// Colors
	array( "name" => "<i class='icon_image' aria-hidden='true'></i>Colors",
			"id" => "tab_menu_5",
			"type" => "tab",
			"icon" => "layout",
			"class" => ""),
      
	// Fonts
	array( "name" => "<i class='icon_pens' aria-hidden='true'></i>Fonts",
			"id" => "tab_menu_6",
			"type" => "tab",
			"icon" => "layout",
			"class" => ""),
	
	// Social Networking Tab
	array( "name" => "<i class='icon_group' aria-hidden='true'></i>Social Networking",
			"id" => "tab_menu_7",
			"type" => "tab",
			"icon" => "layout",
			"class" => ""),
	
	// Other settings Tab
	array( "name" => "<i class='icon_tools' aria-hidden='true'></i>Other Settings",
			"type" => "tab",
			"id" => "tab_menu_8",
			"class" => ""),
	

// General setting Tab
array( "name" => "Close Tabs",
		"type" => "tabs-close",
		"icon" => "layout"),


array( "name" => "Start Container",
		"type" => "container-open",
		"icon" => "layout"),

// Home Container 0
array( "name" => "tab_content_0",
		"type" => "tabcontent-open",
		"display" => "block",
		"icon" => "layout"),

	// Home
	array( "name" => "Welcome to ForeverWood!",
		"type" => "heading",
		"icon" => "layout"),
	
	array("name" => "First of all, I would like to thank you for choosing ForeverWood! I firmly believe that you will be satisfied with this theme.",
		"type" => "infotext"),
	
		array( "name" => "About ForeverWood",
		"type" => "heading",
		"icon" => "layout"),
	
	array("name" => "ForeverWood is an easily customizable WordPress multipurpose theme. It is a fully responsive theme that allows for easy viewing on any device.",
		"type" => "infotext"),

array( "name" => "tab_content_0",
		"type" => "tabcontent-close",
		"icon" => "layout"),
// Close Home

// General setting Tab Container 1
array( "name" => "tab_content_1",
		"type" => "tabcontent-open",
		"display" => "none",
		"icon" => "layout"),

	// General settings
	array( "name" => "General Settings",
		"type" => "heading",
		"icon" => "layout"),		

	array( "name" => "Color Scheme",
		"desc" => "Select one of the preconfigured Color Schemes. You can create your own color scheme under Colors tab.",
		"id" => $foreverwood_shortname."_css",
		"std" => "",
		"class" => "hidden",
		"options" => array(	'a' => 'Orange (default)',
							'b' => 'Blue',
							'c' => 'Green',
							'd' => 'Pink',
              'e' => 'Purple',
              'f' => 'Red',
              'g' => 'Turquoise',),
		"type" => "select"),
    
  array( "name" => "Layout Style",
		"desc" => "Choose the layout style of your website.",
		"id" => $foreverwood_shortname."_layout_width",
		"std" => "",
		"class" => "hidden",
		"options" => array(	'a' => 'Wide',
							'b' => 'Boxed'),
		"type" => "select"),
    
  array( "name" => "Display Breadcrumb Navigation",
		"desc" => "Set the Breadcrumb Navigation to be visible or not. The Breadcrumb NavXT plugin has to be installed for displaying it.",
		"id" => $foreverwood_shortname."_display_breadcrumb",
		"std" => "",
		"class" => "hidden",
		"options" => array(	'a' => 'Display',
							'b' => 'Hide'),
		"type" => "select"),
    
	array( "name" => "Favicon URL",
		"desc" => "Upload your own favicon image (16x16px) or directly insert its URL. You can create your own favicon using <a title='Online Favicon Generator' href='http://www.favicon.cc/' target='_blank'>Favicon generator</a>.",
		"id" => $foreverwood_shortname."_favicon_url",
		"type" => "upload-favicon",
		"std" => ""),	
    
	array( "name" => "Sidebar Settings",
		"type" => "heading",
		"icon" => "layout"),
    
  array( "name" => "Display Sidebar on Posts/Pages",
		"desc" => "Set the right sidebar to be visible or not on the single posts and pages.",
		"id" => $foreverwood_shortname."_display_sidebar",
		"std" => "",
		"class" => "hidden",
		"options" => array(	'a' => 'Display',
							'b' => 'Hide'),
		"type" => "select"),
    
  array( "name" => "Display Sidebar on Archives",
		"desc" => "Set the right sidebar to be visible or not on the archive pages (including all archives, homepage and search results).",
		"id" => $foreverwood_shortname."_display_sidebar_archives",
		"std" => "",
		"class" => "hidden",
		"options" => array(	'a' => 'Display',
							'b' => 'Hide'),
		"type" => "select"),
    
array( "name" => "tab_content_1",
		"type" => "tabcontent-close",
		"icon" => "layout"),
// Close General settings
   	

// Open Header settings
  array( "name" => "tab_content_2",
		"type" => "tabcontent-open",
		"display" => "none",
		"icon" => "layout"),
    
	array( "name" => "Header Settings",
		"type" => "heading",
		"icon" => "layout"),
    
  array( "name" => "Header Layout",
		"desc" => "Set the layout of your Site Title and the Main Header Menu.",
		"id" => $foreverwood_shortname."_header_layout",
		"std" => "",
		"class" => "hidden",
		"options" => array(	'a' => 'Centered',
							'b' => 'Wide'),
		"type" => "select"),
    
  array( "name" => "Display Header Image",
		"desc" => "Set the <a href='themes.php?page=custom-header'>Header Image</a> to be visible on all pages or only on the homepage.",
		"id" => $foreverwood_shortname."_display_header_image",
		"std" => "",
		"class" => "hidden",
		"options" => array(	'a' => 'Everywhere',
							'b' => 'Only on Homepage',
              'c' => 'Everywhere except Homepage'),
		"type" => "select"),
    
  array( "name" => "Title Box/Logo width",
		"desc" => "Set the width of your Site Title or Logo in header (percentages due to the page width). This option is needed only for the Wide Header Layout.",
		"id" => $foreverwood_shortname."_page_title_width",
		"std" => "",
		"class" => "hidden",
		"options" => array(	'a' => '50%',
							'b' => '0%',
              'c' => '10%',
              'd' => '20%',
              'e' => '30%',
              'f' => '40%',
              'g' => '60%',
              'h' => '70%',
              'i' => '80%',
              'j' => '90%',
              'k' => '100%'),
		"type" => "select"),
    
  array( "name" => "Menu Box width",
		"desc" => "Set the width of your Main Header Menu (percentages due to the page width). This option is needed only for the Wide Header Layout.",
		"id" => $foreverwood_shortname."_header_menu_width",
		"std" => "",
		"class" => "hidden",
		"options" => array(	'a' => '50%',
							'b' => '0%',
              'c' => '10%',
              'd' => '20%',
              'e' => '30%',
              'f' => '40%',
              'g' => '60%',
              'h' => '70%',
              'i' => '80%',
              'j' => '90%',
              'k' => '100%'),
		"type" => "select"),
    
  array( "name" => "Fixed Menu",
		"desc" => "Select whether to enable or disable fixing of the Main Header Menu at the top of the browser window. This option works only in the Centered header layout.",
		"id" => $foreverwood_shortname."_fixed_menu",
		"std" => "",
		"class" => "hidden",
		"options" => array(	'a' => 'Enable',
							'b' => 'Disable',),
		"type" => "select"),
    
	array( "name" => "Logo URL",
		"desc" => "Upload your own logo in .jpg, .gif or .png format or directly insert its URL. The logo will automatically replace the Site Title.",
		"id" => $foreverwood_shortname."_logo_url",
		"type" => "upload-logo",
		"std" => ""),
    
	// Homepage header    
  array( "name" => "Homepage Header Image",
		"type" => "heading",
		"icon" => "layout"),
    
  array( "name" => "Header Image Headline",
	  "desc" => "Fill here the headline displayed in the Header Image.",
		"id" => $foreverwood_shortname."_header_image_headline",
		"type" => "text",
		"std" => ""),
    
  array( "name" => "Header Image Link URL",
	   "desc" => "Fill here the URL address of a link displayed in the Header Image.",
		"id" => $foreverwood_shortname."_header_image_link_url",
		"type" => "text",
		"std" => ""),
    
  array( "name" => "Header Image Link Text",
	   "desc" => "Fill here the text of a link displayed in the Header Image.",
		"id" => $foreverwood_shortname."_header_image_link_text",
		"type" => "text",
		"std" => ""),
    
array( "name" => "tab_content_2",
		"type" => "tabcontent-close",
		"icon" => "layout"),
// Close Header settings
 
  
// Open Posts/Pages settings
  array( "name" => "tab_content_3",
		"type" => "tabcontent-open",
		"display" => "none",
		"icon" => "layout"),
    
	array( "name" => "Post Settings",
		"type" => "heading",
		"icon" => "layout"),
    
  array( "name" => "Display Featured Image on single posts",
		"desc" => "Set the Featured Image to be visible or not on the single posts.",
		"id" => $foreverwood_shortname."_display_image_post",
		"std" => "",
		"class" => "hidden",
		"options" => array(	'a' => 'Display',
							'b' => 'Hide'),
		"type" => "select"),
    
  array( "name" => "Display Meta Box on single posts",
		"desc" => "Set the Meta Box (with publish date, author, category and tags) to be visible or not on the single posts.",
		"id" => $foreverwood_shortname."_display_meta_post",
		"std" => "",
		"class" => "hidden",
		"options" => array(	'a' => 'Display',
							'b' => 'Hide'),
		"type" => "select"),
    
	array( "name" => "Next/Previous Post Navigation",
		"desc" => "This feature gives the possibility to easy navigate between the posts in chronological order.",
		"id" => $foreverwood_shortname."_next_preview_post",
		"std" => "",
		"class" => "hidden",
		"options" => array(	'a' => 'Display',
							'b' => 'Hide'),
		"type" => "select"),
    
  array( "name" => "Display Related Posts on single posts",
		"desc" => "Set the Related Posts box to be visible or not on the single posts.",
		"id" => $foreverwood_shortname."_display_related_posts",
		"std" => "",
		"class" => "hidden",
		"options" => array(	'a' => 'Display',
							'b' => 'Hide'),
		"type" => "select"),
    
  array( "name" => "Related Posts headline",
	"desc" => "Fill here the headline of Related Posts box.",
		"id" => $foreverwood_shortname."_related_posts_headline",
		"type" => "text",
		"std" => "Related Posts"),
    
  array( "name" => "Number of Related Posts",
	"desc" => "Fill here the number of related posts to be displayed on the single posts.",
		"id" => $foreverwood_shortname."_related_posts_number",
		"type" => "text",
		"std" => "6"),
    
  array( "name" => "Related Posts format",
		"desc" => "Set here if you want to display the Related Posts as a Slider or as an Unordered List.",
		"id" => $foreverwood_shortname."_related_posts_format",
		"std" => "",
		"class" => "hidden",
		"options" => array(	'a' => 'Slider',
							'b' => 'Unordered List'),
		"type" => "select"),

// PAGES
	array( "name" => "Page Settings",
		"type" => "heading",
		"icon" => "layout"),
    
  array( "name" => "Display Featured Image on pages",
		"desc" => "Set the Featured Image to be visible or not on pages.",
		"id" => $foreverwood_shortname."_display_image_page",
		"std" => "",
		"class" => "hidden",
		"options" => array(	'a' => 'Display',
							'b' => 'Hide'),
		"type" => "select"),
    
array( "name" => "tab_content_3",
		"type" => "tabcontent-close",
		"icon" => "layout"),
// Close Posts/Pages settings


// Open Post Entries settings
array( "name" => "tab_content_4",
		"type" => "tabcontent-open",
		"display" => "none",
		"icon" => "layout"),
    
	// Post Entries   
  array( "name" => "Post Entries Settings",
		"type" => "heading",
		"icon" => "layout"),
    
  array( "name" => "Post Entries Format",
		"desc" => "Set the way how the post entries to be displayed.",
		"id" => $foreverwood_shortname."_post_entry_format",
		"std" => "",
		"class" => "hidden",
		"options" => array(	'a' => 'One Column',
              'b' => 'Grid - Masonry'),
		"type" => "select"),
    
  array( "name" => "Number of Columns in the Grid",
		"desc" => "Set the number of columns to be displayed in the Grid - Masonry format.",
		"id" => $foreverwood_shortname."_grid_columns",
		"std" => "",
		"class" => "hidden",
		"options" => array(	'a' => '3',
              'b' => '4',
              'c' => '2'),
		"type" => "select"),
    
  array( "name" => "Display Meta Box on Post Entries",
		"desc" => "Set the Meta Box (with publish date, author, comments, category and tags) to be visible or not on the post entries.",
		"id" => $foreverwood_shortname."_display_meta_post_entry",
		"std" => "",
		"class" => "hidden",
		"options" => array(	'a' => 'Display',
							'b' => 'Hide'),
		"type" => "select"),  
    
  array( "name" => "Featured Images Hover Effect",
		"desc" => "Select an effect for the Featured Images after placing the mouse cursor on them.",
		"id" => $foreverwood_shortname."_featured_image_hover",
		"std" => "",
		"class" => "hidden",
		"options" => array(	'a' => 'None',
              'b' => 'Fade',
              'c' => 'Focus',
              'd' => 'Shadow',
              'e' => 'Tilt'),
		"type" => "select"),
    
  array( "name" => "Content/Excerpt Displaying",
		"desc" => "Select whether you want to display the full content or the post excerpts on your archive pages (including all archives, homepage and search results).",
		"id" => $foreverwood_shortname."_content_archives",
		"std" => "",
		"class" => "hidden",
		"options" => array(	'a' => 'Excerpt',
							'b' => 'Content',),
		"type" => "select"),
    
  array( "name" => "Excerpt Length",
	"desc" => "Set here the number of words you wish to display in post excerpts.",
		"id" => $foreverwood_shortname."_excerpt_length",
		"type" => "text",
		"std" => "40"),
    
  array( "name" => "Blog Page",
		"type" => "heading",
		"icon" => "layout"),
  
  array( "name" => "Display Site Description",
		"desc" => "Set the Site Description to be visible or not on your Latest Posts page.",
		"id" => $foreverwood_shortname."_display_site_description",
		"std" => "",
		"class" => "hidden",
		"options" => array(	'a' => 'Display',
							'b' => 'Hide'),
		"type" => "select"),

array( "name" => "tab_content_4",
		"type" => "tabcontent-close",
		"icon" => "layout"),
// Close Post Entries settings


// Colors Tab Container
array( "name" => "tab_content_5",
		"type" => "tabcontent-open",
		"display" => "none",
		"icon" => "layout"),
       
  array( "name" => "Color Settings",
		"type" => "heading",
		"icon" => "layout"),   
    
	array("name" => "Since version 2.0.0, the Color settings have been moved to the <a href='customize.php'>Customizer</a>.",
		"type" => "infotext"), 
	
	array( "name" => "tab_content_5",
		"type" => "tabcontent-close",
		"icon" => "layout"),
// Close Color settings

// Open Fonts
array( "name" => "tab_content_6",
		"type" => "tabcontent-open",
		"display" => "none",
		"icon" => "layout"),

	array( "name" => "Font Settings",
		"type" => "heading",
		"icon" => "layout"),		
    
	array("name" => "Since version 2.0.0, the Font settings have been moved to the <a href='customize.php'>Customizer</a>.",
		"type" => "infotext"),	

array( "name" => "tab_content_6",
		"type" => "tabcontent-close",
		"icon" => "layout"),
// Close fonts

// Open Social Networking
array( "name" => "tab_content_7",
		"type" => "tabcontent-open",
		"display" => "none",
		"icon" => "layout"),

	array( "name" => "Share Buttons",
		"type" => "heading",
		"icon" => "layout"),
    
  array( "name" => "Share Buttons Language",
		"desc" => "Set here a language of the Facebook Like and Share buttons.",
		"id" => $foreverwood_shortname."_share_buttons_language",
		"std" => "",
		"class" => "hidden",
		"options" => array(	'a' => 'en_US',
							'b' => 'cs_CZ',
              'c' => 'da_DK',
              'd' => 'de_DE',
              'e' => 'el_GR',
              'f' => 'es_ES',
              'g' => 'fr_FR', 
              'h' => 'hi_IN',
              'i' => 'it_IT',
              'j' => 'nl_NL',
              'k' => 'pl_PL',
              'l' => 'ru_RU',
              'm' => 'sk_SK',
              'n' => 'tr_TR'),
		"type" => "select"),
	
	array( "name" => "Share Buttons on Pages",
		"desc" => "This shows Facebook, Twitter and Google '+1' share buttons below your pages content.",
		"id" => $foreverwood_shortname."_share_buttons_page",
		"std" => "",
		"class" => "hidden",
		"options" => array(	'a' => 'Display',
							'b' => 'Hide'),
		"type" => "select"),
	
	array( "name" => "Share Buttons on Posts",
		"desc" => "This shows Facebook, Twitter and Google '+1' share buttons below your posts content.",
		"id" => $foreverwood_shortname."_share_buttons_post",
		"std" => "",
		"class" => "hidden",
		"options" => array(	'a' => 'Display',
							'b' => 'Hide'),
		"type" => "select"),
    
	array( "name" => "Share Buttons on Post Entries",
		"desc" => "This shows Facebook, Twitter and Google '+1' share buttons on the Post Entries (on homepage/archive pages). Note: share buttons are displayed only in the One Column layout, not in the Grid - Masonry layout.",
		"id" => $foreverwood_shortname."_share_buttons_post_entry",
		"std" => "",
		"class" => "hidden",
		"options" => array(	'a' => 'Display',
							'b' => 'Hide'),
		"type" => "select"),
    
  array( "name" => "Share Buttons on Products",
		"desc" => "This shows Facebook, Twitter and Google '+1' share buttons on the individual WooCommerce Products.",
		"id" => $foreverwood_shortname."_share_buttons_products",
		"std" => "",
		"class" => "hidden",
		"options" => array(	'a' => 'Display',
							'b' => 'Hide'),
		"type" => "select"),

array( "name" => "tab_content_7",
		"type" => "tabcontent-close",
		"icon" => "layout"),
// Close Social Networking


// Open Other settings
array( "name" => "tab_content_8",
		"type" => "tabcontent-open",
		"display" => "none",
		"icon" => "layout"),

	array( "name" => "Other Settings",
		"type" => "heading",
		"icon" => "layout"),
		

array( "type" => "infotext",
		"name" => "OPTIONS: <a href='widgets.php'>Widgets</a> | <a href='nav-menus.php'>Menus</a> | <a href='themes.php?page=custom-header'>Header Image</a> | <a href='themes.php?page=custom-background'>Background Settings</a>"),
    
  array( "name" => "Custom CSS",
		"desc" => "Fill here your own cascading style sheet information (CSS).<br />Example: #content p {font-size: 15px;} #sidebar p {font-size: 14px;}",
		"id" => $foreverwood_shortname."_own_css",
		"type" => "textarea",
		"std" => ""),
    
  array( "name" => "Custom JavaScript - Header",
		"desc" => "Fill here your custom JavaScript code which will appear in the 'head' section. <br />Please do not forget to wrap your JavaScript code between the &lt;script type='text/javascript'&gt;&lt;/script&gt; tags!",
		"id" => $foreverwood_shortname."_own_javascript_header",
		"type" => "textarea",
		"std" => ""),
    
  array( "name" => "Custom JavaScript - Footer",
		"desc" => "Fill here your custom JavaScript code which will appear just before the closing 'body' tag. <br />Please do not forget to wrap your JavaScript code between the &lt;script type='text/javascript'&gt;&lt;/script&gt; tags!",
		"id" => $foreverwood_shortname."_own_javascript_footer",
		"type" => "textarea",
		"std" => ""),

array( "name" => "tab_content_8",
		"type" => "tabcontent-close",
		"icon" => "layout"),
// Close Other settings

array("name" => "Close Container",
		"type" => "container-close",
		"icon" => "layout"),

array( "type" => "close") 
); 


add_action('admin_head', 'foreverwood_admin_css');

function foreverwood_admin_css() { ?>
     
	<script language="JavaScript">
		jQuery.noConflict();
		jQuery(document).ready(function($) {
	
		$(".tabs .tab[id^=tab_menu]").click(function() {
			var curMenu=$(this);
			$(".tabs .tab[id^=tab_menu]").removeClass("selected");
			curMenu.addClass("selected");
	
			var index=curMenu.attr("id").split("tab_menu_")[1];
			$(".curvedContainer .tabcontent").css("display","none");
			$(".curvedContainer #tab_content_"+index).css("display","block");
		});
	});
	</script>

<?php }
function foreverwood_add_admin() {
	global $foreverwood_options; global $foreverwood_themename; global $foreverwood_shortname;
	if ( isset ( $_GET['page'] ) && ( $_GET['page'] == basename(__FILE__) ) ) {
		if ( isset ($_REQUEST['action']) && ( 'save' == $_REQUEST['action'] ) ){
      if ( !empty($_POST) && check_admin_referer('foreverwood_save_options','foreverwood_save_options_field' ) ) {
			foreach ( $foreverwood_options as $value ) {
				if ( array_key_exists('id', $value) ) {
					if ( isset( $_REQUEST[ $value['id'] ] ) ) {
						update_option( $value['id'], $_REQUEST[ $value['id'] ]  );
					}
					else {
						delete_option( $value['id'] );
					}
				}
			}
			header("Location: themes.php?page=".basename(__FILE__)."&saved=true");
		}
    }else if ( isset ($_REQUEST['action']) && ( 'reset' == $_REQUEST['action'] ) ) {
			foreach ($foreverwood_options as $value) {
				if ( array_key_exists('id', $value) ) {
					delete_option( $value['id'] );
				}
			}
			header("Location: themes.php?page=".basename(__FILE__)."&reset=true");
		}
	}
	add_theme_page($foreverwood_themename." Options", 'Theme Options', 'edit_theme_options', 'fw-options.php', 'foreverwood_admin', '', '1');
}



function foreverwood_admin() {
	global $foreverwood_themename, $foreverwood_shortname, $foreverwood_options, $foreverwood_themever, $fwver, $foreverwood_manualurl; 
	if (isset($_REQUEST["saved"]) && !empty($_REQUEST["saved"])) echo '<div id="message" class="updated fade"><p><strong>'.$foreverwood_themename.' settings saved.</strong></p></div>';
	if (isset($_REQUEST["reset"]) && !empty($_REQUEST["reset"])) echo '<div id="message" class="error fade"><p><strong>'.$foreverwood_themename.' '.__( 'settings reset.', 'foreverwood' ).'</strong></p></div>';
  wp_enqueue_style('foreverwood-framework-style', get_template_directory_uri() . '/functions/be/css.css');
  wp_enqueue_style('foreverwood-framework-icons', get_template_directory_uri() . '/css/elegantfont.css');
?>

	<div id="wrap_fm"><!-- [ Header ]-->
		<div class="header_fm">
			<div class="logo_fm">Theme Options</div>
			<div class="frame_vers_fm">
				<div class="theme_version_fm"><?php echo $foreverwood_themename;?> <strong><?php echo $foreverwood_themever;?></strong></div>
				<div class="clear"></div>
			</div>
		</div>

		<!-- [ Top Menu ]-->
		<div class="top_menu_fm">
			<a title="Theme Documentation" target="_blank"class="doc_fm" href="<?php echo $foreverwood_manualurl; ?>">Documentation</a><a title="Theme Support" target="_blank" class="support_fm" href="http://themes.tomastoman.cz/support">Support</a>
		</div>


	<form method="post">
	<?php 
	foreach ($foreverwood_options as $value) {
	switch ( $value['type'] ) {
	case "open":
	?> 
	<?php break; case "title": ?> 

	<!-- [ Body ]-->
	<div id="wrap_body_fm">
	<div class="tabscontainer">


	<?php break; case "close": ?> 

	<!-- start form -->
	<div class="col_100">
		<div class="col_50">
			<input id="submit" class="button-primary" name="save" type="submit" value="Save all changes" /> <input type="hidden" name="action" value="save" />
      <?php wp_nonce_field( 'foreverwood_save_options','foreverwood_save_options_field' ); ?>
			</form>
		</div>
	 	<div class="col_50">
		 	<form method="post">
				<input class="reset button-primary" name="reset" type="submit" value="Reset all settings" />
				<input type="hidden" name="action" value="reset" />
			</form>
		</div>	
		<div class="clear"></div>
	</div>
	<!-- end form -->
</div></div>

<?php break;case 'text': ?>
	<div class="name_fm"><?php echo $value['name']; ?></div>
	<div class="input_fm"><input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_option( $value['id'] ) != "") { echo stripslashes(get_option( $value['id'] )); } else { echo $value['std']; } ?>" /></div>
	<div class="desc_fm"><small><?php echo $value['desc']; ?></small></div>
  
<?php break;case 'upload-favicon': ?>
<?php wp_enqueue_media();
wp_enqueue_script('foreverwood-my-admin-js-favicon', get_template_directory_uri() . '/js/custom-uploader-favicon.js', array('jquery')); ?>
	<div class="name_fm"><?php echo $value['name']; ?></div>
  <input class="upload_image" type="text" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="<?php if ( get_option( $value['id'] ) != "") { echo stripslashes(get_option( $value['id'] )); } else { echo $value['std']; } ?>" /> 
  <input class="upload_image_button button" type="button" value="Upload Image" />
	<div class="desc_fm"><small><?php echo $value['desc']; ?></small></div>
  
<?php break;case 'upload-logo': ?>
<?php wp_enqueue_script('foreverwood-my-admin-js-logo', get_template_directory_uri() . '/js/custom-uploader-logo.js', array('jquery')); ?>
	<div class="name_fm"><?php echo $value['name']; ?></div>
  <input class="upload_logo" type="text" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="<?php if ( get_option( $value['id'] ) != "") { echo stripslashes(get_option( $value['id'] )); } else { echo $value['std']; } ?>" /> 
  <input class="upload_logo_button button" type="button" value="Upload Image" />
	<div class="desc_fm"><small><?php echo $value['desc']; ?></small></div>
  
<?php break;case 'color': ?>
<?php wp_register_script( 'foreverwood_jscolor', get_template_directory_uri() . '/js/jscolor/jscolor.js', array(), '1.4.1', true );
wp_enqueue_script('foreverwood_jscolor'); ?>
	<div class="name_fm"><?php echo $value['name']; ?></div>
	<div class="input_fm"><input class="color {hash:true,caps:false,adjust:false,pickerClosable:true}" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="text" value="<?php if ( get_option( $value['id'] ) != "") { echo stripslashes(get_option( $value['id'] )); } else { echo $value['std']; } ?>" /></div>
	<div class="desc_fm"><small><?php echo $value['desc']; ?></small></div>

<?php break; case 'textarea':?>
	<div class="name_fm"><?php echo $value['name']; ?></div>
	<div class="input_fm"><textarea name="<?php echo $value['id']; ?>" style="height: 100px;" type="<?php echo $value['type']; ?>" cols="" rows=""><?php if ( get_option( $value['id'] ) != "") { echo stripslashes(get_option( $value['id'] )); } else { echo $value['std']; } ?></textarea></div>
	<div class="desc_fm"><small><?php echo $value['desc']; ?></small></div>

<?php break; case 'select': ?>
	<div class="name_fm"><?php echo $value['name']; ?></div>
	<div class="input_fm"><select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>"><?php foreach ($value['options'] as $option) { ?>
	<option <?php if ( get_option( $value['id'] ) == $option) { echo ' selected="selected"'; } elseif ($option == $value['std']) { echo ' selected="selected"'; } ?>><?php echo $option; ?></option>
	<?php } ?>
	</select></div>
	<div class="desc_fm"><small><?php echo $value['desc']; ?></small></div>
	
	<?php break; case "heading":?>
	<h1><?php echo $value['name']; ?></h1>
	
	<?php break; case "subheader":?>
	<div class="name_fm"><?php echo $value['name']; ?></div>
	
  <?php break; case "infotext":?>
	<div class="infotext"><?php echo $value['name']; ?></div>
	
	<?php break; case "paragraph":?>
	<div class="desc_fm"><small><?php echo $value['name']; ?></small></div>
  	
	<?php break; case "tabs-open":?>	
	<div class="tabs">
	
	<?php break; case "tabs-close":?>	
	</div>	
	
	<?php break; case "tab":?>	
	<div class="tab<?php echo $value['class']; ?>" id="<?php echo $value['id']; ?>">
	<div class="link"><?php echo $value['name']; ?></div>
	<div class="arrow"></div>
	</div>
 	
 	<?php break; case "container-open":?>	
	<div class="curvedContainer">
 	
 	<?php break; case "container-close":?>	
	</div>	
 	
	<?php break; case "tabcontent-open":?>	
	<div class="tabcontent" id="<?php echo $value['name']; ?>" style="display:<?php echo $value['display']; ?>" >
	
	<?php break; case "tabcontent-close":?>	
	</div>
	
 	
<?php break;

}
}
?>

<?php
}

add_action('admin_menu', 'foreverwood_add_admin');
?>