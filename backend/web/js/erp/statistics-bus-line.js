$(function () {
    $('.search-btn').click(function () {
        $(this).parents('form').submit();
    });

    initChart($chartTitle, $keys, $values);

    function initChart($title, keys, values) {
        var myChart = echarts.init(document.getElementById('chart_main'));
        option = {
            title: {
                text: $title,
                x: 'center',
                y: 'top',
                textAlign: 'center'
            },
            xAxis: {
                type: 'category',
                data: keys
            },
            yAxis: {
                interval: 1,
                min:0,
                type: 'value'

            },
            series: [{
                data: values,
                type: 'bar',
                itemStyle: {
                    normal: {
                        color: '#4ad2ff',
                        label: {
                            show: true,
                            position: 'top',
                            textStyle: {
                                color: 'black',
                                fontSize: 16
                            }
                        }
                    },

                },

            }]
        };

        myChart.setOption(option);
    }
});