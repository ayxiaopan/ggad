<?php
/**
 * 服务端API集成接口
 * @author Helei
 *
 */
class ServerApi{
	
	protected $_Server = URL_ERPSERVICE_BASEURL;
	protected $_CI;
	protected $_HttpHeaders = array('MLAPI-TOKEN:BB13AC2E-2BC7-4A19-9BEC-C473260F539A');
	
	function __construct(){
		$this->_CI =& get_instance();
		$this->_CI->load->library(array('HttpHelper','AccountCacheHelper','ProjectCacheHelper'));
		$this->_CI->load->model('NoticeModel', 'objnotice');
		$this->_CI->load->model('UserModel', 'objuser');
		$this->_CI->load->model('ProjectModel', 'objproject');
		$this->_CI->load->model('GuestBookModel', 'objguestbook');
	}
	
	/**
	 * 执行HTTP请求操作
	 * @param unknown $url
	 * @param unknown $params
	 * @param unknown $method
	 * @return mixed|NULL
	 */
	function ExecuteHttpResponse($url, $params, $method){
		$response = $this->_CI->httphelper->RunHttpRequestUrl($this->_Server.$url, $params, $method, $this->_HttpHeaders);
		//echo $response;exit;
		if ($response!=''){
			$data = json_decode($response, true);
			if ($data){
				$code = $data['Code'];
				if ($code == 200){
					$body = $data['Body'];
					return $body;
				}
				else{
					//错误处理
					
				}
			}
		}
		return FALSE;
	}
	
	/**
	 * 向服务端提交留言数据
	 * @param unknown $userid
	 * @param unknown $oldpwd
	 * @param unknown $newpwd
	 * @return unknown
	 */
	function SaveCustomerGuestBooks($data){
		$body = $this->ExecuteHttpResponse('data/SaveGuestBooks/', $data, REQUEST_METHOD_POST);
		return $body;
	}
	
	/**
	 * 向服务端提交留言反馈数据
	 * @param unknown $data
	 * @return unknown
	 */
	function SaveCustomerGuestBookReviews($data){
		$body = $this->ExecuteHttpResponse('data/SaveGuestBookReviews/', $data, REQUEST_METHOD_POST);
		return $body;
	}
	
	/**
	 * 向服务端提交效果统计数据
	 * @param unknown $data 格式：array('reportdate'=>'日期','data'=>'数据json')
	 * @return unknown
	 */
	function SaveCustomerReportWithSid($data){
		$body = $this->ExecuteHttpResponse('data/SaveCustomerReportWithSid/', $data, REQUEST_METHOD_POST);
		return $body;
	}
	
	/**
	 * 修改密码，修改成功返回1，原始密码输入错误返回2，返回0修改失败
	 * @param unknown $userid
	 * @param unknown $oldpwd
	 * @param unknown $newpwd
	 * @return unknown
	 */
	function ChangeCustomerAccountPassword($userid, $oldpwd, $newpwd){
		$body = $this->ExecuteHttpResponse('customer/changeaccountpassword/', array('UserID'=>$userid,'OldPassword'=>$oldpwd,'NewPassword'=>$newpwd), REQUEST_METHOD_POST);
		return $body;
	}
	
	/**
	 * 获取所有客户帐号信息
	 */
	function GetCustomerAccountList(){
		$body = $this->ExecuteHttpResponse('customer/getaccountlist/', NULL, REQUEST_METHOD_GET);
		if ($body){
			foreach ($body as $item){
				$this->_CI->accountcachehelper->save($item['UserName'], $item);
			}
		}
	}
	
	/**
	 * 获取客服已处理的留言反馈信息
	 * @param string $handledate 获取此日期以后的所有数据
	 */
	function GetHandledCustomerGuestBookReview($handledate){
		$body = $this->ExecuteHttpResponse('customer/GetHandledCustomerGuestBookReview/', array('HandledDate'=>$handledate), REQUEST_METHOD_GET);
		if ($body){
			foreach ($body as $item){
				$this->_CI->objguestbook->SaveHandledCustomerGuestBookReview($item);
			}
		}
	}
	
	/**
	 * 根据账户名称获取账户信息
	 * @param string $username
	 */
	function GetCustomerAccountInfo($username){
		$body = $this->ExecuteHttpResponse('customer/getaccountinfo/', array('UserName'=>$username), REQUEST_METHOD_GET);
		if ($body){
			$this->_CI->accountcachehelper->save($body['UserName'], $body);
			return $body;
		}
		else{
			return FALSE;	
		}
	}

	/**
	 * 获取所有项目列表
	 */
	function GetCompanyProjectList(){
		$body = $this->ExecuteHttpResponse('customer/getcompanyprojectlist/', NULL, REQUEST_METHOD_GET);
		if ($body){
			foreach ($body as $item){
				//写入缓存
				$this->_CI->projectcachehelper->save($item['CompanyProjectId'], $item);
				$this->_CI->projectcachehelper->save($item['ProjectGUID'], $item);
				
				//写入数据库中保留一份
				$data = array(
					'projectid'=>$item['CompanyProjectId'],
					'projectguid'=>$item['ProjectGUID'],
					'projectname'=>$item['CompanyProjectName'],
					'tbid'=>$item['TBID'],
					'tbprojid'=>$item['TBProjId'],
					);
				$this->_CI->objproject->SaveProjects($data);
			}
		}
	}
	
	/**
	 * 根据日期拉取日期内的通知公告信息
	 * @param string $date
	 */
	function GetCustomerNotices($date){
		$body = $this->ExecuteHttpResponse('customer/getnotices/', array('publishdate'=>$date), REQUEST_METHOD_GET);
		if ($body){
			foreach ($body as $item){
				$this->_CI->objnotice->SaveCustomerNotices($item);
			}
		}
	}
}