<?php

class UserModel extends CI_Model{
	
	function __construct(){
		parent::__construct();
		
		$this->load->library(array('session','ServerApi','AccountCacheHelper'));
		$this->load->model('LogModel', 'objlog');
		$this->load->helper('url');
		$this->load->database();
	}
	
	/**
	 * 修改密码
	 * @param unknown $userid
	 * @param unknown $oldpwd
	 * @param unknown $newpwd
	 */
	function ChangePassword($userid, $username, $oldpwd, $newpwd){
		//更新服务端数据
		$result = $this->serverapi->ChangeCustomerAccountPassword($userid, $oldpwd, $newpwd);
		//记录操作日志
		$logdata = array('logtype'=>'修改密码<UserModel.ChangePassword>');
		switch ($result){
			case 1:{
				//更新本地缓存文件
				$info = $this->accountcachehelper->get($username);
				if ($info){
					$info['Password'] = $newpwd;
					$this->accountcachehelper->save($username, $info);
				}
				
				$logdata['logtext'] = '成功';
				break;
			}
			case 2:{
				$logdata['logtext'] = '失败，原始密码输入错误';
				break;
			}
			default:{
				$logdata['logtext'] = '失败，系统异常';
				break;
			}
		}
		$this->objlog->LogOperators($logdata);
		
		return $result;
	}
	
	/**
	 * 验证客户登录
	 * @param string $username
	 * @param string $password
	 * @param string $ip
	 * @param string $useragent
	 */
	function CheckLogin($username, $password, $ip, $useragent){
		//登录成功后返回值
		$result = array();
		//记录登录日志
		$logdata = array('username'=>$username,'loginip'=>$ip,'useragent'=>$useragent);
		//验证账户过程
		$info = $this->accountcachehelper->get($username);
		if ($info){
			$dbpwd  = $info['Password'];
			$enable = $info['Enabled'];
			//验证帐号状态及密码是否一致
			if ($dbpwd == $password && $enable == 1){
				$result['userid']      = $info['UserID'];
				$result['companyid']   = $info['CompanyId'];
				$result['companyname'] = $info['CompanyName'];
				
				//获取用户拥有的项目列表
				$OwnProjects = $info['OwnProjects'];
				$ProjectGUID = array();
				foreach ($OwnProjects as $Project){
					$ProjectGUID[] = $Project['ProjectGUID'];
				}
				$result['ownprojects'] = $ProjectGUID;
				
				$logdata['loginstatus'] = 1;
				$logdata['loginreamrk'] = '登录成功';
			}
			else{
				$logdata['loginstatus'] = 2;
				$logdata['loginreamrk'] = '密码输入错误';
			}
		}
		else{
			$logdata['loginstatus'] = 3; 
			$logdata['loginreamrk'] = '用户名不存在';
		}
		
		$this->objlog->LogLogins($logdata);
		return $result;
	}
	
}