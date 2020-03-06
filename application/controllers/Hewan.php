<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use chriskacerguis\RestServer\RestController;
Class Hewan extends RestController{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('HewanModel');
        $this->load->library('form_validation');
    }

    public function index_get(){
        $this->db->select('id_hewan, hewan.id_pelanggan, pelanggan.nama "nama_pelanggan", pelanggan.alamat "alamat_pelanggan", 
                        pelanggan.tanggal_lahir "tanggal_lahir_pelanggan", pelanggan.telp "telp_pelanggan",
                        hewan.id_jenis_hewan, jenis_hewan.nama "nama_jenis_hewan", hewan.nama "nama_hewan", hewan.tanggal_lahir "tanggal_lahir_hewan", 
                        hewan.created_at, hewan.created_by, hewan.modified_at, hewan.modified_by, hewan.delete_at, hewan.delete_by, hewan.aktif');
        $this->db->from('hewan');
        $this->db->join('pelanggan', 'hewan.id_pelanggan = pelanggan.id_pelanggan');
        $this->db->join('jenis_hewan', 'hewan.id_jenis_hewan = jenis_hewan.id_jenis_hewan');
        $this->db->where('hewan.aktif',1);
        $this->db->order_by('hewan.id_hewan ASC');
        //return $this->db->get()->result();
        //return $this->returnData($this->db->get_where('layanan', ["aktif" => 1])->result(), false);
        return $this->returnData($this->db->get()->result(), false);
    }

    public function all_get(){
        $this->db->select('id_hewan, hewan.id_pelanggan, pelanggan.nama "nama_pelanggan", pelanggan.alamat "alamat_pelanggan", 
                        pelanggan.tanggal_lahir "tanggal_lahir_pelanggan", pelanggan.telp "telp_pelanggan",
                        hewan.id_jenis_hewan, jenis_hewan.nama "nama_jenis_hewan", hewan.nama "nama_hewan", hewan.tanggal_lahir "tanggal_lahir_hewan", 
                        hewan.created_at, hewan.created_by, hewan.modified_at, hewan.modified_by, hewan.delete_at, hewan.delete_by, hewan.aktif');
        $this->db->from('hewan');
        $this->db->join('pelanggan', 'hewan.id_pelanggan = pelanggan.id_pelanggan');
        $this->db->join('jenis_hewan', 'hewan.id_jenis_hewan = jenis_hewan.id_jenis_hewan');
        $this->db->order_by('hewan.id_hewan ASC');
        return $this->returnData($this->db->get()->result(), false);
    }

    public function index_post($id = null){
        $validation = $this->form_validation;
        $rule = $this->HewanModel->rules();
        if($id == null){
            array_push($rule,
                [
                    'field' => 'id_pelanggan',
                    'label' => 'id_pelanggan',
                    'rules' => 'required'
                ],
                [
                    'field' => 'id_jenis_hewan',
                    'label' => 'id_jenis_hewan',
                    'rules' => 'required'
                ],
                [
                    'field' => 'nama',
                    'label' => 'nama',
                    'rules' => 'required'
                ],
                [
                    'field' => 'tanggal_lahir',
                    'label' => 'tanggal_lahir',
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
        $hewan = new HewanData();
        $hewan->id_pelanggan = $this->post('id_pelanggan');
        $hewan->id_jenis_hewan = $this->post('id_jenis_hewan');
        $hewan->nama = $this->post('nama');
        $hewan->tanggal_lahir = $this->post('tanggal_lahir');
        $hewan->created_by = $this->post('created_by');
        if($id == null){
            $response = $this->HewanModel->store($hewan);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function update_post($id = null){
        $validation = $this->form_validation;
        $rule = $this->HewanModel->rules();
        if($id != null){
            array_push($rule,
                [
                    'field' => 'id_pelanggan',
                    'label' => 'id_pelanggan',
                    'rules' => 'required'
                ],
                [
                    'field' => 'id_jenis_hewan',
                    'label' => 'id_jenis_hewan',
                    'rules' => 'required'
                ],
                [
                    'field' => 'nama',
                    'label' => 'nama',
                    'rules' => 'required'
                ],
                [
                    'field' => 'tanggal_lahir',
                    'label' => 'tanggal_lahir',
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
        $hewan = new HewanData();
        $hewan->id_pelanggan = $this->post('id_pelanggan');
        $hewan->id_jenis_hewan = $this->post('id_jenis_hewan');
        $hewan->nama = $this->post('nama');
        $hewan->tanggal_lahir = $this->post('tanggal_lahir');
        $hewan->modified_by = $this->post('modified_by');
        if($id != null){
            $response = $this->HewanModel->update($hewan,$id);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function delete_post($id = null){
        $validation = $this->form_validation;
        $rule = $this->HewanModel->rules();
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
        $hewan = new HewanData();
        $hewan->delete_by = $this->post('delete_by');
        if($id != null){
            $response = $this->HewanModel->softDelete($hewan,$id);
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

Class HewanData{
    public $id_pelanggan;
    public $id_jenis_hewan;
    public $nama;
    public $tanggal_lahir;
    public $created_at;
    public $created_by;
    public $modified_at;
    public $modified_by;
    public $delete_at;
    public $delete_by;
    public $aktif;
}