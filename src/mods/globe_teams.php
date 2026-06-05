<?php

/**
 * Creates the custom post type for sermons
 */
function teams_post_type() {
  register_post_type('teams',
    array(
      'labels' => array(
        'name' => __( 'Teams and Groups' ),
        'singular_name' => __('Team or Group'),
        'add_new' => __('Add New Team'),
        'add_new_item' => __('Add New Team'),
        'edit_item' => __('Edit Team'),
        'new_item' => __('New Team'),
        'view_item' => __('View Team'),
        'view_items' => __('View Team'),
        'search_items' => __('Search Team'),
        'all_items' => __('All Teams'),
        'not_found' => __('No team found.'),
        'not_found_in_trash' => __('No team found in trash.'),
        'archives' => __('Team Archives'),
        'attributes' => __('Team Attributes'),
        'item_published' => __('Team published.'),
        'item_published_privately' => __('Team published privately.'),
        'item_reverted_to_draft' => __('Team reverted to draft.'),
        'item_scheduled' => __('Team scheduled.'),
        'item_updated' => __('Team updated.'),
      ),
      'description' => 'Serving teams, ministry teams, social groups, etc',
      'public' => true,
      'show_in_rest' => true,
      'rest_base' => 'teams',
      'supports' => array('title', 'editor', 'excerpt', 'thumbnail'),
      'has_archive' => true,
      'rewrite' => array('slug' => 'team'),
      'menu_position' => 5,
      'menu_icon' => 'dashicons-groups'
    )
  );

  // Use old editor
  add_filter('use_block_editor_for_post_type', function($use, $post_type) {
    if ($post_type === 'teams') return false;
    return $use;
  }, 10, 2);

  // Rename the excerpt
  add_filter('gettext', function($translation, $text, $domain) {
    if (get_post_type() === 'teams' && $text === 'Excerpt') {
        return 'Summary';
    }
    return $translation;
  }, 20, 3);

  // make sure that the excerpt is clean of tags
  // By default there is at least a <p> wrapper
  add_filter('the_excerpt', function($excerpt) {
    if (get_post_type() === 'teams') {
        return wp_strip_all_tags($excerpt);
    }
    return $excerpt;
  });
}

/**
 * Creates the team taxonomy
 */
function create_team_taxonomy() {
  register_taxonomy('team_types','teams', array(
    'hierarchical' => true,
    'labels' => array(
      'name' => _x('Team types', 'taxonomy general name'),
      'singular_name' => _x('Team type', 'taxonomy singular name'),
      'menu_name' => __('Team Types'),
      'all_items' => __('All Team Types'),
      'edit_item' => __('Edit Team Type'),
      'update_item' => __('Update Team Types'),
      'add_new_item' => __('Add Team Type'),
      'new_item_name' => __('New Team Type'),
      'back_to_items' => __('Back to all Team Types'),
    ),
    'show_ui' => true,
    'show_in_rest' => true,
    'show_admin_column' => true
  ));
}

function teams_register_leader_meta() {
  register_post_meta('teams', 'team_leader_ids', array(
    'type' => 'integer',
    'description' => 'IDs of team leaders',
    'single' => false, // stores multiple values
    'show_in_rest' => array(
      'schema' => array(
        'type' => 'array',
        'items' => array('type' => 'integer'),
      ),
    ),
    'sanitize_callback' => 'absint',
    'auth_callback' => function() {
       return current_user_can('edit_posts');
    },
  ));
}

// Meta box for team leaders
function teams_add_leaders_meta_box() {
  add_meta_box(
    'team_leaders',
    'Team Leaders',
    'teams_leaders_meta_box_html',
    'teams',
    'side',
    'default'
  );
}

function teams_leaders_meta_box_html($post) {
  $saved_ids = get_post_meta($post->ID, 'team_leader_ids', false) ?: array();
  $saved_ids = array_map('intval', $saved_ids);

  $users = get_users(array('orderby' => 'display_name', 'order' => 'ASC'));

  wp_nonce_field('teams_save_leaders', 'teams_leaders_nonce');

  $view = GLOBE__PLUGIN_DIR . 'src/views/team_leaders.php';
  include( $view );
}

function teams_save_leaders($post_id) {
  // Nonce check
  if (
    ! isset($_POST['teams_leaders_nonce']) ||
    ! wp_verify_nonce($_POST['teams_leaders_nonce'], 'teams_save_leaders')
  ) {
    return;
  }

  // Don't save on autosave
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

  // Permission check
  if (! current_user_can('edit_post', $post_id)) return;

  // Delete existing values and re-save
  delete_post_meta($post_id, 'team_leader_ids');

  if (! empty($_POST['team_leader_ids'])) {
    foreach ($_POST['team_leader_ids'] as $user_id) {
      $clean_id = absint($user_id);
      if ($clean_id > 0) {
        add_post_meta($post_id, 'team_leader_ids', $clean_id);
      }
    }
  }
}

add_action('init', 'teams_post_type');
add_action('init', 'create_team_taxonomy', 0);
add_action('init', 'teams_register_leader_meta');
add_action('add_meta_boxes', 'teams_add_leaders_meta_box');
add_action('save_post_teams', 'teams_save_leaders');
