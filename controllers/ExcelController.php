<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'third_party/PhpSpreadsheet/Spreadsheet.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ExcelController extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Material_model');
    }

    // 1️⃣ EXPORT DATA KE EXCEL
    public function export()
    {
        $materials = $this->Material_model->get_all();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Barcode');
        $sheet->setCellValue('C1', 'Material Name');
        $sheet->setCellValue('D1', 'Unit');
        $sheet->setCellValue('E1', 'Stock');
        $sheet->setCellValue('F1', 'Min Stock');

        $row = 2;
        foreach ($materials as $material) {
            $sheet->setCellValue('A' . $row, $material['id']);
            $sheet->setCellValue('B' . $row, $material['barcode']);
            $sheet->setCellValue('C' . $row, $material['material_name']);
            $sheet->setCellValue('D' . $row, $material['unit']);
            $sheet->setCellValue('E' . $row, $material['stock']);
            $sheet->setCellValue('F' . $row, $material['min_stock']);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'materials_' . date('YmdHis') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $writer->save('php://output');
    }

    // 2️⃣ IMPORT DATA DARI EXCEL KE DATABASE
    public function import()
    {
        $file_mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
        
        if (isset($_FILES['file']['name']) && in_array($_FILES['file']['type'], $file_mimes)) {
            $file = $_FILES['file']['tmp_name'];
            $spreadsheet = IOFactory::load($file);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();

            foreach ($sheetData as $key => $row) {
                if ($key == 0) continue; // Lewati header
                
                $data = [
                    'id' => $row[0],
                    'barcode' => $row[1],
                    'material_name' => $row[2],
                    'unit' => $row[3],
                    'stock' => (int)$row[4],
                    'min_stock' => (int)$row[5]
                ];

                $this->Material_model->update_material($data);
            }
            echo json_encode(["status" => "success", "message" => "Data berhasil diperbarui"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Format file tidak valid"]);
        }
    }

    // 3️⃣ HAPUS DATA BERDASARKAN FILE EXCEL
    public function delete_by_excel()
    {
        $file_mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
        
        if (isset($_FILES['file']['name']) && in_array($_FILES['file']['type'], $file_mimes)) {
            $file = $_FILES['file']['tmp_name'];
            $spreadsheet = IOFactory::load($file);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();

            foreach ($sheetData as $key => $row) {
                if ($key == 0) continue; // Lewati header
                $this->Material_model->delete_material($row[0]); // Hapus berdasarkan ID
            }
            echo json_encode(["status" => "success", "message" => "Data berhasil dihapus"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Format file tidak valid"]);
        }
    }
}
