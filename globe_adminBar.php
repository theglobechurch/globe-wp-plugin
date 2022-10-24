<?php

/**
 * Add go-to live site button to the Wordpress Admin Bar
 *
 * @param [type] $wp_admin_bar
 * @return void
 */
function glb_admin_bar_goToLive($wp_admin_bar) {
  $args = array(
    'title' => '<span class="ab-icon dashicons dashicons-admin-site"></span> <span class="ab-label">Go To globe.church</span>',
    'parent' => 'top-secondary',
    'group' => null,
    'href' => 'https://www.globe.church',
    'meta' => array(
      'title' => 'Go To Live Site'
    )
  );
  $wp_admin_bar->add_node($args);
}

/**
 * Add deploy/publish button to the Wordpress Admin Bar
 *
 * @param [type] $wp_admin_bar
 * @return void
 */
function glb_admin_bar_deploy($wp_admin_bar) {
  $args = array(
    'title' => '<span class="ab-icon dashicons dashicons-cloud-upload"></span> <span class="ab-label">Publish content</span>',
    'group' => null,
    'href' => '/',
    'meta' => array(
      'title' => 'Publish content'
    )
  );
  $wp_admin_bar->add_node($args);
}

add_action('admin_bar_menu', 'glb_admin_bar_goToLive', 500);
add_action('admin_bar_menu', 'glb_admin_bar_deploy', 500);
