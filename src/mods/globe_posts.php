<?php

function glb_rest_add_post_featured_img() {
  register_rest_field( array('post'),
    'featured_img_url',
    array(
      'get_callback'    => 'glb_rest_get_post_featured_img',
      'update_callback' => null,
      'schema'          => null,
    )
  );
}

function glb_rest_get_post_featured_img( $object, $field_name, $request ) {
  if( $object['featured_media']){
    return wp_get_attachment_url( $object['featured_media'] );
  }
  return false;
}


add_action('rest_api_init', 'glb_rest_add_post_featured_img' );
