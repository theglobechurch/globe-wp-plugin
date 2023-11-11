<?php

// Outputs to /wp-json/globe/v1/additionals
add_action( 'rest_api_init', function () {
  register_rest_route( 'globe/v1', '/additionals', array(
    'methods' => 'GET',
    'callback' => 'glb_api_additionalFields'
  ));
});

function glb_api_additionalFields() {
  $savedMeta = get_option( 'glb_options' );
  $now = new DateTime();

  $additionals = array(
    'registeredAddress' => $savedMeta['postal_address'],
    'twitter' => $savedMeta['twitter'],
    'instagram' => $savedMeta['instagram'],
    'overrides' => null
  );

  // If the override date is set and hasn't passed
  if (
    isset($savedMeta['sunday_override_end']) &&
    $savedMeta['sunday_override_end'] != "" &&
    $now < new DateTime($savedMeta['sunday_override_end'])
  ) {
    $additionals['overrides'] = array(
      'serviceTime' => $savedMeta['sunday_time'] ? $savedMeta['sunday_time'] : null,
      'location' => $savedMeta['sunday_location'] ? $savedMeta['sunday_location'] : null,
      'nearestUnderground' => $savedMeta['sunday_location_tube'] ? $savedMeta['sunday_location_tube'] : null,
    );
  }

  return $additionals;
}
