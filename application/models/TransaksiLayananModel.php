<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class TransaksiLayananModel extends CI_Model
{
    private $table = 'transaksi_layanan';

    public $id_transaksi_layanan;
    public $id_customer_service;
    public $id_kasir;
    public $id_hewan;
    public $subtotal;
    public $diskon;
    public $total;
    public $progress;
    public $status;
    public $tanggal_lunas;
    public $created_at;
    public $created_by;
    public $modified_at;
    public $modified_by;

    public $rule = [];

    public function Rules() { return $this->rule; }
   
    public function getAllAktif() {
        return $this->db->get('transaksi_produk')->result();
    } 

    public function store($request) { 
        $date_now = date('dmy');
        $this->db->select_max('id_transaksi_layanan');
        $this->db->like('id_transaksi_layanan', 'LY-'.$date_now, 'after');
        $query = $this->db->get('transaksi_layanan');
        $lastdata = $query->row();
        $last_id = $lastdata->id_transaksi_layanan;
        $last_count = substr($last_id, 10, 2);
        $next_count = $last_count+1;
        $next_id = 'LY-'.$date_now.'-'.sprintf('%02s', $next_count);

        $this->id_transaksi_layanan = $next_id;
        $this->id_customer_service = $request->id_customer_service;
        $this->id_hewan = $request->id_hewan;
        $this->subtotal = $request->subtotal;
        $this->diskon = $request->diskon;
        $this->total = $request->total;
        $this->progress = 'Layanan Selesai';
        $this->status = 'Menunggu Pembayaran';
        $this->created_by = $request->created_by;
        if($this->db->insert($this->table, $this)){
            //$temp = $this->updateTotal($next_id, $request->diskon);
            return ['msg'=>$next_id,'error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function storeReturnObject($request) { 
        $date_now = date('dmy');
        $this->db->select_max('id_transaksi_layanan');
        $this->db->like('id_transaksi_layanan', 'LY-'.$date_now, 'after');
        $query = $this->db->get('transaksi_layanan');
        $lastdata = $query->row();
        $last_id = $lastdata->id_transaksi_layanan;
        $last_count = substr($last_id, 10, 2);
        $next_count = $last_count+1;
        $next_id = 'LY-'.$date_now.'-'.sprintf('%02s', $next_count);

        $this->id_transaksi_layanan = $next_id;
        $this->id_customer_service = $request->id_customer_service;
        $this->id_hewan = $request->id_hewan;
        $this->subtotal = $request->subtotal;
        $this->diskon = $request->diskon;
        $this->total = $request->total;
        $this->progress = 'Layanan Selesai';
        $this->status = 'Menunggu Pembayaran';
        $this->created_by = $request->created_by;
        if($this->db->insert($this->table, $this)){
            //$temp = $this->updateTotal($next_id, $request->diskon);
            $obj = $this->db->get_where('transaksi_layanan', ["id_transaksi_layanan" => $next_id])->row();
            return ['msg'=>$obj,'error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function update($request, $id_transaksi_layanan) {
        $updateData = [
            'id_hewan' => $request->id_hewan,
            'subtotal' => $request->subtotal,
            'diskon' => $request->diskon,
            'total' => $request->total,
            'modified_at' => date('Y-m-d H:i:s'),
            'modified_by' => $request->modified_by
        ];
        $data = $this->db->get_where('transaksi_layanan',['id_transaksi_layanan'=>$id_transaksi_layanan, 'status'=> 'Menunggu Pembayaran'])->row();
        if($data){
            $this->db->where(['id_transaksi_layanan'=>$id_transaksi_layanan, 'status'=> 'Menunggu Pembayaran'])->update($this->table, $updateData);
            //$temp = $this->updateTotal($id_transaksi_layanan, $request->diskon);
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function updateProgress($request, $id_transaksi_layanan) {
        $messageStat = false;
        
        $updateData = [
            'progress' => 'Layanan Selesai',
            'modified_at' => date('Y-m-d H:i:s'),
            'modified_by' => $request->modified_by
        ];
        $data = $this->db->get_where('transaksi_layanan',['id_transaksi_layanan'=>$id_transaksi_layanan, 'status'=> 'Menunggu Pembayaran'])->row();
        if($data){
            $this->db->trans_start();
            $this->db->where(['id_transaksi_layanan'=>$id_transaksi_layanan, 'status'=> 'Menunggu Pembayaran'])->update($this->table, $updateData);            $this->db->select('id_hewan, hewan.id_pelanggan, pelanggan.nama "nama_pelanggan", pelanggan.alamat "alamat_pelanggan", 
                        pelanggan.tanggal_lahir "tanggal_lahir_pelanggan", pelanggan.telp "telp_pelanggan",
                        hewan.id_jenis_hewan, jenis_hewan.nama "nama_jenis_hewan", hewan.nama "nama_hewan", hewan.tanggal_lahir "tanggal_lahir_hewan", 
                        hewan.created_at, hewan.created_by, hewan.modified_at, hewan.modified_by, hewan.delete_at, hewan.delete_by, hewan.aktif');
            $this->db->from('hewan');
            $this->db->join('pelanggan', 'hewan.id_pelanggan = pelanggan.id_pelanggan');
            $this->db->join('jenis_hewan', 'hewan.id_jenis_hewan = jenis_hewan.id_jenis_hewan');
            $this->db->where('id_hewan',$id_hewan);
            $hewan = $this->db->get()->row();

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                # Something went wrong.
                $this->db->trans_rollback();
                return ['msg'=>'Gagal','error'=>true];
            } 
            else {
                # Everything is Perfect. 
                # Committing data to the database.
                // $telp = '';
                // if(substr($hewan->telp_pelanggan,0,3)=='+62'){
                //     $telp = $hewan->telp_pelanggan;
                // }else{
                //     $number = substr($hewan->telp_pelanggan,1,strlen($hewan->telp_pelanggan));
                //     $telp = '+62'.$number;
                // }

                // $fields_string  =   "";
                // $fields = array(
                //             'api_key'       =>  'a0c91022',
                //             'api_secret'    =>  'qCSO83HdmC87Pv3P',
                //             'to'            =>  $telp,
                //             'from'          =>  "Kouvee Pet Shop",
                //             'text'          =>  'Halo '.$hewan->nama_pelanggan.', layanan untuk peliharaan anda sudah selesai dikerjakan, mohon selesaikan pembayaran di Kouvee Pet Shop. Thanks.'
                //             );
                // $url    =   "https://rest.nexmo.com/sms/json";

                // //url-ify the data for the POST
                // foreach($fields as $key=>$value) { 
                //         $fields_string .= $key.'='.$value.'&'; 
                //         }
                // rtrim($fields_string, '&');

                // //open connection
                // $ch = curl_init();

                // //set the url, number of POST vars, POST data
                // curl_setopt($ch,CURLOPT_URL, $url);
                // curl_setopt($ch,CURLOPT_POST, count($fields));
                // curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
                // curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

                // //execute post
                // $curl_res = curl_exec($ch);
                // //close connection
                // curl_close($ch);

                // $result = json_decode($curl_res);

                // if($result->messages[0]->status == 0) {
                //     $messageStat = true;
                // } else {
                //     $messageStat = false;
                // }
                // if($messageStat){
                    $this->db->trans_commit();
                    return ['msg'=>'Berhasil','error'=>false];
                // }
                // $this->db->trans_rollback();
                // return ['msg'=>'Gagal','error'=>true];
            }
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function updateStatus($request, $id_transaksi_layanan) {
        $updateData = [
            'id_kasir' => $request->id_kasir,
            'status' => 'Lunas',
            'tanggal_lunas' => date('Y-m-d H:i:s'),
            'modified_at' => date('Y-m-d H:i:s'),
            'modified_by' => $request->modified_by
        ];

        $data = $this->db->get_where('transaksi_layanan',['id_transaksi_layanan'=>$id_transaksi_layanan, 'progress'=>'Layanan Selesai', 'status'=> 'Menunggu Pembayaran'])->row();
        if($data!=null){
            $this->db->where(['id_transaksi_layanan'=>$id_transaksi_layanan, 'progress'=>'Layanan Selesai', 'status'=> 'Menunggu Pembayaran'])->update('transaksi_layanan', $updateData);
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function updateTotal($id_transaksi_layanan, $diskon) {
        //$transdata =$this->db->get_where('transaksi_produk', ['id_transaksi_produk'=>$id_transaksi_produk])->row();
        $this->db->select_sum('total_harga');
        $this->db->where('id_transaksi_layanan', $id_transaksi_layanan);
        $pricedata = $this->db->get('detail_transaksi_layanan')->row();
        if($pricedata->total_harga==null || $pricedata->total_harga<=$diskon)
        {
            $updateData = [
                'subtotal' => $pricedata->total_harga, 
                'total' => 0
            ];
            if($pricedata->total_harga==null){
                $updateData['subtotal'] = 0;
            }
        }else{
            if($diskon==null){
                $updateData = [
                    'subtotal' => $pricedata->total_harga, 
                    'total' => $pricedata->total_harga
                ];
            }else{
                $updateData = [
                    'subtotal' => $pricedata->total_harga, 
                    'total' => $pricedata->total_harga-$diskon
                ];
            }
        }
        
        if($this->db->where('id_transaksi_layanan',$id_transaksi_layanan)->update($this->table, $updateData)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
    
    public function destroy($id){
        if (empty($this->db->select('*')->where(array('id_transaksi_layanan' => $id))->get($this->table)->row())) 
            return ['msg'=>'Id tidak ditemukan','error'=>true];
        
        $data = $this->db->get_where($this->table, array('id_transaksi_layanan' => $id))->row();
        if($data!=null && $data->id_transaksi_layanan==$id){
            $this->db->trans_start();
            $this->db->delete('detail_transaksi_layanan', ['id_transaksi_layanan' => $id]);
            $this->db->delete($this->table, ['id_transaksi_layanan' => $id]);
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
}
?>