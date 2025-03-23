<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('User_model');
    }

    public function index() {
        if ($this->session->userdata('npk')) {
            redirect('user');
        }

        $this->form_validation->set_rules('npk', 'NPK', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'I-MAT - Login';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/login');
            $this->load->view('templates/auth_footer');
        } else {
            $this->_login();
        }
    }

    private function _login() {
        $npk = $this->input->post('npk');
        $password = $this->input->post('password');
    
        $user = $this->User_model->getUserByNpk($npk);
    
        if ($user) {
            if (password_verify($password, $user['password'])) {
                $data = [
                    'users_id' => $user['id'],  // Simpan ID user ke session
                    'npk' => $user['npk'],
                    'role_id' => $user['role_id']
                ];
                $this->session->set_userdata($data);
    
                if ($user['role_id'] == 1) {
                    redirect('admin');
                } else {
                    redirect('user');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger">Password salah!</div>');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">NPK tidak terdaftar!</div>');
            redirect('auth');
        }
    }
    

    public function registration() {
        if ($this->session->userdata('npk')) {
            redirect('dashboard');
        }

        $this->form_validation->set_rules('name', 'Name', 'required|trim');
        $this->form_validation->set_rules('npk', 'NPK', 'required|trim|is_unique[users.npk]');
        $this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[6]|matches[password2]');
        $this->form_validation->set_rules('password2', 'Password Confirmation', 'required|matches[password1]');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'I-MAT - Registration';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/registration');
            $this->load->view('templates/auth_footer');
        } else {
            $data = [
                'name' => htmlspecialchars($this->input->post('name', true)),
                'npk' => htmlspecialchars($this->input->post('npk', true)),
                'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
                'role_id' => 2, // Default user role
                'date_created' => date('Y-m-d H:i:s')
            ];

            $this->User_model->insertUser($data);

            $this->session->set_flashdata('message', '<div class="alert alert-success">Registrasi berhasil. Silakan login.</div>');
            redirect('auth');
        }
    }

    public function redirect_user() {
        if (!$this->session->userdata('npk')) {
            redirect('auth');
        }

        if ($this->session->userdata('role_id') == 1) {
            redirect('admin/index'); // Admin
        } else {
            redirect('user/index'); // User biasa
        }
    }

    public function blocked() {
        $data['title'] = 'Access Denied';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('auth/blocked', $data);
        $this->load->view('templates/footer');
    }
    

    

    public function logout() {
        $this->session->unset_userdata('npk');
        $this->session->unset_userdata('role_id');

        $this->session->set_flashdata('message', '<div class="alert alert-success">Anda telah logout!</div>');
        redirect('auth');
    }
}
