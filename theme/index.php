<?php
  $context = Timber::get_context();
  $post = new TimberPost();
  $context['post'] = $post;
  $context['posts'] = Timber::get_posts();

  $templates = array( 'default.twig' );

  if(is_home()) {
	  array_unshift( $templates, 'blog-list.twig' );
  }

  Timber::render($templates, $context );
?>