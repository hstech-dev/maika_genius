<?php
 // Path /wp-json/maika-api/v1/pages (Get all pages)
//  register_rest_route('maika-api/v1', '/pages/', array(
//      'methods' => 'GET',
//      'callback' => 'maika_api_get_pages',
//      'permission_callback' => 'maika_api_authenticate_request', // Custom authentication callback
//  ));

 register_rest_route('maika-api/v1', '/settings/', array(
    'methods' => 'POST',
    'callback' => 'maika_chatbox_update_setting',
    'permission_callback' => 'maika_api_authenticate_request', // Custom authentication callback
));
