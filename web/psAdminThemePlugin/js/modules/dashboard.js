myBar = new Chart(document.getElementById("barChart"), {
    type: 'bar',
    data: barChartData,    
    options: {
        responsive: true,
        scales: {
            xAxes: [{
                stacked: false
            }],
            yAxes: [{
                stacked: false,
                ticks: {
                    beginAtZero: true,
                    min: 0,
                    max: max_y,
                    stepSize: 5
                }
            }]
        }
    }
});

myBar2 = new Chart(document.getElementById("barChartUserRelative"), {
    type: 'bar',
    data: barChartUserRelative,    
    options: {
        responsive: true,
        scales: {
            xAxes: [{
                stacked: false,
                beginAtZero: true,
            }],
            yAxes: [{
                stacked: false,
                ticks: {
                    beginAtZero: true,
                    min: 0,
                    max: max_y,
                    stepSize: 5
                }
            }]
        }
    }
});