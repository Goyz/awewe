<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
	LIBRARY CIPTAAN JINGGA LINTAS IMAJI
	KONTEN LIBRARY :
	- Upload File
	- Upload File Multiple
	- RandomString
	- CutString
	- Kirim Email
	- Konversi Bulan
	- Fillcombo
	- Json Datagrid
	
*/
class Lib {
	public function __construct(){
		
	}
	
	//class Upload File Version 1.0 - Beta
	function uploadnong($upload_path="", $object="", $file=""){
		//$upload_path = "./__repository/".$folder."/";
		
		$ext = explode('.',$_FILES[$object]['name']);
		$exttemp = sizeof($ext) - 1;
		$extension = $ext[$exttemp];
		
		$filename =  $file.'.'.$extension;
		
		$files = $_FILES[$object]['name'];
		$tmp  = $_FILES[$object]['tmp_name'];
		if(file_exists($upload_path.$filename)){
			unlink($upload_path.$filename);
			$uploadfile = $upload_path.$filename;
		}else{
			$uploadfile = $upload_path.$filename;
		} 
		
		move_uploaded_file($tmp, $uploadfile);
		if (!chmod($uploadfile, 0775)) {
			echo "Gagal mengupload file";
			exit;
		}
		
		return $filename;
	}
	// end class Upload File
	
	//class Upload File Multiple Version 1.0 - Beta
	function uploadmultiplenong($upload_path="", $object="", $file="", $idx=""){
		$ext = explode('.',$_FILES[$object]['name'][$idx]);
		$exttemp = sizeof($ext) - 1;
		$extension = $ext[$exttemp];
		
		$filename =  $file.'.'.$extension;
		
		$files = $_FILES[$object]['name'][$idx];
		$tmp  = $_FILES[$object]['tmp_name'][$idx];
		if(file_exists($upload_path.$filename)){
			unlink($upload_path.$filename);
			$uploadfile = $upload_path.$filename;
		}else{
			$uploadfile = $upload_path.$filename;
		} 
		
		move_uploaded_file($tmp, $uploadfile);
		if (!chmod($uploadfile, 0775)) {
			echo "Gagal mengupload file";
			exit;
		}
		
		return $filename;
	}
	//end Class Upload File
	
	//class Random String Version 1.0
	function randomString($length,$parameter="") {
        $str = "";
		$rangehuruf = range('A','Z');
		$rangeangka = range('0','9');
		if($parameter == 'angka'){
			$characters = array_merge($rangeangka);
		}elseif($parameter == 'huruf'){
			$characters = array_merge($rangehuruf);
		}else{
			$characters = array_merge($rangehuruf, $rangeangka);
		}
         $max = count($characters) - 1;
         for ($i = 0; $i < $length; $i++) {
              $rand = mt_rand(0, $max);
              $str .= $characters[$rand];
         }
         return $str;
    }
	//end Class Random String
	
	//Class CutString
	function cutstring($text, $length) {
		//$isi_teks = htmlentities(strip_tags($text));
		$isi = substr($text, 0,$length);
		//$isi = substr($isi_teks, 0,strrpos($isi," "));
		$isi = $isi.' ...';
		return $isi;
	}
	//end Class CutString
	
	//Class Kirim Email
	function kirimemail($type="", $email="", $p1="", $p2="", $p3=""){
		$ci =& get_instance();
		
		$ci->load->library('email');
		$html = "";
		$subject = "";
		switch($type){
			case "email_invoice":
				$ci->nsmarty->assign('data_cart', $p1);
				$ci->nsmarty->assign('penunjang', $p2);
				$html = $ci->nsmarty->fetch('frontend/modul/email_invoice.html');
				$subject = "EMAIL INVOICE - ".$p2['no_order'];
			break;
			case "email_konfirmasi":	
				$ci->nsmarty->assign('no_order', $p1);
				$subject = "EMAIL KONFIRMASI PEMBAYARAN";
				$html = $ci->nsmarty->fetch('frontend/modul/email_konfirmasi.html');
			break;
			case "email_pembatalan":
				$ci->nsmarty->assign('kode_pembatalan', $p1);
				$ci->nsmarty->assign('no_order', $p2);
				$html = $ci->nsmarty->fetch('frontend/modul/email_pembatalan.html');
				$subject = "EMAIL PEMBATALAN PESANAN";
			break;
		}
		
		/*
		$config = array(
			"protocol"	=>"smtp"
			,"mailtype" => "html"
			,"smtp_host" => "ssl://server.jingga.co.id"
			,"smtp_user" => "webstore@aldeaz.id"
			,"smtp_pass" => "merdeka18"
			,"smtp_port" => "465",
			'charset' => 'utf-8',
            'wordwrap' => TRUE,
		);
		*/
		
		$config = array(
			"protocol"	=>"smtp"
			,"mailtype" => "html"
			,"smtp_host" => "ssl://smtp.gmail.com"
			,"smtp_user" => "aldeaz.id@gmail.com"
			,"smtp_pass" => "merdeka18"
			,"smtp_port" => "465",
			'charset' => 'utf-8',
            'wordwrap' => TRUE,
		);
		
		//,"smtp_user" => "aldeaz.id@gmail.com","smtp_pass" => "merdeka18" */
		
		$ci->email->initialize($config);
		//$ci->email->from("aldeaz.id@gmail.com", "Aldeaz Notifikasi");
		$ci->email->from("webstore@aldeaz.id", "Aldeaz Notifikasi");
		$ci->email->to($email);
		$ci->email->subject($subject);
		$ci->email->message($html);
		$ci->email->set_newline("\r\n");
		if($ci->email->send())
			//echo "<h3> SUKSES EMAIL ke $email </h3>";
			return 1;
		else
			//echo $this->email->print_debugger();
			return $ci->email->print_debugger();
	}	
	//End Class KirimEmail
	
	//Class Konversi Bulan
	function konversi_bulan($bln,$type=""){
		if($type == 'fullbulan'){
			switch($bln){
				case 1:$bulan='Januari';break;
				case 2:$bulan='Februari';break;
				case 3:$bulan='Maret';break;
				case 4:$bulan='April';break;
				case 5:$bulan='Mei';break;
				case 6:$bulan='Juni';break;
				case 7:$bulan='Juli';break;
				case 8:$bulan='Agustus';break;
				case 9:$bulan='September';break;
				case 10:$bulan='Oktober';break;
				case 11:$bulan='November';break;
				case 12:$bulan='Desember';break;
			}
		}else{
			switch($bln){
				case 1:$bulan='Jan';break;
				case 2:$bulan='Feb';break;
				case 3:$bulan='Mar';break;
				case 4:$bulan='Apr';break;
				case 5:$bulan='Mei';break;
				case 6:$bulan='Jun';break;
				case 7:$bulan='Jul';break;
				case 8:$bulan='Agst';break;
				case 9:$bulan='Sept';break;
				case 10:$bulan='Okt';break;
				case 11:$bulan='Nov';break;
				case 12:$bulan='Des';break;
			}
		}
		return $bulan;
	}
	//End Class Konversi Bulan
	
	//Class Konversi Tanggal
	function konversi_tgl($date){
		$ci =& get_instance();
		$ci->load->helper('terbilang');
		$data=array();
		$timestamp = strtotime($date);
		$day = date('D', $timestamp);
		$day_angka = (int)date('d', $timestamp);
		$month = date('m', $timestamp);
		$years = date('Y', $timestamp);
		switch($day){
			case "Mon":$data['hari']='Senin';break;
			case "Tue":$data['hari']='Selasa';break;
			case "Wed":$data['hari']='Rabu';break;
			case "Thu":$data['hari']='Kamis';break;
			case "Fri":$data['hari']='Jumat';break;
			case "Sat":$data['hari']='Sabtu';break;
			case "Sun":$data['hari']='Minggu';break;
		}
		switch($month){
			case "01":$data['bulan']='Januari';break;	
			case "02":$data['bulan']='Februari';break;	
			case "03":$data['bulan']='Maret';break;	
			case "04":$data['bulan']='April';break;	
			case "05":$data['bulan']='Mei';break;	
			case "06":$data['bulan']='Juni';break;	
			case "07":$data['bulan']='Juli';break;	
			case "08":$data['bulan']='Agustus';break;	
			case "09":$data['bulan']='September';break;	
			case "10":$data['bulan']='Oktober';break;	
			case "11":$data['bulan']='November';break;	
			case "12":$data['bulan']='Desember';break;	
		}
		$data['tahun']=ucwords(number_to_words($years));
		$data['tgl_text']=ucwords(number_to_words($day_angka));
		return $data;
	}
	//End Class Konversi Tanggal
	
	//Class Fillcombo
	function fillcombo($type="", $balikan="", $p1="", $p2="", $p3=""){
		$ci =& get_instance();
		$ci->load->model('modelsx');
		
		$v = $ci->input->post('v');
		if($v != ""){
			$selTxt = $v;
		}else{
			$selTxt = $p1;
		}
		
		$optTemp = '<option value=""> -- Pilih -- </option>';
		switch($type){
			case "pengirim":
				$data = array(
					'0' => array('id'=>'Internal','txt'=>'Internal'),
					'1' => array('id'=>'External','txt'=>'External'),
				);
			break;
			case "jenis_kelamin":
				$data = array(
					'0' => array('id'=>'L','txt'=>'Laki-Laki'),
					'1' => array('id'=>'P','txt'=>'Perempuan'),
				);
			break;
			case "tipe_status":
				$data = array(
					'0' => array('id'=>'1','txt'=>'Aktif'),
					'1' => array('id'=>'0','txt'=>'Tidak Aktif'),
				);
			break;
			case "bulan" :
				$data = $this->arraydate('bulan');
				$optTemp = '<option value=""> -- Month -- </option>';
			break;
			case "tahun" :
				$data = $this->arraydate('tahun');
				$optTemp = '<option value=""> -- Year -- </option>';
			break;			
			default:
				$data = $ci->modelsx->get_combo($type, $p1, $p2);
			break;
		}
		
		if($data){
			foreach($data as $k=>$v){
				if($selTxt == $v['id']){
					$optTemp .= '<option selected value="'.$v['id'].'">'.$v['txt'].'</option>';
				}else{ 
					$optTemp .= '<option value="'.$v['id'].'">'.$v['txt'].'</option>';	
				}
			}
		}
		
		if($balikan == 'return'){
			return $optTemp;
		}elseif($balikan == 'echo'){
			echo $optTemp;
		}
		
	}
	//End Class Fillcombo
	
	//Function Json Grid
	function json_grid($sql,$type="",$table=""){
		$ci =& get_instance();
		$ci->load->database();
		
		$page = (integer) (($ci->input->post('page')) ? $ci->input->post('page') : "1");
		$limit = (integer) (($ci->input->post('rows')) ? $ci->input->post('rows') : "10");
		$count = $ci->db->query($sql)->num_rows();
		
		if( $count >0 ) { $total_pages = ceil($count/$limit); } else { $total_pages = 0; } 
		if ($page > $total_pages) $page=$total_pages; 
		$start = $limit*$page - $limit; // do not put $limit*($page - 1)
		if($start<0) $start=0;
		 		
		$sql = $sql . " LIMIT $start,$limit";
					
		$data = $ci->db->query($sql)->result_array();  
				
		if($data){
		   $responce = new stdClass();
		   $responce->rows= $data;
		   $responce->total =$count;
		   return json_encode($responce);
		}else{ 
		   $responce = new stdClass();
		   $responce->rows = 0;
		   $responce->total = 0;
		   return json_encode($responce);
		} 
	}
	//end Json Grid
	
	//Generate Form Via Field Table
	function generateform($table){
		$ci =& get_instance();
		$ci->load->database();
		
		$field = $ci->db->list_fields($table);
		$arrayform = array();
		$i = 0;
		foreach($field as $k => $v){							
			if($v == 'create_date' || $v == 'create_by'){
				continue;
			}
			
			$label = str_replace('_', ' ', $v);
			$label = strtoupper($label);
			
			if($v == 'id'){
				$arrayform[$k]['tipe'] = "hidden";
			}else{	
				if(strpos($v, 'cl_') !== false){
					$label = str_replace("CL ", "", $label);
					$label = str_replace(" ID", "", $label);
					
					$arrayform[$k]['tipe'] = "combo";
					$arrayform[$k]['ukuran_class'] = "span4";
					$arrayform[$k]['isi_combo'] =  $ci->lib->fillcombo($v, 'return', ($sts_crud == 'edit' ? $data[$y] : "") );
				}elseif(strpos($v, 'tipe_') !== false){
					$arrayform[$k]['tipe'] = "combo";
					$arrayform[$k]['ukuran_class'] = "span4";
					$arrayform[$k]['isi_combo'] =  $ci->lib->fillcombo($v, 'return', ($sts_crud == 'edit' ? $data[$y] : "") );
				}elseif(strpos($v, 'tgl_') !== false){
					$label = str_replace("TGL", "TANGGAL", $label);
					
					$arrayform[$k]['tipe'] = "text";
					$arrayform[$k]['ukuran_class'] = "span2";
				}elseif(strpos($v, 'isi_') !== false){
					$arrayform[$k]['tipe'] = "textarea";
					$arrayform[$k]['ukuran_class'] = "span8";
				}elseif(strpos($v, 'gambar_') !== false){
					$arrayform[$k]['tipe'] = "file";
					$arrayform[$k]['ukuran_class'] = "span8";	
				}else{
					$arrayform[$k]['tipe'] = "text";
					$arrayform[$k]['ukuran_class'] = "span8";
				}
			}
										
			$arrayform[$k]['name'] = $v;
			$arrayform[$k]['label'] = $label;
			$i++;
		}
		
		return $arrayform;
	}
	//End Generate Form Via Field Table
	function uniq_id(){
		$s = strtoupper(md5(uniqid(rand(),true))); 
		//echo $s;
		$guidText = substr($s,0,6);
		return $guidText;
	}
	
	//Class String Sanitizer
	function clean($string) {
		$string = str_replace(' ', '', $string); // Replaces all spaces with hyphens.
		return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
	}	
	
	function get_ldap_user($mod="",$user="",$pwd="",$group=""){
		$ci =& get_instance();
		//echo $user.$pwd;
		$res=array();
		$res["msg"]=1;
		$ldapconn = ldap_connect($ci->config->item("ldap_host"),$ci->config->item("ldap_port"));
		ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);
		ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
		if($ldapconn) {
			if($mod=='data_ldap'){
				$ldapbind = @ldap_bind($ldapconn, $ci->config->item("ldap_user"), $ci->config->item("ldap_pwd"));
			}else{
				$ldapbind = @ldap_bind($ldapconn, $user.$ci->config->item("ldap_prefix_login"), $pwd);
			}
			if ($ldapbind) {
				
				$ldap_fields=array("samaccountname","name","primarygroupid","displayname","distinguishedname","cn","description","memberof","userprincipalname");           
				if($mod=='data_ldap'){
					//(&(&(objectClass=user)(objectCategory=person))(memberOf=CN=IT,CN=Users,DC=goyz,DC=com))
					$filter="(&(&(objectClass=user)(objectCategory=person))";
					if($group!="")$filter .="(memberOf=CN=$group,CN=Users,".$ci->config->item("ldap_tree").")";
					if($user!="")$filter .="(samaccountname=$user)";
					$filter .=" )";
					//echo $filter;
					$result=@ldap_search($ldapconn,$ci->config->item("ldap_tree"), $filter,$ldap_fields);
				}else if($mod=='login'){
                    $result=ldap_search($ldapconn,$ci->config->item("ldap_tree"),"(&(objectCategory=person)(samaccountname=$user))");
				}
				$data=$this->konvert_array($ldapconn,$result);
				$res["data"]=$data;//GAGAL KONEK
			} else {
				//echo "LDAP bind failed...";
				$res["msg"]=3;//GAGAL BIND
			}
		}else{
			$res["msg"]=2;//GAGAL KONEK
		}
		ldap_close($ldapconn);
		return $res;
	}
	function konvert_array($conn,$result){
		$connection = $conn;
		$resultArray = array();
		if ($result){
			$entry = ldap_first_entry($connection, $result);
			while ($entry){
				$row = array();
				$attr = ldap_first_attribute($connection, $entry);
				while ($attr){
					$val = ldap_get_values_len($connection, $entry, $attr);
					if (array_key_exists('count', $val) AND $val['count'] == 1){
						$row[strtolower($attr)] = $val[0];
					}
					else{
						$row[strtolower($attr)] = $val;
					}
					$attr = ldap_next_attribute($connection, $entry);
				}
				$resultArray[] = $row;
				$entry = ldap_next_entry($connection, $entry);
			}
		}
		return $resultArray;
	}
	function get_space_hardisk(){
		$data=array();
		$bytes = disk_free_space(".");
		$si_prefix = array( 'B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB' );
		$base = 1024;
		$class = min((int)log($bytes , $base) , count($si_prefix) - 1);
		//echo $bytes . '<br />';
		//echo sprintf('%1.2f' , $bytes / pow($base,$class)) . ' ' . $si_prefix[$class] . '<br />';
		$data['free_space']=sprintf('%1.2f' , $bytes / pow($base,$class));
		$data['free_space_satuan']=$si_prefix[$class];
		
		$Bytes = disk_total_space("/");
		$Type=array( 'B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB' );
		$counter=0;
		while($Bytes>=1024)
		{
			$Bytes/=1024;
			$counter++;
		}
		$data['total_space']=number_format($Bytes,2);
		$data['total_space_satuan']=$Type[$counter];
		$data['space_pake']=(double)($data['total_space']-$data['free_space']);
		return $data;
		
	}
	
	function arraydate($type=""){
		switch($type){
			case 'tanggal':
				$data = array(
					'0' => array('id'=>'1','txt'=>'1'),
					'1' => array('id'=>'2','txt'=>'2'),
					'2' => array('id'=>'3','txt'=>'3'),
					'3' => array('id'=>'4','txt'=>'4'),
					'4' => array('id'=>'5','txt'=>'5'),
					'5' => array('id'=>'6','txt'=>'6'),
					'6' => array('id'=>'7','txt'=>'7'),
					'7' => array('id'=>'8','txt'=>'8'),
					'8' => array('id'=>'9','txt'=>'9'),
					'9' => array('id'=>'10','txt'=>'10'),
					'10' => array('id'=>'11','txt'=>'11'),
					'11' => array('id'=>'12','txt'=>'12'),
					'12' => array('id'=>'13','txt'=>'13'),
					'13' => array('id'=>'14','txt'=>'14'),
					'14' => array('id'=>'15','txt'=>'15'),
					'15' => array('id'=>'16','txt'=>'16'),
					'16' => array('id'=>'17','txt'=>'17'),
					'17' => array('id'=>'18','txt'=>'18'),
					'18' => array('id'=>'19','txt'=>'19'),
					'19' => array('id'=>'20','txt'=>'20'),
					'20' => array('id'=>'21','txt'=>'21'),
					'21' => array('id'=>'22','txt'=>'22'),
					'22' => array('id'=>'23','txt'=>'23'),
					'23' => array('id'=>'24','txt'=>'24'),
					'24' => array('id'=>'25','txt'=>'25'),
					'25' => array('id'=>'26','txt'=>'26'),
					'26' => array('id'=>'27','txt'=>'27'),
					'27' => array('id'=>'28','txt'=>'28'),
					'28' => array('id'=>'29','txt'=>'29'),
					'29' => array('id'=>'30','txt'=>'30'),
					'30' => array('id'=>'31','txt'=>'31'),
				);				
			break;
			case 'bulan':
				$data = array(
					'0' => array('id'=>'1','txt'=>'Januari'),
					'1' => array('id'=>'2','txt'=>'Februari'),
					'2' => array('id'=>'3','txt'=>'Maret'),
					'3' => array('id'=>'4','txt'=>'April'),
					'4' => array('id'=>'5','txt'=>'Mei'),
					'5' => array('id'=>'6','txt'=>'Juni'),
					'6' => array('id'=>'7','txt'=>'Juli'),
					'7' => array('id'=>'8','txt'=>'Agustus'),
					'8' => array('id'=>'9','txt'=>'September'),
					'9' => array('id'=>'10','txt'=>'Oktober'),
					'10' => array('id'=>'11','txt'=>'November'),
					'11' => array('id'=>'12','txt'=>'Desember'),
				);
			break;
			case 'bulan_singkat':
				$data = array(
					'0' => array('id'=>'1','txt'=>'Jan'),
					'1' => array('id'=>'2','txt'=>'Feb'),
					'2' => array('id'=>'3','txt'=>'Mar'),
					'3' => array('id'=>'4','txt'=>'Apr'),
					'4' => array('id'=>'5','txt'=>'Mei'),
					'5' => array('id'=>'6','txt'=>'Jun'),
					'6' => array('id'=>'7','txt'=>'Jul'),
					'7' => array('id'=>'8','txt'=>'Ags'),
					'8' => array('id'=>'9','txt'=>'Sept'),
					'9' => array('id'=>'10','txt'=>'Okt'),
					'10' => array('id'=>'11','txt'=>'Nov'),
					'11' => array('id'=>'12','txt'=>'Des'),
				);
			break;
			case 'tahun':
				$data = array();
				$year = date('Y');
				$year_kurang = ($year-3);
				$no = 0;
				while($year >= $year_kurang ){
					array_push($data, array('id' => $year, 'txt'=>$year));
					$year--;
				}
			break;
			case 'tahun_lahir':
				$data = array();
				$year = date('Y');
				$no = 0;
				while($year >= 1950){
					array_push($data, array('id' => $year, 'txt'=>$year));
					$year--;
				}
			break;
		}
		
		return $data;
	}
	
}