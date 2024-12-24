<?php
/**
 * Plugin Name: Maika Genius
 * Plugin URI:  https://www.askmaika.ai/maika-genius/
 * Description: Tired of spending hours writing product descriptions and optimizing your website? Maika Genius is the Al-powered solution that empowers you to create engaging content, boost SEO, and drive sales, all with the power of cutting-edge Generative Al.
 * Version:     1.0.3
 * Author:      tomaskmaika
 * Author URI:  https://www.askmaika.ai
 * Text Domain: maika-genius
 * License:     GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * 
 */
 
 // Protect the file from direct access
 if (!defined('ABSPATH')) {
     exit;
 }
 
 // Hook add link 'Settings' page plugin
 add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'maika_add_settings_link');
 function maika_add_settings_link($links) {
     // create link 'Settings'
     $settings_link = '<a href="options-general.php?page=maika-genius">' . __('Settings', 'maika-genius') . '</a>';
     
     // add link 'Settings' before the button 'Deactivate'
     array_unshift($links, $settings_link);
     
     return $links;
 }
 
 add_action("admin_menu", "maika_show_setting_page");
 function maika_show_setting_page(){
     add_menu_page(
         "Maika Genius",
         "Maika Genius",
         "manage_options",
         "maika-genius",
         "maika_setting_page",
         "dashicons-welcome-learn-more",
         8);
        }
        
 function maika_setting_page(){
    // Submit settings
    if (isset($_POST['submit'])){
      // Verify nonce
      if (!isset($_POST['maika_ai_nonce_field']) || !check_admin_referer('maika_ai_settings_nonce', 'maika_ai_nonce_field')) {
        wp_die('An error occurred, take it back!');
      }

      if(isset($_POST['favcolor']) && trim(sanitize_text_field(wp_unslash($_POST['favcolor']))) != ""){
          update_option("maika_ai_favcolor", sanitize_text_field(wp_unslash($_POST['favcolor'])));
      }
      if(isset($_POST['title']) && trim(sanitize_text_field(wp_unslash($_POST['title']))) != ""){
          update_option("maika_ai_title", sanitize_text_field(wp_unslash($_POST['title'])));
      }
    }

    // get value
    $maika_user_id = get_current_user_id();
    $domain_web = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . (isset($_SERVER['HTTP_HOST']) ? sanitize_text_field(wp_unslash($_SERVER['HTTP_HOST'])) : "null");

    // current tab
    $currentTab = isset($_GET['tab']) ? sanitize_text_field(wp_unslash($_GET['tab'])) : "";

    // check rfa
    $maika_rfa = isset($_GET['rfa']) ? sanitize_text_field(wp_unslash($_GET['rfa'])) : "";

    //check application password exists and get value
    $maikaApplicationPassword = maika_check_application_password_exists("maika") ? maika_get_application_password_value("maika") : null;

    //Check rest api woocomerce
    $maikaWooAPIKey = maika_check_woocommerce_api_keys(); // WooConsumerSecret

    $pass_guide_step = 0;
    
    // get value attr
    //$maika_secretKey = get_option("maika_ai_secretKey");
    $maika_favcolor = get_option("maika_ai_favcolor");
    $maika_title = get_option("maika_ai_title");
    $maika_cid = get_option("maika_ai_cid");

    //$maika_connected_maikahub = false;
    
    // [Handle] Disconnect - Clear all data
    if (isset($_POST['clearAll'])){
      // Disconnect website from Hub
      //"secretKey" => $maika_secretKey
      $maika_disconnect_body_data = [
        "cid" => $maika_cid
      ];
      maika_call_post_api(esc_url('https://hub.askmaika.ai/app/api/woo/disconnect_site'), $maika_disconnect_body_data);

      //delete_option("maika_ai_secretKey");
      delete_option("maika_ai_favcolor");
      delete_option("maika_ai_title");
      delete_option("maika_ai_cid");
      delete_option("maika_ssid");
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

    // Maika check connect Maika Hub
    if($maika_cid != false){ //$maika_secretKey != false && 
      //$url_api_check_connect_maika_hub = 'https://hub.askmaika.ai/app/api/woo/connect_status/';
      //$check_connect_maika_hub = maika_call_get_api(esc_url($url_api_check_connect_maika_hub.$maika_cid)); //, $maika_secretKey
      // print_r($check_connect_maika_hub['data']);
      //  && $check_connect_maika_hub['data']['wordpressAPI'] == 'connected' && $check_connect_maika_hub['data']['wooAPI'] == 'connected'
      // if(($check_connect_maika_hub['data']['cidBinding'] ?? null) == 'connected'){
      //   $maika_connected_maikahub = true;
      //   $pass_guide_step = 2;
      // }
      // else{
      //   $pass_guide_step = ($maikaApplicationPassword != null && $maikaWooAPIKey != false) ? 1 : 0;
      // }
      $pass_guide_step = ($maikaApplicationPassword != null && $maikaWooAPIKey != false) ? 2 : 0;
    }
    else{
      $pass_guide_step = ($maikaApplicationPassword != null && $maikaWooAPIKey != false) ? 1 : 0;
    }

    //$pass_guide_step = ($maika_secretKey && $maika_cid && $pass_guide_step == 1) ? 2 : $pass_guide_step;

    $userLogin = wp_get_current_user();
    $current_username = $userLogin->user_login;
    $current_email = $userLogin->user_email;
    $maika_ssid = get_option("maika_ssid");
    if($maika_ssid == false){
      $maika_ssid = bin2hex(random_bytes(16));
      update_option("maika_ssid", $maika_ssid);
    }

    $linkConnectService = "https://hub.askmaika.ai/app/auth/?domain=".esc_url($domain_web)."&ssid=".$maika_ssid."&email=".$current_email."&username=".$current_username;
    
    ?>

<!-- maika_rfa -->
<?php
  if($maika_rfa == "true"){
    echo "
      <iframe id='MAIKA_IFRAME_rfa' src='https://hub.askmaika.ai/app/permission?type=storage&step=ask&display_mode=embed&wp_domain=".esc_url($domain_web)."' style='border: none; height: auto; width: 100%; min-height: 800px;'></iframe>
    ";
  }

  wp_enqueue_script('admin-maika-iframe-resizer');

?>

<!-- GUIDE -->
<div <?php echo $maika_rfa != "true" ? "" : "style='display: none'"; ?> class="mt-8 mr-[20px] mx-auto px-8 py-6 text-base text-gray-800 rounded-lg bg-purple-200" role="alert">
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
        Create your keys <svg id="createYourKeys" class="ml-2" style="height: 20px; width: 20px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M464 256A208 208 0 1 0 48 256a208 208 0 1 0 416 0zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm169.8-90.7c7.9-22.3 29.1-37.3 52.8-37.3l58.3 0c34.9 0 63.1 28.3 63.1 63.1c0 22.6-12.1 43.5-31.7 54.8L280 264.4c-.2 13-10.9 23.6-24 23.6c-13.3 0-24-10.7-24-24l0-13.5c0-8.6 4.6-16.5 12.1-20.8l44.3-25.4c4.7-2.7 7.6-7.7 7.6-13.1c0-8.4-6.8-15.1-15.1-15.1l-58.3 0c-3.4 0-6.4 2.1-7.5 5.3l-.4 1.2c-4.4 12.5-18.2 19-30.6 14.6s-19-18.2-14.6-30.6l.4-1.2zM224 352a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z"/></svg>
      </div>
    </li>
    <li
      class="flex md:w-full items-center <?php echo ($pass_guide_step == 1 || $pass_guide_step == 2) ? "text-purple-600" : "text-gray-600"; ?> sm:after:content-[''] after:w-full after:h-1 after:border-b <?php echo ($pass_guide_step == 2) ? "after:border-purple-400" : "after:border-gray-200"; ?> after:border-1 after:hidden sm:after:inline-block after:mx-4 xl:after:mx-6 ">
      <div class="flex items-center whitespace-nowrap after:content-['/'] sm:after:hidden after:mx-2 ">
        <span
          class="w-6 h-6 <?php echo ($pass_guide_step == 1 || $pass_guide_step == 2) ? "bg-purple-600 border-purple-200 text-sm text-white" : "bg-gray-100 border-gray-200"; ?> border rounded-full flex justify-center items-center mr-3 lg:w-10 lg:h-10"><?php echo ($pass_guide_step == 2) ? "✓" : "2"; ?></span>
        Connect to Maika
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


  <?php
     if($pass_guide_step == 0){
       echo "
       <div id='contentCreateYourKeys' style='display: none;' class='border-l-[3px] rounded border-purple-700 pl-3'>
         <h3 class='text-lg font-semibold'>Introduction to Application Passwords and WooCommerce REST API Keys</h3>
         <ul class='list-disc ml-6 mt-2'>
           <li class='text-base'><span class='font-medium'>Application Password:</span> To fully utilize the amazing features of our application, you'll need to create an Application Password in WordPress. This special password allows our app to connect to your WordPress site securely, without using your main account password. This not only helps protect your account but also ensures that all features work seamlessly. Once created, simply copy and paste this password into our app to complete the connection. You can revoke this password at any time if you no longer want the app to have access.</li>
           <li class='text-base'><span class='font-medium'>WooCommerce REST API Key:</span> In addition, to experience all the fantastic features we offer, you'll also need to create a WooCommerce REST API Key. This unique code enables our application to connect securely to your online store without requiring your main account password. Creating an API Key is straightforward and will make managing orders, products, and customers much easier. Just follow the instructions on the WooCommerce settings page, and you'll receive two codes: <span class='font-medium'>Consumer Key</span> and <span class='font-medium'>Consumer Secret</span>. Copy and paste these codes into our application to activate the features. And rest assured, you can revoke the API Key at any time if you wish to stop the app's access to your store, keeping your account and data safe.</li>
         </ul>
         <h3 class='mt-4 text-lg font-semibold'>How to generate Application Passwords and WooCommerce REST API Keys</h3>
         <p class='text-base'>In just a few simple steps, you can create Application Passwords and WooCommerce REST API Keys for your WordPress site. Our visual guide will walk you through the process quickly, ensuring that you can get started without any hassle:</p>
         <ul class='list-disc ml-6 mt-2'>
           <li class='text-base'><a class='underline text-blue-700' href='".esc_url($domain_web)."/wp-admin/admin.php?page=maika-genius&tab=guide#application-passwords'>Guide to Creating Application Passwords for Your WordPress Site</a></li>
           <li class='text-base'><a class='underline text-blue-700' href='".esc_url($domain_web)."/wp-admin/admin.php?page=maika-genius&tab=guide#woo-rest-api'>Guide to Creating API key for WooCommerce Your Shop</a></li>
         </ul>
       </div>
       ";
     }
     if($pass_guide_step == 1){
       echo "
       <div id='contentCreateYourKeys' style='display: none;' class='border-l-[3px] rounded border-purple-700 pl-3'>
         <h3 class='text-lg font-semibold'>Introduction to Application Passwords and WooCommerce REST API Keys</h3>
         <ul class='list-disc ml-6 mt-2'>
           <li class='text-base'><span class='font-medium'>Application Password:</span> To fully utilize the amazing features of our application, you'll need to create an Application Password in WordPress. This special password allows our app to connect to your WordPress site securely, without using your main account password. This not only helps protect your account but also ensures that all features work seamlessly. Once created, simply copy and paste this password into our app to complete the connection. You can revoke this password at any time if you no longer want the app to have access.</li>
           <li class='text-base'><span class='font-medium'>WooCommerce REST API Key:</span> In addition, to experience all the fantastic features we offer, you'll also need to create a WooCommerce REST API Key. This unique code enables our application to connect securely to your online store without requiring your main account password. Creating an API Key is straightforward and will make managing orders, products, and customers much easier. Just follow the instructions on the WooCommerce settings page, and you'll receive two codes: <span class='font-medium'>Consumer Key</span> and <span class='font-medium'>Consumer Secret</span>. Copy and paste these codes into our application to activate the features. And rest assured, you can revoke the API Key at any time if you wish to stop the app's access to your store, keeping your account and data safe.</li>
         </ul>
         <h3 class='mt-4 text-lg font-semibold'>How to generate Application Passwords and WooCommerce REST API Keys</h3>
         <p class='text-base'>In just a few simple steps, you can create Application Passwords and WooCommerce REST API Keys for your WordPress site. Our visual guide will walk you through the process quickly, ensuring that you can get started without any hassle:</p>
         <ul class='list-disc ml-6 mt-2'>
           <li class='text-base'><a class='underline text-blue-700' href='".esc_url($domain_web)."/wp-admin/admin.php?page=maika-genius&tab=guide#application-passwords'>Guide to Creating Application Passwords for Your WordPress Site</a></li>
           <li class='text-base'><a class='underline text-blue-700' href='".esc_url($domain_web)."/wp-admin/admin.php?page=maika-genius&tab=guide#woo-rest-api'>Guide to Creating API key for WooCommerce Your Shop</a></li>
         </ul>
       </div>
       ";

       echo "
       <div class='mt-4 flex items-center'>
         <h4 class='font-medium text-base'>Click the button next to connect to Maika</h4>
         <a href='".esc_url($linkConnectService)."' ><button class='ml-4 service-button pulse'>Connect your WordPress with Maika</button></a>
       </div>
     ";
     }
 
     if($pass_guide_step == 2){
      echo "
      <div id='contentCreateYourKeys' style='display: none;' class='border-l-[3px] rounded border-purple-700 pl-3'>
        <h3 class='text-lg font-semibold'>Introduction to Application Passwords and WooCommerce REST API Keys</h3>
        <ul class='list-disc ml-6 mt-2'>
          <li class='text-base'><span class='font-medium'>Application Password:</span> To fully utilize the amazing features of our application, you'll need to create an Application Password in WordPress. This special password allows our app to connect to your WordPress site securely, without using your main account password. This not only helps protect your account but also ensures that all features work seamlessly. Once created, simply copy and paste this password into our app to complete the connection. You can revoke this password at any time if you no longer want the app to have access.</li>
          <li class='text-base'><span class='font-medium'>WooCommerce REST API Key:</span> In addition, to experience all the fantastic features we offer, you'll also need to create a WooCommerce REST API Key. This unique code enables our application to connect securely to your online store without requiring your main account password. Creating an API Key is straightforward and will make managing orders, products, and customers much easier. Just follow the instructions on the WooCommerce settings page, and you'll receive two codes: <span class='font-medium'>Consumer Key</span> and <span class='font-medium'>Consumer Secret</span>. Copy and paste these codes into our application to activate the features. And rest assured, you can revoke the API Key at any time if you wish to stop the app's access to your store, keeping your account and data safe.</li>
        </ul>
        <h3 class='mt-4 text-lg font-semibold'>How to generate Application Passwords and WooCommerce REST API Keys</h3>
        <p class='text-base'>In just a few simple steps, you can create Application Passwords and WooCommerce REST API Keys for your WordPress site. Our visual guide will walk you through the process quickly, ensuring that you can get started without any hassle:</p>
        <ul class='list-disc ml-6 mt-2'>
          <li class='text-base'><a class='underline text-blue-700' href='".esc_url($domain_web)."/wp-admin/admin.php?page=maika-genius&tab=guide#application-passwords'>Guide to Creating Application Passwords for Your WordPress Site</a></li>
          <li class='text-base'><a class='underline text-blue-700' href='".esc_url($domain_web)."/wp-admin/admin.php?page=maika-genius&tab=guide#woo-rest-api'>Guide to Creating API key for WooCommerce Your Shop</a></li>
        </ul>
      </div>
      ";
     }
   ?>

</div>
<!-- Show guide for Beginner -->
<?php
  // Load JS file
  // guide to creating your keys
  wp_enqueue_script('admin-maika-beginner-01');  
  // guide: What Maika Genius
  wp_enqueue_script('admin-maika-beginner-02');

?>
<!-- END SCOPE: Show guide for Beginner -->

<div class="maika-tab-container" <?php echo $maika_rfa != "true" ? "" : "style='display: none'"; ?>>
  <div class="maika-tabs" data-tabs-cid="<?php echo esc_html($maika_cid); ?>" data-tabs-domainWeb="<?php echo esc_html($domain_web); ?>">
    <button class="maika-tab <?php echo $currentTab == "home" || $currentTab == "" ? "maika-active" : ""; ?>"
      data-tab="home">Home</button>
    <button class="maika-tab <?php echo $currentTab == "guide" ? "maika-active" : ""; ?>"
      data-tab="guide">Guide</button>
    <button class="maika-tab <?php echo $currentTab == "settings" ? "maika-active" : ""; ?>" data-tab="settings"
      <?php echo $maika_cid ? "" : "style='display: none;'"; ?>>Settings</button>
    <button class="maika-tab <?php echo $currentTab == "product-descriptor" ? "maika-active" : ""; ?>"
      data-tab="product-descriptor">Product Descriptor</button>
    <button class="maika-tab <?php echo $currentTab == "product-catalog-builder" ? "maika-active" : ""; ?>"
    data-tab="product-catalog-builder">Product Catalog Builder</button>
    <button class="maika-tab <?php echo $currentTab == "seo-optimizer" ? "maika-active" : ""; ?>"
      data-tab="seo-optimizer">SEO Optimizer</button>
      <button class="maika-tab <?php echo $currentTab == "livechat" ? "maika-active" : ""; ?>"
      data-tab="livechat">Livechat</button>
  </div>
  <?php
    if(file_exists(plugin_dir_path(__FILE__).'includes/config/constants.php')){
      require_once plugin_dir_path(__FILE__).'includes/config/constants.php';
    }
  ?>
  <div class="maika-tab-content" id="home"
    <?php echo $currentTab == "home" || $currentTab == "" ? "" : "style='display: none'"; ?>>
    <?php
      if(file_exists(plugin_dir_path(__FILE__).'assets/html/content_home.html')){
        ob_start();
        require_once plugin_dir_path(__FILE__).'assets/html/content_home.html';
        $htmlContent = ob_get_clean();
      
        echo wp_kses($htmlContent, Maika_Constants::MAIKA_ALLOWED_TAGS_HTML);
      }
    ?>
  </div>

  <div class="maika-tab-content" id="guide" <?php echo $currentTab == "guide" ? "" : "style='display: none'"; ?>>
    <div id="maika-required">
      <?php
        if($pass_guide_step == 0){
          echo "
          <h2 class='mt-2 text-2xl font-bold'><span class='text-red-500'>[Action Required]</span> Setup API keys for Maika Genius</h2>
          <h3 class='mt-2 text-lg font-semibold'>Required 1: You'll need to create an “<strong class='font-bold'><a class='text-[#0000ff] hover:text-[#135e96]' href='/wp-admin/profile.php'>Application Password</a></strong>“. You can follow the <span class='font-semibold' style='color: blue;'><a href='#application-passwords'>instructions here.</a></span></h3>
          <h3 class='text-lg font-semibold'>Required 2: Next, you'll need to generate a “<strong class='font-bold'><a class='text-[#0000ff] hover:text-[#135e96]' href='/wp-admin/admin.php?page=wc-settings&tab=advanced&section=keys&create-key=1'>WooCommerce REST API key</a></strong>“. Simply follow the steps <span class='font-semibold' style='color: blue;'><a href='#woo-rest-api'>provided here</a></span> to set it up.</h3>
          <hr class='mt-2'>
          ";
        }

        if($pass_guide_step == 1){
          echo "
          <h2 class='mt-2 text-2xl font-bold'><span class='text-red-500'>[Action Required]</span> Connect your WordPress with Maika</h2>
          <h3 class='mt-2 text-lg font-semibold'>Click the \"Connect your WordPress with Maika\" button above to connect with Maika Hub</h3>
          <hr class='mt-2'>
          ";
        }
      ?>
    </div>

    <div id="application-passwords">
      <h2 class="mt-2 text-2xl font-bold">How to create Application Passwords</h2>

      <h3 class="mt-2 text-lg font-semibold">Step 1: Navigate to Your Profile</h3>
      <p>From the left-hand menu, click on <span class="font-semibold">“Users”</span> and then select <span
          class="font-semibold">“<a class='text-[#0000ff] hover:text-[#135e96]' href="/wp-admin/profile.php">Profile</a>”</span> (or <span class="font-semibold">“<a class='text-[#0000ff] hover:text-[#135e96]' href="/wp-admin/profile.php">Your
            Profile</a>”</span>).</p>
      <img class="guide-image ml-4 mt-2" src="<?php echo esc_url(plugins_url('assets/images/your-profile.png', __FILE__)); ?>"
        alt="your-profile">

      <h3 class="mt-4 text-lg font-semibold">Step 2: Find the Application Passwords Section</h3>
      <p>Scroll down to the “<span class="font-semibold">Application Passwords</span>” section, which is usually located
        towards near the bottom of your profile page.</p>

      <h3 class="mt-4 text-lg font-semibold">Step 3: Generate a New Application Password</h3>
      <p>In the “<span class="font-semibold">Application Passwords</span>” section, you’ll see a field to “<span
          class="font-semibold">Add New Application Password</span>”. Enter the Application Passwords name “<span
          class="font-semibold">maika</span>” for your new password to identify its use - <span
          class="text-red-500">Please enter the correct Application Password name: <span class="font-bold">maika</span></span>.<br>After
        entering a name, click the “<span class="font-semibold">Add New Application Password</span>” button. This action
        will generate a new application password for you.</p>
      <img class="guide-image ml-4 mt-2"
        src="<?php echo esc_url(plugins_url('assets/images/profile-application-password.png', __FILE__)); ?>"
        alt="profile-application-password">

      <h3 class="mt-4 text-lg font-semibold">Step 4: Copy Your New 'Application Password'</h3>
      <p>A new application password will be displayed. Copy this password and store it in a safe place. You won’t be
        able to see it again after you leave this page.</p>
      <img class="guide-image ml-4 mt-2"
        src="<?php echo esc_url(plugins_url('assets/images/copy-application-password.png', __FILE__)); ?>"
        alt="copy-application-password">
    </div>

    <hr class="mt-4">

    <div id="woo-rest-api">
      <h2 class="mt-2 text-2xl font-bold">How to create an API key for WooCommerce, follow these steps</h2>

      <h3 class="mt-2 text-lg font-semibold">Step 1: Access WooCommerce</h3>
      <p>Select “<span class="font-semibold">WooCommerce</span>” from the left-hand menu. Click on “<span
          class="font-semibold"><a class='text-[#0000ff] hover:text-[#135e96]' href="/wp-admin/admin.php?page=wc-settings">Settings</a></span>” from the WooCommerce menu.</p>
      <img class="guide-image ml-4 mt-2" src="<?php echo esc_url(plugins_url('assets/images/woo-settings.png', __FILE__)); ?>"
        alt="woo-settings">

      <h3 class="mt-4 text-lg font-semibold">Step 2: Go to API Settings</h3>
      <p>Select the “<span class="font-semibold"><a class='text-[#0000ff] hover:text-[#135e96]' href="/wp-admin/admin.php?page=wc-settings&tab=advanced">Advanced</a></span>” tab. Choose “<span class="font-semibold"><a class='text-[#0000ff] hover:text-[#135e96]' href="/wp-admin/admin.php?page=wc-settings&tab=advanced&section=keys">REST API</a></span>”. Click the “<span class="font-semibold"><a class='text-[#0000ff] hover:text-[#135e96]' href="/wp-admin/admin.php?page=wc-settings&tab=advanced&section=keys&create-key=1">Add Key</a></span>” button.</p>
      <img class="guide-image ml-4 mt-2" src="<?php echo esc_url(plugins_url('assets/images/restapi-addkey.webp', __FILE__)); ?>"
        alt="restapi-addkey">

      <h3 class="mt-4 text-lg font-semibold">Step 3: Fill in the following information</h3>
      <p><span class="font-semibold">Description</span>: A name or description for the API key, enter “<span
          class="font-semibold">maika</span>” - <span class="text-red-500">Please enter the correct description: <span
            class="font-bold">maika</span></span>.<br><span class="font-semibold">User</span>: Choose the user you
        want to assign the API key to, <span class="font-semibold">select your account</span>.<br><span
          class="font-semibold">Permissions</span>: Select the permissions for the API key. Chose <span
          class="font-semibold">Read/Write - </span><span class="text-red-500">Please choose the correct permissions:
          <span class="font-bold">Read/Write</span></span>.</p>
      <img class="guide-image ml-4 mt-2"
        src="<?php echo esc_url(plugins_url('assets/images/woo-generate-api.png', __FILE__)); ?>" alt="woo-generate-api">

      <h3 class="mt-4 text-lg font-semibold">Step 4: Generate the API Key</h3>
      <p>Click the “<span class="font-semibold">Generate API Key</span>” button.
        You will see a message with the <span class="font-semibold">Consumer Key</span> and <span
          class="font-semibold">Consumer Secret</span>. Make sure to <span class="font-semibold">copy and store these
          values in a safe place, as they will not be displayed again.</span></p>
      <img class="guide-image ml-4 mt-2"
        src="<?php echo esc_url(plugins_url('assets/images/restapi-keygeneration.png', __FILE__)); ?>"
        alt="restapi-keygeneration">

    </div>
  </div>

  <div class="maika-tab-content" id="settings" <?php echo $currentTab == "settings" ? "" : "style='display: none'"; ?>>
    <!-- content -->
    <!--<h2>Setting Maika Genius</h2>-->
    <?php
      if($maika_cid != false){ //$maika_connected_maikahub === true

        echo "<div id='iframe_maika_container_settings'>";
        if($currentTab == 'settings'){ // Show iframes if accessing tab directly from link
          // "&secret_key=".esc_html($maika_secretKey).
          echo "
            <iframe id='MAIKA_IFRAME_settings' src='https://hub.askmaika.ai/app/site?cid=".esc_html($maika_cid)."&display_mode=embed&wp_domain=".esc_url($domain_web)."' style='border: none; height: auto; width: 100%; min-height: 800px'></iframe>
          ";
        }
        echo "</div>";
      }
    ?>

    <form method="post">
      <?php wp_nonce_field('maika_ai_settings_nonce', 'maika_ai_nonce_field'); ?>
      
      <input style="cursor: pointer;"
        class="mt-4 rounded border border-purple-600 bg-white px-8 py-2 text-center text-sm font-medium text-purple-600 focus:outline-none hover:text-white hover:bg-purple-600 focus:ring"
        type="submit" name="clearAll" value="Disconnect - Clear all data" />
    </form>
  </div>

  <div class="maika-tab-content" id="product-descriptor"
    <?php echo $currentTab == "product-descriptor" ? "" : "style='display: none'"; ?>>
    <?php 
      if($maika_cid != false){ //$maika_connected_maikahub === true
        // echo "
        // <a href='https://hub.askmaika.ai/app/woo_prod_revise?cid=$maika_cid&secret_key=$maika_secretKey' target='_blank'><button style='margin-bottom: 20px; border: 2px solid #ececec; padding: 4px 12px;' >Go to AI Product Descriptor</button></a>
        // ";

        echo "<div id='iframe_maika_container_product-descriptor'>";
        if($currentTab == 'product-descriptor'){ // Show iframes if accessing tab directly from link
          // "&secret_key=".esc_html($maika_secretKey).
          echo "
          <iframe id='MAIKA_IFRAME_product-descriptor' src='https://hub.askmaika.ai/app/woo_prod_revise?cid=".esc_html($maika_cid)."&display_mode=embed&wp_domain=".esc_url($domain_web)."' style='border: none; height: auto; width: 100%; min-height: 800px'></iframe>
          ";
        }
        echo "</div>";
      }
      else{
        if(file_exists(plugin_dir_path(__FILE__).'assets/html/content_prod_descriptor.html')){
          ob_start();
          require_once plugin_dir_path(__FILE__).'assets/html/content_prod_descriptor.html';
          $htmlContent = ob_get_clean();

          echo wp_kses($htmlContent, Maika_Constants::MAIKA_ALLOWED_TAGS_HTML);
        }
      }
    ?>
  </div>

  <div class="maika-tab-content" id="product-catalog-builder"
    <?php echo $currentTab == "product-catalog-builder" ? "" : "style='display: none'"; ?>>
    <?php
      // if($maika_cid != false){ //$maika_connected_maikahub === true
      //   echo "[iframe]";
      // }
      // else{
      //  render HTML content_prod_cat_builder
      // }

      if(file_exists(plugin_dir_path(__FILE__).'assets/html/content_prod_cat_builder.html')){
        ob_start();
        require_once plugin_dir_path(__FILE__).'assets/html/content_prod_cat_builder.html';
        $htmlContent = ob_get_clean();

        echo wp_kses($htmlContent, Maika_Constants::MAIKA_ALLOWED_TAGS_HTML);
      }
    ?>
  </div>

  <div class="maika-tab-content" id="seo-optimizer"
    <?php echo $currentTab == "seo-optimizer" ? "" : "style='display: none'"; ?>>
    <?php
      // if($maika_cid != false){ //$maika_connected_maikahub === true
      //   echo "[iframe]";
      // }
      // else{
      //   render HTML content_seo_opt
      // }

      if(file_exists(plugin_dir_path(__FILE__).'assets/html/content_seo_opt.html')){
        ob_start();
        require_once plugin_dir_path(__FILE__).'assets/html/content_seo_opt.html';
        $htmlContent = ob_get_clean();

        echo wp_kses($htmlContent, Maika_Constants::MAIKA_ALLOWED_TAGS_HTML);
      }
    ?>
  </div>

  <div class="maika-tab-content" id="livechat"
    <?php echo $currentTab == "livechat" ? "" : "style='display: none'"; ?>>
    <?php
      // if($maika_connected_maikahub === true){
      //   echo "[iframe]";
      // }
      // else{
      //// render HTML content_livechat
      // }

      if(file_exists(plugin_dir_path(__FILE__).'assets/html/content_livechat.html')){
        ob_start();
        require_once plugin_dir_path(__FILE__).'assets/html/content_livechat.html';
        $htmlContent = ob_get_clean();

        echo wp_kses($htmlContent, Maika_Constants::MAIKA_ALLOWED_TAGS_HTML);
      }
    ?>
  </div>
</div>

<?php
    // Enqueue style
    wp_enqueue_style('admin-maika-css');
    wp_enqueue_style('admin-maika-tailwind-css');
    wp_enqueue_style('admin-maika-mautic');
    // Enqueue JavaScript file
    wp_enqueue_script('admin-maika-tabs');
 }

 // ****************************************************
 // === REGISTER JavaScript, CSS,... ADMIN page: maika-genius ===
 // JavaScript
 function maika_enqueue_admin_scripts($hook) {
  // Check plug
  if ($hook != 'toplevel_page_maika-genius') {
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
  // Check plug
  if ($hook != 'toplevel_page_maika-genius') {
      return;
  }

  wp_register_style(
    'admin-maika-css', // Handle cho style
    plugin_dir_url(__FILE__) . 'assets/css/admin-maika.css',
    array(), // Dependencies... if any
    '1.0', // Version
    'all' // Media cho style
  );

  wp_register_style(
    'admin-maika-mautic', // Handle cho style
    plugin_dir_url(__FILE__) . 'assets/css/admin-maika-mautic.css',
    array(), // Dependencies... if any
    '1.0', // Version
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
 // == END SCOPE == REGISTER JavaScript ADMIN page: maika-genius

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

 // ==========================================================
 // Hook add link script in page
 add_action('wp_enqueue_scripts', 'maika_chatbox_add_script');
 function maika_chatbox_add_script() {
    // add file JavaScript (maika.js) in footer
    wp_enqueue_script('maika-chatbox-js', 'https://hub.askmaika.ai/app/static/maika.js', array(), time(), true);
 }

 // ADD SCRIPT Maika Genius CHAT BOX
 // add custom script
 add_action('wp_footer', 'maika_chatbox_config');
 function maika_chatbox_config(){
    // $maika_ai_secretKey = get_option("maika_ai_secretKey");
    $maika_ai_favcolor = get_option("maika_ai_favcolor") ? get_option("maika_ai_favcolor") : "#800080";
    $maika_ai_title = get_option("maika_ai_title") ? get_option("maika_ai_title") : "Maika";
    $maika_ai_cid = get_option("maika_ai_cid");

    if($maika_ai_cid){ //$maika_ai_secretKey && 
      // Localize script to pass PHP variables to JavaScript
      wp_localize_script('maika-engine', 'maikaEngineData', array(
        'cid' => esc_js($maika_ai_cid),
      ));
      // Load into script
      wp_enqueue_script('maika-engine');
    }
 }

 // === REGISTER JavaScript wordpress page ===
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

 // ===============================================================
 //ADD API
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