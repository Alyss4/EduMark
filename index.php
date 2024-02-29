<?php
session_start();
$config = parse_ini_file("config.ini");
try{
    $pdo = new \PDO("mysql:host=".$config["host"].";dbname=".$config["database"].";charset=utf8", $config["user"], $config["password"]);
}
catch (Exception $e){
    echo "<h1> Attention !  Erreur de connexion à la base de données </h1>";
    echo $e->getMessage();
    exit; 
}
require("view/view.php");
require("control/controleur.php");
require("model/note.php");
require("model/competence.php");
require("model/matiere.php");
require("model/upload.php");
require("model/export.php");
require("model/bulletin.php");
require("model/eleve.php");

if (isset($_GET["action"])){
    switch($_GET["action"]){
        case "accueil":
            (new controleur)->accueil();
            break;
        case "writeReport":
            (new controleur)->writeReport();
            break;
        case "exportExcelData":
                (new controleur)->exportExcelData();
            break;
        default:
            (new controleur)->erreur404();
            break;
    }
    }else{
        (new controleur)->accueil();
    }
?>