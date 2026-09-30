<?php

/**
 * Creates the custom post type for link trees (linktree style pages)
 */
function link_tree_post_type() {
  register_post_type('link_tree',
    array(
      'labels' => array(
        'name' => __('Link Tree'),
        'singular_name' => __('Link Tree'),
        'add_new' => __('Add New Tree'),
        'add_new_item' => __('Add New Link Tree'),
        'edit_item' => __('Edit Link Tree'),
        'new_item' => __('New Link Tree'),
        'view_item' => __('View Link Tree'),
        'view_items' => __('View Link Trees'),
        'search_items' => __('Search Link Trees'),
        'all_items' => __('All Link Trees'),
        'not_found' => __('No link trees found.'),
        'not_found_in_trash' => __('No link trees found in trash.'),
      ),
      'hierarchical' => false,
      'public' => true,
      'show_in_rest' => true,
      'rest_base' => 'link-trees',
      'supports' => array('title', 'excerpt', 'thumbnail'),
      // Only admins can create trees; anyone who can edit posts (editors) can still edit them
      'capabilities' => array('create_posts' => 'manage_options'),
      'map_meta_cap' => true,
      'has_archive' => false,
      'rewrite' => array('slug' => 'tree', 'with_front' => false),
      'menu_position' => 7,
      'menu_icon' => 'dashicons-palmtree',
    )
  );

  // Use old editor
  add_filter('use_block_editor_for_post_type', function($use, $post_type) {
    if ($post_type === 'link_tree') return false;
    return $use;
  }, 10, 2);
}

/**
 * Hard cropped 1:1 version of each link image
 */
function glb_register_link_tree_image_size() {
  add_image_size('glb_square', 800, 800, true);
}

/**
 * Stores the links as a single ordered array in post meta.
 * Not exposed raw in REST: the resolved `links` field is used instead.
 */
function glb_register_link_tree_meta() {
  register_post_meta('link_tree', 'glb_tree_links', array(
    'type'              => 'array',
    'single'            => true,
    'default'           => array(),
    'show_in_rest'      => false,
    'sanitize_callback' => 'glb_sanitize_tree_links',
    'auth_callback'     => function() {
      return current_user_can('edit_posts');
    },
  ));
}

function glb_sanitize_tree_links( $links ) {
  if (!is_array($links)) {
    return array();
  }

  $clean = array();
  foreach ($links as $link) {
    if (!is_array($link)) {
      continue;
    }

    // esc_url_raw only allows safe protocols, so javascript: urls are dropped.
    // Relative paths such as "/about" are kept as they are.
    $url = esc_url_raw(trim($link['url'] ?? ''));
    $title = sanitize_text_field($link['title'] ?? '');

    // Skip empty rows
    if (!$url && !$title) {
      continue;
    }

    $clean[] = array(
      'url'         => $url,
      'title'       => $title,
      'description' => sanitize_textarea_field($link['description'] ?? ''),
      'image_id'    => absint($link['image_id'] ?? 0),
    );
  }
  return $clean;
}

// Meta box for the links
function glb_link_tree_add_links_meta_box() {
  add_meta_box(
    'glb_tree_links',
    'Links',
    'glb_link_tree_links_meta_box_html',
    'link_tree',
    'normal',
    'high'
  );
}

function glb_link_tree_links_meta_box_html($post) {
  $links = get_post_meta($post->ID, 'glb_tree_links', true);
  $links = is_array($links) ? $links : array();

  wp_nonce_field('glb_save_tree_links', 'glb_tree_links_nonce');

  $view = GLOBE__PLUGIN_DIR . 'src/views/link_tree_links.php';
  include( $view );
}

function glb_link_tree_save_links($post_id) {
  // Nonce check
  if (
    ! isset($_POST['glb_tree_links_nonce']) ||
    ! wp_verify_nonce($_POST['glb_tree_links_nonce'], 'glb_save_tree_links')
  ) {
    return;
  }

  // Don't save on autosave
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return;
  }

  if (! current_user_can('edit_post', $post_id)) {
    return;
  }

  $links = isset($_POST['glb_tree_links']) ? wp_unslash($_POST['glb_tree_links']) : array();
  // Rows are submitted in DOM order, so this keeps the order set in the UI
  update_post_meta($post_id, 'glb_tree_links', glb_sanitize_tree_links(array_values((array) $links)));
}

function glb_link_tree_admin_scripts($hook) {
  $screen = get_current_screen();
  if (!$screen || $screen->post_type !== 'link_tree' || !in_array($hook, array('post.php', 'post-new.php'))) {
    return;
  }

  $file = GLOBE__PLUGIN_DIR . 'assets/glb_link_tree.js';
  wp_enqueue_script(
    'glb_link_tree',
    plugins_url('assets/glb_link_tree.js', GLOBE__PLUGIN_DIR . 'globe_plugin.php'),
    array('jquery', 'jquery-ui-sortable'),
    date("ymd-Gis", filemtime($file))
  );
}

/**
 * Resolves the stored meta into the public shape:
 * [{ title, description, url, img, img_square }]
 * Urls are returned as stored, so internal links are relative paths like "/about".
 */
function glb_get_tree_links( $post_id ) {
  $stored = get_post_meta($post_id, 'glb_tree_links', true);
  if (!is_array($stored)) {
    return array();
  }

  $links = array();
  foreach ($stored as $link) {
    $url = $link['url'] ?? '';
    if (!$url) {
      continue;
    }

    $img = empty($link['image_id']) ? false : wp_get_attachment_image_url($link['image_id'], 'full');
    $img_square = empty($link['image_id']) ? false : wp_get_attachment_image_url($link['image_id'], 'glb_square');

    $links[] = array(
      'title'       => $link['title'] ?? '',
      'description' => $link['description'] ?? '',
      'url'         => $url,
      'img'         => $img ? $img : null,
      'img_square'  => $img_square ? $img_square : null,
    );
  }
  return $links;
}

// REST API MODIFICATIONS
// link-trees endpoint
function glb_rest_add_link_tree_fields() {
  register_rest_field('link_tree', 'links', array(
    'get_callback' => function( $post ) {
      return glb_get_tree_links($post['id']);
    },
    'schema' => array(
      'description' => 'Resolved links for this tree',
      'type'        => 'array',
      'context'     => array('view', 'edit'),
      'items'       => array(
        'type'       => 'object',
        'properties' => array(
          'title'       => array('type' => 'string'),
          'description' => array('type' => 'string'),
          'url'         => array('type' => 'string'),
          'img'         => array('type' => array('string', 'null'), 'format' => 'uri'),
          'img_square'  => array('type' => array('string', 'null'), 'format' => 'uri'),
        ),
      ),
    ),
  ));

  register_rest_field('link_tree', 'featuredImage', array(
    'get_callback' => function( $post ) {
      $thumbnail_id = get_post_thumbnail_id($post['id']);
      if (!$thumbnail_id) {
        return null;
      }
      return array(
        'url'   => get_the_post_thumbnail_url($post['id'], 'full'),
        'alt'   => get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true),
        'title' => get_the_title($thumbnail_id),
      );
    },
    'schema' => array(
      'description' => 'Featured image for this tree',
      'type'        => 'object',
      'context'     => array('view', 'edit'),
      'properties'  => array(
        'url'   => array('type' => 'string', 'format' => 'uri'),
        'alt'   => array('type' => 'string'),
        'title' => array('type' => 'string'),
      ),
    ),
  ));
}

add_action('init', 'link_tree_post_type');
add_action('init', 'glb_register_link_tree_image_size');
add_action('add_meta_boxes_link_tree', 'glb_link_tree_add_links_meta_box');
add_action('save_post_link_tree', 'glb_link_tree_save_links');
add_action('admin_enqueue_scripts', 'glb_link_tree_admin_scripts');
add_action('init', 'glb_register_link_tree_meta');
add_action('rest_api_init', 'glb_rest_add_link_tree_fields');
