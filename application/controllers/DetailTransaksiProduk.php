<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use chriskacerguis\RestServer\RestController;
Class DetailTransaksiProduk extends RestController{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('DetailTransaksiProdukModel');
        $this->load->library('form_validation');
    }

    public function index_get(){
        return $this->returnData($this->db->get('detail_transaksi_produk')->result(), false);
    }

    public function getByTransactionId_get($id=null){
        if($id == null){
			return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }
        return $this->returnData($this->db->get_where('detail_transaksi_produk', ["id_transaksi_produk" => $id])->result(), false);
    }

    public function search_get($id=null){
        if($id == null){
			return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }
        return $this->returnData($this->db->get_where('detail_transaksi_produk', ["id_detail_transaksi_produk" => $id])->row(), false);
    }

    public function index_post(){
        $validation = $this->form_validation;
        $rule = $this->DetailTransaksiProdukModel->rules();
        array_push($rule,
            [
                'field' => 'id_transaksi_produk',
                'label' => 'id_transaksi_produk',
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
                'field' => 'created_by',
                'label' => 'created_by',
                'rules' => 'required'
            ]
        );
        $validation->set_rules($rule);
		if (!$validation->run()) {
			return $this->returnData($this->form_validation->error_array(), true);
        }

        $transaksi = new DetailTransaksiProdukData();
        $transaksi->id_transaksi_produk = $this->post('id_transaksi_produk');
        $transaksi->id_produk = $this->post('id_produk');
        $transaksi->jumlah = $this->post('jumlah');
        $transaksi->total_harga = $this->post('total_harga');
        $transaksi->created_by = $this->post('created_by');

        $response = $this->DetailTransaksiProdukModel->store($transaksi);
        return $this->returnData($response['msg'], $response['error']);
    }
    
    public function insertMultiple_post(){
        $data = $this->post('detail_transaksi_produk');
        //if($id == null){
        $response = $this->DetailTransaksiProdukModel->storeMultiple($data);
        //}
        return $this->returnData($response['msg'], $response['error']);
    }

    public function update_post($id = null){
        $validation = $this->form_validation;
        $rule = $this->DetailTransaksiProdukModel->rules();
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
                'field' => 'modified_by',
                'label' => 'modified_by',
                'rules' => 'required'
            ]
        );
        $validation->set_rules($rule);
		if (!$validation->run()) {
			return $this->returnData($this->form_validation->error_array(), true);
        }

        $transaksi = new DetailTransaksiProdukData();
        $transaksi->id_produk = $this->post('id_produk');
        $transaksi->jumlah = $this->post('jumlah');
        $transaksi->total_harga = $this->post('total_harga');
        $transaksi->modified_by = $this->post('modified_by');
        if($id == null){
            return $this->returnData('Parameter ID tidak ditemukan', true);
        }
        $response = $this->DetailTransaksiProdukModel->update($transaksi,$id);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function updateMultiple_post(){
        $data = $this->post('detail_transaksi_produk');
        //if($id == null){
        $response = $this->DetailTransaksiProdukModel->updateMultiple($data);
        //}
        return $this->returnData($response['msg'], $response['error']);
    }

    public function index_delete($id = null){
        if($id == null){
			return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }
        $response = $this->DetailTransaksiProdukModel->destroy($id);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function deleteMultiple_post(){
        $data = $this->post('id_detail_transaksi_produk');
        //if($id == null){
        $response = $this->DetailTransaksiProdukModel->deleteMultiple($data);
        //}
        return $this->returnData($response['msg'], $response['error']);
    }

    public function returnData($msg,$error){
        $response['error']=$error;
        $response['message']=$msg;
        return $this->response($response);
    }
}

Class DetailTransaksiProdukData{
    public $id_transaksi_produk;
    public $id_produk;
    public $jumlah;
    public $total_harga;
    public $created_at;
    public $created_by;
    public $modified_at;
    public $modified_by;
}