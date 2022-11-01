<?php

// Remove from the admin nav bar
function glb_adminbar_remove_comments(){
  global $wp_admin_bar;
  $wp_admin_bar->remove_menu('comments');
}

// Disable comment support in post types…
function glb_remove_comment_support() {
  $postTypes = ['post', 'page', 'sermons'];
  foreach ($postTypes as $postType) {
    remove_post_type_support($postType, 'comments');
  }
}

// Remove from admin menu
function gbl_remove_admin_menus() {
  remove_menu_page( 'edit-comments.php' );
}


add_action('init', 'glb_remove_comment_support', 100);
add_action('admin_menu', 'gbl_remove_admin_menus');
add_action('wp_before_admin_bar_render', 'glb_adminbar_remove_comments');
