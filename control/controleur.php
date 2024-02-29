<?php
class Controleur{
    private $vue;
    public function __construct(){
        if(session_status() == PHP_SESSION_NONE){
            session_start();
        }
        $this->vue = new View();
    }
    public function accueil(){
        (new View)->accueil();
    }
    public function erreur404(){
        (new View)->erreur404();
    }
    public function writeReport(){
        $uploadModel = new Upload();
        $lesMatieres = (new Matiere)->getAllMatiere();
        $donneesCompetence = (new Competence)->getCompetence();
        $leBareme = (new Note)->getAllNote();
        $lesEleves = [];
        if(isset($_POST["fileSubmit"])){
            $uploadModel->uploadExcelData();
            $lesEleves = $uploadModel->getEtudiant();
        }
        (new View)->writeReport($donneesCompetence,$lesMatieres,$leBareme, $lesEleves);
    }
    public function exportExcelData() {
        $export = new Export();
        $export->exportExcelData();
    }

}
?>