<?php
// Si ce fichier est appelé directement, exit.
if (!defined('ABSPATH')) {
    exit;
}

function sort_by_last_visit_register_settings() {
    register_setting('sort_by_last_visit_options_group', 'sort_by_last_visit_enable_ajax');
    register_setting('sort_by_last_visit_options_group', 'sort_by_last_visit_disable_ajax_for_admin_front');
    register_setting('sort_by_last_visit_options_group', 'sort_by_last_visit_enable_last_page');
}
add_action('admin_init', 'sort_by_last_visit_register_settings');