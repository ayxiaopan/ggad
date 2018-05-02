<?php

class HttpHelper{
	
	protected $_timeout = 30;
	
	/**
	 * @example RunHttpRequestUrl('http://localhost:51718/customer/getaccountinfo',array('UserName'=>'YinHelei'), 'GET', array('MLAPI-TOKEN : BB13AC2E-2BC7-4A19-9BEC-C473260F539A'));
	 * @param unknown $url
	 * @param unknown $fields
	 * @param array $heads
	 * @return string|unknown
	 */
	function RunHttpRequestUrl($url, $fields, $method = 'POST', $heads = array()){
		$ch = curl_init();
		
		if (!empty($fields)){
			if (strtoupper($method) == 'POST'){
				curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
			}
			else{
				//根据url判断参数连接字符用?还是&
				if (strpos($url, '?')===FALSE){
					$uchar = '?';
				}
				else{
					$uchar = '&';
				}
				
				$url = $url.$uchar.http_build_query($fields);
			}
		}
		
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		if ($heads){
			curl_setopt($ch, CURLOPT_HTTPHEADER, $heads);
		}
		
		curl_setopt($ch, CURLOPT_TIMEOUT, $this->_timeout);
		
		$response = curl_exec($ch);
		
		if (curl_errno($ch)) {
			return curl_error($ch);
		}
		
		curl_close($ch);
		
		return $response;
	}
	
}