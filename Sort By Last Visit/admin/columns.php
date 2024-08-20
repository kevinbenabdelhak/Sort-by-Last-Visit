<?php
// Si ce fichier est appelé directement, exit.
if (!defined('ABSPATH')) {
    exit;
}

function add_last_visit_columns($columns) {
    $columns['last_visit'] = 'Dernière Visite';
    if (get_option('sort_by_last_visit_enable_last_page')) {
        $columns['last_page_visited'] = 'Dernière Page Visitée';
    }
    return $columns;
}
add_filter('manage_users_columns', 'add_last_visit_columns');

function show_last_visit_columns_data($value, $column_name, $user_id) {
    switch ($column_name) {
        case 'last_visit':
            $last_visit = get_user_meta($user_id, 'last_visit', true);
            if (!$last_visit) {
                return 'Jamais connecté';
            } else {
                $date_format = get_option('date_format');
                $time_format = get_option('time_format');
                $timezone = new DateTimeZone(get_option('timezone_string') ? get_option('timezone_string') : 'UTC');
                $datetime = new DateTime($last_visit, new DateTimeZone('UTC'));
                $datetime->setTimezone($timezone);
                return $datetime->format("$date_format $time_format:s");
            }
        case 'last_page_visited':
            if (get_option('sort_by_last_visit_enable_last_page')) {
                $last_page = get_user_meta($user_id, 'last_page_visited', true);
                return $last_page ? $last_page : 'Aucune page visitée';
            }
            break;
    }
    return $value;
}
add_action('manage_users_custom_column', 'show_last_visit_columns_data', 10, 3);

function make_last_visit_column_sortable($sortable_columns) {
    $sortable_columns['last_visit'] = 'last_visit';
    return $sortable_columns;
}
add_filter('manage_users_sortable_columns', 'make_last_visit_column_sortable');

function sort_by_last_visit($query) {
    if (isset($query->query_vars['orderby']) && 'last_visit' == $query->query_vars['orderby']) {
        $query->query_vars['meta_key'] = 'last_visit';
        $query->query_vars['orderby'] = 'meta_value';
        if (!isset($query->query_vars['order'])) {
            $query->query_vars['order'] = 'DESC'; // Par défaut DESC
        }
    }
}
add_action('pre_get_users', 'sort_by_last_visit');