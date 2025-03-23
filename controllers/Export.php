<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Export extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Supply_model'); // Pastikan model sudah ada
        $this->load->library('Spreadsheet_Lib'); // Load library Spreadsheet
    }

    public function export_data() {
        $data = $this->Supply_model->get_all(); // Ambil data dari database

        if (empty($data)) {
            echo "Tidak ada data untuk diekspor.";
            return;
        }

        $filename = 'supply_data.xlsx';
        $this->spreadsheet_lib->createExcel($data, $filename);

        // Download file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        readfile($filename);
        unlink($filename); // Hapus file setelah didownload
    }
}
