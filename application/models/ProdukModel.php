<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class ProdukModel extends CI_Model
{
    private $table = 'produk';

    public $id_produk;
    public $nama;
    public $satuan;
    public $jumlah_stok;
    public $harga;
    public $min_stok;
    public $gambar;
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
        return $this->db->get_where('produk', ["aktif" => 1])->result();
    }

    public function store($request) { 
        $this->nama = $request->nama;
        $this->satuan = $request->satuan;
        $this->jumlah_stok = $request->jumlah_stok;
        $this->harga = $request->harga;
        $this->min_stok = $request->min_stok;
        $this->gambar = $this->uploadImage();
        $this->created_by = $request->created_by;
        $this->aktif=1;
        if($this->db->insert($this->table, $this)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function update($request, $id_produk) { 
        $this->nama = $request->nama;
        if (!empty($_FILES["gambar"])) {
            $image = $this->uploadImage();
        } else {
            $old_data = $this->db->get_where('produk', ["id_produk" => $id_produk])->row();
            $image = $old_data->gambar;
        }
        $updateData = [
            'nama' => $request->nama,
            'satuan' => $request->satuan,
            'jumlah_stok' => $request->jumlah_stok,
            'harga' => $request->harga,
            'min_stok' => $request->min_stok,
            'gambar' => $image,
            'modified_by' => $request->modified_by,
            'modified_at' => date('Y-m-d H:i:s')
        ];
        if($this->db->where('id_produk',$id_produk)->update($this->table, $updateData)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function softDelete($request, $id_produk){
        $updateData = [
            'aktif' => 0,
            'delete_by' => $request->delete_by,
            'delete_at' => date('Y-m-d H:i:s')
        ];
        if($this->db->where('id_produk',$id_produk)->update($this->table, $updateData)){
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

    private function uploadImage()
    {
        $config['upload_path']          = './upload/produk/';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['file_name']            = $this->nama;
        $config['overwrite']			= true;
        $config['max_size']             = 4096; // 4MB
        // $config['max_width']            = 1024;
        // $config['max_height']           = 768;

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('gambar')) {
            return $this->upload->data("file_name");
        }
        
        return "default.jpg";
    }
}
?>