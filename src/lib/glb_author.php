<?php
require_once( GLOBE__PLUGIN_DIR . 'src/lib/glb_media.php' );

class GlbAuthor {

  public function __construct() {}

  public function addAuthor($authorData) {
    $authorId = $this->checkAuthorExists($authorData->slug);

    if ($authorId) {
      return $authorId;
    }

    // Insert User
    $pwlength = 24;
    $authorId = wp_insert_user(
      array(
        'user_login' => $authorData->slug,
        'user_pass' => substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($pwlength/strlen($x)) )),1,$pwlength),
        'user_nicename' => $authorData->name,
        'display_name' => $authorData->name,
        'nickname' => $authorData->name,
        'description' => $authorData->biography,
        'role' => "contributor"
      )
    );

    // Add image
    // Upload the image and get a $media_id
    if ($authorData->img) {
      $glbMedia = new GlbMediaUpload();
      $media_id = $glbMedia->saveMedia($authorData->img, $authorData->name);
      update_user_meta( $authorId, 'glb_userAvatar', $media_id );
    }

    return $authorId;
  }

  private function checkAuthorExists($slug) {
    $users = get_users(
      array(
        'login' => $slug
      )
    );

    if (count($users) == 0) {
      return null;
    }

    return $users[0]->ID;
  }
}
