<?php
 // Protect the file from direct access
 if (!defined('ABSPATH')) {
    exit;
 }

 register_rest_route('maika-api/v1', '/settings/', array(
    'methods' => 'POST',
    'callback' => 'maika_chatbox_update_setting',
    'permission_callback' => 'maika_api_authenticate_request', // Custom authentication callback
 ));
