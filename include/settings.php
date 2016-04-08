<?php

class settings {
	private $_sql = array();
	
	public function __construct() {
		$this->_sql = mysql_query("SELECT * FROM j_settings WHERE id = '1'") or die(mysql_error()); 
	}
	
	public function return_settings() {
		return mysql_fetch_array($this->_sql);
	}
}