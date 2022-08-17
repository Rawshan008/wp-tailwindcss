<?php
/**
 * wp-tailwindcss functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package wp-tailwindcss
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

define('VITE_SERVER', 'http://localhost:3000');
define('VITE_ENTRY_POINT', '/main.js');

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function wp_tailwindcss_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on wp-tailwindcss, use a find and replace
		* to change 'wp-tailwindcss' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'wp-tailwindcss', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'wp-tailwindcss' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'wp_tailwindcss_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'wp_tailwindcss_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function wp_tailwindcss_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'wp_tailwindcss_content_width', 640 );
}
add_action( 'after_setup_theme', 'wp_tailwindcss_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function wp_tailwindcss_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'wp-tailwindcss' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'wp-tailwindcss' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'wp_tailwindcss_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function wp_tailwindcss_scripts() {
	if (defined('IS_VITE_DEVELOPMENT') && IS_VITE_DEVELOPMENT === true) {

		// insert hmr into head for live reload
		function vite_head_module_hook() {
				echo '<script type="module" crossorigin src="' . VITE_SERVER . VITE_ENTRY_POINT . '"></script>';
		}
		add_action('wp_head', 'vite_head_module_hook');        
	} else {
			// Production Version read manifest.json to figure out what to enqueue
			$manifest = json_decode( file_get_contents( get_template_directory() . '/dist/manifest.json'), true );
			
			// is ok
			if (is_array($manifest)) {
					
					// get first key, by default is 'main.js' but it can change
					$manifest_key = array_keys($manifest);
					if (isset($manifest_key[0])) {
							
							// enqueue CSS files
							foreach(@$manifest[$manifest_key[0]]['css'] as $css_file) {
									wp_enqueue_style( 'main', get_template_directory_uri() . '//dist/' . $css_file );
							}
							
							// enqueue main JS file
							$js_file = @$manifest[$manifest_key[0]]['file'];
							if ( ! empty($js_file)) {
									wp_enqueue_script( 'main', get_template_directory_uri() . '//dist/' . $js_file, [], '', true );
							}
							
					}

			}
	}

	wp_enqueue_style( 'wp-tailwindcss-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'wp-tailwindcss-style', 'rtl', 'replace' );

	wp_enqueue_script( 'wp-tailwindcss-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'wp_tailwindcss_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}


add_filter( 'nav_menu_link_attributes', function($atts) {
	$atts['class'] = "p-5 block uppercase font-medium transition-all duration-300 hover:bg-gray-500 hover:text-white";
	return $atts;
}, 100, 1 );