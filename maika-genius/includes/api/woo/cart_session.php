<?php
 // Protect the file from direct access
 if (!defined('ABSPATH')) {
    exit;
 }

add_action('wp_ajax_init_woocommerce_session_maika_genius', 'maika_genius_custom_init_woocommerce_session');
add_action('wp_ajax_nopriv_init_woocommerce_session_maika_genius', 'maika_genius_custom_init_woocommerce_session');

/**
 * Function handle init WooCommerce session.
 */
function maika_genius_custom_init_woocommerce_session() {
    if (!defined( 'WC_ABSPATH')) {
        wp_send_json_error( array( 'message' => 'WooCommerce is not active.'));
        exit;
    }

    if (!function_exists( 'wc_load_cart')) {
        include_once WC_ABSPATH . 'includes/wc-cart-functions.php';
    }
    if (!class_exists( 'WC_Cart')) {
        include_once WC_ABSPATH . 'includes/class-wc-cart.php';
    }

    if (!did_action( 'woocommerce_init')) {
        wp_send_json_error( array( 'message' => 'WooCommerce not initialized yet.'));
        exit;
    }

    if (!WC()->session->has_session()){
        WC()->session->set_customer_session_cookie(true);
    }

    if (is_null( WC()->cart)) {
         wc_load_cart();
    }

    WC()->cart->get_cart();

    // Success
    wp_send_json_success( array( 'message' => 'WooCommerce session and cart initialized successfully.'));

    wp_die();
}

/**
 * Register script and sent variable AJAX URL for JavaScript.
 */
add_action( 'wp_enqueue_scripts', 'maike_genius_enqueue_scripts_for_session_init' );
function maike_genius_enqueue_scripts_for_session_init() {
    wp_localize_script( 'jquery', 'my_custom_ajax_params', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
    ));
}