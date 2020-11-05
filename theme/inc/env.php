<?php
  
// ===========================
// Set environment

if (strpos($_SERVER['SERVER_NAME'], '.local') !== false || strpos($_SERVER['SERVER_NAME'], 'localhost') !== false)
{
  define('ENV', 'local');
  error_reporting(E_ERROR | E_WARNING);
}
else if (strpos($_SERVER['SERVER_NAME'], '.wpengine.com') !== false)
{
  define('ENV', 'staging');
  update_option( 'blog_public', 0 ); // discourage search engines
}
else
{
  define('ENV', 'production');
  update_option( 'blog_public', 1 ); // encourage search engines
}  