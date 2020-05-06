<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use chriskacerguis\RestServer\RestController;
Class PengadaanProduk extends RestController{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('PengadaanProdukModel');
        $this->load->library('form_validation');
    }

    // public function getWithJoin_get() {
    //     $this->db->select('id_transaksi_produk, transaksi_produk.id_hewan, hewan.nama "nama_hewan", hewan.id_pelanggan, pelanggan.nama "nama_pelanggan",
    //                     transaksi_produk.subtotal, transaksi_produk.diskon, transaksi_produk.total, transaksi_produk.status,
    //                     transaksi_produk.tanggal_lunas, transaksi_produk.created_at, transaksi_produk.created_by,
    //                     transaksi_produk.modified_at, transaksi_produk.modified_by');
    //     $this->db->from('transaksi_produk');
    //     $this->db->join('hewan', 'transaksi_produk.id_hewan = hewan.id_hewan');
    //     $this->db->join('pelanggan', 'hewan.id_pelanggan = pelanggan.id_pelanggan');
    //     $this->db->order_by('transaksi_produk.id_transaksi_produk ASC');
    //     return $this->returnData($this->db->get()->result(), false);
    // }
    public function getWithJoin_get() {
        $this->db->select('pengadaan_produk.id_pengadaan_produk, pengadaan_produk.total,pengadaan_produk.status, pengadaan_produk.created_by, pengadaan_produk.modified_by,
                        pengadaan_produk.created_at, pengadaan_produk.modified_at, supplier.nama "nama_supplier"');
        $this->db->from('pengadaan_produk');
        $this->db->join('supplier', 'pengadaan_produk.id_supplier = supplier.id_supplier');
        $this->db->order_by('pengadaan_produk.id_pengadaan_produk ASC');
        return $this->returnData($this->db->get()->result(), false);
    }

    public function index_get(){
        return $this->returnData($this->db->get('pengadaan_produk')->result(), false);
    }

    public function unconfirmed_get(){
        return $this->returnData($this->db->get_where('pengadaan_produk', ["status" => 'Menunggu Konfirmasi'])->result(), false);
    }

    public function confirmed_get(){
        return $this->returnData($this->db->get_where('pengadaan_produk', ["status" => 'Pesanan Diproses'])->result(), false);
    }

    public function processed_get(){
        return $this->returnData($this->db->get_where('pengadaan_produk', ["status" => 'Pesanan Diproses'])->result(), false);
    }

    public function completed_get(){
        return $this->returnData($this->db->get_where('pengadaan_produk', ["status" => 'Pesanan Selesai'])->result(), false);
    }

    public function search_get($id = null){
        return $this->returnData($this->db->get_where('pengadaan_produk', ["id_pengadaan_produk" => $id])->row(), false);
    }

    public function index_post(){
        $validation = $this->form_validation;
        $rule = $this->PengadaanProdukModel->rules();
        array_push($rule,
            [
                'field' => 'id_supplier',
                'label' => 'id_supplier',
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

        $transaksi = new PengadaanProdukData();
        $transaksi->id_supplier = $this->post('id_supplier');
        $transaksi->total = $this->post('total');
        $transaksi->created_by = $this->post('created_by');

        $response = $this->PengadaanProdukModel->store($transaksi);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function insertAndGet_post(){
        $validation = $this->form_validation;
        $rule = $this->PengadaanProdukModel->rules();
        array_push($rule,
            [
                'field' => 'id_supplier',
                'label' => 'id_supplier',
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

        $transaksi = new PengadaanProdukData();
        $transaksi->id_supplier = $this->post('id_supplier');
        $transaksi->total = $this->post('total');
        $transaksi->created_by = $this->post('created_by');

        $response = $this->PengadaanProdukModel->storeReturnObject($transaksi);
        return $this->returnData($response['msg'], $response['error']);
    }
    
    public function update_post($id = null){
        $validation = $this->form_validation;
        $rule = $this->PengadaanProdukModel->rules();
        array_push($rule,
            [
                'field' => 'id_supplier',
                'label' => 'id_supplier',
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

        $transaksi = new PengadaanProdukData();
        $transaksi->id_supplier = $this->post('id_supplier');
        $transaksi->total = $this->post('total');
        $transaksi->modified_by = $this->post('modified_by');
        if($id == null){
            return $this->returnData('Parameter ID tidak ditemukan', true);
        }
        $response = $this->PengadaanProdukModel->update($transaksi,$id);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function updateStatusToProses_post($id = null){
        $validation = $this->form_validation;
        $rule = $this->PengadaanProdukModel->rules();
        array_push($rule,
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

        $transaksi = new PengadaanProdukData();
        $transaksi->modified_by = $this->post('modified_by');
        if($id == null){
            return $this->returnData('Parameter ID tidak ditemukan', true);
        }
        $response = $this->PengadaanProdukModel->updateStatusToProses($transaksi,$id);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function updateStatusToSelesai_post($id = null){
        $validation = $this->form_validation;
        $rule = $this->PengadaanProdukModel->rules();
        array_push($rule,
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

        $transaksi = new PengadaanProdukData();
        $transaksi->modified_by = $this->post('modified_by');
        if($id == null){
            return $this->returnData('Parameter ID tidak ditemukan', true);
        }
        $response = $this->PengadaanProdukModel->updateStatusToSelesai($transaksi,$id);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function index_delete($id = null){
        if($id == null){
			return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }
        $response = $this->PengadaanProdukModel->destroy($id);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function returnData($msg,$error){
        $response['error']=$error;
        $response['message']=$msg;
        return $this->response($response);
    }
}

Class PengadaanProdukData{
    public $id_supplier;
    public $total;
    public $status;
    public $created_at;
    public $created_by;
    public $modified_at;
    public $modified_by;
}