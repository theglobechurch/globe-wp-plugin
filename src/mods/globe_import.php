<?php

require_once( GLOBE__PLUGIN_DIR . 'src/lib/glb_media.php' );
require_once( GLOBE__PLUGIN_DIR . 'src/lib/glb_author.php' );

function glb_add_import_page() {
  add_options_page( 'Import', 'Import from Old Globe Site', 'manage_options', 'globe_import', 'glb_render_plugin_import_page' );
}
add_action( 'admin_menu', 'glb_add_import_page' );

function glb_render_plugin_import_page() {

  if (isset($_POST['importType']) && $_POST['importType'] == "series") {
    importSeries();
  }

  if (isset($_POST['importType']) && $_POST['importType'] == "sermons") {
    importSermons();
  }

  ?>
  <h1>Globe Importer</h1>

  <div>
      <form action="" method="post">
      <input type="hidden" name="importType" value="series" />
      <input type="submit" value="Import Series" />
    </form>
  </div>

  <div>
      <p>Import series before sermons… otherwise sadness</p>
      <form action="" method="post">
      <input type="hidden" name="importType" value="sermons" />
      <input type="submit" value="Import Sermons" />
    </form>
  </div>
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

function importSermons() {
  echo "<h1>Importing Sermons…</h1> <br />";
  $sermons = "https://www.globe.church/export/audio";

  $curl = curl_init();

  curl_setopt($curl, CURLOPT_URL, $sermons);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_HEADER, false);

  $data = json_decode(curl_exec($curl));

  curl_close($curl);

  $authorTool = new GlbAuthor();
  $glbMedia = new GlbMediaUpload();

  $added = 0;
  $skipped = 0;

  foreach ($data as $key => $sermon) {

    // CHECK IF THE SERMON HAS ALREADY BEEN IMPORTED
    $query = new WP_Query(
      array(
        'name' => $sermon->slug,
        'post_type' => 'sermon',
      )
    );
    if( $query->have_posts() ){
      echo 'Skipped: ' . $sermon->name . '<br />';
      $skipped++;
      continue;
    }

    // Get series
    $series = term_exists($sermon->series->slug, 'sermon_series');

    // Create author
    $authorId = $authorTool->addAuthor($sermon->author);

    // Upload MP3
    $media_id = $glbMedia->saveMedia($sermon->audio, "Sermon: " . $sermon->name);

    // Create post
    $date = date_parse($sermon->date);
    $sermon_date = sprintf("%04d-%02d-%02d", $date["year"], $date["month"], $date["day"]);

    $post_id = wp_insert_post(
      array(
        'post_author' => $authorId,
        'post_date' => $sermon_date,
        'post_content' => $sermon->description,
        'post_title' => $sermon->name,
        'post_name' => $sermon->slug,
        'post_excerpt' => $sermon->description,
        'post_status' => 'publish',
        'post_type' => 'sermon',
        'comment_status' => 'closed'
      )
    );

    // Put the sermon into the right sermon series
    wp_set_post_terms( $post_id, [$series['term_id']], 'sermon_series' );

    // Set the custom fields; Sermon date, mp3, passage
    if ($sermon->passage->full) {
      update_post_meta($post_id, 'glb_sermon_passage', $sermon->passage->full);
    }
    update_post_meta($post_id, 'glb_sermon_date', $sermon_date);
    update_post_meta($post_id, 'glb_sermon_mp3', $media_id);

    echo 'Imported: ' . $sermon->name . '<br />';

    $added++;
  }

  ?>
    Added <?php echo $added ?> sermons<br />
    Skipped <?php echo $skipped ?> sermons because they already existed<br />
  <?php

}
