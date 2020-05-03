<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use chriskacerguis\RestServer\RestController;
Class Laporan extends RestController{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('LaporanModel');
        $this->load->library('form_validation');
    }

    public function pendapatanBulananProduk_get(){
        return $this->returnData($this->LaporanModel->PendapatanBulananProduk(), false);
    }

    public function pendapatanBulananLayanan_get(){
        return $this->returnData($this->LaporanModel->PendapatanBulananLayanan(), false);
    }

    public function returnData($msg,$error){
        $response['error']=$error;
        $response['message']=$msg;
        return $this->response($response);
    }
}