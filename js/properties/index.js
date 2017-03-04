$(function () {

    $('#city-select').on('change', function () {
        $.get('/cities/' + this.value + '/neighborhoods', function (neighborhoods) {
            console.log(neighborhoods);
            $('#debug').html(neighborhoods);

            var $neighborhoodSelect = $('#neighborhood-select');
            $neighborhoodSelect.find('option:gt(0)').remove();
            for (var i = 0; i < neighborhoods.length; i++) {
                $neighborhoodSelect
                    .append($('<option>', {
                        value: neighborhoods[i].id,
                        text: neighborhoods[i].name
                    }));
            }
        })
    });

    var $slider = $("#price-range");
    $slider.slider({
        range: true,
        min: 0,
        max: 500000,
        step: 1000,
        values: [
            $('#price-from-input').val(),
            $('#price-to-input').val()
        ],
        slide: function (event, ui) {
            $("#amount").html("$" + ui.values[0] + " <strong>and</strong> $" + ui.values[1]);
            $('#price-from-input').val(ui.values[0]);
            $('#price-to-input').val(ui.values[1]);
        }
    });
    $("#amount").html("$" + $slider.slider("values", 0) +
        " <strong>and</strong> $" + $slider.slider("values", 1));
});

