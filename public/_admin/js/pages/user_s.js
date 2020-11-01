function swapUserData(id, labels, data, count) {
    Chart.helpers.each(Chart.instances, function(instance){
        if(instance.chart.canvas.id !== id) return;

        instance.chart.data.labels = labels;
        instance.chart.data.datasets[0].data = data;
        instance.chart.update();

        $('#'+id+'-count').html(count);
    });
}

function loadUserData(id, color) {
    new Chart(KTUtil.getByID(id).getContext('2d'), {
        type: "line",
        data: {
            labels: [],
            datasets: [{
                label: "Количество регистраций",
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