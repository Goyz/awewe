<?php if (!defined('BASEPATH')) {exit('No direct script access allowed');}

class Mbackend extends CI_Model{
	function __construct(){
		parent::__construct();
		//$this->auth = unserialize(base64_decode($this->session->userdata('44mpp3R4')));
	}
	
	function getdata($type="", $balikan="", $p1="", $p2="",$p3="",$p4=""){
		$where = " WHERE 1=1 ";
		if($this->input->post('key')){
				$where .=" AND ".$this->input->post('kat')." like '%".$this->db->escape_str($this->input->post('key'))."%'";
		}
		if($balikan=='row_array'){
				$where .=" AND A.id=".$this->input->post('id');
		}
		switch($type){
			case "tbl_produk":
				if($balikan=='row_array'){
					$where .=" AND A.id=".$this->input->post('id');
				}
				$sql="SELECT A.*,B.nama_kategori
					  FROM tbl_produk A 
					  LEFT JOIN cl_kategori B ON A.cl_kategori_id=B.id".$where;
			break;
			case "tbl_produk_option":
				$where .=" AND A.tbl_produk_id=".$this->input->post('tbl_produk_id');
				if($balikan=='row_array'){
					$where .=" AND A.id=".$this->input->post('id');
				}
				$sql="SELECT A.*,B.ukuran,B.deskripsi,C.warna
					  FROM tbl_produk_option A 
					  LEFT JOIN cl_ukuran B ON A.cl_ukuran_id=B.id 
					  LEFT JOIN cl_warna C ON A.cl_warna_id =C.id ".$where;
			break;
			case "tbl_foto_produk":
				$sql="SELECT * FROM tbl_foto_produk WHERE tbl_produk_id=".$this->input->post('id');
			break;
			case "cl_kategori":
				if($balikan=='row_array'){
					$where .=" AND A.id=".$this->input->post('id');
				}
				$sql="SELECT A.*
					  FROM cl_kategori A ".$where;
			break;
			case "cl_ukuran":
				if($balikan=='row_array'){
					$where .=" AND A.id=".$this->input->post('id');
				}
				$sql="SELECT A.*
					  FROM cl_ukuran A ".$where;
			break;
			case "cl_warna":
				if($balikan=='row_array'){
					$where .=" AND A.id=".$this->input->post('id');
				}
				$sql="SELECT A.*
					  FROM cl_warna A ".$where;
			break;
			default:
				$sql="SELECT A.*
					  FROM ".$type." A ".$where;
			break;
		}
		
		if($balikan == 'json'){
			return $this->lib->json_grid($sql);
		}elseif($balikan == 'row_array'){
			return $this->db->query($sql)->row_array();
		}elseif($balikan == 'result'){
			return $this->db->query($sql)->result();
		}elseif($balikan == 'result_array'){
			return $this->db->query($sql)->result_array();
		}
		
	}
	
	
	function get_combo($type="", $p1="", $p2=""){
		switch($type){
			case "cl_unit_kerja":
				$sql = "
					SELECT id, nama_unit as txt
					FROM cl_unit_kerja
				";
			break;
			case "cl_jenis_dokumen":
				$sql = "
					SELECT id, tipe_dokumen as txt
					FROM cl_jenis_dokumen
				";
			break;
			case "cl_area":
				$sql = "
					SELECT id, nama_area as txt
					FROM cl_area
				";
			break;
		}
		
		return $this->db->query($sql)->result_array();
	}
	
	function getapi($type="", $p1="", $p2=""){
		$where = " WHERE 1=1 ";
		$per_page = (isset($p1['per_page']) || $p1['per_page'] != null ? $p1['per_page'] : 50);
		$page = (isset($p1['page'])  || $p1['page'] != null ? $p1['page'] : 1);
		$end = $page * $per_page; 
		$start = $end - $per_page + 1;
		if($start < 0) $start = 0;
		
		switch($type){
			case "document_file":
				if($p1['no_dokumen']){
					$where .= " AND LOWER(A.no_dokumen) like '%".strtolower($p1['no_dokumen'])."%' ";
				}
				
				if($p1['perihal']){
					$where .= " AND LOWER(A.perihal) like '%".strtolower($p1['perihal'])."%' ";
				}
				
				$sql = "
					SELECT * FROM (
						SELECT ROW_NUMBER() OVER (ORDER BY A.id ASC) as rowID,A.*, 
							B.nama_unit as unit_kerja, A.create_date as tanggal_upload,
							C.tipe_dokumen, D.nama_unit as pengirim_internal,
							to_char(A.tanggal_arsip, 'DD Month YYYY') as tanggal_arsipnya,
							to_char(A.create_date, 'DD Month YYYY') as tanggal_uploadnya
						FROM tbl_upload_file A
						LEFT JOIN cl_unit_kerja B ON B.id = A.cl_unit_kerja_id
						LEFT JOIN cl_jenis_dokumen C ON C.id = A.cl_jenis_dokumen_id
						LEFT JOIN cl_unit_kerja D ON D.id = A.pengirim_internal_unit_kerja
						$where
						ORDER BY A.create_date DESC
					) AS X WHERE X.rowID BETWEEN $start AND $end
				";
			
				return $this->db->query($sql)->result_array();
			break;
		}
	}
	
	function simpandata($table,$data,$sts_crud){ //$sts_crud --> STATUS NYEE INSERT, UPDATE, DELETE
		$this->db->trans_begin();
		if(isset($data['id'])){
			$id = $data['id'];
			unset($data['id']);
		}
		
		//print_r($_FILES);exit;
		switch($table){
			case "tbl_slider":
				$data['create_date']=date('Y-m-d H:i:s');
				$data['create_by']=$this->auth['username'];
				if(isset($_FILES['gambar'])){
					if($_FILES['gambar']['name']!=""){
						$name=date('YmdHis');
						$data['gambar']=$this->lib->uploadnong('__repository/banner/', 'gambar', $name);
					}
				}
				
				
				
			break;
			case "cl_kelas":
			case "cl_edisi":
				$data['create_date']=date('Y-m-d H:i:s');
				$data['create_by']=$this->auth['username'];
			break;
		}
		
		switch ($sts_crud){
			case "add":
				$data['create_date']=date('Y-m-d H:i:s');
				$data['create_by']=$this->auth['username'];
				$this->db->insert($table,$data);
				if($table=="tbl_buku"){
					$id=$this->db->insert_id();
				}
				if($table=="tbl_produk"){
					$id=$this->db->insert_id();
					$upload_path='__repository/produk/';
					$idx=0;
					$file_name=date('YmdHis');
					$data_gambar=array('tbl_produk_id'=>$id,
									   'create_by'=>$this->auth['username'],
									   'create_date'=>date('Y-m-d H:i:s')
					);
					foreach($_FILES['foto_produk']['name'] as $v){
						if($v!=''){
							$nama_file = $file_name.'_'.$idx;
							$data_gambar['foto_produk']=$this->lib->uploadmultiplenong($upload_path, "foto_produk", $nama_file, $idx);
							$this->db->insert('tbl_foto_produk',$data_gambar);
							$idx++;
						}
					}
				}
			break;
			case "edit":
				
				$this->db->update($table, $data, array('id' => $id) );
				if($table=="tbl_produk"){
					$upload_path='__repository/produk/';
					$idx=0;
					$file_name=date('YmdHis');
					$data_gambar=array('tbl_produk_id'=>$id,
									   'create_by'=>$this->auth['username'],
									   'create_date'=>date('Y-m-d H:i:s')
					);
					foreach($_FILES['foto_produk']['name'] as $v){
						if($v!=''){
							$nama_file = $file_name.'_'.$idx;
							$data_gambar['foto_produk']=$this->lib->uploadmultiplenong($upload_path, "foto_produk", $nama_file, $idx);
							$this->db->insert('tbl_foto_produk',$data_gambar);
							$idx++;
						}
					}
				}
			break;
			case "delete":
				if($table=="tbl_slider"){
					$gbr=$this->db->get_where('tbl_slider',array('id'=>$id))->row_array();
					if(isset($gbr['gambar'])){
						chmod('__repository/banner/'.$gbr['gambar'],0777);
						unlink('__repository/banner/'.$gbr['gambar']);
					}
				}
				if($table=="tbl_foto_produk"){
					$gbr=$this->db->get_where('tbl_foto_produk',array('id'=>$id))->row_array();
					if(isset($gbr['foto_produk'])){
						chmod('__repository/produk/'.$gbr['foto_produk'],0777);
						unlink('__repository/produk/'.$gbr['foto_produk']);
					}
				}
				if($table=="tbl_produk"){
					$gbr=$this->db->get_where('tbl_foto_produk',array('tbl_produk_id'=>$id))->result_array();
					
					if(count($gbr)>0){
						foreach($gbr as $v){
							chmod('__repository/produk/'.$v['foto_produk'],0777);
							unlink('__repository/produk/'.$v['foto_produk']);
						}
						$this->db->delete('tbl_foto_produk', array('tbl_produk_id' => $id));
					}
					$this->db->delete('tbl_produk_option', array('tbl_produk_id' => $id));
				}
				
				$this->db->delete($table, array('id' => $id));
				
			break;
			
		}
		
		if($this->db->trans_status() == false){
			$this->db->trans_rollback();
			return 0;
		} else{
			if($table=="tbl_buku"){
				if($sts_crud !='delete'){
					$this->db->trans_commit();
					$js=array('msg'=>1,'data'=>$id);
					return json_encode($js);
				}else{
					return $this->db->trans_commit();
				}
			}elseif($table=="tbl_paket"){
				if($sts_crud =='disable_enable'){
					$this->db->trans_commit();
					$js=array('msg'=>1,'data'=>$id);
					return json_encode($js);
				}else{
					return $this->db->trans_commit();
				}				
			}else{
				return $this->db->trans_commit();
			}
		}
	
	}
	
}
