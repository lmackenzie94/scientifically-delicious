<?php
	
$timber = new \Timber\Timber();

\Timber::$dirname = [ 'twig', 'twig/pages', 'twig/sections', 'twig/blocks', 'twig/partials' ];
  
// timber context

add_filter('timber_context', function($context) 
{
  global $post;

  $context['header_menu'] = new \Timber\Menu( 'primary' );
  $context['footer_menu'] = new \Timber\Menu( 'footer' );
  $context['icons'] = file_get_contents( get_stylesheet_directory() . '/build/spritemap.svg');

  $context['options'] = get_fields('options');
  $context['primary_sidebar'] = Timber::get_widgets('primary_sidebar');

  return $context;
} );

// Custom Twig filters

add_filter( 'get_twig', function ( $twig ) 
{
  $twig->addExtension( new Twig_Extension_StringLoader() );
  
  // filters

  $twig->addFilter( new Twig_SimpleFilter( 'svg', function( $value ) {
    return __svg($value);
  } ) );

  $twig->addFilter( new Twig_SimpleFilter( 'icon', function( $value ) {
    return '<svg class="icon"><use xlink:href="#sprite-' . $value . '"></use></svg>';
  } ) );

  // functions  
  
  $twig->addFunction(new Timber\Twig_Function('dump', function($var) {
    return '<pre>' . print_r($var, true) . '</pre>';
  } ) );  

  $twig->addFunction(new Timber\Twig_Function('shuffle', function($array) {
    shuffle($array);
    return $array;
  } ) );  

  return $twig;
} );
