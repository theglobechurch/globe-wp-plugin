<?php

require_once( GLOBE__PLUGIN_DIR . 'src/lib/glb_media.php' );

function glb_add_import_page() {
  add_options_page( 'Import', 'Import from Old Globe Site', 'manage_options', 'globe_import', 'glb_render_plugin_import_page' );
}
add_action( 'admin_menu', 'glb_add_import_page' );

function glb_render_plugin_import_page() {

  if (isset($_POST['importType']) && $_POST['importType'] == "series") {
    importSeries();
  }

  ?>
  <h1>Globe Importer</h1>

  <form action="" method="post">
    <input type="hidden" name="importType" value="series" />
    <input type="submit" value="Import Series" />
  </form>
  <?php
}

function importSeries() {
  echo "<h1>Importing series…</h1> <br />";
  $series = "https://www.globe.church/export/series";

  $curl = curl_init();

  curl_setopt($curl, CURLOPT_URL, $series);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_HEADER, false);

  $data = json_decode(curl_exec($curl));

  curl_close($curl);

  $glbMedia = new GlbMediaUpload();

  $added = 0;
  $skipped = 0;

  foreach ($data as $key => $series) {

    $term = term_exists($series->slug, 'sermon_series' );
    if ( $term !== 0 && $term !== null ) {
      $skipped ++;
      continue;
    }

    // Create new taxonomy
    $term = wp_insert_term(
      $series->title,
      'sermon_series',
      array(
        'description' => $series->description,
        'slug' => $series->slug,
      )
    );

    // Format the dates…
    $date = date_parse($series->start_date);
    $start = sprintf("%04d-%02d-%02d", $date["year"], $date["month"], $date["day"]);

    $date = date_parse($series->end_date);
    $end = sprintf("%04d-%02d-%02d", $date["year"], $date["month"], $date["day"]);

    // Upload media
    $media_id = $glbMedia->saveMedia($series->image);

    // Fill additional fields
    update_term_meta(
      $term['term_id'],
      'sermon_startDate',
      $start
    );
    update_term_meta(
      $term['term_id'],
      'sermon_endDate',
      $end
    );
    update_term_meta(
      $term['term_id'],
      'glb_sermon_artwork',
      $media_id
    );

    $added++;
  }

  ?>
    Added <?php echo $added ?> series<br />
    Skipped <?php echo $skipped ?> series because they already existed<br />
  <?php

}
