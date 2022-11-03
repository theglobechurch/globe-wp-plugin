<?php

function glb_add_settings_page() {
  add_options_page( 'Globe CMS Settings', 'Globe CMS Settings', 'manage_options', 'globe-cms-settings', 'glb_render_plugin_settings_page' );
}
add_action( 'admin_menu', 'glb_add_settings_page' );

function glb_render_plugin_settings_page() {
  ?>
  <h1>Globe CMS Settings</h1>
  <form action="options.php" method="post">
    <?php
      settings_fields( 'glb_plugin_options' );
      do_settings_sections( 'glb_plugin' );
    ?>
    <input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Save' ); ?>" />
  </form>
  <?php
}

function glb_register_settings() {
  register_setting('glb_plugin_options', 'glb_plugin_options', 'glb_plugin_options_validate');
  add_settings_section('netlify_settings', 'Netlify settings', 'glb_section_text', 'glb_plugin');
  add_settings_field('glb_plugin_setting_deploy_url_hook', 'Netlify Deploy URL Hook', 'glb_plugin_netlify_deploy_url', 'glb_plugin', 'netlify_settings');
}
add_action('admin_init', 'glb_register_settings');


function glb_section_text() {
  echo '<p>Adjust how to deploy to Netlify…</p>';
}

function glb_plugin_netlify_deploy_url() {
  $options = get_option('glb_plugin_options');
  $val = "";
  if (isset($options['netlify_deploy_url'])) {
    $val = esc_attr($options['netlify_deploy_url']);
  }
  echo "<input id='glb_plugin_netlify_deploy_url' name='glb_plugin_options[netlify_deploy_url]' type='text' value='" . $val . "' />";
}

function glb_plugin_options_validate($input) {
  $newinput['netlify_deploy_url'] = trim( $input['netlify_deploy_url'] );

  // Deploy hook should be a valid URL
  if (filter_var($newinput['netlify_deploy_url'], FILTER_VALIDATE_URL) === FALSE) {
    $newinput['netlify_deploy_url'] = '';
  }

  return $newinput;
}
