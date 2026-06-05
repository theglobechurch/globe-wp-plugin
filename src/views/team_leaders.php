<select name="team_leader_ids[]" id="team_leader_ids" multiple style="min-height:120px; width: stretch; margin: 0">
    <?php foreach ($users as $user) : ?>
        <option value="<?php echo esc_attr($user->ID); ?>"
            <?php echo in_array($user->ID, $saved_ids) ? 'selected' : ''; ?>>
            <?php echo esc_html($user->display_name); ?>
        </option>
    <?php endforeach; ?>
</select>
<p class="description">Hold Ctrl / Cmd to select multiple leaders.</p>
