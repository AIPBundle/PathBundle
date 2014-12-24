(function () {
    'use strict';

    var grillecompetenceId;
    var grillecompetenceElement;

    $('.grillecompetence-delete-button').click(function () {
        $('#delete-grillecompetence-validation-box').modal('show');
        grillecompetenceId = $(this).attr('btn-grillecompetence-id');
        grillecompetenceElement = $(this).parent().parent();
    });

    $('#delete-confirm-ok').click(function () {
        $.ajax({
            url: Routing.generate('claro_grillecompetence_delete', {'grillecompetenceId': grillecompetenceId}),
            type: 'DELETE',
            success: function () {
                $('#delete-grillecompetence-validation-box').modal('hide');
                grillecompetence.remove();
            }
        });
    });
})();