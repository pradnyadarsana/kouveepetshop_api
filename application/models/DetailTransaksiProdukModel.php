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
            $this->kurangStokProduk($this->id_produk, $this->jumlah);
            $this->updateTotal($request->id_transaksi_produk);
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function storeMultiple($request) {
        $jsondata = json_decode($request);
        $dataset = array();
        $id_transaksi_produk = 0;
        foreach($jsondata as $data){
            $id_transaksi_produk = $data->id_transaksi_produk;
            $dataset[] = 
                array(
                    'id_transaksi_produk' => $data->id_transaksi_produk,
                    'id_produk' => $data->id_produk,
                    'jumlah' => $data->jumlah,
                    'total_harga' => $data->total_harga,
                    'created_by' => $data->created_by,
                );
        }
        //echo count($dataset);
        if($this->db->insert_batch($this->table, $dataset)){
            foreach($jsondata as $data){
                $this->kurangStokProduk($data->id_produk, $data->jumlah);
            }
            $this->updateTotal($id_transaksi_produk);
            return ['msg'=>'Berhasil','error'=>false];
        }
        //$this->db->delete('transaksi_produk', array('id_transaksi_produk' => $id_transaksi_produk));
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
        $new_sum_change = $request->jumlah-$data->jumlah;
        if($this->db->where('id_detail_transaksi_produk',$id_detail_transaksi_produk)->update($this->table, $updateData)){
            $this->kurangStokProduk($data->id_produk, $new_sum_change);
            $this->updateTotal($data->id_transaksi_produk);
            return ['msg'=>'Berhasil','error'=>false];
        }
        $this->updateTotal($data->id_transaksi_produk);
        return ['msg'=>'Gagal','error'=>true];
    }

    public function updateMultiple($request) {
        $jsondata = json_decode($request);
        $id_transaksi_produk = 0;
        $this->db->trans_start();
        foreach($jsondata as $data){
            $id_detail_transaksi_produk = $data->id_detail_transaksi_produk;
            $id_transaksi_produk = $data->id_transaksi_produk;
            $updateData = [
                'id_produk' => $data->id_produk,
                'jumlah' => $data->jumlah,
                'total_harga' => $data->total_harga,
                'modified_at' => date('Y-m-d H:i:s'),
                'modified_by' => $data->modified_by
            ];
            $data_before = $this->db->get_where($this->table, array('id_detail_transaksi_produk' => $id_detail_transaksi_produk))->row();
            $new_sum_change = $data->jumlah-$data_before->jumlah;
            $this->db->where('id_detail_transaksi_produk',$id_detail_transaksi_produk)->update($this->table, $updateData);
            $this->kurangStokProduk($data->id_produk, $new_sum_change);
        }
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            # Something went wrong.
            $this->db->trans_rollback();
            $this->updateTotal($id_transaksi_produk);
            return ['msg'=>'Gagal','error'=>true];
        } 
        else {
            # Everything is Perfect. 
            # Committing data to the database.
            $this->db->trans_commit();
            $this->updateTotal($id_transaksi_produk);
            return ['msg'=>'Berhasil','error'=>false];
        }
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
        $this->db->where('id_transaksi_produk',$id_transaksi_produk)->update('transaksi_produk', $updateData);
    }
    
    public function destroy($id){
        if (empty($this->db->select('*')->where(array('id_detail_transaksi_produk' => $id))->get($this->table)->row())) 
            return ['msg'=>'Id tidak ditemukan','error'=>true];
        
        $data = $this->db->get_where($this->table, array('id_detail_transaksi_produk' => $id))->row();
        if($data!=null && $data->id_detail_transaksi_produk==$id){
            if($this->db->delete($this->table, array('id_detail_transaksi_produk' => $id))){
                $this->tambahStokProduk($data->id_produk, $data->jumlah);
                $this->updateTotal($data->id_transaksi_produk);
                return ['msg'=>'Berhasil','error'=>false];
            }
            $this->updateTotal($data->id_transaksi_produk);
            return ['msg'=>'Gagal','error'=>true];
        }
        $this->updateTotal($data->id_transaksi_produk);
        return ['msg'=>'Id tidak ditemukan','error'=>true];
    }
    
    public function deleteMultiple($request){
        $jsondata = json_decode($request);

        $data_transaksi = $this->db->get_where($this->table, array('id_detail_transaksi_produk' => $jsondata[0]))->row();
        $id_transaksi_produk = $data_transaksi->id_transaksi_produk;

        $this->db->select('*');
        $this->db->from('detail_transaksi_produk');
        $this->db->where_in('id_detail_transaksi_produk', $jsondata);
        $result = $this->db->get()->result();
        if($this->db->where_in('id_detail_transaksi_produk', $jsondata)->delete($this->table)){
            //add jumlah produk
            foreach($result as $trans){
                $this->tambahStokProduk($trans->id_produk, $trans->jumlah);
            }
            $this->updateTotal($id_transaksi_produk);
            return ['msg'=>'Berhasil','error'=>false];
        } 
        $this->updateTotal($id_transaksi_produk);
        return ['msg'=>'Gagal','error'=>true];
    }
    
    public function kurangStokProduk($id_produk, $qty){
        $data = $this->db->get_where('produk', array('id_produk' => $id_produk))->row();
        $new_sum = $data->jumlah_stok-$qty;
        $updateData = [
            'jumlah_stok' => $new_sum
        ];
        $result = $this->db->where('id_produk',$data->id_produk)->update('produk', $updateData);
        if($result){
            $this->manageNotifikasi($id_produk);
        }
    }

    public function tambahStokProduk($id_produk, $qty){
        $data = $this->db->get_where('produk', array('id_produk' => $id_produk))->row();
        $new_sum = $data->jumlah_stok+$qty;
        $updateData = [
            'jumlah_stok' => $new_sum
        ];
        $result = $this->db->where('id_produk',$data->id_produk)->update('produk', $updateData);
        if($result){
            $this->manageNotifikasi($id_produk);
        }
    }

    public function manageNotifikasi($id_produk){
        $result = $this->db->query('select * from produk where id_produk='.$id_produk.' and jumlah_stok < min_stok ');
        $notifResult = $this->db->get_where('notifikasi', ["id_produk" => $id_produk]);
        if($result->num_rows()!=0){
            if($notifResult->num_rows()==0){
                $this->db->insert('notifikasi', ["id_produk"=>$id_produk]);
            }
        }else{
            if($notifResult->num_rows()!=0){
                $this->db->delete('notifikasi', ["id_produk"=>$id_produk]);
            }
        }
    }
}
?>