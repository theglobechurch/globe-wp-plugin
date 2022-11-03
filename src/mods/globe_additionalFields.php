<?php

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
    'manage_options',
    'glb',
    'glb_options_page_html'
  );
}

add_action( 'admin_menu', 'glb_options_page' );

function glb_options_page_html() {
  if (!current_user_can('manage_options')) {
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
  ?>
  <div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <p>⚠️ Currently these fields don't output to the frontend… but they might in the future.</p>
    <form action="options.php" method="post">
      <?php
      settings_fields('glb');
      do_settings_sections('glb');
      submit_button('Save');
      ?>
    </form>
  </div>
  <?php
}
