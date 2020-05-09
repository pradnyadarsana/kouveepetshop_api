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
        $this->load->library('pdf');
        include_once APPPATH . '/third_party/fpdf/fpdf.php';
    }

    public function getWithJoin_get() {
        $this->db->select('pengadaan_produk.id_pengadaan_produk,pengadaan_produk.id_supplier, pengadaan_produk.total,pengadaan_produk.status, pengadaan_produk.created_by, pengadaan_produk.modified_by,
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

    function cetakStruk_get($id_pengadaan_produk = null){
        // $this->load->helper('directory'); //load directory helper
        $dir = "controllers/PDF/"; // Your Path to folder
        // $map = directory_map($dir); /* This function reads the directory path specified in the first parameter and builds an array representation of it and all its contained files. */
        $pdf = new FPDF('p','mm','A4');
        // membuat halaman baru
        $pdf->AddPage();
    
        // $dataTransaksi = null;
        // $dataDetailTransaksi = null;
        $pengadaan_produk_data = null;
        $detail_pengadaan_data = null;

        $this->db->select('pengadaan_produk.id_pengadaan_produk, pengadaan_produk.id_supplier, 
                        supplier.nama "nama_supplier", supplier.alamat "alamat_supplier", supplier.telp "telp_supplier", 
                        pengadaan_produk.total, pengadaan_produk.status,
                        pengadaan_produk.created_at, pengadaan_produk.created_by,
                        pengadaan_produk.modified_at, pengadaan_produk.modified_by');
        $this->db->from('pengadaan_produk');
        $this->db->join('supplier', 'pengadaan_produk.id_supplier = supplier.id_supplier', 'left');
        $this->db->where('id_pengadaan_produk',$id_pengadaan_produk);
        $resultTransaksi = $this->db->get();

        if($resultTransaksi->num_rows()!=0){
            $pengadaan_produk_data = $resultTransaksi->row();

            $this->db->select('detail_pengadaan.id_detail_pengadaan, detail_pengadaan.id_pengadaan_produk, detail_pengadaan.id_produk, 
                            produk.nama "nama_produk", produk.satuan "satuan_produk", 
                            detail_pengadaan.jumlah, detail_pengadaan.harga, detail_pengadaan.total_harga, 
                            detail_pengadaan.created_at, detail_pengadaan.created_by, 
                            detail_pengadaan.modified_at, detail_pengadaan.modified_by');
            $this->db->from('detail_pengadaan');
            $this->db->join('produk','detail_pengadaan.id_produk = produk.id_produk', 'left');
            $this->db->where('id_pengadaan_produk',$id_pengadaan_produk);
            $detail_pengadaan_data = $this->db->get()->result();
        }else{
            $this->returnData("ID Pengadaan Produk tidak ditemukan!",true);
        }

        $month_name = array("Januari", "Februari", "Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
        $tanggal_dibuat = date("j",strtotime($pengadaan_produk_data->created_at))." ".
                            $month_name[date("n",strtotime($pengadaan_produk_data->created_at))-1]." ".
                            date("Y",strtotime($pengadaan_produk_data->created_at));
        $tanggal_cetak = date("j")." ".$month_name[date("n")-1]." ".date("Y");

        $pdf->Image(APPPATH.'controllers/PDF/Logo/kouvee.png',10,10,-200);
        $pdf->Cell(10,50,'',0,1);// Memberikan space kebawah agar tidak terlalu rapat
        $pdf->Cell(70);
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(50,7,'Surat Pemesanan',0,1,'C');
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(190,8,'NO : '.$pengadaan_produk_data->id_pengadaan_produk,0,1, 'R');
        $pdf->Cell(190,8,'Tanggal : '.$tanggal_dibuat,0,0, 'R');
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(10,10,'',0,1);
        $pdf->Cell(45,6,'Kepada Yth :',0,1);
        $pdf->Cell(45,6,$pengadaan_produk_data->nama_supplier,0,1);
        $pdf->Cell(45,6,$pengadaan_produk_data->alamat_supplier,0,1);
        $pdf->Cell(45,6,$pengadaan_produk_data->telp_supplier,0,1);
        $pdf->Cell(10,10,'',0,1);
        //$pdf->Cell(70,10);
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(50,7,'Mohon untuk disediakan produk-produk berikut ini :',0,1);
        $pdf->Cell(10,5,'',0,1);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(10,6,'NO',1,0,'C');
        $pdf->Cell(60,6,'NAMA PRODUK',1,0,'C');
        $pdf->Cell(25,6,'SATUAN',1,0,'C');
        $pdf->Cell(35,6,'HARGA',1,0,'C');
        $pdf->Cell(20,6,'JUMLAH',1,0,'C');
        $pdf->Cell(40,6,'TOTAL HARGA',1,1,'C');
        $pdf->SetFont('Arial','',10);
        $i = 1;
        foreach ($detail_pengadaan_data as $item){    
            $pdf->Cell(10,10,$i,1,0,'C');
            $pdf->Cell(60,10,$item->nama_produk,1,0,'L');
            $pdf->Cell(25,10,$item->satuan_produk,1,0,'C');
            $pdf->Cell(35,10,'Rp. '.$item->harga,1,0,'C');
            $pdf->Cell(20,10,$item->jumlah,1,0,'C');
            $pdf->Cell(40,10,'Rp. '.$item->total_harga,1,1,'C');
            $i++;
        }
        $pdf->Cell(10,10,'',0,1);
        $pdf->SetFont('Arial','B',13);
        $pdf->Cell(65,10,'Total Biaya Dikeluarkan: Rp. '.$pengadaan_produk_data->total,0,1);
        $pdf->Cell(10,20,'',0,1);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(190,7,'Dicetak tanggal '.$tanggal_cetak,0,1,'R');
        $pdf->Output($pengadaan_produk_data->id_pengadaan_produk.'.pdf','D');
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