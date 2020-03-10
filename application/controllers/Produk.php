<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use chriskacerguis\RestServer\RestController;
Class Produk extends RestController{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('ProdukModel');
        $this->load->library('form_validation');
    }

    public function index_get(){
        return $this->returnData($this->db->get_where('produk', ["aktif" => 1])->result(), false);
    }

    public function all_get(){
        return $this->returnData($this->db->get('produk')->result(), false);
    }

    public function search_get($id){
        return $this->returnData($this->db->get_where('produk', ["id_produk" => $id])->result(), false);
    }

    public function index_post($id = null){
        $validation = $this->form_validation;
        $rule = $this->ProdukModel->rules();
        if($id == null){
            array_push($rule,
                [
                    'field' => 'nama',
                    'label' => 'nama',
                    'rules' => 'required|is_unique[produk.nama]'
                ],
                [
                    'field' => 'satuan',
                    'label' => 'satuan',
                    'rules' => 'required'
                ],
                [
                    'field' => 'jumlah_stok',
                    'label' => 'jumlah_stok',
                    'rules' => 'required|integer|greater_than_equal_to[0]'
                ],
                [
                    'field' => 'harga',
                    'label' => 'harga',
                    'rules' => 'required|integer|greater_than[0]'
                ],
                [
                    'field' => 'min_stok',
                    'label' => 'min_stok',
                    'rules' => 'required|integer|greater_than_equal_to[0]'
                ],
                [
                    'field' => 'created_by',
                    'label' => 'created_by',
                    'rules' => 'required',
                ]
            );
        }
        $validation->set_rules($rule);
		if (!$validation->run()) {
			return $this->returnData($this->form_validation->error_array(), true);
        }
        $produk = new ProdukData();
        $produk->nama = $this->post('nama');
        $produk->satuan = $this->post('satuan');
        $produk->jumlah_stok = $this->post('jumlah_stok');
        $produk->harga = $this->post('harga');
        $produk->min_stok = $this->post('min_stok');
        $produk->gambar = $this->post('gambar');
        $produk->created_by = $this->post('created_by');
        if($id == null){
            $response = $this->ProdukModel->store($produk);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function update_post($id = null){
        $validation = $this->form_validation;
        $rule = $this->ProdukModel->rules();
        if($id != null){
            array_push($rule,
                [
                    'field' => 'nama',
                    'label' => 'nama',
                    'rules' => 'required'
                ],
                [
                    'field' => 'satuan',
                    'label' => 'satuan',
                    'rules' => 'required'
                ],
                [
                    'field' => 'jumlah_stok',
                    'label' => 'jumlah_stok',
                    'rules' => 'required|integer|greater_than_equal_to[0]'
                ],
                [
                    'field' => 'harga',
                    'label' => 'harga',
                    'rules' => 'required|integer|greater_than[0]'
                ],
                [
                    'field' => 'min_stok',
                    'label' => 'min_stok',
                    'rules' => 'required|integer|greater_than_equal_to[0]'
                ],
                [
                    'field' => 'modified_by',
                    'label' => 'modified_by',
                    'rules' => 'required',
                ]

            );
        }
        $validation->set_rules($rule);
        if (!$validation->run()) {
            return $this->returnData($this->form_validation->error_array(), true);
        }
        $produk = new ProdukData();
        $produk->nama = $this->post('nama');
        $produk->satuan = $this->post('satuan');
        $produk->jumlah_stok = $this->post('jumlah_stok');
        $produk->harga = $this->post('harga');
        $produk->min_stok = $this->post('min_stok');
        $produk->gambar = $this->post('gambar');
        $produk->modified_by = $this->post('modified_by');
        if($id != null){
            $response = $this->ProdukModel->update($produk,$id);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function delete_post($id = null){
        $validation = $this->form_validation;
        $rule = $this->ProdukModel->rules();
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
        $produk = new ProdukData();
        $produk->delete_by = $this->post('delete_by');
        if($id != null){
            $response = $this->ProdukModel->softDelete($produk,$id);
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

Class ProdukData{
    public $id_produk;
    public $nama;
    public $satuan;
    public $jumlah_stok;
    public $harga;
    public $min_stok;
    public $gambar;
    public $created_at;
    public $created_by;
    public $modified_at;
    public $modified_by;
    public $delete_at;
    public $delete_by;
    public $aktif;
}