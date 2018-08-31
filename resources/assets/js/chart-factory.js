window.Chart = require('chart.js');

// ChartFactory Object
let ChartFactory = {
    // create chart function
    create: function (result) {
        if (result) {
            // call chart builder function for result chart type
            switch (result.chart_type) {
                case 'bubble':
                    this.buildBubbleChart(result);
                    break;
                case 'pie':
                    this.buildPieChart(result);
                    break;
                case 'bar':
                    this.buildBarChart(result);
                    break;
                case 'line':
                    this.buildLineChart(result);
                    break;
                default:
                    break;
            }
        }
    },
    // build Bubble chart
    buildBubbleChart: function (result) {
        // parse axis data
        let Axis = JSON.parse(result.data);
        let chartRecords = [];

        // formatting data to our standard
        result.chart_records.forEach(function (item, i, arr) {
            item = JSON.parse(item.data);

            chartRecords.push({
                label: item.label,
                backgroundColor: item.backgroundColor,
                borderColor: item.borderColor,
                data: [{
                    x: Number(item.data.x),
                    y: Number(item.data.y)
                }]
            });
        });

        // find max value for X axis
        let rCoef = chartRecords.reduce(function (prev, current) {
            return (prev.data[0]["x"] > current.data[0]["x"]) ? prev : current
        });

        rCoef = Number(rCoef.data[0]["x"] / 100).toFixed(3);

        // push data with bubbles radius to final variable
        let formattedChart = [];
        chartRecords.forEach(function (item, i, arr) {
            item.data[0]["r"] = Number(item.data[0]["x"] / rCoef).toFixed(3);
            formattedChart.push(item);
        });

        // build bubble chart
        let myChart = new Chart(document.getElementById("chart"), {
            type: result.chart_type,
            data: {
                labels: result.name,
                datasets: formattedChart
            },
            options: {
                title: {
                    display: true,
                    text: result.name
                }, scales: {
                    yAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: Axis.yAxes
                        }
                    }],
                    xAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: Axis.xAxes
                        }
                    }]
                }
            }
        });
    },
    buildPieChart: function(result) {
        let chartRecords = {
            labels: [],
            backgroundColors: [],
            data: [],
        };
        let datasetsLabel = JSON.parse(result.data);
        datasetsLabel = datasetsLabel.title;

        // formatting data to our standard
        result.chart_records.forEach(function (item, i, arr) {
            item = JSON.parse(item.data);

            chartRecords.labels.push(item.label);
            chartRecords.backgroundColors.push(item.backgroundColor);
            chartRecords.data.push(item.value);
        });

        // build pie chart
        new Chart(document.getElementById("chart"), {
            type: 'pie',
            data: {
                labels: chartRecords.labels,
                datasets: [{
                    label: datasetsLabel,
                    backgroundColor: chartRecords.backgroundColors,
                    data: chartRecords.data
                }]
            },
            options: {
                title: {
                    display: true,
                    text: result.name
                }
            }
        });
    },
    buildBarChart: function(result) {
        let chartRecords = {
            labels: [],
            backgroundColors: [],
            data: [],
        };
        let additionalData = JSON.parse(result.data);
        additionalData = additionalData.title;

        // formatting data to our standard
        result.chart_records.forEach(function (item, i, arr) {
            item = JSON.parse(item.data);

            chartRecords.labels.push(item.label);
            chartRecords.backgroundColors.push(item.backgroundColor);
            chartRecords.data.push(item.value);
        });

        // Bar chart
        new Chart(document.getElementById("chart"), {
            type: 'bar',
            data: {
                labels: chartRecords.labels,
                datasets: [
                    {
                        label: additionalData,
                        backgroundColor: chartRecords.backgroundColors,
                        data: chartRecords.data
                    }
                ]
            },
            options: {
                legend: { display: false },
                title: {
                    display: true,
                    text: result.name
                }
            }
        });
    },
    buildLineChart: function(result) {
        let chartRecords = [];
        let additionalData = JSON.parse(result.data);
        additionalData = additionalData.labels.split(',');

        // formatting data to our standard
        result.chart_records.forEach(function (item, i, arr) {
            item = JSON.parse(item.data);

            chartRecords.push({
                data: item.values.split(','),
                label: item.label,
                borderColor: item.borderColor,
                fill: false
            });
        });

        new Chart(document.getElementById("chart"), {
            type: 'line',
            data: {
                labels: additionalData,
                datasets: chartRecords
            },
            options: {
                title: {
                    display: true,
                    text: result.name
                }
            }
        });
    }
};

// set ChartFactory to export
module.exports = ChartFactory;
