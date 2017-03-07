<?php if (!defined('BASEPATH')) {exit('No direct script access allowed');}

class Mlogin extends CI_Model{
	function __construct(){
		parent::__construct();
		//$this->auth = unserialize(base64_decode($this->session->userdata('44mpp3R4')));
	}
	
	function getdata($type="", $balikan="", $p1="", $p2="",$p3="",$p4=""){
		$where  = " WHERE 1=1 ";
		$where2 = "";
		//$where .=" AND ".$this->input->post('kat')." like '%".$this->db->escape_str($this->input->post('key'))."%'";
		
		switch($type){
			
			case "tbl_user":
				$sql="SELECT ROW_NUMBER() OVER (ORDER BY A.id ASC) as rowID,A.*,B.group_user,C.nama_unit 
						FROM tbl_user A 
						LEFT JOIN cl_group_user B ON A.cl_user_group_id=B.id 
						LEFT JOIN cl_unit_kerja C ON A.cl_unit_kerja_id=C.id 
						".$where;
			break;
			case "ldap_user":
				if($this->input->post('key') || $this->input->post('group')){
					$data=$this->lib->get_ldap_user("data_ldap",$this->input->post('key'),"",$this->input->post('group'));
				}else{
					$data=$this->lib->get_ldap_user("data_ldap");
				}
				//print_r($data);
				if($data['msg']==1){
				   $responce = new stdClass();
				   $responce->rows= $data['data'];
				   $responce->total =count($data);
				   return json_encode($responce);
				}else{ 
				   $responce = new stdClass();
				   $responce->rows = 0;
				   $responce->total = 0;
				   return json_encode($responce);
				} 
				
			break;
			case "data_login":
				$sql = "
					SELECT *
					FROM admin
					WHERE username = '".$p1."'
				";				
			break;
			
			//User Management Role
			case "menu":
				$sql = "
					SELECT a.tbl_menu_id, b.nama_menu, b.type_menu, b.icon_menu, b.url, b.ref_tbl
						FROM tbl_user_prev_group a
					LEFT JOIN tbl_user_menu b ON a.tbl_menu_id = b.id 
					WHERE a.cl_user_group_id=".$this->auth['cl_user_group_id']." 
					AND (b.type_menu='P' OR b.type_menu='PC') AND b.status='1'
				";
				$parent = $this->db->query($sql)->result_array();
				$menu = array();
				foreach($parent as $v){
					$menu[$v['tbl_menu_id']]=array();
					$menu[$v['tbl_menu_id']]['parent']=$v['nama_menu'];
					$menu[$v['tbl_menu_id']]['icon_menu']=$v['icon_menu'];
					$menu[$v['tbl_menu_id']]['url']=$v['url'];
					$menu[$v['tbl_menu_id']]['type_menu']=$v['type_menu'];
					$menu[$v['tbl_menu_id']]['judul_kecil']=$v['ref_tbl'];
					$menu[$v['tbl_menu_id']]['child']=array();
					$sql="
						SELECT a.tbl_menu_id, b.nama_menu, b.url, b.icon_menu , b.type_menu, b.ref_tbl
							FROM tbl_user_prev_group a
						LEFT JOIN tbl_user_menu b ON a.tbl_menu_id = b.id 
						WHERE a.cl_user_group_id=".$this->auth['cl_user_group_id']." 
						AND (b.type_menu = 'C' OR b.type_menu = 'CHC') 
						AND b.status='1' AND b.parent_id=".$v['tbl_menu_id'];
					$child = $this->db->query($sql)->result_array();
					foreach($child as $x){
						$menu[$v['tbl_menu_id']]['child'][$x['tbl_menu_id']]=array();
						$menu[$v['tbl_menu_id']]['child'][$x['tbl_menu_id']]['menu']=$x['nama_menu'];
						$menu[$v['tbl_menu_id']]['child'][$x['tbl_menu_id']]['type_menu']=$x['type_menu'];
						$menu[$v['tbl_menu_id']]['child'][$x['tbl_menu_id']]['url']=$x['url'];
						$menu[$v['tbl_menu_id']]['child'][$x['tbl_menu_id']]['icon_menu']=$x['icon_menu'];
						$menu[$v['tbl_menu_id']]['child'][$x['tbl_menu_id']]['judul_kecil']=$x['ref_tbl'];
						
						if($x['type_menu'] == 'CHC'){
							$menu[$v['tbl_menu_id']]['child'][$x['tbl_menu_id']]['sub_child'] = array();
							$sqlSubChild="
								SELECT a.tbl_menu_id, b.nama_menu, b.url, b.icon_menu , b.type_menu, b.ref_tbl
									FROM tbl_user_prev_group a
								LEFT JOIN tbl_user_menu b ON a.tbl_menu_id = b.id 
								WHERE a.cl_user_group_id=".$this->auth['cl_user_group_id']." 
								AND b.type_menu = 'CC'
								AND b.parent_id_2 = ".$x['tbl_menu_id']."
								AND b.status='1' 
							";
							$SubChild = $this->db->query($sqlSubChild)->result_array();
							foreach($SubChild as $z){
								$menu[$v['tbl_menu_id']]['child'][$x['tbl_menu_id']]['sub_child'][$z['tbl_menu_id']]['sub_menu'] = $z['nama_menu'];
								$menu[$v['tbl_menu_id']]['child'][$x['tbl_menu_id']]['sub_child'][$z['tbl_menu_id']]['type_menu'] = $z['type_menu'];
								$menu[$v['tbl_menu_id']]['child'][$x['tbl_menu_id']]['sub_child'][$z['tbl_menu_id']]['url'] = $z['url'];
								$menu[$v['tbl_menu_id']]['child'][$x['tbl_menu_id']]['sub_child'][$z['tbl_menu_id']]['icon_menu'] = $z['icon_menu'];
							}
						}
						
					}
				}
				
				/*
				echo "<pre>";
				print_r($menu);exit;
				//*/
				
				$array = $menu;
			break;		
			
			case "menu_parent":
				$sql = "
					SELECT A.*
					FROM tbl_user_menu A
					WHERE (A.type_menu = 'P' OR A.type_menu = 'PC') AND A.status = '1'
				";
			break;
			case "menu_child":
				$sql = "
					SELECT A.*
					FROM tbl_user_menu A
					WHERE (A.type_menu = 'C') AND A.parent_id = '".$p1."' AND A.status = '1'
				";
			break;
			case "menu_child_2":
				$sql = "
					SELECT A.*
					FROM tbl_user_menu A
					WHERE A.type_menu = 'CC' AND A.parent_id_2 = '".$p1."' AND A.status = '1'
				";
			break;
			case "previliges_menu":
				$sql = "
					SELECT A.*
					FROM tbl_user_prev_group A
					WHERE A.tbl_menu_id = '".$p1."' AND A.cl_user_group_id = '".$p2."'
				";
			break;			
			//End Modul User Management			
			
			default:
				if($balikan=='get'){$where .=" AND A.id=".$this->input->post('id');}
				$sql="SELECT ROW_NUMBER() OVER (ORDER BY A.id ASC) as rowID, A.* FROM ".$type." A ".$where;
				if($balikan=='get')return $this->db->query($sql)->row_array();
			break;
		}
		
		if($balikan == 'json'){
			return $this->lib->json_grid($sql,$type);
		}elseif($balikan == 'row_array'){
			return $this->db->query($sql)->row_array();
		}elseif($balikan == 'result'){
			return $this->db->query($sql)->result();
		}elseif($balikan == 'result_array'){
			return $this->db->query($sql)->result_array();
		}elseif($balikan == 'variable'){
			return $array;
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
	
	
	function simpandata($table,$data,$sts_crud){ //$sts_crud --> STATUS NYEE INSERT, UPDATE, DELETE
		$this->db->trans_begin();
		if(isset($data['id'])){
			$id = $data['id'];
			unset($data['id']);
		}
		$log=array('create_date'=>date('Y-m-d H:i:s'),
				   'create_by'=>$this->auth['nama_user']
		);
		$des_tbl="";
		switch($table){
			case "tbl_ldap_group": 
				//echo "<pre>";print_r($data);exit;
				$des_tbl="Data Konfigurasi LDAP Group dan Unit";
				if($sts_crud=='add'){
					$dt=array();
					foreach($data['user'] as $v){
						$sql="SELECT * FROM tbl_ldap_group WHERE user_ldap='".$v['samaccountname']."'";
						$ex=$this->db->query($sql)->row_array();
						if(!isset($ex['user_ldap'])){
							$dt[]=array('user_ldap'=>$v['samaccountname'],
										'cl_group_user_id'=>$data['cl_group_user_id'],
										'cl_unit_kerja_id'=>$data['cl_unit_kerja_id'],
										'flag'=>'P',
										'create_date'=>date('Y-m-d H:i:s'),
										'create_by'=>$this->auth['nama_user'],
							);
						}
					}
					if(count($dt)>0){
						$this->db->insert_batch('tbl_ldap_group', $dt);
						$log['aktivitas']="Insert ".$des_tbl;
						$log['data_id']=0;
						$log['flag_tbl']="tbl_ldap_group";
						$this->db->insert('tbl_log', $log);
					}
					
				}else{
					
					$this->db->update($table, $data, array('id' => $id) );	
					$log['aktivitas']="Update ".$des_tbl;
					$log['data_id']=$id;
					$log['flag_tbl']="tbl_ldap_group";
					$this->db->insert('tbl_log', $log);
				}
				if($this->db->trans_status() == false){
					$this->db->trans_rollback();
					return 'gagal';
				}else{
					 return $this->db->trans_commit();
				}
			break;
			case "mapping": 
				$table="tbl_ldap_group";
				
			break;
			case "user_mng": $table="tbl_user"; break;
			case "group": $table="cl_group_user"; break;
			case "unit": $table="cl_unit_kerja"; break;
			case "tbl_user":
				if($sts_crud=='add'){
					$cek_user=$this->db->get_where('tbl_user',array('nama_user'=>$data['nama_user']))->row_array();
					if(isset($cek_user['nama_user'])){
						$this->db->trans_rollback();
						return 2;
					}else{
						$pwd=$data['password'];unset($data['password']);
						$data['password']=$this->encrypt->encode($pwd);
					}
				}
				//print_r($data);exit;
			break;
			case "sharing":
				
				$pilih=$data['pilihan'];
				$tgl=$data['tanggal'];
				$bagi=explode('-',$tgl);
				$data['tgl_mulai']=str_replace('/','-',$bagi[0]);
				$data['tgl_akhir']=str_replace('/','-',$bagi[1]);
				//print_r($data);exit;
				unset($data['tanggal']);
				unset($data['pilihan']);
				$data['create_date'] = date('Y-m-d H:i:s');
				$data['create_by'] = $this->auth['nama_user'];
				
				$get_no_doc=$this->db->get_where('tbl_upload_file',array('id'=>$data['tbl_upload_file_id']))->row_array();
				foreach($pilih as $x){
					$data['cl_unit_id']=$x;
					$this->db->insert('tbl_sharing_file',$data);
					
					$log['aktivitas']="Sharing File No.Doc <b>".$get_no_doc['no_dokumen']."</b> File Name <b>".$get_no_doc['nama_file']."</b>";
					$log['data_id']=$this->db->insert_id();
					$log['flag_tbl']="tbl_sharing_file";
					$this->db->insert('tbl_log', $log);
				}
				
				if($this->db->trans_status() == false){
					$this->db->trans_rollback();
					return 'gagal';
				}else{
					 return $this->db->trans_commit();
				}
			break;
			
			case "tbl_upload_file":				
				$target_path = "__repository/".$this->auth['cl_unit_kerja_id']."/";
				if(!is_dir($target_path)) {
					mkdir($target_path, 0777);         
				}
				
				$nama_area = $this->db->get_where("cl_area", array("id"=>$data['cl_area_id']) )->row_array();
				$nama_folder = str_replace(" ", "_", strtolower($nama_area["nama_area"]) );
				$target_path2 = $target_path.$nama_folder."/";
				if(!is_dir($target_path2)) {
					mkdir($target_path2, 0777);
				}
				
				if($sts_crud == 'add'){
					$sqlmax = "
						SELECT MAX(id) as id_max
						FROM tbl_upload_file
					";
					$querymax = $this->db->query($sqlmax)->row_array();
					if($querymax['id_max'] != null){
						$idmax = ($querymax['id_max'] + 1);
						$idmax = date('Ym')."-".sprintf('%05d', $idmax);
					}else{
						$idmax = date('Ym')."-00001";
					}
					$data['id_dokumen'] = $idmax;
					//$data['no_dokumen'] = $idmax;
					$namefilenya = $idmax;
				}elseif($sts_crud == 'edit'){
					$getdata = $this->db->get_where('tbl_upload_file', array('id'=>$id) )->row_array();
					$namefilenya = $getdata['id_dokumen'];
				}elseif($sts_crud == 'delete'){
					if(file_exists($target_path2.$data['nama_file'])){
						unlink($target_path2.$data['nama_file']);
					}
				}
				
				if(isset($_FILES['filename']['name'])){
					if($_FILES['filename']['name'] != ''){	
						if($sts_crud == 'edit'){
							unlink($target_path2.$getdata['nama_file']);
						}
						$filebersih = $this->lib->clean(pathinfo($_FILES['filename']['name'], PATHINFO_FILENAME));
						$file_p = $namefilenya.strtoupper($filebersih);
						$filename_p =  $this->lib->uploadnong($target_path2, 'filename', $file_p); 
						$data['nama_file'] = $filename_p;
					}
				}
				
				$data['cl_unit_kerja_id'] = $this->auth['cl_unit_kerja_id'];
				
				$tgl = $data['jangka_waktu'];
				$bagi = explode('-',$tgl);
				$data['jangka_waktu_mulai'] = str_replace('/','-',$bagi[0]);
				$data['jangka_waktu_akhir'] = str_replace('/','-',$bagi[1]);
				
				$data['nilai_kontrak'] = str_replace('.','',$data['nilai_kontrak']);
				
				unset($data['jangka_waktu']);
			break;
			case "user_role_group":
				$id_group = $id;
				$this->db->delete('tbl_user_prev_group', array('cl_user_group_id'=>$id_group) );
				if(isset($data['data'])){
					$postdata = $data['data'];
					$row=array();
					foreach($postdata as $v){
						$pecah = explode("_",$v);
						$row["buat"]=0;
						$row["baca"]=0;
						$row["ubah"]=0;
						$row["hapus"]=0;
						$row["sharing_file"]=0;
						$row["download_file"]=0;
						
						switch($pecah[0]){
							case "C":
								$row["buat"]=1;
							break;
							case "R":
								$row["baca"]=1;
							break;
							case "U":
								$row["ubah"]=1;
							break;
							case "D":
								$row["hapus"]=1;
							break;
							case "SH":
								$row["sharing_file"]=1;
							break;
							case "DW":
								$row["download_file"]=1;
							break;
						}
						
						$row["tbl_menu_id"] = $pecah[1];
						$row["cl_user_group_id"] = $id_group;
						
						$cek_data = $this->db->get_where('tbl_user_prev_group', array('tbl_menu_id'=>$pecah[1], 'cl_user_group_id'=>$id_group) )->row_array();
						if(!$cek_data){
							$this->db->insert('tbl_user_prev_group', $row);
						}else{
							if($row["buat"]==0)unset($row["buat"]);
							if($row["baca"]==0)unset($row["baca"]);
							if($row["ubah"]==0)unset($row["ubah"]);
							if($row["hapus"]==0)unset($row["hapus"]);
							if($row["sharing_file"]==0)unset($row["sharing_file"]);
							if($row["download_file"]==0)unset($row["download_file"]);
							
							$this->db->update('tbl_user_prev_group', $row, array('tbl_menu_id'=>$pecah[1], 'cl_user_group_id'=>$id_group) );
						}
					}	
				}
			break;
			
		}
		
		switch ($sts_crud){
			case "add":
				$data['create_date'] = date('Y-m-d H:i:s');
				$data['create_by'] = $this->auth['nama_user'];
				if(isset($data['id']) || $data['id']=='' ){unset($data['id']);}
				//print_r($data);exit;
				$this->db->insert($table,$data);
				if($table=='tbl_upload_file'){
					$log['aktivitas']="Upload File No.Doc <b>".$data['no_dokumen']."</b> File Name <b>".$data['nama_file']."</b>";
					$log['data_id']=$this->db->insert_id();
					$log['flag_tbl']="tbl_upload_file";
					$this->db->insert('tbl_log', $log);
				}
				
				
			break;
			case "edit":
				$this->db->update($table, $data, array('id' => $id) );	
				if($table=='tbl_upload_file'){
					$log['aktivitas']="Update File No.Doc <b>".$data['no_dokumen']."</b> File Name <b>".$getdata['nama_file']."</b>";
					$log['data_id']=$id;
					$log['flag_tbl']="tbl_upload_file";
					$this->db->insert('tbl_log', $log);
				}				
			break;
			case "delete":
				if($table=='tbl_upload_file'){
					$get_no_doc=$this->db->get_where('tbl_upload_file',array('id'=>$id))->row_array();
					$log['aktivitas']="Hapus File No.Doc <b>".$get_no_doc['no_dokumen']."</b> File Name <b>".$get_no_doc['nama_file']."</b>";
					$log['data_id']=$id;
					$log['flag_tbl']="tbl_upload_file";
					$this->db->insert('tbl_log', $log);
				}
				$this->db->delete($table, array('id' => $id));
				
			break;
			case "req_delete":
				if($table=='tbl_upload_file'){
					$get_no_doc=$this->db->get_where('tbl_upload_file',array('id'=>$id))->row_array();
					$log['aktivitas']="Request Hapus File No.Doc <b>".$get_no_doc['no_dokumen']."</b> File Name <b>".$get_no_doc['nama_file']."</b>";
					$log['data_id']=$id;
					$log['flag_tbl']="tbl_upload_file";
					$this->db->insert('tbl_log', $log);
				}
				if($table=="tbl_ldap_group")$this->db->delete($table, array('id' => $id));
				else $this->db->update($table, array('status_data'=>'RD'), array('id' => $id) );	
			break;
			case "reject":
				$this->db->update($table, array('status_data'=>null), array('id' => $id) );			
			break;
		}
		
		if($this->db->trans_status() == false){
			$this->db->trans_rollback();
			return 'gagal';
		}else{
			 return $this->db->trans_commit();
		}
	}
	
}
