<?php
class Supply_model extends CI_Model {
    public function get_all() {
        $this->db->select('s.id, u.name AS nama, s.model, f.lot, s.tanggal, m.material_name, s.quantity, m.unit');
        $this->db->from('supply_materials s');
        $this->db->join('materials m', 's.material_id = m.id', 'left');
        $this->db->join('users u', 's.nama = u.npk', 'left');  // Ambil nama dari users berdasarkan npk
        $this->db->join('fas f', 's.model = f.model AND DATE(s.tanggal) = f.tanggal', 'left'); // Ambil lot dari fas yang berjalan
        $this->db->order_by('s.tanggal', 'DESC'); // Urutkan dari terbaru
        return $this->db->get()->result_array();
    }
    
    public function get_today_supply()
{
    $today = date('Y-m-d'); // Ambil tanggal hari ini

    $this->db->select('
        u.name AS nama, 
        s.model, 
        f.lot, 
        s.tanggal, 
        m.material_name AS material_name, 
        s.quantity, 
        COALESCE(mp.quantity, 0) AS standar, 
        (s.quantity - COALESCE(mp.quantity, 0)) AS selisih
    ');
    $this->db->from('supply_materials s');
    $this->db->join('users u', 's.nama = u.npk', 'left');  // Ambil nama dari users berdasarkan npk
    $this->db->join('materials m', 's.material_id = m.id', 'left');
    $this->db->join('material_predictions mp', 's.model = mp.model AND s.material_id = mp.material_id', 'left');
    $this->db->join('fas f', 's.model = f.model AND f.tanggal = NOW()::DATE', 'left');
    $this->db->where('DATE(s.tanggal)', $today);
    $this->db->order_by('s.tanggal', 'DESC');

    $query = $this->db->get();
    return $query->result_array();
}




   


    public function count_supply() {
        return $this->db->count_all('supply_materials');
    }
    

    public function insert($data) {
        return $this->db->insert('supply_materials', $data);
    }

    public function insert_supply($data)
{
    return $this->db->insert('supply_materials', $data);
}


    public function get_supply_with_standar() {
    $this->db->select('
        s.nama, 
        s.model, 
        f.lot, 
        s.tanggal, 
        m.material_name, 
        s.quantity, 
        COALESCE(mp.quantity, 0) AS standar, 
        (s.quantity - COALESCE(mp.quantity, 0)) AS selisih
    ');
    $this->db->from('supply_materials s');
    $this->db->join('material_predictions mp', 's.model = mp.model AND s.material_id = mp.material_id', 'left');
    $this->db->join('materials m', 's.material_id = m.id', 'left');
    $this->db->join('fas f', 's.model = f.model AND f.tanggal = NOW()::DATE', 'left'); // Perbaikan fungsi tanggal
    $this->db->order_by('s.tanggal', 'DESC');

    $query = $this->db->get();
    return $query->result_array();
}
    
    

    

    

public function add_supply($data) {
    // Ambil data pengguna yang sedang login berdasarkan NPK
    $user = $this->User_model->get_by_id($this->session->userdata('npk'));

    if (!$user) {
        return ['status' => 'error', 'message' => 'User tidak ditemukan!'];
    }

    // Ambil material berdasarkan ID
    $this->db->where('id', $data['material_id']);
    $material = $this->db->get('materials')->row_array();

    if (!$material) {
        return ['status' => 'error', 'message' => 'Material tidak ditemukan!'];
    }

    // Cek apakah stok cukup
    if ($material['stock'] < $data['quantity']) {
        return ['status' => 'error', 'message' => 'Stok tidak mencukupi! Stok saat ini: ' . $material['stock']];
    }

    // Mulai transaksi database
    $this->db->trans_start();

    // Simpan data supply ke tabel supply_materials
    $this->db->insert('supply_materials', [
        'nama'        => $user['name'], // Ambil nama dari tabel users
        'npk'         => $user['npk'], // Simpan NPK untuk referensi
        'model'       => $data['model'],
        'material_id' => $data['material_id'],
        'quantity'    => $data['quantity'],
        'tanggal'     => time(), // Simpan sebagai UNIX timestamp (integer)
        'lot'         => $data['lot']
    ]);

    // Kurangi stok di tabel materials
    $this->db->set('stock', 'stock - ' . (int) $data['quantity'], FALSE);
    $this->db->where('id', $data['material_id']);
    $this->db->update('materials');

    // Selesaikan transaksi database
    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE) {
        return ['status' => 'error', 'message' => 'Gagal menyimpan data supply!'];
    }

    return ['status' => 'success', 'message' => 'Supply berhasil ditambahkan dan stok berkurang!'];
}
}