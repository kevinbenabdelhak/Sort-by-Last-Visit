<?php
/**
 * Plugin Name: Sort by Last Visit
 * Plugin URI: https://kevin-benabdelhak.fr/plugins/Sort-by-Last-Visit/
 * Description: Sort by Last Visit est un plugin WordPress qui ajoute une colonne "Dernière Visite" dans l'interface de gestion des utilisateurs, permettant de visualiser et de trier facilement les utilisateurs en fonction de leur dernière visite. Optimisez votre gestion d'utilisateurs avec un affichage clair et pratique !
 * Version: 1.0
 * Author: Kevin BENABDELHAK
 * Author URI: https://kevin-benabdelhak.fr/a-propos/
 * License: GPL2
 */

// Si ce fichier est appelé directement, aborter.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Fonction pour mettre à jour la date et l'heure de la dernière visite
add_action('wp', 'track_user_last_visit');

function track_user_last_visit() {
    if (is_user_logged_in()) {
        $user_id = get_current_user_id();
        $current_time = current_time('mysql');
        update_user_meta($user_id, 'last_visit', $current_time);
    }
}

// Ajouter une nouvelle colonne dans la liste des utilisateurs
add_filter('manage_users_columns', 'add_last_visit_column');

function add_last_visit_column($columns) {
    $columns['last_visit'] = 'Dernière Visite';
    return $columns;
}

// Afficher les données de la dernière visite dans la colonne supplémentaire
add_action('manage_users_custom_column', 'show_last_visit_column_data', 10, 3);

function show_last_visit_column_data($value, $column_name, $user_id) {
    if ($column_name == 'last_visit') {
        $last_visit = get_user_meta($user_id, 'last_visit', true);
        if (!$last_visit) {
            return 'Jamais connecté';
        } else {
            $date_format = get_option('date_format');
            $time_format = get_option('time_format');
            $timezone = new DateTimeZone(get_option('timezone_string') ? get_option('timezone_string') : 'UTC');
            $datetime = new DateTime($last_visit, new DateTimeZone('UTC'));
            $datetime->setTimezone($timezone);
            return $datetime->format("$date_format $time_format");
        }
    }
    return $value;
}

// Rendre la colonne "Dernière Visite" sortable
add_filter('manage_users_sortable_columns', 'make_last_visit_column_sortable');

function make_last_visit_column_sortable($sortable_columns) {
    $sortable_columns['last_visit'] = 'last_visit';
    return $sortable_columns;
}

// Gérer le tri de la colonne "Dernière Visite"
add_action('pre_get_users', 'sort_by_last_visit');

function sort_by_last_visit($query) {
    if ( isset($query->query_vars['orderby']) && 'last_visit' == $query->query_vars['orderby'] ) {
        $query->query_vars['meta_key'] = 'last_visit';
        $query->query_vars['orderby'] = 'meta_value';
    }
}