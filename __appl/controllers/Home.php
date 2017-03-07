<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Home extends JINGGA_Controller {
	function __construct(){
		parent::__construct();
	  }
    public function index()
    {
        header('Location: '.$this->host.'Frontend');
    }
}