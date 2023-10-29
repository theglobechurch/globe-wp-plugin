<?php

require_once( GLOBE__PLUGIN_DIR . 'src/lib/netlify.php' );

function glb_settings_init() {
  register_setting( 'glb', 'glb_options' );

  add_settings_section(
    'glb_section_sunday_override',
    'Override Sunday Service Details',
    'glb_section_sunday_override_callback',
    'glb',
    array(
      'before_section' => '<hr />',
      'after_section' => '<hr />'
    )
  );

  add_settings_section(
    'glb_section_socials',
    'Social media handles',
    'glb_section_socials_callback',
    'glb',
    array(
      'after_section' => '<hr />'
    )
  );

  add_settings_section(
    'glb_section_physical',
    'Physical location',
    'glb_section_physical_callback',
    'glb',
    array(
    )
  );


  // Physical location override
  add_settings_field(
    'glb_sunday_time',
    'Service Time',
    'glb_sunday_time_cb',
    'glb',
    'glb_section_sunday_override',
    array(
      'label_for'         => 'glb_sunday_time',
      'class'             => 'glb_row',
      'glb_custom_data' => 'custom',
    )
  );

  add_settings_field(
    'glb_sunday_location',
    'Location',
    'glb_sunday_location_cb',
    'glb',
    'glb_section_sunday_override',
    array(
      'label_for'         => 'glb_sunday_location',
      'class'             => 'glb_row',
      'glb_custom_data' => 'custom',
    )
  );

  add_settings_field(
    'glb_sunday_location_tube',
    'Nearest Underground Station',
    'glb_sunday_location_tube_cb',
    'glb',
    'glb_section_sunday_override',
    array(
      'label_for'         => 'glb_sunday_location_tube',
      'class'             => 'glb_row',
      'glb_custom_data' => 'custom',
    )
  );

  add_settings_field(
    'glb_sunday_override_end',
    'Disable the override on',
    'glb_sunday_override_end_cb',
    'glb',
    'glb_section_sunday_override',
    array(
      'label_for'         => 'glb_sunday_override_end',
      'class'             => 'glb_row',
      'glb_custom_data' => 'custom',
    )
  );

  // Social media
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


function glb_section_sunday_override_callback($args) {
  // Could output something… but doesn't…
  // echo 'Hello world'
  ?>
  <p class="description">ℹ️ Be aware these will override the information displayed on the homepage; updates to the calendar will still be required</p>
  <?php
}

function glb_section_physical_callback($args) {
  // Could output something… but doesn't…
  // echo 'Hello world'
}

function glb_section_socials_callback($args) {
  // Could output something… but doesn't…
  // echo 'Hello world'
}

function glb_sunday_time_cb($args) {
  $options = get_option( 'glb_options' );
  ?>
    <input type="text" value="<?php echo isset($options['sunday_time']) ? $options['sunday_time'] : null ?>" name="glb_options[sunday_time]" />
    <p class="description">Default: <code>4pm</code></p>
  <?php
}

function glb_sunday_location_cb($args) {
  $options = get_option( 'glb_options' );
  ?>
    <input type="text" style="width: 100%" value="<?php echo isset($options['sunday_location']) ? $options['sunday_location'] : null ?>" name="glb_options[sunday_location]" />
    <p class="description">Default: <code>Ark Globe Academy, Harper Road, London, SE1 6AF</code></p>
  <?php
}

function glb_sunday_location_tube_cb($args) {
  $options = get_option( 'glb_options' );
  ?>
    <input type="text" value="<?php echo isset($options['sunday_location_tube']) ? $options['sunday_location_tube'] : null ?>" name="glb_options[sunday_location_tube]" />
    <p class="description">Default: <code>Elephant and Castle</code></p>
  <?php
}

function glb_sunday_override_end_cb($args) {
  $options = get_option( 'glb_options' );
  ?>
    <input type="date" value="<?php echo isset($options['sunday_override_end']) ? $options['sunday_override_end'] : null ?>" name="glb_options[sunday_override_end]" />
  <?php
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
  <input type="text" style="width: 100%" value="<?php echo isset($options['postal_address']) ? $options['postal_address'] : '' ?>" name="glb_options[postal_address]" />
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

