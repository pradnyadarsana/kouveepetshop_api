<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use chriskacerguis\RestServer\RestController;
Class Pegawai extends RestController{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('PegawaiModel');
        $this->load->library('form_validation');
    }

    public function index_get(){
        return $this->returnData($this->db->get_where('pegawai', ["aktif" => 1])->result(), false);
    }

    public function nonAktif_get(){
        return $this->returnData($this->db->get_where('pegawai', ["aktif" => 0])->result(), false);
    }

    public function all_get(){
        return $this->returnData($this->db->get('pegawai')->result(), false);
    }

    public function search_get($id){
        return $this->returnData($this->db->get_where('pegawai', ["id_pegawai" => $id])->row(), false);
    }

    public function index_post($id = null){
        $validation = $this->form_validation;
        $rule = $this->PegawaiModel->rules();
        if($id == null){
            array_push($rule,
                [
                    'field' => 'nama',
                    'label' => 'nama',
                    'rules' => 'required'
                ],
                [
                    'field' => 'alamat',
                    'label' => 'alamat',
                    'rules' => 'required'
                ],
                [
                    'field' => 'tanggal_lahir',
                    'label' => 'tanggal_lahir',
                    'rules' => 'required'
                ],
                [
                    'field' => 'telp',
                    'label' => 'telp',
                    'rules' => 'required|numeric'
                ],
                [
                    'field' => 'username',
                    'label' => 'username',
                    'rules' => 'required|alpha_numeric|min_length[4]|is_unique[pegawai.username]'
                ],
                [
                    'field' => 'password',
                    'label' => 'password',
                    'rules' => 'required'
                ],
                [
                    'field' => 'role',
                    'label' => 'role',
                    'rules' => 'required'
                ],
                [
                    'field' => 'created_by',
                    'label' => 'created_by',
                    'rules' => 'required'
                ]
            );
        }
        $validation->set_rules($rule);
		if (!$validation->run()) {
			return $this->returnData($this->form_validation->error_array(), true);
        }
        $pegawai = new PegawaiData();
        $pegawai->nama = $this->post('nama');
        $pegawai->alamat = $this->post('alamat');
        $pegawai->tanggal_lahir = $this->post('tanggal_lahir');
        $pegawai->telp = $this->post('telp');
        $pegawai->username = $this->post('username');
        $pegawai->password = $this->post('password');
        $pegawai->role = $this->post('role');
        $pegawai->created_by = $this->post('created_by');
        if($id == null){
            $response = $this->PegawaiModel->store($pegawai);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function update_post($id = null){
        $validation = $this->form_validation;
        $rule = $this->PegawaiModel->rules();
        if($id != null){
            array_push($rule,
                [
                    'field' => 'nama',
                    'label' => 'nama',
                    'rules' => 'required'
                ],
                [
                    'field' => 'alamat',
                    'label' => 'alamat',
                    'rules' => 'required'
                ],
                [
                    'field' => 'tanggal_lahir',
                    'label' => 'tanggal_lahir',
                    'rules' => 'required'
                ],
                [
                    'field' => 'telp',
                    'label' => 'telp',
                    'rules' => 'required|numeric'
                ],
                [
                    'field' => 'username',
                    'label' => 'username',
                    'rules' => 'required|alpha_numeric|min_length[4]'
                ],
                [
                    'field' => 'role',
                    'label' => 'role',
                    'rules' => 'required'
                ],
                [
                    'field' => 'modified_by',
                    'label' => 'modified_by',
                    'rules' => 'required'
                ]
            );
        }
        $validation->set_rules($rule);
        if (!$validation->run()) {
            return $this->returnData($this->form_validation->error_array(), true);
        }
        $pegawai = new PegawaiData();
        $pegawai->nama = $this->post('nama');
        $pegawai->alamat = $this->post('alamat');
        $pegawai->tanggal_lahir = $this->post('tanggal_lahir');
        $pegawai->telp = $this->post('telp');
        $pegawai->username = $this->post('username');
        $pegawai->role = $this->post('role');
        $pegawai->modified_by = $this->post('modified_by');
        if($id != null){
            $response = $this->PegawaiModel->update($pegawai,$id);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function delete_post($id = null){
        $validation = $this->form_validation;
        $rule = $this->PegawaiModel->rules();
        if($id != null){
            array_push($rule,
                [
                    'field' => 'delete_by',
                    'label' => 'delete_by',
                    'rules' => 'required'
                ]
            );
        }
        $validation->set_rules($rule);
        if (!$validation->run()) {
            return $this->returnData($this->form_validation->error_array(), true);
        }
        $pegawai = new PegawaiData();
        $pegawai->delete_by = $this->post('delete_by');
        if($id != null){
            $response = $this->PegawaiModel->softDelete($pegawai,$id);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    // public function index_delete($id = null){
    //     if($id == null){
	// 		return $this->returnData('Parameter Id Tidak Ditemukan', true);
    //     }
    //     $response = $this->PricelistModel->destroy($id);
    //     return $this->returnData($response['msg'], $response['error']);
    // }

    public function updatepass_post($id = null){
        $validation = $this->form_validation;
        $rule = $this->PegawaiModel->rules();
        if($id != null){
            array_push($rule,
                [
                    'field' => 'password',
                    'label' => 'password',
                    'rules' => 'required'
                ],
                [
                    'field' => 'modified_by',
                    'label' => 'modified_by',
                    'rules' => 'required'
                ]
            );
        }
        $validation->set_rules($rule);
        if (!$validation->run()) {
            return $this->returnData($this->form_validation->error_array(), true);
        }
        $pegawai = new PegawaiData();
        $pegawai->password = $this->post('password');
        $pegawai->modified_by = $this->post('modified_by');
        if($id != null){
            $response = $this->PegawaiModel->change_password($pegawai,$id);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function auth_post(){
        $validation = $this->form_validation;
        $rule = $this->PegawaiModel->rules();
        array_push($rule,
            [
                'field' => 'username',
                'label' => 'username',
                'rules' => 'required'
            ],
            [
                'field' => 'password',
                'label' => 'password',
                'rules' => 'required'
            ]
        );
        $validation->set_rules($rule);
        if (!$validation->run()) {
            return $this->returnData($this->form_validation->error_array(), true);
        }        

        $pegawai = new PegawaiData();
        $pegawai->username = $this->post('username');
        $pegawai->password = $this->post('password');

        $response = $this->PegawaiModel->verify($pegawai);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function returnData($msg,$error){
        $response['error']=$error;
        $response['message']=$msg;
        return $this->response($response);
    }
}

Class PegawaiData{
    public $nama;
    public $alamat;
    public $tanggal_lahir;
    public $telp;
    public $username;
    public $password;
    public $role;
    public $created_at;
    public $created_by;
    public $modified_at;
    public $modified_by;
    public $delete_at;
    public $delete_by;
    public $aktif;
}