var climatelabels = [], temperature = [], humidity = [], climatedata = [];

function updateClimateData() {


    function formatDate(itemdate) {
        return moment(itemdate).format("Do HH:mm");
    }

    $.ajax({
        url: 'api.php?getClimateData24hrs&PHP',
        dataType: 'json'
    }).done(function (results) {

        results.forEach(function (packet) {
            // console.log(speedlabels.indexOf(formatDate(packet.start_time)));
            if (climatelabels.indexOf(formatDate(packet.timestamp)) === -1) {
                climatelabels.push(formatDate(packet.timestamp));
                temperature.push(parseFloat(packet.temperature));
                humidity.push(parseFloat(packet.humidity));

            }

        });
        climateChart.update();
        climatedata = results;
    });
}


setInterval(function () {
    // console.log('updateSpeedTestData');
    updateClimateDataData();
}, 6000);


var climateChartctx = document.getElementById("climateChart");
var climateChart = new Chart(climateChartctx, {
    type: 'line',
    data: {
        labels: climatelabels,
        datasets: [{
            label: 'Temperature',
            data: temperature,
            backgroundColor: 'rgba(75, 192, 192, 0.1)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1,
            cubicInterpolationMode: 'monotone',
            yAxisID: "y-axis-1"
        },
            {
                label: 'Humidity',
                data: humidity,
                backgroundColor: 'rgba(255, 99, 132, 0.1)',
                borderColor: 'rgba(255,99,132,1)',
                borderWidth: 1,
                yAxisID: "y-axis-1"
            }

        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        legend: {
            display: false
        },
        scales: {
            yAxes: [{
                type: "linear", // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                display: true,
                position: "left",
                id: "y-axis-1",
                ticks : {
                    min: 0
                }
            }],
            xAxes: [
                {
                    // type :'time',
                    display: true,
                    scaleLabel: {
                        display: true
                    },
                    ticks: {
                        // autoSkip: true,
                        maxTicksLimit: 10,
                        maxRotation: 0,
                        minRotation: 0
                    }
                }
            ]
        },
        tooltips: {
            enabled: true,
            mode: "x-axis",
            intersect: false
        }
    }
});
updateClimateData();
