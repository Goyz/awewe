<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$route['default_controller'] = 'home';
$route['Backoffice'] = 'backend/backend';
$route['Frontend'] = 'frontend/home';
$route['404_override'] = '';
$route['(\w{2})/(.*)'] = '$2';
$route['(\w{2})'] = $route['default_controller'];
$route['translate_uri_dashes'] = FALSE;



$route['Backoffice-masuk'] = 'login';
$route['Backoffice-keluar'] = 'login/logout';
$route['Backoffice-grid/(:any)'] = 'backend/backend/get_grid/$1';
$route['Backoffice-form/(:any)'] = 'backend/backend/get_form/$1';
$route['Backoffice-combo'] = 'backend/get_combo';
$route['Backoffice-simpan/(:any)/(:any)'] = 'backend/simpandata/$1/$2';
$route['Backoffice-delete'] = 'backend/simpandata';
$route['Backoffice-upload'] = 'backend/upload';
$route['Backoffice-hapusFile'] = 'backend/hapus_file';
$route['Backoffice-GetDetil'] = 'backend/get_konten';
$route['Backoffice-Cetak'] = 'backend/cetak';
$route['Backoffice-SetFlag'] = 'backend/set_flag';
$route['Backoffice-Dashboard'] = 'backend/get_konten';
$route['Backoffice-GetDataChart'] = 'backend/get_chart';
$route['Backoffice-laporan/(:any)'] = 'backend/get_form/$1';
$route['Backoffice-Pesan'] = 'backend/get_pesan';
$route['Backoffice-Data/(:any)'] = 'backend/backend/getdata/$1';
$route['Backoffice-HapusGambar'] = 'backend/backend/simpandata/tbl_foto_produk/delete';
