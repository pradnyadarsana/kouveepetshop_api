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
    function laporanProdukTerlaris_get($param){
        // $this->load->helper('directory'); //load directory helper
        $dir = "controllers/PDF/"; // Your Path to folder
        // $map = directory_map($dir); /* This function reads the directory path specified in the first parameter and builds an array representation of it and all its contained files. */
        $pdf = new FPDF('p','mm','A4');
        // membuat halaman baru
        $pdf->AddPage();
    
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
        $produkMax1 = '-';
        $produkMax2= '-';
        $produkMax3 = '-';
        $produkMax4 = '-';
        $produkMax5 = '-';
        $produkMax6 = '-';
        $produkMax7 = '-';
        $produkMax8 = '-';
        $produkMax9 = '-';
        $produkMax10 = '-';
        $produkMax11= '-';
        $produkMax12 = '-';
        $bulan = explode("-", $param);
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

        $tgl = $param;


        $month_name = array("Januari", "Februari", "Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
        $nowDate = date("d");
        $nowMonth = date("m");
        $nowYear = date("Y");
        //setlocale(LC_TIME, 'id');
        //$month_name = date('F', mktime(0, 0, 0, $nowMonth));
        
        $newDate = date("Y-m-d", strtotime($tgl));
        // setting jenis font yang akan digunakan
        $pdf->Image(APPPATH.'controllers/PDF/Logo/kouvee.png',10,10,-200);
        $pdf->Cell(10,50,'',0,1);
        $pdf->Cell(70);
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(50,7,'Laporan Produk Terlaris',0,1,'C');
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(15,8,'Tahun',0,0);
        $pdf->Cell(15,8,': '.$tgl,0,1);

        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(180,7,'_________________________________________________________________',0,1,'C');

        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(10,6,'NO',1,0,'C');
        $pdf->Cell(40,6,'BULAN',1,0,'C');
        $pdf->Cell(65,6,'NAMA PRODUK',1,0,'C');
        $pdf->Cell(65,6,'JUMLAH PEMBELIAN',1,1,'C');
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(10,10,'1',1,0,'C');
        $pdf->Cell(40,10,'Januari',1,0,'L');
        $pdf->Cell(65,10,$produkMax1,1,0,'C');
        $pdf->Cell(65,10,$jumlahMax1,1,1,'C');
        $pdf->Cell(10,10,'2',1,0,'C');
        $pdf->Cell(40,10,'Februari',1,0,'L');
        $pdf->Cell(65,10,$produkMax2,1,0,'C');
        $pdf->Cell(65,10,$jumlahMax2,1,1,'C');
        $pdf->Cell(10,10,'3',1,0,'C');
        $pdf->Cell(40,10,'Maret',1,0,'L');
        $pdf->Cell(65,10,$produkMax3,1,0,'C');
        $pdf->Cell(65,10,$jumlahMax3,1,1,'C');
        $pdf->Cell(10,10,'4',1,0,'C');
        $pdf->Cell(40,10,'April',1,0,'L');
        $pdf->Cell(65,10,$produkMax4,1,0,'C');
        $pdf->Cell(65,10,$jumlahMax4,1,1,'C');
        $pdf->Cell(10,10,'5',1,0,'C');
        $pdf->Cell(40,10,'Mei',1,0,'L');
        $pdf->Cell(65,10,$produkMax5,1,0,'C');
        $pdf->Cell(65,10,$jumlahMax5,1,1,'C');
        $pdf->Cell(10,10,'6',1,0,'C');
        $pdf->Cell(40,10,'Juni',1,0,'L');
        $pdf->Cell(65,10,$produkMax6,1,0,'C');
        $pdf->Cell(65,10,$jumlahMax6,1,1,'C');
        $pdf->Cell(10,10,'7',1,0,'C');
        $pdf->Cell(40,10,'Juli',1,0,'L');
        $pdf->Cell(65,10,$produkMax7,1,0,'C');
        $pdf->Cell(65,10,$jumlahMax7,1,1,'C');
        $pdf->Cell(10,10,'8',1,0,'C');
        $pdf->Cell(40,10,'Agustus',1,0,'L');
        $pdf->Cell(65,10,$produkMax8,1,0,'C');
        $pdf->Cell(65,10,$jumlahMax8,1,1,'C');
        $pdf->Cell(10,10,'9',1,0,'C');
        $pdf->Cell(40,10,'September',1,0,'L');
        $pdf->Cell(65,10,$produkMax9,1,0,'C');
        $pdf->Cell(65,10,$jumlahMax9,1,1,'C');
        $pdf->Cell(10,10,'10',1,0,'C');
        $pdf->Cell(40,10,'October',1,0,'L');
        $pdf->Cell(65,10,$produkMax10,1,0,'C');
        $pdf->Cell(65,10,$jumlahMax10,1,1,'C');
        $pdf->Cell(10,10,'11',1,0,'C');
        $pdf->Cell(40,10,'November',1,0,'L');
        $pdf->Cell(65,10,$produkMax11,1,0,'C');
        $pdf->Cell(65,10,$jumlahMax11,1,1,'C');
        $pdf->Cell(10,10,'12',1,0,'C');
        $pdf->Cell(40,10,'Desember',1,0,'L');
        $pdf->Cell(65,10,$produkMax12,1,0,'C');
        $pdf->Cell(65,10,$jumlahMax12,1,1,'C');


        $now = date("d-m-Y");
        $pdf->Cell(10,20,'',0,1);
        $pdf->Cell(135);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(30,7,'Dicetak tanggal '.$nowDate.' '.$month_name[intval($nowMonth)-1].' '.$nowYear,0,1,'C');
        $pdf->Output($nowDate.'.pdf','I');
        //.$param
    }
    function laporanPendapatanBulanan_get($param){
        // $this->load->helper('directory'); //load directory helper
        $dir = "controllers/PDF/"; // Your Path to folder
        // $map = directory_map($dir); /* This function reads the directory path specified in the first parameter and builds an array representation of it and all its contained files. */
        $pdf = new FPDF('p','mm','A4');
        // membuat halaman baru
        $pdf->AddPage();
    
        $i = 1;
        $totalProduk = 0;
        $totalLayanan = 0;
        $produk = array();
        $layanan = array();
        $bulan = explode("-", $param);
        $dataProduk = "SELECT transaksi_produk.id_transaksi_produk , transaksi_produk.subtotal  from transaksi_produk
        WHERE month(transaksi_produk.created_at)=? AND year(transaksi_produk.created_at)=? AND transaksi_produk.status = 'Lunas'
        GROUP BY transaksi_produk.id_transaksi_produk";
        $hasilProduk = $this->db->query($dataProduk,[$bulan[1],$bulan[0]])->result();


        $dataLayanan = "SELECT transaksi_layanan.id_transaksi_layanan , transaksi_layanan.subtotal  from transaksi_layanan
        WHERE month(transaksi_layanan.created_at)=? AND year(transaksi_layanan.created_at)=? AND transaksi_layanan.status = 'Lunas'
        GROUP BY transaksi_layanan.id_transaksi_layanan";
        $hasilLayanan = $this->db->query($dataLayanan,[$bulan[1],$bulan[0]])->result();
      

        $detailProduk = "SELECT produk.nama, detail_transaksi_produk.total_harga from detail_transaksi_produk
                INNER JOIN produk USING(id_produk)
                WHERE detail_transaksi_produk.id_transaksi_produk = ?
                GROUP BY produk.nama";
        for($k = 0;$k <sizeof($hasilProduk); $k++ ){
                $hasilProduk2[$k] = $this->db->query($detailProduk,[$hasilProduk[$k]->id_transaksi_produk])->result();
            }
            // print_r($hasilProduk);

        $detailLayanan = "SELECT layanan.nama, detail_transaksi_layanan.total_harga from detail_transaksi_layanan
                JOIN harga_layanan USING(id_harga_layanan)
                INNER JOIN layanan USING(id_layanan)
                WHERE detail_transaksi_layanan.id_transaksi_layanan = ?
                GROUP BY layanan.nama";
        for($k = 0;$k <sizeof($hasilLayanan); $k++ ){
                $hasilLayanan2[$k] = $this->db->query($detailLayanan,[$hasilLayanan[$k]->id_transaksi_layanan])->result();
            }
            // print_r($hasilLayanan2);

        for($l = 0 ; $l < count($hasilProduk2) ; $l++){
            for($m = 0 ; $m < count($hasilProduk2) ; $m++){
                if(isset($hasilProduk2[$l][$m])){

                    array_push($produk,$hasilProduk2[$l][$m]); 
                }
                }
            }
        for($l = 0 ; $l < count($hasilLayanan2) ; $l++){
            for($m = 0 ; $m < count($hasilLayanan2) ; $m++){
                if(isset($hasilLayanan2[$l][$m])){

                    array_push($layanan,$hasilLayanan2[$l][$m]); 
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
        for($o = 0; $o<count($layanan);$o++){
            for($p = $o +1; $p<count($layanan); $p++){
                if($layanan[$o]->nama == $layanan[$p]->nama){
                    $layanan[$o]->total_harga = $layanan[$o]->total_harga + $layanan[$p]->total_harga;
                    \array_splice($layanan, $p, 1);
                }
            }
        }
        for($q = 0; $q< count($hasilProduk); $q++){
            $totalProduk = $totalProduk + $hasilProduk[$q]->subtotal;
        }
        for($q = 0; $q< count($hasilLayanan); $q++){
            $totalLayanan = $totalLayanan + $hasilLayanan[$q]->subtotal;
        }

        $tgl = $bulan[1];
        $tahun = $bulan[0];
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
        $pdf->Cell(10,50,'',0,1);
        $pdf->Cell(70);
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(50,7,'Laporan Pendapatan Bulanan',0,1,'C');
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(15,8,'Bulan',0,0);
        $pdf->Cell(15,8,': '.$tgl,0,1);
        $pdf->Cell(15,8,'Tahun',0,0);
        $pdf->Cell(15,8,': '.$tahun,0,1);

        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(180,7,'_________________________________________________________________',0,1,'C');

        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(10,6,'NO',1,0,'C');
        $pdf->Cell(85,6,'NAMA PRODUK',1,0,'C');
        $pdf->Cell(85,6,'HARGA',1,1,'C');
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
        $pdf->Cell(65,10,'Total :Rp. '.$totalProduk,1,1);

        $pdf->Cell(180,7,'',0,1,'C');
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(10,6,'NO',1,0,'C');
        $pdf->Cell(85,6,'NAMA JASA LAYANAN',1,0,'C');
        $pdf->Cell(85,6,'HARGA',1,1,'C');
        $pdf->SetFont('Arial','',10);
        $i = 1;

        foreach ($layanan as $loop){
                $pdf->Cell(10,10,$i,1,0,'C');
                $pdf->Cell(85,10,$loop->nama,1,0,'L');
                $pdf->Cell(85,10,'Rp  '.$loop->total_harga,1,1,'L');     
            $i++;
        }

        $pdf->Cell(10,10,'',0,1);
 
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(65,10,'Total :Rp. '.$totalLayanan,1,1);

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