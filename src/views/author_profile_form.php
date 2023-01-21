<h3><?php _e("Extra profile information", "blank"); ?></h3>

<table class="form-table">

  <tr class="form-field">
    <th>
      <label>Profile image</label>
    </th>
    <td>
      <?php if( isset($image_id) && $image = wp_get_attachment_image_url( $image_id, 'medium' ) ) : ?>
        <a href="#" class="glb-upload">
          <img src="<?php echo esc_url( $image ) ?>" alt="" />
        </a><br />
        <a href="#" class="glb-remove">Remove image</a>
        <input type="hidden" name="glb_userAvatar" value="<?php echo absint( $image_id ) ?>">
      <?php else : ?>
        <a href="#" class="button glb-upload">Upload image</a>
        <a href="#" class="glb-remove" style="display:none">Remove image</a>
        <input type="hidden" name="glb_userAvatar" value="">
      <?php endif; ?>
    </td>
  </tr>

  <tr class="form-field">
    <th>
      <label>Profile page?</label>
      <p><small>If selected than a profile page will be generated for this user</small></p>
    </th>

    <td>
      <p>
        <label>
          <input type="radio" value="1" name="glb_profilePage" <?php if ($hasProfilePage == true) { echo "checked"; } ?> />
          Create profile page
        </label>
      </p>
      <p>
        <label>
          <input type="radio" value="0" name="glb_profilePage" <?php if ($hasProfilePage == false ) { echo "checked"; } ?> />
          Don't create profile page
        </label>
      </p>
    </td>
  </tr>

  <tr class="form-field">
    <th>
      <label>Detailed bio</label>
      <p><small>This is displayed on it's own page, for the short bio on blog posts update the <em>Biographical Info</em> field.</small></p>
    </th>
    <td>

      <?php
      wp_editor( $bigBio , 'glb_userBigBio', array(
            'wpautop'       => true,
            'tinymce'       => array(
              'toolbar1'      => 'bold,italic,underline,link,unlink'
            ),
            'media_buttons' => false,
            'textarea_name' => 'glb_userBigBio',
            'textarea_rows' => 10
        ) );
      ?>
    </td>
  </tr>

</table>
