<?php

function globePeopleShortcode($atts = array()) {

  $atts = array_change_key_case( (array) $atts, CASE_LOWER );


  if (!isset($atts['people'])) { return; }

  $userIds = [];

  // Strip out anything that isn't numeric
  foreach(explode(',', $atts['people']) as $id) {

    if (!is_numeric($id)) {
      continue;
    }

    $userIds[] = (int) $id;
  }

  $args = [
    'include' => $userIds,
    'fields'  => [ 'ID', 'user_login', 'display_name', 'user_url' ],
    'orderby' => 'display_name',
    'search_columns' => 'ID'
  ];

  $users = get_users( $args );

  $name = $userIds[0];
  $view = GLOBE__PLUGIN_DIR . 'src/views/shortcodes/people.php';
  ob_start();
  include( $view );
  $output = ob_get_clean();
  return $output;
}

add_shortcode('globePeople', 'globePeopleShortcode');
