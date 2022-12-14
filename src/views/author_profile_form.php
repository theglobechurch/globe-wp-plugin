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

</table>
