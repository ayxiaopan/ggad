<?php
/**
 * 留言板及留言保存管理
 * @author Suop
 *
 */
class Guestbook extends CI_Controller{
	
	function __construct(){
		parent::__construct();
		$this->load->library(array('user_agent','JScript','encryption'));
		$this->load->helper('url');
	}
	
	/**
	 * 留言提交URL
	 */
	function save(){
		if ($this->input->method(TRUE) == 'POST'){
			//-------------------------------------------------------
			$name    = trim($this->input->post('name', true));
			$mobile  = trim($this->input->post('mobile', true));
			$address = $this->input->post('address', true);
			$content = $this->input->post('content', true);
			//-------------------------------------------------------			
			$hkey = $this->input->post('hkey', true);//获取传递信息
			//$arr  = json_decode($this->encryption->decrypt($hkey));//解密
			
			//---------------------------------------------------------------
			// Set Default Values.
			//---------------------------------------------------------------
			$guid      = $hkey;//$arr->guid;//项目GUID
			$url  	   = '';//$arr->url;//请求页面所在地址
			$referer   = $this->input->post('refurl', true);//请求页面来源地址referer
			$uuid      = $this->input->post('uuid', true);
			$visitid   = $this->input->post('visitid', true);
						
			//数据验证
			if ($mobile == '' || $name == ''){
				$this->jscript->AlertAndRedirect('提示：姓名和手机不能为空！', $url);
			}
			
			if (strlen($mobile) != 11){
				$this->jscript->AlertAndRedirect('提示：手机号码填写错误！', $url);
			}
			
			$data = array();
			//-----------------------------------
			$data['gbname']      = $name;
			$data['gbmobile']    = $this->encryption->encrypt($mobile);
			$data['gbaddress']   = trim($address);
			$data['gbbody']      = trim($content);
			//-----------------------------------
			$data['projectguid'] = $guid;
			//-----------------------------------
			$data['url']         = $url;
			$data['referer']     = $referer;
			$data['uuid']        = $uuid;
			$data['visitid']     = $visitid;
			//-----------------------------------
			$data['ip']          = $this->input->ip_address();
			$data['useragent']   = $this->input->user_agent();
			
			$this->load->model('GuestBookModel', 'gbook');
			$this->gbook->SaveGuestBooks($data);
			
			$this->jscript->AlertAndRedirect('提示：留言成功！', $url);
		}
		else{
			show_404('', FALSE);
		}
	}
}