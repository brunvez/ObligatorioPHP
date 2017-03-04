$(function () {
    Galleria.loadTheme('js/galleria/themes/classic/galleria.classic.min.js');

    $('a.show-full-description').on('click', function () {
        $.get($(this).attr('href'), function (data) {
            $(data).modal();
            var waitForGalleria = setInterval(function () {
                if ($('.galleria').length) {
                    clearInterval(waitForGalleria);
                    Galleria.run('.galleria');
                }
            }, 100);
        });
        return false;
    });
});