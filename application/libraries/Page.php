<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page {
	
	/**
	 * 动态创建翻页
	 * @param unknown $currentpageindex
	 * @param unknown $recordcount
	 * @param unknown $pagesize
	 * @param string $url
	 * @param string $prefix 翻页拼接参数
	 * @param number $pageshowsize
	 * @return string
	 */
	public function CreatePageNavV3($currentpageindex, $recordcount, $pagesize, $url='', $prefix='p', $pageshowsize = 5, $ext = ''){
		$html = '<ul class="am-pagination am-pagination-centered">';
		$pagecount = ceil($recordcount/$pagesize);//计算页码
		
		//页面显示尺寸默认为5页
		//$pageshowsize = 10;
		$bs = ceil($currentpageindex/$pageshowsize);//倍数
		
		//计算下5页数是否大于总页数
		$ps = $bs * $pageshowsize;
		if ($ps > $pagecount){
			$ps = $pagecount;
		}
		
		if ($currentpageindex > $pageshowsize){
			$pre5page = ($bs - 2) * $pageshowsize + 1;
			$pre5urls = $url.$prefix.$pre5page.$ext;
			$html.= '<li><a href="'.$pre5urls.'">上'.$pageshowsize.'页</a></li>';
		}
		
		if ($currentpageindex == 1){
			$html.= '<li class="am-disabled"><a href="#">&laquo;</a></li>';
		}
		else{
			$html.= '<li><a href="'.$url.$prefix.($currentpageindex-1).$ext.'">&laquo;</a></li>';
		}
		
		$i = ($bs - 1) * $pageshowsize + 1;
		for ($i; $i <= $ps; $i++){
			if ($currentpageindex == $i){
				$html.= '<li class="am-active"><a>'.$i.'</a></li>';
			}
			else{
				$html.= '<li><a href="'.$url.$prefix.$i.$ext.'">'.$i.'</a></li>';
			}
		}
		
		if ($currentpageindex == $pagecount){
			$html.= '<li class="am-disabled"><a href="#">&raquo;</a></li>';
		}
		else{
			$html.= '<li><a href="'.$url.$prefix.($currentpageindex + 1).$ext.'">&raquo;</a></li>';
		}
		
		//超过5页显示按钮下5页
		if ($pagecount > $ps){
			$html.= '<li><a href="'.$url.$prefix.($bs * $pageshowsize + 1).$ext.'">下'.$pageshowsize.'页</a></li>';
		}
		
		$html.= '<li>共 <label class="am-text-warning">'.$recordcount.'</label> 条数据</li>';
		
		$html.= '</ul>';
		return $html;
	}
	
	/**
	 * 动态创建翻页导航
	 * @param unknown $currentpageindex
	 * @param unknown $pagecount
	 * @param unknown $pageurl
	 */
	public function CreatePageNavV2($currentpageindex, $recordcout, $pagesize, $parms = array(), $url='/s', $pageshowsize = 10){
		$html = '<div class="feed-card-page" style="display: block;">';
		$pagecount = ceil($recordcout/$pagesize);//计算页码
		
		//页面显示尺寸默认为5页
		//$pageshowsize = 10;
		$bs = ceil($currentpageindex/$pageshowsize);//倍数
	
		//计算下5页数是否大于总页数
		$ps = $bs * $pageshowsize;
		if ($ps > $pagecount){
			$ps = $pagecount;
		}
	
		if ($currentpageindex > $pageshowsize){
			$pre5page = ($bs - 2) * $pageshowsize + 1;
			$pre5urls = $url.'?'.http_build_query($parms).'&p='.$pre5page;
			$html.= '<span class="pagebox_pre pre5"><a href="'.$pre5urls.'">上'.$pageshowsize.'页</a></span>';
		}
	
		if ($currentpageindex == 1){
			$html.= '<span class="pagebox_pre_nolink">上一页</span>';
		}
		else{
			$html.= '<span class="pagebox_pre"><a href="'.$url.'?'.http_build_query($parms).'&p='.($currentpageindex-1).'">上一页</a></span>';
		}
	
		$i = ($bs - 1) * $pageshowsize + 1;
		for ($i; $i <= $ps; $i++){
			if ($currentpageindex == $i){
				$html.= '<span class="pagebox_num_nonce">'.$i.'</span>';
			}
			else{
				$html.= '<span class="pagebox_num"><a href="'.$url.'?'.http_build_query($parms).'&p='.$i.'">'.$i.'</a></span>';
			}
		}
	
		if ($currentpageindex == $pagecount){
			$html.= '<span class="pagebox_next_nolink">下一页</span>';
		}
		else{
			$html.= '<span class="pagebox_next"><a href="'.$url.'?'.http_build_query($parms).'&p='.($currentpageindex + 1).'">下一页</a></span>';
		}
	
		//超过5页显示按钮下5页
		if ($pagecount > $ps){
			$html.= '<span class="pagebox_next next5"><a href="'.$url.'?'.http_build_query($parms).'&p='.($bs * $pageshowsize + 1).'">下'.$pageshowsize.'页</a></span>';
		}
	
		$html.= '</div>';
		return $html;
	}
	
	/**
	 * 动态创建翻页导航
	 * @param unknown $currentpageindex
	 * @param unknown $pagecount
	 * @param unknown $pageurl
	 */
	public function CreatePageNav($currentpageindex, $pagecount, $filename = 'index'){
		$html = '<div class="feed-card-page" style="display: block;">';
		
		//页面显示尺寸默认为5页
		$pageshowsize = 5;
		$bs = ceil($currentpageindex/$pageshowsize);//倍数
		
		//计算下5页数是否大于总页数
		$ps = $bs * $pageshowsize;
		if ($ps > $pagecount){
			$ps = $pagecount;
		}
		
		if ($currentpageindex > $pageshowsize){
			$pre5page = ($bs - 2) * $pageshowsize + 1;
			$pre5urls = $pre5page == 1 ? $filename.'.shtml':$filename.'_'.$pre5page.'.shtml';
			$html.= '<span class="pagebox_pre pre5"><a href="'.$pre5urls.'">上'.$pageshowsize.'页</a></span>';
		}
		
		if ($currentpageindex == 1){
			$html.= '<span class="pagebox_pre_nolink">上一页</span>';
		}
		else{
			$html.= '<span class="pagebox_pre"><a href="'.$filename.(($currentpageindex-1) == 1 ? '':('_'.($currentpageindex-1))).'.shtml">上一页</a></span>';
		}
		
		$i = ($bs - 1) * $pageshowsize + 1;
		for ($i; $i <= $ps; $i++){
			if ($currentpageindex == $i){
				$html.= '<span class="pagebox_num_nonce">'.$i.'</span>';
			}
			else{
				$html.= '<span class="pagebox_num"><a href="'.$filename.($i == 1 ? '' : ('_'.$i)).'.shtml">'.$i.'</a></span>';
			}
		}
		
		if ($currentpageindex == $pagecount){
			$html.= '<span class="pagebox_next_nolink">下一页</span>';
		}
		else{
			$html.= '<span class="pagebox_next"><a href="'.$filename.'_'.($currentpageindex + 1).'.shtml">下一页</a></span>';
		}
		
		//超过5页显示按钮下5页
		if ($pagecount > $ps){
			$html.= '<span class="pagebox_next next5"><a href="'.$filename.'_'.($bs * $pageshowsize + 1).'.shtml">下'.$pageshowsize.'页</a></span>';
		}
		
		$html.= '</div>';
		return $html;
	}
}