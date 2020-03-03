<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UkuranHewanModel extends CI_Model
{
    private $table = 'ukuran_hewan';

    public $id_ukuran_hewan;
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
        return $this->db->get_where('ukuran_hewan', ["aktif" => 1])->result();
    } 

    public function store($request) { 
        $this->nama = $request->nama;
        $this->created_by = $request->created_by;
        $this->aktif=1;
        if($this->db->insert($this->table, $this)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function update($request, $id_ukuran_hewan) { 
        $updateData = [
            'nama' => $request->nama, 
            'modified_by' => $request->modified_by,
            'modified_at' => date('Y-m-d H:i:s')
        ];
        if($this->db->where('id_ukuran_hewan',$id_ukuran_hewan)->update($this->table, $updateData)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function softDelete($request, $id_ukuran_hewan){
        $updateData = [
            'aktif' => 0,
            'delete_by' => $request->delete_by,
            'delete_at' => date('Y-m-d H:i:s')
        ];
        if($this->db->where('id_ukuran_hewan',$id_ukuran_hewan)->update($this->table, $updateData)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
    
    // public function destroy($id){
    //     if (empty($this->db->select('*')->where(array('id' => $id))->get($this->table)->row())) return ['msg'=>'Id tidak ditemukan','error'=>true];
        
    //     if($this->db->delete($this->table, array('id' => $id))){
    //         return ['msg'=>'Berhasil','error'=>false];
    //     }
    //     return ['msg'=>'Gagal','error'=>true];
    // }
}
?>