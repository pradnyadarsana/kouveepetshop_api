<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class PengadaanProdukModel extends CI_Model
{
    private $table = 'pengadaan_produk';

    public $id_pengadaan_produk;
    public $id_supplier;
    public $total;
    public $status;
    public $created_at;
    public $created_by;
    public $modified_at;
    public $modified_by;

    public $rule = [];

    public function Rules() { return $this->rule; }
   
    public function getAllAktif() {
        return $this->db->get('pengadaan_produk')->result();
    } 

    public function store($request) { 
        $date_now = date('Y-m-d');
        $this->db->select_max('id_pengadaan_produk');
        $this->db->like('id_pengadaan_produk', 'PO-'.$date_now, 'after');
        $query = $this->db->get('pengadaan_produk');
        $lastdata = $query->row();
        $last_id = $lastdata->id_pengadaan_produk;
        $last_count = substr($last_id, 14, 2);
        $next_count = $last_count+1;
        $next_id = 'PO-'.$date_now.'-'.sprintf('%02s', $next_count);

        $this->id_pengadaan_produk = $next_id;
        $this->id_supplier = $request->id_supplier;
        $this->total = $request->total;
        $this->status = 'Menunggu Konfirmasi';
        $this->created_by = $request->created_by;
        if($this->db->insert($this->table, $this)){
            //$temp = $this->updateTotal($next_id, $request->diskon);
            return ['msg'=>$next_id,'error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function storeReturnObject($request) { 
        $date_now = date('Y-m-d');
        $this->db->select_max('id_pengadaan_produk');
        $this->db->like('id_pengadaan_produk', 'PO-'.$date_now, 'after');
        $query = $this->db->get('pengadaan_produk');
        $lastdata = $query->row();
        $last_id = $lastdata->id_pengadaan_produk;
        $last_count = substr($last_id, 14, 2);
        $next_count = $last_count+1;
        $next_id = 'PO-'.$date_now.'-'.sprintf('%02s', $next_count);

        $this->id_pengadaan_produk = $next_id;
        $this->id_supplier = $request->id_supplier;
        $this->total = $request->total;
        $this->status = 'Menunggu Konfirmasi';
        $this->created_by = $request->created_by;
        if($this->db->insert($this->table, $this)){
            //$temp = $this->updateTotal($next_id, $request->diskon);
            $obj = $this->db->get_where('pengadaan_produk', ["id_pengadaan_produk" => $next_id])->row();
            return ['msg'=>$obj,'error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function update($request, $id_pengadaan_produk) {
        $updateData = [
            'id_supplier' => $request->id_supplier,
            'total' => $request->total,
            'modified_at' => date('Y-m-d H:i:s'),
            'modified_by' => $request->modified_by
        ];
        $data = $this->db->get_where('pengadaan_produk',['id_pengadaan_produk'=>$id_pengadaan_produk, 'status'=> 'Menunggu Konfirmasi'])->row();
        if($data){
            $this->db->where(['id_pengadaan_produk'=>$id_pengadaan_produk, 'status'=> 'Menunggu Konfirmasi'])->update($this->table, $updateData);
            //$temp = $this->updateTotal($id_transaksi_produk, $request->diskon);
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function updateStatusToProses($request, $id_pengadaan_produk) {
        $updateData = [
            'status' => 'Pesanan Diproses',
            'modified_at' => date('Y-m-d H:i:s'),
            'modified_by' => $request->modified_by
        ];

        $data = $this->db->get_where('pengadaan_produk',['id_pengadaan_produk'=>$id_pengadaan_produk, 'status'=> 'Menunggu Konfirmasi'])->row();
        if($data!=null){
            $this->db->where(['id_pengadaan_produk'=>$id_pengadaan_produk, 'status'=> 'Menunggu Konfirmasi'])->update($this->table, $updateData);
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function updateStatusToSelesai($request, $id_pengadaan_produk) {
        $updateData = [
            'status' => 'Pesanan Selesai',
            'modified_at' => date('Y-m-d H:i:s'),
            'modified_by' => $request->modified_by
        ];

        $data = $this->db->get_where('pengadaan_produk',['id_pengadaan_produk'=>$id_pengadaan_produk, 'status'=> 'Pesanan Diproses'])->row();
        if($data!=null){
            $this->db->trans_start();
            $this->db->where(['id_pengadaan_produk'=>$id_pengadaan_produk, 'status'=> 'Pesanan Diproses'])->update($this->table, $updateData);
            $detail = $this->db->get_where('detail_pengadaan', array('id_pengadaan_produk' => $id_pengadaan_produk))->result();
            foreach ($detail as $item) {
                $this->tambahStokProduk($item->id_produk,$item->jumlah);
            }
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
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function updateTotal($id_pengadaan_produk) {
        //$transdata =$this->db->get_where('transaksi_produk', ['id_transaksi_produk'=>$id_transaksi_produk])->row();
        $this->db->select_sum('total_harga');
        $this->db->where('id_pengadaan_produk', $id_pengadaan_produk);
        $pricedata = $this->db->get('detail_pengadaan')->row();

        $updateData = [
            'total' => $pricedata->total_harga
        ];
        
        $this->db->where('id_pengadaan_produk',$id_pengadaan_produk)->update($this->table, $updateData);
    }
    
    public function destroy($id){
        if (empty($this->db->select('*')->where(['id_pengadaan_produk' => $id, 'status' => 'Menunggu Konfirmasi'])->get($this->table)->row())) 
            return ['msg'=>'Id tidak ditemukan','error'=>true];
        
        $data = $this->db->get_where($this->table, ['id_pengadaan_produk' => $id, 'status' => 'Menunggu Konfirmasi'])->row();
        $detail = $this->db->get_where('detail_pengadaan', array('id_pengadaan_produk' => $id))->result();
        if($data!=null && $data->id_pengadaan_produk==$id){
            $this->db->trans_start();
            $this->db->delete('detail_pengadaan', ['id_pengadaan_produk' => $id]);
            $this->db->delete($this->table, ['id_pengadaan_produk' => $id]);
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
            
        }
        return ['msg'=>'Id tidak ditemukan','error'=>true];
    }

    public function tambahStokProduk($id_produk, $qty){
        $data = $this->db->get_where('produk', array('id_produk' => $id_produk))->row();
        $new_sum = $data->jumlah_stok+$qty;
        $updateData = [
            'jumlah_stok' => $new_sum
        ];
        $this->db->where('id_produk',$data->id_produk)->update('produk', $updateData);
    }
}
?>