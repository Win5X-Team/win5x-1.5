$(document).ready(function() {
    new Chart(KTUtil.getByID("popularity").getContext("2d"), {
        type: "doughnut",
        data: {
            datasets: [{
                data: __popularity_data,
                backgroundColor: __game_colors
            }],
            labels: __game_names
        },
        options: {
            cutoutPercentage: 75,
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                display: false, position: "top"
            },
            title: {
                display: false, text: "Игры"
            },
            animation: {
                animateScale: true, animateRotate: true
            },
            tooltips: {
                enabled: true, intersect: false, mode: "nearest", bodySpacing: 5, yPadding: 10, xPadding: 10, caretPadding: 0, displayColors: false,
                backgroundColor: KTApp.getStateColor("brand"), titleFontColor: "#ffffff", cornerRadius: 4, footerSpacing: 0, titleSpacing: 0
            }
        }
    });

    new Chart(KTUtil.getByID("daily_games").getContext("2d"), {
        type: "bar",
        data: {
            labels: __game_names,
            datasets:[ {
                backgroundColor: __game_colors,
                data: __game_data
            }, {
                backgroundColor: "#f3f3fb",
                data: __game_data
            }]
        },
        options: {
            title: {
                display: false
            },
            tooltips: {
                intersect: false,
                mode: "nearest",
                xPadding: 10,
                yPadding: 10,
                caretPadding: 10
            },
            legend: {
                display: false
            },
            responsive:true,
            maintainAspectRatio:false,
            barRadius: 4,
            scales: {
                xAxes: [{
                    display: false, gridLines: false, stacked: true
                }],
                yAxes: [{
                    display: false,
                    stacked: true,
                    gridLines: false
                }]
            },
            layout: {
                padding: {
                    left: 0, right: 0, top: 0, bottom: 0
                }
            }
        }
    });
});

function swapGameData(id, game_id, days) {
    send('#'+game_id, '/admin/game_stats/'+(days === 'today' ? 'today/'+game_id : 'days/'+game_id+'/'+days), function(response) {
        Chart.helpers.each(Chart.instances, function (instance) {
            if (instance.chart.canvas.id !== id) return;

            let json = JSON.parse(response);

            let labels = Array.from(JSON.parse('['+json.labels+']'));
            let d = Array.from(JSON.parse('['+json.days+']'));
            if(days !== 'today') {
                labels = labels.reverse();
                d = d.reverse();
            }

            instance.chart.data.labels = labels;
            instance.chart.data.datasets[0].data = d;
            instance.chart.update();

            $('#'+id+'-count').html(json.total);
        });
    });
}

function loadGameData(id, color) {
    new Chart(KTUtil.getByID(id).getContext('2d'), {
        type: "line",
        data: {
            labels: [],
            datasets: [{
                label: "Количество игр",
                backgroundColor: color,
                borderColor: color,
                pointBackgroundColor: Chart.helpers.color("#000000").alpha(0).rgbString(),
                pointBorderColor: Chart.helpers.color("#000000").alpha(0).rgbString(),
                pointHoverBackgroundColor: color,
                pointHoverBorderColor: Chart.helpers.color("#000000").alpha(.1).rgbString(),
                data: []
            }]
        },
        options: {
            title: {
                display: false
            },
            tooltips: {
                intersect: false,
                mode: "nearest",
                xPadding: 10,
                yPadding: 10,
                caretPadding: 10
            },
            legend: {
                display: false
            },
            responsive: true,
            maintainAspectRatio: false,
            hover: {
                mode: "index"
            },
            scales: {
                xAxes: [{
                    display: false,
                    gridLines: false,
                    scaleLabel: {
                        display: true,
                        labelString: "N/A"
                    }
                }],
                yAxes: [{
                    display: false,
                    gridLines: false,
                    scaleLabel: {
                        display: true,
                        labelString: "N/A"
                    },
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }, elements: {
                line: {
                    tension: 1e-7
                },
                point: {
                    radius: 4,
                    borderWidth: 12
                }
            }
        }
    });
}