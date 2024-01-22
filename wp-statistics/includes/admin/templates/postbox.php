<div id="postbox-container-1" class="postbox-container">
    <div class="meta-box-sortables">
        <div id="wps-plugins-support" class="postbox">
            <h2 class="hndle"><span><?php _e('Stay Connected', 'wp-statistics'); ?></span></h2>

            <div class="inside">
                <form action="https://dashboard.mailerlite.com/forms/421827/86962232715379904/share" method="get" target="_blank" novalidate>
                    <p><?php _e('Subscribe to our newsletter and be the first to know about new updates and features.', 'wp-statistics'); ?></p>
                    <input name="fields[email]" type="email" class="ltr" value="<?php bloginfo('admin_email'); ?>">
                    <input type="hidden" name="ml-submit" value="1">
                    <input type="hidden" name="anticsrf" value="true">
                    <input type="submit" value="<?php _e('Subscribe', 'wp-statistics'); ?>" name="subscribe" class="button">
                </form>
            </div>
        </div>
    </div>

    <?php
    // Check Disable PostBox
    if (apply_filters('wp_statistics_ads_setting_page_show', true) === false) {
        return;
    }

    $response      = wp_remote_get('https://wp-statistics.com/wp-json/plugin/postbox');
    $response_code = wp_remote_retrieve_response_code($response);

    if (!is_wp_error($response) and $response_code == '200') :
        $result = json_decode($response['body']);
        foreach ($result->items as $item) : ?>
            <div class="meta-box-sortables">
                <div class="inside-no-padding"><?php echo wp_kses_post($item->content); ?></div>
            </div>
        <?php
        endforeach;
    endif;
    ?>
</div>