<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use chriskacerguis\RestServer\RestController;
Class Notifikasi extends RestController{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('NotifikasiModel');
        $this->load->library('form_validation');
	}
	
	public function getWithJoin_get() {
		$this->db->select('notifikasi.id_produk, produk.id_produk, produk.nama, produk.gambar, notifikasi.created_at');
		$this->db->from('notifikasi');
		$this->db->join('id_produk', 'notifikasi.id_produk = produk.id_produk');
		$this->db->order_by('notifikasi.created_at ASC');
		return $this->returnData($this->db->get()->result(), false);
	}

    public function index_get(){
        return $this->returnData($this->db->get('notifikasi')->result(), false);
    }

    public function all_get(){
        return $this->returnData($this->db->get('notifikasi')->result(), false);
    }

    public function allOrderAsc_get(){
        $query = $this->db->from('notifikasi')->order_by('created_at', 'ASC')->get();
        return $this->returnData($query->result(), false);
    }

    public function allOrderDesc_get(){
        $query = $this->db->from('notifikasi')->order_by('created_at', 'DESC')->get();
        return $this->returnData($query->result(), false);
    }

    public function new_get(){
        return $this->returnData($this->db->get_where('notifikasi', ["status" => 0])->result(), false);
    }

    public function newOrderAsc_get(){
        $query = $this->db->from('notifikasi')->where(["status" => 0])->order_by('created_at', 'ASC')->get();
        return $this->returnData($query->result(), false);
    }

    public function newOrderDesc_get(){
        $query = $this->db->from('notifikasi')->where(["status" => 0])->order_by('created_at', 'DESC')->get();
        return $this->returnData($query->result(), false);
    }

    public function opened_get(){
        return $this->returnData($this->db->get_where('notifikasi', ["status" => 1])->result(), false);
    }

    public function openedOrderAsc_get(){
        $query = $this->db->from('notifikasi')->where(["status" => 1])->order_by('created_at', 'ASC')->get();
        return $this->returnData($query->result(), false);
    }

    public function openedOrderDesc_get(){
        $query = $this->db->from('notifikasi')->where(["status" => 1])->order_by('created_at', 'DESC')->get();
        return $this->returnData($query->result(), false);
    }

    public function search_get($id){
        return $this->returnData($this->db->get_where('notifikasi', ["id_notifikasi" => $id])->row(), false);
    }

    public function updateStatus_post($id_notifikasi = null){
        if($id_notifikasi == null){
            return $this->returnData('Parameter ID tidak ditemukan', true);
        }
        $response = $this->NotifikasiModel->updateStatusToOpened($id_notifikasi);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function returnData($msg,$error){
        $response['error']=$error;
        $response['message']=$msg;
        return $this->response($response);
    }
}

Class NotifikasiData{
    public $id_produk;
    public $status;
    public $created_at;
}
