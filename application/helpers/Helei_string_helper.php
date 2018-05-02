<?php

if ( ! function_exists('create_guid'))
{
	/**
	 * 生成唯一GUID
	 * @param bool $usenamespace 是否附加命名空间
	 * @return string
	 */
	function create_guid($usenamespace = TRUE){
		if (function_exists('com_create_guid')){
			return com_create_guid();
		}
		else{
			mt_srand((double)microtime()*10000);
			$exdata = uniqid(rand(), true);
			if ($usenamespace){
				$exdata .= $_SERVER['REQUEST_TIME'];
				if (isset($_SERVER['HTTP_USER_AGENT'])){
					$exdata .= $_SERVER['HTTP_USER_AGENT'];
				}
				if (isset($_SERVER['REMOTE_ADDR'])){
					$exdata .= $_SERVER['REMOTE_ADDR'];
				}
				if (isset($_SERVER['REMOTE_PORT'])){
					$exdata .= $_SERVER['REMOTE_PORT'];
				}
			}
			
			$charid = strtoupper(md5($exdata));
			$hyphen = chr(45);// "-"
			$uuid = substr($charid, 0, 8).$hyphen.substr($charid, 8, 4).$hyphen.substr($charid,12, 4).$hyphen.substr($charid,16, 4).$hyphen.substr($charid,20,12);
			return $uuid;
		}
	}
}