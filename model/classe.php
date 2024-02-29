<?php
class Classe {
    private $pdo;

    public function __construct(){
        $config = parse_ini_file('config.ini');
        try{
            $this->pdo = new \PDO("mysql:host=".$config["host"].";dbname=".$config["database"].";charset=utf8", $config["user"], $config["password"]);
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }

    public function getAllClasse(){
        $sql ='SELECT id, niveau, groupe FROM classe';
        $req = $this->pdo->prepare($sql);
        $req->execute();
        return $req->fetchAll();
    }
    
    public function getClasse($id){
        $sql ='SELECT id, niveau, groupe FROM classe WHERE id = :id';
        $req = $this->pdo->prepare($sql);
        $req->bindParam(':id', $id, PDO::PARAM_INT);
        $req->execute();
        return $req->fetch();
    }

    public function addClasse($niveau, $groupe){
        $sql ='INSERT INTO classe (niveau, groupe) VALUES (:niveau, :groupe)';
        $req = $this->pdo->prepare($sql);
        $req->bindParam(':niveau', $niveau, PDO::PARAM_STR);
        $req->bindParam(':groupe', $groupe, PDO::PARAM_INT);
        $req->execute();
    }
}
?>