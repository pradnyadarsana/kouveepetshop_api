<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class LayananModel extends CI_Model
{
    private $table = 'layanan';

    public $id_layanan;
    public $nama;
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
        return $this->db->get_where('layanan', ["aktif" => 1])->result();
    } 

    public function store($request) { 
        $this->nama = $request->nama;
        $this->created_by = $request->created_by;
        $this->aktif=1;
        if($this->db->insert($this->table, $this)){
            return ['msg'=>$this->db->insert_id(),'error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function update($request, $id_layanan) { 
        $updateData = [
            'nama' => $request->nama, 
            'modified_by' => $request->modified_by,
            'modified_at' => date('Y-m-d H:i:s')
        ];
        if($this->db->where('id_layanan',$id_layanan)->update($this->table, $updateData)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function softDelete($request, $id_layanan){
        $updateData = [
            'aktif' => 0,
            'delete_by' => $request->delete_by,
            'delete_at' => date('Y-m-d H:i:s')
        ];
        $this->db->trans_start();
        $this->db->where('id_layanan',$id_layanan)->update($this->table, $updateData);
        $this->db->where('id_layanan',$id_layanan)->update('harga_layanan', $updateData);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            # Something went wrong.
            $this->db->trans_rollback();
            return ['msg'=>'Gagal','error'=>true];
        } 
        else {
            # Everything is Perfect. 
            # Committing data to the database.
            $this->db->trans_commit();
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