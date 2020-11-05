<?php
  $context = Timber::get_context();
  $post = new TimberPost();
  $context['post'] = $post;
  $context['search_term'] = get_search_query();
//   $context['post'] = new Timber\PostQuery();
  Timber::render('search.twig', $context );
?>