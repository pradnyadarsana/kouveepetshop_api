<?php
Class CetakStrukLayanan extends CI_Controller{
    
    function __construct() {
        parent::__construct();
        $this->load->library('pdf');
        include_once APPPATH . '/third_party/fpdf/fpdf.php';
    }
    function _remap($param) {
        $this->index($param);
    }
    function index($param){
        // $this->load->helper('directory'); //load directory helper
        $dir = "controllers/PDF/"; // Your Path to folder
        // $map = directory_map($dir); /* This function reads the directory path specified in the first parameter and builds an array representation of it and all its contained files. */
        $pdf = new FPDF('p','mm','A4');
        // membuat halaman baru
        $pdf->AddPage();
    
        $dataDetailTransaksi = $this->db->get_where('detail_transaksi_layanan', ["id_transaksi_layanan" => $param])->result();
        $dataTransaksi= $this->db->get_where('transaksi_layanan', ["id_transaksi_layanan" => $param])->row();
        $id_kasir = $dataTransaksi->id_kasir;
        $id_customer_service = $dataTransaksi->id_customer_service;
        $id_hewan = $dataTransaksi->id_hewan;
        $hewan = $this->db->get_where('hewan', ["id_hewan" => $id_hewan])->row();
        $id_pelanggan = $hewan->id_pelanggan;
        $id_jenis_hewan = $hewan->id_jenis_hewan;
        $kasir = $this->db->get_where('pegawai', ["id_pegawai" => $id_kasir])->row();
        $customer_service = $this->db->get_where('pegawai', ["id_pegawai" => $id_customer_service])->row();
        $tgl = $dataTransaksi->created_at;
        $pelanggan = $this->db->get_where('pelanggan', ["id_pelanggan" => $id_pelanggan])->row();
        $jenis_hewan = $this->db->get_where('jenis_hewan', ["id_jenis_hewan" => $id_jenis_hewan])->row();


        $subtotal = $dataTransaksi->subtotal;
        $id_transaksi = $dataTransaksi->id_transaksi_layanan;
        $diskon = $dataTransaksi->diskon;
        $total = $dataTransaksi->total;
        $tanggal_lunas = $dataTransaksi->tanggal_lunas;
        // $nama_kasir = $kasir->nama;
        $nama_customer_service = $customer_service->nama;
        $nama_pelanggan = $pelanggan->nama;
        $no_telp = $pelanggan->telp;
        $nama_jenis_hewan = $jenis_hewan->nama;
        $nama_hewan = $hewan->nama;

        $month_name = array("Januari", "Februari", "Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
        $nowDate = date("d");
        $nowMonth = date("m");
        $nowYear = date("Y");
        //setlocale(LC_TIME, 'id');
        //$month_name = date('F', mktime(0, 0, 0, $nowMonth));
        $id_p = sprintf( $id_transaksi);
        $newDate = date("Y-m-d", strtotime($tgl));
        // setting jenis font yang akan digunakan
        $pdf->Image(APPPATH.'controllers/PDF/Logo/kouvee.png',10,10,-200);
        // $pdf->Image(APPPATH.'controllers/PDF/Logo/kouveelogo.png',20,25,-800);
        $pdf->Cell(10,50,'',0,1);
        // $pdf->Image(APPPATH.'controllers/PDF/Logo/kotak.jpg',5,80,-700);
        // Memberikan space kebawah agar tidak terlalu rapat
        $pdf->Cell(70);
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(50,7,'Nota Lunas',0,1,'C');
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(140);
        $pdf->Cell(30,8,'NO : '.$id_p,0,1);
        $pdf->Cell(140);
        $pdf->Cell(30,8,'Tanggal : '.$tgl,0,0);
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(10,10,'',0,1);
        $pdf->Cell(45,6,'Kasir  ',0,0);
        // $pdf->Cell(45,6,':  '.$nama_kasir,0,1);
        $pdf->Cell(45,6,'Customer Service ',0,0);
        $pdf->Cell(45,6,':  '.$nama_customer_service,0,1);
        $pdf->Cell(45,6,'Member  ',0,0);
        $pdf->Cell(45,6,':  '.$nama_pelanggan,0,1);
        $pdf->Cell(45,6,'Telp  ',0,0);
        $pdf->Cell(45,6,':  '.$no_telp,0,1);
        $pdf->Cell(45,6,'Nama Hewan  ',0,0);
        $pdf->Cell(45,6,':  '.$nama_hewan.'-'.'('.$nama_jenis_hewan.')',0,1);
        // $pdf->Cell(30,6,$alamat_supplier,0,1);
        // $pdf->Cell(30,6,$no_telp,0,1);
        $pdf->Cell(10,10,'',0,1);
        $pdf->Cell(70);
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(50,7,'Jasa Layanan',0,1,'C');
        $pdf->Cell(180,7,'_________________________________________________________________',0,1,'C');
    
      
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(10,10,'',0,1);
        $pdf->Cell(45,6,'NAMA LAYANAN',1,0,'C');
        $pdf->Cell(45,6,'HARGA',1,0,'C');
        $pdf->Cell(45,6,'JUMLAH',1,0,'C');
        $pdf->Cell(45,6,'TOTAL',1,1,'C');
        $pdf->SetFont('Arial','',10);
        $i = 1;
   
               
                foreach ($dataDetailTransaksi as $loop){
                    if($loop->id_transaksi_layanan == $dataTransaksi->id_transaksi_layanan)
                    {
                     
                        $id_layanan = $loop->id_layanan;
                        $produk = $this->db->get_where('layanan', ["id_layanan" => $id_layanan])->row();
                        $pdf->Cell(45,10,$produk->nama,1,0,'L');
                        $pdf->Cell(45,10,$produk->harga,1,0,'C');
                        $pdf->Cell(45,10,$loop->jumlah,1,0,'C');
                        $pdf->Cell(45,10,$loop->total_harga,1,1,'C');
                    } 
                }
                $pdf->Cell(10,10,'',0,1);
                $pdf->Cell(45,6,'Sub Total :Rp.   '.$subtotal,0,1);
                $pdf->Cell(45,6,'Diskon     :Rp.   '.$diskon,0,1);
                $pdf->SetFont('Arial','B',16);
                $pdf->Cell(65,10,'Total       :Rp.   '.$total,1,1);
           
        date_default_timezone_set('Asia/Jakarta');
        $now = date("d-m-Y");
        $pdf->Cell(10,20,'',0,1);
        $pdf->Cell(135);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(30,7,'Dicetak tanggal '.$nowDate.' '.$month_name[intval($nowMonth)-1].' '.$nowYear,0,1,'C');
        $pdf->Output('Struk_Produk_Kouvee_.pdf','I');
        //.$param
    }
}