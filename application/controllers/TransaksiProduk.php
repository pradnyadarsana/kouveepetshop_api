<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use chriskacerguis\RestServer\RestController;
Class TransaksiProduk extends RestController{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('TransaksiProdukModel');
        $this->load->library('form_validation');
    }

    public function getTransaksiByMonth_get($param){
        $i = 1;
        $totalPengeluaran = 0;
        $produk1 = array();
        $produk2 = array();
        $produk3 = array();
        $produk4 = array();
        $produk5 = array();
        $produk6 = array();
        $produk7 = array();
        $produk8 = array();
        $produk9 = array();
        $produk10 = array();
        $produk11 = array();
        $produk12 = array();
        $jumlahMax1 = '0';
        $jumlahMax2 = '0';
        $jumlahMax3 = '0';
        $jumlahMax4 = '0';
        $jumlahMax5 = '0';
        $jumlahMax6 = '0';
        $jumlahMax7 = '0';
        $jumlahMax8 = '0';
        $jumlahMax9 = '0';
        $jumlahMax10 = '0';
        $jumlahMax11 = '0';
        $jumlahMax12 = '0';
        $produkMax1 = 'Tidak Ada Transaksi';
        $produkMax2= 'Tidak Ada Transaksi';
        $produkMax3 = 'Tidak Ada Transaksi';
        $produkMax4 = 'Tidak Ada Transaksi';
        $produkMax5 = 'Tidak Ada Transaksi';
        $produkMax6 = 'Tidak Ada Transaksi';
        $produkMax7 = 'Tidak Ada Transaksi';
        $produkMax8 = 'Tidak Ada Transaksi';
        $produkMax9 = 'Tidak Ada Transaksi';
        $produkMax10 = 'Tidak Ada Transaksi';
        $produkMax11= 'Tidak Ada Transaksi';
        $produkMax12 = 'Tidak Ada Transaksi';
        $data = "SELECT transaksi_produk.id_transaksi_produk , transaksi_produk.subtotal  from transaksi_produk
        WHERE  year(transaksi_produk.created_at)=? AND transaksi_produk.status = 'Lunas'
        GROUP BY transaksi_produk.id_transaksi_produk";
        $hasil = $this->db->query($data,[$param])->result();
        $detailTransaksi = "SELECT produk.nama, detail_transaksi_produk.total_harga, detail_transaksi_produk.jumlah,month(detail_transaksi_produk.created_at) as 'bulan' from detail_transaksi_produk
                INNER JOIN produk USING(id_produk)
                WHERE detail_transaksi_produk.id_transaksi_produk = ?
                GROUP BY produk.nama";

        for($k = 0;$k <sizeof($hasil); $k++ ){
                $hasil2[$k] = $this->db->query($detailTransaksi,[$hasil[$k]->id_transaksi_produk])->result();
            }

        for($l = 0 ; $l < count($hasil2) ; $l++){
            for($m = 0 ; $m < count($hasil2) ; $m++){
                if(isset($hasil2[$l][$m])){
                    if($hasil2[$l][$m]->bulan==1){
                        array_push($produk1,$hasil2[$l][$m]); 
                    }elseif($hasil2[$l][$m]->bulan==2){
                        array_push($produk2,$hasil2[$l][$m]); 
                    }elseif($hasil2[$l][$m]->bulan==3){
                        array_push($produk3,$hasil2[$l][$m]); 
                    }elseif($hasil2[$l][$m]->bulan==4){
                        array_push($produk4,$hasil2[$l][$m]); 
                    }elseif($hasil2[$l][$m]->bulan==5){
                        array_push($produk5,$hasil2[$l][$m]); 
                    }elseif($hasil2[$l][$m]->bulan==6){
                        array_push($produk6,$hasil2[$l][$m]); 
                    }elseif($hasil2[$l][$m]->bulan==7){
                        array_push($produk7,$hasil2[$l][$m]); 
                    }elseif($hasil2[$l][$m]->bulan==8){
                        array_push($produk8,$hasil2[$l][$m]); 
                    }elseif($hasil2[$l][$m]->bulan==9){
                        array_push($produk9,$hasil2[$l][$m]); 
                    }elseif($hasil2[$l][$m]->bulan==10){
                        array_push($produk10,$hasil2[$l][$m]); 
                    }elseif($hasil2[$l][$m]->bulan==11){
                        array_push($produk11,$hasil2[$l][$m]); 
                    }else{
                        array_push($produk12,$hasil2[$l][$m]); 
                    }
                }
                }
            }
        for($o = 0; $o<count($produk1);$o++){
            if($produk1[$o]->jumlah > $jumlahMax1){
                $jumlahMax1 = $produk1[$o]->jumlah;
                $produkMax1 = $produk1[$o]->nama;
            }
        }
        for($o = 0; $o<count($produk2);$o++){
            if($produk2[$o]->jumlah > $jumlahMax2){
                $jumlahMax2 = $produk2[$o]->jumlah;
                $produkMax2 = $produk2[$o]->nama;
            }
        }
        for($o = 0; $o<count($produk3);$o++){
            if($produk3[$o]->jumlah > $jumlahMax3){
                $jumlahMax3 = $produk3[$o]->jumlah;
                $produkMax3 = $produk3[$o]->nama;
            }
        }
        for($o = 0; $o<count($produk4);$o++){
            if($produk4[$o]->jumlah > $jumlahMax4){
                $jumlahMax4 = $produk4[$o]->jumlah;
                $produkMax4 = $produk4[$o]->nama;
            }
        }
        for($o = 0; $o<count($produk5);$o++){
            if($produk5[$o]->jumlah > $jumlahMax5){
                $jumlahMax5 = $produk5[$o]->jumlah;
                $produkMax5 = $produk5[$o]->nama;
            }
        }
        for($o = 0; $o<count($produk6);$o++){
            if($produk6[$o]->jumlah > $jumlahMax6){
                $jumlahMax6= $produk6[$o]->jumlah;
                $produkMax6 = $produk6[$o]->nama;
            }
        }
        for($o = 0; $o<count($produk7);$o++){
            if($produk7[$o]->jumlah > $jumlahMax7){
                $jumlahMax7 = $produk7[$o]->jumlah;
                $produkMax7 = $produk7[$o]->nama;
            }
        }
        for($o = 0; $o<count($produk8);$o++){
            if($produk8[$o]->jumlah > $jumlahMax8){
                $jumlahMax8 = $produk8[$o]->jumlah;
                $produkMax8 = $produk8[$o]->nama;
            }
        }
        for($o = 0; $o<count($produk9);$o++){
            if($produk9[$o]->jumlah > $jumlahMax9){
                $jumlahMax9 = $produk9[$o]->jumlah;
                $produkMax9= $produk9[$o]->nama;
            }
        }
        for($o = 0; $o<count($produk10);$o++){
            if($produk10[$o]->jumlah > $jumlahMax10){
                $jumlahMax10 = $produk10[$o]->jumlah;
                $produkMax10 = $produk10[$o]->nama;
            }
        }
        for($o = 0; $o<count($produk11);$o++){
            if($produk11[$o]->jumlah > $jumlahMax11){
                $jumlahMax11 = $produk11[$o]->jumlah;
                $produkMax11= $produk11[$o]->nama;
            }
        }
        for($o = 0; $o<count($produk12);$o++){
            if($produk12[$o]->jumlah > $jumlahMax12){
                $jumlahMax12 = $produk12[$o]->jumlah;
                $produkMax12 = $produk12[$o]->nama;
            }
        }
            print_r($jumlahMax5);
            print_r($produkMax5);
        }

    public function getWithJoin_get() {
        $this->db->select('id_transaksi_produk, transaksi_produk.id_hewan, hewan.nama "nama_hewan", hewan.id_jenis_hewan, jenis_hewan.nama "jenis_hewan", hewan.id_pelanggan, pelanggan.nama "nama_pelanggan", pelanggan.telp "telp",
                        transaksi_produk.subtotal, transaksi_produk.diskon, transaksi_produk.total, transaksi_produk.status,
                        transaksi_produk.tanggal_lunas, transaksi_produk.created_at, transaksi_produk.created_by,
                        transaksi_produk.modified_at, transaksi_produk.modified_by');
        $this->db->from('transaksi_produk');
        $this->db->join('hewan', 'transaksi_produk.id_hewan = hewan.id_hewan', 'left outer');
         $this->db->join('jenis_hewan', 'hewan.id_jenis_hewan = jenis_hewan.id_jenis_hewan', 'left');
        $this->db->join('pelanggan', 'hewan.id_pelanggan = pelanggan.id_pelanggan', 'left');
        $this->db->order_by('transaksi_produk.id_transaksi_produk ASC');
        return $this->returnData($this->db->get()->result(), false);
    }

    public function index_get(){
        return $this->returnData($this->db->get('transaksi_produk')->result(), false);
    }

    public function waitingPayment_get(){
        return $this->returnData($this->db->get_where('transaksi_produk', ["status" => 'Menunggu Pembayaran'])->result(), false);
    }

    public function paidOff_get(){
        return $this->returnData($this->db->get_where('transaksi_produk', ["status" => 'Lunas'])->result(), false);
    }

    public function search_get($id = null){
        return $this->returnData($this->db->get_where('transaksi_produk', ["id_transaksi_produk" => $id])->row(), false);
    }

    public function index_post(){
        $validation = $this->form_validation;
        $rule = $this->TransaksiProdukModel->rules();
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

        $transaksi = new TransaksiProdukData();
        $transaksi->id_customer_service = $this->post('id_customer_service');
        $transaksi->id_hewan = $this->post('id_hewan');
        $transaksi->subtotal = $this->post('subtotal');
        $transaksi->diskon = $this->post('diskon');
        $transaksi->total = $this->post('total');
        $transaksi->created_by = $this->post('created_by');

        $response = $this->TransaksiProdukModel->store($transaksi);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function insertAndGet_post(){
        $validation = $this->form_validation;
        $rule = $this->TransaksiProdukModel->rules();
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

        $transaksi = new TransaksiProdukData();
        $transaksi->id_customer_service = $this->post('id_customer_service');
        $transaksi->id_hewan = $this->post('id_hewan');
        $transaksi->subtotal = $this->post('subtotal');
        $transaksi->diskon = $this->post('diskon');
        $transaksi->total = $this->post('total');
        $transaksi->created_by = $this->post('created_by');

        $response = $this->TransaksiProdukModel->storeReturnObject($transaksi);
        return $this->returnData($response['msg'], $response['error']);
    }
    
    public function update_post($id = null){
        $validation = $this->form_validation;
        $rule = $this->TransaksiProdukModel->rules();
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

        $transaksi = new TransaksiProdukData();
        $transaksi->id_hewan = $this->post('id_hewan');
        $transaksi->subtotal = $this->post('subtotal');
        $transaksi->diskon = $this->post('diskon');
        $transaksi->total = $this->post('total');
        $transaksi->modified_by = $this->post('modified_by');
        if($id == null){
            return $this->returnData('Parameter ID tidak ditemukan', true);
        }
        $response = $this->TransaksiProdukModel->update($transaksi,$id);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function updateStatus_post($id = null){
        $validation = $this->form_validation;
        $rule = $this->TransaksiProdukModel->rules();
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

        $transaksi = new TransaksiProdukData();
        $transaksi->id_kasir = $this->post('id_kasir');
        $transaksi->modified_by = $this->post('modified_by');
        if($id == null){
            return $this->returnData('Parameter ID tidak ditemukan', true);
        }
        $response = $this->TransaksiProdukModel->updateStatus($transaksi,$id);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function index_delete($id = null){
        if($id == null){
			return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }
        $response = $this->TransaksiProdukModel->destroy($id);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function returnData($msg,$error){
        $response['error']=$error;
        $response['message']=$msg;
        return $this->response($response);
    }
}

Class TransaksiProdukData{
    public $id_customer_service;
    public $id_kasir;
    public $id_hewan;
    public $subtotal;
    public $diskon;
    public $total;
    public $status;
    public $tanggal_lunas;
    public $created_at;
    public $created_by;
    public $modified_at;
    public $modified_by;
}
