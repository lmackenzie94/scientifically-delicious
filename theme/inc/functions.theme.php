<?php
/**
 * Site theme functions
 *
 */

// extras

if ( file_exists(__DIR__ . '/env.php') ) include('env.php');
if ( file_exists(__DIR__ . '/cpt.php') ) include('cpt.php');
if ( file_exists(__DIR__ . '/ajax.php') ) include('ajax.php');
if ( file_exists(__DIR__ . '/api.php') ) include('api.php');
if ( file_exists(__DIR__ . '/acf.php') ) include('acf.php');
if ( file_exists(__DIR__ . '/timber.php') ) include('timber.php');

// ACF options page

if( function_exists('acf_add_options_page') ) 
{

	// add parent
	$parent = acf_add_options_page([
		'page_title'   => 'Site General Settings',
		'menu_title'   => 'Site Settings',
		'redirect'     => false
	]);

}
	 
// image sizes

if ( function_exists( 'add_image_size' ) ) 
{
	add_image_size( 'xlarge', 2000, 1500 );
}

function __svg( $filename )
{
	// filename is a local file in assets/img/
	if ( file_exists( get_stylesheet_directory() . "/assets/img/{$filename}.svg" ) )
	{
		return file_get_contents( get_stylesheet_directory() . "/assets/img/{$filename}.svg" );
	}
	// if filename is a file_id
	if ( get_attached_file($filename) ) {
		return file_get_contents( get_attached_file($filename) );
	}
	// filename is a URL
	if ( filter_var($filename, FILTER_VALIDATE_URL) ) 
	{ 
	  return file_get_contents( $filename );
	}	
	return '';
}
