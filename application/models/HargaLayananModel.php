<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class HargaLayananModel extends CI_Model
{
    private $table = 'harga_layanan';

    public $id_harga_layanan;
    public $id_layanan;
    public $id_ukuran_hewan;
    public $harga;
    public $created_at;
    public $created_by;
    public $modified_at;
    public $modified_by;
    public $delete_at;
    public $delete_by;
    public $aktif;

    public $rule = [];

    public function Rules() { return $this->rule; }
   
    public function getAllAktif() {
        //return $this->db->get_where('harga_layanan', ["aktif" => 1])->result();
        $this->db->select('id_harga_layanan, harga_layanan.id_layanan, layanan.nama "nama_layanan", harga_layanan.id_ukuran_hewan, 
                        ukuran_hewan.nama "nama_ukuran_hewan", harga_layanan.harga,
                        harga_layanan.created_at, harga_layanan.created_by, harga_layanan.modified_at, harga_layanan.modified_by,
                        harga_layanan.delete_at, harga_layanan.delete_by, harga_layanan.aktif');
        $this->db->from('harga_layanan');
        $this->db->join('layanan', 'harga_layanan.id_layanan = layanan.id_layanan');
        $this->db->join('ukuran_hewan', 'harga_layanan.id_ukuran_hewan = ukuran_hewan.id_ukuran_hewan');
        $this->db->where('harga_layanan.aktif',1);
        $this->db->order_by('harga_layanan.id_harga_layanan ASC');
        return $this->db->get()->result();
    }

    public function store($request) { 
        $this->id_layanan = $request->id_layanan;
        $this->id_ukuran_hewan = $request->id_ukuran_hewan;
        $this->harga = $request->harga;
        $this->created_by = $request->created_by;
        $this->aktif=1;
        if($this->db->insert($this->table, $this)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function storeMultiple($request) {
        $jsondata = json_decode($request);
        $dataset = array();
        foreach($jsondata as $data){
            $dataset[] =  
                array(
                    'id_layanan' => $data->id_layanan,
                    'id_ukuran_hewan' => $data->id_ukuran_hewan,
                    'harga' => $data->harga,
                    'created_by' => $data->created_by,
                    'aktif' => 1,
                );
        }
        //echo count($dataset);
        $query = $this->db->insert_batch($this->table, $dataset);
        if($query){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function update($request, $id_harga_layanan) { 
        $updateData = [
            'id_layanan' => $request->id_layanan,
            'id_ukuran_hewan' => $request->id_ukuran_hewan,
            'harga' => $request->harga, 
            'modified_by' => $request->modified_by,
            'modified_at' => date('Y-m-d H:i:s')
        ];
        if($this->db->where('id_harga_layanan',$id_harga_layanan)->update($this->table, $updateData)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function softDelete($request, $id_harga_layanan){
        $updateData = [
            'aktif' => 0,
            'delete_by' => $request->delete_by,
            'delete_at' => date('Y-m-d H:i:s')
        ];
        if($this->db->where('id_harga_layanan',$id_harga_layanan)->update($this->table, $updateData)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
    
    public function destroy($id){
        if (empty($this->db->select('*')->where(array('id_layanan' => $id))->get($this->table)->row())) return ['msg'=>'Id tidak ditemukan','error'=>true];
        
        if($this->db->delete($this->table, array('id_layanan' => $id))){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
}
?>