<?php
/**
 * Ajax endpoints
 *
 */  
  
add_action( 'wp_ajax_submit_contact_form', 'my_submit_contact_form' );
add_action( 'wp_ajax_nopriv_submit_contact_form', 'my_submit_contact_form' );

function my_submit_contact_form()
{
  // get vars from POST data

  $first_name       = $_POST['first_name'];
  $last_name        = $_POST['last_name'];
  $email_address    = $_POST['email'];
  $phone_number     = $_POST['phone'];
  $message          = $_POST['message'];

  $contact_email    = get_field('contact_email', 'options');

  // message

  $body = '
  <div style="font-family: Arial, Helvetica; font-size: 14px; color: #333;">
  <p>
  <strong>Name&nbsp;&nbsp;&nbsp;</strong> ' . $first_name . ' ' . $last_name . '<br>
  <strong>Email&nbsp;&nbsp;&nbsp;</strong> ' . $email_address . '<br>
  <strong>Phone&nbsp;&nbsp;&nbsp;</strong> ' . $phone_number . '<p>
  <p><strong>Message</strong></p><hr>' . wpautop(stripslashes($message)) .' </div>' ;

  $to         = $contact_email ? $contact_email : get_option('admin_email');
  $subject    = '[' . get_bloginfo('title') . '] Contact form submission';
  $headers    = [
    'Content-Type: text/html; charset=UTF-8',
    'From: ' . get_bloginfo('title') . ' <' . get_field('site_email', 'options') . '>',
    'Reply-To: ' . $first_name . ' ' . $last_name . ' <' . $email_address . '>',
  ];

  if ( $to == $contact_email )
  {
    $headers[] = 'Cc: ' . get_option('admin_email');
  }

  $success = wp_mail( $to, $subject, $body, $headers );

  // Send the email

  if ( $success )
  {
    wp_send_json_success( [
      'message' => '<svg class="icon"><use xlink:href="#check"></svg> Message sent. Thanks for contacting us!',
    ] );
  }
  else
  {
    wp_send_json_error([
      'message' => '<svg class="icon"><use xlink:href="#x"></svg> There was a problem. Please try again!',
    ]);
  }

} 