<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class PelangganModel extends CI_Model
{
    private $table = 'pelanggan';

    public $id_pelanggan;
    public $nama;
    public $alamat;
    public $tanggal_lahir;
    public $telp;
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
        return $this->db->get_where('pelanggan', ["aktif" => 1])->result();
    } 

    public function store($request) { 
        $this->nama = $request->nama;
        $this->alamat = $request->alamat;
        $this->tanggal_lahir = $request->tanggal_lahir;
        $this->telp = $request->telp;
        $this->created_by = $request->created_by;
        $this->aktif=1;
        if($this->db->insert($this->table, $this)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function update($request, $id_pelanggan) { 
        $updateData = [
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'tanggal_lahir' => $request->tanggal_lahir,
            'telp' => $request->telp,
            'modified_by' => $request->modified_by,
            'modified_at' => date('Y-m-d H:i:s')
        ];
        if($this->db->where('id_pelanggan',$id_pelanggan)->update($this->table, $updateData)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function softDelete($request, $id_pelanggan){
        $updateData = [
            'aktif' => 0,
            'delete_by' => $request->delete_by,
            'delete_at' => date('Y-m-d H:i:s')
        ];
        if($this->db->where('id_pelanggan',$id_pelanggan)->update($this->table, $updateData)){
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