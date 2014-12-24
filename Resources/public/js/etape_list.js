(function () {
    'use strict';

    var etapeId;
    var etapeElement;

    $('.etape-delete-button').click(function () {
        $('#delete-etape-validation-box').modal('show');
        etapeId = $(this).attr('btn-etape-id');
        etapeElement = $(this).parent().parent();
    });

    $('#delete-confirm-ok').click(function () {
        $.ajax({
            url: Routing.generate('claro_etape_delete', {'etapeId': etapeId}),
            type: 'DELETE',
            success: function () {
                $('#delete-etape-validation-box').modal('hide');
                etape.remove();
            }
        });
    });
})();