<?php
class View{
    public function entete(){
        echo 
        '<!DOCTYPE html>
        <html data-bs-theme="light" lang="en">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
            <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
            <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&amp;display=swap">
            <link rel="stylesheet" href="bootstrap/css/style.css"
        </head>
        <body>
        <nav class="navbar navbar-expand-md fixed-top navbar-shrink py-3 navbar-light" id="mainNav">
            <div class="container"><a class="navbar-brand d-flex align-items-center" href="index.php?action=accueil"><span>EduMark</span></a>
                <div class="collapse navbar-collapse" id="navcol-1"></div>
            </div>
        </nav>';
    }

    public function accueil(){
        $this->entete();
        echo '
        <title>Accueil - EduMark</title>
        <section class="py-4 py-md-5 my-5">
            <div class="container py-md-5">
                <div class="row">
                    <div class="col-md-6 text-center"><img class="img-fluid" src="img/accueil.png"></div>
                    <div class="col-md-5 col-xl-4 text-center text-md-start">
                        <h2 class="display-6 fw-bold mb-5"><span class="underline pb-1"><strong>Menu</strong></span></h2>
                        <form method="post" data-bs-theme="light" style="text-align: center;">
                            <div class="mb-3"><a class="btn btn-primary shadow" role="button" href="index.php?action=writeReport">Rédiger un bulletin</a></div>
                            <div class="mb-3"><a class="btn btn-primary shadow" role="button" href="signup.html">Importer un bulletin</a></div>
                            <div class="mb-5"><a class="btn btn-primary shadow" role="button" href="signup.html">Sign up</a></div>
                        </form>
                    </div>
                </div>
            </div>
        </section>';
        $this->footer();
    }
    public function writeReport($donneesCompetence,$lesMatieres, $leBareme){
        $this->entete();
        echo '
        <title>Création d\'un Bulletin - EduMark</title>
        <section class="py-5 mt-5">
            <div class="container py-5" style="margin-right: 0px;margin-left: 0px;display: inline;">
                <div class="row" style="margin-right: 0px;margin-left: 0px;">
                    <div class="col-md-12 col-xl-12 text-center" style="margin-right: 0px;margin-left: 0px;">
                        <h2 class="display-6 fw-bold mb-4">Rédiger un <span class="underline">nouveau</span> bulletin ?</h2>
                        <p class="text-muted">Choisissez une matière.</p>
                        <div class="dropdown">
                            <select class="btn btn-primary dropdown-toggle" aria-label="Choisir une matière">';
                                foreach($lesMatieres as $laMatiere){
                                    echo '<option value='.$laMatiere["id"].'>'.$laMatiere["nomMatiere"].'</option>';
                                };
                                echo'
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section id="saisieEleve">
            <div style="text-align : center">
                <form method="post" enctype="multipart/form-data" id="form">
                    <p class="text-muted">Ou importer une liste d\'élève en appuyant ici
                        <label for="excelFile" class="btn-excel-upload">
                            <img src="img/add_button_blue.svg" alt="Add">
                        </label>
                        <input id="excelFile" name="excelFile" type="file" class="btnAddListStudent">
                        <button type="submit" name="fileSubmit" id="submitBtn" class="btnSubmit" >Valider</button>
                    </p>
                </form>
            </div>
            <div class="container rowInput">
                <div class="mb-3 row">
                    <div class="col-5">
                        <input class="form-control" type="text" id="nom0" name="nom" placeholder="Nom de l\'élève">
                    </div>
                    <div class="col-5">
                        <input class="form-control" type="text" id="prenom0" name="prenom" placeholder="Prénom de l\'élève">
                    </div>
                    <div class="col-2">
                        <button class="btn btnAddStudent" type="button" onclick="return actionAddStudent()" id="btnAddStudent">
                            <img src="img/add_button_green.svg" alt="Add" />
                        </button>
                    </div>
                </div>
            </div>
        </section>
        <section id="datagrid">
        <div class="mx-5">
            <div class="table-responsive">
                <table class="table" style="width: 100%;">
                    <thead>
                        <tr>';
                            echo '
                            <th>Nom</th>
                            <th>Prénom</th>';
                            foreach($donneesCompetence as $competence){
                                echo'<th scope="col" style="text-align: -webkit-center" id="domaine'.$competence["id"].'">Domaine N°'.$competence["domaine"].'
                                        <img class="" src="img/question_mark.svg" onmouseover="questionMarkDetails(this)" onmouseleave="hideQuestionMarkDetails(this)" name="questionMark">
                                        <div name="questionMarkText" id='.$competence["id"].'>
                                            <h5 class="card-title">Domaine N°'.$competence["domaine"].'</h5>
                                            <p class="card-text">'.$competence["designation"].'</p>
                                        </div>
                                    </th> 
                                ';
                            }
                            echo'
                        </tr>
                    </thead>
                    <tbody id="datagridContent">
                        <tr id="etudiant0">';
                            echo 
                            '<td></td>
                            <td></td>';
                            foreach($donneesCompetence as $competence){
                                echo'<td data-label="Attributes" id="result'.$competence["id"].'" scope="row">
                                        <div class="cellBareme">';
                                                foreach($leBareme as $bareme){
                                                    echo '<div class="cellBaremeColor" id=note'.$bareme['id'].' onclick="changeContent(this)">'.$bareme['points'].'</div>';
                                                }
                                                echo'
                                        </div>
                                    </td>
                                ';
                            }
                            echo'
                            <td>
                                <button class="btn btn-danger btn-circle btn-del" type="button" onclick="return btnDelEleve(this)" id="btnDelEleve">-</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div>
                <button class="btn btn-primary shadow d-block mx-auto" type="submit">Exporter en PDF </button>
            </div>
        </div>   
    </section>';
    $this->footer();
}
    
    public function erreur404(){
        $this->entete();
        echo'
        <section class="py-5 mt-5">
            <div class="container">
                <div class="row row-cols-1 d-flex justify-content-center align-items-center">
                    <div class="col-md-10 text-center"><img class="img-fluid w-50" src="img/404.svg"></div>
                    <div class="col text-center">
                        <h3 class="display-3 fw-bold mb-2"><p class="fs-1">Page Introuvable !</p></h3>
                        <p class="fs-5 text-muted">Rien ici ! Que cherches-tu ici ? </p>
                    </div>
                </div>
            </div>
        </section>';
        $this->footer();
    }
    
    public function footer(){
        echo '
        <footer>
            <div class="container py-4 py-lg-5">
                <hr>
                <div class="text-muted d-flex justify-content-between align-items-center pt-3">
                    <p class="mb-0">Copyright © 2024 EduMark</p>
                </div>
            </div>
        </footer>
        <script src="js/event.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script src="js/startup-modern.js"></script>
        <script src="https://kit.fontawesome.com/eb8d62d7f2.js" crossorigin="anonymous"></script>
        </body>
        </html>';
    }
}



?>