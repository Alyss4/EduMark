function sendTableToPHP() {
    var data = [];
    var tableRows = document.querySelectorAll('#datagridContent tr');

    for (var i = 1; i < tableRows.length; i++) {
        var tr = tableRows[i];
        var rowData = {
            "nom": tr.cells[0].innerText,
            "prenom": tr.cells[1].innerText,
            "notes": {}
        };
        var competenceCells = tr.querySelectorAll('.cellCompetence');
        competenceCells.forEach(function(competence) {
            var noteCells = competence.querySelectorAll('.cellBaremeColor');
            var cellIndex = competence.id; 
            var notes = []; 
            noteCells.forEach(function(noteCell) {
                notes.push(noteCell.textContent);
            });
            rowData.notes[cellIndex] = notes.join(", "); 
        });
        data.push(rowData);
    }
    document.getElementById('dataInputTableData').value = JSON.stringify(data);
    document.getElementById('dataTableForm').submit();
}
