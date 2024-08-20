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