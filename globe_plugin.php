<?php

/*
Plugin Name: Globe CMS Mods
Plugin URI: http://globe.church
Description: General WP mods for The Globe Church
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

require_once( GLOBE__PLUGIN_DIR . 'globe_sermons.php' );
require_once( GLOBE__PLUGIN_DIR . 'globe_adminBar.php' );
require_once( GLOBE__PLUGIN_DIR . 'globe_login.php' );

function glb_include_js() {
  // WordPress media uploader scripts
  if ( ! did_action( 'wp_enqueue_media' ) ) {
    wp_enqueue_media();
  }
  $jsVer  = date("ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) . '_inc/glb_admin.js' ));

  wp_enqueue_script(
    'glb_admin',
    plugins_url( '_inc/glb_admin.js', __FILE__ ),
    array( 'jquery' ),
    $jsVer
  );
}

add_action( 'admin_enqueue_scripts', 'glb_include_js' );
