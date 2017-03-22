$(function () {

    var $body = $("body");

    $(document).on({
        ajaxStart: function () {
            $body.addClass("loading");
        },
        ajaxStop: function () {
            $body.removeClass("loading");
        }
    });

    $('#city-select').on('change', function () {
        $.get('/cities/' + this.value + '/neighborhoods', function (neighborhoods) {
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

    $(document).on('click', '.paginator a', function () {
        window.history.replaceState('', '', this.href);
        $.get(this.href, function (data) {
            var $propertiesContainer = $('.properties');
            $propertiesContainer.replaceWith(data.properties);
            var propertiesPos = $propertiesContainer.offset().top;
            $('html, body').animate({
                scrollTop: propertiesPos
            }, 1000);
            $('.paginator').html(data.paginator);
        });
        return false;
    });

    $(document).on('click', '#ask-question', function () {
        var body = $('#question-body').val();
        var property_id = $(this).data('property-id');
        var question = { body: body, property_id: property_id };
        $.post('/questions', { question: question }, function (response) {
            if(response.error){
                alertify.error(response.error);
            } else {
                $('#question-body').val('');
                $('#questions').append(response);
            }
        });
    });
});

