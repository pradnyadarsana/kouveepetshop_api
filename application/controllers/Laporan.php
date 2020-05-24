<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');
use chriskacerguis\RestServer\RestController;
Class Laporan extends RestController{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('LaporanModel');
        $this->load->library('form_validation');
        $this->load->library('pdf');
        include_once APPPATH . '/third_party/fpdf/fpdf.php';
    }

    // public function pendapatanBulananProduk_get(){
    //     return $this->returnData($this->LaporanModel->PendapatanBulananProduk(), false);
    // }

    // public function pendapatanBulananLayanan_get(){
    //     return $this->returnData($this->LaporanModel->PendapatanBulananLayanan(), false);
    // }

    
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
        
            if(isset($hasil2)){
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
                        $pdf->Cell(85,10,'Rp  '.number_format($loop->total_harga,2),1,1,'L');

                    
                    $i++;
                }
                $pdf->Cell(10,10,'',0,1);
        
                $pdf->SetFont('Arial','B',16);
                $pdf->Cell(65,10,'Total :Rp '.number_format($totalPengeluaran,2),1,1);
            }else{

            
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

                $pdf->Cell(10,10,$i,1,0,'C');
                $pdf->Cell(85,10,'-',1,0,'L');
                $pdf->Cell(85,10,'Rp - ',1,1,'L');

            
  
        
        $pdf->Cell(10,10,'',0,1);
 
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(65,10,'Total :Rp -',1,1);
    }

        $now = date("d-m-Y");
        $pdf->Cell(10,20,'',0,1);
        $pdf->Cell(135);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(30,7,'Dicetak tanggal '.$nowDate.' '.$month_name[intval($nowMonth)-1].' '.$nowYear,0,1,'C');
        $pdf->Output('Laporan Pengadaan Bulan '.$tgl.'-'.$bulan[0].'.pdf','D');
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
        $data = "SELECT transaksi_produk.id_transaksi_produk , transaksi_produk.total  from transaksi_produk
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

            if(isset($hasil2)){
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
            
            }else{
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
        $pdf->Output('Laporan Produk Terlaris '.'-'.$bulan[0].'.pdf','D');
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
        $dataProduk = "SELECT transaksi_produk.id_transaksi_produk , transaksi_produk.total  from transaksi_produk
        WHERE month(transaksi_produk.created_at)=? AND year(transaksi_produk.created_at)=? AND transaksi_produk.status = 'Lunas'
        GROUP BY transaksi_produk.id_transaksi_produk";
        $hasilProduk = $this->db->query($dataProduk,[$bulan[1],$bulan[0]])->result();


        $dataLayanan = "SELECT transaksi_layanan.id_transaksi_layanan , transaksi_layanan.total  from transaksi_layanan
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
           

        $detailLayanan = "SELECT layanan.nama, detail_transaksi_layanan.total_harga from detail_transaksi_layanan
                JOIN harga_layanan USING(id_harga_layanan)
                INNER JOIN layanan USING(id_layanan)
                WHERE detail_transaksi_layanan.id_transaksi_layanan = ?
                GROUP BY layanan.nama";
        for($k = 0;$k <sizeof($hasilLayanan); $k++ ){
                $hasilLayanan2[$k] = $this->db->query($detailLayanan,[$hasilLayanan[$k]->id_transaksi_layanan])->result();
            }
    

        if(isset($hasilProduk2)){
            if(isset($hasilLayanan2)){
                for($l = 0 ; $l < count($hasilProduk2) ; $l++){
                    for($m = 0 ; $m < count($hasilProduk2) ; $m++){
                        if(isset($hasilProduk2[$l][$m])){
        
                            array_push($produk,$hasilProduk2[$l][$m]); 
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
                 for($q = 0; $q< count($hasilProduk); $q++){
                    $totalProduk = $totalProduk + $hasilProduk[$q]->total;
                }
        
                for($l = 0 ; $l < count($hasilLayanan2) ; $l++){
                    for($m = 0 ; $m < count($hasilLayanan2) ; $m++){
                        if(isset($hasilLayanan2[$l][$m])){
        
                            array_push($layanan,$hasilLayanan2[$l][$m]); 
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
                for($q = 0; $q< count($hasilLayanan); $q++){
                    $totalLayanan = $totalLayanan + $hasilLayanan[$q]->total;
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
                        $pdf->Cell(85,10,'Rp  '.number_format($loop->total_harga,2),1,1,'L');     
                    $i++;
                }
        
                $pdf->Cell(10,10,'',0,1);
         
                $pdf->SetFont('Arial','B',16);
                $pdf->Cell(65,10,'Total :Rp '.number_format($totalProduk,2),1,1);
        
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
                        $pdf->Cell(85,10,'Rp  '.number_format($loop->total_harga,2),1,1,'L');     
                    $i++;
                }
        
                $pdf->Cell(10,10,'',0,1);
         
                $pdf->SetFont('Arial','B',16);
                $pdf->Cell(65,10,'Total :Rp '.number_format($totalLayanan,2),1,1);
            }else{
                for($l = 0 ; $l < count($hasilProduk2) ; $l++){
                    for($m = 0 ; $m < count($hasilProduk2) ; $m++){
                        if(isset($hasilProduk2[$l][$m])){
        
                            array_push($produk,$hasilProduk2[$l][$m]); 
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
                 for($q = 0; $q< count($hasilProduk); $q++){
                    $totalProduk = $totalProduk + $hasilProduk[$q]->total;
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
                        $pdf->Cell(85,10,'Rp  '.number_format($loop->total_harga,2),1,1,'L');     
                    $i++;
                }
        
                $pdf->Cell(10,10,'',0,1);
         
                $pdf->SetFont('Arial','B',16);
                $pdf->Cell(65,10,'Total :Rp '.number_format($totalProduk,2),1,1);
        
                $pdf->Cell(180,7,'',0,1,'C');
                $pdf->SetFont('Arial','B',10);
                $pdf->Cell(10,6,'NO',1,0,'C');
                $pdf->Cell(85,6,'NAMA JASA LAYANAN',1,0,'C');
                $pdf->Cell(85,6,'HARGA',1,1,'C');
                $pdf->SetFont('Arial','',10);
                $i = 1;
        
                
                        $pdf->Cell(10,10,$i,1,0,'C');
                        $pdf->Cell(85,10,'-',1,0,'L');
                        $pdf->Cell(85,10,'Rp -',1,1,'L');     

        
                $pdf->Cell(10,10,'',0,1);
         
                $pdf->SetFont('Arial','B',16);
                $pdf->Cell(65,10,'Total :Rp -',1,1);
            }
        }else{
            if(isset($hasilLayanan2)){
                 
        
                for($l = 0 ; $l < count($hasilLayanan2) ; $l++){
                    for($m = 0 ; $m < count($hasilLayanan2) ; $m++){
                        if(isset($hasilLayanan2[$l][$m])){
        
                            array_push($layanan,$hasilLayanan2[$l][$m]); 
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
                for($q = 0; $q< count($hasilLayanan); $q++){
                    $totalLayanan = $totalLayanan + $hasilLayanan[$q]->total;
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
        

                        $pdf->Cell(10,10,$i,1,0,'C');
                        $pdf->Cell(85,10,'-',1,0,'L');
                        $pdf->Cell(85,10,'Rp -',1,1,'L');     

                $pdf->Cell(10,10,'',0,1);
         
                $pdf->SetFont('Arial','B',16);
                $pdf->Cell(65,10,'Total :Rp -',1,1);
        
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
                        $pdf->Cell(85,10,'Rp  '.number_format($loop->total_harga,2),1,1,'L');     
                    $i++;
                }
        
                $pdf->Cell(10,10,'',0,1);
         
                $pdf->SetFont('Arial','B',16);
                $pdf->Cell(65,10,'Total :Rp '.number_format($totalLayanan,2),1,1);
            }else{
               
        
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
        

                        $pdf->Cell(10,10,$i,1,0,'C');
                        $pdf->Cell(85,10,'-',1,0,'L');
                        $pdf->Cell(85,10,'Rp  -',1,1,'L');     

        
                $pdf->Cell(10,10,'',0,1);
         
                $pdf->SetFont('Arial','B',16);
                $pdf->Cell(65,10,'Total :Rp. -',1,1);
        
                $pdf->Cell(180,7,'',0,1,'C');
                $pdf->SetFont('Arial','B',10);
                $pdf->Cell(10,6,'NO',1,0,'C');
                $pdf->Cell(85,6,'NAMA JASA LAYANAN',1,0,'C');
                $pdf->Cell(85,6,'HARGA',1,1,'C');
                $pdf->SetFont('Arial','',10);
                $i = 1;

                        $pdf->Cell(10,10,$i,1,0,'C');
                        $pdf->Cell(85,10,'-',1,0,'L');
                        $pdf->Cell(85,10,'Rp  -',1,1,'L');     

        
                $pdf->Cell(10,10,'',0,1);
         
                $pdf->SetFont('Arial','B',16);
                $pdf->Cell(65,10,'Total :Rp. -',1,1);
            }
        }
        
        $month_name = array("Januari", "Februari", "Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
        $nowDate = date("d");
        $nowMonth = date("m");
        $nowYear = date("Y");

        $now = date("d-m-Y");
        $pdf->Cell(10,20,'',0,1);
        $pdf->Cell(135);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(30,7,'Dicetak tanggal '.$nowDate.' '.$month_name[intval($nowMonth)-1].' '.$nowYear,0,1,'C');
        $pdf->Output('Laporan Pendapatan Bulan '.$tgl.'-'.$bulan[0].'.pdf','D');
        //.$param
    }
    function laporanPendapatanTahunan_get($param){
        // $this->load->helper('directory'); //load directory helper
        $dir = "controllers/PDF/"; // Your Path to folder
        // $map = directory_map($dir); /* This function reads the directory path specified in the first parameter and builds an array representation of it and all its contained files. */
        $pdf = new FPDF('p','mm','A4');
        // membuat halaman baru
        $pdf->AddPage();
    
        $i = 1;
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
        
        $layanan1 = array();
        $layanan2 = array();
        $layanan3 = array();
        $layanan4 = array();
        $layanan5 = array();
        $layanan6 = array();
        $layanan7 = array();
        $layanan8 = array();
        $layanan9 = array();
        $layanan10 = array();
        $layanan11 = array();
        $layanan12 = array();

        $totalP1 = '0';
        $totalP2 = '0';
        $totalP3 = '0';
        $totalP4 = '0';
        $totalP5 = '0';
        $totalP6 = '0';
        $totalP7 = '0';
        $totalP8 = '0';
        $totalP9 = '0';
        $totalP10 = '0';
        $totalP11 = '0';
        $totalP12 = '0';

        $totalL1 = '0';
        $totalL2 = '0';
        $totalL3 = '0';
        $totalL4 = '0';
        $totalL5 = '0';
        $totalL6 = '0';
        $totalL7 = '0';
        $totalL8 = '0';
        $totalL9 = '0';
        $totalL10 = '0';
        $totalL11 = '0';
        $totalL12 = '0';


        $sub1 = 0;
        $sub2 = 0;
        $sub3 = 0;
        $sub4 = 0;
        $sub5 = 0;
        $sub6 = 0;
        $sub7 = 0;
        $sub8 = 0;
        $sub9 = 0;
        $sub10 = 0;
        $sub11 = 0;
        $sub12 = 0;
        $total = 0;
        $produk = array();
        $layanan = array();
        $bulan = explode("-", $param);
        $dataProduk = "SELECT transaksi_produk.id_transaksi_produk , transaksi_produk.total, month(transaksi_produk.created_at) as 'bulan'  from transaksi_produk 
        WHERE year(transaksi_produk.created_at)=? AND transaksi_produk.status = 'Lunas'
        GROUP BY transaksi_produk.id_transaksi_produk";
        $hasilProduk = $this->db->query($dataProduk,[$bulan[0]])->result();
        // print_r($hasilProduk);

        $dataLayanan = "SELECT transaksi_layanan.id_transaksi_layanan , transaksi_layanan.total, month(transaksi_layanan.created_at) as 'bulan'  from transaksi_layanan
        WHERE year(transaksi_layanan.created_at)=? AND transaksi_layanan.status = 'Lunas'
        GROUP BY transaksi_layanan.id_transaksi_layanan";
        $hasilLayanan = $this->db->query($dataLayanan,[$bulan[0]])->result();
        

        if(isset($hasilProduk)){
            for($i = 0; $i<count($hasilProduk); $i++){
                if($hasilProduk[$i]->bulan == 1){
                    array_push($produk1,$hasilProduk[$i]->total);
                }elseif($hasilProduk[$i]->bulan == 2){
                    array_push($produk2,$hasilProduk[$i]->total);
                }elseif($hasilProduk[$i]->bulan == 3){
                    array_push($produk3,$hasilProduk[$i]->total);
                }elseif($hasilProduk[$i]->bulan == 4){
                    array_push($produk4,$hasilProduk[$i]->total);
                }elseif($hasilProduk[$i]->bulan == 5){
                    array_push($produk5,$hasilProduk[$i]->total);
                }elseif($hasilProduk[$i]->bulan == 6){
                    array_push($produk6,$hasilProduk[$i]->total);
                }elseif($hasilProduk[$i]->bulan == 7){
                    array_push($produk7,$hasilProduk[$i]->total);
                }elseif($hasilProduk[$i]->bulan == 8){
                    array_push($produk8,$hasilProduk[$i]->total);
                }elseif($hasilProduk[$i]->bulan == 9){
                    array_push($produk9,$hasilProduk[$i]->total);
                }elseif($hasilProduk[$i]->bulan == 10){
                    array_push($produk10,$hasilProduk[$i]->total);
                }elseif($hasilProduk[$i]->bulan == 11){
                    array_push($produk11,$hasilProduk[$i]->total);
                }else{
                    array_push($produk12,$hasilProduk[$i]->total);
                }
            }

            if(isset($produk1)){
                for($j=0 ; $j<count($produk1); $j++){
                    $totalP1 = $totalP1 + $produk1[$j];
                }
            }else{
                $totalP1 = 0;
            }
            if(isset($produk2)){
                for($j=0 ; $j<count($produk2); $j++){
                    $totalP2 = $totalP2 + $produk2[$j];
                }
            }else{
                $totalP2 = 0;
            }
            if(isset($produk3)){
                for($j=0 ; $j<count($produk3); $j++){
                    $totalP3 = $totalP3 + $produk3[$j];
                }
            }else{
                $totalP3 = 0;
            }
            if(isset($produk4)){
                for($j=0 ; $j<count($produk4); $j++){
                    $totalP4 = $totalP4 + $produk4[$j];
                }
            }else{
                $totalP4 = 0;
            }
            if(isset($produk5)){
                for($j=0 ; $j<count($produk5); $j++){
                    $totalP5 = $totalP5 + $produk5[$j];
                }
            }else{
                $totalP5 = 0;
            }
            if(isset($produk6)){
                for($j=0 ; $j<count($produk6); $j++){
                    $totalP6 = $totalP6 + $produk6[$j];
                }
            }else{
                $totalP6 = 0;
            }
            if(isset($produk7)){
                for($j=0 ; $j<count($produk7); $j++){
                    $totalP7 = $totalP7 + $produk7[$j];
                }
            }else{
                $totalP7 = 0;
            }
            if(isset($produk8)){
                for($j=0 ; $j<count($produk8); $j++){
                    $totalP8 = $totalP8 + $produk8[$j];
                }
            }else{
                $totalP8 = 0;
            }
            if(isset($produk9)){
                for($j=0 ; $j<count($produk9); $j++){
                    $totalP9 = $totalP9 + $produk9[$j];
                }
            }else{
                $totalP9 = 0;
            }
            if(isset($produk10)){
                for($j=0 ; $j<count($produk10); $j++){
                    $totalP10 = $totalP10 + $produk10[$j];
                }
            }else{
                $totalP10 = 0;
            }
            if(isset($produk11)){
                for($j=0 ; $j<count($produk11); $j++){
                    $totalP11 = $totalP11 + $produk11[$j];
                }
            }else{
                $totalP11 = 0;
            }
            if(isset($produk12)){
                for($j=0 ; $j<count($produk12); $j++){
                    $totalP12 = $totalP12 + $produk12[$j];
                }
            }else{
                $totalP12 = 0;
            }
        }

        if(isset($hasilLayanan)){
            for($i = 0; $i<count($hasilLayanan); $i++){
                if($hasilLayanan[$i]->bulan == 1){
                    array_push($layanan1,$hasilLayanan[$i]->total);
                }elseif($hasilLayanan[$i]->bulan == 2){
                    array_push($layanan2,$hasilLayanan[$i]->total);
                }elseif($hasilLayanan[$i]->bulan == 3){
                    array_push($layanan3,$hasilLayanan[$i]->total);
                }elseif($hasilLayanan[$i]->bulan == 4){
                    array_push($layanan4,$hasilLayanan[$i]->total);
                }elseif($hasilLayanan[$i]->bulan == 5){
                    array_push($layanan5,$hasilLayanan[$i]->total);
                }elseif($hasilLayanan[$i]->bulan == 6){
                    array_push($layanan6,$hasilLayanan[$i]->total);
                }elseif($hasilLayanan[$i]->bulan == 7){
                    array_push($layanan7,$hasilLayanan[$i]->total);
                }elseif($hasilLayanan[$i]->bulan == 8){
                    array_push($layanan8,$hasilLayanan[$i]->total);
                }elseif($hasilLayanan[$i]->bulan == 9){
                    array_push($layanan9,$hasilLayanan[$i]->total);
                }elseif($hasilLayanan[$i]->bulan == 10){
                    array_push($layanan10,$hasilLayanan[$i]->total);
                }elseif($hasilLayanan[$i]->bulan == 11){
                    array_push($layanan11,$hasilLayanan[$i]->total);
                }else{
                    array_push($layanan12,$hasilLayanan[$i]->total);
                }
            }

            if(isset($layanan1)){
                for($j=0 ; $j<count($layanan1); $j++){
                    $totalP1 = $totalL1 + $layanan1[$j];
                }
            }else{
                $totalL1 = 0;
            }
            if(isset($layanan2)){
                for($j=0 ; $j<count($layanan2); $j++){
                    $totalL2 = $totalL2 + $layanan2[$j];
                }
            }else{
                $totalL2 = 0;
            }
            if(isset($layanan3)){
                for($j=0 ; $j<count($layanan3); $j++){
                    $totalL3 = $totalL3 + $layanan3[$j];
                }
            }else{
                $totalL3 = 0;
            }
            if(isset($layanan4)){
                for($j=0 ; $j<count($layanan4); $j++){
                    $totalL4 = $totalL4 + $layanan4[$j];
                }
            }else{
                $totalL4 = 0;
            }
            if(isset($layanan5)){
                for($j=0 ; $j<count($layanan5); $j++){
                    $totalL5 = $totalL5 + $layanan5[$j];
                }
            }else{
                $totalL5 = 0;
            }
            if(isset($layanan6)){
                for($j=0 ; $j<count($layanan6); $j++){
                    $totalL6 = $totalL6 + $layanan6[$j];
                }
            }else{
                $totalL6 = 0;
            }
            if(isset($layanan7)){
                for($j=0 ; $j<count($layanan7); $j++){
                    $totalL7 = $totalL7 + $layanan7[$j];
                }
            }else{
                $totalL7 = 0;
            }
            if(isset($layanan8)){
                for($j=0 ; $j<count($layanan8); $j++){
                    $totalL8 = $totalL8 + $layanan8[$j];
                }
            }else{
                $totalL8 = 0;
            }
            if(isset($layanan9)){
                for($j=0 ; $j<count($layanan9); $j++){
                    $totalL9 = $totalL9 + $layanan9[$j];
                }
            }else{
                $totalL9 = 0;
            }
            if(isset($layanan10)){
                for($j=0 ; $j<count($layanan10); $j++){
                    $totalL10 = $totalL10 + $layanan10[$j];
                }
            }else{
                $totalL10 = 0;
            }
            if(isset($layanan11)){
                for($j=0 ; $j<count($layanan11); $j++){
                    $totalL11 = $totalL11 + $layanan11[$j];
                }
            }else{
                $totalL11 = 0;
            }
            if(isset($layanan12)){
                for($j=0 ; $j<count($layanan12); $j++){
                    $totalL12 = $totalL12 + $layanan12[$j];
                }
            }else{
                $totalL12 = 0;
            }
        }
        $sub1 = $totalP1 + $totalL1;
        $sub2 = $totalP2 + $totalL2;
        $sub3 = $totalP3 + $totalL3;
        $sub4 = $totalP4 + $totalL4;
        $sub5 = $totalP5 + $totalL5;
        $sub6 = $totalP6 + $totalL6;
        $sub7 = $totalP7 + $totalL7;
        $sub8 = $totalP8 + $totalL8;
        $sub9 = $totalP9 + $totalL9;
        $sub10 = $totalP10 + $totalL10;
        $sub11 = $totalP11 + $totalL11;
        $sub12 = $totalP12 + $totalL12;

        $total = $sub1 + $sub2 + $sub3 + $sub4 + $sub5 + $sub6 + $sub7 + $sub8 + $sub9 + $sub10 + $sub11 + $sub12;
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
        $pdf->Cell(50,7,'Laporan Pendapatan Terlaris',0,1,'C');
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(15,8,'Tahun',0,0);
        $pdf->Cell(15,8,': '.$tgl,0,1);

        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(180,7,'_________________________________________________________________',0,1,'C');

        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(10,6,'NO',1,0,'C');
        $pdf->Cell(40,6,'BULAN',1,0,'C');
        $pdf->Cell(43,6,'PENJUALAN PRODUK',1,0,'C');
        $pdf->Cell(43,6,'JASA LAYANAN',1,0,'C');
        $pdf->Cell(43,6,'TOTAL',1,1,'C');
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(10,10,'1',1,0,'C');
        $pdf->Cell(40,10,'Januari',1,0,'L');
        $pdf->Cell(43,10,'Rp '.number_format($totalP1,2),1,0,'L');
        $pdf->Cell(43,10,'Rp '.number_format($totalL1,2),1,0,'L');
        $pdf->Cell(43,10,'Rp '.number_format($sub1,2),1,1,'L');
        $pdf->Cell(10,10,'2',1,0,'C');
        $pdf->Cell(40,10,'Februari',1,0,'L');
        $pdf->Cell(43,10,'Rp '.number_format($totalP2,2),1,0,'L');
        $pdf->Cell(43,10,'Rp '.number_format($totalL2,2),1,0,'L');
        $pdf->Cell(43,10,'Rp '.number_format($sub2,2),1,1,'L');
        $pdf->Cell(10,10,'3',1,0,'C');
        $pdf->Cell(40,10,'Maret',1,0,'L');
        $pdf->Cell(43,10,'Rp '.number_format($totalP3,2),1,0,'L');
        $pdf->Cell(43,10,'Rp '.number_format($totalL3,2),1,0,'L');
        $pdf->Cell(43,10,'Rp '.number_format($sub3,2),1,1,'L');
        $pdf->Cell(10,10,'4',1,0,'C');
        $pdf->Cell(40,10,'April',1,0,'L');
        $pdf->Cell(43,10,'Rp '.number_format($totalP4,2),1,0,'L');
        $pdf->Cell(43,10,'Rp '.number_format($totalL4,2),1,0,'L');
        $pdf->Cell(43,10,'Rp '.number_format($sub4,2),1,1,'L');
        $pdf->Cell(10,10,'5',1,0,'C');
        $pdf->Cell(40,10,'Mei',1,0,'L');
        $pdf->Cell(43,10,'Rp '.number_format($totalP5,2),1,0,'L');
        $pdf->Cell(43,10,'Rp '.number_format($totalL5,2),1,0,'L');
        $pdf->Cell(43,10,'Rp '.number_format($sub5,2),1,1,'L');
        $pdf->Cell(10,10,'6',1,0,'C');
        $pdf->Cell(40,10,'Juni',1,0,'L');
        $pdf->Cell(43,10,'Rp '.number_format($totalP6,2),1,0,'L');
        $pdf->Cell(43,10,'Rp '.number_format($totalL6,2),1,0,'L');
        $pdf->Cell(43,10,'Rp '.number_format($sub6,2),1,1,'L');
        $pdf->Cell(10,10,'7',1,0,'C');
        $pdf->Cell(40,10,'Juli',1,0,'L');
        $pdf->Cell(43,10,'Rp '.number_format($totalP7,2),1,0,'L');
        $pdf->Cell(43,10,'Rp '.number_format($totalL7,2),1,0,'L');
        $pdf->Cell(43,10,'Rp '.number_format($sub7,2),1,1,'L');
        $pdf->Cell(10,10,'8',1,0,'C');
        $pdf->Cell(40,10,'Agustus',1,0,'L');
        $pdf->Cell(43,10,'Rp '.number_format($totalP8,2),1,0,'L');
        $pdf->Cell(43,10,'Rp '.number_format($totalL8,2),1,0,'L');
        $pdf->Cell(43,10,'Rp '.number_format($sub8,2),1,1,'L');
        $pdf->Cell(10,10,'9',1,0,'C');
        $pdf->Cell(40,10,'September',1,0,'L');
        $pdf->Cell(43,10,'Rp '.number_format($totalP9,2),1,0,'L');
        $pdf->Cell(43,10,'Rp '.number_format($totalL9,2),1,0,'L');
        $pdf->Cell(43,10,'Rp '.number_format($sub9,2),1,1,'L');
        $pdf->Cell(10,10,'10',1,0,'C');
        $pdf->Cell(40,10,'October',1,0,'L');
        $pdf->Cell(43,10,'Rp '.number_format($totalP10,2),1,0,'L');
        $pdf->Cell(43,10,'Rp '.number_format($totalL10,2),1,0,'L');
        $pdf->Cell(43,10,'Rp '.number_format($sub10,2),1,1,'L');
        $pdf->Cell(10,10,'11',1,0,'C');
        $pdf->Cell(40,10,'November',1,0,'L');
        $pdf->Cell(43,10,'Rp '.number_format($totalP11,2),1,0,'L');
        $pdf->Cell(43,10,'Rp '.number_format($totalL11,2),1,0,'L');
        $pdf->Cell(43,10,'Rp '.number_format($sub11,2),1,1,'L');
        $pdf->Cell(10,10,'12',1,0,'C');
        $pdf->Cell(40,10,'Desember',1,0,'L');
        $pdf->Cell(43,10,'Rp '.number_format($totalP12,2),1,0,'L');
        $pdf->Cell(43,10,'Rp '.number_format($totalL12,2),1,0,'L');
        $pdf->Cell(43,10,'Rp '.number_format($sub12,2),1,1,'L');

        $pdf->Cell(10,10,'',0,1);
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(65,10,'Total :Rp '.number_format($total,2),1,1);
        $now = date("d-m-Y");
        $pdf->Cell(10,20,'',0,1);
        $pdf->Cell(135);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(30,7,'Dicetak tanggal '.$nowDate.' '.$month_name[intval($nowMonth)-1].' '.$nowYear,0,1,'C');
        $pdf->Output('Laporan Produk Terlaris '.'-'.$bulan[0].'.pdf','I');
        //.$param
    }
    public function returnData($msg,$error){
        $response['error']=$error;
        $response['message']=$msg;
        return $this->response($response);
    }
}