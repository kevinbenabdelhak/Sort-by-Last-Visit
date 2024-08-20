<?php
// Si ce fichier est appelé directement, exit.
if (!defined('ABSPATH')) {
    exit;
}

function track_user_last_visit() {
    if (is_admin()) return;

    if (current_user_can('administrator') && get_option('sort_by_last_visit_disable_ajax_for_admin_front')) {
        return;
    }

    if (is_user_logged_in()) {
        $user_id = get_current_user_id();
        $current_time = current_time('mysql');
        update_user_meta($user_id, 'last_visit', $current_time);

        if (get_option('sort_by_last_visit_enable_last_page')) {
            update_user_meta($user_id, 'last_page_visited', $_SERVER['REQUEST_URI']);
        }
    }
}
add_action('wp_head', 'track_user_last_visit');