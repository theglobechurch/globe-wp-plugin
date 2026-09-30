<?php
/**
 * Link rows for the link tree meta box.
 * Expects $links (array of saved links). The image picker reuses the
 * .glb-upload / .glb-remove handlers in assets/glb_admin.js
 */
function glb_link_tree_row($index, $link) {
  $image_id = absint($link['image_id'] ?? 0);
  $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'medium') : '';
  ?>
  <div class="glb-tree-link">
    <div class="glb-tree-link__handle" title="Drag to reorder">&#9776;</div>
    <div class="glb-tree-link__fields">
      <p>
        <label>Title<br>
          <input type="text" class="widefat" name="glb_tree_links[<?php echo esc_attr($index); ?>][title]" value="<?php echo esc_attr($link['title'] ?? ''); ?>">
        </label>
      </p>
      <p>
        <label>URL <span class="description">(full address, or a path on this site such as /about)</span><br>
          <input type="text" class="widefat" name="glb_tree_links[<?php echo esc_attr($index); ?>][url]" value="<?php echo esc_attr($link['url'] ?? ''); ?>">
        </label>
      </p>
      <p>
        <label>Description<br>
          <textarea class="widefat" rows="2" name="glb_tree_links[<?php echo esc_attr($index); ?>][description]"><?php echo esc_textarea($link['description'] ?? ''); ?></textarea>
        </label>
      </p>
    </div>
    <div class="glb-tree-link__image">
      <a href="#" class="glb-upload<?php echo $image_url ? '' : ' button'; ?>"><?php
        echo $image_url ? '<img src="' . esc_url($image_url) . '" alt="" class="widefat">' : 'Upload image';
      ?></a>
      <a href="#" class="glb-remove"<?php echo $image_url ? '' : ' style="display:none"'; ?>>Remove image</a>
      <input type="hidden" name="glb_tree_links[<?php echo esc_attr($index); ?>][image_id]" value="<?php echo $image_id ? esc_attr($image_id) : ''; ?>">
    </div>
    <div class="glb-tree-link__delete">
      <a href="#" class="glb-tree-link-delete submitdelete">Delete link</a>
    </div>
  </div>
  <?php
}
?>
<style>
  .glb-tree-link { display: grid; grid-template-columns: 24px 1fr 180px; gap: 0 16px; padding: 12px; margin-bottom: 12px; border: 1px solid #c3c4c7; background: #fff; }
  .glb-tree-link__handle { cursor: move; color: #8c8f94; font-size: 18px; line-height: 1.6; }
  .glb-tree-link__image img { max-width: 100%; height: auto; display: block; margin-bottom: 4px; }
  .glb-tree-link__delete { grid-column: 2 / 4; text-align: right; }
  .glb-tree-link.ui-sortable-helper { box-shadow: 0 2px 8px rgba(0,0,0,.2); }
</style>

<div id="glb-tree-links">
  <?php foreach (array_values($links) as $i => $link) { glb_link_tree_row($i, $link); } ?>
</div>

<p><button type="button" class="button button-primary" id="glb-tree-link-add">Add link</button></p>

<script type="text/template" id="glb-tree-link-template">
  <?php glb_link_tree_row('__INDEX__', array()); ?>
</script>
