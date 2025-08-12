<?php
// if uninstall.php is not called by WordPress, die
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die;
}

delete_option("maika_ai_cid");
delete_option("maika_ai_cid_temp");
// delete_site_option("maika_ai_cid");
