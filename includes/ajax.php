<?php
// Si ce fichier est appelé directement, exit.
if (!defined('ABSPATH')) {
    exit;
}

function get_last_visits() {
    $users = get_users();
    $data = array();
    foreach ($users as $user) {
        $last_visit = get_user_meta($user->ID, 'last_visit', true);
        $last_page = get_user_meta($user->ID, 'last_page_visited', true);
        if ($last_visit) {
            $date_format = get_option('date_format');
            $time_format = get_option('time_format');
            $timezone = new DateTimeZone(get_option('timezone_string') ? get_option('timezone_string') : 'UTC');
            $datetime = new DateTime($last_visit, new DateTimeZone('UTC'));
            $datetime->setTimezone($timezone);
            $formatted_last_visit = $datetime->format("$date_format $time_format:s");
            $data[] = array('id' => $user->ID, 'last_visit' => $formatted_last_visit, 'last_page' => $last_page);
        }
    }
    wp_send_json_success($data);
}
add_action('wp_ajax_get_last_visits', 'get_last_visits');