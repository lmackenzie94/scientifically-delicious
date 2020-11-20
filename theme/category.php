<?php
  $context = Timber::get_context();
  $post = new TimberPost();
  $context['post'] = $post;
    // $context['posts'] = new Timber\PostQuery();
  Timber::render('category.twig', $context );
?>