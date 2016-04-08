<?php
session_start();
require "../include/include.php";

function return_error($var) {
	$json_array["status"] = $var;
	return json_encode($json_array);
}

switch($_GET['t']) {
	default:
		break;
	
	case 'color':
		if(isset($_GET['id']) && $_GET['id'] > 0 && isset($_GET['cid']) && $_GET['cid'] > 0) {
			$sql = mysql_query("SELECT j_products_size.name as item_size, j_products_size.id as item_size_id
									FROM j_products_stock
									INNER JOIN j_products_size ON j_products_size.id = j_products_stock.size_id
									WHERE j_products_stock.product_id = ". safe($_GET['id']) ." AND j_products_stock.color_id = ". safe($_GET['cid']));
			if(mysql_num_rows($sql) > 0) {
				$json_array = array();
				$json_array["dropdown"] =  "<option selected>Vælg størrelse</option>";
				while($show = mysql_fetch_array($sql)) {
					$json_array["dropdown"] .=  "<option value='".$show['item_size_id']."'>".$show['item_size']."</option>";
				}
				echo json_encode($json_array);
			} else {
				echo return_error("error");
			}
		} else {
			echo return_error("error");
		}
		break;
	
	case 'size':
		if(isset($_GET['id']) && $_GET['id'] > 0 && isset($_GET['cid']) && $_GET['cid'] > 0 && isset($_GET['sid']) && $_GET['sid'] > 0) {
			$sql = mysql_query("SELECT * from j_products_stock WHERE product_id = '".safe($_GET['id'])."' AND color_id = '".safe($_GET['cid'])."' AND size_id = '".safe($_GET['sid'])."'") or die(mysql_error());
			if(mysql_num_rows($sql) > 0) {
				$show = mysql_fetch_array($sql);
				$json_array = array();
				$json_array["dropdown"] = "";
				for($i = 0 ; $i < $show['stock']+1 ; $i++) {
					$json_array["dropdown"] .= "<option value='".$i."'>".$i."</option>";
				}
				$json_array["id"] = $show['id'];
				$json_array["total_stock"] = $show['stock'];
				echo json_encode($json_array);
			} else {
				echo return_error("error");
			}
		} else {
			echo return_error("error");
		}
		break;
}
?>
