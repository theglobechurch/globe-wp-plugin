<?php
/* A lot of this code is cribbed from the import-xml-feed plugin
 * https://wordpress.org/plugins/import-xml-feed/
 */

class GlbMediaUpload {

  public function __construct() {

  }

  public function saveMedia($url, $title = null) {
    $media_id = $this->createMedia($url, $title);

    return $media_id;
  }

  private function createMedia($url, $title = null) {

    $upload_dir = wp_upload_dir();
    $filename = basename($url);

    if( wp_mkdir_p( $upload_dir['path'] ) ) {
      $file = $upload_dir['path'] . '/' . $filename;
    } else {
      $file = $upload_dir['basedir'] . '/' . $filename;
    }

    if( !file_exists( $file ) ) {
      $mediaAsset = file_get_contents($url);
      file_put_contents( $file, $mediaAsset );

      $wp_filetype = wp_check_filetype( $filename, null );

      $filetitle = $title ?? $filename;

      $attachment = array(
          'post_mime_type'    =>  $wp_filetype['type'],
          'post_title'        =>  sanitize_file_name($filetitle),
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
                'key'     => '_wp_attached_file',
            ),
        )
    );
    $query = new WP_Query( $query_args );

    if ( $query->have_posts() ) {
      $attachment_id = $query->posts[0];
      // foreach ( $query->posts as $post_id ) :
      //   $meta = wp_get_attachment_metadata( $post_id );
      //   $original_file = basename( $meta['file'] );
      //   $cropped_image_files = wp_list_pluck( $meta['sizes'], 'file' );
      //   if ( $original_file === $file || in_array( $file, $cropped_image_files ) ) :
      //     $attachment_id = $post_id;
      //     break;
      //   endif;
      // endforeach;
      wp_reset_query();
      wp_reset_postdata();
    }
    return $attachment_id;
  }

}
