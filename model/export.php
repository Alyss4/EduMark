<?php
require 'C:\xampp\htdocs\EduMark\vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Export {
    private $pdo;

    public function __construct() {
        $config = parse_ini_file("config.ini");
        try {
            $this->pdo = new \PDO("mysql:host=".$config["host"].";dbname=".$config["database"].";charset=utf8", $config["user"], $config["password"]);
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }

    public function exportExcelData() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $jsondata = $_POST['data'];
            $lesDatas = json_decode($jsondata, true);
        
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $spreadsheet->getDefaultStyle()
                        ->getFont()
                        ->setName('Arial')
                        ->setSize(12);
        
            $spreadsheet->getActiveSheet()
                ->setCellValue('A1', 'Nom')
                ->setCellValue('B1', 'Prénom')
                ->setCellValue('C1','lol')
                ->setCellValue('G1','lol')
                ->setCellValue('K1','lol')
                ->setCellValue('O1','lol')
                ->setCellValue('S1','lol')
                ->setCellValue('W1','lol')
                ->setCellValue('AA1','lol')
                ->setCellValue('AE1','lol');
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
            foreach($lesDatas as $data){
                $sheet->setCellValue('A'.$row, $data['nom']);
                $sheet->setCellValue('B'.$row, $data['prenom']);
                $col = 'C';
                foreach($data['notes'] as $note){
                    $notesArray = explode(', ', $note);
                    foreach($notesArray as $noteValue){
                        $sheet->setCellValue($col.$row, $noteValue);
                        $col++;
                    }
                }
                $row++; 

            }
        
            $writer = new Xlsx($spreadsheet);
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="bulletin_eleve.xlsx"');
            $writer->save('php://output');
        }
    }
}

?>
