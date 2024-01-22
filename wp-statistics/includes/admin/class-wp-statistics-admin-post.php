<?php

namespace WP_STATISTICS;

class Admin_Post
{
    /**
     * Hits Chart Post/page Meta Box
     *
     * @var string
     */
    public static $hits_chart_post_meta_box = 'post';

    /**
     * Admin_Post constructor.
     */
    public function __construct()
    {

        // Add Hits Column in All Admin Post-Type Wp_List_Table
        if (User::Access('read') and Option::get('pages') and !Option::get('disable_column')) {
            add_action('admin_init', array($this, 'init'));
        }

        // Add WordPress Post/Page Hit Chart Meta Box in edit Page
        if (User::Access('read') and !Option::get('disable_editor')) {
            add_action('add_meta_boxes', array($this, 'define_post_meta_box'));
        }

        // Add Post Hit Number in Publish Meta Box in WordPress Edit a post/page
        if (Option::get('pages') and Option::get('hit_post_metabox')) {
            add_action('post_submitbox_misc_actions', array($this, 'post_hit_misc'));
        }

        // Remove Post Hits when Post Id deleted
        add_action('deleted_post', array($this, 'modify_delete_post'));
    }

    /**
     * Init Hook
     */
    public function init()
    {
        foreach (Helper::get_list_post_type() as $type) {
            add_action('manage_' . $type . '_posts_columns', array($this, 'add_hit_column'), 10, 2);
            add_action('manage_' . $type . '_posts_custom_column', array($this, 'render_hit_column'), 10, 2);
            add_filter('manage_edit-' . $type . '_sortable_columns', array($this, 'modify_sortable_columns'));
        }
        add_filter('posts_clauses', array($this, 'modify_order_by_hits'), 10, 2);
    }

    /**
     * Add a custom column to post/pages for hit statistics.
     *
     * @param array $columns Columns
     * @return array Columns
     */
    public function add_hit_column($columns)
    {
        $columns['wp-statistics-post-hits'] = __('Visits', 'wp-statistics');
        return $columns;
    }

    /**
     * Render the custom column on the post/pages lists.
     *
     * @param string $column_name Column Name
     * @param string $post_id Post ID
     */
    public function render_hit_column($column_name, $post_id)
    {
        if ($column_name == 'wp-statistics-post-hits') {

            $post_type = Pages::get_post_type($post_id);

            $hitPostType = $post_type;
            if (Pages::checkIfPageIsHome($post_id)) {
                $hitPostType = 'home';
            }

            $hit_number = wp_statistics_pages('total', "", $post_id, null, null, $hitPostType);

            if ($hit_number) {
                $preview_chart_unlock_html = sprintf('<div class="wps-admin-column__unlock"><a href="%s" target="_blank"><span>%s</span><img src="%s"/></a></div>',
                    'https://wp-statistics.com/product/wp-statistics-mini-chart?utm_source=wp_statistics&utm_medium=display&utm_campaign=wordpress',
                    __('Unlock This Feature!', 'wp-statistics'),
                    WP_STATISTICS_URL . 'assets/images/mini-chart-posts-preview.png'
                );

                // Remove post_type_ from prefix of custom post type because of incompatibility with WP Statistics MiniChart
                $actual_post_type = $post_type;
                if (strpos($actual_post_type, "post_type_") === 0) {
                    $actual_post_type = substr($actual_post_type, strlen("post_type_"));
                }

                echo apply_filters("wp_statistics_before_hit_column_{$actual_post_type}", $preview_chart_unlock_html, $post_id, $post_type);

                echo sprintf('<a href="%s" class="wps-admin-column__link">%s</a>',
                    Menus::admin_url('pages', array('ID' => $post_id, 'type' => $post_type)),
                    number_format($hit_number)
                );
            }

        }
    }

    /**
     * Added Sortable Params
     *
     * @param $columns
     * @return mixed
     */
    public function modify_sortable_columns($columns)
    {
        $columns['wp-statistics-post-hits'] = 'hits';
        return $columns;
    }

    /**
     * Sort Post By Hits
     *
     * @param $clauses
     * @param $query
     */
    public function modify_order_by_hits($clauses, $query)
    {
        global $wpdb;

        // Check in Admin
        if (!is_admin()) {
            return;
        }

        // If order-by.
        if (isset($query->query_vars['orderby']) and isset($query->query_vars['order']) and $query->query_vars['orderby'] == 'hits') {
            // Get global Variable
            $order = $query->query_vars['order'];

            // Select Field
            $clauses['fields'] .= ", (select SUM(" . DB::table("pages") . ".count) from " . DB::table("pages") . " where (" . DB::table("pages") . ".type = 'page' OR " . DB::table("pages") . ".type = 'post' OR " . DB::table("pages") . ".type = 'product') AND {$wpdb->posts}.ID = " . DB::table("pages") . ".id) as post_hits_sortable ";

            // And order by it.
            $clauses['orderby'] = " coalesce(post_hits_sortable, 0) $order";
        }

        return $clauses;
    }

    /**
     * Delete All Post Hits When Post is Deleted
     *
     * @param $post_id
     */
    public static function modify_delete_post($post_id)
    {
        global $wpdb;
        $wpdb->query("DELETE FROM `" . DB::table('pages') . "` WHERE `id` = " . esc_sql($post_id) . " AND (`type` = 'post' OR `type` = 'page' OR `type` = 'product');");
    }

    /**
     * Add Post Hit Number in Publish Meta Box in WordPress Edit a post/page
     */
    public function post_hit_misc()
    {
        global $post;
        if ($post->post_status == 'publish') {
            echo "<div class='misc-pub-section misc-pub-hits'>" . __('Visits', 'wp-statistics') . ": <a href='" . Menus::admin_url('pages', array('ID' => $post->ID, 'type' => Pages::get_post_type($post->ID))) . "'>" . number_format(wp_statistics_pages('total', "", $post->ID)) . "</a></div>";
        }
    }

    /**
     * Define Hit Chart Meta Box
     */
    public function define_post_meta_box()
    {

        // Get MetaBox information
        $metaBox = Meta_Box::getList(self::$hits_chart_post_meta_box);

        // Add MEtaBox To all Post Type
        foreach (Helper::get_list_post_type() as $screen) {
            add_meta_box(Meta_Box::getMetaBoxKey(self::$hits_chart_post_meta_box), $metaBox['name'], Meta_Box::LoadMetaBox(self::$hits_chart_post_meta_box), $screen, 'normal', 'high', array('__block_editor_compatible_meta_box' => true, '__back_compat_meta_box' => false));
        }
    }

}

new Admin_Post;
