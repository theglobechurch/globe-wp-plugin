<?php

/**
 * Creates the custom post type for sermons
 */
function sermon_post_type() {
  register_post_type('sermon',
    array(
      'labels' => array(
        'name' => __( 'Sermons' ),
        'singular_name' => __('Sermon'),
        'add_new' => __('Add New Sermon'),
        'add_new_item' => __('Add New Sermon'),
        'edit_item' => __('Edit Sermon'),
        'new_item' => __('New Sermon'),
        'view_item' => __('View Sermon'),
        'view_items' => __('View Sermon'),
        'search_items' => __('Search Sermon'),
        'all_items' => __('All Sermon'),
        'not_found' => __('No sermons found.'),
        'not_found_in_trash' => __('No sermons found in trash.'),
        'archives' => __('Sermon Archives'),
        'attributes' => __('Sermon Attributes'),
      ),
      'public' => true,
      'show_in_rest' => true,
      'rest_base' => 'sermons',
      'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
      'has_archive' => true,
      'rewrite' => array('slug' => 'sermon'),
      'menu_position' => 5,
      'menu_icon' => 'dashicons-format-audio'
    )
  );
  add_post_type_support( 'sermon', 'author' );
}

/**
 * Creates the sermon series taxonomy
 */
function create_sermon_taxonomy() {
  register_taxonomy('sermon_series','sermon', array(
    'hierarchical' => true,
    'labels' => array(
      'name' => _x('Sermon Series', 'taxonomy general name'),
      'singular_name' => _x('Sermon Series', 'taxonomy singular name'),
      'menu_name' => __('Sermon Series'),
      'all_items' => __('All Sermon Series'),
      'edit_item' => __('Edit Sermon Series'),
      'update_item' => __('Update Sermon Series'),
      'add_new_item' => __('Add Sermon Series'),
      'new_item_name' => __('New Sermon Series'),
      'back_to_items' => __('Back to all Sermon Series')
    ),
    'show_ui' => true,
    'show_in_rest' => true,
    'show_admin_column' => true
  ));
}


function sermon_add_taxonomy_fields($taxonomy) {
  $view = GLOBE__PLUGIN_DIR . 'src/views/sermon_series_add_fields.php';
  include( $view );
}


function sermon_edit_taxonomy_fields( $term, $taxonomy ) {
  $sermon_startDate = get_term_meta( $term->term_id, 'sermon_startDate', true );
  $sermon_endDate = get_term_meta( $term->term_id, 'sermon_endDate', true );
  $image_id = get_term_meta( $term->term_id, 'glb_sermon_artwork', true );

  $view = GLOBE__PLUGIN_DIR . 'src/views/sermon_series_edit_fields.php';
  include( $view );
}


function glb_save_term_fields( $term_id ) {
  if (isset($_POST[ 'sermon_startDate' ])) {
    update_term_meta(
      $term_id,
      'sermon_startDate',
      sanitize_text_field( $_POST[ 'sermon_startDate' ] )
    );
  }

  if (isset($_POST[ 'sermon_endDate' ])) {
    update_term_meta(
      $term_id,
      'sermon_endDate',
      sanitize_text_field( $_POST[ 'sermon_endDate' ] )
    );
  }

  if (isset($_POST[ 'glb_sermon_artwork' ])) {
    update_term_meta(
      $term_id,
      'glb_sermon_artwork',
      absint( $_POST[ 'glb_sermon_artwork' ] )
    );
  }

}


function glb_register_sermon_meta() {
  register_post_meta('sermon', 'glb_sermon_passage', array(
      'type'         => 'string',
      'single'       => true,
      'default'      => '',
      'show_in_rest' => true,
    )
  );

  register_post_meta('sermon', 'glb_sermon_date', array(
      'type'         => 'string',
      'single'       => true,
      'default'      => '',
      'show_in_rest' => true,
    )
  );

  register_post_meta('sermon', 'glb_sermon_mp3', array(
      'type'         => 'string',
      'single'       => true,
      'default'      => '',
      'show_in_rest' => true,
    )
  );
}

// REST API MODIIFICATIONS
// Sermon endpoint
function glb_rest_add_sermon_url(){
  register_rest_field( array('sermon'),
    'sermon_data',
    array(
      'get_callback'    => 'glb_rest_get_get_sermon_url',
      'update_callback' => null,
      'schema'          => null,
    )
  );
}


function glb_rest_get_get_sermon_url( $object, $field_name, $request ) {
  if( $object['meta']['glb_sermon_mp3'] ){
    $filemeta = wp_get_attachment_metadata ($object['meta']['glb_sermon_mp3']);
    return array(
      'url' => wp_get_attachment_url( $object['meta']['glb_sermon_mp3'] ),
      'filesize' => $filemeta ? $filemeta['filesize'] : null,
      'fileformat' => $filemeta ? $filemeta['fileformat'] : null,
      'length' => $filemeta ? $filemeta['length'] : null,
      'length' => $filemeta ? $filemeta['length_formatted'] : null,
    );
  }
  return false;
}

// Sermon series endpoint
function glb_rest_add_sermon_series_meta($object) {
  register_rest_field( array('sermon_series'),
    'artwork_url',
    array(
      'get_callback'    => 'glb_rest_get_series_artwork_url',
      'update_callback' => null,
      'schema'          => null,
    )
  );

  register_rest_field( array('sermon_series'),
    'start_date',
    array(
      'get_callback'    => 'glb_rest_get_series_start_date',
      'update_callback' => null,
      'schema'          => null,
    )
  );

  register_rest_field( array('sermon_series'),
    'end_date',
    array(
      'get_callback'    => 'glb_rest_get_series_end_date',
      'update_callback' => null,
      'schema'          => null,
    )
  );
}

function glb_rest_get_series_artwork_url( $object, $field_name, $request ) {
  $media_id = get_term_meta( $object['id'], 'glb_sermon_artwork', true );
  if( $media_id ){
    return wp_get_attachment_url( $media_id );
  }
  return false;
}

function glb_rest_get_series_start_date( $object, $field_name, $request ) {
  return get_term_meta( $object['id'], 'sermon_startDate', true );
}

function glb_rest_get_series_end_date( $object, $field_name, $request ) {
  return get_term_meta( $object['id'], 'sermon_endDate', true );
}


add_action('init', 'sermon_post_type');
add_action('init', 'create_sermon_taxonomy', 0);
add_action('init', 'glb_register_sermon_meta' );
add_action('sermon_series_add_form_fields', 'sermon_add_taxonomy_fields');
add_action('sermon_series_edit_form_fields', 'sermon_edit_taxonomy_fields', 10, 2 );
add_action('created_sermon_series', 'glb_save_term_fields' );
add_action('edited_sermon_series', 'glb_save_term_fields' );
add_action('rest_api_init', 'glb_rest_add_sermon_url' );
add_action('rest_api_init', 'glb_rest_add_sermon_series_meta' );
