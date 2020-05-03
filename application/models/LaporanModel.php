<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class LaporanModel extends CI_Model
{
    public function PendapatanBulananProduk() {
        // $this->db->select('produk.nama "nama_produk"');
        // $this->db->select_sum('detail_transaksi_produk.total_harga', 'harga');
        // $this->db->from('detail_transaksi_produk');
        // $this->db->join('produk','detail_transaksi_produk.id_produk=produk.id_produk');
        // $this->db->where('month(detail_transaksi_produk.created_at)','month(sysdate())-2');
        // $this->db->group_by('produk.nama');
        //return $this->db->get()->result();

        return $this->db->query('select B.nama "nama_produk", sum(A.total_harga) "harga" from detail_transaksi_produk A
        inner join produk B on A.id_produk=B.id_produk where month(A.created_at)=month(sysdate())-1
        group by B.nama')->result();
    }

    public function PendapatanBulananLayanan(){
        return $this->db->query('select concat(C.nama," ", D.nama) "nama_layanan", sum(A.total_harga) "harga" from detail_transaksi_layanan A
        join harga_layanan B on A.id_harga_layanan=B.id_harga_layanan
        join layanan C on B.id_layanan=.C.id_layanan
        join ukuran_hewan D on B.id_ukuran_hewan=D.id_ukuran_hewan
        where month(A.created_at)=month(sysdate())-1
        group by B.id_harga_layanan')->result();
    }
}
?>