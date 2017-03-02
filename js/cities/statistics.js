$(function () {

    $('#cities-select').on('change', function () {
        var city = this.value;

        $.get('/cities/' + city + '/properties_per_neighborhood', function (data) {

            var salesCounts = [];
            var salesAverages = [];
            var rentalCounts = [];
            var rentalAverages = [];
            var names = [];

            $.each(data, function (index, statistic) {
                if (statistic.operation === 'S') {
                    salesCounts.push({
                        y: parseInt(statistic.total_count),
                        city: city,
                        operation: 'S',
                        neighborhood: statistic.neighborhood_id
                    });
                    salesAverages.push(parseFloat(statistic.average));
                } else {
                    rentalCounts.push({
                        y: parseInt(statistic.total_count),
                        city: city,
                        operation: 'R',
                        neighborhood: statistic.neighborhood_id
                    });
                    rentalAverages.push(parseFloat(statistic.average));
                }
                names.push(statistic.name);
            });

            var categories = names.filter(onlyUnique);

            Highcharts.chart('container', {
                title: {
                    text: 'Number of Properties and Average Price per Square Meter per Neighborhood'
                },
                xAxis: [{
                    categories: categories,
                    crosshair: true
                }],
                yAxis: [{ // Secondary yAxis
                    title: {
                        text: 'Average Price',
                        style: {
                            color: Highcharts.getOptions().colors[0]
                        }
                    },
                    labels: {
                        style: {
                            color: Highcharts.getOptions().colors[0]
                        }
                    },
                    opposite: true
                }, { // Primary yAxis
                    labels: {
                        style: {
                            color: Highcharts.getOptions().colors[1]
                        }
                    },
                    title: {
                        text: 'Number of Properties',
                        style: {
                            color: Highcharts.getOptions().colors[1]
                        }
                    }
                }],
                series: [{
                    name: 'Properties for Sale',
                    type: 'column',
                    yAxis: 1,
                    data: salesCounts,
                    point: {
                        events: {
                            click: goToProperty
                        }
                    }
                }, {
                    name: 'Properties for Rental',
                    type: 'column',
                    yAxis: 1,
                    data: rentalCounts,
                    point: {
                        events: {
                            click: goToProperty
                        }
                    }
                }, {
                    name: 'Sales Average price per Square Meter',
                    type: 'spline',
                    data: salesAverages,
                    tooltip: {
                        valuePrefix: '$'
                    }
                }, {
                    name: 'Rental Average Price per Square Meter',
                    type: 'spline',
                    data: rentalAverages,
                    tooltip: {
                        valuePrefix: '$'
                    }
                }]
            });

        });
    });

    function goToProperty() {
        window.location.replace('/properties?city=' + this.city
            + '&operation=' + this.operation
            + '&neighborhood=' + this.neighborhood)
    }

    function onlyUnique(value, index, self) {
        return self.indexOf(value) === index;
    }
});