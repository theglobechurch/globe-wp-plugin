<?php

/*
Plugin Name: Globe CMS Mods
Plugin URI: http://globe.church
Description: General WP mods for The Globe Church. If you disable this everything will break horribly. You've been warned!
Author: James Doc
Version: 0.0.1
Author URI: https://jamesdoc.com
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
  echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
  exit;
}

define( 'GLOBE__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once( GLOBE__PLUGIN_DIR . 'src/mods/globe_sermons.php' );
require_once( GLOBE__PLUGIN_DIR . 'src/mods/globe_adminBar.php' );
require_once( GLOBE__PLUGIN_DIR . 'src/mods/globe_login.php' );
require_once( GLOBE__PLUGIN_DIR . 'src/mods/globe_comments.php' );
require_once( GLOBE__PLUGIN_DIR . 'src/mods/globe_pluginSettings.php' );
require_once( GLOBE__PLUGIN_DIR . 'src/mods/globe_peopleShortcode.php' );
require_once( GLOBE__PLUGIN_DIR . 'src/mods/globe_additionalFields.php' );
require_once( GLOBE__PLUGIN_DIR . 'src/mods/globe_deploy.php' );
require_once( GLOBE__PLUGIN_DIR . 'src/mods/globe_userProfile.php' );
require_once( GLOBE__PLUGIN_DIR . 'src/mods/globe_posts.php' );
require_once( GLOBE__PLUGIN_DIR . 'src/mods/globe_api_additionalsEndpoint.php' );


function maintenance_mode() {

  // Ignore if it's the JSON API…
  if (strpos( $_SERVER['REQUEST_URI'], 'wp-json')) {
    return;
  }

  // Ignore if it's the Login page
  if (is_login()) {
    return;
  }

  // Ignore if user is logged in…
  if (is_user_logged_in()) {
    return;
  }

  $view = GLOBE__PLUGIN_DIR . 'src/views/maintenance_mode.php';
  include( $view );
  die();
}
add_action('init', 'maintenance_mode');

// Can be removed after init import:
// require_once( GLOBE__PLUGIN_DIR . 'src/mods/globe_import.php' );

function glb_include_js() {
  // WordPress media uploader scripts
  if ( ! did_action( 'wp_enqueue_media' ) ) {
    wp_enqueue_media();
  }
  $jsVer  = date("ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) . 'assets/glb_admin.js' ));

  wp_enqueue_script(
    'glb_admin',
    plugins_url( 'assets/glb_admin.js', __FILE__ ),
    array( 'jquery' ),
    $jsVer
  );

  // Add Gut editor
  $asset_file = include( plugin_dir_path( __FILE__ ) . 'build/index.asset.php');
  wp_enqueue_script(
    'glb_gut_editor',
    plugins_url( 'build/index.js', __FILE__ ),
    $asset_file['dependencies'],
    $asset_file['version']
  );
}
add_action( 'admin_enqueue_scripts', 'glb_include_js' );
