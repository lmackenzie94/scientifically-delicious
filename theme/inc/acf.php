<?php
/**
 * ACF helper functions
 *
 */  


add_filter('acf/fields/google_map/api', function ( $api ) {
    $api['key'] = '';
    return $api;
});

// create blocks

add_action('acf/init', function () {
    // check function exists
    if (function_exists('acf_register_block')) {

        // hero
        acf_register_block([
            'name' => 'hero',
            'title' => __('Hero'),
            'description' => __('Hero'),
            'render_callback' => '__block_render_callback',
            'category' => 'my-blocks',
            'mode' => 'preview',
            'icon' => 'video-alt3',
            'keywords' => ['hero'],
            'supports' => [ 'multiple' => false, ],
        ]);     

        // recipe
acf_register_block([
    'name' => 'recipe',
    'title' => __('Recipe'),
    'description' => __('recipe'),
    'render_callback' => '__block_render_callback',
    'category' => 'my-blocks',
    'mode' => 'preview',
    'icon' => 'editor-alignleft',
    'keywords' => ['recipe'],
    //'supports' => [ 'multiple' => false, ],
]);

        // recipe-list
acf_register_block([
    'name' => 'recipe-list',
    'title' => __('Recipe List'),
    'description' => __('recipe-list'),
    'render_callback' => '__block_render_callback',
    'category' => 'my-blocks',
    'mode' => 'preview',
    'icon' => 'editor-alignleft',
    'keywords' => ['recipe-list'],
    //'supports' => [ 'multiple' => false, ],
]);


// home-recipe-suggestions
acf_register_block([
    'name' => 'home-recipe-suggestions',
    'title' => __('Recipe Suggestions'),
    'description' => __('home-recipe-suggestions'),
    'render_callback' => '__block_render_callback',
    'category' => 'my-blocks',
    'mode' => 'preview',
    'icon' => 'editor-alignleft',
    'keywords' => ['home-recipe-suggestions'],
    //'supports' => [ 'multiple' => false, ],
]);


    }
});

// renders blocks

function __block_render_callback($block, $content = '', $is_preview = false, $post_id = 0) {
    global $params;
    $slug = str_replace('acf/', '', $block['name']);
    $context = Timber::get_context();
    $context['slug'] = $slug;
    $context['post'] = new TimberPost($post_id);
    $context['block'] = $block;
    $context['fields'] = get_fields();

    $args = array(
    'posts_per_page' => -1,
    'post_type' => 'recipe',
    'orderby' => 'rand'
  );  

  $args2 = array(
    'posts_per_page' => -1,
    'post_type' => 'recipe',
  );  

  $context['all_recipes'] = Timber::get_posts($args2);
   $context['random_recipes'] = Timber::get_posts($args);
    Timber::render(array(
            "blocks/{$slug}.twig",
    ), $context);
}

// include site styles in block editor


add_action( 'enqueue_block_assets', function() {
	wp_enqueue_style(
		'block_css',
		get_bloginfo( 'stylesheet_directory' ) . '/build/bundle.css'
	);
});

// block templates


add_action('init', function() {
    $post_type_object = get_post_type_object( 'recipe' );
    $post_type_object->template = [
        [ 'acf/recipe' ],
    ];
    $post_type_object->template_lock = 'all';
});
