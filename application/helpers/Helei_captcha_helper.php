<?php

if ( ! function_exists('create_login_captcha'))
{
	/**
	 * 生成登录验证码专用
	 * @param number $width 宽
	 * @param number $height 高
	 * @param number $length 字符长度
	 * 
	 */
	function create_login_captcha($width = 60, $height = 30, $length = 4){
		$allchar = array('0','1','2','3','4','5','6','7','8','9');
		$randomcode = '';
		for($i=0;$i<$length;$i++){
			$tempid = mt_rand(0,count($allchar)-1);
			$randomcode.=$allchar[$tempid];
		}
		
		//将验证码写入Session
		$CI =& get_instance();
		$CI->load->library('session');
		$CI->session->captchacode = $randomcode;
		
		Header("Content-type: image/PNG");
		
		$im = imagecreatetruecolor($width,$height);
		$black = ImageColorAllocate($im, 0,0,0);
		$gray = ImageColorAllocate($im, 238,232,244);
		$linecolor = imagecolorallocate($im, 195, 195, 195);
		
		imagefill($im,0,0,$gray);
		
		//绘制干扰线和点
		for ($l=0;$l<3;$l++){
			imageline($im, mt_rand(0, $width), mt_rand(0, $height), mt_rand(0, $width), mt_rand(0, $height), $linecolor);
			imagesetpixel($im, mt_rand(0,60), mt_rand(0,20), $black);
		}
		
		imagestring($im, 4, 10, 6, $randomcode, $black);
		imagepng($im);
		imagedestroy($im);
	}
}