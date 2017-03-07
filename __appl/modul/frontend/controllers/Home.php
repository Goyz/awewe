<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Home extends JINGGA_Controller {
	function __construct(){
		parent::__construct();
		$this->nsmarty->setTemplateDir(APPPATH.'modul/frontend/views');   
		$this->nsmarty->assign('temp_style',$this->host.'__assets/frontend/');
	  }
    public function index()
    {
        $this->nsmarty->display('home.html');
    }
}