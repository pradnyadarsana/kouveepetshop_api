<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class PegawaiModel extends CI_Model
{
    private $table = 'pegawai';

    public $id_pegawai;
    public $nama;
    public $alamat;
    public $tanggal_lahir;
    public $telp;
    public $username;
    public $password;
    public $role;
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
        return $this->db->get_where('pegawai', ["aktif" => 1])->result();
    } 

    public function store($request) { 
        $this->nama = $request->nama;
        $this->alamat = $request->alamat;
        $this->tanggal_lahir = $request->tanggal_lahir;
        $this->telp = $request->telp;
        $this->username = $request->username;
        $this->password = password_hash($request->password, PASSWORD_BCRYPT);
        $this->role = $request->role;
        $this->created_by = $request->created_by;
        $this->aktif=1;
        if($this->db->insert($this->table, $this)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function update($request, $id_pegawai) { 
        $updateData = [
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'tanggal_lahir' => $request->tanggal_lahir,
            'telp' => $request->telp,
            'username' => $request->username,
            'role' => $request->role,
            'modified_by' => $request->modified_by,
            'modified_at' => date('Y-m-d H:i:s')
        ];
        if($this->db->where('id_pegawai',$id_pegawai)->update($this->table, $updateData)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function softDelete($request, $id_pegawai){
        $updateData = [
            'aktif' => 0,
            'delete_by' => $request->delete_by,
            'delete_at' => date('Y-m-d H:i:s')
        ];
        if($this->db->where('id_pegawai',$id_pegawai)->update($this->table, $updateData)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function change_password($request, $id_pegawai){
        $updateData = [
            'password' => password_hash($request->password, PASSWORD_BCRYPT),
            'modified_by' => $request->modified_by,
            'modified_at' => date('Y-m-d H:i:s')
        ];
        if($this->db->where('id_pegawai',$id_pegawai)->update($this->table, $updateData)){
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

    public function verify($request){
        $pegawai = $this->db->get_where('pegawai', ["username" => $request->username])->row();
        if(!empty($pegawai) && password_verify($request->password, $pegawai->password)){
            return ['msg'=>$pegawai,'error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
}
?>