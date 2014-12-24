(function () {
    'use strict';

    var ensemblecompetenceId;
    var ensemblecompetenceElement;

    $('.ensemblecompetence-delete-button').click(function () {
        $('#delete-ensemblecompetence-validation-box').modal('show');
        ensemblecompetenceId = $(this).attr('btn-ensemblecompetence-id');
        ensemblecompetenceElement = $(this).parent().parent();
    });

    $('#delete-confirm-ok').click(function () {
        $.ajax({
            url: Routing.generate('claro_ensemblecompetence_delete', {'ensemblecompetenceId': ensemblecompetenceId}),
            type: 'DELETE',
            success: function () {
                $('#delete-ensemblecompetence-validation-box').modal('hide');
                ensemblecompetence.remove();
            }
        });
    });
})();