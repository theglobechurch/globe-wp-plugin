<tr class="form-field">
  <th>
    <label for="sermon_startDate">Series Start Date</label>
  </th>
  <td>
    <input type="date" name="sermon_startDate" id="sermon_startDate" value="<?php echo esc_attr( $sermon_startDate ) ?>" />
    <p>When does the series start?</p>
  </td>
</tr>

<tr class="form-field">
  <th>
    <label for="sermon_endDate">Series End Date</label>
  </th>
  <td>
    <input type="date" name="sermon_endDate" id="sermon_endDate" value="<?php echo esc_attr( $sermon_endDate ) ?>" />
    <p>When does the series end?</p>
  </td>
</tr>

<tr class="form-field">
  <th>
    <label>Sermon Artwork</label>
  </th>
  <td>
    <?php if( isset($image_id) && $image = wp_get_attachment_image_url( $image_id, 'medium' ) ) : ?>
      <a href="#" class="glb-upload">
        <img src="<?php echo esc_url( $image ) ?>" alt="" />
      </a><br />
      <a href="#" class="glb-remove">Remove image</a>
      <input type="hidden" name="glb_sermon_artwork" value="<?php echo absint( $image_id ) ?>">
    <?php else : ?>
      <a href="#" class="button glb-upload">Upload image</a>
      <a href="#" class="glb-remove" style="display:none">Remove image</a>
      <input type="hidden" name="glb_sermon_artwork" value="">
    <?php endif; ?>
  </td>
</tr>
