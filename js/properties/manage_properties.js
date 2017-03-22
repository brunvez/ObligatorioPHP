$(function () {
    $('button.delete-property').on('click', function () {
        var propertyId = $(this).data('property-id');
        var $row = $(this).closest('tr');

        $('#confirm').modal({
            backdrop: 'static',
            keyboard: false
        })
            .one('click', '#delete', function () {
                $.post('/properties/' + propertyId, { reason: $('#dismiss-reason').val() },
                     function (result) {
                        alertify.success(result);
                        $row.fadeOut(1000);
                });
            });
    });
});