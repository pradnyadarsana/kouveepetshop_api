<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');
use chriskacerguis\RestServer\RestController;
Class CetakStruk extends RestController{
    
    function __construct() {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->library('pdf');
        include_once APPPATH . '/third_party/fpdf/fpdf.php';
    }
    
    function transaksiLayanan_get($param){
        // $this->load->helper('directory'); //load directory helper
        $dir = "controllers/PDF/"; // Your Path to folder
        // $map = directory_map($dir); /* This function reads the directory path specified in the first parameter and builds an array representation of it and all its contained files. */
        $pdf = new FPDF('p','mm','A4');
        // membuat halaman baru
        $pdf->AddPage();
    
        $dataTransaksi = null;
        $dataDetailTransaksi = null;

        $nama_kasir = "-";
        $nama_customer_service = "-";
        $nama_jenis_hewan = "-";
        $nama_hewan = "Guest";
        $nama_pelanggan = "Guest";
        $no_telp = "-";

        $resultTransaksi = $this->db->get_where('transaksi_layanan', ["id_transaksi_layanan" => $param]);
        if($resultTransaksi->num_rows()!=0){
            $dataTransaksi = $resultTransaksi->row();
            $dataDetailTransaksi = $this->db->get_where('detail_transaksi_layanan', ["id_transaksi_layanan" => $param])->result();
            
            
            if($dataTransaksi->id_kasir!=null){
                $id_kasir = $dataTransaksi->id_kasir;
                $kasir = $this->db->get_where('pegawai', ["id_pegawai" => $id_kasir])->row();
                $nama_kasir = $kasir->nama;
            }
            if($dataTransaksi->id_customer_service!=null){
                $id_customer_service = $dataTransaksi->id_customer_service;
                $customer_service = $this->db->get_where('pegawai', ["id_pegawai" => $id_customer_service])->row();
                $nama_customer_service = $customer_service->nama;
            }
            if($dataTransaksi->id_hewan!=null){
                $id_hewan = $dataTransaksi->id_hewan;
                $hewan = $this->db->get_where('hewan', ["id_hewan" => $id_hewan])->row();
                $id_pelanggan = $hewan->id_pelanggan;
                $id_jenis_hewan = $hewan->id_jenis_hewan;

                $jenis_hewan = $this->db->get_where('jenis_hewan', ["id_jenis_hewan" => $id_jenis_hewan])->row();
                $pelanggan = $this->db->get_where('pelanggan', ["id_pelanggan" => $id_pelanggan])->row();

                $nama_jenis_hewan = $jenis_hewan->nama;
                $nama_hewan = $hewan->nama;
                $nama_pelanggan = $pelanggan->nama;
                $no_telp = $pelanggan->telp;
            }
        }else{
            $this->returnData("ID Transaksi Layanan tidak ditemukan!",true);
        }
        
        $subtotal = $dataTransaksi->subtotal;
        $id_transaksi = $dataTransaksi->id_transaksi_layanan;
        $total = $dataTransaksi->total;
        $tanggal_lunas = "-";
        $diskon = "0";
        $tgl = $dataTransaksi->created_at;
        if($dataTransaksi->diskon!=null){
            $diskon = $dataTransaksi->diskon;
        }
        if($dataTransaksi->tanggal_lunas!=null){
            $tanggal_lunas = date("j F Y H:i",strtotime($dataTransaksi->tanggal_lunas));
        }

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
        $pdf->Cell(30,8,'Tanggal : '.$tanggal_lunas,0,0);
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(10,10,'',0,1);
        $pdf->Cell(45,6,'Kasir  ',0,0);
        $pdf->Cell(45,6,':  '.$nama_kasir,0,1);
        $pdf->Cell(45,6,'Customer Service ',0,0);
        $pdf->Cell(45,6,':  '.$nama_customer_service,0,1);
        $pdf->Cell(45,6,'Member  ',0,0);
        $pdf->Cell(45,6,':  '.$nama_pelanggan,0,1);
        $pdf->Cell(45,6,'Telp  ',0,0);
        $pdf->Cell(45,6,':  '.$no_telp,0,1);
        $pdf->Cell(45,6,'Nama Hewan  ',0,0);
        $pdf->Cell(45,6,':  '.$nama_hewan.' - '.'('.$nama_jenis_hewan.')',0,1);
        // $pdf->Cell(30,6,$alamat_supplier,0,1);
        // $pdf->Cell(30,6,$no_telp,0,1);
        $pdf->Cell(10,10,'',0,1);
        $pdf->Cell(70);
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(50,7,'Jasa Layanan',0,1,'C');
        $pdf->Cell(180,7,'_________________________________________________________________',0,1,'C');
    
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(10,6,'NO',1,0,'C');
        $pdf->Cell(55,6,'NAMA LAYANAN',1,0,'C');
        $pdf->Cell(35,6,'UKURAN',1,0,'C');
        $pdf->Cell(30,6,'HARGA',1,0,'C');
        $pdf->Cell(20,6,'JUMLAH',1,0,'C');
        $pdf->Cell(30,6,'TOTAL',1,1,'C');
        $pdf->SetFont('Arial','',10);
        $i = 1;

        foreach ($dataDetailTransaksi as $loop){
            if($loop->id_transaksi_layanan == $dataTransaksi->id_transaksi_layanan)
            {

                $id_harga_layanan = $loop->id_harga_layanan;
                $harga_layanan = $this->db->get_where('harga_layanan', ["id_harga_layanan" => $id_harga_layanan])->row();
                $harga = $harga_layanan->harga;
                $id_layanan = $harga_layanan->id_layanan;
                $layanan = $this->db->get_where('layanan', ["id_layanan" => $id_layanan])->row();
                $nama_layanan = $layanan->nama;
                $id_ukuran_hewan = $harga_layanan->id_ukuran_hewan;
                $ukuran = $this->db->get_where('ukuran_hewan', ["id_ukuran_hewan" => $id_ukuran_hewan])->row();
                $nama_ukuran = $ukuran->nama;       
                
                $pdf->Cell(10,10,$i,1,0,'C');
                $pdf->Cell(55,10,$layanan->nama,1,0,'C');
                $pdf->Cell(35,10,$ukuran->nama,1,0,'C');
                $pdf->Cell(30,10,'Rp. '.$harga_layanan->harga,1,0,'C');
                $pdf->Cell(20,10,$loop->jumlah,1,0,'C');
                $pdf->Cell(30,10,'Rp. '.$loop->total_harga,1,1,'C');
            }
            $i++;
        }
        $pdf->Cell(10,10,'',0,1);
        $pdf->Cell(45,6,'Sub Total :Rp.   '.$subtotal,0,1);
        $pdf->Cell(45,6,'Diskon     :Rp.   '.$diskon,0,1);
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(60,10,'Total       :Rp. '.$total,1,1);

        $now = date("d-m-Y");
        $pdf->Cell(10,20,'',0,1);
        $pdf->Cell(135);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(30,7,'Dicetak tanggal '.$nowDate.' '.$month_name[intval($nowMonth)-1].' '.$nowYear,0,1,'C');
        $pdf->Output($id_transaksi.'.pdf','D');
        //.$param
    }

    function transaksiProduk_get($param){
        // $this->load->helper('directory'); //load directory helper
        $dir = "controllers/PDF/"; // Your Path to folder
        // $map = directory_map($dir); /* This function reads the directory path specified in the first parameter and builds an array representation of it and all its contained files. */
        $pdf = new FPDF('p','mm','A4');
        // membuat halaman baru
        $pdf->AddPage();
    
        $dataTransaksi = null;
        $dataDetailTransaksi = null;

        $nama_kasir = "-";
        $nama_customer_service = "-";
        $nama_jenis_hewan = "-";
        $nama_hewan = "Guest";
        $nama_pelanggan = "Guest";
        $no_telp = "-";

        $resultTransaksi = $this->db->get_where('transaksi_produk', ["id_transaksi_produk" => $param]);
        if($resultTransaksi->num_rows()!=0){
            $dataTransaksi = $resultTransaksi->row();
            $dataDetailTransaksi = $this->db->get_where('detail_transaksi_produk', ["id_transaksi_produk" => $param])->result();

            if($dataTransaksi->id_kasir!=null){
                $id_kasir = $dataTransaksi->id_kasir;
                $kasir = $this->db->get_where('pegawai', ["id_pegawai" => $id_kasir])->row();
                $nama_kasir = $kasir->nama;
            }
            if($dataTransaksi->id_customer_service!=null){
                $id_customer_service = $dataTransaksi->id_customer_service;
                $customer_service = $this->db->get_where('pegawai', ["id_pegawai" => $id_customer_service])->row();
                $nama_customer_service = $customer_service->nama;
            }
            if($dataTransaksi->id_hewan!=null){
                $id_hewan = $dataTransaksi->id_hewan;
                $hewan = $this->db->get_where('hewan', ["id_hewan" => $id_hewan])->row();
                $id_pelanggan = $hewan->id_pelanggan;
                $id_jenis_hewan = $hewan->id_jenis_hewan;

                $jenis_hewan = $this->db->get_where('jenis_hewan', ["id_jenis_hewan" => $id_jenis_hewan])->row();
                $pelanggan = $this->db->get_where('pelanggan', ["id_pelanggan" => $id_pelanggan])->row();

                $nama_jenis_hewan = $jenis_hewan->nama;
                $nama_hewan = $hewan->nama;
                $nama_pelanggan = $pelanggan->nama;
                $no_telp = $pelanggan->telp;
            }
        }else{
            $this->returnData("ID Transaksi Produk tidak ditemukan!",true);
        }

        $subtotal = $dataTransaksi->subtotal;
        $id_transaksi = $dataTransaksi->id_transaksi_produk;
        $total = $dataTransaksi->total;
        $tanggal_lunas = "-";
        $diskon = "0";
        $tgl = $dataTransaksi->created_at;
        if($dataTransaksi->diskon!=null){
            $diskon = $dataTransaksi->diskon;
        }
        if($dataTransaksi->tanggal_lunas!=null){
            $tanggal_lunas = date("j F Y H:i",strtotime($dataTransaksi->tanggal_lunas));
        }

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
        $pdf->Cell(30,8,'Tanggal : '.$tanggal_lunas,0,0);
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(10,10,'',0,1);
        $pdf->Cell(45,6,'Kasir  ',0,0);
        $pdf->Cell(45,6,':  '.$nama_kasir,0,1);
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
        $pdf->Cell(50,7,'Pembelian Produk',0,1,'C');
        $pdf->Cell(180,7,'_________________________________________________________________',0,1,'C');

        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(10,6,'NO',1,0,'C');
        $pdf->Cell(70,6,'NAMA PRODUK',1,0,'C');
        $pdf->Cell(40,6,'HARGA',1,0,'C');
        $pdf->Cell(20,6,'JUMLAH',1,0,'C');
        $pdf->Cell(40,6,'TOTAL',1,1,'C');
        $pdf->SetFont('Arial','',10);
        $i = 1;

        foreach ($dataDetailTransaksi as $loop){
            if($loop->id_transaksi_produk == $dataTransaksi->id_transaksi_produk)
            {
                
                $id_produk = $loop->id_produk;
                $produk = $this->db->get_where('produk', ["id_produk" => $id_produk])->row();
                $pdf->Cell(10,10,$i,1,0,'C');
                $pdf->Cell(70,10,$produk->nama,1,0,'L');
                $pdf->Cell(40,10,'Rp. '.$produk->harga,1,0,'C');
                $pdf->Cell(20,10,$loop->jumlah,1,0,'C');
                $pdf->Cell(40,10,'Rp. '.$loop->total_harga,1,1,'C');
            }
            $i++;
        }
        $pdf->Cell(10,10,'',0,1);
        $pdf->Cell(45,6,'Sub Total :Rp.   '.$subtotal,0,1);
        $pdf->Cell(45,6,'Diskon     :Rp.   '.$diskon,0,1);
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(65,10,'Total :Rp. '.$total,1,1);

        $now = date("d-m-Y");
        $pdf->Cell(10,20,'',0,1);
        $pdf->Cell(135);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(30,7,'Dicetak tanggal '.$nowDate.' '.$month_name[intval($nowMonth)-1].' '.$nowYear,0,1,'C');
        $pdf->Output($id_transaksi.'.pdf','D');
        //.$param
    }
    function pengadaanProduk_get($param){
        // $this->load->helper('directory'); //load directory helper
        $dir = "controllers/PDF/"; // Your Path to folder
        // $map = directory_map($dir); /* This function reads the directory path specified in the first parameter and builds an array representation of it and all its contained files. */
        $pdf = new FPDF('p','mm','A4');
        // membuat halaman baru
        $pdf->AddPage();
    
        $dataPengadaan = null;
        $dataDetailPengadaan = null;

        $nama_supplier = "-";
        $nama_produk = "-";
        $jumlah = "-";
        $harga = "-";
        $total = "-";
        $total_harga = "-";

        $resultPengadaan = $this->db->get_where('pengadaan_produk', ["id_pengadaan_produk" => $param]);
        if($resultPengadaan->num_rows()!=0){
            $dataPengadaan = $resultPengadaan->row();
            $dataDetailPengadaan = $this->db->get_where('detail_pengadaan', ["id_pengadaan_produk" => $param])->result();

            if($dataPengadaan->id_supplier!=null){
                $id_supplier = $dataPengadaan->id_supplier;
                $supplier = $this->db->get_where('supplier', ["id_supplier" => $id_supplier])->row();
                $nama_supplier = $supplier->nama;
                $telp_supplier = $supplier->telp;
                $alamat_supplier = $supplier->alamat;
            }
            // if($dataPengadaan->id_produk!=null){
            //     $id_produk = $dataPengadaan->id_produk;
            //     $produk = $this->db->get_where('produk', ["id_produk" => $id_produk])->row();
            //     $nama_produk = $produk->nama;
            // }
           
        }else{
            $this->returnData("ID Pengadaan Produk tidak ditemukan!",true);
        }

        $total = $dataPengadaan->total;
        $id_pengadaan_produk = $dataPengadaan->id_pengadaan_produk;
        $tgl = $dataPengadaan->created_at;
        

        $month_name = array("Januari", "Februari", "Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
        $nowDate = date("d");
        $nowMonth = date("m");
        $nowYear = date("Y");
        //setlocale(LC_TIME, 'id');
        //$month_name = date('F', mktime(0, 0, 0, $nowMonth));
        $id_p = sprintf( $id_pengadaan_produk);
        $newDate = date("Y-m-d", strtotime($tgl));
        // setting jenis font yang akan digunakan
        $pdf->Image(APPPATH.'controllers/PDF/Logo/kouvee.png',10,10,-200);
        // $pdf->Image(APPPATH.'controllers/PDF/Logo/kouveelogo.png',20,25,-800);
        $pdf->Cell(10,50,'',0,1);
        // $pdf->Image(APPPATH.'controllers/PDF/Logo/kotak.jpg',5,80,-700);
        // Memberikan space kebawah agar tidak terlalu rapat
        $pdf->Cell(70);
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(50,7,'Surat Pemesanan',0,1,'C');
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(140);
        $pdf->Cell(15,8,'NO',0,0);
        $pdf->Cell(15,8,': '.$id_p,0,1);
        $pdf->Cell(140);
        $pdf->Cell(15,8,'Tanggal',0,0);
        $pdf->Cell(15,8,': '.$tgl,0,0);
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(10,10,'',0,1);
        $pdf->Image(APPPATH.'controllers/PDF/kotak.jpg',5,80,-600);
        // $pdf->Cell(45,6,'Supplier  ',0,0);
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(45,6,'Kepada Yth.',0,1);
        $pdf->Cell(5,5,'',0,1);
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(45,6,$nama_supplier,0,1);
        // $pdf->Cell(45,6,'Alamat ',0,0);
        $pdf->Cell(45,6,$alamat_supplier,0,1);
        // $pdf->Cell(45,6,'Telp  ',0,0);
        $pdf->Cell(45,6,$telp_supplier,0,1);
        $pdf->Cell(10,10,'',0,1);
        // $pdf->Cell(70);
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(45,7,'Mohon disediakan produk-produk berikut :',0,1,'L');
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(180,7,'_________________________________________________________________',0,1,'C');

        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(10,6,'NO',1,0,'C');
        $pdf->Cell(70,6,'NAMA PRODUK',1,0,'C');
        $pdf->Cell(40,6,'Satuan',1,0,'C');
        $pdf->Cell(60,6,'JUMLAH',1,1,'C');

        $pdf->SetFont('Arial','',10);
        $i = 1;

        foreach ($dataDetailPengadaan as $loop){
            if($loop->id_pengadaan_produk == $dataPengadaan->id_pengadaan_produk)
            {
                
                $id_produk = $loop->id_produk;
                $jumlah = $loop->jumlah;
                $produk = $this->db->get_where('produk', ["id_produk" => $id_produk])->row();
                $satuan = $produk->satuan;
                $pdf->Cell(10,10,$i,1,0,'C');
                $pdf->Cell(70,10,$produk->nama,1,0,'L');
                $pdf->Cell(40,10,$satuan,1,0,'C');
                $pdf->Cell(60,10,$loop->jumlah,1,1,'C');

            }
            $i++;
        }


        $now = date("d-m-Y");
        $pdf->Cell(10,20,'',0,1);
        $pdf->Cell(135);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(30,7,'Dicetak tanggal '.$nowDate.' '.$month_name[intval($nowMonth)-1].' '.$nowYear,0,1,'C');
        $pdf->Output($id_pengadaan_produk.'.pdf','D');
        //.$param
    }

    function laporanPengadaanBulanan_get($param){
        // $this->load->helper('directory'); //load directory helper
        $dir = "controllers/PDF/"; // Your Path to folder
        // $map = directory_map($dir); /* This function reads the directory path specified in the first parameter and builds an array representation of it and all its contained files. */
        $pdf = new FPDF('p','mm','A4');
        // membuat halaman baru
        $pdf->AddPage();
    
        $i = 1;
        $totalPengeluaran = 0;
        $produk = array();
        $cekNama = array();
        $bulan = explode("-", $param);
        $data = "SELECT pengadaan_produk.id_pengadaan_produk , pengadaan_produk.total  from pengadaan_produk
        WHERE month(pengadaan_produk.created_at)=? AND year(pengadaan_produk.created_at)=? AND pengadaan_produk.status = 'Pesanan Selesai'
        GROUP BY pengadaan_produk.id_pengadaan_produk";
        $hasil = $this->db->query($data,[$bulan[1],$bulan[0]])->result();
        $detailPengadaan = "SELECT produk.nama, detail_pengadaan.total_harga from detail_pengadaan
                INNER JOIN produk USING(id_produk)
                WHERE detail_pengadaan.id_pengadaan_produk = ?
                GROUP BY produk.nama";
        for($k = 0;$k <sizeof($hasil); $k++ ){
                $hasil2[$k] = $this->db->query($detailPengadaan,[$hasil[$k]->id_pengadaan_produk])->result();
            }

        for($l = 0 ; $l < count($hasil2) ; $l++){
            for($m = 0 ; $m < count($hasil2) ; $m++){
                if(isset($hasil2[$l][$m])){

                    array_push($produk,$hasil2[$l][$m]); 
                }
                }
            }
        for($o = 0; $o<count($produk);$o++){
            for($p = $o +1; $p<count($produk); $p++){
                if($produk[$o]->nama == $produk[$p]->nama){
                    $produk[$o]->total_harga = $produk[$o]->total_harga + $produk[$p]->total_harga;
                    \array_splice($produk, $p, 1);
                }
            }
        }
        for($q = 0; $q< count($hasil); $q++){
            $totalPengeluaran = $totalPengeluaran + $hasil[$q]->total;
        }

        

        $tgl = $bulan[1];
        if($bulan[1]==1){
            $tgl = 'Januari';
        }else if($bulan[1]==2){
            $tgl = 'Februari';
        }else if($bulan[1]==3){
            $tgl = 'Maret';
        }else if($bulan[1]==4){
            $tgl = 'April';
        }else if($bulan[1]==5){
            $tgl = 'Mei';
        }else if($bulan[1]==6){
            $tgl = 'Juni';
        }else if($bulan[1]==7){
            $tgl = 'Juli';
        }else if($bulan[1]==8){
            $tgl = 'Agustus';
        }else if($bulan[1]==9){
            $tgl = 'September';
        }else if($bulan[1]==10){
            $tgl = 'Oktober';
        }else if($bulan[1]==11){
            $tgl = 'November';
        }else if($bulan[1]==12){
            $tgl = 'Desember';
        }

        $month_name = array("Januari", "Februari", "Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
        $nowDate = date("d");
        $nowMonth = date("m");
        $nowYear = date("Y");
        //setlocale(LC_TIME, 'id');
        //$month_name = date('F', mktime(0, 0, 0, $nowMonth));
        
        $newDate = date("Y-m-d", strtotime($tgl));
        // setting jenis font yang akan digunakan
        $pdf->Image(APPPATH.'controllers/PDF/Logo/kouvee.png',10,10,-200);
        // $pdf->Image(APPPATH.'controllers/PDF/Logo/kouveelogo.png',20,25,-800);
        $pdf->Cell(10,50,'',0,1);
        // $pdf->Image(APPPATH.'controllers/PDF/Logo/kotak.jpg',5,80,-700);
        // Memberikan space kebawah agar tidak terlalu rapat
        $pdf->Cell(70);
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(50,7,'Laporan Pengadaan Bulanan',0,1,'C');
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(15,8,'Bulan',0,0);
        $pdf->Cell(15,8,': '.$tgl,0,1);
        $pdf->Cell(15,8,'Tahun',0,0);
        $pdf->Cell(15,8,': '.$bulan[0],0,0);
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(10,10,'',0,1);

        $pdf->Cell(10,10,'',0,1);
        // $pdf->Cell(70);
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(180,7,'_________________________________________________________________',0,1,'C');

        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(10,6,'NO',1,0,'C');
        $pdf->Cell(85,6,'NAMA PRODUK',1,0,'C');
        $pdf->Cell(85,6,'JUMLAH PENGELUARAN',1,1,'C');

        $pdf->SetFont('Arial','',10);
        $i = 1;

        foreach ($produk as $loop){
      
                

                $pdf->Cell(10,10,$i,1,0,'C');
                $pdf->Cell(85,10,$loop->nama,1,0,'L');
                $pdf->Cell(85,10,'Rp  '.$loop->total_harga,1,1,'L');

            
            $i++;
        }
        $pdf->Cell(10,10,'',0,1);
 
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(65,10,'Total :Rp. '.$totalPengeluaran,1,1);


        $now = date("d-m-Y");
        $pdf->Cell(10,20,'',0,1);
        $pdf->Cell(135);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(30,7,'Dicetak tanggal '.$nowDate.' '.$month_name[intval($nowMonth)-1].' '.$nowYear,0,1,'C');
        $pdf->Output($nowDate.'.pdf','I');
        //.$param
    }

    public function returnData($msg,$error){
        $response['error']=$error;
        $response['message']=$msg;
        return $this->response($response);
    }
}