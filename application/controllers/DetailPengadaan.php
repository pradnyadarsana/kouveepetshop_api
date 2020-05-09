<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use chriskacerguis\RestServer\RestController;
Class DetailPengadaan extends RestController{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('DetailPengadaanModel');
        $this->load->library('form_validation');
    }

    public function getWithJoin_get() {
        $this->db->select('detail_pengadaan.id_detail_pengadaan,detail_pengadaan.id_produk,detail_pengadaan.id_pengadaan_produk,detail_pengadaan.jumlah,detail_pengadaan.harga, detail_pengadaan.total_harga, detail_pengadaan.created_by, detail_pengadaan.modified_by,
                        detail_pengadaan.created_at, detail_pengadaan.modified_at, produk.nama "nama_produk"');
        $this->db->from('detail_pengadaan');
        $this->db->join('produk', 'detail_pengadaan.id_produk = produk.id_produk');
        $this->db->order_by('detail_pengadaan.id_detail_pengadaan ASC');
        return $this->returnData($this->db->get()->result(), false);
    }
    public function index_get(){
        return $this->returnData($this->db->get('detail_pengadaan')->result(), false);
    }

    public function getByIdPengadaan_get($id=null){
        if($id == null){
			return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }
        return $this->returnData($this->db->get_where('detail_pengadaan', ["id_pengadaan_produk" => $id])->result(), false);
    }

    public function search_get($id=null){
        if($id == null){
			return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }
        return $this->returnData($this->db->get_where('detail_pengadaan', ["id_detail_pengadaan" => $id])->row(), false);
    }

    public function index_post(){
        $validation = $this->form_validation;
        $rule = $this->DetailPengadaanModel->rules();
        array_push($rule,
            [
                'field' => 'id_pengadaan_produk',
                'label' => 'id_pengadaan_produk',
                'rules' => 'required'
            ],
            [
                'field' => 'id_produk',
                'label' => 'id_produk',
                'rules' => 'required'
            ],
            [
                'field' => 'jumlah',
                'label' => 'jumlah',
                'rules' => 'required'
            ],
            [
                'field' => 'harga',
                'label' => 'harga',
                'rules' => 'required'
            ],
            [
                'field' => 'created_by',
                'label' => 'created_by',
                'rules' => 'required'
            ]
        );
        $validation->set_rules($rule);
		if (!$validation->run()) {
			return $this->returnData($this->form_validation->error_array(), true);
        }

        $transaksi = new DetailPengadaanData();
        $transaksi->id_pengadaan_produk = $this->post('id_pengadaan_produk');
        $transaksi->id_produk = $this->post('id_produk');
        $transaksi->jumlah = $this->post('jumlah');
        $transaksi->harga = $this->post('harga');
        $transaksi->total_harga = $this->post('total_harga');
        $transaksi->created_by = $this->post('created_by');

        $response = $this->DetailPengadaanModel->store($transaksi);
        return $this->returnData($response['msg'], $response['error']);
    }
    
    public function insertMultiple_post(){
        $data = $this->post('detail_pengadaan');
        //if($id == null){
        $response = $this->DetailPengadaanModel->storeMultiple($data);
        //}
        return $this->returnData($response['msg'], $response['error']);
    }

    public function update_post($id = null){
        $validation = $this->form_validation;
        $rule = $this->DetailPengadaanModel->rules();
        array_push($rule,
            [
                'field' => 'id_produk',
                'label' => 'id_produk',
                'rules' => 'required'
            ],
            [
                'field' => 'jumlah',
                'label' => 'jumlah',
                'rules' => 'required'
            ],
            [
                'field' => 'harga',
                'label' => 'harga',
                'rules' => 'required'
            ],
            [
                'field' => 'modified_by',
                'label' => 'modified_by',
                'rules' => 'required'
            ]
        );
        $validation->set_rules($rule);
		if (!$validation->run()) {
			return $this->returnData($this->form_validation->error_array(), true);
        }

        $transaksi = new DetailPengadaanData();
        $transaksi->id_produk = $this->post('id_produk');
        $transaksi->jumlah = $this->post('jumlah');
        $transaksi->harga = $this->post('harga');
        $transaksi->total_harga = $this->post('total_harga');
        $transaksi->modified_by = $this->post('modified_by');
        if($id == null){
            return $this->returnData('Parameter ID tidak ditemukan', true);
        }
        $response = $this->DetailPengadaanModel->update($transaksi,$id);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function updateMultiple_post(){
        $data = $this->post('detail_pengadaan');
        //if($id == null){
        $response = $this->DetailPengadaanModel->updateMultiple($data);
        //}
        return $this->returnData($response['msg'], $response['error']);
    }

    public function index_delete($id = null){
        if($id == null){
			return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }
        $response = $this->DetailPengadaanModel->destroy($id);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function deleteMultiple_post(){
        $data = $this->post('id_detail_pengadaan');
        //if($id == null){
        $response = $this->DetailPengadaanModel->deleteMultiple($data);
        //}
        return $this->returnData($response['msg'], $response['error']);
    }

    public function returnData($msg,$error){
        $response['error']=$error;
        $response['message']=$msg;
        return $this->response($response);
    }
}

Class DetailPengadaanData{
    public $id_pengadaan_produk;
    public $id_produk;
    public $jumlah;
    public $harga;
    public $total_harga;
    public $created_at;
    public $created_by;
    public $modified_at;
    public $modified_by;
}