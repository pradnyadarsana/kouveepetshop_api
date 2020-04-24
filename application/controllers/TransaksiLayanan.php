<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use chriskacerguis\RestServer\RestController;
Class TransaksiLayanan extends RestController{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('TransaksiLayananModel');
        $this->load->library('form_validation');
    }

    public function index_get(){
        return $this->returnData($this->db->get('transaksi_layanan')->result(), false);
    }

    public function onProgress_get(){
        return $this->returnData($this->db->get_where('transaksi_layanan', ["progress" => 'Sedang Diproses'])->result(), false);
    }

    public function progressDone_get(){
        return $this->returnData($this->db->get_where('transaksi_layanan', ["progress" => 'Layanan Selesai'])->result(), false);
    }

    public function progressDoneAndWaitingPayment_get(){
        return $this->returnData($this->db->get_where('transaksi_layanan', ["progress" => 'Layanan Selesai', "status" => 'Menunggu Pembayaran'])->result(), false);
    }

    public function waitingPayment_get(){
        return $this->returnData($this->db->get_where('transaksi_layanan', ["status" => 'Menunggu Pembayaran'])->result(), false);
    }

    public function paidOff_get(){
        return $this->returnData($this->db->get_where('transaksi_layanan', ["status" => 'Lunas'])->result(), false);
    }

    public function search_get($id = null){
        return $this->returnData($this->db->get_where('transaksi_layanan', ["id_transaksi_layanan" => $id])->row(), false);
    }

    public function index_post(){
        $validation = $this->form_validation;
        $rule = $this->TransaksiLayananModel->rules();
        array_push($rule,
            [
                'field' => 'id_customer_service',
                'label' => 'id_customer_service',
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

        $transaksi = new TransaksiLayananData();
        $transaksi->id_customer_service = $this->post('id_customer_service');
        $transaksi->id_hewan = $this->post('id_hewan');
        $transaksi->diskon = $this->post('diskon');
        $transaksi->total = $this->post('total');
        $transaksi->created_by = $this->post('created_by');

        $response = $this->TransaksiLayananModel->store($transaksi);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function insertAndGet_post(){
        $validation = $this->form_validation;
        $rule = $this->TransaksiLayananModel->rules();
        array_push($rule,
            [
                'field' => 'id_customer_service',
                'label' => 'id_customer_service',
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

        $transaksi = new TransaksiLayananData();
        $transaksi->id_customer_service = $this->post('id_customer_service');
        $transaksi->id_hewan = $this->post('id_hewan');
        $transaksi->diskon = $this->post('diskon');
        $transaksi->total = $this->post('total');
        $transaksi->created_by = $this->post('created_by');

        $response = $this->TransaksiLayananModel->storeReturnObject($transaksi);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function update_post($id = null){
        $validation = $this->form_validation;
        $rule = $this->TransaksiLayananModel->rules();
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

        $transaksi = new TransaksiLayananData();
        $transaksi->id_hewan = $this->post('id_hewan');
        $transaksi->subtotal = $this->post('subtotal');
        $transaksi->diskon = $this->post('diskon');
        $transaksi->total = $this->post('total');
        $transaksi->modified_by = $this->post('modified_by');
        if($id == null){
            return $this->returnData('Parameter ID tidak ditemukan', true);
        }
        $response = $this->TransaksiLayananModel->update($transaksi,$id);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function updateProgress_post($id = null){
        $validation = $this->form_validation;
        $rule = $this->TransaksiLayananModel->rules();
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

        $transaksi = new TransaksiLayananData();
        $transaksi->modified_by = $this->post('modified_by');
        if($id == null){
            return $this->returnData('Parameter ID tidak ditemukan', true);
        }
        $response = $this->TransaksiLayananModel->updateProgress($transaksi,$id);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function updateStatus_post($id = null){
        $validation = $this->form_validation;
        $rule = $this->TransaksiLayananModel->rules();
        array_push($rule,
            [
                'field' => 'id_kasir',
                'label' => 'id_kasir',
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

        $transaksi = new TransaksiLayananData();
        $transaksi->id_kasir = $this->post('id_kasir');
        $transaksi->modified_by = $this->post('modified_by');
        if($id == null){
            return $this->returnData('Parameter ID tidak ditemukan', true);
        }
        $response = $this->TransaksiLayananModel->updateStatus($transaksi,$id);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function index_delete($id = null){
        if($id == null){
			return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }
        $response = $this->TransaksiLayananModel->destroy($id);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function returnData($msg,$error){
        $response['error']=$error;
        $response['message']=$msg;
        return $this->response($response);
    }
}

Class TransaksiLayananData{
    public $id_customer_service;
    public $id_kasir;
    public $id_hewan;
    public $subtotal;
    public $diskon;
    public $total;
    public $progress;
    public $status;
    public $tanggal_lunas;
    public $created_at;
    public $created_by;
    public $modified_at;
    public $modified_by;
}