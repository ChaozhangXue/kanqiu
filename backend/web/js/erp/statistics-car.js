$(function () {
    $('.search-btn').click(function () {
        $(this).parents('form').submit();
    });
    initChart($chartTitle, $keys, $values, $values1);

    function initChart($title, keys, values, values1) {
        var myChart = echarts.init(document.getElementById('chart_main'));
        if ($('select[name=chart_type]').val() == 'default') {
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
        } else {
            option = {
                title: {
                    text: $title,
                    x: 'center'
                },
                tooltip: {
                    trigger: 'item',
                    formatter: "{b} : {c} ({d}%)"
                },
                legend: {
                    type: 'scroll',
                    orient: 'vertical',
                    right: 10,
                    top: 20,
                    bottom: 20,
                    data: keys,
                },
                series: [
                    {
                        type: 'pie',
                        radius: '55%',
                        center: ['40%', '50%'],
                        data: values1,
                        itemStyle: {
                            emphasis: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                            }
                        }
                    }
                ]
            };
        }

        myChart.setOption(option);
    }
});