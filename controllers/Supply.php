<?php
class Supply extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Supply_model');
        $this->load->model('Material_model');
        $this->load->model('User_model');

        // Pastikan user sudah login
        if (!$this->session->userdata('npk')) {
            redirect('auth'); // Redirect ke halaman login
        }
    }

    public function index() {
        $data['supply'] = $this->Supply_model->get_all();
        $data['models'] = $this->Material_model->get_fas_models();
        $data['users'] = $this->User_model->get_by_id($this->session->userdata('npk'));

        $data['title'] = 'I-MAT - Supply Material';


        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('supply/index', $data);
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
?>