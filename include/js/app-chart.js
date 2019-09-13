$(document).ready(function () {
    var data, options;

    var monitorLen = $('.chart-area').length;
        
    for (var i=0; i<monitorLen; i++) {
        var monitor = $('.chart-area:eq('+i+')');
        var monitorID = monitor.data('monitor-id');
        var monitorType = monitor.data('monitor-type');
        var monitorFirstValue = monitor.data('first-val');
        var monitorSecondValue = monitor.data('second-val');

        switch (monitorType) {
            case "disk":
                data = {
                    series: [
                        {
                            name: "Used",
                            data: monitorFirstValue / 1000 / 1000
                        },
                        {
                            name: "Avail",
                            data: (monitorSecondValue - monitorFirstValue) / 1000 / 1000
                        }
                    ]
                };
                options = {
                    chart: {
                        width: 270,
                        height: 120,
                        format: function(value, chartType, areaType, valuetype, legendName) {
                            if (areaType === 'makingSeriesLabel') value = value + '%';
                            return value;
                        }
                    },
                    chartExportMenu: {
                        visible: false
                    },
                    series: {
                        startAngle: -90,
                        endAngle: 90,
                        radiusRange: ['50%', '100%'],
                        showLegend: false,
                        showLabel: false
                    },
                    tooltip: {
                        suffix: 'GB'
                    },
                    legend: {
                        visible: false
                    }
                };
                break;
            case "cpu":
                data = {
                        series: [
                            {
                                name: "Using",
                                data: (monitorFirstValue + monitorSecondValue)
                            },
                            {
                                name: "Idle",
                                data: (100 - (monitorFirstValue + monitorSecondValue))
                            }
                        ]
                    };
                options = {
                    chart: {
                        width: 270,
                        height: 120,
                        format: function(value, chartType, areaType, valuetype, legendName) {
                            if (areaType === 'makingSeriesLabel') value = value + '%';
                            return value;
                        }
                    },
                    chartExportMenu: {
                        visible: false
                    },
                    series: {
                        startAngle: -90,
                        endAngle: 90,
                        radiusRange: ['50%', '100%'],
                        showLegend: false,
                        showLabel: false
                    },
                    legend: {
                        visible: false
                    }
                };
                break;
            default:
                break;
        }

        var theme = {
            series: {
                colors: ['red', 'orange'],
                label: {
                    color: '#fff',
                    fontWeight: 'bold',
                    fontFamily: 'Verdana'
                }
            }
        };
        
        // For apply tui theme
        tui.chart.registerTheme('myTheme', theme);
        options.theme = 'myTheme';

        var container = document.getElementById('chart-' + monitorID);
        tui.chart.pieChart(container, data, options);
    }
});