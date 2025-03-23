<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    

    public function getUserByNpk($npk) {
        return $this->db->get_where('users', ['npk' => $npk])->row_array();
    }

    public function insertUser($data) {
        return $this->db->insert('users', $data);
    }

    public function get_by_id($npk) {
        return $this->db->get_where('users', ['npk' => $npk])->row_array();
    }
    

    // Ambil semua data user (jika dibutuhkan)
    public function get_all() {
        return $this->db->get('users')->result_array();
    }

    // Cek login berdasarkan username/email dan password
    public function check_login($username, $password) {
        $this->db->where('username', $username);
        $user = $this->db->get('users')->row_array();

        if ($user) {
            // Verifikasi password (gunakan password_hash() di database)
            if (password_verify($password, $user['password'])) {
                return $user;
            }
        }
        return false;
    }
}
