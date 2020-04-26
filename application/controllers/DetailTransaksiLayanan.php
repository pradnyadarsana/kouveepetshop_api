<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use chriskacerguis\RestServer\RestController;
Class DetailTransaksiLayanan extends RestController{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('DetailTransaksiLayananModel');
        $this->load->library('form_validation');
    }

    public function getWithJoin_get() {
        $this->db->select('id_detail_transaksi_layanan, detail_transaksi_layanan.id_transaksi_layanan,
                        detail_transaksi_layanan.id_harga_layanan, harga_layanan.id_layanan, harga_layanan.id_ukuran_hewan,
                        layanan.nama "nama_layanan", ukuran_hewan.nama "ukuran_hewan", harga_layanan.harga, detail_transaksi_layanan.jumlah,
                        detail_transaksi_layanan.total_harga, detail_transaksi_layanan.created_at, detail_transaksi_layanan.created_by,
                        detail_transaksi_layanan.modified_at, detail_transaksi_layanan.modified_by');
        $this->db->from('detail_transaksi_layanan');
        $this->db->join('harga_layanan', 'detail_transaksi_layanan.id_harga_layanan = harga_layanan.id_harga_layanan');
        $this->db->join('layanan', 'harga_layanan.id_layanan = layanan.id_layanan');
        $this->db->join('ukuran_hewan', 'harga_layanan.id_ukuran_hewan = ukuran_hewan.id_ukuran_hewan');
        $this->db->order_by('detail_transaksi_layanan.id_detail_transaksi_layanan ASC');
        return $this->returnData($this->db->get()->result(), false);
    }

    public function index_get(){
        return $this->returnData($this->db->get('detail_transaksi_layanan')->result(), false);
    }

    public function getByTransactionId_get($id=null){
        if($id == null){
			return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }
        return $this->returnData($this->db->get_where('detail_transaksi_layanan', ["id_transaksi_layanan" => $id])->result(), false);
    }

    public function search_get($id=null){
        if($id == null){
			return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }
        return $this->returnData($this->db->get_where('detail_transaksi_layanan', ["id_detail_transaksi_layanan" => $id])->row(), false);
    }

    public function index_post(){
        $validation = $this->form_validation;
        $rule = $this->DetailTransaksiLayananModel->rules();
        array_push($rule,
            [
                'field' => 'id_transaksi_layanan',
                'label' => 'id_transaksi_layanan',
                'rules' => 'required'
            ],
            [
                'field' => 'id_harga_layanan',
                'label' => 'id_harga_layanan',
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

        $transaksi = new DetailTransaksiLayananData();
        $transaksi->id_transaksi_layanan = $this->post('id_transaksi_layanan');
        $transaksi->id_harga_layanan = $this->post('id_harga_layanan');
        $transaksi->jumlah = $this->post('jumlah');
        $transaksi->total_harga = $this->post('total_harga');
        $transaksi->created_by = $this->post('created_by');

        $response = $this->DetailTransaksiLayananModel->store($transaksi);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function insertMultiple_post(){
        $data = $this->post('detail_transaksi_layanan');
        //if($id == null){
        $response = $this->DetailTransaksiLayananModel->storeMultiple($data);
        //}
        return $this->returnData($response['msg'], $response['error']);
    }

    public function update_post($id = null){
        $validation = $this->form_validation;
        $rule = $this->DetailTransaksiLayananModel->rules();
        array_push($rule,
            [
                'field' => 'id_harga_layanan',
                'label' => 'id_harga_layanan',
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

        $transaksi = new DetailTransaksiLayananData();
        $transaksi->id_harga_layanan = $this->post('id_harga_layanan');
        $transaksi->jumlah = $this->post('jumlah');
        $transaksi->total_harga = $this->post('total_harga');
        $transaksi->modified_by = $this->post('modified_by');
        if($id == null){
            return $this->returnData('Parameter ID tidak ditemukan', true);
        }
        $response = $this->DetailTransaksiLayananModel->update($transaksi,$id);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function updateMultiple_post(){
        $data = $this->post('detail_transaksi_layanan');
        //if($id == null){
        $response = $this->DetailTransaksiLayananModel->updateMultiple($data);
        //}
        return $this->returnData($response['msg'], $response['error']);
    }

    public function index_delete($id = null){
        if($id == null){
			return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }
        $response = $this->DetailTransaksiLayananModel->destroy($id);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function deleteMultiple_post(){
        $data = $this->post('id_detail_transaksi_layanan');
        //if($id == null){
        $response = $this->DetailTransaksiLayananModel->deleteMultiple($data);
        //}
        return $this->returnData($response['msg'], $response['error']);
    }

    public function returnData($msg,$error){
        $response['error']=$error;
        $response['message']=$msg;
        return $this->response($response);
    }
}

Class DetailTransaksiLayananData{
    public $id_transaksi_layanan;
    public $id_harga_layanan;
    public $jumlah;
    public $total_harga;
    public $created_at;
    public $created_by;
    public $modified_at;
    public $modified_by;
}