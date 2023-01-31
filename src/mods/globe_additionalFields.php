<?php

require_once( GLOBE__PLUGIN_DIR . 'src/lib/netlify.php' );

function glb_settings_init() {
  register_setting( 'glb', 'glb_options' );

  add_settings_section(
    'glb_section_socials',
    'Social media handles',
    'glb_section_socials_callback',
    'glb'
  );

  add_settings_section(
    'glb_section_physical',
    'Physical location',
    'glb_section_physical_callback',
    'glb'
  );

  add_settings_field(
    'glb_twitter',
    'Twitter',
    'glb_twitter_cb',
    'glb',
    'glb_section_socials',
    array(
      'label_for'         => 'glb_twitter',
      'class'             => 'glb_row',
      'glb_custom_data' => 'custom',
    )
  );

  add_settings_field(
    'glb_instagram',
    'Instagram',
    'glb_instagram_cb',
    'glb',
    'glb_section_socials',
    array(
      'label_for'         => 'glb_instagram',
      'class'             => 'glb_row',
      'glb_custom_data' => 'custom',
    )
  );

  add_settings_field(
    'glb_postal_address',
    'Postal address',
    'glb_postal_address_cb',
    'glb',
    'glb_section_physical',
    array(
      'label_for'         => 'glb_postal_address',
      'class'             => 'glb_row',
      'glb_custom_data' => 'custom',
    )
  );
}

add_action( 'admin_init', 'glb_settings_init' );


function glb_section_physical_callback($args) {
  // Could output something… but doesn't…
  // echo 'Hello world'
}

function glb_section_socials_callback($args) {
  // Could output something… but doesn't…
  // echo 'Hello world'
}


function glb_twitter_cb( $args ) {
  $options = get_option( 'glb_options' );
  ?>
    <input type="text" value="<?php echo isset($options['twitter']) ? $options['twitter'] : '' ?>" name="glb_options[twitter]" />
  <?php
}

function glb_instagram_cb( $args ) {
  $options = get_option( 'glb_options' );
  ?>
    <input type="text" value="<?php echo isset($options['instagram']) ? $options['instagram'] : '' ?>" name="glb_options[instagram]" />
  <?php
}

function glb_postal_address_cb($args) {
  $options = get_option('glb_options');
  ?>
  <input type="text" value="<?php echo isset($options['postal_address']) ? $options['postal_address'] : '' ?>" name="glb_options[postal_address]" />
  <?php
}

function glb_options_page() {
  add_menu_page(
    'Additional fields',
    'Globe Additionals',
    'edit_others_pages',
    'glb',
    'glb_options_page_html'
  );

  add_submenu_page(
    'glb',
    'Globe Additionals',
    'Globe Additionals',
    'edit_others_pages',
    'glb',
    'glb_options_page_html'
  );

  add_submenu_page(
    'glb',
    'Deploy Content',
    'Deploy',
    'edit_others_pages',
    'glb_deploy',
    'glb_deploy_page_html'
  );

}

add_action( 'admin_menu', 'glb_options_page' );

function glb_deploy_page_html() {
  if (!current_user_can('edit_others_pages')) {
    return;
  }

  $options = get_option('glb_plugin_options');
  if (!isset($options['netlify_deploy_url']) || $options['netlify_deploy_url'] == "") {
    echo "<h1>⚠️ Netlify settings missing</h1>";
    return;
  }

  if (isset($_POST['deployit']) == 1) {
    $deployHook = $options['netlify_deploy_url'] . "?trigger_title=Triggered%20from%20CMS%20deploy.";

    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $deployHook,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 10,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_POST => 1
    ));
    curl_exec($curl);
    curl_close($curl);

    $view = GLOBE__PLUGIN_DIR . 'src/views/deploy_queued.php';
    include( $view );

  } else {

    $token = $options['netlify_token'];
    $url = 'sites/'.$options['netlify_site_id'].'/deploys';
    $netlifyCall = new NetlifyCall($url, $token);
    $deployData = $netlifyCall->call_cURL();

    $view = GLOBE__PLUGIN_DIR . 'src/views/deploy_button.php';
    include( $view );
  }
}

function glb_options_page_html() {
  if (!current_user_can('edit_others_pages')) {
    return;
  }

  if (isset($_GET['settings-updated'])) {
    add_settings_error(
      'glb_messages',
      'glb_message',
      'Settings saved',
      'updated'
    );
  }

  settings_errors('glb_messages');
  $view = GLOBE__PLUGIN_DIR . 'src/views/public_fields_form.php';
  include( $view );
}

