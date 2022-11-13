<div class="wrap">
  <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
  <p>⚠️ Currently these fields don't output to the frontend… but they might in the future.</p>
  <form action="options.php" method="post">
    <?php
      settings_fields('glb');
      do_settings_sections('glb');
      submit_button('Save');
    ?>
  </form>
</div>
