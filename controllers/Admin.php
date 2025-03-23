<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Fas_model');
        $this->load->model('Material_model');
        $this->load->model('Supply_model');
        $this->load->model('User_model');
        date_default_timezone_set('Asia/Jakarta'); // Pastikan timezone Jakarta digunakan

        $this->load->database(); // Pastikan database dimuat

        // Cek apakah user sudah login
        if (!$this->session->userdata('npk')) {
            redirect('auth'); // Redirect ke login jika tidak login
        }

        // Ambil data user
        $user = $this->User_model->get_by_id($this->session->userdata('npk'));

        // Cek apakah user bukan admin (role_id != 1)
        if (!$user || $user['role_id'] != 1) {
            redirect('auth/blocked'); // Redirect jika bukan admin
        }
    }

    public function index()
{
    date_default_timezone_set('Asia/Jakarta');
    $today = date('Y-m-d');

    $this->load->model('User_model');
    $this->load->model('Fas_model');
    $this->load->model('Supply_model');
    $this->load->model('Material_model');

    $data['title'] = 'I-MAT - Dashboard';
    $data['materials'] = $this->Material_model->get_index();
    $data['supply'] = $this->Supply_model->get_today_supply(); // Ambil supply hari ini
    $data['models'] = $this->Material_model->get_fas_models();
    $data['material_predictions'] = $this->Material_model->get_material_predictions_by_fas_date($today);

    // Ambil user login
    $npk = $this->session->userdata('npk');
    if (!$npk) {
        redirect('auth');
    }
    $data['users'] = $this->User_model->get_by_id($npk);

    // Hitung total data
    $data['total_fas'] = $this->Fas_model->count_fas();
    $data['total_supply'] = $this->Supply_model->count_supply();

    // Ambil data FAS untuk hari ini
    $data['fas_today'] = $this->Fas_model->get_fas_by_date($today);

    // Load tampilan
    $this->load->view('templates/header', $data);
    $this->load->view('templates/sidebar', $data);
    $this->load->view('templates/topbar', $data);
    $this->load->view('admin/index', $data);
    $this->load->view('templates/footer');
}

    

    public function get_chart_data() {
        $this->load->model('Material_model');
        $chart_data = $this->Material_model->get_chart_data();
        echo json_encode($chart_data);
    }

    // FAS
    public function fas() {
        $this->load->model('Fas_model');
        $data['fas'] = $this->Fas_model->get_all_fas();
        $data['title'] = 'I-MAT - Fas';
        $data['users'] = $this->User_model->get_by_id($this->session->userdata('npk'));
        // Ambil tanggal dari parameter GET atau gunakan tanggal hari ini sebagai default
        $tanggal = $this->input->get('tanggal') ?? date('Y-m-d');
        date_default_timezone_set('Asia/Jakarta'); // Set zona waktu ke Jakarta
        $today = date('Y-m-d'); // Ambil tanggal hari ini
        // Ambil data FAS untuk hari ini
        $data['fas_today'] = $this->Fas_model->get_fas_by_date($today);

        // Ambil tanggal dari parameter GET, jika tidak ada gunakan hari ini
        $selected_date = $this->input->get('date') ? $this->input->get('date') : date('Y-m-d');
        // Ambil data berdasarkan tanggal
        $data['fas'] = $this->Material_model->get_fas_by_date($tanggal);
        $data['tanggal'] = $tanggal; // Kirim tanggal ke view

        // Cek apakah ada data di tanggal yang diminta
        $data['fas'] = $this->Fas_model->get_fas_by_date($tanggal);
        if (empty($data['fas'])) {
            log_message('debug', 'Tidak ada data FAS pada tanggal: ' . $tanggal);
        }

        $data['fas'] = $this->Material_model->get_fas_by_date($selected_date);
        $data['selected_date'] = $selected_date;

        // Hitung tanggal sebelumnya & sesudahnya
        $data['prev_date'] = date('Y-m-d', strtotime($selected_date . ' -1 day'));
        $data['next_date'] = date('Y-m-d', strtotime($selected_date . ' +1 day'));
    
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/fas', $data);
        $this->load->view('templates/footer');
    }
    
    public function add_fas() {
        $this->load->model('Fas_model');
    
        $data = [
            'warna_lot' => $this->input->post('warna_lot'),
            'tanggal' => $this->input->post('tanggal'),
            'invoice' => $this->input->post('invoice'),
            'lot' => $this->input->post('lot'),
            'model' => $this->input->post('model'),
            'ckd_set_name' => $this->input->post('ckd_set_name'),
            'status' => 'PLAN'
        ];
    
        $this->Fas_model->add_fas($data);
        redirect('admin/fas');
    }

    public function material_predictions($model = null) {
        $data['title'] = 'Kebutuhan Material';
        $data['materials'] = $this->Material_model->get_by_model($model);
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/material_predictions', $data);
        $this->load->view('templates/footer');
    }

    public function get_material_data() {
        $model = $this->input->get('model');
    
        // Ambil data material berdasarkan model yang dipilih
        $this->load->model('Material_model');
        $data = $this->Material_model->get_by_model($model);
    
        echo json_encode($data);
    }

    public function get_materials_by_model()
{
    $this->load->model('Material_model');

    $model = $this->input->get('model', true);

    if (empty($model)) {
        echo json_encode(["error" => "Model tidak boleh kosong"]);
        return;
    }

    $materials = $this->Material_model->get_by_model($model);

    if (!empty($materials)) {
        echo json_encode($materials);
    } else {
        echo json_encode(["error" => "Tidak ada data ditemukan"]);
    }
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
        // Ambil parameter pencarian dari GET
    $search = $this->input->get('search', true);
    $data['materials'] = $this->Material_model->get_index($search);
        $data['users'] = $this->User_model->get_by_id($this->session->userdata('npk'));

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/material', $data);
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

    // **Tambah Stok Material**
    public function add_stock() {
        $barcode = $this->input->post('barcode');
        $quantity = (int) $this->input->post('quantity');

        // Cek apakah barcode valid
        $material = $this->Material_model->get_by_barcode($barcode);
        if (!$material) {
            $this->session->set_flashdata('error', 'Material tidak ditemukan!');
            redirect('admin/material');
        }

        $material_id = $material['id'];
        $type = 'pemasukan'; // Menandakan ini transaksi penambahan stok

        // Update stok
        $this->Material_model->update_stock($material_id, $quantity, $type);

        // Simpan ke monitoring transaksi
        $data_transaction = [
            'material_id' => $material_id,
            'barcode' => $barcode,
            'type' => $type,
            'quantity' => $quantity,
            'unit' => $material['unit'], // Pastikan unit diambil dari tabel materials
            'date_added' => date('Y-m-d H:i:s')
        ];
        $this->Material_model->save_monitoring($data_transaction);
        

        $this->session->set_flashdata('success', 'Stok material berhasil ditambahkan!');
        redirect('admin/material');
    }

    
    
    public function add_transaction() {
        $this->load->model('Material_model');
    
        $barcode = $this->input->post('barcode');
        $type = $this->input->post('type'); // 'pemasukan' atau 'pengeluaran'
        $quantity = (int) $this->input->post('quantity');
    
        // Ambil data material berdasarkan barcode
        $material = $this->Material_model->get_by_barcode($barcode);
        
        // Cek apakah material ditemukan
        if (!$material) {
            $this->session->set_flashdata('error', 'Material tidak ditemukan.');
            redirect('material/monitoring'); // Redirect ke halaman monitoring jika gagal
            return;
        }
    
        $material_id = $material['id']; // Ambil ID material
    
        // Buat array data transaksi
        $data_transaction = [
            'material_id' => $material_id,
            'barcode' => $barcode,
            'type' => $type,
            'quantity' => $quantity,
            'date_added' => date('Y-m-d H:i:s')
        ];
        
        // Simpan ke database
        $this->Material_model->save_monitoring($data_transaction);
    
        // Redirect dengan pesan sukses
        $this->session->set_flashdata('success', 'Transaksi berhasil ditambahkan.');
        redirect('material/monitoring');
    }

    public function monitoring() {
        $this->load->model('Material_model');
        $data['supply'] = $this->Supply_model->get_all();
    
        $data['title'] = 'Monitoring Material';
        $data['users'] = $this->User_model->get_by_id($this->session->userdata('npk'));
    
        // Ambil data transaksi material dari model
        $data['transactions'] = $this->Material_model->get_monitoring_data();
        // $data['supply'] = $this->Material_model->get_supply_data(); // Ambil data supply
    
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/monitoring', $data); // Pastikan view ini ada!
        $this->load->view('templates/footer');
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
        $this->load->view('admin/supply', $data);
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

    public function get_lot_by_model_today($model)
{
    $this->load->model('Fas_model');
    $lots = $this->Fas_model->get_lot_by_model_today($model);
    
    if (empty($lots)) {
        echo json_encode(["status" => "error", "message" => "Tidak ada lot yang berjalan hari ini"]);
        return;
    }

    echo json_encode($lots);
}

    
    

}