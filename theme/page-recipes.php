<?php
  $context = Timber::get_context();
  $post = new TimberPost();
  $categories = Timber::get_terms();
  $context['post'] = $post;
  $context['categories'] = $categories;

  global $paged;
	if (!isset($paged) || !$paged){
		$paged = 1;
  }
  
	$args = array(
		'post_type' => 'event',
		'posts_per_page' => 5,
		'paged' => $paged
	);

  $args = array(
    // 'numberposts' => -1,
    'posts_per_page' => 2,
    'post_type' => 'recipe',
    'paged' => $paged
  );  
  $context['all_recipes'] = new Timber\PostQuery($args);

  Timber::render('recipes.twig', $context );
?>