<?php

class JScript {
	
	const BSCRIPT = '<script language="javascript">';
	const ESCRIPT = '</script>';
	
	/**
	 * 提示框
	 * @param string $messages
	 */
	public function Alert($messages){
		echo self::BSCRIPT;
		echo 'alert("'.$messages.'");';
		echo self::ESCRIPT;
		exit;
	}
	
	/**
	 * 适用于POP弹出窗口提交数据成功后刷新父页面
	 *
	 * @param string $alertmsg 消息提示内容
	 */
	public function AlertAndReload($alertmsg){
		echo '<script language="javascript">';
		echo 'alert("'.$alertmsg.'");';
		echo 'window.parent.location.href=window.parent.location.href;';
		echo '</script>';
		exit;
	}

	/**
	 * 适用于POP弹出窗口提交数据成功后关闭弹出窗口
	 *
	 * @param string $alertmsg 消息提示内容
	 */
	public function AlertAndClose($alertmsg, $ispop = false){
		echo '<script language="javascript">';
		echo 'alert("'.$alertmsg.'");';
		if ($ispop){
			echo 'window.parent.ClosePopWindow();';
		}
		else{
			echo 'window.close();';
		}
		echo '</script>';
		exit;
	}
	
	/**
	 * 提示框+跳转
	 * @param string $messages
	 * @param string $url
	 */
	public function AlertAndRedirect($messages, $url){
		echo self::BSCRIPT;
		echo 'alert("'.$messages.'");';
		echo 'location.replace("'.$url.'");';
		echo self::ESCRIPT;
		exit;
	}
	
	/**
	 * 根据对话框重定义到指定的页面中
	 * @param string $messages
	 * @param string $trueurl
	 * @param string $falseurl
	 */
	public function ConfirmGoTo($messages, $trueurl, $falseurl){
		echo self::BSCRIPT;
		echo 'if (confirm("'.$messages.'")){';
		echo 'window.location="'.$trueurl.'";}';
		echo 'else{';
		echo 'window.location="'.$falseurl.'";}';
		echo self::ESCRIPT;
		exit;
	}
	
	/**
	 * 提示框+返回上一页
	 * @param string $messages
	 */
	public function AlertAndGoBack($messages, $loop = -1){
		echo self::BSCRIPT;
		echo 'alert("'.$messages.'");';
		echo 'history.go('.$loop.');';
		echo self::ESCRIPT;
		exit;
	}
}