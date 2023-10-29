<?php

// Outputs to /wp-json/globe/v1/additionals
add_action( 'rest_api_init', function () {
  register_rest_route( 'globe/v1', '/additionals', array(
    'methods' => 'GET',
    'callback' => 'glb_api_additionalFields'
  ));
});

function glb_api_additionalFields() {
  return array('GNDN' => 'work in progress');
}
