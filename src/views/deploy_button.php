<h1>Netlify deploy status:</h1>
<a href="https://app.netlify.com/sites/tgc-static/deploys" target="_blank" rel="noopener">
  <img src="https://camo.githubusercontent.com/6d434c1adce62d09b602d714707605111b2243dd41132cc57968bfc726a5279b/68747470733a2f2f6170692e6e65746c6966792e636f6d2f6170692f76312f6261646765732f65666534623165332d616263332d346434302d386333632d6564323766643833363563302f6465706c6f792d737461747573" alt="Netlify deploy status" />
</a>

<h2>Deploy the content changes:</h2>

<p>⌛ Deploys can take a couple of minutes before they are published. Please be patient.</p>

<form method="post">
  <input type="hidden" name="page" value="glb_deploy" />
  <input type="hidden" name="deployit" value="1" />
  <p>
    <input type="submit" value="Deploy" class="button" />
  </p>
</form>
<p>
  <a href="https://globe.church" rel="noopener">Go to live site</a>.
</p>
<hr />
<h2>Deploy history</h2>
<p><em>Last ten, see the full history on <a href="https://app.netlify.com/sites/tgc-static/deploys" target="_blank" rel="noopener">Netlify</a>.</em></p>

<table class="wp-list-table widefat fixed striped table-view-list">
<?php
  $loop = 0;
  foreach($deployData as $deploy) {
    if($deploy->context == "production" && $loop < 10) {
?>
  <tr>

    <td>
    <?php if($deploy->screenshot_url) {?>
        <img style="padding-right: 1rem; float: left; width: 33%; max-width: 150px;" src="<?php echo $deploy->screenshot_url ?>" alt="Deploy preview" loading="lazy" decoding="async" />
      <?php } ?>
      <strong><?php echo $deploy->state; ?></strong><br />
      <?php echo $deploy->title ?>
    </td>

    <td>
      Published at: <?php echo $deploy->published_at; ?><br />
      (<?php echo $deploy->deploy_time; ?>s)
    </td>
  </tr>

<?php
      $loop++;
    } // End if
  } // End foreach
?>
</table>
