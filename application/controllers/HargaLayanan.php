<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use chriskacerguis\RestServer\RestController;
Class HargaLayanan extends RestController{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('HargaLayananModel');
        $this->load->library('form_validation');
    }

    public function index_get(){
        return $this->returnData($this->db->get_where('harga_layanan', ["aktif" => 1])->result(), false);
    }

    public function nonAktif_get(){
        return $this->returnData($this->db->get_where('harga_layanan', ["aktif" => 0])->result(), false);
    }

    public function all_get(){
        return $this->returnData($this->db->get('harga_layanan')->result(), false);
    }

    public function getWithJoin_get(){
        $this->db->select('id_harga_layanan, harga_layanan.id_layanan, layanan.nama "nama_layanan", harga_layanan.id_ukuran_hewan, 
                        ukuran_hewan.nama "nama_ukuran_hewan", harga_layanan.harga,
                        harga_layanan.created_at, harga_layanan.created_by, harga_layanan.modified_at, harga_layanan.modified_by,
                        harga_layanan.delete_at, harga_layanan.delete_by, harga_layanan.aktif');
        $this->db->from('harga_layanan');
        $this->db->join('layanan', 'harga_layanan.id_layanan = layanan.id_layanan');
        $this->db->join('ukuran_hewan', 'harga_layanan.id_ukuran_hewan = ukuran_hewan.id_ukuran_hewan');
        $this->db->where('harga_layanan.aktif',1);
        $this->db->order_by('harga_layanan.id_harga_layanan ASC');
        //return $this->db->get()->result();
        //return $this->returnData($this->db->get_where('layanan', ["aktif" => 1])->result(), false);
        return $this->returnData($this->db->get()->result(), false);
    }

    public function getAllWithJoin_get(){
        $this->db->select('id_harga_layanan, harga_layanan.id_layanan, layanan.nama "nama_layanan", harga_layanan.id_ukuran_hewan, 
                        ukuran_hewan.nama "nama_ukuran_hewan", harga_layanan.harga,
                        harga_layanan.created_at, harga_layanan.created_by, harga_layanan.modified_at, harga_layanan.modified_by,
                        harga_layanan.delete_at, harga_layanan.delete_by, harga_layanan.aktif');
        $this->db->from('harga_layanan');
        $this->db->join('layanan', 'harga_layanan.id_layanan = layanan.id_layanan');
        $this->db->join('ukuran_hewan', 'harga_layanan.id_ukuran_hewan = ukuran_hewan.id_ukuran_hewan');
        $this->db->order_by('harga_layanan.id_harga_layanan ASC');
        return $this->returnData($this->db->get()->result(), false);
    }

    public function search_get($id){
        return $this->returnData($this->db->get_where('harga_layanan', ["id_harga_layanan" => $id])->row(), false);
    }

    public function searchByIdLayanan_get($id){
        return $this->returnData($this->db->get_where('harga_layanan', ["id_layanan" => $id, "aktif" => 1])->result(), false);
    }

    public function index_post($id = null){
        $validation = $this->form_validation;
        $rule = $this->HargaLayananModel->rules();
        if($id == null){
            array_push($rule,
                [
                    'field' => 'id_layanan',
                    'label' => 'id_layanan',
                    'rules' => 'required'
                ],
                [
                    'field' => 'id_ukuran_hewan',
                    'label' => 'id_ukuran_hewan',
                    'rules' => 'required'
                ],
                [
                    'field' => 'harga',
                    'label' => 'harga',
                    'rules' => 'required|integer'
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
        $hargalayanan = new HargaLayananData();
        $hargalayanan->id_layanan = $this->post('id_layanan');
        $hargalayanan->id_ukuran_hewan = $this->post('id_ukuran_hewan');
        $hargalayanan->harga = $this->post('harga');
        $hargalayanan->created_by = $this->post('created_by');
        if($id == null){
            $response = $this->HargaLayananModel->store($hargalayanan);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function insertMultiple_post($id = null){
        // $validation = $this->form_validation;
        // $rule = $this->HargaLayananModel->rules();
        // if($id == null){
        //     array_push($rule,
        //         [
        //             'field' => 'id_layanan',
        //             'label' => 'id_layanan',
        //             'rules' => 'required'
        //         ],
        //         [
        //             'field' => 'id_ukuran_hewan',
        //             'label' => 'id_ukuran_hewan',
        //             'rules' => 'required'
        //         ],
        //         [
        //             'field' => 'harga',
        //             'label' => 'harga',
        //             'rules' => 'required|integer'
        //         ],
        //         [
        //             'field' => 'created_by',
        //             'label' => 'created_by',
        //             'rules' => 'required'
        //         ]
        //     );
        // }
        // $validation->set_rules($rule);
		// if (!$validation->run()) {
		// 	return $this->returnData($this->form_validation->error_array(), true);
        // }
        $datahargalayanan = $this->post('harga_layanan');
        if($id == null){
            $response = $this->HargaLayananModel->storeMultiple($datahargalayanan);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function update_post($id = null){
        $validation = $this->form_validation;
        $rule = $this->HargaLayananModel->rules();
        if($id != null){
            array_push($rule,
                [
                    'field' => 'id_layanan',
                    'label' => 'id_layanan',
                    'rules' => 'required'
                ],
                [
                    'field' => 'id_ukuran_hewan',
                    'label' => 'id_ukuran_hewan',
                    'rules' => 'required'
                ],
                [
                    'field' => 'harga',
                    'label' => 'harga',
                    'rules' => 'required|integer'
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
        $hargalayanan = new HargaLayananData();
        $hargalayanan->id_layanan = $this->post('id_layanan');
        $hargalayanan->id_ukuran_hewan = $this->post('id_ukuran_hewan');
        $hargalayanan->harga = $this->post('harga');
        $hargalayanan->modified_by = $this->post('modified_by');
        if($id != null){
            $response = $this->HargaLayananModel->update($hargalayanan,$id);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function delete_post($id = null){
        $validation = $this->form_validation;
        $rule = $this->HargaLayananModel->rules();
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
        $hargalayanan = new HargaLayananData();
        $hargalayanan->delete_by = $this->post('delete_by');
        if($id != null){
            $response = $this->HargaLayananModel->softDelete($hargalayanan,$id);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function index_delete($id = null){
        if($id == null){
			return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }
        $response = $this->HargaLayananModel->destroy($id);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function returnData($msg,$error){
        $response['error']=$error;
        $response['message']=$msg;
        return $this->response($response);
    }
}

Class HargaLayananData{
    public $id_layanan;
    public $id_ukuran_hewan;
    public $harga;
    public $created_at;
    public $created_by;
    public $modified_at;
    public $modified_by;
    public $delete_at;
    public $delete_by;
    public $aktif;
}