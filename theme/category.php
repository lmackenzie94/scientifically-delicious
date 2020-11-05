<?php
  $context = Timber::get_context();
  $post = new TimberPost();
  $context['post'] = $post;
  //   $context['post'] = new Timber\PostQuery();
  Timber::render('category.twig', $context );
?>