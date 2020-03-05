<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use chriskacerguis\RestServer\RestController;
Class Pelanggan extends RestController{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('PelangganModel');
        $this->load->library('form_validation');
    }

    public function index_get(){
        return $this->returnData($this->db->get_where('pelanggan', ["aktif" => 1])->result(), false);
    }

    public function all_get(){
        return $this->returnData($this->db->get('pelanggan')->result(), false);
    }

    public function index_post($id = null){
        $validation = $this->form_validation;
        $rule = $this->PelangganModel->rules();
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
        $pelanggan = new PelangganData();
        $pelanggan->nama = $this->post('nama');
        $pelanggan->alamat = $this->post('alamat');
        $pelanggan->tanggal_lahir = $this->post('tanggal_lahir');
        $pelanggan->telp = $this->post('telp');
        $pelanggan->created_by = $this->post('created_by');
        if($id == null){
            $response = $this->PelangganModel->store($pelanggan);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function update_post($id = null){
        $validation = $this->form_validation;
        $rule = $this->PelangganModel->rules();
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
        $pelanggan = new PelangganData();
        $pelanggan->nama = $this->post('nama');
        $pelanggan->alamat = $this->post('alamat');
        $pelanggan->tanggal_lahir = $this->post('tanggal_lahir');
        $pelanggan->telp = $this->post('telp');
        $pelanggan->modified_by = $this->post('modified_by');
        if($id != null){
            $response = $this->PelangganModel->update($pelanggan,$id);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function delete_post($id = null){
        $validation = $this->form_validation;
        $rule = $this->PelangganModel->rules();
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
        $pelanggan = new PelangganData();
        $pelanggan->delete_by = $this->post('delete_by');
        if($id != null){
            $response = $this->PelangganModel->softDelete($pelanggan,$id);
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

    public function returnData($msg,$error){
        $response['error']=$error;
        $response['message']=$msg;
        return $this->response($response);
    }
}

Class PelangganData{
    public $nama;
    public $alamat;
    public $tanggal_lahir;
    public $telp;
    public $created_at;
    public $created_by;
    public $modified_at;
    public $modified_by;
    public $delete_at;
    public $delete_by;
    public $aktif;
}