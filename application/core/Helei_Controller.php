<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 系统用户验证基类
 * @author Suop
 *
 */
class Suop_Controller extends CI_Controller {
	
	protected $_userid;
	protected $_username;
	protected $_companyid;
	protected $_ownprojects;
	
	function __construct(){
		parent::__construct();
		
		$this->load->library('session');
		$this->load->helper('url');
		
		if ($this->session->userid){
			$this->_userid      = $this->session->userid;
			$this->_username    = $this->session->username;
			$this->_companyid   = $this->session->companyid;
			$this->_ownprojects = $this->session->ownprojects;
		}
		else{
			redirect('/customer/login/');
		}
	}
	
}
