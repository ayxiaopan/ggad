<?php
/**
 * 留言表
 * @author Suop
 *
 */
class GuestBookModel extends CI_Model{
	
	protected $_name = 'guestbook';
	protected $_primary = 'gbid';
	
	function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->library(array('encryption'));
	}
	
	/**
	 * 保存留言数据
	 * @param unknown $data
	 */
	function SaveGuestBooks($data){
		//生成留言唯一GUID
		$gbid = create_guid();
		$data['gbid'] = $gbid;
		//-----------------------------------
		$data['shortdate']	 = date('Y-m-d');
		$data['postdate']	 = date('Y-m-d H:i:s');

		//写入数据库
		$this->db->insert($this->_name, $data);
	}
	
	/**
	 * 客户后台获取留言列表
	 * @param unknown $count
	 * @param unknown $guids
	 * @param unknown $fields
	 * @param unknown $where
	 * @param unknown $order
	 * @param unknown $page
	 * @param unknown $limit
	 * @return unknown
	 */
	function GetGuestBooksWithGuid($count, $guids, $fields = NULL, $where = NULL, $order = NULL, $page = NULL, $limit = NULL){
		//如果项目GUIDS为空，则强制什么也不显示，否则查询出错。
		if (empty($guids)){
			echo 'Error No Datas!';
			exit;
		}
		
		if ($count){
			$this->db->where_in('projectguid', $guids);
			if (!empty($where)) {
				$this->db->where($where);
			}
			return $this->db->count_all_results($this->_name);
		}
		else{
			if (!empty($fields)){
				$this->db->select($fields);
			}
			else{
				$this->db->select('*');
			}
			
			$this->db->from($this->_name);
			$this->db->where_in('projectguid', $guids);
			
			if (!empty($where)) {
				$this->db->where($where);
			}
			
			if (!empty($page) && !empty($limit)){
				$this->db->limit($limit, ($page - 1) * $limit);
			}
			
			if (!empty($order)){
				$this->db->order_by($order);
			}
			
			$query = $this->db->get();
			return $query->result_array();
		}
	}
}