<?php
/**
 * Plugin Name: Resilient Philippines Site Builder
 * Description: Dashboard layout builder for pages, reusable site components, and optional custom header/footer controls.
 * Version: 0.2.4
 * Author: ACCORD
 * Text Domain: rp-site-builder
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'RPSB_VERSION', '0.2.4' );
define( 'RPSB_FILE', __FILE__ );
define( 'RPSB_PATH', plugin_dir_path( __FILE__ ) );
define( 'RPSB_URL', plugin_dir_url( __FILE__ ) );

function rpsb_register_component_type() {
	register_post_type(
		'rpsb_component',
		array(
			'labels'       => array(
				'name'          => __( 'Site Components', 'rp-site-builder' ),
				'singular_name' => __( 'Site Component', 'rp-site-builder' ),
				'add_new_item'  => __( 'Add Site Component', 'rp-site-builder' ),
				'edit_item'     => __( 'Edit Site Component', 'rp-site-builder' ),
				'menu_name'     => __( 'Components', 'rp-site-builder' ),
			),
			'public'       => false,
			'show_ui'      => true,
			'show_in_menu' => 'rpsb-site-builder',
			'supports'     => array( 'title', 'editor', 'revisions' ),
			'menu_icon'    => 'dashicons-layout',
			'capability_type' => 'page',
		)
	);
}
add_action( 'init', 'rpsb_register_component_type' );

function rpsb_default_options() {
	return array(
		'custom_header_enabled' => '0',
		'custom_footer_enabled' => '0',
		'brand_text'            => get_bloginfo( 'name' ),
		'logo_url'              => '',
		'cta_label'             => __( 'Donate', 'rp-site-builder' ),
		'cta_url'               => home_url( '/donate/' ),
		'footer_intro'          => __( 'A collaborative space for humanitarian learning, disaster risk reduction resources, and partner knowledge products across the Philippines.', 'rp-site-builder' ),
		'footer_links'          => "Resource Hub|" . home_url( '/resource-hub/' ) . "\nOpportunities|" . home_url( '/opportunities/' ) . "\nContact Us|" . home_url( '/contact-us/' ),
	);
}

function rpsb_get_options() {
	$options = get_option( 'rpsb_options', array() );
	return wp_parse_args( is_array( $options ) ? $options : array(), rpsb_default_options() );
}

function rpsb_register_settings() {
	register_setting( 'rpsb_options', 'rpsb_options', 'rpsb_sanitize_options' );
}
add_action( 'admin_init', 'rpsb_register_settings' );

function rpsb_sanitize_options( $input ) {
	$defaults = rpsb_default_options();
	$output   = array();

	$output['custom_header_enabled'] = ! empty( $input['custom_header_enabled'] ) ? '1' : '0';
	$output['custom_footer_enabled'] = ! empty( $input['custom_footer_enabled'] ) ? '1' : '0';
	$output['brand_text']            = isset( $input['brand_text'] ) ? sanitize_text_field( $input['brand_text'] ) : $defaults['brand_text'];
	$output['logo_url']              = isset( $input['logo_url'] ) ? esc_url_raw( $input['logo_url'] ) : '';
	$output['cta_label']             = isset( $input['cta_label'] ) ? sanitize_text_field( $input['cta_label'] ) : $defaults['cta_label'];
	$output['cta_url']               = isset( $input['cta_url'] ) ? esc_url_raw( $input['cta_url'] ) : $defaults['cta_url'];
	$output['footer_intro']          = isset( $input['footer_intro'] ) ? sanitize_textarea_field( $input['footer_intro'] ) : $defaults['footer_intro'];
	$output['footer_links']          = isset( $input['footer_links'] ) ? sanitize_textarea_field( $input['footer_links'] ) : $defaults['footer_links'];

	return $output;
}

function rpsb_admin_menu() {
	add_menu_page(
		__( 'Site Builder', 'rp-site-builder' ),
		__( 'Site Builder', 'rp-site-builder' ),
		'edit_pages',
		'rpsb-site-builder',
		'rpsb_render_dashboard',
		'dashicons-layout',
		26
	);

	add_submenu_page(
		'rpsb-site-builder',
		__( 'Visual Builder', 'rp-site-builder' ),
		__( 'Visual Builder', 'rp-site-builder' ),
		'edit_pages',
		'rpsb-visual-builder',
		'rpsb_render_visual_builder'
	);

	add_submenu_page(
		'rpsb-site-builder',
		__( 'Builder Settings', 'rp-site-builder' ),
		__( 'Header & Footer', 'rp-site-builder' ),
		'manage_options',
		'rpsb-settings',
		'rpsb_render_settings'
	);
}
add_action( 'admin_menu', 'rpsb_admin_menu' );

function rpsb_admin_assets( $hook ) {
	$is_builder_screen = in_array( $hook, array( 'post.php', 'post-new.php', 'toplevel_page_rpsb-site-builder', 'site-builder_page_rpsb-settings' ), true ) || false !== strpos( $hook, 'rpsb-visual-builder' );

	if ( ! $is_builder_screen ) {
		return;
	}

	wp_enqueue_style( 'rpsb-admin', RPSB_URL . 'assets/admin.css', array(), RPSB_VERSION );
	wp_enqueue_script( 'rpsb-admin', RPSB_URL . 'assets/admin.js', array(), RPSB_VERSION, true );
	wp_enqueue_media();

	if ( false !== strpos( $hook, 'rpsb-visual-builder' ) ) {
		wp_enqueue_style( 'rpsb-front', RPSB_URL . 'assets/frontend.css', array(), RPSB_VERSION );
		wp_enqueue_style( 'rpsb-visual-builder', RPSB_URL . 'assets/visual-builder.css', array( 'rpsb-admin', 'rpsb-front' ), RPSB_VERSION );
		wp_enqueue_script( 'rpsb-visual-builder', RPSB_URL . 'assets/visual-builder.js', array( 'rpsb-admin' ), RPSB_VERSION, true );
	}

	wp_localize_script(
		'rpsb-admin',
		'rpsbAdmin',
		array(
			'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
			'blocks'     => rpsb_block_templates(),
			'components' => rpsb_get_component_choices(),
			'nonce'      => wp_create_nonce( 'rpsb_visual_builder' ),
		)
	);
}
add_action( 'admin_enqueue_scripts', 'rpsb_admin_assets' );

function rpsb_front_assets() {
	wp_enqueue_style( 'rpsb-front', RPSB_URL . 'assets/frontend.css', array(), RPSB_VERSION );

	$options = rpsb_get_options();
	if ( '1' === $options['custom_header_enabled'] || '1' === $options['custom_footer_enabled'] ) {
		$css = '';
		if ( '1' === $options['custom_header_enabled'] ) {
			$css .= '.rp-site-header{display:none!important;}';
		}
		if ( '1' === $options['custom_footer_enabled'] ) {
			$css .= '.rp-site-footer{display:none!important;}';
		}
		wp_add_inline_style( 'rpsb-front', $css );
	}
}
add_action( 'wp_enqueue_scripts', 'rpsb_front_assets' );

function rpsb_body_classes( $classes ) {
	if ( is_singular( 'page' ) && '1' === get_post_meta( get_queried_object_id(), '_rpsb_enabled', true ) ) {
		$classes[] = 'rpsb-builder-page';
	}

	return $classes;
}
add_filter( 'body_class', 'rpsb_body_classes' );

function rpsb_add_page_metabox() {
	add_meta_box(
		'rpsb_page_builder',
		__( 'Resilient Site Builder', 'rp-site-builder' ),
		'rpsb_render_page_metabox',
		'page',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'rpsb_add_page_metabox' );

function rpsb_render_page_metabox( $post ) {
	wp_nonce_field( 'rpsb_save_page_builder', 'rpsb_page_builder_nonce' );
	$enabled = get_post_meta( $post->ID, '_rpsb_enabled', true );
	$layout  = get_post_meta( $post->ID, '_rpsb_layout', true );
	?>
	<div class="rpsb-builder" data-rpsb-builder>
		<label class="rpsb-toggle">
			<input type="checkbox" name="rpsb_enabled" value="1" <?php checked( $enabled, '1' ); ?>>
			<span><?php esc_html_e( 'Use builder layout for this page', 'rp-site-builder' ); ?></span>
		</label>
		<p class="description"><?php esc_html_e( 'When enabled, the sections below replace the normal page content on the front end.', 'rp-site-builder' ); ?></p>
		<input type="hidden" name="rpsb_layout" value="<?php echo esc_attr( $layout ); ?>" data-rpsb-layout>
		<div class="rpsb-toolbar">
			<button type="button" class="button" data-rpsb-add="hero"><?php esc_html_e( 'Add Hero', 'rp-site-builder' ); ?></button>
			<button type="button" class="button" data-rpsb-add="text"><?php esc_html_e( 'Add Text', 'rp-site-builder' ); ?></button>
			<button type="button" class="button" data-rpsb-add="image_text"><?php esc_html_e( 'Add Image + Text', 'rp-site-builder' ); ?></button>
			<button type="button" class="button" data-rpsb-add="image"><?php esc_html_e( 'Add Image', 'rp-site-builder' ); ?></button>
			<button type="button" class="button" data-rpsb-add="cards"><?php esc_html_e( 'Add Cards', 'rp-site-builder' ); ?></button>
			<button type="button" class="button" data-rpsb-add="cta"><?php esc_html_e( 'Add CTA', 'rp-site-builder' ); ?></button>
			<button type="button" class="button" data-rpsb-add="shortcode"><?php esc_html_e( 'Add Shortcode', 'rp-site-builder' ); ?></button>
			<button type="button" class="button" data-rpsb-add="component"><?php esc_html_e( 'Add Component', 'rp-site-builder' ); ?></button>
		</div>
		<div class="rpsb-sections" data-rpsb-sections></div>
	</div>
	<?php
}

function rpsb_save_page_builder( $post_id ) {
	if ( ! isset( $_POST['rpsb_page_builder_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['rpsb_page_builder_nonce'] ) ), 'rpsb_save_page_builder' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_page', $post_id ) ) {
		return;
	}

	update_post_meta( $post_id, '_rpsb_enabled', isset( $_POST['rpsb_enabled'] ) ? '1' : '0' );

	$layout = isset( $_POST['rpsb_layout'] ) ? wp_unslash( $_POST['rpsb_layout'] ) : '[]';
	$layout = rpsb_sanitize_layout_json( $layout );
	update_post_meta( $post_id, '_rpsb_layout', wp_json_encode( $layout ) );
}
add_action( 'save_post_page', 'rpsb_save_page_builder' );

function rpsb_sanitize_layout_json( $json ) {
	$decoded = json_decode( $json, true );
	if ( ! is_array( $decoded ) ) {
		return array();
	}

	$clean = array();
	foreach ( $decoded as $section ) {
		if ( empty( $section['type'] ) ) {
			continue;
		}

		$type = sanitize_key( $section['type'] );
		$item = array( 'type' => $type );
		foreach ( array( 'eyebrow', 'title', 'text', 'html', 'button_label', 'button_url', 'image_url', 'image_alt', 'caption', 'background', 'columns', 'shortcode', 'component_id', 'align', 'width', 'padding', 'theme', 'kicker', 'meta' ) as $key ) {
			if ( ! isset( $section[ $key ] ) ) {
				continue;
			}
			$value = $section[ $key ];
			if ( in_array( $key, array( 'button_url', 'image_url' ), true ) ) {
				$item[ $key ] = esc_url_raw( $value );
			} elseif ( 'component_id' === $key ) {
				$item[ $key ] = absint( $value );
			} elseif ( 'columns' === $key ) {
				$item[ $key ] = max( 1, min( 4, absint( $value ) ) );
			} elseif ( in_array( $key, array( 'text', 'html' ), true ) ) {
				$item[ $key ] = wp_kses_post( $value );
			} elseif ( in_array( $key, array( 'background', 'align', 'width', 'padding', 'theme' ), true ) ) {
				$item[ $key ] = sanitize_key( $value );
			} else {
				$item[ $key ] = sanitize_text_field( $value );
			}
		}
		$clean[] = $item;
	}

	return $clean;
}

function rpsb_block_templates() {
	return array(
		'hero'      => array(
			'type'         => 'hero',
			'eyebrow'      => __( 'Resilient Philippines', 'rp-site-builder' ),
			'title'        => __( 'Page headline', 'rp-site-builder' ),
			'text'         => __( 'Add a short introduction for this page.', 'rp-site-builder' ),
			'button_label' => '',
			'button_url'   => '',
			'image_url'    => '',
			'align'        => 'left',
			'padding'      => 'spacious',
			'theme'        => 'navy',
		),
		'text'      => array(
			'type'       => 'text',
			'title'      => __( 'Section heading', 'rp-site-builder' ),
			'text'       => __( 'Add section copy here.', 'rp-site-builder' ),
			'background' => 'white',
			'align'      => 'left',
			'width'      => 'contained',
			'padding'    => 'default',
		),
		'image_text' => array(
			'type'         => 'image_text',
			'kicker'       => __( 'Featured', 'rp-site-builder' ),
			'title'        => __( 'Image and text section', 'rp-site-builder' ),
			'text'         => __( 'Pair an image with focused copy and an optional button.', 'rp-site-builder' ),
			'button_label' => '',
			'button_url'   => '',
			'image_url'    => '',
			'image_alt'    => '',
			'background'   => 'white',
			'align'        => 'left',
			'padding'      => 'default',
		),
		'image'      => array(
			'type'       => 'image',
			'title'      => __( 'Image', 'rp-site-builder' ),
			'image_url'  => '',
			'image_alt'  => '',
			'caption'    => '',
			'background' => 'white',
			'align'      => 'center',
			'width'      => 'wide',
			'padding'    => 'compact',
		),
		'cards'     => array(
			'type'    => 'cards',
			'title'   => __( 'Cards section', 'rp-site-builder' ),
			'text'    => __( "First card title|Card description\nSecond card title|Card description\nThird card title|Card description", 'rp-site-builder' ),
			'columns' => 3,
			'background' => 'soft',
			'padding' => 'default',
		),
		'cta'       => array(
			'type'         => 'cta',
			'title'        => __( 'Call to action', 'rp-site-builder' ),
			'text'         => __( 'Prompt visitors to take the next step.', 'rp-site-builder' ),
			'button_label' => __( 'Learn more', 'rp-site-builder' ),
			'button_url'   => home_url( '/' ),
			'theme'        => 'navy',
			'padding'      => 'default',
		),
		'shortcode' => array(
			'type'      => 'shortcode',
			'title'     => __( 'Shortcode block', 'rp-site-builder' ),
			'shortcode' => '',
		),
		'html'      => array(
			'type'  => 'html',
			'title' => __( 'HTML block', 'rp-site-builder' ),
			'html'  => '',
		),
		'component' => array(
			'type'         => 'component',
			'title'        => __( 'Reusable component', 'rp-site-builder' ),
			'component_id' => 0,
		),
	);
}

function rpsb_filter_page_content( $content ) {
	if ( ! empty( $GLOBALS['rpsb_rendering_component'] ) ) {
		return $content;
	}

	if ( is_admin() || ! is_singular( 'page' ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}

	$post_id = get_the_ID();
	if ( '1' !== get_post_meta( $post_id, '_rpsb_enabled', true ) ) {
		return $content;
	}

	$layout = get_post_meta( $post_id, '_rpsb_layout', true );
	$html   = rpsb_render_layout( $layout );

	return $html ? $html : $content;
}
add_filter( 'the_content', 'rpsb_filter_page_content', 99 );

function rpsb_render_layout( $layout_json ) {
	$sections = json_decode( $layout_json, true );
	if ( ! is_array( $sections ) ) {
		return '';
	}

	ob_start();
	foreach ( $sections as $section ) {
		rpsb_render_section( $section );
	}
	return ob_get_clean();
}

function rpsb_render_section( $section ) {
	$type = isset( $section['type'] ) ? sanitize_key( $section['type'] ) : '';

	if ( 'hero' === $type ) {
		rpsb_render_hero_section( $section );
	} elseif ( 'text' === $type ) {
		rpsb_render_text_section( $section );
	} elseif ( 'image_text' === $type ) {
		rpsb_render_image_text_section( $section );
	} elseif ( 'image' === $type ) {
		rpsb_render_image_section( $section );
	} elseif ( 'cards' === $type ) {
		rpsb_render_cards_section( $section );
	} elseif ( 'cta' === $type ) {
		rpsb_render_cta_section( $section );
	} elseif ( 'shortcode' === $type ) {
		echo '<div class="rpsb-shortcode-block">' . do_shortcode( isset( $section['shortcode'] ) ? $section['shortcode'] : '' ) . '</div>';
	} elseif ( 'html' === $type ) {
		rpsb_render_html_section( $section );
	} elseif ( 'component' === $type ) {
		echo rpsb_render_component_shortcode( array( 'id' => isset( $section['component_id'] ) ? absint( $section['component_id'] ) : 0 ) );
	}
}

function rpsb_render_hero_section( $section ) {
	$image = ! empty( $section['image_url'] ) ? esc_url( $section['image_url'] ) : '';
	$classes = rpsb_section_classes( 'rpsb-hero', $section );
	?>
	<section class="<?php echo esc_attr( $classes ); ?>">
		<?php if ( $image ) : ?>
			<img src="<?php echo $image; ?>" alt="<?php echo esc_attr( isset( $section['image_alt'] ) ? $section['image_alt'] : '' ); ?>">
		<?php endif; ?>
		<div class="rp-section-inner rpsb-hero-content">
			<div class="rpsb-hero-copy">
				<?php if ( ! empty( $section['eyebrow'] ) ) : ?><p class="rp-eyebrow"><?php echo esc_html( $section['eyebrow'] ); ?></p><?php endif; ?>
				<?php if ( ! empty( $section['title'] ) ) : ?><h2><?php echo esc_html( $section['title'] ); ?></h2><?php endif; ?>
				<?php if ( ! empty( $section['text'] ) ) : ?><p><?php echo wp_kses_post( $section['text'] ); ?></p><?php endif; ?>
				<?php if ( ! empty( $section['button_label'] ) && ! empty( $section['button_url'] ) ) : ?>
					<a class="rp-button" href="<?php echo esc_url( $section['button_url'] ); ?>"><?php echo esc_html( $section['button_label'] ); ?></a>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
}

function rpsb_render_text_section( $section ) {
	$classes = rpsb_section_classes( 'rpsb-section rpsb-cards-section', $section );
	?>
	<section class="<?php echo esc_attr( $classes ); ?>">
		<div class="rp-section-inner">
			<?php if ( ! empty( $section['title'] ) ) : ?><h2><?php echo esc_html( $section['title'] ); ?></h2><?php endif; ?>
			<?php if ( ! empty( $section['text'] ) ) : ?><div class="rpsb-rich-text"><?php echo wpautop( wp_kses_post( $section['text'] ) ); ?></div><?php endif; ?>
		</div>
	</section>
	<?php
}

function rpsb_render_image_section( $section ) {
	$image = ! empty( $section['image_url'] ) ? esc_url( $section['image_url'] ) : '';
	if ( ! $image ) {
		return;
	}
	$classes = rpsb_section_classes( 'rpsb-section rpsb-image-section', $section );
	?>
	<section class="<?php echo esc_attr( $classes ); ?>">
		<div class="rp-section-inner">
			<figure class="rpsb-image-figure">
				<img src="<?php echo $image; ?>" alt="<?php echo esc_attr( isset( $section['image_alt'] ) ? $section['image_alt'] : '' ); ?>">
				<?php if ( ! empty( $section['caption'] ) ) : ?>
					<figcaption><?php echo esc_html( $section['caption'] ); ?></figcaption>
				<?php endif; ?>
			</figure>
		</div>
	</section>
	<?php
}

function rpsb_render_image_text_section( $section ) {
	$classes = rpsb_section_classes( 'rpsb-section rpsb-image-text-section', $section );
	$image   = ! empty( $section['image_url'] ) ? esc_url( $section['image_url'] ) : '';
	?>
	<section class="<?php echo esc_attr( $classes ); ?>">
		<div class="rp-section-inner rpsb-image-text">
			<div class="rpsb-image-text-media">
				<?php if ( $image ) : ?>
					<img src="<?php echo $image; ?>" alt="<?php echo esc_attr( isset( $section['image_alt'] ) ? $section['image_alt'] : '' ); ?>">
				<?php endif; ?>
			</div>
			<div class="rpsb-image-text-copy">
				<?php if ( ! empty( $section['kicker'] ) ) : ?><p class="rp-eyebrow"><?php echo esc_html( $section['kicker'] ); ?></p><?php endif; ?>
				<?php if ( ! empty( $section['title'] ) ) : ?><h2><?php echo esc_html( $section['title'] ); ?></h2><?php endif; ?>
				<?php if ( ! empty( $section['text'] ) ) : ?><div class="rpsb-rich-text"><?php echo wpautop( wp_kses_post( $section['text'] ) ); ?></div><?php endif; ?>
				<?php if ( ! empty( $section['button_label'] ) && ! empty( $section['button_url'] ) ) : ?>
					<a class="rp-button" href="<?php echo esc_url( $section['button_url'] ); ?>"><?php echo esc_html( $section['button_label'] ); ?></a>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
}

function rpsb_render_cards_section( $section ) {
	$columns = isset( $section['columns'] ) ? max( 1, min( 4, absint( $section['columns'] ) ) ) : 3;
	$rows    = ! empty( $section['text'] ) ? preg_split( '/\r\n|\r|\n/', $section['text'] ) : array();
	$classes = rpsb_section_classes( 'rpsb-section', $section );
	?>
	<section class="<?php echo esc_attr( $classes ); ?>">
		<div class="rp-section-inner">
			<?php if ( ! empty( $section['title'] ) ) : ?><h2><?php echo esc_html( $section['title'] ); ?></h2><?php endif; ?>
			<div class="rpsb-card-grid" style="--rpsb-columns: <?php echo absint( $columns ); ?>;">
				<?php foreach ( $rows as $row ) : ?>
					<?php
					$parts = array_map( 'trim', explode( '|', $row, 2 ) );
					if ( empty( $parts[0] ) ) {
						continue;
					}
					?>
					<article class="rp-card rpsb-card">
						<h3><?php echo esc_html( $parts[0] ); ?></h3>
						<?php if ( ! empty( $parts[1] ) ) : ?><p><?php echo esc_html( $parts[1] ); ?></p><?php endif; ?>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
}

function rpsb_render_cta_section( $section ) {
	$classes = rpsb_section_classes( 'rpsb-cta', $section );
	?>
	<section class="<?php echo esc_attr( $classes ); ?>">
		<div class="rp-section-inner">
			<?php if ( ! empty( $section['title'] ) ) : ?><h2><?php echo esc_html( $section['title'] ); ?></h2><?php endif; ?>
			<?php if ( ! empty( $section['text'] ) ) : ?><p><?php echo esc_html( $section['text'] ); ?></p><?php endif; ?>
			<?php if ( ! empty( $section['button_label'] ) && ! empty( $section['button_url'] ) ) : ?>
				<a class="rp-button" href="<?php echo esc_url( $section['button_url'] ); ?>"><?php echo esc_html( $section['button_label'] ); ?></a>
			<?php endif; ?>
		</div>
	</section>
	<?php
}

function rpsb_section_classes( $base, $section ) {
	$background = isset( $section['background'] ) ? sanitize_key( $section['background'] ) : '';
	$theme      = isset( $section['theme'] ) ? sanitize_key( $section['theme'] ) : '';
	$align      = isset( $section['align'] ) ? sanitize_key( $section['align'] ) : '';
	$width      = isset( $section['width'] ) ? sanitize_key( $section['width'] ) : '';
	$padding    = isset( $section['padding'] ) ? sanitize_key( $section['padding'] ) : '';
	$classes    = array_filter(
		array(
			$base,
			$background ? 'rpsb-section-' . $background : '',
			$theme ? 'rpsb-theme-' . $theme : '',
			$align ? 'rpsb-align-' . $align : '',
			$width ? 'rpsb-width-' . $width : '',
			$padding ? 'rpsb-padding-' . $padding : '',
		)
	);

	return implode( ' ', $classes );
}

function rpsb_render_html_section( $section ) {
	if ( empty( $section['html'] ) ) {
		return;
	}
	?>
	<section class="rpsb-section rpsb-html-section">
		<div class="rp-section-inner">
			<?php echo wp_kses_post( $section['html'] ); ?>
		</div>
	</section>
	<?php
}

function rpsb_render_component_shortcode( $atts ) {
	$atts = shortcode_atts( array( 'id' => 0 ), $atts, 'rpsb_component' );
	$id   = absint( $atts['id'] );
	if ( ! $id || 'rpsb_component' !== get_post_type( $id ) || 'publish' !== get_post_status( $id ) ) {
		return '';
	}

	static $rendering_components = array();
	if ( in_array( $id, $rendering_components, true ) ) {
		return '<!-- Site Builder Component Recursion Blocked -->';
	}
	$rendering_components[] = $id;

	$old_rendering_component = ! empty( $GLOBALS['rpsb_rendering_component'] );
	$GLOBALS['rpsb_rendering_component'] = true;
	$content = apply_filters( 'the_content', get_post_field( 'post_content', $id ) );
	$GLOBALS['rpsb_rendering_component'] = $old_rendering_component;

	array_pop( $rendering_components );

	return '<section class="rpsb-section rpsb-component"><div class="rp-section-inner">' . $content . '</div></section>';
}
add_shortcode( 'rpsb_component', 'rpsb_render_component_shortcode' );

function rpsb_render_dashboard() {
	if ( isset( $_POST['rpsb_create_page_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['rpsb_create_page_nonce'] ) ), 'rpsb_create_page' ) && current_user_can( 'edit_pages' ) ) {
		$title = isset( $_POST['rpsb_page_title'] ) ? sanitize_text_field( wp_unslash( $_POST['rpsb_page_title'] ) ) : '';
		if ( $title ) {
			$page_id = wp_insert_post(
				array(
					'post_type'    => 'page',
					'post_title'   => $title,
					'post_status'  => 'draft',
					'post_content' => '',
				)
			);
			if ( $page_id && ! is_wp_error( $page_id ) ) {
				update_post_meta( $page_id, '_rpsb_enabled', '1' );
				update_post_meta( $page_id, '_rpsb_layout', wp_json_encode( array( rpsb_block_templates()['hero'], rpsb_block_templates()['text'] ) ) );
				wp_safe_redirect( get_edit_post_link( $page_id, 'raw' ) );
				exit;
			}
		}
	}

	$pages = get_pages( array( 'sort_column' => 'post_modified', 'sort_order' => 'DESC', 'number' => 25 ) );
	?>
	<div class="wrap rpsb-dashboard">
		<h1><?php esc_html_e( 'Site Builder', 'rp-site-builder' ); ?></h1>
		<div class="rpsb-admin-grid">
			<div class="rpsb-panel">
				<h2><?php esc_html_e( 'Create a Builder Page', 'rp-site-builder' ); ?></h2>
				<form method="post">
					<?php wp_nonce_field( 'rpsb_create_page', 'rpsb_create_page_nonce' ); ?>
					<label for="rpsb_page_title"><?php esc_html_e( 'Page title', 'rp-site-builder' ); ?></label>
					<input id="rpsb_page_title" class="regular-text" type="text" name="rpsb_page_title" required>
					<button class="button button-primary" type="submit"><?php esc_html_e( 'Create Draft Page', 'rp-site-builder' ); ?></button>
				</form>
			</div>
			<div class="rpsb-panel">
				<h2><?php esc_html_e( 'Editable Pages', 'rp-site-builder' ); ?></h2>
				<table class="widefat striped">
					<thead><tr><th><?php esc_html_e( 'Page', 'rp-site-builder' ); ?></th><th><?php esc_html_e( 'Builder', 'rp-site-builder' ); ?></th><th><?php esc_html_e( 'Action', 'rp-site-builder' ); ?></th></tr></thead>
					<tbody>
						<?php foreach ( $pages as $page ) : ?>
							<tr>
								<td><?php echo esc_html( $page->post_title ); ?></td>
								<td><?php echo '1' === get_post_meta( $page->ID, '_rpsb_enabled', true ) ? esc_html__( 'Enabled', 'rp-site-builder' ) : esc_html__( 'Off', 'rp-site-builder' ); ?></td>
								<td>
									<a class="button button-small" href="<?php echo esc_url( add_query_arg( array( 'page' => 'rpsb-visual-builder', 'page_id' => $page->ID ), admin_url( 'admin.php' ) ) ); ?>"><?php esc_html_e( 'Visual Builder', 'rp-site-builder' ); ?></a>
									<a href="<?php echo esc_url( get_edit_post_link( $page->ID ) ); ?>"><?php esc_html_e( 'Classic edit', 'rp-site-builder' ); ?></a>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<?php
}

function rpsb_render_visual_builder() {
	if ( ! current_user_can( 'edit_pages' ) ) {
		wp_die( esc_html__( 'You do not have permission to edit pages.', 'rp-site-builder' ) );
	}

	$page_id = isset( $_GET['page_id'] ) ? absint( $_GET['page_id'] ) : 0;
	$pages   = get_pages( array( 'sort_column' => 'post_title', 'sort_order' => 'ASC' ) );
	$page    = $page_id ? get_post( $page_id ) : null;

	if ( $page && ! current_user_can( 'edit_page', $page->ID ) ) {
		$page = null;
	}

	$layout = $page ? get_post_meta( $page->ID, '_rpsb_layout', true ) : '[]';
	if ( ! $layout ) {
		$layout = '[]';
	}
	$page_source = $page ? rpsb_get_page_source_data( $page ) : array();
	?>
	<div class="wrap rpsb-visual-wrap">
		<div class="rpsb-visual-topbar">
			<div>
				<h1><?php esc_html_e( 'Visual Builder', 'rp-site-builder' ); ?></h1>
				<p><?php esc_html_e( 'Design pages visually, then save the layout back to WordPress.', 'rp-site-builder' ); ?></p>
			</div>
			<form method="get" class="rpsb-page-picker">
				<input type="hidden" name="page" value="rpsb-visual-builder">
				<select name="page_id">
					<option value="0"><?php esc_html_e( 'Choose a page', 'rp-site-builder' ); ?></option>
					<?php foreach ( $pages as $item ) : ?>
						<option value="<?php echo absint( $item->ID ); ?>" <?php selected( $page_id, $item->ID ); ?>><?php echo esc_html( $item->post_title ); ?></option>
					<?php endforeach; ?>
				</select>
				<button class="button" type="submit"><?php esc_html_e( 'Open', 'rp-site-builder' ); ?></button>
			</form>
		</div>

		<?php if ( ! $page ) : ?>
			<div class="rpsb-panel">
				<h2><?php esc_html_e( 'Select a page to start designing.', 'rp-site-builder' ); ?></h2>
				<p><?php esc_html_e( 'You can create new draft pages from the main Site Builder screen.', 'rp-site-builder' ); ?></p>
			</div>
		<?php else : ?>
			<div
				class="rpsb-visual-builder"
				data-rpsb-visual-builder
				data-page-id="<?php echo absint( $page->ID ); ?>"
				data-edit-url="<?php echo esc_url( get_edit_post_link( $page->ID, 'raw' ) ); ?>"
				data-view-url="<?php echo esc_url( get_permalink( $page->ID ) ); ?>"
				data-layout="<?php echo esc_attr( $layout ); ?>"
				data-rpsb-enabled="<?php echo esc_attr( get_post_meta( $page->ID, '_rpsb_enabled', true ) ); ?>"
			>
				<aside class="rpsb-vb-sidebar">
					<div class="rpsb-vb-sidebar-head">
						<strong><?php echo esc_html( get_the_title( $page ) ); ?></strong>
						<span data-rpsb-save-status><?php esc_html_e( 'Not saved', 'rp-site-builder' ); ?></span>
					</div>
					<div class="rpsb-vb-actions">
						<button type="button" class="button button-primary" data-rpsb-save><?php esc_html_e( 'Save Layout', 'rp-site-builder' ); ?></button>
						<button type="button" class="button" data-rpsb-import-current><?php esc_html_e( 'Import Current Content', 'rp-site-builder' ); ?></button>
						<a class="button" href="<?php echo esc_url( get_permalink( $page->ID ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'View Page', 'rp-site-builder' ); ?></a>
					</div>
					<div class="rpsb-vb-tabs">
						<button type="button" class="is-active" data-rpsb-tab="add"><?php esc_html_e( 'Add', 'rp-site-builder' ); ?></button>
						<button type="button" data-rpsb-tab="edit"><?php esc_html_e( 'Edit', 'rp-site-builder' ); ?></button>
						<button type="button" data-rpsb-tab="layout"><?php esc_html_e( 'Layout', 'rp-site-builder' ); ?></button>
					</div>
					<div class="rpsb-vb-panel is-active" data-rpsb-panel="add">
						<button type="button" class="rpsb-add-block" data-rpsb-add="hero">Hero</button>
						<button type="button" class="rpsb-add-block" data-rpsb-add="text">Text</button>
						<button type="button" class="rpsb-add-block" data-rpsb-add="image_text">Image + Text</button>
						<button type="button" class="rpsb-add-block" data-rpsb-add="image">Image</button>
						<button type="button" class="rpsb-add-block" data-rpsb-add="cards">Cards</button>
						<button type="button" class="rpsb-add-block" data-rpsb-add="cta">CTA</button>
		<button type="button" class="rpsb-add-block" data-rpsb-add="shortcode">Shortcode</button>
						<button type="button" class="rpsb-add-block" data-rpsb-add="html">HTML</button>
						<button type="button" class="rpsb-add-block" data-rpsb-add="component">Component</button>
						<hr>
						<button type="button" class="button" data-rpsb-template="landing"><?php esc_html_e( 'Insert Landing Template', 'rp-site-builder' ); ?></button>
						<button type="button" class="button" data-rpsb-template="content"><?php esc_html_e( 'Insert Content Template', 'rp-site-builder' ); ?></button>
					</div>
					<div class="rpsb-vb-panel" data-rpsb-panel="edit">
						<div data-rpsb-inspector><?php esc_html_e( 'Select a section on the canvas.', 'rp-site-builder' ); ?></div>
					</div>
					<div class="rpsb-vb-panel" data-rpsb-panel="layout">
						<div data-rpsb-structure></div>
					</div>
				</aside>
				<main class="rpsb-vb-stage">
					<div class="rpsb-vb-device">
						<div class="rpsb-segment" aria-label="<?php esc_attr_e( 'Preview mode', 'rp-site-builder' ); ?>">
							<button type="button" class="is-active" data-rpsb-stage-mode="builder"><?php esc_html_e( 'Builder', 'rp-site-builder' ); ?></button>
							<button type="button" data-rpsb-stage-mode="live"><?php esc_html_e( 'Live Page', 'rp-site-builder' ); ?></button>
						</div>
						<div class="rpsb-segment" aria-label="<?php esc_attr_e( 'Device width', 'rp-site-builder' ); ?>">
							<button type="button" class="is-active" data-rpsb-device="desktop"><?php esc_html_e( 'Desktop', 'rp-site-builder' ); ?></button>
							<button type="button" data-rpsb-device="tablet"><?php esc_html_e( 'Tablet', 'rp-site-builder' ); ?></button>
							<button type="button" data-rpsb-device="mobile"><?php esc_html_e( 'Mobile', 'rp-site-builder' ); ?></button>
						</div>
					</div>
					<div class="rpsb-vb-canvas-wrap" data-rpsb-device-wrap>
						<div class="rpsb-vb-canvas" data-rpsb-canvas></div>
						<iframe class="rpsb-vb-live-frame" data-rpsb-live-frame src="<?php echo esc_url( get_permalink( $page->ID ) ); ?>" title="<?php esc_attr_e( 'Live page preview', 'rp-site-builder' ); ?>"></iframe>
					</div>
				</main>
				<script type="application/json" data-rpsb-page-source><?php echo wp_json_encode( $page_source ); ?></script>
			</div>
		<?php endif; ?>
	</div>
	<?php
}

function rpsb_ajax_save_layout() {
	check_ajax_referer( 'rpsb_visual_builder', 'nonce' );

	$page_id = isset( $_POST['page_id'] ) ? absint( $_POST['page_id'] ) : 0;
	if ( ! $page_id || ! current_user_can( 'edit_page', $page_id ) ) {
		wp_send_json_error( array( 'message' => __( 'You cannot edit this page.', 'rp-site-builder' ) ), 403 );
	}

	$layout = isset( $_POST['layout'] ) ? wp_unslash( $_POST['layout'] ) : '[]';
	$clean  = rpsb_sanitize_layout_json( $layout );
	update_post_meta( $page_id, '_rpsb_enabled', '1' );
	update_post_meta( $page_id, '_rpsb_layout', wp_json_encode( $clean ) );
	wp_update_post(
		array(
			'ID'           => $page_id,
			'post_content' => '<!-- Built with Resilient Philippines Site Builder -->',
		)
	);

	wp_send_json_success(
		array(
			'message' => __( 'Layout saved.', 'rp-site-builder' ),
			'layout'  => $clean,
		)
	);
}
add_action( 'wp_ajax_rpsb_save_layout', 'rpsb_ajax_save_layout' );

function rpsb_get_page_source_data( $page ) {
	$content = trim( wp_strip_all_tags( strip_shortcodes( $page->post_content ), true ) );
	if ( ! $content ) {
		$content = trim( wp_strip_all_tags( get_the_excerpt( $page ), true ) );
	}

	return array(
		'title'       => get_the_title( $page ),
		'content'     => $content,
		'raw_content' => $page->post_content,
		'permalink'   => get_permalink( $page ),
	);
}

function rpsb_get_component_choices() {
	$components = get_posts(
		array(
			'post_type'      => 'rpsb_component',
			'post_status'    => 'publish',
			'posts_per_page' => 100,
			'orderby'        => 'title',
			'order'          => 'ASC',
		)
	);
	$choices = array();
	foreach ( $components as $component ) {
		$choices[] = array(
			'id'    => $component->ID,
			'title' => get_the_title( $component ),
		);
	}

	return $choices;
}

function rpsb_render_settings() {
	$options = rpsb_get_options();
	?>
	<div class="wrap rpsb-settings">
		<h1><?php esc_html_e( 'Header & Footer Builder', 'rp-site-builder' ); ?></h1>
		<form method="post" action="options.php">
			<?php settings_fields( 'rpsb_options' ); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><?php esc_html_e( 'Custom header', 'rp-site-builder' ); ?></th>
					<td><label><input type="checkbox" name="rpsb_options[custom_header_enabled]" value="1" <?php checked( $options['custom_header_enabled'], '1' ); ?>> <?php esc_html_e( 'Replace the theme header with builder header', 'rp-site-builder' ); ?></label></td>
				</tr>
				<tr>
					<th scope="row"><label for="rpsb_brand_text"><?php esc_html_e( 'Brand text', 'rp-site-builder' ); ?></label></th>
					<td><input id="rpsb_brand_text" class="regular-text" type="text" name="rpsb_options[brand_text]" value="<?php echo esc_attr( $options['brand_text'] ); ?>"></td>
				</tr>
				<tr>
					<th scope="row"><label for="rpsb_logo_url"><?php esc_html_e( 'Logo URL', 'rp-site-builder' ); ?></label></th>
					<td><input id="rpsb_logo_url" class="regular-text" type="url" name="rpsb_options[logo_url]" value="<?php echo esc_attr( $options['logo_url'] ); ?>"></td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Header CTA', 'rp-site-builder' ); ?></th>
					<td>
						<input class="regular-text" type="text" name="rpsb_options[cta_label]" value="<?php echo esc_attr( $options['cta_label'] ); ?>" placeholder="<?php esc_attr_e( 'Button label', 'rp-site-builder' ); ?>">
						<input class="regular-text" type="url" name="rpsb_options[cta_url]" value="<?php echo esc_attr( $options['cta_url'] ); ?>" placeholder="<?php esc_attr_e( 'Button URL', 'rp-site-builder' ); ?>">
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Custom footer', 'rp-site-builder' ); ?></th>
					<td><label><input type="checkbox" name="rpsb_options[custom_footer_enabled]" value="1" <?php checked( $options['custom_footer_enabled'], '1' ); ?>> <?php esc_html_e( 'Replace the theme footer with builder footer', 'rp-site-builder' ); ?></label></td>
				</tr>
				<tr>
					<th scope="row"><label for="rpsb_footer_intro"><?php esc_html_e( 'Footer intro', 'rp-site-builder' ); ?></label></th>
					<td><textarea id="rpsb_footer_intro" class="large-text" rows="3" name="rpsb_options[footer_intro]"><?php echo esc_textarea( $options['footer_intro'] ); ?></textarea></td>
				</tr>
				<tr>
					<th scope="row"><label for="rpsb_footer_links"><?php esc_html_e( 'Footer links', 'rp-site-builder' ); ?></label></th>
					<td>
						<textarea id="rpsb_footer_links" class="large-text code" rows="6" name="rpsb_options[footer_links]"><?php echo esc_textarea( $options['footer_links'] ); ?></textarea>
						<p class="description"><?php esc_html_e( 'One link per line: Label|https://example.com/page', 'rp-site-builder' ); ?></p>
					</td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

function rpsb_render_custom_header() {
	$options = rpsb_get_options();
	if ( '1' !== $options['custom_header_enabled'] ) {
		return;
	}
	?>
	<header class="rpsb-site-header" role="banner">
		<div class="rp-header-inner">
			<a class="rp-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php if ( ! empty( $options['logo_url'] ) ) : ?>
					<img src="<?php echo esc_url( $options['logo_url'] ); ?>" alt="<?php echo esc_attr( $options['brand_text'] ); ?>" class="rp-brand-logo">
				<?php else : ?>
					<span class="rp-brand-text"><?php echo esc_html( $options['brand_text'] ); ?></span>
				<?php endif; ?>
			</a>
			<nav class="rp-nav" aria-label="<?php esc_attr_e( 'Primary navigation', 'rp-site-builder' ); ?>">
				<?php
				if ( has_nav_menu( 'main-navigation' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'main-navigation',
							'container'      => false,
							'fallback_cb'    => false,
						)
					);
				}
				?>
				<?php if ( ! empty( $options['cta_label'] ) && ! empty( $options['cta_url'] ) ) : ?>
					<a class="rp-button rp-button-donate" href="<?php echo esc_url( $options['cta_url'] ); ?>"><?php echo esc_html( $options['cta_label'] ); ?></a>
				<?php endif; ?>
			</nav>
		</div>
	</header>
	<?php
}
add_action( 'wp_body_open', 'rpsb_render_custom_header', 1 );

function rpsb_render_custom_footer() {
	$options = rpsb_get_options();
	if ( '1' !== $options['custom_footer_enabled'] ) {
		return;
	}
	?>
	<footer class="rpsb-site-footer" role="contentinfo">
		<div class="rp-footer-inner">
			<div>
				<h2><?php echo esc_html( $options['brand_text'] ); ?></h2>
				<p><?php echo esc_html( $options['footer_intro'] ); ?></p>
			</div>
			<div>
				<h3><?php esc_html_e( 'Links', 'rp-site-builder' ); ?></h3>
				<ul class="rp-footer-list">
					<?php foreach ( rpsb_parse_footer_links( $options['footer_links'] ) as $link ) : ?>
						<li><a href="<?php echo esc_url( $link['url'] ); ?>"><?php echo esc_html( $link['label'] ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</footer>
	<?php
}
add_action( 'wp_footer', 'rpsb_render_custom_footer', 5 );

function rpsb_parse_footer_links( $text ) {
	$links = array();
	foreach ( preg_split( '/\r\n|\r|\n/', $text ) as $line ) {
		$parts = array_map( 'trim', explode( '|', $line, 2 ) );
		if ( 2 !== count( $parts ) || empty( $parts[0] ) || empty( $parts[1] ) ) {
			continue;
		}
		$links[] = array(
			'label' => $parts[0],
			'url'   => $parts[1],
		);
	}
	return $links;
}
