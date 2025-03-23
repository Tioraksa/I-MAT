<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fas extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Fas_model');

        // Cek apakah user sudah login
        if (!$this->session->userdata('npk')) {
            redirect('auth'); // Redirect ke login jika tidak login
        }

        // Ambil data user
        $user = $this->User_model->get_by_id($this->session->userdata('npk'));

        // Cek apakah user bukan admin (role_id != 1)
        if (!$user || $user['role_id'] != 2) {
            redirect('dashboard'); // Redirect jika bukan admin
        }
    }

    public function index() {
        $data['fas'] = $this->Fas_model->get_all_fas();
        $data['title'] = 'I-MAT - Fas';
        $data['users'] = $this->db->get_where('users', ['npk' => $this->session->userdata('npk')])->row_array();
        
            
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('fas/index', $data);
            $this->load->view('templates/footer');
    }

    public function get_materials($model) {
        $this->load->model('Material_model');
        $materials = $this->Material_model->get_by_model($model);
        echo json_encode($materials);
    }
}
