<?php

class menu {

	private $_menu_array = array();
	private $_page_array = array();
	
	public function __construct() {
		if($this->init_menu()) {
			$this->init_current_page();
		}
	}
	
	private function init_menu() {
		$sql = mysql_query("SELECT * FROM j_pages ORDER BY `order_id` ASC") or die(mysql_error());
		if(mysql_num_rows($sql) > 0) {
			while($output = mysql_fetch_array($sql)) {
				$this->_menu_array[$output['id']] = $output;
			}
			return true;
		}
		return false;
	}
	
	private function init_current_page() {
		if(isset($_GET['p'])) {
			$sql = mysql_query("SELECT * FROM j_pages WHERE name = '".safe($_GET['p'])."'") or die(mysql_error());
			if(mysql_num_rows($sql) > 0) {
				$this->_page_array = mysql_fetch_array($sql);
				return true;
			}
			return false;
		}
		return false;
	}
	
	public function return_menu() {
		if(!empty($this->_menu_array)) {
		return $this->_menu_array;
		}
	}
	
	public function return_current_page() {
		if(!empty($this->_page_array)) {
			return $this->_page_array;
		}
	}
	
	public function content_type_page() {
		if(empty($this->_page_array) || $this->_page_array['type'] != "page") {
			return false;
		}
		if($this->_page_array['type'] == "page") {
			return true;
		}
	}
	
	public function show_content() {
		$page_id = ($this->_page_array['id'] > 0 ? $this->_page_array['id'] : "1");
		$sql = mysql_query("SELECT `content` FROM j_content WHERE page_id = '".$page_id."'") or die(mysql_error());
		$return = mysql_fetch_array($sql);
		return $return['content'];
	}
}