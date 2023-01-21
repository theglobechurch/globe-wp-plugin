<div class="glb_peopleList">
<?php foreach ($users as $person) : ?>
  <?php $personMeta = get_user_meta( $person->ID); ?>

  <?php if (isset($personMeta['glb_profilePage'][0])) : ?>
    <a href="/people/<?php echo $person->user_login; ?>">
  <?php else : ?>
    <div>
  <?php endif; ?>

    <?php if( isset($personMeta['glb_userAvatar']) && $image = wp_get_attachment_image_url( $personMeta['glb_userAvatar'][0], 'thumbnail' ) ) : ?>
      <img src="<?php echo esc_url( $image ) ?>" alt="<?php echo $person->display_name ?> photo" loading="lazy" decoding="async" />
    <?php endif; ?>

    <p><?php echo $person->display_name ?></p>

    <?php if (isset($personMeta['glb_profilePage'][0])) : ?>
      </a>
    <?php else : ?>
      </div>
    <?php endif; ?>
<?php endforeach; ?>
</div>
