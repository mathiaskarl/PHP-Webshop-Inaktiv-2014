<?php

class cart {
	
	private $_session = array();
	private $_cart = array();
	private $_row_id;
	
	private $_data = array();
	
	public $error_msg;
	
	
	public function __construct() {
		if($this->session_contains()) {
			$this->_cart = $_SESSION['cart'];
			$this->_session = $_SESSION['cart'];
		}
	}
	
	public function store_data($array = array()) {
		foreach($array as $key => $value) {
			$this->_data[$key] = $value;
		}
	}
	
	public function insert($array = array()) {
		if(count($array) > 0 && is_array($array)) {
			$this->store_data($array);
			
			if(!$this->in_cart()) {
				if($this->_data["stock"] < 1) {
					$this->error_msg = "Ikke flere på lager";
					return false;
				}
				
				$this->_cart[$this->create_row_id()] = array ('row_id' => $this->_row_id);
				foreach ($this->_data as $key => $value) {
					$this->_cart[$this->_row_id][$key] = $value;
				}
				$this->session_save();
				return true;
			}
			$this->update();
			return true;
		}
		$this->error_msg = "Ingen data indsat";
		return false;
	}
	
	
	public function update() {
		foreach($this->_session as $key => $value) {
			if($this->_data['id'] == $value['id']) {
				$this->_cart[$key]['qty'] = ($value['qty']+$this->_data['qty']);
				$this->session_save();
			}
		}
	}
	
	public function remove($array = array()) {
		if(count($array) > 0 && is_array($array)) {
			$this->store_data($array);
			
			if($this->in_cart()) {
				foreach($this->_session as $key => $value) {
					if($this->_data['id'] == $value['id']) {
						$this->_cart[$key]['qty'] = ($value['qty']-$this->_data['qty']);
						if($this->_cart[$key]['qty'] < 1 || $this->_cart[$key]['qty'] == 0) {
							unset($this->_cart[$key]);
						}
						$this->session_save();
						return true;
					}
				}
			}
			$this->error_msg = "Id'et eksisterer ikke i kurven";
			return false;
		}
		$this->error_msg = "Ingen data indsat";
		return false;
	
	}
	
	public function in_cart() {
		foreach($this->_session as $key => $value) {
			if(in_array($this->_data['id'], $value)) {
				return true;
			}
		}
		return false;
	}
	
	//
	// Extra
	//
	private function create_row_id() {
		$random_string = "abcdefghijklmnopqrstuvxy1234567890";
		$output = "";
		for($i = 0; $i < 20; $i++) {
			$x = rand(0, (strlen($random_string)-1));
			$output .= substr($random_string, $x, 1);
		}
		$this->_row_id = $output;
		return $output;
	}
	
	public function return_error() {
		return $this->error_msg;
	}
	
	public function return_subtotal() {
		$sub_total = 0;
		foreach($this->_session as $key => $value) {
			$sub_total = $sub_total+($value['price']*$value['qty']);
		}
		return $sub_total;
	}
	
	//
	// Session handeling
	//
	public function session_exists() {
		if(isset($_SESSION['cart'])) {
			return true;
		}
		return false;
	}
	
	public function session_contains() {
		if(!$this->session_exists()) {
			return false;
		}
		if(count($_SESSION['cart']) == 0) {
			$this->session_unset();
			return false;
		}
		return true;
	}
	
	public function session_save() {
		unset($_SESSION['cart']);
		$_SESSION['cart'] = $this->_cart;
	}
	
	public function session_unset() {
		unset($_SESSION['cart']);
		$_session = null;
	}
	
	public function session_create($array = array()) {
		$_SESSION['cart'] = array();
	}
}

?>