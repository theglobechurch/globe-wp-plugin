<?php

/**
 * Creates the custom post type for podcasts
 */
function podcast_post_type() {
  register_post_type('podcast',
    array(
      'labels' => array(
        'name' => __( 'Globe Talks' ),
        'singular_name' => __('Globe Talks'),
        'add_new' => __('Add New Episode'),
        'add_new_item' => __('Add New Episode'),
        'edit_item' => __('Edit Episode'),
        'new_item' => __('New Episode'),
        'view_item' => __('View Episode'),
        'view_items' => __('View Episodes'),
        'search_items' => __('Search Episodes'),
        'all_items' => __('All Episodes'),
        'not_found' => __('No episodes found.'),
        'not_found_in_trash' => __('No episodes found in trash.'),
        'archives' => __('Episodes Archives'),
        'attributes' => __('Episodes Attributes'),
      ),
      'hierarchical' => false,
      'public' => true,
      'show_in_rest' => true,
      'rest_base' => 'podcast',
      'supports' => array('title', 'editor', 'custom-fields'),
      'has_archive' => true,
      'rewrite' => array('slug' => 'podcast'),
      'menu_position' => 6,
      'menu_icon' => 'dashicons-microphone',
      'rewrite'      => array( 'slug' => 'podcast', 'with_front' => false ),
    )
  );
  add_post_type_support( 'podcast', 'author' );
}

function glb_register_podcast_meta() {
  register_post_meta('podcast', 'glb_podcast_mp3', array(
      'type'         => 'string',
      'single'       => true,
      'default'      => '',
      'show_in_rest' => true,
    )
  );
}

// REST API MODIIFICATIONS
// podcast endpoint
function glb_rest_add_podcast_url(){
  register_rest_field( array('podcast'),
    'podcast_data',
    array(
      'get_callback'    => 'glb_rest_get_get_podcast_url',
      'update_callback' => null,
      'schema'          => null,
    )
  );
}


function glb_rest_get_get_podcast_url( $object, $field_name, $request ) {
  if( $object['meta']['glb_podcast_mp3'] ){
    $filemeta = wp_get_attachment_metadata ($object['meta']['glb_podcast_mp3']);
    return array(
      'url' => wp_get_attachment_url( $object['meta']['glb_podcast_mp3'] ),
      'filesize' => $filemeta['filesize'],
      'fileformat' => $filemeta['fileformat'],
      'length' => $filemeta['length'],
      'length' => $filemeta['length_formatted'],
    );
  }
  return false;
}

add_action('init', 'podcast_post_type');
add_action('init', 'glb_register_podcast_meta' );
add_action('rest_api_init', 'glb_rest_add_podcast_url' );
