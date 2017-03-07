<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends JINGGA_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->library(array('encrypt','lib'));
		$this->load->model('mlogin');
	}
	
	public function index(){
		$user = $this->db->escape_str($this->input->post('user'));
		$pass = $this->db->escape_str($this->input->post('pwd'));
		$error=false;
		//echo $user;exit;
		//echo $this->encrypt->encode($pass);exit;
		if($user && $pass){
			$cek_user = $this->mlogin->getdata('data_login','row_array',$user);
			//echo $this->encrypt->decode($cek_user['password']);exit;
			//print_r($cek_user);exit;
			if(count($cek_user)>0){
				if(isset($cek_user['status']) && $cek_user['status']==1){
					if($pass == $this->encrypt->decode($cek_user['password'])){
						$this->session->set_userdata('4ld33334zzzzzzt', base64_encode(serialize($cek_user)));
					}else{
						$error=true;
						$this->session->set_flashdata('error', 'Password Tidak Benar');
					}
				}else{
					$error=true;
					$this->session->set_flashdata('error', 'USER Sudah Tidak Aktif Lagi');
				}
			}else{
				$error=true;
				$this->session->set_flashdata('error', 'User Tidak Terdaftar');
			}
		}else{
			$error=true;
			$this->session->set_flashdata('error', 'Isi User Dan Password');
		}
		header("Location: " . $this->host ."Backoffice");
	
		
	}
	
	function logout(){
		$log = $this->db->update('admin', array('last_log_date'=>date('Y-m-d')), array('username'=>$this->auth['username']) );
		if($log){
			$this->session->unset_userdata('4ld33334zzzzzzt', 'limit');
			$this->session->sess_destroy();
			header("Location: " . $this->host ."Backoffice");
		}
	}
	
}
