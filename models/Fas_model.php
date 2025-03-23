<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fas_model extends CI_Model {
    public function get_all_fas() {
        return $this->db->get('fas')->result_array();
    }

    public function add_fas($data) {
        return $this->db->insert('fas', $data);
    }

    public function count_fas() {
        return $this->db->count_all('fas');
    }

    public function get_fas_by_today()
    {
        $this->db->where('tanggal', date('Y-m-d')); // Ambil data hanya untuk tanggal hari ini
        return $this->db->get('fas')->result_array();
    }

    

public function get_fas_by_date($date) {
    return $this->db->where('tanggal', $date)->get('fas')->result_array();
}

public function get_lot_by_model_today($model)
{
    date_default_timezone_set('Asia/Jakarta'); // Pastikan zona waktu Jakarta
    $today = date('Y-m-d'); // Ambil tanggal hari ini

    return $this->db->select('lot')
                    ->from('fas')
                    ->where('model', $model)
                    ->where('tanggal', $today) // Ambil hanya FAS untuk hari ini
                    ->where('status', 'PLAN') // Hanya ambil FAS yang masih berjalan
                    ->get()
                    ->result_array();
}





    
}
