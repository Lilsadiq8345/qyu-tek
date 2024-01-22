<?php

namespace WP_STATISTICS\MetaBox;

use WP_STATISTICS\TimeZone;

class top_pages_chart
{
    /**
     * Default Number day in Hits Chart
     *
     * @var int
     */
    public static $default_days_ago = 30;

    /**
     * Show Chart Hit
     *
     * @param array $arg
     * @return array
     * @throws \Exception
     */
    public static function get($arg = array())
    {
        /**
         * Filters the args used from metabox for query stats
         *
         * @param array $args The args passed to query stats
         * @since 14.2.1
         *
         */
        $arg = apply_filters('wp_statistics_meta_box_top_pages_chart_args', $arg);

        // Set Default Params
        $defaults = array(
            'ago'  => 0,
            'from' => '',
            'to'   => '',
            'type' => 'post',
        );
        $args     = wp_parse_args($arg, $defaults);

        // Set Default Params
        $date = $stats = $total_daily = $search_engine_list = array();

        // Check Default
        if (empty($args['from']) and empty($args['to']) and $args['ago'] < 1) {
            $args['ago'] = self::$default_days_ago;
        }

        // Get time ago Days Or Between Two Days
        if ($args['ago'] > 0) {
            $days_list = TimeZone::getListDays(array('from' => TimeZone::getTimeAgo($args['ago'])));
        } else {
            $days_list = TimeZone::getListDays(array('from' => $args['from'], 'to' => $args['to']));
        }

        // Get List Of Days
        $days_time_list = array_keys($days_list);
        foreach ($days_list as $k => $v) {
            $date[]          = $v['format'];
            $total_daily[$k] = 0;
        }

        // Prepare title Hit Chart
        if ($args['ago'] > 0) {
            $count_day = $args['ago'];
        } else {
            $count_day = TimeZone::getNumberDayBetween($args['from'], $args['to']);
        }

        // Set Title
        if (end($days_time_list) == TimeZone::getCurrentDate("Y-m-d")) {
            $title = sprintf(__('Top 5 Popular Pages in the Last %s Days', 'wp-statistics'), $count_day);
        } else {
            $title = sprintf(__('Top 5 Popular Pages Between %s and %s', 'wp-statistics'), $args['from'], $args['to']);
        }

        $post_type = !empty($args['type']) ? $args['type'] : 'post';

        // Get List Of Top Pages
        $top_pages = wp_statistics_get_top_pages(reset($days_time_list), end($days_time_list), 5, $post_type);

        // Push List to data
        foreach ($top_pages[1] as $item) {

            // Get Number Search every Days
            foreach ($days_time_list as $d) {
                $getStatic         = wp_statistics_pages($d, $item[0], -1, null, null, $post_type);
                $stats[$item[0]][] = $getStatic;
                $total_daily[$d]   = $total_daily[$d] + $getStatic;
            }
        }

        // Prepare Response
        $response = array(
            'days'  => $count_day,
            'from'  => reset($days_time_list),
            'to'    => end($days_time_list),
            'type'  => (($args['from'] != "" and $args['to'] != "" and $args['ago'] != self::$default_days_ago) ? 'between' : 'ago'),
            'title' => $title,
            'date'  => $date,
            'stat'  => $stats,
            'pages' => $top_pages,
            'total' => array(
                'stat' => array_values($total_daily)
            )
        );

        // Check For No Data Meta Box
        if (count(array_filter($total_daily)) < 1 and !isset($args['no-data'])) {
            $response['no_data'] = 1;
        }

        // Response
        return $response;
    }

}