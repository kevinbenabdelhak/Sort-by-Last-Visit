<?php

/**

 * Plugin Name: Sort by Last Visit

 * Description: Sort by Last Visit est un plugin WordPress qui ajoute une colonne "Dernière Visite" dans l'interface de gestion des utilisateurs.

 * Version: 1.1

 * Author: Kevin BENABDELHAK

 * License: GPL2

 */



if (!defined('ABSPATH')) {

    exit;

}



if ( !class_exists( 'YahnisElsts\\PluginUpdateChecker\\v5\\PucFactory' ) ) {
    require_once __DIR__ . '/plugin-update-checker/plugin-update-checker.php';
}
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$monUpdateChecker = PucFactory::buildUpdateChecker(
    'https://github.com/kevinbenabdelhak/Sort-by-Last-Visit/', 
    __FILE__,
    'Sort-by-Last-Visit' 
);

$monUpdateChecker->setBranch('main');








// Inclure les fichiers nécessaires

require_once plugin_dir_path(__FILE__) . 'includes/settings.php';

require_once plugin_dir_path(__FILE__) . 'includes/functions.php';

require_once plugin_dir_path(__FILE__) . 'includes/ajax.php';

require_once plugin_dir_path(__FILE__) . 'admin/options.php';

require_once plugin_dir_path(__FILE__) . 'admin/scripts.php';

require_once plugin_dir_path(__FILE__) . 'admin/columns.php';