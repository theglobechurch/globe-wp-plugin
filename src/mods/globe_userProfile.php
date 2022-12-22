<?php


  function extra_user_profile_fields( $user ) {
    $image_id = get_user_meta( $user->ID, 'glb_userAvatar', true);
    $view = GLOBE__PLUGIN_DIR . 'src/views/author_profile_form.php';
    include( $view );
  }

  function save_extra_user_profile_fields( $user_id ) {
    if ( empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'update-user_' . $user_id ) ) {
        return;
    }

    if ( !current_user_can( 'edit_user', $user_id ) ) {
        return false;
    }

    update_user_meta( $user_id, 'glb_userAvatar', $_POST['glb_userAvatar'] );
  }

  function glb_rest_get_user_profile_picture( $object, $field_name, $request  ) {
    $user_id = $object['id'];
    $media_id = get_user_meta($user_id, 'glb_userAvatar', true);
    if (!$media_id) { return; }
    return wp_get_attachment_url($media_id);
  }

  function glb_rest_add_user_profile(){
    register_rest_field( array('user'),
      'profile_photo_url',
      array(
        'get_callback'    => 'glb_rest_get_user_profile_picture',
        'update_callback' => null,
        'schema'          => null,
      )
    );
  }

  add_action( 'show_user_profile', 'extra_user_profile_fields' );
  add_action( 'edit_user_profile', 'extra_user_profile_fields' );

  add_action( 'personal_options_update', 'save_extra_user_profile_fields' );
  add_action( 'edit_user_profile_update', 'save_extra_user_profile_fields' );

  add_action('rest_api_init', 'glb_rest_add_user_profile' );
