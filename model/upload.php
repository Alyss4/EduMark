<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
class Upload{
    private $pdo;
    public function __construct(){
        $config = parse_ini_file("config.ini");
        try{
            $this->pdo = new \PDO("mysql:host=".$config["host"].";dbname=".$config["database"].";charset=utf8", $config["user"], $config["password"]);
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }

    public function uploadExcelData(){
        if (!isset($_POST["fileSubmit"])) {
            return;
        }
        $file_error = $_FILES["excelFile"]["error"];
        $message = $this->getUploadErrorMessage($file_error);
        if ($file_error === UPLOAD_ERR_OK) {
            $file = $_FILES["excelFile"]["tmp_name"];
            $mimeType = mime_content_type($file);
    
            if (in_array($mimeType, ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])) {
                $tableur = IOFactory::load($file);
                $contenuFeuille = $tableur->getActiveSheet();
                $data = $contenuFeuille->toArray();
                $this->insertStudentDatabase($data);
                $message="Le fichier Excel a été traité avec succès.";
            } else {
                $message = "La taille du fichier est trop grande";
            }
        }
        echo $message;
    }

    private function insertStudentDatabase($data){
        //Me permet de stocker les index des colonnes qui contiennent les noms et prénoms 
        $trouveNom = null;
        $trouvePrenom = null;
        // Je parcours la première ligne du fichier excel
        foreach($data[0] as $index => $value){
            //Je verifie que chaques valeur est une chaine de caractères
            if(is_string($value)) {           
                //Si c'est une chaine de caractères je met en minuscule
                $lowercaseValue = strtolower($value);
                //Je vérifie que la chaine de caractères soit 'nom' ou 'prenom'
                if ($lowercaseValue === 'nom') {
                    // Si c'est le cas je stock l'index de la colonne dans la variable $trouveNom
                    $trouveNom = $index;
                } elseif ($lowercaseValue === 'prenom') {
                    // Si c'est le cas je stock l'index de la colonne dans la variable $trouvePrenom
                    $trouvePrenom = $index;
                }
            }
            if($trouveNom !== null && $trouvePrenom !== null){
                break;
            }
        }
        $sql = "INSERT INTO eleve (nom, prenom, idClasse) VALUES (:nom, :prenom, 'lol')";
        $req = $this->pdo->prepare($sql);
        // Boucle à travers chaque ligne du fichier excel 
        for ($i = 1; $i < count($data); $i++) {
            // J'utilise les index des colonnes nom et prénom pour récupérer les valeurs correspondantes dans chaque ligne
            $nom = $data[$i][$trouveNom];
            $prenom = $data[$i][$trouvePrenom];
            $req->bindParam(":nom", $nom);
            $req->bindParam(":prenom", $prenom);
            if (!$req->execute()) {
                $message ="Erreur lors de l'insertion des données.";
            }
        }
    }
    
    
    private function getUploadErrorMessage($code) {
        $errors = [
            UPLOAD_ERR_OK => "Le fichier Excel a été traité avec succès.",
            UPLOAD_ERR_INI_SIZE => "Le fichier téléchargé dépasse le upload_max_filesize directive en php.ini.",
            UPLOAD_ERR_FORM_SIZE => "Le fichier téléchargé dépasse le MAX_FILE_TAILLE directive spécifiée dans le formulaire HTML",
            UPLOAD_ERR_PARTIAL => "Le fichier téléchargé n'a été que partiellement téléchargé",
            UPLOAD_ERR_NO_FILE => "Aucun fichier n'a été téléchargé.",
            UPLOAD_ERR_NO_TMP_DIR => "Manque d'un dossier temporaire.",
            UPLOAD_ERR_CANT_WRITE => "Impossible d'écrire le fichier sur le disque.",
            UPLOAD_ERR_EXTENSION => "Une extension PHP a arrêté le téléchargement du fichier. PHP ne le fait pas fournir un moyen de déterminer quelle extension a causé le téléchargement du fichier stop; examiner la liste des extensions chargées avec phpinfo(d) peut aider.",
        ];
        return $errors[$code] ?? "Une erreur est survenue!";
    }
    //Créer fonction insertStudentDataBase
    //Faire une boucle pour chaque $data , récupérer le nom prénom classe
    //SQL insertion des datas tables élèves
    //Gestion des erreurs si erreurs
}