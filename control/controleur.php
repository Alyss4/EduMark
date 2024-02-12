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
        $lesMatieres = (new Matiere)->getAllMatiere();
        $donneesCompetence = (new Competence)->getCompetence();
        $leBareme = (new Note)->getAllNote();
        (new View)->writeReport($donneesCompetence,$lesMatieres,$leBareme);
    }

}
?>