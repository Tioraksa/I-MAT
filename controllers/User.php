<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Fas_model');
        $this->load->model('Material_model');
        $this->load->model('Supply_model');
        $this->load->model('User_model');
        $this->load->database(); // Pastikan database dimuat

        // Cek apakah user sudah login
        if (!$this->session->userdata('npk')) {
            redirect('auth'); // Redirect ke login jika tidak login
        }

        // Ambil data user
        $user = $this->User_model->get_by_id($this->session->userdata('npk'));

        // Cek apakah user bukan operator (role_id != 2)
        if (!$user || $user['role_id'] != 2) {
            redirect('auth/blocked'); // Redirect jika bukan admin
        }
    }

    // Halaman utama profil
    public function index() {
        $data['title'] = 'I-MAT - Dashboard';
        $data['users'] = $this->User_model->get_by_id($this->session->userdata('npk'));

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('user/index', $data);
        $this->load->view('templates/footer');
    }

    // FAS
    public function fas() {
        $data['fas'] = $this->Fas_model->get_all_fas();
        $data['title'] = 'I-MAT - Fas';
        $data['users'] = $this->User_model->get_by_id($this->session->userdata('npk'));
        $selected_date = $this->input->get('date') ? $this->input->get('date') : date('Y-m-d');

        // Ambil data berdasarkan tanggal yang dipilih
        $data['fas'] = $this->Material_model->get_fas_by_date($selected_date);
        $data['selected_date'] = $selected_date;

        // Hitung tanggal sebelumnya & sesudahnya
        $data['prev_date'] = date('Y-m-d', strtotime($selected_date . ' -1 day'));
        $data['next_date'] = date('Y-m-d', strtotime($selected_date . ' +1 day'));
        
            
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('user/fas', $data);
            $this->load->view('templates/footer');
    }

    public function material_predictions($model = null) {
        $data['title'] = 'Kebutuhan Material';
        $data['materials'] = $this->Material_model->get_materials_by_model($model);
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('user/material_predictions', $data);
        $this->load->view('templates/footer');
    }

    public function get_material_data() {
        $model = $this->input->get('model');
    
        // Ambil data material berdasarkan model yang dipilih
        $this->load->model('Material_model');
        $data = $this->Material_model->get_materials_by_model($model);
    
        echo json_encode($data);
    }

    public function get_materials_by_model()
{
    $model = $this->input->get('model'); // Ambil model dari request
    $this->load->model('Material_model');
    $materials = $this->Material_model->get_materials_by_model($model);

    echo json_encode($materials); // Kirim data dalam format JSON
}

     // Mendapatkan data material berdasarkan model
     public function get_materials() {
        $model = $this->input->get('model'); // Ambil parameter model dari GET

        if (!$model) {
            echo json_encode([]); // Jika tidak ada model, kembalikan array kosong
            return;
        }

        $query = $this->db->query("
            SELECT m.material_name, mm.quantity, m.unit 
            FROM model_materials mm
            JOIN materials m ON mm.material_id = m.id
            WHERE mm.model = ?", [$model]);

        $materials = $query->result_array();

        echo json_encode($materials);
    }

    // Material
    public function material() {
        $data['title'] = 'I-MAT - List Material';
        $data['materials'] = $this->Material_model->get_index();
        $data['users'] = $this->User_model->get_by_id($this->session->userdata('npk'));

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('user/material', $data);
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

    // Supply
    public function supply() {
        $data['supply'] = $this->Supply_model->get_all();
        $data['models'] = $this->Material_model->get_fas_models();
        $data['users'] = $this->User_model->get_by_id($this->session->userdata('npk'));

        $data['title'] = 'I-MAT - Supply Material';


        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('user/supply', $data);
        $this->load->view('templates/footer');
    }

    public function get_material_by_barcode() {
        $barcode = $this->input->post('barcode');
    
        $this->load->model('Material_model');
        $material = $this->Material_model->get_by_barcode($barcode);
    
        if ($material) {
            echo json_encode([
                "status" => "success",
                "material_id" => $material['id'], // Kirim Material ID
                "material_name" => $material['material_name']
            ]);
        } else {
            echo json_encode(["status" => "error"]);
        }
    }
    
    

    public function add_supply()
{
    $this->load->model('Supply_model');
    $this->load->model('Material_model');

    $material_id = $this->input->post('material_id');
    $quantity = (int) $this->input->post('quantity');

    if (!$material_id || $quantity <= 0) {
        echo json_encode(["status" => "error", "message" => "Data tidak valid"]);
        return;
    }

    // Ambil data material untuk validasi stok
    $material = $this->Material_model->get_by_barcode($this->input->post('barcode'));
    if (!$material) {
        echo json_encode(["status" => "error", "message" => "Material tidak ditemukan"]);
        return;
    }

    if ($material['stock'] < $quantity) {
        echo json_encode(["status" => "error", "message" => "Stok tidak mencukupi"]);
        return;
    }

    // Tambahkan data supply
    $supply_data = [
        'nama' => $this->session->userdata('npk'),
        'model' => $this->input->post('model'),
        'tanggal' => date('Y-m-d H:i:s'),
        'material_id' => $material_id,
        'quantity' => $quantity
    ];

    $insert_id = $this->Supply_model->insert_supply($supply_data);

    if ($insert_id) {
        // Kurangi stok
        $update_stock = $this->Material_model->reduce_stock($material_id, $quantity);

        if ($update_stock) {
            echo json_encode(["status" => "success", "message" => "Supply berhasil ditambahkan dan stok dikurangi"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Gagal mengurangi stok"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal menambahkan supply"]);
    }
}



}