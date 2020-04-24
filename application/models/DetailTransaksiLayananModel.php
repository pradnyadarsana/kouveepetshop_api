<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class DetailTransaksiLayananModel extends CI_Model
{
    private $table = 'detail_transaksi_layanan';

    public $id_detail_transaksi_layanan;
    public $id_transaksi_layanan;
    public $id_harga_layanan;
    public $jumlah;
    public $total_harga;
    public $created_at;
    public $created_by;
    public $modified_at;
    public $modified_by;

    public $rule = [];

    public function Rules() { return $this->rule; }
   
    public function getAllAktif() {
        return $this->db->get('detail_transaksi_layanan')->result();
    } 

    public function store($request) {
        $this->id_transaksi_layanan = $request->id_transaksi_layanan;
        $this->id_harga_layanan = $request->id_harga_layanan;
        $this->jumlah = $request->jumlah;
        $this->total_harga = $request->total_harga;
        $this->created_by = $request->created_by;

        if($this->db->insert($this->table, $this)){
            if($this->groomingCheck($request->id_harga_layanan)){
                $debug = 'grooming';
                $this->setProgress($request->id_transaksi_layanan, 'Sedang Diproses');
            }
            $this->updateTotal($request->id_transaksi_layanan);
            return ['msg'=>'Berhasil ','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function storeMultiple($request) {
        $groomStat = false;
        $jsondata = json_decode($request);
        $dataset = array();
        foreach($jsondata as $data){
            $dataset[] = 
                array(
                    'id_transaksi_layanan' => $data->id_transaksi_layanan,
                    'id_harga_layanan' => $data->id_harga_layanan,
                    'jumlah' => $data->jumlah,
                    'total_harga' => $data->total_harga,
                    'created_by' => $data->created_by,
                );
            if($this->groomingCheck($data->id_harga_layanan)){
                $groomStat = true;
            }
        }
        //echo count($dataset);
        if($this->db->insert_batch($this->table, $dataset)){
            if($groomStat){
                $this->setProgress($dataset[0]["id_transaksi_layanan"], 'Sedang Diproses');
            }
            $this->updateTotal($dataset[0]["id_transaksi_layanan"]);
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function update($request, $id_detail_transaksi_layanan) {
        $updateData = [
            'id_harga_layanan' => $request->id_harga_layanan,
            'jumlah' => $request->jumlah,
            'total_harga' => $request->total_harga,
            'modified_at' => date('Y-m-d H:i:s'),
            'modified_by' => $request->modified_by
        ];

        $data = $this->db->get_where($this->table, array('id_detail_transaksi_layanan' => $id_detail_transaksi_layanan))->row();
        $id_transaksi_layanan = $data->id_transaksi_layanan;
        $groomState = $this->groomingCheck($request->id_harga_layanan);

        if($this->db->where('id_detail_transaksi_layanan',$id_detail_transaksi_layanan)->update($this->table, $updateData)){

            if($groomState){
                $this->setProgress($data->id_transaksi_layanan, 'Sedang Diproses');
            }else{
                $transdata = $this->db->get_where('detail_transaksi_layanan', ['id_transaksi_layanan'=>$id_transaksi_layanan])->result();
                $setProgress = true;
                foreach ($transdata as $temp) {
                    if($this->groomingCheck($temp->id_harga_layanan)){
                        $setProgress = false;
                    }
                }
                if($setProgress){
                    $this->setProgress($id_transaksi_layanan, 'Layanan Selesai');
                }
            }
            
            $this->updateTotal($data->id_transaksi_layanan);
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function updateMultiple($request) {
        $groomStat = false;
        $jsondata = json_decode($request);
        //$id_transaksi_produk = 0;
        $this->db->trans_start();
        foreach($jsondata as $data){
            $id_detail_transaksi_layanan = $data->id_detail_transaksi_layanan;
            $id_transaksi_layanan = $data->id_transaksi_layanan;
            $updateData = [
                'id_harga_layanan' => $data->id_harga_layanan,
                'jumlah' => $data->jumlah,
                'total_harga' => $data->total_harga,
                'modified_at' => date('Y-m-d H:i:s'),
                'modified_by' => $data->modified_by
            ];
            if($this->groomingCheck($data->id_harga_layanan)){
                $groomStat = true;
            }
            $this->db->where('id_detail_transaksi_layanan',$id_detail_transaksi_layanan)->update($this->table, $updateData);
        }
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            # Something went wrong.
            $this->db->trans_rollback();
            $this->updateTotal($id_transaksi_layanan);
            return ['msg'=>'Gagal','error'=>true];
        } 
        else {
            # Everything is Perfect. 
            # Committing data to the database.
            $this->db->trans_commit();

            if($groomStat){
                $this->setProgress($jsondata[0]->id_transaksi_layanan, 'Sedang Diproses');
            }
            $this->updateTotal($id_transaksi_layanan);
            return ['msg'=>'Berhasil','error'=>false];
        }
    }
    
    public function destroy($id){
        if (empty($this->db->select('*')->where(array('id_detail_transaksi_layanan' => $id))->get($this->table)->row())) 
            return ['msg'=>'Id tidak ditemukan','error'=>true];
        
        $data = $this->db->get_where($this->table, array('id_detail_transaksi_layanan' => $id))->row();
        $id_transaksi_layanan = $data->id_transaksi_layanan;
        $groomState = $this->groomingCheck($data->id_harga_layanan);

        if($data!=null && $data->id_detail_transaksi_layanan==$id){
            if($this->db->delete($this->table, array('id_detail_transaksi_layanan' => $id))){
                
                if($groomState){
                    $transdata = $this->db->get_where('detail_transaksi_layanan', ['id_transaksi_layanan'=>$id_transaksi_layanan])->result();
                    $setProgress = true;
                    foreach ($transdata as $temp) {
                        if($this->groomingCheck($temp->id_harga_layanan)){
                            $setProgress = false;
                        }
                    }
                    if($setProgress){
                        $this->setProgress($id_transaksi_layanan, 'Layanan Selesai');
                    }
                }
                
                $this->updateTotal($data->id_transaksi_layanan);
                return ['msg'=>'Berhasil','error'=>false];
            }
            return ['msg'=>'Gagal','error'=>true];
        }
        return ['msg'=>'Id tidak ditemukan','error'=>true];
    }

    public function deleteMultiple($request){
        $jsondata = json_decode($request);

        $setProgress = true;
        
        $id_transaksi_layanan = 0;

        $this->db->trans_start();

        foreach($jsondata as $id){
            $data = $this->db->get_where($this->table, array('id_detail_transaksi_layanan' => $id))->row();
            $id_transaksi_layanan = $data->id_transaksi_layanan;
            //$groomState = $this->groomingCheck($data->id_harga_layanan);

            if($data!=null && $data->id_detail_transaksi_layanan==$id){
                if($this->db->delete($this->table, array('id_detail_transaksi_layanan' => $id))){
                }
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            # Something went wrong.
            $this->db->trans_rollback();
            $this->updateTotal($id_transaksi_layanan);
            return ['msg'=>'Gagal','error'=>true];
        } 
        else {
            # Everything is Perfect. 
            # Committing data to the database.
            $this->db->trans_commit();

            //if($groomState){
            $transdata = $this->db->get_where('detail_transaksi_layanan', ['id_transaksi_layanan'=>$id_transaksi_layanan])->result();
            
            foreach ($transdata as $temp) {
                if($this->groomingCheck($temp->id_harga_layanan)){
                    $setProgress = false;
                }
            }
            //}
            if($setProgress){
                $this->setProgress($id_transaksi_layanan, 'Layanan Selesai');
            }
            $this->updateTotal($id_transaksi_layanan);
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
    
    public function updateTotal($id_transaksi_layanan) {
        $transdata = $this->db->get_where('transaksi_layanan', ['id_transaksi_layanan'=>$id_transaksi_layanan])->row();
        $this->db->select_sum('total_harga');
        $this->db->where('id_transaksi_layanan', $id_transaksi_layanan);
        $pricedata = $this->db->get('detail_transaksi_layanan')->row();
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
        $this->db->where('id_transaksi_layanan',$id_transaksi_layanan)->update('transaksi_layanan', $updateData);
    }

    public function groomingCheck($id_harga_layanan){
        $this->db->select('id_harga_layanan, harga_layanan.id_layanan, layanan.nama "nama_layanan"');
        $this->db->from('harga_layanan');
        $this->db->join('layanan', 'harga_layanan.id_layanan = layanan.id_layanan');
        $this->db->where('harga_layanan.id_harga_layanan',$id_harga_layanan);
        $data = $this->db->get()->row();
        if($data!=null){
            if(strpos(strtolower($data->nama_layanan),'grooming') !== false){
                return true;
            }else{
                return false;
            }   
        }
    }

    public function setProgress($id_transaksi_layanan, $progress) {
        $updateData = [
            'progress' => $progress
        ];
        $data = $this->db->get_where('transaksi_layanan',['id_transaksi_layanan'=>$id_transaksi_layanan, 'status'=> 'Menunggu Pembayaran'])->row();
        if($data!=null){
            $this->db->where(['id_transaksi_layanan'=>$id_transaksi_layanan, 'status'=> 'Menunggu Pembayaran'])->update('transaksi_layanan', $updateData);
        }
    }
}
?>