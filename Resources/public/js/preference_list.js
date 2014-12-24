(function () {
    'use strict';

    var preferenceId;
    var preferenceElement;

    $('.preference-delete-button').click(function () {
        $('#delete-preference-validation-box').modal('show');
        preferenceId = $(this).attr('btn-preference-id');
        preferenceElement = $(this).parent().parent();
    });

    $('#delete-confirm-ok').click(function () {
        $.ajax({
            url: Routing.generate('claro_preference_delete', {'preferenceId': preferenceId}),
            type: 'DELETE',
            success: function () {
                $('#delete-preference-validation-box').modal('hide');
                preference.remove();
            }
        });
    });
})();