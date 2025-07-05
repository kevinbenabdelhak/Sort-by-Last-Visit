<?php
// Si ce fichier est appelé directement, exit.
if (!defined('ABSPATH')) {
    exit;
}

function enqueue_last_visit_script($hook) {
    if ($hook != 'users.php') {
        return;
    }
    if (get_option('sort_by_last_visit_enable_ajax')) {
        wp_enqueue_script('sort-by-last-visit', plugin_dir_url(__FILE__) . '../assets/js/sort-by-last-visit.js', array('jquery'), null, true);
        wp_localize_script('sort-by-last-visit', 'ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')));
    }
}
add_action('admin_enqueue_scripts', 'enqueue_last_visit_script');

function last_visit_admin_footer_script() {
    if (get_option('sort_by_last_visit_enable_ajax')) {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                function refreshLastVisitColumn() {
                    $.ajax({
                        url: ajax_object.ajaxurl,
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            action: 'get_last_visits'
                        },
                        success: function(response) {
                            if (response.success) {
                                response.data.forEach(function(user) {
                                    var row = $('tr#user-' + user.id);
                                    var lastVisitCell = row.find('.column-last_visit');
                                    var currentLastVisit = lastVisitCell.text().trim();
                                    var lastPageCell = row.find('.column-last_page_visited');
                                    var currentLastPage = lastPageCell.text().trim();
                                    
                                    if (currentLastVisit !== user.last_visit) {
                                        lastVisitCell.text(user.last_visit);
                                        row.addClass('highlight').prependTo('tbody');
                                        setTimeout(function() {
                                            row.removeClass('highlight');
                                        }, 2000);
                                    }
                                    
                                    if (user.last_page && currentLastPage !== user.last_page) {
                                        lastPageCell.text(user.last_page);
                                        row.addClass('highlight').prependTo('tbody');
                                        setTimeout(function() {
                                            row.removeClass('highlight');
                                        }, 2000);
                                    }
                                });
                            }
                        }
                    });
                }

                // Rafraîchissement initial
                refreshLastVisitColumn();

                // Intervalle pour vérifier les mises à jour toutes les 5 secondes
                setInterval(refreshLastVisitColumn, 5000);

                // Détecter les changements de page et mettre immédiatement à jour
                $(document).on('click', 'a', function() {
                    refreshLastVisitColumn();
                });
            });
        </script>
        <style>
            .highlight {
                background-color: #ffdddd !important;
            }
        </style>
        <?php
    }
}
add_action('admin_footer-users.php', 'last_visit_admin_footer_script');