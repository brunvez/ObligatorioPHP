$(function () {

    $(document).on('shown.bs.modal', function () {
        var $propertyPhotos = $('#property-photos');
        $propertyPhotos.carousel({
            interval: 3000
        });

        $propertyPhotos.carousel('cycle');
    });

    $(document).on('hidden.bs.modal', function () {
        $('#property-modal').remove();
    });

    $('body').on('click', 'a.show-full-description', function () {
        $.get($(this).attr('href'), function (data) {
            $(data).modal();
        });
        return false;
    });
});