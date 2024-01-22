<div class="postbox">
    <table class="form-table">
        <tbody>
        <tr valign="top">
            <th scope="row" colspan="2"><h3><?php _e('User Role Exclusions', 'wp-statistics'); ?> <a href="#" class="wps-tooltip" title="<?php _e('Exclude specific user roles from data collection.', 'wp-statistics') ?>"><i class="wps-tooltip-icon"></i></a></h3></th>
        </tr>

        <?php
        $role_option_list = '';
        foreach (\WP_STATISTICS\User::get_role_list() as $role) {
            $store_name       = 'exclude_' . str_replace(" ", "_", strtolower($role));
            $option_name      = 'wps_' . $store_name;
            $role_option_list .= $option_name . ',';

            $translated_role_name = translate_user_role($role);
            ?>

            <tr valign="top">
                <th scope="row"><label for="<?php echo esc_attr($option_name); ?>"><?php echo esc_attr($translated_role_name); ?></label>
                </th>
                <td>
                    <input id="<?php echo esc_attr($option_name); ?>" type="checkbox" value="1" name="<?php echo esc_attr($option_name); ?>" <?php echo WP_STATISTICS\Option::get($store_name) == true ? "checked='checked'" : ''; ?>><label for="<?php echo esc_attr($option_name); ?>"><?php _e('Exclude', 'wp-statistics'); ?></label>
                    <p class="description"><?php echo sprintf(__('Exclude data collection for users with the %s role.', 'wp-statistics'), esc_attr($translated_role_name)); ?></p>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<div class="postbox">
    <table class="form-table">
        <tbody>
        <tr valign="top">
            <th scope="row" colspan="2"><h3><?php _e('IP Exclusions', 'wp-statistics'); ?> <a href="#" class="wps-tooltip" title="<?php _e('Specify which IP addresses or ranges should be excluded from statistics.', 'wp-statistics') ?>"><i class="wps-tooltip-icon"></i></a></h3></th>
        </tr>

        <tr valign="top">
            <th scope="row"><label for="wps_exclude_ip"><?php _e('Excluded IP Address List', 'wp-statistics'); ?></label></th>
            <td>
                <textarea id="wps_exclude_ip" name="wps_exclude_ip" rows="5" cols="60" class="code" dir="ltr"><?php echo esc_textarea(WP_STATISTICS\Option::get('exclude_ip')); ?></textarea>
                <p class="description"><?php echo __('Specify the IP addresses you want to exclude. Enter one IP address or range per line.', 'wp-statistics'); ?></p>
                <p class="description"><?php echo __('For IPv4 addresses, both 192.168.0.0/24 and 192.168.0.0/255.255.255.0 formats are acceptable. To specify an IP address, use a subnet value of 32 or 255.255.255.255.', 'wp-statistics'); ?></p>
                <p class="description"><?php echo __('For IPv6 addresses, use the fc00::/7 format.', 'wp-statistics'); ?></p>
                <?php
                foreach (\WP_STATISTICS\IP::$private_SubNets as $ip) {
                    ?>
                    <a onclick="var wps_exclude_ip = getElementById('wps_exclude_ip'); if( wps_exclude_ip != null ) { wps_exclude_ip.value = jQuery.trim( wps_exclude_ip.value + '\n<?php echo esc_attr($ip); ?>' ); }" class="button"><?php _e('Add', 'wp-statistics'); ?><?php echo esc_attr($ip); ?></a>
                    <?php
                }
                ?>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<div class="postbox">
    <table class="form-table">
        <tbody>
        <tr valign="top">
            <th scope="row" colspan="2"><h3><?php _e('Robot Exclusions', 'wp-statistics'); ?> <a href="#" class="wps-tooltip" title="<?php _e('Define bots and spiders to exclude from your website\'s statistics.', 'wp-statistics') ?>"><i class="wps-tooltip-icon"></i></a></h3></th>
        </tr>

        <tr valign="top">
            <th scope="row"><label for="wps_robotlist"><?php _e('Robot List', 'wp-statistics'); ?></label></th>
            <td>
                    <textarea name="wps_robotlist" class="code textarea-input-reset" dir="ltr" rows="10" cols="60" id="wps_robotlist"><?php
                        $robotlist = WP_STATISTICS\Option::get('robotlist');
                        if ($robotlist == '') {
                            $robotlist = WP_STATISTICS\Helper::get_robots_list();
                            update_option('wps_robotlist', $robotlist);
                        }
                        echo esc_textarea($robotlist);
                        ?>
                    </textarea>
                <p class="description"><?php echo __('Enter robot agents to exclude. One agent name per line, minimum four characters.', 'wp-statistics'); ?></p>
                <a onclick="var wps_robotlist = getElementById('wps_robotlist'); wps_robotlist.value = '<?php echo str_replace(array("\r\n", "\n", "\r"), '\n', esc_html(\WP_STATISTICS\Helper::get_robots_list())); ?>';" class="button"><?php _e('Reset to Default', 'wp-statistics'); ?></a>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row">
                <label for="wps_robot_threshold"><?php _e('Robot Visit Threshold', 'wp-statistics'); ?></label>
            </th>
            <td>
                <input id="wps_robot_threshold" type="text" size="5" name="wps_robot_threshold" value="<?php echo esc_attr(WP_STATISTICS\Option::get('robot_threshold')); ?>">
                <p class="description"><?php echo __('Set a threshold for daily robot visits. Robots exceeding this number daily will be identified as bots.', 'wp-statistics'); ?></p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row">
                <label for="force_robot_update"><?php _e('Force Robot List Update After Upgrades', 'wp-statistics'); ?></label>
            </th>
            <td>
                <input id="force_robot_update" type="checkbox" value="1" name="wps_force_robot_update" <?php echo WP_STATISTICS\Option::get('force_robot_update') == true ? "checked='checked'" : ''; ?>><label for="force_robot_update"><?php _e('Enable', 'wp-statistics'); ?></label>
                <p class="description"><?php echo sprintf(__('Reset the robot list to default after WP Statistics updates. Custom entries will be lost if enabled.', 'wp-statistics'), $role); ?></p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row"><label for="use_honeypot"><?php _e('Activate Honey Pot Protection', 'wp-statistics'); ?></label></th>
            <td>
                <input id="use_honeypot" type="checkbox" value="1" name="wps_use_honeypot" <?php echo WP_STATISTICS\Option::get('use_honeypot') == true ? "checked='checked'" : ''; ?>><label for="wps_use_honeypot"><?php _e('Enable', 'wp-statistics'); ?></label>
                <p class="description"><?php echo __('Turn on Honey Pot to detect and filter out bots. This adds a hidden trap for malicious automated scripts.', 'wp-statistics'); ?></p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row"><label for="honeypot_postid"><?php _e('Honey Pot Trap Page', 'wp-statistics'); ?></label></th>
            <td>
                <?php wp_dropdown_pages(array('show_option_none' => __('Please select', 'wp-statistics'), 'id' => 'honeypot_postid', 'name' => 'wps_honeypot_postid', 'selected' => WP_STATISTICS\Option::get('honeypot_postid'))); ?>
                <p class="description"><?php echo __('Choose an existing Honey Pot trap page from the list or set up a new one to catch bots.', 'wp-statistics'); ?></p>
                <p><input id="wps_create_honeypot" type="checkbox" value="1" name="wps_create_honeypot"> <label for="wps_create_honeypot"><?php _e('Create a new Honey Pot page', 'wp-statistics'); ?></label></p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row">
                <label for="corrupt_browser_info"><?php _e('Identify Incomplete Browser Data as Bot Activity', 'wp-statistics'); ?></label>
            </th>
            <td>
                <input id="corrupt_browser_info" type="checkbox" value="1" name="wps_corrupt_browser_info" <?php echo WP_STATISTICS\Option::get('corrupt_browser_info') == true ? "checked='checked'" : ''; ?>><label for="corrupt_browser_info"><?php _e('Enable', 'wp-statistics'); ?></label>
                <p class="description"><?php echo __('Enable this to classify visitors with incomplete browser details, such as a missing IP or user agent, as bots. This helps in preventing skewed analytics from corrupt data.', 'wp-statistics'); ?></p>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<div class="postbox">
    <table class="form-table">
        <tbody>
        <tr valign="top">
            <th scope="row" colspan="2"><h3><?php _e('GeoIP Exclusions', 'wp-statistics'); ?> <a href="#" class="wps-tooltip" title="<?php _e('Filter out or specifically include visits from certain countries.', 'wp-statistics') ?>"><i class="wps-tooltip-icon"></i></a></h3></th>
        </tr>

        <tr valign="top">
            <th scope="row"><label for="wps_excluded_countries"><?php _e('Excluded Countries', 'wp-statistics'); ?></label></th>
            <td>
                <textarea id="wps_excluded_countries" name="wps_excluded_countries" rows="5" cols="50" class="code" dir="ltr"><?php echo esc_textarea(WP_STATISTICS\Option::get('excluded_countries')); ?></textarea>
                <p class="description"><?php echo __('Enter country codes to exclude from stats. Use \'000\' for unknown countries.', 'wp-statistics') ?></p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row"><label for="wps_included_countries"><?php _e('Included Countries', 'wp-statistics'); ?></label></th>
            <td>
                <textarea id="wps_included_countries" name="wps_included_countries" rows="5" cols="50" class="code" dir="ltr"><?php echo esc_textarea(WP_STATISTICS\Option::get('included_countries')); ?></textarea>
                <p class="description"><?php echo __('Specify country codes to include in stats. \'000\' means unknown countries.', 'wp-statistics'); ?></p>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<div class="postbox">
    <table class="form-table">
        <tbody>
        <tr valign="top">
            <th scope="row" colspan="2"><h3><?php _e('URL Exclusions', 'wp-statistics'); ?> <a href="#" class="wps-tooltip" title="<?php _e('Choose specific site URLs to keep out of the statistics.', 'wp-statistics') ?>"><i class="wps-tooltip-icon"></i></a></h3></th>
        </tr>

        <tr valign="top">
            <th scope="row"><label for="wps-exclude-loginpage"><?php _e('Excluded Login Page', 'wp-statistics'); ?></label></th>
            <td>
                <input id="wps-exclude-loginpage" type="checkbox" value="1" name="wps_exclude_loginpage" <?php echo WP_STATISTICS\Option::get('exclude_loginpage') == true ? "checked='checked'" : ''; ?>><label for="wps-exclude-loginpage"><?php _e('Exclude', 'wp-statistics'); ?></label>
                <p class="description"><?php _e('Prevent the login page from being counted as a hit.', 'wp-statistics'); ?></p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><label for="wps-exclude-feeds"><?php _e('Excluded RSS Feeds', 'wp-statistics'); ?></label></th>
            <td>
                <input id="wps-exclude-feeds" type="checkbox" value="1" name="wps_exclude_feeds" <?php echo WP_STATISTICS\Option::get('exclude_feeds') == true ? "checked='checked'" : ''; ?>><label for="wps-exclude-feeds"><?php _e('Exclude', 'wp-statistics'); ?></label>
                <p class="description"><?php _e('Stop RSS feeds from being recorded as hits.', 'wp-statistics'); ?></p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><label for="wps-exclude-404s"><?php _e('Excluded 404 Pages', 'wp-statistics'); ?></label></th>
            <td>
                <input id="wps-exclude-404s" type="checkbox" value="1" name="wps_exclude_404s" <?php echo WP_STATISTICS\Option::get('exclude_404s') == true ? "checked='checked'" : ''; ?>><label for="wps-exclude-404s"><?php _e('Exclude', 'wp-statistics'); ?></label>
                <p class="description"><?php _e('Exclude URLs that return a \'404 - Not Found\' message.', 'wp-statistics'); ?></p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><label for="wps_excluded_urls"><?php _e('Excluded URLs', 'wp-statistics'); ?></label></th>
            <td>
                <textarea id="wps_excluded_urls" name="wps_excluded_urls" rows="5" cols="80" class="code" dir="ltr"><?php echo esc_textarea(WP_STATISTICS\Option::get('excluded_urls')); ?></textarea>
                <p class="description"><?php echo __('Enter specific URLs to exclude. URL parameters aren\'t considered', 'wp-statistics'); ?></p>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<div class="postbox">
    <table class="form-table">
        <tbody>
        <tr valign="top">
            <th scope="row" colspan="2"><h3><?php _e('Host Exclusions', 'wp-statistics'); ?> <a href="#" class="wps-tooltip" title="<?php _e('Filter out visits from specific hosts.', 'wp-statistics') ?>"><i class="wps-tooltip-icon"></i></a></h3></th>
        </tr>

        <tr valign="top">
            <th scope="row"><label for="wps_excluded_hosts"><?php _e('Excluded Hosts', 'wp-statistics'); ?></label></th>
            <td>
                <textarea id="wps_excluded_hosts" name="wps_excluded_hosts" rows="5" cols="80" class="code" dir="ltr"><?php echo esc_textarea(WP_STATISTICS\Option::get('excluded_hosts')); ?></textarea>
                <p class="description"><?php echo __('Provide host names to exclude. Relies on cached IP, not live DNS lookup.', 'wp-statistics'); ?></p><br>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<div class="postbox">
    <table class="form-table">
        <tbody>
        <tr valign="top">
            <th scope="row" colspan="2"><h3><?php _e('General Exclusions', 'wp-statistics'); ?></h3></th>
        </tr>

        <tr valign="top">
            <th scope="row"><label for="wps-exclusions"><?php _e('Enable Record Exclusions', 'wp-statistics'); ?></label></th>
            <td>
                <input id="wps-exclusions" type="checkbox" value="1" name="wps_record_exclusions" <?php echo WP_STATISTICS\Option::get('record_exclusions') == true ? "checked='checked'" : ''; ?>><label for="wps-exclusions"><?php _e('Enable', 'wp-statistics'); ?></label>
                <p class="description"><?php echo __('Maintain a log of all excluded hits for insight into exclusions.', 'wp-statistics') ?></p>
            </td>
        </tr>
        </tbody>
    </table>
</div>

<?php submit_button(__('Update', 'wp-statistics'), 'primary', 'submit', '', array('OnClick' => "var wpsCurrentTab = getElementById('wps_current_tab'); wpsCurrentTab.value='exclusions-settings'")); ?>