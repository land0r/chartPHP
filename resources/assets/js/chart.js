window.axios = require('axios');
window.ChartFactory = require('./chart-factory');
window.importToPdf = require('./import-to-pdf');

async function getChart(id) {
    return axios.get('/charts/'+id)
        .then(function(response) {
            console.log(response.data);
            return response.data;
        });
}

let chartBlock = document.getElementById('chart-view');
let diagram = getChart(chartBlock.dataset.id)
    .then(function (result) {
        ChartFactory.create(result);
    });
