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
        if (isset($_POST["fileSubmit"])){
            $file_error = $_FILES["excelFile"]["error"];
            switch($file_error){
                case UPLOAD_ERR_OK:
                    $message = "Le fichier Excel a été traité avec succès.";
                    $file = $_FILES["excelFile"]["tmp_name"];
                    $filesInfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mimeType = finfo_file($filesInfo, $_FILES["excelFile"]["tmp_name"]);
                    finfo_close($filesInfo);
                    switch($mimeType){
                        case 'application/vnd.ms-excel':
                        case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
                            $tableur = IOFactory::load($file);
                            $contenuFeuille = $tableur->getActiveSheet();
                            $data = $contenuFeuille->toArray();
                            $this->insertStudentDatabase($data);
                            $message="Le fichier Excel a été traité avec succès.";
                        break;
                        default:
                            $message = "La taille du fichier est trop grande";
                        break;
                    }
                break;
                case UPLOAD_ERR_INI_SIZE:
                    $message = "La taille du fichier est trop grande.";
                break;
                case UPLOAD_ERR_NO_FILE:
                    $message = "Aucun fichier n'a été trouvé";
                break;
                default: 
                    $message = "Erreur innatendue lors du téléchargement du fichier.";
                break;
            }
        }
    }
    //Créer fonction insertStudentDataBase
    //Faire une boucle pour chaque $data , récupérer le nom prénom classe
    //SQL insertion des datas tables élèves
    //Gestion des erreurs si erreurs
}