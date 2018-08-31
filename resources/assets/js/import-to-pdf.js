window.jsPDF = require('jspdf');

let importToPdf = document.getElementById('import-to-pdf');

module.exports = importToPdf.addEventListener('click', function () {
    let doc = new jsPDF();

    try {
        let chart = document.getElementById('chart');
        let pdf = new jsPDF('l', 'pt', 'a4');
        let pdfData = chart.toDataURL("image/png", 1.0);

        pdf.addImage(pdfData, 'PNG', 5, 5);
        pdf.save(importToPdf.getAttribute('data-chart') + ".pdf");
    } catch(e) {
        console.error(e.message);
    }
});
