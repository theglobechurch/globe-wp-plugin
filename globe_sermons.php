<?php

function sermon_post_type() {
  register_post_type('sermons',
    array(
      'labels' => array(
        'name' => __( 'Sermons' ),
        'singular_name' => __('Sermon')
      ),
      'public' => true,
      'show_in_rest' => true,
      'supports' => array('title', 'editor', 'thumbnail'),
      'has_archive' => true,
      'rewrite' => array('slug' => 'sermon'),
      'menu_position' => 5,
      'menu_icon' => 'dashicons-format-audio'
    )
  );
}

function create_sermon_taxonomy() {
  register_taxonomy('sermon_series','sermons', array(
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
  $view = GLOBE__PLUGIN_DIR . 'views/sermon_series_add_fields.php';
  include( $view );
}


function sermon_edit_taxonomy_fields( $term, $taxonomy ) {
  $sermon_startDate = get_term_meta( $term->term_id, 'sermon_startDate', true );
  $sermon_endDate = get_term_meta( $term->term_id, 'sermon_endDate', true );
  $image_id = get_term_meta( $term->term_id, 'glb_sermon_artwork', true );

  $view = GLOBE__PLUGIN_DIR . 'views/sermon_series_edit_fields.php';
  include( $view );
}



function glb_save_term_fields( $term_id ) {
  update_term_meta(
    $term_id,
    'sermon_startDate',
    sanitize_text_field( $_POST[ 'sermon_startDate' ] )
  );
  update_term_meta(
    $term_id,
    'sermon_endDate',
    sanitize_text_field( $_POST[ 'sermon_endDate' ] )
  );
  update_term_meta(
    $term_id,
    'glb_sermon_artwork',
    absint( $_POST[ 'glb_sermon_artwork' ] )
  );

}

add_action('init', 'sermon_post_type');
add_action('init', 'create_sermon_taxonomy', 0);
add_action('sermon_series_add_form_fields', 'sermon_add_taxonomy_fields');
add_action('sermon_series_edit_form_fields', 'sermon_edit_taxonomy_fields', 10, 2 );
add_action( 'created_sermon_series', 'glb_save_term_fields' );
add_action( 'edited_sermon_series', 'glb_save_term_fields' );
