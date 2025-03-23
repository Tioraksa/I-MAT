<?php
class Material_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all() {
        return $this->db->get('materials')->result_array();
    }

    public function update_material($data)
    {
        $this->db->where('id', $data['id']);
        return $this->db->update('materials', $data);
    }

    public function delete_material($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('materials');
    }

    public function get_chart_data() {
        // Ambil data stok dari tabel `materials`
        $this->db->select('material_name, stock, min_stock');
        $materials = $this->db->get('materials')->result_array();

        // Ambil data penggunaan material dari `material_transactions`
        $this->db->select("m.material_name, SUM(mt.quantity) as total_usage");
        $this->db->from("material_transactions mt");
        $this->db->join("materials m", "mt.material_id = m.id");
        $this->db->where("mt.type", "pengeluaran"); // Hanya ambil pengeluaran
        $this->db->group_by("m.material_name");
        $transactions = $this->db->get()->result_array();

        return [
            'materials' => $materials,
            'transactions' => $transactions
        ];
    }

    public function get_materials_by_category($category) {
        return $this->db->select('m.id, m.material_name, m.unit, mp.quantity')
                        ->from('material_predictions mp')
                        ->join('materials m', 'mp.material_id = m.id')
                        ->where('m.material_name ILIKE', $category . '%') // Sesuaikan dengan pola nama material
                        ->get()
                        ->result_array();
    }

    
    public function get_by_model($model)
    {
        $this->db->select('m.material_name, mp.quantity, m.unit');
        $this->db->from('material_predictions mp');
        $this->db->join('materials m', 'mp.material_id = m.id', 'left');
        $this->db->where('LEFT(mp.model, 3) =', $model); // Ambil 3 huruf pertama

        $query = $this->db->get();

        // Debugging: Cek hasil query
        log_message('debug', 'Query get_by_model: ' . $this->db->last_query());

        return $query->result_array();
    }
    

    public function get_fas_by_today()
    {
        $this->db->where('tanggal', date('Y-m-d')); // Ambil data hanya untuk tanggal hari ini
        return $this->db->get('fas')->result_array();
    }

    public function get_fas_by_date($tanggal)
{
    return $this->db->where('tanggal', $tanggal)->get('fas')->result_array();
}

public function get_material_predictions_by_fas_date($date) {
    $this->db->select('mp.model, f.lot, m.material_name, mp.quantity, m.unit');
    $this->db->from('material_predictions mp');
    $this->db->join('fas f', 'mp.model = f.model');
    $this->db->join('materials m', 'mp.material_id = m.id'); // Gabung dengan tabel materials
    $this->db->where('f.tanggal', $date);
    return $this->db->get()->result_array();
}






    public function add_stock($barcode, $quantity) {
        $this->db->where('barcode', $barcode);
        $material = $this->db->get('materials')->row();
    
        if ($material) {
            $this->db->set('stock', 'stock + ' . (int)$quantity, FALSE);
            $this->db->where('barcode', $barcode);
            $this->db->update('materials');
        }
    }

    public function save_monitoring($data) {
        return $this->db->insert('material_transactions', $data);
    }
    
    public function get_pengeluaran() {
        $this->db->select('p.barcode, m.material_name, p.quantity, p.tanggal, u.name AS operator');
        $this->db->from('pengeluaran_material p');
        $this->db->join('materials m', 'p.material_id = m.id');
        $this->db->join('users u', 'p.npk = u.npk');
        return $this->db->get()->result_array();
    }
    
    // **Update Stok Material**
    public function update_stock($material_id, $quantity, $type) {
        if ($type == 'pemasukan') {
            $this->db->set('stock', 'stock + ' . (int)$quantity, FALSE);
        } elseif ($type == 'pengeluaran') {
            $this->db->set('stock', 'stock - ' . (int)$quantity, FALSE);
        }

        $this->db->where('id', $material_id);
        return $this->db->update('materials');
    }

    // Ambil data monitoring input/output material
    public function get_monitoring_data() {
        $this->db->select('material_transactions.*, materials.material_name, materials.unit');
        $this->db->from('material_transactions');
        $this->db->join('materials', 'materials.id = material_transactions.material_id');
        $this->db->order_by('material_transactions.date_added', 'DESC');
        return $this->db->get()->result_array();
    }

    public function get_supply_data()
{
    $this->db->select('s.*, m.material_name, m.unit');
    $this->db->from('supply s');
    $this->db->join('materials m', 'm.id = s.material_id', 'left');
    $this->db->order_by('s.tanggal', 'DESC');
    return $this->db->get()->result_array();
}

    
    

public function get_index($search = null) {
    $this->db->select('*');
    $this->db->from('materials');

    if ($search) {
        $this->db->like('barcode', $search);
        $this->db->or_like('material_name', $search);
        $this->db->or_like('unit', $search);
    }

    return $this->db->get()->result_array();
}


    public function get_fas_models() {
        $this->db->distinct();
        $this->db->select('model');
        $query = $this->db->get('fas');
        return $query->result_array();
    }
    
    

    // Mengambil semua material
    public function get_all_materials() {
        return $this->db->get('materials')->result_array();
    }

    // Mengambil data material berdasarkan ID
    public function get_material_by_id($id) {
        return $this->db->get_where('materials', ['id' => $id])->row_array();
    }

    // Mengambil data material berdasarkan barcode
    public function get_by_barcode($barcode) {
        return $this->db->get_where('materials', ['barcode' => $barcode])->row_array();
    }

    // Mengurangi stok material
    public function reduce_stock($id, $quantity) {
        $this->db->set('stock', 'stock - ' . (int)$quantity, FALSE);
        $this->db->where('id', $id);
        return $this->db->update('materials');
    }

    // Menambah stok material
    public function increase_stock($id, $quantity) {
        $this->db->set('stock', 'stock + ' . (int)$quantity, FALSE);
        $this->db->where('id', $id);
        return $this->db->update('materials');
    }

    // Mendapatkan semua model material yang tersedia
    public function get_models() {
        $this->db->distinct();
        $this->db->select('model');
        return $this->db->get('materials')->result_array();
    }

    // Mendapatkan daftar material dengan stok di bawah minimum
    public function get_low_stock_materials() {
        $this->db->where('stock <= min_stock');
        return $this->db->get('materials')->result_array();
    }
}
?>
