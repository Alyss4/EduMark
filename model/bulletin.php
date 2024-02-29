<?php
    class Bulletin{
        private $pdo;
        public function __construct(){
            $config = parse_ini_file('config.ini');
            try{
                $this->pdo = new \PDO("mysql:host=".$config["host"].";dbname=".$config["database"].";charset=utf8", $config["user"], $config["password"]);
            }catch(Exception $e){
                echo $e->getMessage();
            }
        }
        public function getBulletin(){
            $sql ='SELECT id, idEleve, idCompetence, idMatiere, idProfesseur, idTypeNote FROM bulletin';
            $req = $this->pdo->prepare($sql);
            $req->execute();
            return $req->fetchAll();
        }
        public function getAllBulletin($id){
            $sql ='SELECT id, idEleve, idCompetence, idMatiere, idProfesseur, idTypeNote FROM bulletin WHERE id = :id';
            $req = $this->pdo->prepare($sql);
            $req->bindParam(':id', $id, PDO::PARAM_INT);
            $req->execute();
            return $req->fetchAll();
        }
        public function addBulletin($idEleve, $idCompetence, $idMatiere, $idProfesseur, $idTypeNote){
            $sql ='INSERT INTO bulletin (idEleve, idCompetence, idMatiere, idProfesseur, idTypeNote) VALUES (:idEleve, :idCompetence, :idMatiere, :idProfesseur, :idTypeNote)';
            $req = $this->pdo->prepare($sql);
            $req->bindParam(':idEleve', $idEleve, PDO::PARAM_INT);
            $req->bindParam(':idCompetence', $idCompetence, PDO::PARAM_INT);
            $req->bindParam(':idMatiere', $idMatiere, PDO::PARAM_INT);
            $req->bindParam(':idProfesseur', $idProfesseur, PDO::PARAM_INT);
            $req->bindParam(':idTypeNote', $idTypeNote, PDO::PARAM_INT);
            $req->execute();
        }
    }

?>