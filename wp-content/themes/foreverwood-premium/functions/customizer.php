<?php
/**
 * ForeverWood Theme Customizer.
 * @package ForeverWood
 * @since ForeverWood 2.0.0
*/
function foreverwood_customize_register($wp_customize){

$foreverwood_fonts = array(
			'default' => 'default',	
			'Abel' => 'Abel',			
			'Aclonica' => 'Aclonica',
			'Actor' => 'Actor',
			'Adamina' => 'Adamina',
			'Aldrich' => 'Aldrich',
			'Alegreya Sans' => 'Alegreya Sans',
			'Alice' => 'Alice',
			'Alike' => 'Alike',
			'Allan' => 'Allan',
			'Allerta' => 'Allerta',
      'Amarante' => 'Amarante',
			'Amaranth' => 'Amaranth',
      'Andika' => 'Andika',
			'Antic' => 'Antic',
			'Anton' => 'Anton',
			'Arimo' => 'Arimo',	
			'Artifika' => 'Artifika',
			'Arvo' => 'Arvo',
			'Bitter' => 'Bitter',
			'Brawler' => 'Brawler',
			'Buda' => 'Buda',	
      'Butcherman' => 'Butcherman',	
      'Cabin' => 'Cabin',
			'Candal' => 'Candal',
			'Cantarell' => 'Cantarell',	
      'Cherry Swash' => 'Cherry Swash',				
			'Chivo' => 'Chivo',			
			'Coda' => 'Coda',	
      'Concert One' => 'Concert One',		
			'Copse' => 'Copse',
			'Corben' => 'Corben',
			'Cousine' => 'Cousine',			
			'Coustard' => 'Coustard',
			'Covered By Your Grace' => 'Covered By Your Grace',
			'Crafty Girls' => 'Crafty Girls',
			'Crimson Text' => 'Crimson Text',
			'Crushed' => 'Crushed',
			'Cuprum' => 'Cuprum',
			'Damion' => 'Damion',
			'Dancing Script' => 'Dancing Script',
			'Dawning of a New Day' => 'Dawning of a New Day',
			'Days One' => 'Days One',
			'Delius' => 'Delius',
			'Delius Swash Caps' => 'Delius Swash Caps',
			'Delius Unicase' => 'Delius Unicase',
			'Didact Gothic' => 'Didact Gothic',
			'Dorsa' => 'Dorsa',
			'Dosis' => 'Dosis',
			'Droid Sans' => 'Droid Sans',
			'Droid Sans Mono' => 'Droid Sans Mono',
      'Droid Serif' => 'Droid Serif',
			'EB Garamond' => 'EB Garamond',
			'Expletus Sans' => 'Expletus Sans',
			'Fanwood Text' => 'Fanwood Text',
			'Federo' => 'Federo',
			'Fontdiner Swanky' => 'Fontdiner Swanky',
			'Forum' => 'Forum',
			'Francois One' => 'Francois One',
			'Gentium Basic' => 'Gentium Basic',
			'Gentium Book Basic' => 'Gentium Book Basic',
			'Geo' => 'Geo',
			'Geostar' => 'Geostar',
			'Geostar Fill' => 'Geostar Fill',
      'Gilda Display' => 'Gilda Display',
			'Give You Glory' => 'Give You Glory',
			'Gloria Hallelujah' => 'Gloria Hallelujah',
			'Goblin One' => 'Goblin One',
			'Goudy Bookletter 1911' => 'Goudy Bookletter 1911',
			'Gravitas One' => 'Gravitas One',
			'Gruppo' => 'Gruppo',
			'Hammersmith One' => 'Hammersmith One',
			'Hind' => 'Hind',
			'Holtwood One SC' => 'Holtwood One SC',
			'Homemade Apple' => 'Homemade Apple',
			'Inconsolata' => 'Inconsolata',
			'Indie Flower' => 'Indie Flower',
      'IM Fell English' => 'IM Fell English',
			'Irish Grover' => 'Irish Grover',
			'Irish Growler' => 'Irish Growler',
			'Istok Web' => 'Istok Web',
			'Judson' => 'Judson',
			'Julee' => 'Julee',
			'Just Another Hand' => 'Just Another Hand',
			'Just Me Again Down Here' => 'Just Me Again Down Here',
			'Kameron' => 'Kameron',
			'Kelly Slab' => 'Kelly Slab',
			'Kenia' => 'Kenia',
			'Kranky' => 'Kranky',
			'Kreon' => 'Kreon',
			'Kristi' => 'Kristi',
			'La Belle Aurore' => 'La Belle Aurore',
      'Lato' => 'Lato',
			'League Script' => 'League Script',
			'Leckerli One' => 'Leckerli One',
			'Lekton' => 'Lekton',
      'Lily Script One' => 'Lily Script One',
			'Limelight' => 'Limelight',
			'Lobster' => 'Lobster',
			'Lobster Two' => 'Lobster Two',
			'Lora' => 'Lora',
			'Love Ya Like A Sister' => 'Love Ya Like A Sister',
			'Loved by the King' => 'Loved by the King',
      'Lovers Quarrel' => 'Lovers Quarrel',
			'Luckiest Guy' => 'Luckiest Guy',
			'Maiden Orange' => 'Maiden Orange',
			'Mako' => 'Mako',
			'Marvel' => 'Marvel',
			'Maven Pro' => 'Maven Pro',
			'Meddon' => 'Meddon',
			'MedievalSharp' => 'MedievalSharp',
      'Medula One' => 'Medula One',
			'Megrim' => 'Megrim',
			'Merienda One' => 'Merienda One',
			'Merriweather' => 'Merriweather',
			'Metrophobic' => 'Metrophobic',
			'Michroma' => 'Michroma',
			'Miltonian Tattoo' => 'Miltonian Tattoo',
			'Miltonian' => 'Miltonian',
			'Modern Antiqua' => 'Modern Antiqua',
			'Molengo' => 'Molengo',
      'Monofett' => 'Monofett',
			'Monoton' => 'Monoton',
      'Montaga' => 'Montaga',
			'Montez' => 'Montez',
      'Montserrat' => 'Montserrat',
			'Mountains of Christmas' => 'Mountains of Christmas',
			'Muli' => 'Muli',
			'Neucha' => 'Neucha',
			'Neuton' => 'Neuton',
			'News Cycle' => 'News Cycle',
			'Nixie One' => 'Nixie One',
			'Nobile' => 'Nobile',
			'Noto Sans' => 'Noto Sans',
			'Nova Cut' => 'Nova Cut',
			'Nova Flat' => 'Nova Flat',
			'Nova Mono' => 'Nova Mono',
			'Nova Oval' => 'Nova Oval',
			'Nova Round' => 'Nova Round',
			'Nova Script' => 'Nova Script',
			'Nova Slim' => 'Nova Slim',
			'Nova Square' => 'Nova Square',
			'Numans' => 'Numans',
			'Nunito' => 'Nunito',
      'Open Sans' => 'Open Sans',
			'Oswald' => 'Oswald',
			'Over the Rainbow' => 'Over the Rainbow',
			'Ovo' => 'Ovo',
			'Oxygen' => 'Oxygen',
			'Pacifico' => 'Pacifico',
			'Passero One' => 'Passero One',
			'Passion One' => 'Passion One',
			'Patrick Hand' => 'Patrick Hand',
			'Paytone One' => 'Paytone One',
			'Permanent Marker' => 'Permanent Marker',
			'Philosopher' => 'Philosopher',
			'Play' => 'Play',
			'Playfair Display' => 'Playfair Display',
			'Podkova' => 'Podkova',
			'Poller One' => 'Poller One',
			'Pompiere' => 'Pompiere',
			'Prata' => 'Prata',
			'Prociono' => 'Prociono',
			'PT Sans' => 'PT Sans',
			'PT Sans Caption' => 'PT Sans Caption',
			'PT Sans Narrow' => 'PT Sans Narrow',
			'PT Serif' => 'PT Serif',
			'PT Serif Caption' => 'PT Serif Caption',
			'Puritan' => 'Puritan',
			'Quattrocento' => 'Quattrocento',
			'Quattrocento Sans' => 'Quattrocento Sans',
			'Questrial' => 'Questrial',
			'Radley' => 'Radley',
			'Raleway' => 'Raleway', 
      'Rationale' => 'Rationale',
			'Redressed' => 'Redressed',
      'Reenie Beanie' => 'Reenie Beanie', 
      'Roboto' => 'Roboto',
      'Roboto Condensed' => 'Roboto Condensed',
			'Rock Salt' => 'Rock Salt',
			'Rochester' => 'Rochester',
			'Rokkitt' => 'Rokkitt',
			'Rosario' => 'Rosario',
			'Ruslan Display' => 'Ruslan Display',
      'Sancreek' => 'Sancreek',
			'Sansita One' => 'Sansita One',
			'Schoolbell' => 'Schoolbell',
			'Shadows Into Light' => 'Shadows Into Light',
			'Shanti' => 'Shanti',
			'Short Stack' => 'Short Stack',
			'Sigmar One' => 'Sigmar One',
			'Six Caps' => 'Six Caps',
			'Slackey' => 'Slackey',
			'Smokum' => 'Smokum',
			'Smythe' => 'Smythe',
			'Sniglet' => 'Sniglet',
			'Snippet' => 'Snippet',
			'Sorts Mill Goudy' => 'Sorts Mill Goudy',
			'Special Elite' => 'Special Elite',
			'Spinnaker' => 'Spinnaker',
			'Stardos Stencil' => 'Stardos Stencil',
			'Sue Ellen Francisco' => 'Sue Ellen Francisco',
			'Sunshiney' => 'Sunshiney',
			'Swanky and Moo Moo' => 'Swanky and Moo Moo',
			'Syncopate' => 'Syncopate',
			'Tangerine' => 'Tangerine',
			'Tenor Sans' => 'Tenor Sans',
			'Terminal Dosis Light' => 'Terminal Dosis Light',
			'Tinos' => 'Tinos',
			'Titillium Web' => 'Titillium Web',
			'Tulpen One' => 'Tulpen One',
			'Ubuntu' => 'Ubuntu',
			'Ultra' => 'Ultra',
      'UnifrakturCook' => 'UnifrakturCook',
			'UnifrakturMaguntia' => 'UnifrakturMaguntia',
      'Unkempt' => 'Unkempt',
			'Unna' => 'Unna',
			'Varela' => 'Varela',
			'Varela Round' => 'Varela Round',
			'Vibur' => 'Vibur',
			'Vidaloka' => 'Vidaloka',
			'Volkhov' => 'Volkhov',
			'Vollkorn' => 'Vollkorn',
			'Voltaire' => 'Voltaire',
			'VT323' => 'VT323',
			'Waiting for the Sunrise' => 'Waiting for the Sunrise',
			'Wallpoet' => 'Wallpoet',
			'Walter Turncoat' => 'Walter Turncoat',
			'Wire One' => 'Wire One',
			'Yanone Kaffeesatz' => 'Yanone Kaffeesatz',
			'Yellowtail' => 'Yellowtail',
			'Yeseva One' => 'Yeseva One',
			'Zeyada' => 'Zeyada');

    $wp_customize->add_section('foreverwood_header_color_settings', array(
        'title'    => __('ForeverWood Header Color Settings', 'foreverwood'),
        'description' => '',
        'priority' => 120,
    ));
    $wp_customize->add_section('foreverwood_content_color_settings', array(
        'title'    => __('ForeverWood Main Content Color Settings', 'foreverwood'),
        'description' => '',
        'priority' => 130,
    ));
    $wp_customize->add_section('foreverwood_sidebar_color_settings', array(
        'title'    => __('ForeverWood Sidebar Color Settings', 'foreverwood'),
        'description' => '',
        'priority' => 140,
    ));
    $wp_customize->add_section('foreverwood_footer_color_settings', array(
        'title'    => __('ForeverWood Footer Color Settings', 'foreverwood'),
        'description' => '',
        'priority' => 150,
    ));
    $wp_customize->add_section('foreverwood_html_color_settings', array(
        'title'    => __('ForeverWood Form/HTML Elements Color Settings', 'foreverwood'),
        'description' => '',
        'priority' => 160,
    ));
    $wp_customize->add_section('foreverwood_font_settings', array(
        'title'    => __('ForeverWood Font Settings', 'foreverwood'),
        'description' => '',
        'priority' => 170,
    ));
    
    //  ==============================
    //  = Character Set              =
    //  ==============================
    $wp_customize->add_setting('foreverwood_character_set', array(
        'default'        => get_option('foreverwood_character_set', 'latin'),
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control('foreverwood_character_set_control', array(
        'label'      => __('Character Set', 'foreverwood'),
        'section'    => 'foreverwood_font_settings',
        'settings'   => 'foreverwood_character_set',
        'type'       => 'radio',
        'choices'    => array(
            'latin' => __( 'Latin' , 'foreverwood' ),
            'latin-ext' => __( 'Latin Extended' , 'foreverwood' ),
            'cyrillic' => __( 'Cyrillic' , 'foreverwood' ),
            'cyrillic-ext' => __( 'Cyrillic Extended' , 'foreverwood' ),
            'greek' => __( 'Greek' , 'foreverwood' ),
            'greek-ext' => __( 'Greek Extended' , 'foreverwood' ),
            'vietnamese' => __( 'Vietnamese' , 'foreverwood' ),
        ),
    ));
 
    //  =============================
    //  = Body font                 =
    //  =============================
     $wp_customize->add_setting('foreverwood_body_google_fonts', array(
        'default'        => get_option('foreverwood_body_google_fonts', 'default'),
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
 
    ));
    $wp_customize->add_control( 'foreverwood_body_google_fonts_control', array(
        'settings' => 'foreverwood_body_google_fonts',
        'label'   => __('Body font', 'foreverwood'),
        'section' => 'foreverwood_font_settings',
        'type'    => 'select',
        'choices'    => $foreverwood_fonts,
    ));
    
    //  ==========================
    //  = Body font size         =
    //  ==========================
    $wp_customize->add_setting('foreverwood_body_google_fonts_size', array(
        'default'        => get_option('foreverwood_body_google_fonts_size', '14'),
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control('foreverwood_body_google_fonts_size_control', array(
        'label'      => __('Body font size', 'foreverwood'),
        'section'    => 'foreverwood_font_settings',
        'settings'   => 'foreverwood_body_google_fonts_size',
    ));
    
    //  =============================
    //  = Site Title font           =
    //  =============================
     $wp_customize->add_setting('foreverwood_headings_google_fonts', array(
        'default'        => get_option('foreverwood_headings_google_fonts', 'default'),
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
 
    ));
    $wp_customize->add_control( 'foreverwood_headings_google_fonts_control', array(
        'settings' => 'foreverwood_headings_google_fonts',
        'label'   => __('Site Title font', 'foreverwood'),
        'section' => 'foreverwood_font_settings',
        'type'    => 'select',
        'choices'    => $foreverwood_fonts,
    ));
    
    //  ==========================
    //  = Site Title font size   =
    //  ==========================
    $wp_customize->add_setting('foreverwood_headings_google_fonts_size', array(
        'default'        => get_option('foreverwood_headings_google_fonts_size', '48'),
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control('foreverwood_headings_google_fonts_size_control', array(
        'label'      => __('Site Title font size', 'foreverwood'),
        'section'    => 'foreverwood_font_settings',
        'settings'   => 'foreverwood_headings_google_fonts_size',
    ));
    
    //  =============================
    //  = Page/Post Headlines font  =
    //  =============================
     $wp_customize->add_setting('foreverwood_headline_google_fonts', array(
        'default'        => get_option('foreverwood_headline_google_fonts', 'default'),
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
 
    ));
    $wp_customize->add_control( 'foreverwood_headline_google_fonts_control', array(
        'settings' => 'foreverwood_headline_google_fonts',
        'label'   => __('Page/Post Headlines (h1 - h6) font', 'foreverwood'),
        'section' => 'foreverwood_font_settings',
        'type'    => 'select',
        'choices'    => $foreverwood_fonts,
    ));
    
    //  ==========================
    //  = H1 Headlines font size =
    //  ==========================
    $wp_customize->add_setting('foreverwood_headline_h1_size', array(
        'default'        => get_option('foreverwood_headline_h1_size', '28'),
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control('foreverwood_headline_h1_size_control', array(
        'label'      => __('H1 Headlines font size', 'foreverwood'),
        'section'    => 'foreverwood_font_settings',
        'settings'   => 'foreverwood_headline_h1_size',
    ));
    
    //  ==========================
    //  = H2 Headlines font size =
    //  ==========================
    $wp_customize->add_setting('foreverwood_headline_h2_size', array(
        'default'        => get_option('foreverwood_headline_h2_size', '22'),
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control('foreverwood_headline_h2_size_control', array(
        'label'      => __('H2 Headlines font size', 'foreverwood'),
        'section'    => 'foreverwood_font_settings',
        'settings'   => 'foreverwood_headline_h2_size',
    ));
    
    //  ==========================
    //  = H3 Headlines font size =
    //  ==========================
    $wp_customize->add_setting('foreverwood_headline_h3_size', array(
        'default'        => get_option('foreverwood_headline_h3_size', '19'),
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control('foreverwood_headline_h3_size_control', array(
        'label'      => __('H3 Headlines font size', 'foreverwood'),
        'section'    => 'foreverwood_font_settings',
        'settings'   => 'foreverwood_headline_h3_size',
    ));
    
    //  ==========================
    //  = H4 Headlines font size =
    //  ==========================
    $wp_customize->add_setting('foreverwood_headline_h4_size', array(
        'default'        => get_option('foreverwood_headline_h4_size', '17'),
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control('foreverwood_headline_h4_size_control', array(
        'label'      => __('H4 Headlines font size', 'foreverwood'),
        'section'    => 'foreverwood_font_settings',
        'settings'   => 'foreverwood_headline_h4_size',
    ));
    
    //  ==========================
    //  = H5 Headlines font size =
    //  ==========================
    $wp_customize->add_setting('foreverwood_headline_h5_size', array(
        'default'        => get_option('foreverwood_headline_h5_size', '15'),
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control('foreverwood_headline_h5_size_control', array(
        'label'      => __('H5 Headlines font size', 'foreverwood'),
        'section'    => 'foreverwood_font_settings',
        'settings'   => 'foreverwood_headline_h5_size',
    ));
    
    //  ==========================
    //  = H6 Headlines font size =
    //  ==========================
    $wp_customize->add_setting('foreverwood_headline_h6_size', array(
        'default'        => get_option('foreverwood_headline_h6_size', '14'),
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control('foreverwood_headline_h6_size_control', array(
        'label'      => __('H6 Headlines font size', 'foreverwood'),
        'section'    => 'foreverwood_font_settings',
        'settings'   => 'foreverwood_headline_h6_size',
    ));
    
    //  =============================================
    //  = ForeverWood Posts Widgets headlines font =
    //  =============================================
     $wp_customize->add_setting('foreverwood_headline_box_google_fonts', array(
        'default'        => get_option('foreverwood_headline_box_google_fonts', 'default'),
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
 
    ));
    $wp_customize->add_control( 'foreverwood_headline_box_google_fonts_control', array(
        'settings' => 'foreverwood_headline_box_google_fonts',
        'label'   => __('ForeverWood Posts Widgets headlines font', 'foreverwood'),
        'section' => 'foreverwood_font_settings',
        'type'    => 'select',
        'choices'    => $foreverwood_fonts,
    ));
    
    //  ==================================================
    //  = ForeverWood Posts Widgets headlines font size =
    //  ==================================================
    $wp_customize->add_setting('foreverwood_headline_box_google_fonts_size', array(
        'default'        => get_option('foreverwood_headline_box_google_fonts_size', '28'),
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control('foreverwood_headline_box_google_fonts_size_control', array(
        'label'      => __('ForeverWood Posts Widgets headlines font size', 'foreverwood'),
        'section'    => 'foreverwood_font_settings',
        'settings'   => 'foreverwood_headline_box_google_fonts_size',
    ));
    
    //  =============================
    //  = Post Entry Headline font  =
    //  =============================
     $wp_customize->add_setting('foreverwood_postentry_google_fonts', array(
        'default'        => get_option('foreverwood_postentry_google_fonts', 'default'),
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
 
    ));
    $wp_customize->add_control( 'foreverwood_postentry_google_fonts_control', array(
        'settings' => 'foreverwood_postentry_google_fonts',
        'label'   => __('Post Entry Headline font', 'foreverwood'),
        'section' => 'foreverwood_font_settings',
        'type'    => 'select',
        'choices'    => $foreverwood_fonts,
    ));
    
    //  =================================
    //  = Post Entry Headline font size =
    //  =================================
    $wp_customize->add_setting('foreverwood_postentry_google_fonts_size', array(
        'default'        => get_option('foreverwood_postentry_google_fonts_size', '23'),
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control('foreverwood_postentry_google_fonts_size_control', array(
        'label'      => __('Post Entry Headline font size', 'foreverwood'),
        'section'    => 'foreverwood_font_settings',
        'settings'   => 'foreverwood_postentry_google_fonts_size',
    ));
    
    //  ========================================
    //  = Sidebar/Footer Widget Headlines font =
    //  ========================================
     $wp_customize->add_setting('foreverwood_sidebar_google_fonts', array(
        'default'        => get_option('foreverwood_sidebar_google_fonts', 'default'),
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
 
    ));
    $wp_customize->add_control( 'foreverwood_sidebar_google_fonts_control', array(
        'settings' => 'foreverwood_sidebar_google_fonts',
        'label'   => __('Sidebar/Footer Widget Headlines font', 'foreverwood'),
        'section' => 'foreverwood_font_settings',
        'type'    => 'select',
        'choices'    => $foreverwood_fonts,
    ));
    
    //  ======================================
    //  = Sidebar Widget Headlines font size =
    //  ======================================
    $wp_customize->add_setting('foreverwood_sidebar_google_fonts_size', array(
        'default'        => get_option('foreverwood_sidebar_google_fonts_size', '19'),
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control('foreverwood_sidebar_google_fonts_size_control', array(
        'label'      => __('Sidebar Widget Headlines font size', 'foreverwood'),
        'section'    => 'foreverwood_font_settings',
        'settings'   => 'foreverwood_sidebar_google_fonts_size',
    ));
    
    //  ======================================
    //  = Footer Widget Headlines font size  =
    //  ======================================
    $wp_customize->add_setting('foreverwood_footer_google_fonts_size', array(
        'default'        => get_option('foreverwood_footer_google_fonts_size', '19'),
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control('foreverwood_footer_google_fonts_size_control', array(
        'label'      => __('Footer Widget Headlines font size', 'foreverwood'),
        'section'    => 'foreverwood_font_settings',
        'settings'   => 'foreverwood_footer_google_fonts_size',
    ));
    
    //  =============================
    //  = Main Header Menu font     =
    //  =============================
     $wp_customize->add_setting('foreverwood_menu_google_fonts', array(
        'default'        => get_option('foreverwood_menu_google_fonts', 'default'),
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
 
    ));
    $wp_customize->add_control( 'foreverwood_menu_google_fonts_control', array(
        'settings' => 'foreverwood_menu_google_fonts',
        'label'   => __('Main Header Menu font', 'foreverwood'),
        'section' => 'foreverwood_font_settings',
        'type'    => 'select',
        'choices'    => $foreverwood_fonts,
    ));
    
    //  ==============================
    //  = Main Header Menu font size =
    //  ==============================
    $wp_customize->add_setting('foreverwood_menu_google_fonts_size', array(
        'default'        => get_option('foreverwood_menu_google_fonts_size', '15'),
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control('foreverwood_menu_google_fonts_size_control', array(
        'label'      => __('Main Header Menu font size', 'foreverwood'),
        'section'    => 'foreverwood_font_settings',
        'settings'   => 'foreverwood_menu_google_fonts_size',
    ));
    
//  ===========================================================================
//  = COLOR SETTINGS                                                          =
//  ===========================================================================
    
    //  ======================================================
    //  = Social Networks Links background color             =
    //  ======================================================
    $wp_customize->add_setting('foreverwood_top_menu_background_color', array(
        'default'           => get_option('foreverwood_top_menu_background_color', ''),
        'capability'        => 'edit_theme_options',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'foreverwood_top_menu_background_color_control', array(
        'label'    => __('Social Networks Links background color', 'foreverwood'),
        'section'  => 'foreverwood_header_color_settings',
        'settings' => 'foreverwood_top_menu_background_color',
    )));
    
    //  =========================================================
    //  = Social Networks Links border-bottom color             =
    //  =========================================================
    $wp_customize->add_setting('foreverwood_top_menu_border_color', array(
        'default'           => get_option('foreverwood_top_menu_border_color', ''),
        'capability'        => 'edit_theme_options',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'foreverwood_top_menu_border_color_control', array(
        'label'    => __('Social Networks Links border-bottom color', 'foreverwood'),
        'section'  => 'foreverwood_header_color_settings',
        'settings' => 'foreverwood_top_menu_border_color',
    )));
    
    //  ===================================
    //  = Social Networks Links color     =
    //  ===================================
    $wp_customize->add_setting('foreverwood_social_icons_color', array(
        'default'           => get_option('foreverwood_social_icons_color', ''),
        'capability'        => 'edit_theme_options',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'foreverwood_social_icons_color_control', array(
        'label'    => __('Social Networks Links color', 'foreverwood'),
        'section'  => 'foreverwood_header_color_settings',
        'settings' => 'foreverwood_social_icons_color',
    )));
    
    //  =====================================
    //  = Social Networks Links hover color =
    //  =====================================
    $wp_customize->add_setting('foreverwood_social_icons_hover_color', array(
        'default'           => get_option('foreverwood_social_icons_hover_color', ''),
        'capability'        => 'edit_theme_options',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'foreverwood_social_icons_hover_color_control', array(
        'label'    => __('Social Networks Links hover color', 'foreverwood'),
        'section'  => 'foreverwood_header_color_settings',
        'settings' => 'foreverwood_social_icons_hover_color',
    )));
    
    //  ====================================
    //  = Main Menu Links color            =
    //  ====================================
    $wp_customize->add_setting('foreverwood_main_header_menu_color', array(
        'default'           => get_option('foreverwood_main_header_menu_color', ''),
        'capability'        => 'edit_theme_options',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'foreverwood_main_header_menu_color_control', array(
        'label'    => __('Main Menu Links color', 'foreverwood'),
        'section'  => 'foreverwood_header_color_settings',
        'settings' => 'foreverwood_main_header_menu_color',
    )));
    
    //  ====================================
    //  = Main Menu Links hover color      =
    //  ====================================
    $wp_customize->add_setting('foreverwood_main_header_menu_hover_color', array(
        'default'           => get_option('foreverwood_main_header_menu_hover_color', ''),
        'capability'        => 'edit_theme_options',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'foreverwood_main_header_menu_hover_color_control', array(
        'label'    => __('Main Menu Links hover color', 'foreverwood'),
        'section'  => 'foreverwood_header_color_settings',
        'settings' => 'foreverwood_main_header_menu_hover_color',
    )));
    
    //  ==========================================
    //  = Active Main Menu Links color           =
    //  ==========================================
    $wp_customize->add_setting('foreverwood_active_main_header_menu_color', array(
        'default'           => get_option('foreverwood_active_main_header_menu_color', ''),
        'capability'        => 'edit_theme_options',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'foreverwood_active_main_header_menu_color_control', array(
        'label'    => __('Active Main Menu Links color', 'foreverwood'),
        'section'  => 'foreverwood_header_color_settings',
        'settings' => 'foreverwood_active_main_header_menu_color',
    )));
    
    //  =================================================
    //  = Main Menu drop-down submenus background color =
    //  =================================================
    $wp_customize->add_setting('foreverwood_main_submenu_background_color', array(
        'default'           => get_option('foreverwood_main_submenu_background_color', ''),
        'capability'        => 'edit_theme_options',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'foreverwood_main_submenu_background_color_control', array(
        'label'    => __('Main Menu drop-down submenus background color', 'foreverwood'),
        'section'  => 'foreverwood_header_color_settings',
        'settings' => 'foreverwood_main_submenu_background_color',
    )));
    
    //  =================================================
    //  = Main Menu drop-down submenus text color       =
    //  =================================================
    $wp_customize->add_setting('foreverwood_main_submenu_text_color', array(
        'default'           => get_option('foreverwood_main_submenu_text_color', ''),
        'capability'        => 'edit_theme_options',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'foreverwood_main_submenu_text_color_control', array(
        'label'    => __('Main Menu drop-down submenus text color', 'foreverwood'),
        'section'  => 'foreverwood_header_color_settings',
        'settings' => 'foreverwood_main_submenu_text_color',
    )));
    
    //  ===================================================
    //  = Main Menu drop-down submenus items border color =
    //  ===================================================
    $wp_customize->add_setting('foreverwood_main_submenu_border_color', array(
        'default'           => get_option('foreverwood_main_submenu_border_color', ''),
        'capability'        => 'edit_theme_options',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'foreverwood_main_submenu_border_color_control', array(
        'label'    => __('Main Menu drop-down submenus items border color', 'foreverwood'),
        'section'  => 'foreverwood_header_color_settings',
        'settings' => 'foreverwood_main_submenu_border_color',
    )));
    
    //  ===================================================
    //  = Main Menu drop-down submenus items hover color  =
    //  ===================================================
    $wp_customize->add_setting('foreverwood_main_submenu_hover_color', array(
        'default'           => get_option('foreverwood_main_submenu_hover_color', ''),
        'capability'        => 'edit_theme_options',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'foreverwood_main_submenu_hover_color_control', array(
        'label'    => __('Main Menu drop-down submenus items hover color', 'foreverwood'),
        'section'  => 'foreverwood_header_color_settings',
        'settings' => 'foreverwood_main_submenu_hover_color',
    )));
    
    //  ==========================================
    //  = Header Content background color        =
    //  ==========================================
    $wp_customize->add_setting('foreverwood_header_content_background_color', array(
        'default'           => get_option('foreverwood_header_content_background_color', ''),
        'capability'        => 'edit_theme_options',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'foreverwood_header_content_background_color_control', array(
        'label'    => __('Header Content background color', 'foreverwood'),
        'section'  => 'foreverwood_header_color_settings',
        'settings' => 'foreverwood_header_content_background_color',
    )));
    
    //  ==========================================
    //  = Header Content border-bottom color     =
    //  ==========================================
    $wp_customize->add_setting('foreverwood_header_content_border_color', array(
        'default'           => get_option('foreverwood_header_content_border_color', ''),
        'capability'        => 'edit_theme_options',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'foreverwood_header_content_border_color_control', array(
        'label'    => __('Header Content border-bottom color', 'foreverwood'),
        'section'  => 'foreverwood_header_color_settings',
        'settings' => 'foreverwood_header_content_border_color',
    )));
    
    //  ==========================================
    //  = Site Title color                       =
    //  ==========================================
    $wp_customize->add_setting('foreverwood_site_title_color', array(
        'default'           => get_option('foreverwood_site_title_color', ''),
        'capability'        => 'edit_theme_options',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'foreverwood_site_title_color_control', array(
        'label'    => __('Site Title color', 'foreverwood'),
        'section'  => 'foreverwood_header_color_settings',
        'settings' => 'foreverwood_site_title_color',
    )));
    
    //  ==========================================
    //  = Site Title hover color                 =
    //  ==========================================
    $wp_customize->add_setting('foreverwood_site_title_hover_color', array(
        'default'           => get_option('foreverwood_site_title_hover_color', ''),
        'capability'        => 'edit_theme_options',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'foreverwood_site_title_hover_color_control', array(
        'label'    => __('Site Title hover color', 'foreverwood'),
        'section'  => 'foreverwood_header_color_settings',
        'settings' => 'foreverwood_site_title_hover_color',
    )));
    
    //  ==========================================
    //  = Homepage Header Image Headline color   =
    //  ==========================================
    $wp_customize->add_setting('foreverwood_header_image_headline_color', array(
        'default'           => get_option('foreverwood_header_image_headline_color', ''),
        'capability'        => 'edit_theme_options',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'foreverwood_header_image_headline_color_control', array(
        'label'    => __('Homepage Header Image Headline color', 'foreverwood'),
        'section'  => 'foreverwood_header_color_settings',
        'settings' => 'foreverwood_header_image_headline_color',
    )));
    
    //  ==========================================
    //  = Main Content background color          =
    //  ==========================================
    $wp_customize->add_setting('foreverwood_main_content_background_color', array(
        'default'           => get_option('foreverwood_main_content_background_color', ''),
        'capability'        => 'edit_theme_options',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'foreverwood_main_content_background_color_control', array(
        'label'    => __('Main Content background color', 'foreverwood'),
        'section'  => 'foreverwood_content_color_settings',
        'settings' => 'foreverwood_main_content_background_color',
    )));
    
    //  ==========================================
    //  = Main Content Text color                =
    //  ==========================================
    $wp_customize->add_setting('foreverwood_body_color', array(
        'default'           => get_option('foreverwood_body_color', ''),
        'capability'        => 'edit_theme_options',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'foreverwood_body_color_control', array(
        'label'    => __('Main Content Text color', 'foreverwood'),
        'section'  => 'foreverwood_content_color_settings',
        'settings' => 'foreverwood_body_color',
    )));
    
    //  ==========================================
    //  = Page/Post Title color                  =
    //  ==========================================
    $wp_customize->add_setting('foreverwood_main_headlines_color', array(
        'default'           => get_option('foreverwood_main_headlines_color', ''),
        'capability'        => 'edit_theme_options',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'foreverwood_main_headlines_color_control', array(
        'label'    => __('Page/Post Title color', 'foreverwood'),
        'section'  => 'foreverwood_content_color_settings',
        'settings' => 'foreverwood_main_headlines_color',
    )));
    
    //  ==========================================
    //  = Page/Post Headlines color              =
    //  ==========================================
    $wp_customize->add_setting('foreverwood_headlines_color', array(
        'default'           => get_option('foreverwood_headlines_color', ''),
        'capability'        => 'edit_theme_options',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'foreverwood_headlines_color_control', array(
        'label'    => __('H1 - H6 Headlines color', 'foreverwood'),
        'section'  => 'foreverwood_content_color_settings',
        'settings' => 'foreverwood_headlines_color',
    )));
    
    //  ===============================================
    //  = ForeverWood Posts Widgets headlines color  =
    //  ===============================================
    $wp_customize->add_setting('foreverwood_homepage_headlines_color', array(
        'default'           => get_option('foreverwood_homepage_headlines_color', ''),
        'capability'        => 'edit_theme_options',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'foreverwood_homepage_headlines_color_control', array(
        'label'    => __('ForeverWood Posts Widgets headlines color', 'foreverwood'),
        'section'  => 'foreverwood_content_color_settings',
        'settings' => 'foreverwood_homepage_headlines_color',
    )));
    
    //  ===============================================
    //  = Post Entry Headline color                   =
    //  ===============================================
    $wp_customize->add_setting('foreverwood_post_entry_color', array(
        'default'           => get_option('foreverwood_post_entry_color', ''),
        'capability'        => 'edit_theme_options',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'foreverwood_post_entry_color_control', array(
        'label'    => __('Post Entry Headline color', 'foreverwood'),
        'section'  => 'foreverwood_content_color_settings',
        'settings' => 'foreverwood_post_entry_color',
    )));
    
    //  ===============================================
    //  = Post Entry Headline hover color             =
    //  ===============================================
    $wp_customize->add_setting('foreverwood_post_entry_hover_color', array(
        'default'           => get_option('foreverwood_post_entry_hover_color', ''),
        'capability'        => 'edit_theme_options',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'foreverwood_post_entry_hover_color_control', array(
        'label'    => __('Post Entry Headline hover color', 'foreverwood'),
        'section'  => 'foreverwood_content_color_settings',
        'settings' => 'foreverwood_post_entry_hover_color',
    )));
    
    //  ===============================================
    //  = Main Content Links color                    =
    //  ===============================================
    $wp_customize->add_setting('foreverwood_content_links_color', array(
        'default'           => get_option('foreverwood_content_links_color', ''),
        'capability'        => 'edit_theme_options',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'foreverwood_content_links_color_control', array(
        'label'    => __('Main Content Links color', 'foreverwood'),
        'section'  => 'foreverwood_content_color_settings',
        'settings' => 'foreverwood_content_links_color',
    )));
    
    //  =================================================
    //  = Post Author's Comments background color       =
    //  =================================================
    $wp_customize->add_setting('foreverwood_author_comments_background_color', array(
        'default'           => get_option('foreverwood_author_comments_background_color', ''),
        'capability'        => 'edit_theme_options',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'foreverwood_author_comments_background_color_control', array(
        'label'    => __('Post Author&#8217;s Comments background color', 'foreverwood'),
        'section'  => 'foreverwood_content_color_settings',
        'settings' => 'foreverwood_author_comments_background_color',
    )));
    
    //  =================================================
    //  = Sticky Post background color                  =
    //  =================================================
    $wp_customize->add_setting('foreverwood_sticky_post_background_color', array(
        'default'           => get_option('foreverwood_sticky_post_background_color', ''),
        'capability'        => 'edit_theme_options',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'foreverwood_sticky_post_background_color_control', array(
        'label'    => __('Sticky Post background color', 'foreverwood'),
        'section'  => 'foreverwood_content_color_settings',
        'settings' => 'foreverwood_sticky_post_background_color',
    )));
    
    //  ==================================================
    //  = ForeverWood Posts-Thumbnail items hover color =
    //  ==================================================
    $wp_customize->add_setting('foreverwood_posts_thumbnail_background_color', array(
        'default'           => get_option('foreverwood_posts_thumbnail_background_color', ''),
        'capability'        => 'edit_theme_options',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'foreverwood_posts_thumbnail_background_color_control', array(
        'label'    => __('ForeverWood Posts-Thumbnail items hover color', 'foreverwood'),
        'section'  => 'foreverwood_content_color_settings',
        'settings' => 'foreverwood_posts_thumbnail_background_color',
    )));
    
    //  =================================================
    //  = Highlighted text background color             =
    //  =================================================
    $wp_customize->add_setting('foreverwood_highlight_background_color', array(
        'default'           => get_option('foreverwood_highlight_background_color', ''),
        'capability'        => 'edit_theme_options',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'foreverwood_highlight_background_color_control', array(
        'label'    => __('Highlighted text background color', 'foreverwood'),
        'section'  => 'foreverwood_content_color_settings',
        'settings' => 'foreverwood_highlight_background_color',
    )));
    
    //  ==========================================
    //  = Sidebar Widget Headlines color         =
    //  ==========================================
    $wp_customize->add_setting('foreverwood_sidebar_widget_color', array(
        'default'           => get_option('foreverwood_sidebar_widget_color', ''),
        'capability'        => 'edit_theme_options',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'foreverwood_sidebar_widget_color_control', array(
        'label'    => __('Sidebar Widget Headlines color', 'foreverwood'),
        'section'  => 'foreverwood_sidebar_color_settings',
        'settings' => 'foreverwood_sidebar_widget_color',
    )));
    
    //  =============================================
    //  = Sidebar Widget Headlines background color =
    //  =============================================
    $wp_customize->add_setting('foreverwood_sidebar_widget_background_color', array(
        'default'           => get_option('foreverwood_sidebar_widget_background_color', ''),
        'capability'        => 'edit_theme_options',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'foreverwood_sidebar_widget_background_color_control', array(
        'label'    => __('Sidebar Widget Headlines background color', 'foreverwood'),
        'section'  => 'foreverwood_sidebar_color_settings',
        'settings' => 'foreverwood_sidebar_widget_background_color',
    )));
    
    //  ==========================================
    //  = Sidebar Text color                     =
    //  ==========================================
    $wp_customize->add_setting('foreverwood_sidebar_text_color', array(
        'default'           => get_option('foreverwood_sidebar_text_color', ''),
        'capability'        => 'edit_theme_options',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'foreverwood_sidebar_text_color_control', array(
        'label'    => __('Sidebar Text color', 'foreverwood'),
        'section'  => 'foreverwood_sidebar_color_settings',
        'settings' => 'foreverwood_sidebar_text_color',
    )));
    
    //  ==========================================
    //  = Sidebar Links color                    =
    //  ==========================================
    $wp_customize->add_setting('foreverwood_sidebar_links_color', array(
        'default'           => get_option('foreverwood_sidebar_links_color', ''),
        'capability'        => 'edit_theme_options',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'foreverwood_sidebar_links_color_control', array(
        'label'    => __('Sidebar Links color', 'foreverwood'),
        'section'  => 'foreverwood_sidebar_color_settings',
        'settings' => 'foreverwood_sidebar_links_color',
    )));
    
    //  ==========================================
    //  = Footer background color                =
    //  ==========================================
    $wp_customize->add_setting('foreverwood_footer_background_color', array(
        'default'           => get_option('foreverwood_footer_background_color', ''),
        'capability'        => 'edit_theme_options',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'foreverwood_footer_background_color_control', array(
        'label'    => __('Footer background color', 'foreverwood'),
        'section'  => 'foreverwood_footer_color_settings',
        'settings' => 'foreverwood_footer_background_color',
    )));
    
    //  ==========================================
    //  = Footer Notices background color        =
    //  ==========================================
    $wp_customize->add_setting('foreverwood_footer_notices_background_color', array(
        'default'           => get_option('foreverwood_footer_notices_background_color', ''),
        'capability'        => 'edit_theme_options',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'foreverwood_footer_notices_background_color_control', array(
        'label'    => __('Footer Notices background color', 'foreverwood'),
        'section'  => 'foreverwood_footer_color_settings',
        'settings' => 'foreverwood_footer_notices_background_color',
    )));
    
    //  ==========================================
    //  = Footer Widget Headlines color          =
    //  ==========================================
    $wp_customize->add_setting('foreverwood_footer_widget_color', array(
        'default'           => get_option('foreverwood_footer_widget_color', ''),
        'capability'        => 'edit_theme_options',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'foreverwood_footer_widget_color_control', array(
        'label'    => __('Footer Widget Headlines color', 'foreverwood'),
        'section'  => 'foreverwood_footer_color_settings',
        'settings' => 'foreverwood_footer_widget_color',
    )));
    
    //  ==========================================
    //  = Footer Widget Headlines border color   =
    //  ==========================================
    $wp_customize->add_setting('foreverwood_footer_widget_border_color', array(
        'default'           => get_option('foreverwood_footer_widget_border_color', ''),
        'capability'        => 'edit_theme_options',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'foreverwood_footer_widget_border_color_control', array(
        'label'    => __('Footer Widget Headlines border color', 'foreverwood'),
        'section'  => 'foreverwood_footer_color_settings',
        'settings' => 'foreverwood_footer_widget_border_color',
    )));
    
    //  ==========================================
    //  = Footer Text color                      =
    //  ==========================================
    $wp_customize->add_setting('foreverwood_footer_text_color', array(
        'default'           => get_option('foreverwood_footer_text_color', ''),
        'capability'        => 'edit_theme_options',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'foreverwood_footer_text_color_control', array(
        'label'    => __('Footer Text color', 'foreverwood'),
        'section'  => 'foreverwood_footer_color_settings',
        'settings' => 'foreverwood_footer_text_color',
    )));
    
    //  ==========================================
    //  = Footer Notices Text color              =
    //  ==========================================
    $wp_customize->add_setting('foreverwood_footer_notices_text_color', array(
        'default'           => get_option('foreverwood_footer_notices_text_color', ''),
        'capability'        => 'edit_theme_options',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'foreverwood_footer_notices_text_color_control', array(
        'label'    => __('Footer Notices Text color', 'foreverwood'),
        'section'  => 'foreverwood_footer_color_settings',
        'settings' => 'foreverwood_footer_notices_text_color',
    )));
    
    //  ==========================================
    //  = Footer Links color                     =
    //  ==========================================
    $wp_customize->add_setting('foreverwood_footer_links_color', array(
        'default'           => get_option('foreverwood_footer_links_color', ''),
        'capability'        => 'edit_theme_options',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'foreverwood_footer_links_color_control', array(
        'label'    => __('Footer Links color', 'foreverwood'),
        'section'  => 'foreverwood_footer_color_settings',
        'settings' => 'foreverwood_footer_links_color',
    )));
    
    //  ==========================================
    //  = Footer Notices Links color             =
    //  ==========================================
    $wp_customize->add_setting('foreverwood_footer_notices_links_color', array(
        'default'           => get_option('foreverwood_footer_notices_links_color', ''),
        'capability'        => 'edit_theme_options',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'foreverwood_footer_notices_links_color_control', array(
        'label'    => __('Footer Notices Links color', 'foreverwood'),
        'section'  => 'foreverwood_footer_color_settings',
        'settings' => 'foreverwood_footer_notices_links_color',
    )));
    
    //  ==========================================
    //  = Searchform border color                =
    //  ==========================================
    $wp_customize->add_setting('foreverwood_searchform_border_color', array(
        'default'           => get_option('foreverwood_searchform_border_color', ''),
        'capability'        => 'edit_theme_options',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'foreverwood_searchform_border_color_control', array(
        'label'    => __('Searchform border color', 'foreverwood'),
        'section'  => 'foreverwood_html_color_settings',
        'settings' => 'foreverwood_searchform_border_color',
    )));
    
    //  ==========================================
    //  = Search Button background color         =
    //  ==========================================
    $wp_customize->add_setting('foreverwood_searchform_button_background_color', array(
        'default'           => get_option('foreverwood_searchform_button_background_color', ''),
        'capability'        => 'edit_theme_options',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'foreverwood_searchform_button_background_color_control', array(
        'label'    => __('Search Button background color', 'foreverwood'),
        'section'  => 'foreverwood_html_color_settings',
        'settings' => 'foreverwood_searchform_button_background_color',
    )));
    
    //  ==========================================
    //  = Search Button hover color              =
    //  ==========================================
    $wp_customize->add_setting('foreverwood_searchform_button_hover_color', array(
        'default'           => get_option('foreverwood_searchform_button_hover_color', ''),
        'capability'        => 'edit_theme_options',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'foreverwood_searchform_button_hover_color_control', array(
        'label'    => __('Search Button hover color', 'foreverwood'),
        'section'  => 'foreverwood_html_color_settings',
        'settings' => 'foreverwood_searchform_button_hover_color',
    )));
    
    //  ==========================================
    //  = Form Fields background color           =
    //  ==========================================
    $wp_customize->add_setting('foreverwood_form_fields_background_color', array(
        'default'           => get_option('foreverwood_form_fields_background_color', ''),
        'capability'        => 'edit_theme_options',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'foreverwood_form_fields_background_color_control', array(
        'label'    => __('Form Fields background color', 'foreverwood'),
        'section'  => 'foreverwood_html_color_settings',
        'settings' => 'foreverwood_form_fields_background_color',
    )));
    
    //  ==========================================
    //  = Form Fields border color               =
    //  ==========================================
    $wp_customize->add_setting('foreverwood_form_fields_border_color', array(
        'default'           => get_option('foreverwood_form_fields_border_color', ''),
        'capability'        => 'edit_theme_options',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'foreverwood_form_fields_border_color_control', array(
        'label'    => __('Form Fields border color', 'foreverwood'),
        'section'  => 'foreverwood_html_color_settings',
        'settings' => 'foreverwood_form_fields_border_color',
    )));
    
    //  ==========================================
    //  = Buttons background color               =
    //  ==========================================
    $wp_customize->add_setting('foreverwood_form_buttons_background_color', array(
        'default'           => get_option('foreverwood_form_buttons_background_color', ''),
        'capability'        => 'edit_theme_options',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'foreverwood_form_buttons_background_color_control', array(
        'label'    => __('Buttons background color', 'foreverwood'),
        'section'  => 'foreverwood_html_color_settings',
        'settings' => 'foreverwood_form_buttons_background_color',
    )));
    
    //  ==========================================
    //  = Buttons hover color                    =
    //  ==========================================
    $wp_customize->add_setting('foreverwood_form_buttons_hover_color', array(
        'default'           => get_option('foreverwood_form_buttons_hover_color', ''),
        'capability'        => 'edit_theme_options',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'foreverwood_form_buttons_hover_color_control', array(
        'label'    => __('Buttons hover color', 'foreverwood'),
        'section'  => 'foreverwood_html_color_settings',
        'settings' => 'foreverwood_form_buttons_hover_color',
    )));
    
    //  ==========================================
    //  = Table Headers background color         =
    //  ==========================================
    $wp_customize->add_setting('foreverwood_table_headers_background_color', array(
        'default'           => get_option('foreverwood_table_headers_background_color', ''),
        'capability'        => 'edit_theme_options',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'foreverwood_sanitize_text',
    ));
 
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'foreverwood_table_headers_background_color_control', array(
        'label'    => __('Table Headers background color', 'foreverwood'),
        'section'  => 'foreverwood_html_color_settings',
        'settings' => 'foreverwood_table_headers_background_color',
    )));
}
 
add_action('customize_register', 'foreverwood_customize_register');

/**
 * Sanitize URIs
*/
function foreverwood_sanitize_uri($uri) {
	if('' === $uri){
		return '';
	}
	return esc_url_raw($uri);
}

/**
 * Sanitize Texts
*/
function foreverwood_sanitize_text($str) {
	if('' === $str){
		return '';
	}
	return sanitize_text_field($str);
} ?>