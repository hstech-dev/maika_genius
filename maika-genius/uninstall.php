<?php
// if uninstall.php is not called by WordPress, die
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die;
}

// delete_option("maika_ai_secretKey");
// for site options in Multisite
// delete_site_option("maika_ai_secretKey");

//delete_option("maika_ai_favcolor");
// delete_site_option("maika_ai_favcolor");

//delete_option("maika_ai_title");
// delete_site_option("maika_ai_title");

delete_option("maika_ai_cid");
// delete_site_option("maika_ai_cid");

delete_option("maika_ssid");
// delete_site_option("maika_ssid");