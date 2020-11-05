<?php
  
if ( file_exists( __DIR__ . '/vendor/autoload.php') )
{
  require __DIR__ . '/vendor/autoload.php';
}
  
/***************************************************
 * Generic functions.php for child theme
 * DO NOT EDIT
 * Theme-specific functions go in inc/functions.theme.php
 */

// init variables

$this_theme = wp_get_theme();

if ( $this_theme->exists() )
{
	$theme_text_domain  = $this_theme->get( 'TextDomain' );
	$theme_version      = $this_theme->get( 'Version' );
	$parent_theme       = $this_theme->get( 'Template' );
}
else
{
	$theme_text_domain  = 'wp-theme';
	$theme_version      = '1.0';
	$parent_theme       = '';
}

// hide the admin bar

add_filter( 'show_admin_bar', '__return_false' );

// register navs

register_nav_menus( [
	'primary' 	=> __( 'Primary Menu', $theme_text_domain ),
	'footer' 		=> __( 'Footer Menu', $theme_text_domain ),
] );

// init sidebars

add_action( 'widgets_init', function() {
  
    global $theme_text_domain;
      
    // remove extra sidebars
    unregister_sidebar( 'sidebar-2' );
    unregister_sidebar( 'sidebar-3' );
    unregister_sidebar( 'sidebar-4' );
    unregister_sidebar( 'sidebar-5' );

    // sidebar
    register_sidebar( array(
        'name'          => __( 'Primary Sidebar', 'theme_name' ),
        'id'            => 'primary_sidebar',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
}, 11 );


// Clean up wordpress <head>
remove_action('wp_head', 'rsd_link'); // remove really simple discovery link
remove_action('wp_head', 'wp_generator'); // remove wordpress version
remove_action('wp_head', 'feed_links', 2); // remove rss feed links (make sure you add them in yourself if youre using feedblitz or an rss service)
remove_action('wp_head', 'feed_links_extra', 3); // removes all extra rss feed links
remove_action('wp_head', 'index_rel_link'); // remove link to index page
remove_action('wp_head', 'wlwmanifest_link'); // remove wlwmanifest.xml (needed to support windows live writer)
remove_action('wp_head', 'start_post_rel_link', 10, 0); // remove random post link
remove_action('wp_head', 'parent_post_rel_link', 10, 0); // remove parent post link
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // remove the next and previous post links
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

/**
 * Theme assets
 */
add_action('wp_enqueue_scripts', function () {
    $manifest = json_decode(file_get_contents('build/assets.json', true));
    $main = $manifest->main;

    // theme assets
    wp_enqueue_style('theme-css', get_template_directory_uri() . "/build/" . $main->css,  false, null);
    wp_enqueue_script('theme-js', get_template_directory_uri() . "/build/" . $main->js, ['jquery'], null, true);

    // google fonts
    // wp_enqueue_style('add_google_fonts', 'https://fonts.googleapis.com/css2?family=Amatic+SC:wght@400;700&display=swap', false);



    // modernizr
    wp_enqueue_script('modernizr', '//cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.min.js' );    
    
    // localize js
    wp_localize_script('theme-js', 'env', [
        'siteUrl' => get_bloginfo('url'),
        'themeUrl' => get_bloginfo( 'stylesheet_directory' ),
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
    ]);
}, 100);


/**
 * Theme setup
 */
add_action('after_setup_theme', function () {
    /**
     * Enable features from Soil when plugin is activated
     * @link https://roots.io/plugins/soil/
     */
    add_theme_support('soil-clean-up');
    add_theme_support('soil-nav-walker');
    add_theme_support('soil-nice-search');
    add_theme_support('soil-relative-urls');
    /**
     * Enable plugins to manage the document title
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
     */
    add_theme_support('title-tag');
    /**
     * Register navigation menus
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */
    register_nav_menus([
        'primary_navigation' => __('Primary Navigation', 'mini')
    ]);
    /**
     * Enable post thumbnails
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');
    /**
     * Enable HTML5 markup support
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
     */
    add_theme_support('html5', ['caption', 'comment-form', 'comment-list', 'gallery', 'search-form']);
    /**
     * Enable selective refresh for widgets in customizer
     * @link https://developer.wordpress.org/themes/advanced-topics/customizer-api/#theme-support-in-sidebars
     */
    // add_theme_support('customize-selective-refresh-widgets');

    // add editor styles
    add_theme_support( 'editor-styles');
    add_editor_style( 'style-editor.css' );

}, 20);


add_action('rest_api_init', function () {
	$namespace = 'presspack/v1';
	register_rest_route( $namespace, '/path/(?P<url>.*?)', array(
		'methods'  => 'GET',
		'callback' => 'get_post_for_url',
	));
});

/**
* This fixes the wordpress rest-api so we can just lookup pages by their full
* path (not just their name). This allows us to use React Router.
*
* @return WP_Error|WP_REST_Response
*/
function get_post_for_url($data)
{
    $postId    = url_to_postid($data['url']);
    $postType  = get_post_type($postId);
    $controller = new WP_REST_Posts_Controller($postType);
    $request    = new WP_REST_Request('GET', "/wp/v2/{$postType}s/{$postId}");
    $request->set_url_params(array('id' => $postId));
    return $controller->get_item($request);
}

add_filter('body_class', 'add_slug_to_body_class'); // Add slug to body class (Starkers build)

// Add page slug to body class, love this - Credit: Starkers Wordpress Theme
function add_slug_to_body_class($classes) {
    global $post;
    if (is_home()) {
        $key = array_search('blog', $classes);
        if ($key > -1) {
            unset($classes[$key]);
        }
    } elseif (is_page()) {
        $classes[] = sanitize_html_class($post->post_name);
    } elseif (is_singular()) {
        $classes[] = sanitize_html_class($post->post_name);
    }
    return $classes;
}

// search filter (only Recipes)

function search_filter($query) {
    if ($query->is_search()) {
        $query->set('post_type', 'recipe');
    }
}

add_filter('pre_get_posts', 'search_filter');

// archive filter (only Recipes show on Category pages)

function archive_filter($query) {
    if ($query->is_category()) {
        $query->set('post_type', 'recipe');
    }
}

add_filter('pre_get_posts', 'archive_filter');


// load theme's extra functions file

require_once( get_stylesheet_directory() . '/inc/functions.theme.php' );

