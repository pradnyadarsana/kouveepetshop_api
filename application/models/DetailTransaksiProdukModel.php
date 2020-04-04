<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class DetailTransaksiProdukModel extends CI_Model
{
    private $table = 'detail_transaksi_produk';

    public $id_detail_transaksi_produk;
    public $id_transaksi_produk;
    public $id_produk;
    public $jumlah;
    public $total_harga;
    public $created_at;
    public $created_by;
    public $modified_at;
    public $modified_by;

    public $rule = [];

    public function Rules() { return $this->rule; }
   
    public function getAllAktif() {
        return $this->db->get('detail_transaksi_produk')->result();
    } 

    public function store($request) {
        $this->id_transaksi_produk = $request->id_transaksi_produk;
        $this->id_produk = $request->id_produk;
        $this->jumlah = $request->jumlah;
        $this->total_harga = $request->total_harga;
        $this->created_by = $request->created_by;
        if($this->db->insert($this->table, $this)){
            $temp = $this->updateTotal($request->id_transaksi_produk);
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function storeMultiple($request) {
        $jsondata = json_decode($request);
        $dataset = [];
        foreach($jsondata as $data){
            array_push($dataset, 
                [
                    'id_transaksi_produk' => $data->id_transaksi_produk,
                    'id_produk' => $data->id_produk,
                    'jumlah' => $data->jumlah,
                    'total_harga' => $data->total_harga,
                    'created_by' => $data->created_by,
                ]
            );
        }
        //echo count($dataset);
        if($this->db->insert_batch($this->table, $dataset)){
            $temp = updateTotal($data->id_transaksi_produk);
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function update($request, $id_detail_transaksi_produk) {
        $updateData = [
            'id_produk' => $request->id_produk,
            'jumlah' => $request->jumlah,
            'total_harga' => $request->total_harga,
            'modified_at' => date('Y-m-d H:i:s'),
            'modified_by' => $request->modified_by
        ];
        $data = $this->db->get_where($this->table, array('id_detail_transaksi_produk' => $id_detail_transaksi_produk))->row();
        if($this->db->where('id_detail_transaksi_produk',$id_detail_transaksi_produk)->update($this->table, $updateData)){
            $temp = $this->updateTotal($data->id_transaksi_produk);
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function updateTotal($id_transaksi_produk) {
        $transdata = $this->db->get_where('transaksi_produk', ['id_transaksi_produk'=>$id_transaksi_produk])->row();
        $this->db->select_sum('total_harga');
        $this->db->where('id_transaksi_produk', $id_transaksi_produk);
        $pricedata = $this->db->get('detail_transaksi_produk')->row();
        if($pricedata->total_harga==null || $pricedata->total_harga<=$transdata->diskon)
        {
            $updateData = [
                'subtotal' => $pricedata->total_harga, 
                'total' => 0
            ];
            if($pricedata->total_harga==null){
                $updateData['subtotal'] = 0;
            }
        }else{
            if($transdata->diskon==null){
                $updateData = [
                    'subtotal' => $pricedata->total_harga, 
                    'total' => $pricedata->total_harga
                ];
            }else{
                $updateData = [
                    'subtotal' => $pricedata->total_harga, 
                    'total' => $pricedata->total_harga-$transdata->diskon
                ];
            }
             
        }
        
        if($this->db->where('id_transaksi_produk',$id_transaksi_produk)->update('transaksi_produk', $updateData)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
    
    public function destroy($id){
        if (empty($this->db->select('*')->where(array('id_detail_transaksi_produk' => $id))->get($this->table)->row())) 
            return ['msg'=>'Id tidak ditemukan','error'=>true];
        
        $data = $this->db->get_where($this->table, array('id_detail_transaksi_produk' => $id))->row();
        if($data!=null && $data->id_detail_transaksi_produk==$id){
            if($this->db->delete($this->table, array('id_detail_transaksi_produk' => $id))){
                $temp = $this->updateTotal($data->id_transaksi_produk);
                return ['msg'=>'Berhasil','error'=>false];
            }
            return ['msg'=>'Gagal','error'=>true];
        }
        return ['msg'=>'Id tidak ditemukan','error'=>true];
    }
}
?>