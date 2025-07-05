<?php
// Si ce fichier est appelé directement, exit.
if (!defined('ABSPATH')) {
    exit;
}

function sort_by_last_visit_add_admin_menu() {
    add_options_page(
        'Options - Sort by Last Visit',
        'Sort by Last Visit',
        'manage_options',
        'sort-by-last-visit-options',
        'sort_by_last_visit_options_page'
    );
}
add_action('admin_menu', 'sort_by_last_visit_add_admin_menu');

function sort_by_last_visit_options_page() {
    ?>
    <div class="wrap">
        <h1>Options du plugin</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('sort_by_last_visit_options_group');
            do_settings_sections('sort_by_last_visit-options');
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Activer AJAX</th>
                    <td>
                        <input type="checkbox" name="sort_by_last_visit_enable_ajax" value="1" <?php checked(1, get_option('sort_by_last_visit_enable_ajax'), true); ?> />
                        <p>Activer ou désactiver l'utilisation d'AJAX pour la mise à jour en temps réel de la liste des utilisateurs en admin.</p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Désactiver AJAX pour l'administrateur en front</th>
                    <td>
                        <input type="checkbox" name="sort_by_last_visit_disable_ajax_for_admin_front" value="1" <?php checked(1, get_option('sort_by_last_visit_disable_ajax_for_admin_front', 1), true); ?> />
                        <p>Désactiver l'utilisation de AJAX pour les administrateurs sur la partie publique du site.</p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Activer Dernière Page Visitée</th>
                    <td>
                        <input type="checkbox" name="sort_by_last_visit_enable_last_page" value="1" <?php checked(1, get_option('sort_by_last_visit_enable_last_page'), true); ?> />
                        <p>Afficher la dernière page visitée par les utilisateurs dans la liste des utilisateurs.</p>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}