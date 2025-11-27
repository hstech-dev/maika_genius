<?php
/**
 * Plugin Name: Maika Genius
 * Plugin URI:  https://www.askmaika.ai/maika-genius/
 * Description: Tired of spending hours writing product descriptions and optimizing your website? Maika Genius is the Al-powered solution that empowers you to create engaging content, boost SEO, and drive sales, all with the power of cutting-edge Generative Al.
 * Version:     1.4.1
 * Author:      tomaskmaika
 * Author URI:  https://www.askmaika.ai
 * Text Domain: maika-genius
 * License:     GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * 
 */
 
 // Protect the file from direct access
 if (!defined('ABSPATH')) {
     // demo: Maika CID: dev_hiy__hsnails_stack-us3_st4as_com 
     exit;
 }
 
 // Hook add link 'Settings' page plugin
 add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'maika_add_settings_link');
 function maika_add_settings_link($links) {
     // create link 'Settings'
     $settings_link = '<a href="admin.php?page=maika-genius-settings">' . __('Settings', 'maika-genius') . '</a>';
     
     // add link 'Settings' before the button 'Deactivate'
     array_unshift($links, $settings_link);
     
     return $links;
 }
 
 add_action("admin_menu", "maika_show_setting_page");
 function maika_show_setting_page(){
    // main menu
    add_menu_page(
        "Maika Genius",
        "Maika Genius",
        "manage_options",
        "maika-genius",
        "maika_genius_home_page", //maika_setting_page
        "dashicons-welcome-learn-more",
        8
    );

    // sub menu
    add_submenu_page(
      "maika-genius",             // Slug parent menu
      "Home",                     // Page title
      "Home",                     // Menu title
      "manage_options",           // Permission
      "maika-genius",             // Slug submenu
      "maika_genius_home_page"    // Callback function
    );

    add_submenu_page(
      "maika-genius",
      "Settings",
      "Settings",
      "manage_options",
      "maika-genius-guide",
      "maika_genius_guide_page"
    );

    // add_submenu_page(
    //   "maika-genius",
    //   "Settings",
    //   "Settings",
    //   "manage_options",
    //   "maika-genius-settings",
    //   "maika_genius_settings_page"
    // );

    // add_submenu_page(
    //   "maika-genius",
    //   "Shop Structure",
    //   "Shop Structure",
    //   "manage_options",
    //   "maika-genius-shop-structure",
    //   "maika_genius_shop_structure_page"
    // );

    // add_submenu_page(
    //   "maika-genius",
    //   "Structure Editor",
    //   "Structure Editor",
    //   "manage_options",
    //   "maika-genius-shop-structure-editor",
    //   "maika_genius_shop_structure_editor_page"
    // );

    // add_submenu_page(
    //   "maika-genius",
    //   "Catalog Builder",
    //   "Catalog Builder",
    //   "manage_options",
    //   "maika-genius-product-catalog-builder",
    //   "maika_genius_product_catalog_builder_page"
    // );

    // add_submenu_page(
    //   "maika-genius",
    //   "Product Descriptor",
    //   "Product Descriptor",
    //   "manage_options",
    //   "maika-genius-product-descriptor",
    //   "maika_genius_product_descriptor_page"
    // );

    // add_submenu_page(
    //   "maika-genius",
    //   "SEO Optimizer",
    //   "SEO Optimizer",
    //   "manage_options",
    //   "maika-genius-seo-optimizer",
    //   "maika_genius_seo_optimizer_page"
    // );

    // add_submenu_page(
    //   "maika-genius",
    //   "Livechat",
    //   "Livechat",
    //   "manage_options",
    //   "maika-genius-livechat",
    //   "maika_genius_livechat_page"
    // );
 }

 // Notice
 add_action('admin_notices', 'shop_structure_admin_notice');
 function shop_structure_admin_notice(){
    // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    if (isset($_GET['page']) && strpos(sanitize_text_field(wp_unslash($_GET['page'])), 'maika-genius') === 0) {
      return;
    }
    ?>
    <div class="notice maika-genius-notice-info is-dismissible">
      <div class="maika-genius-notice-layout">
        <div class="maika-genius-notice-aside">
          <?php 
            maika_show_image_from_plugin('maika-genius.png', $alt = 'maika genius logo', $class = '')
          ?>
        </div>
        <div class="maika-genius-notice-content">
          <?php
            $maika_cid = get_option("maika_ai_cid");
            if($maika_cid != false){
              ?>
                <h2 class="maika-genius-notice-content-title">Maika Genius - What's shaping your store’s success?</h2>
                <p>Your store has a hidden structure that drives conversions, but you haven't seen it yet. Click <em>Analyze Your Store Free</em> to reveal how everything connects – and what’s missing.</p>
                <p>&#10024; A single click could transform the way you organize and sell.</p>
                <a href="/wp-admin/admin.php?page=maika-genius-shop-structure">
                  <button class="maika-genius-notice-content-button">Analyze Your Store Free</button>
                </a>
              <?php
            }
            else {
              ?>
                <h2 class="maika-genius-notice-content-title">Maika Genius - Unlock the Hidden Potential of Your Store</h2>
                <p>You’re just one step away from uncovering the structure behind high-converting stores. Complete the essential setup so Maika Genius can start analyzing and offering insights to help you improve your store’s performance.</p>
                <a href="/wp-admin/admin.php?page=maika-genius-guide">
                  <button class="maika-genius-notice-content-button">Set Up Maika Genius</button>
                </a>
              <?php
            }
          ?>
          
        </div>
      </div>
    </div>
    <?php
 }

 // CSS for notice
 add_action('admin_enqueue_scripts', function () {
    wp_add_inline_style('wp-admin', "
      .maika-genius-notice-layout {
        display: flex;
        column-gap: 12px;
        padding-top: 12px;
        padding-bottom: 12px;
      }
      .maika-genius-notice-info {
        border-left-color: #9333EA;
      }
      .maika-genius-notice-aside {
        width: 40px;
      }
      .maika-genius-notice-aside img {
        width: 100%;
      }
      .maika-genius-notice-content {
        width: calc(100% - 40px);
      }
      .maika-genius-notice-content-title {
        margin-top: .5em;
      }
      .maika-genius-notice-content-button {
        margin-top: 10px;
        padding: 8px 20px 10px;
        background: #9333EA;
        border-width: 0;
        border-radius: 4px;
        outline: none;
        color: white;
        font-weight: 500;
        transition: all 300ms;
      }
      .maika-genius-notice-content-button:hover {
        cursor: pointer;
        background: #7e28c9;
      }
    ");
 });
 // End - Notice

 // Callback function for Home page
 function maika_genius_home_page() {
    if(file_exists(plugin_dir_path(__FILE__).'includes/config/constants.php')){
      require_once plugin_dir_path(__FILE__).'includes/config/constants.php';

      // if(file_exists(plugin_dir_path(__FILE__).'assets/html/content_home.html')){
      if(file_exists(plugin_dir_path(__FILE__).'assets/html/content_home.php')){
        wp_enqueue_style('admin-maika-tailwind-css');

        $maika_cid = get_option("maika_ai_cid");

        // ob_start();
        // require_once plugin_dir_path(__FILE__).'assets/html/content_home.html';
        require_once plugin_dir_path(__FILE__).'assets/html/content_home.php';
        // $htmlContent = ob_get_clean();
      
        // echo wp_kses($htmlContent, Maika_Constants::MAIKA_ALLOWED_TAGS_HTML);
      }
    }
 }

 // Callback function for Guide page
 function maika_genius_guide_page() {
    //echo "Maika CID: " .get_option("maika_ai_cid") ."<br>";
    $pass_guide_step = maika_check_pass_guide_step();

    wp_enqueue_style('admin-maika-tailwind-css');
    wp_enqueue_style('admin-maika-css');

    maika_guide_process_bar();
 }

 function maika_genius_settings_page() {
    // --- Enqueue style
    wp_enqueue_style('admin-maika-css');
    wp_enqueue_style('admin-maika-tailwind-css');
    // wp_enqueue_style('admin-maika-mautic');
    // --- Enqueue JavaScript file
    // wp_enqueue_script('admin-maika-tabs');
    wp_enqueue_script('admin-maika-iframe-notification');
    wp_enqueue_script('admin-maika-iframe-resizer');

    $domain_web = maika_getlink_domain_web();

    // check rfa
    $maika_rfa = maika_check_rfa();
    if($maika_rfa == "true"){
      maika_show_iframe_for_rfa();
    }

    $maika_cid = get_option("maika_ai_cid");
    if ($maika_rfa != "true" && $maika_cid == false){
      maika_show_popup_if_not_integration("the Settings feature");
    }

    // [Action] Button 'Disconnect - Clear all data'
    if (isset($_POST['clearAll']) && check_admin_referer('maika_ai_clear_all_settings_action', 'maika_ai_clear_all_settings_nonce')){
      // Disconnect website from Hub
      $maika_disconnect_body_data = [
        "cid" => $maika_cid
      ];
      maika_call_post_api(esc_url('https://hub.askmaika.ai/app/api/woo/disconnect_site'), $maika_disconnect_body_data);

      delete_option("maika_ai_cid");
      delete_option("maika_ai_cid_temp");
      // delete_option("maika_ssid");
      $del_AP = maika_delete_application_password_exists("maika");
      $del_WooAPI = maika_delete_woocommerce_api_keys();

      //redirect link
      $rd = esc_url($domain_web)."/wp-admin/admin.php?page=maika-genius";

      wp_localize_script('admin-maika-disconnect', 'adminMaikaEngineData', array(
        'slugMaikaGenius' => esc_url($rd),
      ));
      // Load into script
      wp_enqueue_script('admin-maika-disconnect');
    }

    // maika_guide_process_bar();
    ?>
    <div class="maika-tab-container" <?php echo $maika_rfa != "true" ? "" : "style='display: none'"; ?>>
      <div class="maika-tab-content" id="settings">
        <!-- content -->
        <?php
          $hideBtnClearAllData = false;
          if($maika_cid != false){ //$maika_connected_maikahub === true
            echo "<div id='iframe_maika_container_settings'>";
              echo "
                <iframe id='MAIKA_IFRAME_settings' src='https://hub.askmaika.ai/app/site?cid=".esc_html($maika_cid)."&display_mode=embed&wp_domain=".esc_url($domain_web)."&mode=setting' style='border: none; height: auto; width: 100%; min-height: 800px'></iframe>
              ";
            echo "</div>";
          }
          else {
            $hideBtnClearAllData = true;
            $pass_guide_step = maika_check_pass_guide_step();
            echo "<div style='display: flex; align-items: center; gap: 10px;'>
              <h2 style='color: red; font-weight: 500; font-size: 1.2rem;'>You need to connect to Maika Hub!</h2>";
            if($pass_guide_step == 0){
              echo "<a target='_blank' href='".esc_url($domain_web)."/wp-admin/admin.php?page=maika-genius-guide'><button class='maika-genius-button'>Connect Now With Guide</button></a>";
            }
            echo "</div>";
          }

          if(!$hideBtnClearAllData){
        ?>
            <hr style="margin-bottom: 14px;">
            <form method="post">
              <?php wp_nonce_field('maika_ai_clear_all_settings_action', 'maika_ai_clear_all_settings_nonce'); ?>

              <input style="cursor: pointer;"
                class="maika-reset-button"
                type="submit" name="clearAll" value="Disconnect - Clear all data" />
            </form>
        <?php
          } // end --- if(!$hideBtnClearAllData)
        ?>
      </div>
    </div>
    <?php
 }

 function maika_genius_shop_structure_page(){
    // --- Enqueue style
    wp_enqueue_style('admin-maika-css');
    wp_enqueue_style('admin-maika-tailwind-css');
    // wp_enqueue_style('admin-maika-mautic');
    // --- Enqueue JavaScript file
    // wp_enqueue_script('admin-maika-tabs');
    wp_enqueue_script('admin-maika-iframe-notification');
    wp_enqueue_script('admin-maika-iframe-resizer');

    $domain_web = maika_getlink_domain_web();

    // check rfa
    $maika_rfa = maika_check_rfa();
    if($maika_rfa == "true"){
      maika_show_iframe_for_rfa();
    }

    $maika_cid = get_option("maika_ai_cid");
    if ($maika_rfa != "true" && $maika_cid == false){
      maika_show_popup_if_not_integration("the Shop Structure feature");
    }

    // maika_guide_process_bar();
  ?>
    <div class="maika-tab-container" <?php echo $maika_rfa != "true" ? "" : "style='display: none'"; ?>>
      <div class="maika-tab-content" id="shop-structure">
      <?php 
        if($maika_cid != false){
          echo "<div id='iframe_maika_container_shop-structure'>";
            echo "
            <iframe id='MAIKA_IFRAME_shop-structure' src='https://hub.askmaika.ai/app/woo_shop_structure?cid=".esc_html($maika_cid)."&display_mode=embed&wp_domain=".esc_url($domain_web)."' style='border: none; height: auto; width: 100%; min-height: 800px'></iframe>
            ";
          echo "</div>";
        }
        else {
          echo "<h2 style='color: red; font-weight: 500; font-size: 1.2rem;'>You need to connect to Maika Hub to use this feature!</h2>";
        }
      ?>
      </div>
    </div>
  <?php
 }

  function maika_genius_shop_structure_editor_page(){
    // --- Enqueue style
    wp_enqueue_style('admin-maika-css');
    wp_enqueue_style('admin-maika-tailwind-css');
    // wp_enqueue_style('admin-maika-mautic');
    // --- Enqueue JavaScript file
    // wp_enqueue_script('admin-maika-tabs');
    wp_enqueue_script('admin-maika-iframe-notification');
    wp_enqueue_script('admin-maika-iframe-resizer');

    $domain_web = maika_getlink_domain_web();

    // check rfa
    $maika_rfa = maika_check_rfa();
    if($maika_rfa == "true"){
      maika_show_iframe_for_rfa();
    }

    $maika_cid = get_option("maika_ai_cid");
    if ($maika_rfa != "true" && $maika_cid == false){
      maika_show_popup_if_not_integration("the Structure Editor feature");
    }

    // maika_guide_process_bar();
  ?>
    <div class="maika-tab-container" <?php echo $maika_rfa != "true" ? "" : "style='display: none'"; ?>>
      <div class="maika-tab-content" id="structure-editor">
      <?php 
        if($maika_cid != false){
          echo "<div id='iframe_maika_container_structure-editor'>";
            echo "
            <iframe id='MAIKA_IFRAME_structure-editor' src='https://hub.askmaika.ai/app/woo_shop_structure_edit?cid=".esc_html($maika_cid)."&display_mode=embed&wp_domain=".esc_url($domain_web)."' style='border: none; height: auto; width: 100%; min-height: 800px'></iframe>
            ";
          echo "</div>";
        }
        else {
          echo "<h2 style='color: red; font-weight: 500; font-size: 1.2rem;'>You need to connect to Maika Hub to use this feature!</h2>";
        }
      ?>
      </div>
    </div>
  <?php
 }

 function maika_genius_product_catalog_builder_page(){
    // --- Enqueue style
    wp_enqueue_style('admin-maika-css');
    wp_enqueue_style('admin-maika-tailwind-css');
    // wp_enqueue_style('admin-maika-mautic');
    // --- Enqueue JavaScript file
    // wp_enqueue_script('admin-maika-tabs');
    wp_enqueue_script('admin-maika-iframe-notification');
    wp_enqueue_script('admin-maika-iframe-resizer');

    $domain_web = maika_getlink_domain_web();

    // check rfa
    $maika_rfa = maika_check_rfa();
    if($maika_rfa == "true"){
      maika_show_iframe_for_rfa();
    }

    $maika_cid = get_option("maika_ai_cid");
    if ($maika_rfa != "true" && $maika_cid == false){
      maika_show_popup_if_not_integration("the Catalog Builder feature");
    }

    // maika_guide_process_bar();
  ?>
    <div class="maika-tab-container" <?php echo $maika_rfa != "true" ? "" : "style='display: none'"; ?>>
      <div class="maika-tab-content" id="product-catalog-builder">
        <?php
          if($maika_cid != false){
            echo "<div id='iframe_maika_container_product-catalog-builder'>";
              echo "
              <iframe id='MAIKA_IFRAME_product-catalog-builder' src='https://hub.askmaika.ai/app/woo_prod_catalog?cid=".esc_html($maika_cid)."&display_mode=embed&wp_domain=".esc_url($domain_web)."' style='border: none; height: auto; width: 100%; min-height: 800px'></iframe>
              ";
            echo "</div>";
          }
          else{
            if(file_exists(plugin_dir_path(__FILE__).'assets/html/content_prod_cat_builder.html') 
            && file_exists(plugin_dir_path(__FILE__).'includes/config/constants.php')){
              ob_start();
              require_once plugin_dir_path(__FILE__).'assets/html/content_prod_cat_builder.html';
              $htmlContent = ob_get_clean();

              require_once plugin_dir_path(__FILE__).'includes/config/constants.php';
              echo wp_kses($htmlContent, Maika_Constants::MAIKA_ALLOWED_TAGS_HTML);
            }
            else{
              echo "<h2 style='color: red; font-weight: 500; font-size: 1.2rem;'>You need to connect to Maika Hub to use this feature!</h2>";
            }
          }
        ?>
      </div>
    </div>
  <?php
 }

 function maika_genius_product_descriptor_page(){
    // --- Enqueue style
    wp_enqueue_style('admin-maika-css');
    wp_enqueue_style('admin-maika-tailwind-css');
    // wp_enqueue_style('admin-maika-mautic');
    // --- Enqueue JavaScript file
    // wp_enqueue_script('admin-maika-tabs');
    wp_enqueue_script('admin-maika-iframe-notification');
    wp_enqueue_script('admin-maika-iframe-resizer');

    $domain_web = maika_getlink_domain_web();

    // check rfa
    $maika_rfa = maika_check_rfa();
    if($maika_rfa == "true"){
      maika_show_iframe_for_rfa();
    }

    $maika_cid = get_option("maika_ai_cid");
    if ($maika_rfa != "true" && $maika_cid == false){
      maika_show_popup_if_not_integration("the Product Descriptor feature");
    }

    // maika_guide_process_bar();
  ?>
    <div class="maika-tab-container" <?php echo $maika_rfa != "true" ? "" : "style='display: none'"; ?>>
      <div class="maika-tab-content" id="product-descriptor">
        <?php 
          if($maika_cid != false){ //$maika_connected_maikahub === true
            // echo "
            // <a href='https://hub.askmaika.ai/app/woo_prod_revise?cid=$maika_cid&secret_key=$maika_secretKey' target='_blank'><button style='margin-bottom: 20px; border: 2px solid #ececec; padding: 4px 12px;' >Go to AI Product Descriptor</button></a>
            // ";
          
            echo "<div id='iframe_maika_container_product-descriptor'>";
              echo "
              <iframe id='MAIKA_IFRAME_product-descriptor' src='https://hub.askmaika.ai/app/woo_prod_revise?cid=".esc_html($maika_cid)."&display_mode=embed&wp_domain=".esc_url($domain_web)."' style='border: none; height: auto; width: 100%; min-height: 800px'></iframe>
              ";
            echo "</div>";
          }
          else{
            if(file_exists(plugin_dir_path(__FILE__).'assets/html/content_prod_descriptor.html') 
            && file_exists(plugin_dir_path(__FILE__).'includes/config/constants.php')){
              ob_start();
              require_once plugin_dir_path(__FILE__).'assets/html/content_prod_descriptor.html';
              $htmlContent = ob_get_clean();

              require_once plugin_dir_path(__FILE__).'includes/config/constants.php';
              echo wp_kses($htmlContent, Maika_Constants::MAIKA_ALLOWED_TAGS_HTML);
            }
            else{
              echo "<h2 style='color: red; font-weight: 500; font-size: 1.2rem;'>You need to connect to Maika Hub to use this feature!</h2>";
            }
          }
        ?>
      </div>
    </div>
  <?php
 }

 function maika_genius_seo_optimizer_page(){
    // --- Enqueue style
    wp_enqueue_style('admin-maika-css');
    wp_enqueue_style('admin-maika-tailwind-css');
    wp_enqueue_style('admin-maika-mautic');
    // --- Enqueue JavaScript file
    // wp_enqueue_script('admin-maika-tabs');
    wp_enqueue_script('admin-maika-iframe-notification');
    wp_enqueue_script('admin-maika-iframe-resizer');

    $domain_web = maika_getlink_domain_web();

    // check rfa
    $maika_rfa = maika_check_rfa();
    if($maika_rfa == "true"){
      maika_show_iframe_for_rfa();
    }

    $maika_cid = get_option("maika_ai_cid");

    // maika_guide_process_bar();
  ?>
    <div class="maika-tab-container" <?php echo $maika_rfa != "true" ? "" : "style='display: none'"; ?>>
      <div class="maika-tab-content" id="seo-optimizer">
        <?php
          if(file_exists(plugin_dir_path(__FILE__).'assets/html/content_seo_opt.html') 
          && file_exists(plugin_dir_path(__FILE__).'includes/config/constants.php')){
            ob_start();
            require_once plugin_dir_path(__FILE__).'assets/html/content_seo_opt.html';
            $htmlContent = ob_get_clean();

            require_once plugin_dir_path(__FILE__).'includes/config/constants.php';
            echo wp_kses($htmlContent, Maika_Constants::MAIKA_ALLOWED_TAGS_HTML);
          }
        ?>
      </div>
    </div>
  <?php
 }

 function maika_genius_livechat_page(){
    // --- Enqueue style
    wp_enqueue_style('admin-maika-css');
    wp_enqueue_style('admin-maika-tailwind-css');
    wp_enqueue_style('admin-maika-mautic');
    // --- Enqueue JavaScript file
    // wp_enqueue_script('admin-maika-tabs');
    wp_enqueue_script('admin-maika-iframe-notification');
    wp_enqueue_script('admin-maika-iframe-resizer');

    $domain_web = maika_getlink_domain_web();

    // check rfa
    $maika_rfa = maika_check_rfa();
    if($maika_rfa == "true"){
      maika_show_iframe_for_rfa();
    }
  
    $maika_cid = get_option("maika_ai_cid");
    if ($maika_rfa != "true" && $maika_cid == false){
      maika_show_popup_if_not_integration("the Livechat feature");
    }
  
    // maika_guide_process_bar();
  ?>
    <div class="maika-tab-container" <?php echo $maika_rfa != "true" ? "" : "style='display: none'"; ?>>
      <div class="maika-tab-content" id="livechat">
        <?php
          if($maika_cid != false){ //$maika_connected_maikahub === true
            echo "<div id='iframe_maika_container_livechat'>";
              echo "
                <iframe id='MAIKA_IFRAME_livechat' src='https://hub.askmaika.ai/app/site?cid=".esc_html($maika_cid)."&display_mode=embed&wp_domain=".esc_url($domain_web)."&mode=livechat' style='border: none; height: auto; width: 100%; min-height: 800px'></iframe>
              ";
            echo "</div>";
          }
          else {
            if(file_exists(plugin_dir_path(__FILE__).'assets/html/content_livechat.html') 
              && file_exists(plugin_dir_path(__FILE__).'includes/config/constants.php')){
                ob_start();
                require_once plugin_dir_path(__FILE__).'assets/html/content_livechat.html';
                $htmlContent = ob_get_clean();
             
                require_once plugin_dir_path(__FILE__).'includes/config/constants.php';
                echo wp_kses($htmlContent, Maika_Constants::MAIKA_ALLOWED_TAGS_HTML);
              }
              else{
                echo "<h2 style='color: red; font-weight: 500; font-size: 1.2rem;'>You need to connect to Maika Hub to use this feature!</h2>";
              }
          }
        ?>
      </div>
    </div>
  <?php
 }

 // ****************************************************
 // ==================== ENQUEUE ADMIN SCRIPTS - REGISTER JavaScript, CSS,... ADMIN page: maika-genius ====================
 // JavaScript
 function maika_enqueue_admin_scripts($hook) {
    // Check plug
    if (  $hook != 'toplevel_page_maika-genius' 
          && $hook != 'maika-genius_page_maika-genius-settings' 
          && $hook != 'maika-genius_page_maika-genius-shop-structure' 
          && $hook != 'maika-genius_page_maika-genius-shop-structure-editor' 
          && $hook != 'maika-genius_page_maika-genius-product-catalog-builder' 
          && $hook != 'maika-genius_page_maika-genius-product-descriptor' 
          && $hook != 'maika-genius_page_maika-genius-seo-optimizer' 
          && $hook != 'maika-genius_page_maika-genius-livechat' 
          // && $hook != 'maika-genius_page_maika-genius-ai' 
          && $hook != 'maika-genius_page_maika-genius-guide'
        ) {
        return;
    }

    wp_register_script(
      'admin-maika-tabs', 
      plugin_dir_url( __FILE__ ) . 'assets/js/admin-maika-tabs.js', 
      array(), // Dependencies... if any
      time(),   // Version
      false     // Load into footer
    );

    wp_register_script(
      'admin-maika-iframe-resizer', 
      plugin_dir_url( __FILE__ ) . 'assets/js/admin-maika-iframe-resizer.js', 
      array(), // Dependencies... if any
      time(),   // Version
      true     // Load into footer
    );

    wp_register_script(
      'admin-maika-iframe-notification', 
      plugin_dir_url( __FILE__ ) . 'assets/js/admin-maika-iframe-notification.js', 
      array(), // Dependencies... if any
      time(),   // Version
      true     // Load into footer
    );

    wp_register_script(
      'admin-maika-disconnect', 
      plugin_dir_url( __FILE__ ) . 'assets/js/admin-maika-disconnect.js', 
      array(), // Dependencies... if any
      '1.0',   // Version
      true     // Load into footer
    );

    wp_register_script(
      'admin-maika-beginner-01', 
      plugin_dir_url( __FILE__ ) . 'assets/js/admin-maika-beginner-01.js', 
      array(), // Dependencies... if any
      '1.0',   // Version
      true     // Load into footer
    );

    wp_register_script(
      'admin-maika-beginner-02', 
      plugin_dir_url( __FILE__ ) . 'assets/js/admin-maika-beginner-02.js', 
      array(), // Dependencies... if any
      '1.0',   // Version
      true     // Load into footer
    );
 }

 // CSS
 function maika_enqueue_admin_css($hook) {
    // Check slug
    if (  $hook != 'toplevel_page_maika-genius' 
          && $hook != 'maika-genius_page_maika-genius-settings'
          && $hook != 'maika-genius_page_maika-genius-shop-structure' 
          && $hook != 'maika-genius_page_maika-genius-shop-structure-editor' 
          && $hook != 'maika-genius_page_maika-genius-product-catalog-builder' 
          && $hook != 'maika-genius_page_maika-genius-product-descriptor' 
          && $hook != 'maika-genius_page_maika-genius-seo-optimizer' 
          && $hook != 'maika-genius_page_maika-genius-livechat' 
          // && $hook != 'maika-genius_page_maika-genius-ai' 
          && $hook != 'maika-genius_page_maika-genius-guide'
        ) {
        return;
    }

    wp_register_style(
      'admin-maika-css', // Handle cho style
      plugin_dir_url(__FILE__) . 'assets/css/admin-maika.css',
      array(), // Dependencies... if any
      time(), // Version
      'all' // Media cho style
    );

    wp_register_style(
      'admin-maika-mautic', // Handle cho style
      plugin_dir_url(__FILE__) . 'assets/css/admin-maika-mautic.css',
      array(), // Dependencies... if any
      time(), // Version
      'all' // Media cho style
    );

    wp_register_style(
      'admin-maika-tailwind-css', // Handle cho style
      plugin_dir_url(__FILE__) . 'assets/css/admin-maika-tailwind.css',
      array(), // Dependencies... if any
      time(), // Version
      'all' // Media cho style
    );
 }

 add_action('admin_enqueue_scripts', 'maika_enqueue_admin_scripts');
 add_action('admin_enqueue_scripts', 'maika_enqueue_admin_css');


 // ==================== ENQUEUE SCRIPTS ====================
 // Hook add link script in user page
 add_action('wp_enqueue_scripts', 'maika_chatbox_add_script');
 function maika_chatbox_add_script() {
    // add file JavaScript (maika.js) in footer
    wp_enqueue_script('maika-chatbox-js', 'https://hub.askmaika.ai/app/static/maika.js', array(), time(), true);
 }

 // ADD SCRIPT Maika Genius CHAT BOX
 // add custom script
 add_action('wp_footer', 'maika_chatbox_config');
 function maika_chatbox_config(){
    $maika_ai_cid = get_option("maika_ai_cid");

    if($maika_ai_cid){
      // Localize script to pass PHP variables to JavaScript
      wp_localize_script('maika-engine', 'maikaEngineData', array(
        'cid' => esc_js($maika_ai_cid),
      ));
      // Load into script
      wp_enqueue_script('maika-engine');
    }
 }

 // ==================== REGISTER JavaScript wordpress page ====================
 function maika_enqueue_maika_engine_script() {
  // Register the script
  wp_register_script(
      'maika-engine',
      plugin_dir_url( __FILE__ ) . 'assets/js/maika-engine.js', // Adjust the path if needed
      array(), // Dependencies
      time(), // Version (null will use the current version)
      true // Load in the footer
  );
 }
 add_action('wp_enqueue_scripts', 'maika_enqueue_maika_engine_script');

 // ==================== UTILS FUNCTION ====================
 function maika_check_rfa(){
    // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    return isset($_GET['rfa']) ? sanitize_text_field(wp_unslash($_GET['rfa'])) : "";
 }

 function maika_show_image_from_plugin($filename, $alt = '', $class = '') {
    // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage
    echo '<img src="' . esc_url(plugins_url('assets/images/' . $filename, __FILE__)) . '" alt="' . esc_attr($alt) . '" class="' . esc_attr($class) . '" loading="lazy">';
 }

 function maika_show_iframe_for_rfa(){
    $domain_web = maika_getlink_domain_web();

    echo "
      <iframe id='MAIKA_IFRAME_rfa' src='https://hub.askmaika.ai/app/permission?type=storage&step=ask&display_mode=embed&wp_domain=".esc_url($domain_web)."' style='border: none; height: auto; width: 100%; min-height: 800px;'></iframe>
    ";
 }

 function maika_show_popup_if_not_integration($feature_name = 'this features'){
  ?>
  <!-- Overlay -->
  <div id="maika-overlay" style="position: fixed; z-index: 9998; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4); backdrop-filter: blur(2px);"></div>

  <!-- Popup -->
  <div id="maika-popup" style="position: fixed; z-index: 9999; top: 50%; left: 50%; transform: translate(-50%, -50%); max-width: 90%; width: 400px; background: #fff; padding: 1.8rem; border-radius: 12px; box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2); font-family: system-ui, sans-serif;">

    <!-- Btn Close -->
    <!-- <div onclick="document.getElementById('maika-popup').style.display='none';document.getElementById('maika-overlay').style.display='none';"
         style="position: absolute; top: 10px; right: 14px; font-size: 1.25rem; color: #aaa; cursor: pointer; font-weight: bold;">
      ×
    </div> -->

    <!-- Title -->
    <h2 style="font-size: 1.45rem; font-weight: bold; color: #9333ea; margin-bottom: 0.75rem; text-align: center;">
      Connect with Maika Hub
    </h2>

    <!-- Content -->
    <p style="font-size: 1rem; color: #333; text-align: center; margin-bottom: 1.25rem;">
      To unlock <?php echo esc_html($feature_name); ?>, please take a moment to connect your website with <strong>Maika Hub</strong>. It’s quick and easy!
    </p>

    <!-- Redirect button to the Guide Page -->
    <a href="/wp-admin/admin.php?page=maika-genius-guide"
       style="display: inline-block; background-color: #9333ea; color: #fff; text-decoration: none; padding: 0.6rem 1.25rem; border-radius: 6px; outline: none; font-weight: 600; text-align: center; transition: background 0.3s; width: 100%; box-sizing: border-box;">
      View Integration Guide
    </a>
  </div>
  <?php
 }

 function maika_check_application_password_exists($application_name) {
    // get list Application Passwords
    $app_passwords = get_user_meta(get_current_user_id(), '_application_passwords', true);

    // check empty
    if (empty($app_passwords)) {
        return false;
    }

    // loop & check
    foreach ($app_passwords as $app_password) {
        if ($app_password['name'] == $application_name) {
            return true;
        }
    }

    return false;
  }

 function maika_get_application_password_value($application_name) {
    // get list Application Passwords
    $app_passwords = get_user_meta(get_current_user_id(), '_application_passwords', true);

    // check empty
    if (empty($app_passwords)) {
        return false;
    }

    // loop & check
    foreach ($app_passwords as $app_password) {
        if ($app_password['name'] == $application_name) {
            return $app_password['password'];
        }
    }

    return false;
 }

 function maika_create_application_password_for_current_user( $app_name = 'maika' ) {
    if ( ! is_user_logged_in() ) {
        return new WP_Error( 'not_logged_in', 'Bạn chưa đăng nhập' );
    }

    $user_id = get_current_user_id();

    if(maika_check_application_password_exists($app_name) != false){
      maika_delete_application_password_exists($app_name); // Delete Old AP
    }

    // Create Application Password
    $new_password = WP_Application_Passwords::create_new_application_password(
        $user_id,
        array( 'name' => $app_name )
    );

    if ( is_wp_error( $new_password ) ) {
        return $new_password;
    }

    update_option("mkg_wp_ap", $new_password[0]);

    return $new_password[0];
 }
 
 function maika_delete_application_password_exists($application_name){
    // Get the current user ID
    $user_id = get_current_user_id();

    // Get the current application passwords for the user
    $application_passwords = get_user_meta( $user_id, '_application_passwords', true);

    // Check if there are any application passwords and loop through them
    if (!empty($application_passwords)) {
        foreach($application_passwords as $index => $app_password){
            if ($app_password['name'] == 'maika'){
                // Remove the application password from the array
                unset($application_passwords[$index]);
            
                // Update the user meta with the new passwords array
                update_user_meta($user_id, '_application_passwords', $application_passwords);
                return true;
            }
        }
    }
 }
 
 function maika_check_woocommerce_api_keys(){
    $maikaWooAPIKey = maika_get_list_woocommerce_api_keys();
    foreach ($maikaWooAPIKey as $key) {
        if($key->user_id == get_current_user_id() && $key->description == "maika"){
            return $key->consumer_secret;
        }
    }
    return false;
 }
 
 function maika_get_list_woocommerce_api_keys() {
    global $wpdb;

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
    $keys = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}woocommerce_api_keys", OBJECT);
    return $keys;
 }

 function maika_create_wc_rest_api_key_for_current_user( $description = 'maika' ) {
    if ( ! is_user_logged_in() ) {
        return new WP_Error( 'not_logged_in', 'Bạn chưa đăng nhập' );
    }
    
    if(maika_check_woocommerce_api_keys() != false){
      maika_delete_woocommerce_api_keys(); // Delete Old Woo Rest API Key
    }

    $user_id = get_current_user_id();
    global $wpdb;

    $consumer_key    = 'ck_' . wc_rand_hash();
    $consumer_secret = 'cs_' . wc_rand_hash();

    $data = array(
        'user_id'         => $user_id,
        'description'     => $description,
        'permissions'     => 'read_write',
        'consumer_key'    => wc_api_hash( $consumer_key ),
        'consumer_secret' => $consumer_secret,
        'truncated_key'   => substr( $consumer_key, -7 ),
    );

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
    $wpdb->insert( $wpdb->prefix . 'woocommerce_api_keys', $data );

    update_option("mkg_wcm_ck", $consumer_key);
    update_option("mkg_wcm_cs", $consumer_secret);

    return array(
        'key_id'          => $wpdb->insert_id,
        'consumer_key'    => $consumer_key,
        'consumer_secret' => $consumer_secret,
    );
 }
 
 function maika_delete_woocommerce_api_keys(){
    global $wpdb;
    // Get the current user ID
    $user_id = get_current_user_id();
  
    // Query to get the API key with name 'maika' for the current user
    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
    $api_key = $wpdb->get_row( 
        $wpdb->prepare(
            "SELECT key_id FROM {$wpdb->prefix}woocommerce_api_keys WHERE user_id = %d AND description = %s", 
            $user_id, 
            'maika'
        )
    );
   
    // If the API key is found, delete it
    if ($api_key) {
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $wpdb->delete( 
            "{$wpdb->prefix}woocommerce_api_keys", 
            array( 'key_id' => $api_key->key_id ), 
            array( '%d' )
        );
        return true;
    }
 }
 
 function maika_call_get_api($url, $api_key='') {
    // Set up the arguments for the request
    $args = [
        'headers' => [
            'Content-Type' => 'application/json' // Set the content type to JSON if necessary
            //'c-secret-key' => $api_key, // If the API requires authorization
        ],
        //'timeout' => 15, // Optional: Set a timeout for the request
    ];
  
    // Make the request
    $response = wp_remote_get($url, $args);
  
    // Check for errors
    if (is_wp_error($response)) {
        return $response->get_error_message(); // Return the error message
    }
  
    // Parse the JSON response
    $responseData = json_decode(wp_remote_retrieve_body($response), true);
  
    return $responseData; // Return the parsed data
 }
 
 function maika_call_post_api($url, $arr_body_data) {
    // Set up the arguments for the request
    $args = [
        'headers' => [
            'Content-Type' => 'application/json', // Set the content type to JSON if necessary
        ],
        'body' => json_encode($arr_body_data),
        //'timeout' => 15, // Optional: Set a timeout for the request
    ];

    // Send POST request
    $response = wp_remote_post($url, $args);

    // Check for errors
    if (is_wp_error($response)) {
        return $response->get_error_message(); // Return the error message
    }

    // Parse the JSON response
    $responseData = json_decode(wp_remote_retrieve_body($response), true);

    return $responseData; // Return the parsed data
 }
 
 function maika_mask_string($string, $visibleChars = 8) {
    $length = strlen($string);

    if ($length <= $visibleChars) {
        return $string;
    }

    $start = substr($string, 0, $visibleChars);
    $end = substr($string, -$visibleChars);

    $maskedString = $start . str_repeat('*', $length - $visibleChars * 2) . $end;

    return $maskedString;
 }
 
 function maika_check_pass_guide_step(){
    //check application password exists and get value
    $maikaApplicationPassword = maika_check_application_password_exists("maika") ? maika_get_application_password_value("maika") : null;
    //Check rest api woocomerce
    $maikaWooAPIKey = maika_check_woocommerce_api_keys(); // WooConsumerSecret
  
    $pass_guide_step = 0;
    $maika_cid = get_option("maika_ai_cid");
    $maika_ai_cid_temp = get_option("maika_ai_cid_temp");
  
    if($maika_cid != false && ($maikaApplicationPassword != null && $maikaWooAPIKey != false)){
      $pass_guide_step = 2;
    }
    else{
      if($maika_ai_cid_temp != false){
        $pass_guide_step = 1;
      }
      // $pass_guide_step = ($maikaApplicationPassword != null && $maikaWooAPIKey != false) ? 1 : 0;
    }
   
    return $pass_guide_step;
 }
 
 function maika_getlink_connect_maikahub(){
    $domain_web = maika_getlink_domain_web();
  
    $userLogin = wp_get_current_user();
    $current_username = $userLogin->user_login;
    $current_email = $userLogin->user_email;
  
    $maika_ssid = get_option("maika_ssid");
    if($maika_ssid == false){
      $maika_ssid = bin2hex(random_bytes(16));
      update_option("maika_ssid", $maika_ssid);
    }
   
    return "https://hub.askmaika.ai/app/auth/?domain=".esc_url($domain_web)."&ssid=".$maika_ssid."&email=".$current_email."&username=".$current_username;
 }

 function maika_getlink_create_account_maikahub(){
    $domain_web = maika_getlink_domain_web();
  
    $userLogin = wp_get_current_user();
    $current_username = $userLogin->user_login;
    $current_email = $userLogin->user_email;
  
    $maika_ssid = get_option("maika_ssid");
    if($maika_ssid == false){
      $maika_ssid = bin2hex(random_bytes(16));
      update_option("maika_ssid", $maika_ssid);
    }
   
    // return "https://hub.askmaika.ai/app/auth/?domain=".esc_url($domain_web)."&ssid=".$maika_ssid."&email=".$current_email."&username=".$current_username;

    $fingerprint = maika_get_instance_string();

    return "https://hub.askmaika.ai/app/auth/?domain=" .esc_url($domain_web). "&ssid=" .$maika_ssid. "&fingerprint=" .$fingerprint;
 }
 
 function maika_getlink_domain_web(){
    return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . (isset($_SERVER['HTTP_HOST']) ? sanitize_text_field(wp_unslash($_SERVER['HTTP_HOST'])) : "null");
 }

 function maika_get_instance_string(){
    $maika_instance_string = get_option("mkg_instance_string");
    if($maika_instance_string != false){
      return $maika_instance_string;
    }

    $domain_web = maika_getlink_domain_web();
    $maika_create_instance_string = maika_normalize_tring($domain_web);
    update_option("mkg_instance_string", $maika_create_instance_string);
    return $maika_create_instance_string;
 }

 function maika_normalize_tring($inputString)
 {
     $normalizedString = mb_strtolower($inputString, 'UTF-8');
 
     $normalizedString = str_replace(
         ['á', 'à', 'ả', 'ạ', 'ã', 'ă', 'ằ', 'ắ', 'ẳ', 'ặ', 'ẵ', 'â', 'ầ', 'ấ', 'ẩ', 'ậ', 'ẫ'],
         ['a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a'],
         $normalizedString
     );
     $normalizedString = str_replace(
         ['é', 'è', 'ẻ', 'ẹ', 'ẽ', 'ê', 'ề', 'ế', 'ể', 'ệ', 'ễ'],
         ['e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e'],
         $normalizedString
     );
     $normalizedString = str_replace(
         ['í', 'ì', 'ỉ', 'ị', 'ĩ'],
         ['i', 'i', 'i', 'i', 'i'],
         $normalizedString
     );
     $normalizedString = str_replace(
         ['ó', 'ò', 'ỏ', 'ọ', 'õ', 'ô', 'ồ', 'ố', 'ổ', 'ộ', 'ỗ', 'ơ', 'ờ', 'ớ', 'ở', 'ợ', 'ỡ'],
         ['o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o'],
         $normalizedString
     );
     $normalizedString = str_replace(
         ['ú', 'ù', 'ủ', 'ụ', 'ũ', 'ư', 'ừ', 'ứ', 'ử', 'ự', 'ữ'],
         ['u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u'],
         $normalizedString
     );
     $normalizedString = str_replace(
         ['ý', 'ỳ', 'ỷ', 'ỵ', 'ỹ'],
         ['y', 'y', 'y', 'y', 'y'],
         $normalizedString
     );
     $normalizedString = str_replace(
         ['đ'],
         ['d'],
         $normalizedString
     );
 
     $normalizedString = preg_replace('/[^a-z0-9]/', '', $normalizedString);
 
     return $normalizedString;
 }

 function maika_guide_process_bar(){
    $maika_rfa = maika_check_rfa();
    $domain_web = maika_getlink_domain_web();
    $linkConnectService = maika_getlink_connect_maikahub();
    $maika_cid = get_option("maika_ai_cid");
    // [Action] Button 'Disconnect - Clear all data'
    if (isset($_POST['reset-and-reconnect']) && check_admin_referer('maika_ai_clear_all_settings_action', 'maika_ai_clear_all_settings_nonce')) {

      if($maika_cid != false){
        // Disconnect website from Hub
        $maika_disconnect_body_data = [
          "cid" => $maika_cid
        ];
        maika_call_post_api(esc_url('https://hub.askmaika.ai/app/api/woo/disconnect_site'), $maika_disconnect_body_data);
  
        delete_option("maika_ai_cid");

        $del_AP = maika_delete_application_password_exists("maika");
        $del_WooAPI = maika_delete_woocommerce_api_keys();
      }

      $maika_cid_temp = get_option("maika_ai_cid_temp");
      if($maika_cid_temp != false){
        delete_option("maika_ai_cid_temp");
      }

      //redirect link
      $rd = esc_url($domain_web)."/wp-admin/admin.php?page=maika-genius-guide";

      wp_localize_script('admin-maika-disconnect', 'adminMaikaEngineData', array(
        'slugMaikaGenius' => esc_url($rd),
      ));
      // Load into script
      wp_enqueue_script('admin-maika-disconnect');
    }

    // Handle generate key & finish interation
    if (isset($_POST['submit_generate_and_send_key']) && check_admin_referer('maika_ai_generate_and_send_key_action', 'maika_ai_generate_and_send_key_nonce')) {
      $maika_wp_ap = maika_create_application_password_for_current_user();
      
      $maika_wcm_ak = maika_create_wc_rest_api_key_for_current_user();
      $maika_wcm_ck = $maika_wcm_ak['consumer_key'];
      $maika_wcm_cs = $maika_wcm_ak['consumer_secret'];
      $maika_ssid = get_option("maika_ssid");
      $current_user = wp_get_current_user();
      $current_username = $current_user->user_login;
      $maika_ai_cid_temp = get_option("maika_ai_cid_temp");
      $maika_credential_body_data = [
        "cid" => $maika_ai_cid_temp,
        "secretKey" => "$maika_ssid",
        "wp_name" => "$current_username",
        "wp_password" => "$maika_wp_ap",
        "woo_name" => "$maika_wcm_ck",
        "woo_password" => "$maika_wcm_cs"
      ];
      $resultCallAPI = maika_call_post_api(esc_url('https://hub.askmaika.ai/app/api/woo/site_credential'), $maika_credential_body_data);
      if($resultCallAPI['status'] == "SUCCESS"){
        update_option("maika_ai_cid", $maika_ai_cid_temp);

        // Delete WP_AP and WCM_AK temporarily stored in DB
        delete_option("mkg_wp_ap");
        delete_option("mkg_wcm_ck");
        delete_option("mkg_wcm_cs");
      }
      else{
        echo "<h2 style='margin-top: 20px; color: red; font-size: 1.25rem;'>An error occurred please try again or contact <a style='color: blue;' target='_blank' href='https://www.facebook.com/hybrid.maika'>https://www.facebook.com/hybrid.maika</a> for support!</h2>";
      }
      // echo "---<br>";
      // echo ($resultCallAPI['status']);
      // echo "<br>---";
    }

    $pass_guide_step = maika_check_pass_guide_step();
    ?>

    <div <?php echo $maika_rfa != "true" ? "style='z-index: 9999; position: relative;'" : "style='display: none;'"; ?> class="mt-4 mr-[20px] mx-auto px-8 py-6 text-base text-gray-800 rounded-lg bg-purple-200" role="alert">
    <h2 class="mt-4 mb-4 text-2xl lg:text-3xl font-semibold flex items-center justify-left">Get connected with platform Maika <svg id="whatMaikaGenius" class="ml-2" style="height: 25px; width: 25px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="rgb(147 51 234 / var(--tw-bg-opacity))" d="M464 256A208 208 0 1 0 48 256a208 208 0 1 0 416 0zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm169.8-90.7c7.9-22.3 29.1-37.3 52.8-37.3l58.3 0c34.9 0 63.1 28.3 63.1 63.1c0 22.6-12.1 43.5-31.7 54.8L280 264.4c-.2 13-10.9 23.6-24 23.6c-13.3 0-24-10.7-24-24l0-13.5c0-8.6 4.6-16.5 12.1-20.8l44.3-25.4c4.7-2.7 7.6-7.7 7.6-13.1c0-8.4-6.8-15.1-15.1-15.1l-58.3 0c-3.4 0-6.4 2.1-7.5 5.3l-.4 1.2c-4.4 12.5-18.2 19-30.6 14.6s-19-18.2-14.6-30.6l.4-1.2zM224 352a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z"/></svg></h2>

      <div id='contentWhatMaikaGenius' style='display: none;' class='mb-4 border-l-[3px] rounded border-purple-700 pl-3'>
        <p class='text-base'><span class="font-medium">Maika Genius </span>leverages the power of cutting-edge Al technology to supercharge
          your shop, but that power comes with a demand for significant computational resources. To ensure seamless
          performance and prevent strain on your server, Maika Genius operates through a cloud-based platform. Simply create
          a free Maika account, connect your website to your account, and let Maika Genius do the heavy lifting! For
          detailed instructions on setting up your account and connecting your website, please refer to our comprehensive
          user guide.</p>
      </div>

      <ol class="mt-4 mb-2 flex items-center w-full text-sm text-gray-500 font-medium sm:text-base">
        <li
          class="flex md:w-full items-center text-purple-600 sm:after:content-[''] after:w-full after:h-1 after:border-b <?php echo ($pass_guide_step == 1 || $pass_guide_step == 2) ? "after:border-purple-400" : "after:border-gray-200"; ?> after:border-1 after:hidden sm:after:inline-block after:mx-4 xl:after:mx-6 ">
          <div class="flex items-center whitespace-nowrap after:content-['/'] sm:after:hidden after:mx-2 ">
            <span
              class="w-6 h-6 bg-purple-600 border border-purple-200 rounded-full flex justify-center items-center mr-3 text-sm text-white lg:w-10 lg:h-10"><?php echo ($pass_guide_step == 1 || $pass_guide_step == 2) ? "✓" : "1"; ?></span>
            Connect Maika Hub
          </div>
        </li>
        <li
          class="flex md:w-full items-center <?php echo ($pass_guide_step == 1 || $pass_guide_step == 2) ? "text-purple-600" : "text-gray-600"; ?> sm:after:content-[''] after:w-full after:h-1 after:border-b <?php echo ($pass_guide_step == 2) ? "after:border-purple-400" : "after:border-gray-200"; ?> after:border-1 after:hidden sm:after:inline-block after:mx-4 xl:after:mx-6 ">
          <div class="flex items-center whitespace-nowrap after:content-['/'] sm:after:hidden after:mx-2 ">
            <span
              class="w-6 h-6 <?php echo ($pass_guide_step == 1 || $pass_guide_step == 2) ? "bg-purple-600 border-purple-200 text-sm text-white" : "bg-gray-100 border-gray-200"; ?> border rounded-full flex justify-center items-center mr-3 lg:w-10 lg:h-10"><?php echo ($pass_guide_step == 2) ? "✓" : "2"; ?></span>
            Grant Access Permission
          </div>
        </li>
        <li
          class="flex md:w-full items-center <?php echo ($pass_guide_step == 2) ? "text-purple-600" : "text-gray-600"; ?>">
          <div class="flex items-center">
            <span
              class="w-6 h-6 <?php echo ($pass_guide_step == 2) ? "bg-purple-600 border-purple-200 text-sm text-white" : "bg-gray-100 border-gray-200"; ?> border rounded-full flex justify-center items-center mr-3 lg:w-10 lg:h-10">🚀</span>
            Finish
          </div>
        </li>
      </ol>

      <!-- ######################## -->
      <div style="margin: 20px auto 0; font-family: system-ui, sans-serif;">
        <!-- Step 1 -->
        <div id="step-1" style="<?php echo $pass_guide_step === 0 ? "display: block;" : "display: none;"; ?> background: #f9f9f9; padding: 1.5rem; border-radius: 10px; margin-bottom: 2rem;">
          <h2 style="color: #9333ea; margin-bottom: 0.75rem; font-size: 1.2rem; font-weight: 500;">Step 1: Connect Maika Hub</h2>
          <h3 style="font-size: 1.1rem; color: #444; font-weight: 500; margin-bottom: .5rem;">Why Maika Genius Needs Maika Hub</h3>
          <p style="font-size: 1rem; color: #444; margin-bottom: .5rem;">
            Maika Genius works hand-in-hand with <strong style="font-weight: 500;">Maika Hub</strong> and <strong style="font-weight: 500;">Maika CRM</strong> to deliver AI-driven content, recommendations, and marketing ideas for your WooCommerce store.
          </p>
          <p style="font-size: 1rem; color: #444; margin-bottom: .5rem;">To get started, you’ll need a <strong style="font-weight: 500;">free Maika Hub account</strong> — it takes less than a minute.</p>
          <p style="font-size: 1rem; color: #444; margin-bottom: .75rem;"><strong style="font-weight: 500;">➡ Click the button below to <em>Connect Maika Hub</em>.</strong></p>

          <a style="outline: none;" href="<?php echo esc_url(maika_getlink_create_account_maikahub()); ?>" class="service-button pulse">
            Connect Maika Hub
          </a>

          <hr style="margin-top: 16px; margin-bottom: 16px;">

          <details>
            <summary class="cursor-pointer">
              <h3 style="text-decoration: 2px underline; display: inline-block; font-size: 1.1rem; color: #444; font-weight: 500; margin-bottom: .5rem;">What happens after connecting to Maika Hub?</h3>
            </summary>
            <ol style="list-style: decimal; margin-left: 24px;">
              <li><strong style="font-weight: 500;">Integration</strong> – Your store securely connects with Maika Hub, sharing product details (categories, attributes, descriptions) so Maika understands your offerings.</li>
              <li><strong style="font-weight: 500;">AI personalization</strong> – Maika generates tailored content and recommendations for your store, improving over time from your products and customer interactions.</li>
              <li><strong style="font-weight: 500;">Optional live chat</strong> – Enable AI-powered chat on your site, managed from your Maika Hub dashboard.</li>
            </ol>
            <h4 style="font-size: 1.05rem; color: #444; font-weight: 500; margin-top: 20px; margin-bottom: .5rem;">Privacy you can trust</h4>
            <p style="font-size: 1rem; color: #444; margin-bottom: .5rem;">Your data is only used to enhance AI features for your store. If live chat is enabled, conversations are processed securely. See our <a style="color: blue;" href="https://www.askmaika.ai/terms/" target="_blank">Terms of Use</a> and <a style="color: blue;" href="https://www.askmaika.ai/privacy-policy/" target="_blank">Privacy Policy</a> for details.</p>
          </details>
        </div>

        <!-- Step 2 -->
        <div id="step-2" style="<?php echo $pass_guide_step === 1 ? "display: block;" : "display: none;"; ?> background: #f9f9f9; padding: 1.5rem; border-radius: 10px;">
          <h2 style="color: #9333ea; margin-bottom: 0.75rem; font-size: 1.2rem; font-weight: 500;">Step 2: Grant Access Permission</h2>
          <h3 style="font-size: 1.1rem; color: #444; font-weight: 500; margin-bottom: .5rem;">The Easy Way – One Click Setup 🚀</h3>
          <p style="font-size: 1rem; color: #444; margin-bottom: .5rem;">
            Connecting your WordPress and WooCommerce store to Maika Hub has never been easier.
          </p>
          <p style="font-size: 1rem; color: #444; margin-bottom: .5rem;"><strong style="font-weight: 500;">➡ Just click the button below, and we’ll take care of everything for you.</strong></p>

          <form method="POST">
            <?php wp_nonce_field('maika_ai_generate_and_send_key_action', 'maika_ai_generate_and_send_key_nonce'); ?>
            <button class="service-button pulse" type="submit" name="submit_generate_and_send_key">Grant Access Permission</button>
          </form>

          <hr style="margin-top: 16px; margin-bottom: 16px;">

          <details>
            <summary class="cursor-pointer">
              <h3 style="text-decoration: 2px underline; display: inline-block; font-size: 1.1rem; color: #444; font-weight: 500; margin-bottom: .5rem;">What happens when I press "Grant Access Permission"?</h3>
            </summary>
            
            <ol style="list-style: disc; margin-left: 24px;">
              <li><strong style="font-weight: 500;">Automatically create</strong> both your <strong style="font-weight: 500;">Application Password</strong> (WordPress) and <strong style="font-weight: 500;">WooCommerce REST API Key</strong>.</li>
              <li><strong style="font-weight: 500;">Securely send</strong> them to <strong style="font-weight: 500;">Maika Hub</strong>.</li>
              <li>Instantly <strong style="font-weight: 500;">complete the integration</strong> so you can start using all features right away.</li>
            </ol>

            <hr style="margin-top: 16px; margin-bottom: 16px;">

            <h4 style="font-size: 1.1rem; color: #444; font-weight: 500; margin-top: 10px; margin-bottom: .5rem;">What Are Application Password & WooCommerce REST API Key?</h4>
            <h5 style="font-size: 1.05rem; color: #444; font-weight: 500; margin-top: 10px; margin-bottom: .5rem;">🔑 Application Password</h5>
            <p style="font-size: 1rem; color: #444; margin-bottom: .5rem;">An <strong style="font-weight: 500;">Application Password</strong> is a special login key created in your WordPress site. It allows <strong style="font-weight: 500;">Maika Hub</strong> to connect securely <strong style="font-weight: 500;">without using your main WordPress password</strong>.</p>
            <ol style="list-style: disc; margin-left: 24px;">
              <li>Keeps your account safe.</li>
              <li>Can be <strong style="font-weight: 500;">revoked anytime</strong> if you no longer need the connection.</li>
              <li>Enables our app to interact with your site smoothly.</li>
            </ol>

            <h5 style="font-size: 1.05rem; color: #444; font-weight: 500; margin-top: 20px; margin-bottom: .5rem;">🛒 WooCommerce REST API Key</h5>
            <p style="font-size: 1rem; color: #444; margin-bottom: .5rem;">The <strong style="font-weight: 500;">WooCommerce REST API Key</strong> is a pair of codes (<strong style="font-weight: 500;">Consumer Key</strong> and <strong style="font-weight: 500;">Consumer Secret</strong>) that:</p>
            <ol style="list-style: disc; margin-left: 24px;">
              <li>Allow <strong style="font-weight: 500;">Maika Hub</strong> to connect to your WooCommerce store securely.</li>
              <li>Let you manage <strong style="font-weight: 500;">orders, products, and customers</strong> directly through the integration.</li>
              <li>Can be <strong style="font-weight: 500;">revoked anytime</strong> for maximum control and safety.</li>
            </ol>
            <p style="font-size: 1rem; color: #444; margin-bottom: .5rem;">With both keys, your store and <strong style="font-weight: 500;">Maika Hub</strong> can communicate securely, unlocking the full power of our features.</p>
          </details>
        </div>
      </div>

      <!-- Step 3 -->
      <div id="step-3" style="<?php echo $pass_guide_step === 2 ? "display: block;" : "display: none;"; ?> background: #f9f9f9; padding: 1.5rem; border-radius: 10px; margin-bottom: 2rem;">
        <h2 style="color: #9333ea; margin-bottom: 0.75rem; font-size: 1.2rem; font-weight: 500;">🎉 Integration Complete!</h2>
        <p style="font-size: 1rem; color: #444; margin-bottom: .5rem;">Your store is now <strong style="font-weight: 500;">successfully connected</strong> to <strong style="font-weight: 500;">Maika Hub</strong>.</p>
        <p style="font-size: 1rem; color: #444; margin-bottom: .5rem;">Everything’s ready — you can start enjoying the full power of <strong style="font-weight: 500;">Maika Genius</strong> right away! 🚀</p>

        <hr style="margin-top: 16px; margin-bottom: 16px;">

        <h3 style="font-size: 1.1rem; color: #444; font-weight: 500; margin-top: 10px; margin-bottom: .5rem;">✨ What’s Next?</h3>
        <p style="font-size: 1rem; color: #444; margin-bottom: .5rem;">Unlock a new level of eCommerce experience with Maika Genius</p>
        

        <div class="maika-feature-grid-container">
          <div class="maika-feature-card">
              <h3>Brand Personality</h3>
              <a href="https://hub.askmaika.ai/app/site/<?php echo $maika_cid; ?>">Launch →</a>
              
          </div>

          <div class="maika-feature-card">
              <h3>Shop Structure</h3>
              <a href="https://hub.askmaika.ai/app/site/<?php echo $maika_cid; ?>/woo_shop_structure">Launch →</a>
          </div>

          <div class="maika-feature-card">
              <h3>Catalog Builder</h3>
              <a href="https://hub.askmaika.ai/app/site/<?php echo $maika_cid; ?>/woo_prod_catalog">Launch →</a>
          </div>

          <div class="maika-feature-card">
              <h3>Product Descriptor</h3>
              <a href="https://hub.askmaika.ai/app/site/<?php echo $maika_cid; ?>/woo_prod_descriptor">Launch →</a>
          </div>

          <div class="maika-feature-card">
              <h3>Livechat</h3>
              <a href="https://hub.askmaika.ai/app/site/<?php echo $maika_cid; ?>?mode=chat">Launch →</a>
          </div>
        </div>
      </div>
      <!-- ######################## -->
      <!-- Reset & Re-connect [button] -->
      <div id="reset-and-reconnect" style="<?php echo $pass_guide_step >= 1 ? "display: block;" : "display: none;"; ?> margin-top: 20px; text-align: end;">
        <form method="POST">
            <?php wp_nonce_field('maika_ai_clear_all_settings_action', 'maika_ai_clear_all_settings_nonce'); ?>
            <button class="maika-reset-button" type="submit" name="reset-and-reconnect">Reset & Re-Connect</button>
        </form>
      </div>
    </div>

    <?php
    // Load JS file show guide for Beginner
    // guide to creating your keys
    // wp_enqueue_script('admin-maika-beginner-01');  
    // guide: What Maika Genius
    wp_enqueue_script('admin-maika-beginner-02');
 }

 // ==================== ADD API ====================
 // Hook to register the custom REST API route
 add_action('rest_api_init', 'maika_register_api_routes');
 
 // Auth
 if(file_exists(plugin_dir_path(__FILE__).'auth_api.php')){
    require_once plugin_dir_path(__FILE__).'auth_api.php';
 }

 //Register api
 function maika_register_api_routes() {
    if(file_exists(plugin_dir_path(__FILE__).'includes/api/wp/register_api.php')){
        require_once plugin_dir_path(__FILE__).'includes/api/wp/register_api.php';
    }
 }

 // Models
 if(file_exists(plugin_dir_path(__FILE__).'includes/api/wp/models.php')){
    require_once plugin_dir_path(__FILE__).'includes/api/wp/models.php';
 }
 
 // Routes
 if(file_exists(plugin_dir_path(__FILE__).'includes/api/wp/routes.php')){
    require_once plugin_dir_path(__FILE__).'includes/api/wp/routes.php';
 }

 // Cart Session
 if(file_exists(plugin_dir_path(__FILE__).'includes/api/woo/cart_session.php')){
    require_once plugin_dir_path(__FILE__).'includes/api/woo/cart_session.php';
 }