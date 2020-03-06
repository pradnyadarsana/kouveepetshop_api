<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class HewanModel extends CI_Model
{
    private $table = 'hewan';

    public $id_hewan;
    public $id_pelanggan;
    public $id_jenis_hewan;
    public $nama;
    public $tanggal_lahir;
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
        $this->db->select('id_hewan, hewan.id_pelanggan, pelanggan.nama "nama_pelanggan", pelanggan.alamat "alamat_pelanggan", 
                        pelanggan.tanggal_lahir "tanggal_lahir_pelanggan", pelanggan.telp "telp_pelanggan",
                        hewan.id_jenis_hewan, jenis_hewan.nama "nama_jenis_hewan", hewan.nama "nama_hewan", hewan.tanggal_lahir "tanggal_lahir_hewan", 
                        hewan.created_at, hewan.created_by, hewan.modified_at, hewan.modified_by, hewan.delete_at, hewan.delete_by, hewan.aktif');
        $this->db->from('hewan');
        $this->db->join('pelanggan', 'hewan.id_pelanggan = pelanggan.id_pelanggan');
        $this->db->join('jenis_hewan', 'hewan.id_jenis_hewan = jenis_hewan.id_jenis_hewan');
        $this->db->where('hewan.aktif',1);
        $this->db->order_by('hewan.id_hewan ASC');
        return $this->db->get()->result();
    }

    public function store($request) { 
        $this->id_pelanggan = $request->id_pelanggan;
        $this->id_jenis_hewan = $request->id_jenis_hewan;
        $this->nama = $request->nama;
        $this->tanggal_lahir = $request->tanggal_lahir;
        $this->created_by = $request->created_by;
        $this->aktif=1;
        if($this->db->insert($this->table, $this)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function update($request, $id_hewan) { 
        $updateData = [
            'id_pelanggan' => $request->id_pelanggan,
            'id_jenis_hewan' => $request->id_jenis_hewan,
            'nama' => $request->nama,
            'tanggal_lahir' => $request->tanggal_lahir,
            'modified_by' => $request->modified_by,
            'modified_at' => date('Y-m-d H:i:s')
        ];
        if($this->db->where('id_hewan',$id_hewan)->update($this->table, $updateData)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function softDelete($request, $id_hewan){
        $updateData = [
            'aktif' => 0,
            'delete_by' => $request->delete_by,
            'delete_at' => date('Y-m-d H:i:s')
        ];
        if($this->db->where('id_hewan',$id_hewan)->update($this->table, $updateData)){
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