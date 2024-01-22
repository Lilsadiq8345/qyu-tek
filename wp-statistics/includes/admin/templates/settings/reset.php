<div class="postbox">
    <table class="form-table">
        <tbody>
        <tr valign="top">
            <th scope="row" colspan="2"><h3><?php _e('Restore Default Settings', 'wp-statistics'); ?> <a href="#" class="wps-tooltip" title="<?php _e('Here you can revert WP Statistics to its original configuration. Please proceed with caution as changes made here are irreversible.', 'wp-statistics') ?>"><i class="wps-tooltip-icon"></i></a></h3></th>
        </tr>

        <tr valign="top">
            <th scope="row">
                <label for="reset-plugin"><?php _e('Reset Options', 'wp-statistics'); ?></label>
            </th>

            <td>
                <input id="reset-plugin" type="checkbox" name="wps_reset_plugin">
                <label for="reset-plugin"><?php _e('Reset', 'wp-statistics'); ?></label>
                <p class="description"><?php _e('Revert all user-specific and global configurations to the WP Statistics default settings, preserving your existing data.', 'wp-statistics'); ?></p>
                <p class="description"><?php _e('<b>Caution</b>: This change is irreversible.', 'wp-statistics'); ?></p>
                <p class="description"><?php _e('<b>For multisite users</b>: Every site within the network will return to the default settings.', 'wp-statistics'); ?></p>
            </td>
        </tr>
        </tbody>
    </table>
</div>

<?php submit_button(__('Restore Defaults', 'wp-statistics'), 'primary', 'submit', '', array('OnClick' => "var wpsCurrentTab = getElementById('wps_current_tab'); wpsCurrentTab.value='reset-settings'")); ?>
