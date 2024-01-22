<?php

namespace WP_STATISTICS;

class taxonomies_page
{
    private static $taxonomies = [];
    private static $defaultTaxonomies = [];
    private static $taxonomy = 'category';

    public function __construct()
    {
        add_action('init', [$this, 'init']);
    }

    public function init()
    {
        // Check if in taxonomies Page
        if (Menus::in_page('taxonomies')) {

            // Disable Screen Option
            add_filter('screen_options_show_screen', '__return_false');

            // Is Validate Date Request
            $DateRequest = Admin_Template::isValidDateRequest();
            if (!$DateRequest['status']) {
                wp_die($DateRequest['message']);
            }

            // Get all taxonomies
            self::$taxonomies = Helper::get_list_taxonomy();

            // Set default taxonomies
            self::$defaultTaxonomies = apply_filters('wp_statistics_default_taxonomies', ['category', 'post_tag']);

            // Check validate taxonomy
            if (!empty($_GET['taxonomy']) && in_array($_GET['taxonomy'], self::$defaultTaxonomies)) {
                self::$taxonomy = sanitize_text_field($_GET['taxonomy']);
            } else {
                wp_redirect(add_query_arg([
                    'taxonomy' => self::$taxonomy
                ], admin_url('admin.php?page=' . Menus::get_page_slug('taxonomies'))));
                exit;
            }

            // Check Validate int Params
            if (isset($_GET['ID']) and (!is_numeric($_GET['ID']) || ($_GET['ID'] != 0 and term_exists((int)trim($_GET['ID']), self::$taxonomy) == null))) {
                wp_die(__("The request is invalid.", "wp-statistics"));
            }
        }
    }

    /**
     * Display Html Page
     *
     * @throws \Exception
     */
    public static function view()
    {
        // Page title
        $taxonomyTitle = array_key_exists(self::$taxonomy, self::$taxonomies) ? self::$taxonomies[self::$taxonomy] : '';
        $args['title'] = __($taxonomyTitle . ' statistics', 'wp-statistics');

        // Taxonomy
        $args['taxonomies']    = self::$taxonomies;
        $args['taxonomy']      = self::$taxonomy;
        $args['taxonomyTitle'] = $taxonomyTitle;
        $args['custom_get']    = ['taxonomy' => self::$taxonomy];

        // Get Current Page Url
        $args['pageName']   = Menus::get_page_slug('taxonomies');
        $args['pagination'] = Admin_Template::getCurrentPaged();

        // Get Taxonomy
        $taxonomy = get_taxonomy(self::$taxonomy);

        // Get List Category
        $terms = get_terms(self::$taxonomy, array(
            'hide_empty' => true,
        ));

        $args['tabs'] = [];
        foreach (self::$taxonomies as $slug => $title) {
            $class = ($slug == self::$taxonomy ? 'current' : '');
            $link  = Menus::admin_url('wps_taxonomies_page', ['taxonomy' => $slug]);
            if (!in_array($slug, self::$defaultTaxonomies)) {
                $class .= ' wps-locked';
                $link  = sprintf('%s/product/wp-statistics-data-plus?utm_source=wp_statistics&utm_medium=display&utm_campaign=wordpress', WP_STATISTICS_SITE_URL);
            }
            $args['tabs'][] = [
                'link'  => $link,
                'title' => $title,
                'class' => $class,
            ];
        }

        // Get Top Categories By Hits
        $args['top_list'] = array();
        if (!isset($_GET['ID']) || (isset($_GET['ID']) and $_GET['ID'] == 0)) {

            // Set Type List
            $args['top_list_type'] = self::$taxonomy;
            $args['top_title']     = __('Top ' . strtolower($taxonomyTitle) . ' Sorted by Visits', 'wp-statistics');

            // Push List Category
            foreach ($terms as $term) {
                $args['top_list'][$term->term_id] = array('ID' => $term->term_id, 'name' => $term->name, 'link' => add_query_arg('ID', $term->term_id), 'count_visit' => (int)wp_statistics_pages('total', null, $term->term_id, null, null, self::$taxonomy));
            }

        } else {
            $termID = (int)sanitize_text_field($_GET['ID']);
            $term   = get_term($termID);

            // Set Type List
            $args['top_list_type'] = $taxonomy->object_type;
            $args['top_title']     = __($taxonomyTitle . ': ' . ucfirst($term->name) . ' Most Popular Posts by Visits', 'wp-statistics');

            // Set Title
            $args['title']      = __($taxonomyTitle . ': ' . ucfirst($term->name) . ' Statistics', 'wp-statistics');
            $args['term_title'] = $term->name;

            // Get Top Posts From Category
            $post_lists = Helper::get_post_list(array(
                'post_type' => $args['top_list_type'],
                'tax_query' => [
                    [
                        'taxonomy' => self::$taxonomy,
                        'field'    => 'term_id',
                        'terms'    => sanitize_text_field($_GET['ID'])
                    ]
                ],
            ));

            $total_posts_visits_in_taxonomy = 0;
            foreach ($post_lists as $post_id => $post_title) {
                // Get post visit
                $post_type   = Pages::get_post_type($post_id);
                $count_visit = (int)wp_statistics_pages('total', null, $post_id, null, null, $post_type);

                // Add to total visits
                $total_posts_visits_in_taxonomy += $count_visit;

                // Push to top list
                $args['top_list'][$post_id] = array(
                    'ID'          => $post_id,
                    'name'        => $post_title,
                    'link'        => Menus::admin_url('pages', array('ID' => $post_id)),
                    'count_visit' => $count_visit
                );
            }

            // Check Number Post From Category
            $this_item = get_term_by('id', (int)trim($_GET['ID']), self::$taxonomy);

            // Set Number Post
            $args['number_post_in_taxonomy'] = $this_item->count;

            // The total posts visits in taxonomy
            $args['total_posts_visits_in_taxonomy'] = $total_posts_visits_in_taxonomy;
        }

        // Sort By Visit Count
        Helper::SortByKeyValue($args['top_list'], 'count_visit');

        // Get Only 5 Item
        if (count($args['top_list']) > 5) {
            $args['top_list'] = array_chunk($args['top_list'], 5);
            $args['top_list'] = $args['top_list'][0];
        }

        // Show Template Page
        Admin_Template::get_template(array('layout/header', 'layout/tabbed-page-header', 'pages/taxonomies', 'layout/postbox.hide', 'layout/footer'), $args);
    }

}

new taxonomies_page;