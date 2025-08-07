<?php
 // Protect the file from direct access
 if (!defined('ABSPATH')) {
   exit;
 }
 
 // Authentication callback
 function maika_api_authenticate_request($request) {
    return true;
 }