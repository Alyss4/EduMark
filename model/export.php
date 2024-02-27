<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $jsondata = $_POST['data'];
    $data = json_decode($jsondata, true);

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $spreadsheet->getDefaultStyle()
                ->getFont()
                ->setName('Arial')
                ->setSize(12);

    $spreadsheet->getActiveSheet()
        ->setCellValue('A1', 'Nom')
        ->setCellValue('B1', 'Prénom')
        ->setCellValue('C1','Domaine 1 - cycle 4')
        ->setCellValue('G1','Domaine 1 - cycle 4')
        ->setCellValue('K1','Domaine 1 - cycle 4')
        ->setCellValue('O1','Domaine 1 - cycle 4')
        ->setCellValue('S1','Domaine 2 - cycle 4')
        ->setCellValue('W1','Domaine 3 - cycle 4')
        ->setCellValue('AA1','Domaine 4 - cycle 4')
        ->setCellValue('AE1','Domaine 5 - cycle 4');
    //FUSIONER LES COLONNES 
    $spreadsheet->getActiveSheet()->mergeCells('C1:F1');
    $spreadsheet->getActiveSheet()->mergeCells('G1:J1');
    $spreadsheet->getActiveSheet()->mergeCells('K1:N1');
    $spreadsheet->getActiveSheet()->mergeCells('O1:R1');	
    $spreadsheet->getActiveSheet()->mergeCells('S1:V1');
    $spreadsheet->getActiveSheet()->mergeCells('W1:Z1');
    $spreadsheet->getActiveSheet()->mergeCells('AA1:AD1');
    $spreadsheet->getActiveSheet()->mergeCells('AE1:AH1');
    //LIGNES

    $row = 2;
    foreach($lesData as $data){
        $sheet->setCellValue('A'.$row, $data['nom']);
        $sheet->setCellValue('A'.$row, $data['prenom']);
        $row++; 
    }

    $writer = new Xlsx($spreadsheet);

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="bulletin_eleve.xlsx"');

    $writer->save('php://output');
}



?>