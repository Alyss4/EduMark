<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
class Upload{
    private $pdo;
    private $lesEleves = [];
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
    public function insertStudentDatabase($donneesExcel){
        $trouveNom = null;
        $trouvePrenom = null;
        //Mon tableau élève me permettra d'afficher dans ma view 
        $eleves = [];
        // Parcourir chaque ligne pour trouver les index des colonnes "nom" et "prénom"
        foreach($donneesExcel as $row){
            // Rechercher les colonnes "nom" et "prénom" dans cette ligne
            $trouveNom = array_search('Nom', $row);
            $trouvePrenom = array_search('Prénom', $row);
            // Si les index sont trouvés, arrêter la recherche
            if($trouveNom !== false && $trouvePrenom !== false){
                break;
            }
        }
        // Si les colonnes "nom" et "prénom" sont trouvées
        if($trouveNom !== false && $trouvePrenom !== false){
            $sql = "INSERT INTO eleve (nom, prenom, idClasse) VALUES (:nom, :prenom, 'lol')";
            $req = $this->pdo->prepare($sql);
            foreach ($donneesExcel as $row){
                // Vérifier que la ligne ne contient pas les valeurs de titre
                if($row[$trouveNom] !== 'Nom' && $row[$trouvePrenom] !== 'Prénom'){
                    // Vérifier que la ligne contient les valeurs de "nom" et "prénom"
                    if(isset($row[$trouveNom]) && isset($row[$trouvePrenom])){
                        $nom = $row[$trouveNom];
                        $prenom = $row[$trouvePrenom];
                        $eleves =['nom' => $nom, 'prénom' => $prenom];
                        $req->bindParam(":nom", $nom);
                        $req->bindParam(":prenom", $prenom);
                        if (!$req->execute()){
                            $message = "Erreur lors de l'insertion des données.";
                        }else{
                            $this->lesEleves[] = ['nom' => $nom, 'prenom' => $prenom];
                        }
                    }
                }
            }
        } else {
            $message = "Les colonnes 'nom' et 'prenom' n'ont pas été trouvées dans le fichier Excel.";
        }
        return $eleves;
    }

    public function getEtudiant(){
        return $this->lesEleves;
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
}