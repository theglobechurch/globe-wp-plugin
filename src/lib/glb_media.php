<?php
/* A lot of this code is cribbed from the import-xml-feed plugin
 * https://wordpress.org/plugins/import-xml-feed/
 */

class GlbMediaUpload {

  public function __construct() {

  }

  public function saveMedia($url) {
    $media_id = $this->createMedia($url);

    return $media_id;
  }

  private function createMedia($url) {

    $upload_dir = wp_upload_dir();
    $mediaAsset = file_get_contents($url);
    $filename = basename($url);

    if( wp_mkdir_p( $upload_dir['path'] ) ) {
      $file = $upload_dir['path'] . '/' . $filename;
    } else {
      $file = $upload_dir['basedir'] . '/' . $filename;
    }

    if( !file_exists( $file ) ) {

      file_put_contents( $file, $mediaAsset );

      $wp_filetype = wp_check_filetype( $filename, null );

      $attachment = array(
          'post_mime_type'    =>  $wp_filetype['type'],
          'post_title'        =>  sanitize_file_name( $filename ),
          'post_content'      =>  '',
          'post_status'       =>  'inherit'
      );

      $attach_id = wp_insert_attachment( $attachment, $file);

      require_once(ABSPATH . 'wp-admin/includes/image.php');

      $attach_data = wp_generate_attachment_metadata( $attach_id, $file );

      wp_update_attachment_metadata( $attach_id, $attach_data );


    } else {
      $attach_id = $this->findMedia($file);
    }

    return $attach_id;
  }

  private function findMedia($filepath) {
    $attachment_id = 0;
    $dir = wp_upload_dir();
    $file = basename( $filepath );
    $query_args = array(
        'post_type'   => 'attachment',
        'post_status' => 'inherit',
        'fields'      => 'ids',
        'meta_query'  => array(
            array(
                'value'   => $file,
                'compare' => 'LIKE',
                'key'     => '_wp_attachment_metadata',
            ),
        )
    );
    $query = new WP_Query( $query_args );
    if ( $query->have_posts() ) {
      foreach ( $query->posts as $post_id ) :
        $meta = wp_get_attachment_metadata( $post_id );
        $original_file       = basename( $meta['file'] );
        $cropped_image_files = wp_list_pluck( $meta['sizes'], 'file' );
        if ( $original_file === $file || in_array( $file, $cropped_image_files ) ) :
          $attachment_id = $post_id;
          break;
        endif;
      endforeach;
      wp_reset_query();
      wp_reset_postdata();
    }
    return $attachment_id;
}

}
