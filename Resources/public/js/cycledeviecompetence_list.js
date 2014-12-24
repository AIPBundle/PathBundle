(function () {
    'use strict';

    var cycledeviecompetenceId;
    var cycledeviecompetenceElement;

    $('.cycledeviecompetence-delete-button').click(function () {
        $('#delete-cycledeviecompetence-validation-box').modal('show');
        cycledeviecompetenceId = $(this).attr('btn-cycledeviecompetence-id');
        cycledeviecompetenceElement = $(this).parent().parent();
    });

    $('#delete-confirm-ok').click(function () {
        $.ajax({
            url: Routing.generate('claro_cycledeviecompetence_delete', {'cycledeviecompetenceId': cycledeviecompetenceId}),
            type: 'DELETE',
            success: function () {
                $('#delete-cycledeviecompetence-validation-box').modal('hide');
                cycledeviecompetence.remove();
            }
        });
    });
})();