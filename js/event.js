var compteurEtudiant = 0;
var destination = document.getElementById("datagridContent");
var newRow;
document.getElementById('etudiant0').style.display = 'none';   
function actionAddStudent(){
    var nomEleve = document.getElementById("nom0").value;
    var prenomEleve = document.getElementById("prenom0").value;
    actionAddStudentWithData(nomEleve, prenomEleve);
}
function actionAddStudentWithData(nomEleve, prenomEleve) {
    var newRow = document.getElementById('etudiant0').cloneNode(true);
    var newId = 'etudiant' + compteurEtudiant;
    while (document.getElementById(newId) !== null) {
        compteurEtudiant++;
        newId = 'etudiant' + compteurEtudiant;
    }
    newRow.id = newId;
    compteurEtudiant++;
    if (nomEleve === "" && prenomEleve === "") {
        alert('Veuillez remplir les champs textes ! ');
    } else if (nomEleve === "") {
        alert('Veuillez saisir un nom pour votre élève ! ');
    } else if (prenomEleve === "") {
        alert('Veuillez saisir un prénom pour votre élève ! ');
    } else {
        newRow.getElementsByTagName('td')[0].textContent = nomEleve;
        newRow.getElementsByTagName('td')[1].textContent = prenomEleve;

        document.getElementById("nom0").value = "";
        document.getElementById("prenom0").value = "";

        newRow.style.display = "table-row";
        destination.appendChild(newRow);
    }
    return false;
}
function btnDelEleve(button){
    var row = button.parentNode.parentNode;
    var idEtudiant = row.id.substring(8);
    if (idEtudiant === '0'){
        row.getElementsByTagName('td')[0].textContent = '';
        row.getElementsByTagName('td')[1].textContent = '';
        compteurEtudiant = 1;
    }else{
        row.parentNode.removeChild(row);
        compteurEtudiant--;
    }
    return false;
}
function questionMarkDetails(imgElement) {
    var questionMarkText = imgElement.parentNode.querySelector('[name="questionMarkText"]');
    if (questionMarkText){
        questionMarkText.style.display = 'block';
    }
}
function hideQuestionMarkDetails(imgElement) {
    var questionMarkText = imgElement.parentNode.querySelector('[name="questionMarkText"]');
    if (questionMarkText){
        questionMarkText.style.display = 'none';
    }
}
function changeContent(cellule){
    if (cellule.textContent === "X") {
        cellule.textContent = cellule.dataset.contenuOriginal || "";
    } else {
        cellule.dataset.contenuOriginal = cellule.textContent || "";
        cellule.textContent = "X";
    }
}
function assignColors() {
    var tableRows = document.querySelectorAll('#datagridContent tr');
    var colors = ['#C2D69B', '#95b3d7', '#fabf8f', '#d99594'];
    tableRows.forEach(function(row, rowIndex) {
        var cells = row.querySelectorAll('.cellBaremeColor'); 
        cells.forEach(function(cell, cellIndex) {
            var colorIndex = cellIndex % colors.length;
            cell.style.backgroundColor = colors[colorIndex]; 
        });
    });
}
window.addEventListener('DOMContentLoaded', assignColors);
document.getElementById('excelFile').onchange = function() {
    if (this.files && this.files.length > 0) {
        var submitBtn = document.getElementById('submitBtn');
        submitBtn.click();
    }
};
var indexEleves = document.getElementsByName('index');
var nomEleves = document.getElementsByName('nom');
var prenomEleves = document.getElementsByName('prenom');
for (var i = 0; i < indexEleves.length; i++) {
    var index = indexEleves[i].value.split('/').join('');
    var nom = nomEleves[i].value.split('/').join(''); 
    var prenom = prenomEleves[i].value.split('/').join(''); 
    actionAddStudentWithData(nom, prenom);
}


    


