<?php
/**
 * Prespa Travel functions and definitions
 *
 * @since 1.0.0
 */

/**
 * Register child theme's styles
 */
function prespa_travel_enqueue_theme_styles() {
	wp_enqueue_style( 'prespa-travel-styles', get_stylesheet_directory_uri() . '/style.css', array(), wp_get_theme()->get( 'Version' ) );
}

add_action( 'wp_enqueue_scripts', 'prespa_travel_enqueue_theme_styles' );

/**
 * Registers block patterns categories, and type.
 */
function prespa_travel_register_block_patterns() {
	$block_pattern_categories = array(
		'prespa-travel' => array( 'label' => esc_html__( 'Prespa Travel', 'prespa-travel' ) ),
	);

	$block_pattern_categories = apply_filters( 'prespa_travel_block_pattern_categories', $block_pattern_categories );

	foreach ( $block_pattern_categories as $name => $properties ) {
		if ( ! WP_Block_Pattern_Categories_Registry::get_instance()->is_registered( $name ) ) {
			register_block_pattern_category( $name, $properties );
		}
	}
}

add_action( 'init', 'prespa_travel_register_block_patterns', 9 );

// Change theme defaults in the customizer
function prespa_travel_customize_register( $wp_customize ) {
	$primary_accent_color_setting = $wp_customize->get_setting( 'primary_accent_color' );
	$secondary_accent_color_setting = $wp_customize->get_setting( 'secondary_accent_color' );
	$content_layout_setting = $wp_customize->get_setting( 'content_layout' );
	$header_button_text = $wp_customize->get_setting( 'header_button_text' );
	$has_secondary_menu = $wp_customize->get_setting( 'has_secondary_menu' );
	$header_menu_position = $wp_customize->get_setting( 'header-menu-position' );

	if ( $primary_accent_color_setting ) {
		$primary_accent_color_setting->default = '#58643c';
	}

	if ( $secondary_accent_color_setting ) {
		$secondary_accent_color_setting->default = '#f2e2d4';
	}

	if ( $content_layout_setting ) {
		$content_layout_setting->default = 'one_container';
	}

	if ( $header_button_text ) {
		$header_button_text->default = '';
	}

	if ( $has_secondary_menu ) {
		$has_secondary_menu->default = false;
	}
	
	if ( $header_menu_position ) {
		$header_menu_position->default = 'sticky';
	}
}

add_action( 'customize_register', 'prespa_travel_customize_register', 999, 1 );

// Overwrite parent theme customizer defaults
function prespa_customizer_values( $value ) {
	$defaults = array(
		'content_layout'     => 'one_container',
		'header_button_text' => '',
		'has_secondary_menu' => false,
		'header-menu-position' => 'sticky'
	);
	// Return the value from the theme mod, or fallback to the default
	return get_theme_mod( $value, $defaults[$value]);
}

// Overwrite parent theme color scheme
function prespa_customize_colors_css() {

	$body_text_color          = get_theme_mod( 'body_text_color' );
	$body_bgr_color           = get_theme_mod( 'body_bgr_color' );
	$headings_text_color      = get_theme_mod( 'headings_text_color', '#101011' );
	$link_headings_text_color = get_theme_mod( 'link_headings_text_color', '#101011' );
	$links_text_color         = get_theme_mod( 'links_text_color' );
	$buttons_bgr_color        = get_theme_mod( 'buttons_bgr_color' );
	$primary_accent_color     = get_theme_mod( 'primary_accent_color', '#58643c' );
	$secondary_accent_color   = get_theme_mod( 'secondary_accent_color', '#f2e2d4' );

	?>
	
	<style>
	body:not(.dark-mode) {
	<?php if ( $links_text_color ) : ?>
		--wp--preset--color--links: <?php echo esc_attr( $links_text_color ); ?>;
	<?php endif; ?>
	<?php if ( $link_headings_text_color ) : ?>
		--wp--preset--color--link-headings: <?php echo esc_attr( $link_headings_text_color ); ?>;
	<?php endif; ?>
	<?php if ( $body_bgr_color ) : ?>
		--wp--preset--color--bgr: <?php echo esc_attr( $body_bgr_color ); ?>;
	<?php else : ?>
		--wp--preset--color--bgr: var(--wp--preset--color--white);
	<?php endif; ?>
	}

	<?php if ( $body_text_color ) : ?> 
	body {
		color: <?php echo esc_attr( $body_text_color ); ?>;
	}
	<?php endif; ?>
	<?php if ( $body_bgr_color ) : ?> 
	body {
		background-color: var(--wp--preset--color--bgr);
	}
	<?php endif; ?>
	h1, h2, h3, h4, h5, h6 {
		color: <?php echo esc_attr( $headings_text_color ); ?>;
	}

	body:not(.dark-mode) input[type="button"], 
	body:not(.dark-mode) input[type="reset"], 
	body:not(.dark-mode) [type="submit"]:not(.header-search-form button),
	.wp-block-button > .slider-button,
	.wp-block-button .wp-block-button__link,
	.prespa-featured-products-wrapper .button {
		background-color: <?php echo $buttons_bgr_color ? esc_attr( $buttons_bgr_color ) : esc_attr( $primary_accent_color ); ?>;
	}

	<?php if ( $buttons_bgr_color ) : ?>
	.wp-element-button, .wp-block-button__link {
		background-color: <?php echo esc_attr( $buttons_bgr_color ); ?>;
	}
	<?php endif; ?>
	.back-to-top,
	.dark-mode .back-to-top,
	.navigation .page-numbers:hover,
	.navigation .page-numbers.current  {
		background-color: <?php echo $buttons_bgr_color ? esc_attr( prespa_brightness( $buttons_bgr_color, -50 ) ) : esc_attr( $primary_accent_color ); ?>
	}
	.fallback-svg {
		background: <?php echo esc_attr( prespa_hex_to_rgba( $primary_accent_color, .1 ) ); ?>;
	}
	.preloader .bounce1, .preloader .bounce2, .preloader .bounce3 {
		background-color: <?php echo esc_attr( prespa_brightness( $primary_accent_color, -25 ) ); // WPCS: XSS ok. ?>;
	}

	.top-meta a:nth-of-type(3n+1),
	.recent-posts-pattern .taxonomy-category a:nth-of-type(3n+1) {
		background-color:  <?php echo esc_attr( $primary_accent_color ); ?>;
		z-index: 1;
	}

	.top-meta a:nth-of-type(3n+1):hover,
	.recent-posts-pattern .taxonomy-category a:nth-of-type(3n+1):hover {
		background-color: <?php echo esc_attr( prespa_brightness( $primary_accent_color, -25 ) ); // WPCS: XSS ok. ?>;
	}

	.top-meta a:nth-of-type(3n+2) {
		background-color: <?php echo esc_attr( $secondary_accent_color ); ?>;
	}

	.top-meta a:nth-of-type(3n+2):hover {
		background-color: <?php echo esc_attr( prespa_brightness( $secondary_accent_color, -25 ) ); // WPCS: XSS ok. ?>;
	}

	.call-to-action.wp-block-button .wp-block-button__link {
		background-color: transparent;
		border: 1px solid #fff;
	}

	@media(min-width:54rem){
		body:not(.dark-mode):not(.has-transparent-header) .call-to-action.wp-block-button .wp-block-button__link {
			background-color:  <?php echo esc_attr( $secondary_accent_color ); ?>;
			color: var(--wp--preset--color--links);
			font-weight: bold;
		}
	}

	body:not(.dark-mode) .call-to-action.wp-block-button .wp-block-button__link:hover {
		background-color: <?php echo esc_attr( $primary_accent_color ); ?>;
		color: var(--wp--preset--color--white);
	}

	.categories-section .category-meta {
		background-color: <?php echo esc_attr( prespa_hex_to_rgba( $primary_accent_color, .6 ) ); ?>;
		z-index: 1;
	}

	.categories-section .category-meta:hover {
		background-color: <?php echo esc_attr( prespa_hex_to_rgba( $primary_accent_color, .75 ) ); ?>;
		z-index: 1;
	}

	.section-features figure::before {
		background: <?php echo esc_attr( $primary_accent_color ); ?>;
		opacity: .85;
	}

	@media (max-width: 54em) {
		.slide-menu, .site-menu.toggled > .menu-toggle {
			background-color:  <?php echo esc_attr( $primary_accent_color ); ?>;
		}
	}

	@media (min-width:54em){
		#secondary .tagcloud a:hover {
			background-color: <?php echo esc_attr( $secondary_accent_color ); ?>;
		}
	}
	</style>
	
	<?php
}

function prespa_starter_content_setup() {
	$default_page_content = '
	<!-- wp:pattern {"slug":"prespa-travel/hero-image"} /-->
	<!-- wp:pattern {"slug":"prespa-travel/partners"} /-->
	<!-- wp:pattern {"slug":"prespa-travel/banner"} /-->
	';

	add_theme_support(
		'starter-content',
		array(
			'posts'     => array(
				'home'  => array(
					'post_type'    => 'page',
					'post_title'   => _x( 'Home', 'Theme starter content', 'prespa-travel' ),
					'post_content' => $default_page_content,
				),
				'blog'
			),
			'options'   => array(
                'show_on_front'  => 'page',
                'page_on_front'  => '{{home}}',
                'page_for_posts' => '{{blog}}'
            ),
			'nav_menus' => array(
                'menu-1' => array(
                    'name'  => __( 'Primary', 'prespa-travel' ),
                    'items' => array(
                        'page_home',
                        'page_blog',
                    ),
                ),
            )
		)
	);
}
