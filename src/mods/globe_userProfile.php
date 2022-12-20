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


  // TODO: Expose glb_userAvatar in REST API
  // /wp-json/wp/v2/users/

  add_action( 'show_user_profile', 'extra_user_profile_fields' );
  add_action( 'edit_user_profile', 'extra_user_profile_fields' );

  add_action( 'personal_options_update', 'save_extra_user_profile_fields' );
  add_action( 'edit_user_profile_update', 'save_extra_user_profile_fields' );
