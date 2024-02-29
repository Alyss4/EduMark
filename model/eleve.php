<?php
class Eleve {
    private $pdo;

    public function __construct(){
        $config = parse_ini_file('config.ini');
        try{
            $this->pdo = new \PDO("mysql:host=".$config["host"].";dbname=".$config["database"].";charset=utf8", $config["user"], $config["password"]);
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }

    public function getAllStudent(){
        $sql ='SELECT id, nom, prenom FROM eleve';
        $req = $this->pdo->prepare($sql);
        $req->execute();
        return $req->fetchAll();
    }
    
    public function getStudent($id){
        $sql ='SELECT id, nom, prenom FROM eleve WHERE id = :id';
        $req = $this->pdo->prepare($sql);
        $req->bindParam(':id', $id, PDO::PARAM_INT);
        $req->execute();
        return $req->fetch();
    }

    public function addStudent($nom, $prenom, $idClasse){
        $sql ='INSERT INTO eleve (nom, prenom, idClasse) VALUES (:nom, :prenom, :idClasse)';
        $req = $this->pdo->prepare($sql);
        $req->bindParam(':nom', $nom, PDO::PARAM_STR);
        $req->bindParam(':prenom', $prenom, PDO::PARAM_STR);
        $req->bindParam(':idClasse', $idClasse, PDO::PARAM_INT);
        $req->execute();
    }
}
?>