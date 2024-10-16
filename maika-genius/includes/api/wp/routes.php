<?php
 // Update secret key and cid
 function maika_chatbox_update_setting($data) {
    // Get Body Params...
    $parameters = $data->get_params();
    $cid = isset($parameters['cid']) ? $parameters['cid'] : null;
    $ssid = isset($parameters['ssid']) ? $parameters['ssid'] : null;
    $secret_key = isset($parameters['secret_key']) ? $parameters['secret_key'] : null;

    if($cid == null || $ssid == null || $secret_key == null){
        return new WP_Error(
            'missing',
            'Missing param...',
            array('status' => 400)
        );
    }

    $check_maika_ssid = get_option("maika_ssid");
    if($ssid == $check_maika_ssid){
        update_option("maika_ai_secretKey", $secret_key);
        update_option("maika_ai_cid", $cid);

        delete_option("maika_ssid");
        // Sample data to return
        $response = array(
            'status' => 'success',
            'message' => 'success'
        );
        return rest_ensure_response($response);
    }

    return new WP_Error(
        'invalid',
        'Session_ID invalid',
        array('status' => 400)
    );
 }
