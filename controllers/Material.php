<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Material extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Material_model');
    }

    // Menampilkan Supply List Material
    public function index() {
        $data['title'] = 'I-MAT - List Material';
        $data['materials'] = $this->Material_model->get_index();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('material/index', $data);
        $this->load->view('templates/footer');
    }

    public function reduce_stock($material_id, $quantity) {
        $this->db->where('id', $material_id);
        $material = $this->db->get('materials')->row();
    
        if ($material->stock >= $quantity) {
            $this->db->set('stock', 'stock - ' . (int)$quantity, FALSE);
            $this->db->where('id', $material_id);
            $this->db->update('materials');
            return true;
        } else {
            return false; // Stok tidak mencukupi
        }
    }
   
    
    
    // Menampilkan Laporan Pengeluaran dan Pemasukan Material
    public function laporan() {
        $data['title'] = 'Laporan Pengeluaran & Pemasukan Material';
        $data['transactions'] = $this->Material_model->get_transactions();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('material/laporan', $data);
        $this->load->view('templates/footer');
    }

    // Monitoring stok material
    public function monitoring() {
        $data['title'] = 'Monitoring Stok Material';
        $data['stock'] = $this->Material_model->get_stock_status();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('material/monitoring', $data);
        $this->load->view('templates/footer');
    }

    // Prediksi kebutuhan material
    public function prediksi() {
        $data['title'] = 'Prediksi Kebutuhan Material';
        $data['predictions'] = $this->Material_model->predict_material();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('material/prediksi', $data);
        $this->load->view('templates/footer');
    }
}   