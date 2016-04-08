<?php
switch($_GET['d']) {	
	case "test":
		$insert_array = array('id' => '4', 'stock' => '1', 'name' => 'Testprodukt', 'qty' => '1', 'price' => '19,00');
		$remove_array = array('id' => '4', 'qty' => '1');
		$cart->insert($insert_array);
		echo $cart->return_subtotal();
		echo "Session: ". $_SESSION['cart'] ." <br /><br /> Error: ". $cart->return_error() ." <br /> <br /><pre>";
		print_r($_SESSION['cart']);
	break;
	
	case "categories":
	if(isset($_GET['cid']) && is_numeric($_GET['cid'])) {
		$cat_sql = mysql_query("SELECT * FROM j_categories WHERE id = '".safe($_GET['cid'])."'") or die(mysql_error());
		if(mysql_num_rows($cat_sql) > 0) {
			$products = ($_GET['cid'] == "99998" ? "j_products.cat_id != '99999'" : "j_products.cat_id = '".safe($_GET['cid'])."'");
			$show_cat = mysql_fetch_array($cat_sql);
			$sort_order = ($_GET['sort'] == "newest" ? "ORDER BY j_products.id DESC" : ($_GET['sort'] == "price" ? "ORDER BY j_products.price ASC, j_products.price_sale DESC" : ($_GET['sort'] == "name" ? "ORDER BY j_products.name ASC" : "ORDER BY j_products.id DESC")));
			echo "
			<div style='height:24px;'>
				<div class='line_title'  style='width:200px;float:left;'>".$show_cat['name']."</div>
				<div class='sort_box'>
					<a href='?p=webshop&d=categories&cid=".$show_cat['id']."&sort=newest' class='button_sort_"; if($_GET['sort'] != "price" && $_GET['sort'] != "name") { echo "black"; } else { echo "white"; } echo "'>Nyeste</a>
					<a href='?p=webshop&d=categories&cid=".$show_cat['id']."&sort=price' class='button_sort_"; if($_GET['sort'] == "price") { echo "black"; } else { echo "white"; } echo "'>Pris</a>
					<a href='?p=webshop&d=categories&cid=".$show_cat['id']."&sort=name' class='button_sort_"; if($_GET['sort'] == "name") { echo "black"; } else { echo "white"; } echo "'>Navn</a>
				</div>
				<div style='clear:both;'></div>
			</div>
			<div class='breadcrumps'><a href='?p=webshop'>Startside</a> » <a href='?p=webshop&d=categories&cid=".$show_cat['id']."'>".$show_cat['name']."</a></div>";
			
			$query = mysql_query("SELECT j_products.price, j_products.price_sale, j_products.product_id, j_products.name, j_products_images.image AS image FROM j_products
								  LEFT JOIN j_products_images ON j_products_images.product_id = j_products.product_id
								  WHERE ".$products." GROUP BY j_products.price, j_products.price_sale, j_products.product_id, j_products.name") or die(mysql_error());
			$numSql = (mysql_num_rows($query) > 0 ? mysql_num_rows($query) : 0);
			$rowsperpage = 18;
			$totalpages = ceil($numSql / $rowsperpage);
			$currentpage = (isset($_GET['s']) && is_numeric($_GET['s']) ? (int) $_GET['s'] : 1);
			$currentpage = ($currentpage > $totalpages ? $totalpages : $currentpage);
			$currentpage = ($currentpage < 1 ? 1 : $currentpage);
			$offset = ($currentpage - 1) * $rowsperpage;

			$query = mysql_query("SELECT j_products.id, j_products.price, j_products.price_sale, j_products.product_id, j_products.name, j_products_images.image AS image FROM j_products
								  LEFT JOIN j_products_images ON j_products_images.product_id = j_products.product_id
								  WHERE ".$products." GROUP BY j_products.id, j_products.price, j_products.price_sale, j_products.product_id, j_products.name ".$sort_order." LIMIT $offset, $rowsperpage") or die(mysql_error());
			$range = 2;
			$showPage = "<ul class='pagination'>";
			if ($currentpage > 1) {
				$prevpage = $currentpage-1;
				$showPage .= "<li><a href='?p=webshop&d=categories&cid=".$show_cat['id']."&sort=".$_GET['sort']."&s=$prevpage'>«</a></li>";
			} else {
				$showPage .= "<li><span>«</span></li> ";
			}
			for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++) {
				if (($x > 0) && ($x <= $totalpages)) {
					if ($x == $currentpage) {
						$showPage .= "<li><span><u>$x</u></span></li>";
					} else {
						$showPage .= "<li><a href='?p=webshop&d=categories&cid=".$show_cat['id']."&sort=".$_GET['sort']."&s=$x'>$x</a></li>";
					} 
				}
			}                  
			if ($currentpage != $totalpages && $rowsperpage < $numSql) {
			   $nextpage = $currentpage + 1;
			   $showPage .= "<li><a href='?p=webshop&d=categories&cid=".$show_cat['id']."&sort=".$_GET['sort']."&s=$nextpage'>»</a></li> ";
			} else {
				$showPage .= "<li><span>»</span></li> ";
			}
			$showPage .= "</ul>";

			if(mysql_num_rows($query) > 0) {
				while($show = mysql_fetch_array($query)) {
					$image = ($show['image'] != null ? $show['image'] : "test.png");
					$price = ($show['price_sale'] > 0 ? $show['price_sale'] : $show['price']);
					echo "
					<div class='product_front'>";
						if($show['price_sale'] > 0) {
						echo "<div class='product_offer'>Tilbud!</div>";
						}
						echo "
						<div class='product_content'>
						<a href='?p=webshop&d=item&id=".$show['product_id']."'>
						<img src='images/products/thumb/".$image."'><br />
						".$show['name']."</a></div>
						<div class='product_price'>".$price.",00 ,-</div>
						<div class='product_add'><a href='?p=webshop&d=item&id=".$show['product_id']."'>Mere info</a></div>
						<div style='clear:both;'></div>
					</div>";
				}
				echo "<div style='clear:both;'></div>
					 <div style='width:300px;text-align:center;margin: 0px auto;'>".$showPage."</div>";
			} else {
				echo "Der findes pt. ingen produkter under denne kategori.";
			}
		} else {
			echo "
			<script type='text/javascript'>
			   window.location = '?p=webshop'
			</script>";
		}
	} else {
		echo "
		<script type='text/javascript'>
           window.location = '?p=webshop'
	    </script>";

	}
	break;
	
	case "item":
	if(isset($_GET['id']) && is_numeric($_GET['id'])) {
		$sql = mysql_query("SELECT j_products.*, j_categories.name as cat_name FROM j_products
								INNER JOIN j_categories ON j_categories.id = j_products.cat_id
								WHERE j_products.product_id = '".safe($_GET['id'])."'") or die(mysql_error());
		if(mysql_num_rows($sql) > 0) {
			$show = mysql_fetch_array($sql);
			$ajax_sql = mysql_query("SELECT j_products_color.name as item_color, j_products_color.id as item_color_id, j_products_size.name as item_size, j_products_size.id as item_size_id, j_products_stock.stock as stock
									FROM j_products_stock
									RIGHT JOIN j_products_color ON j_products_color.id = j_products_stock.color_id
									RIGHT JOIN j_products_size ON j_products_size.id = j_products_stock.size_id
									WHERE j_products_stock.product_id = ". $show['product_id']);
			$item_stock = (mysql_num_rows($ajax_sql) > 0 ? true : false);
			echo "
			<form name='submit' method='post' id='form' action=''>
			
			<div class='line_title'  style='width:200px;'>".$show['name']."</div>
			<div class='breadcrumps'><a href='?p=webshop'>Startside</a> » <a href='?p=webshop&d=categories&cid=".$show['cat_id']."'>".$show['cat_name']."</a> » ".$show['name']."</div>
			<div id='item_images'>";
			$sql_image = mysql_query("SELECT * FROM j_products_images WHERE product_id = '".$show['product_id']."' AND main = '1'") or die(mysql_error());
			$show_image = mysql_fetch_array($sql_image);
			$main_image = (!empty($show_image['image']) ? $show_image['image'] : "test.png");
			echo "
			<div id='item_image_big'>
				<img src='images/products/full/".$main_image."' width='250' height='250'>
			</div>";
			$sql_images = mysql_query("SELECT * FROM j_products_images WHERE product_id = '".$show['product_id']."' AND main != '1'") or die(mysql_error());
			echo "<div id='item_image_small'>";
			while($show_thumb_image = mysql_fetch_array($sql_images)) {
				echo "<div class='item_image_small'><img src='images/products/thumb_small/".$show_thumb_image['image']."'></div>";
			}
			echo "
				</div>
			</div>
			<input type='hidden' id='hidden_id' name='hidden_id' value='".$show['product_id']."'>
			<input type='hidden' id='hiden_stock_id' name='hidden_stock_id'>
			<div id='line_spacer'></div>
			<div id='item_settings'>
				<table id='item_table'>
					<tr>
					<td style='width:150px;'>Produkt:</td>
					<td style='width:200px;'>". $show['name'] ."</td>
					</tr>
					<tr>
					<td>Status:</td>";
					if($item_stock) {
					echo "<td style='font-weight:bold;color:#74c561;'>På lager</td>";
					} else {
					echo "<td style='font-weight:bold;color:#de7575;'>Solgt</td>";
					}
					echo "
					</tr>
					<tr>
					<td>Farve:</td>
					<td>
					<label>
						<select name='color' id='selectbox_color' style='width:120px;float:left;'>
							<option selected>Vælg farve</option>";
							if($item_stock) {
								$color_array[] = "unindentified";
								while ($ajax_show = mysql_fetch_array($ajax_sql)) {
									if(!in_array($ajax_show["item_color_id"], $color_array)) {
										echo "
										<option value='".$ajax_show['item_color_id']."'>".$ajax_show['item_color']."</option>";
										$color_array[] = $ajax_show['item_color_id'];
									}
								}
							}
						echo "
						</select>
						<img id='color_load_img' src='images/ajax-loader.gif' style='display:none;float:left; margin: 3px 0px 0px 10px;'>
					</label>
					</td>
					</tr>
					<tr>
					<td>Størrelse:</td>
					<td>
					<label>
						<select disabled id='selectbox_size' style='width:120px;float:left;'>
							<option selected>Vælg størrelse</option>
						</select>
						<img id='size_load_img' src='images/ajax-loader.gif' style='display:none;float:left; margin: 3px 0px 0px 10px;'><div style='clear:both;'></div>
					</label>
					</td>
					</tr>
					<tr>
					<td>Specifikationer:</td>
					<td>Se nedenfor</td>
					</tr>
					<tr>
					<td>Antal:</td>
					<td>
					<input type='hidden' id='stock_amount' name='stock_amount'>
					<label>
						<select disabled id='selectbox_stock' style='width:50px;'>
							<option selected>0</option>
						</select>
					</label>
					</td>
					</tr>
					<tr>
					<td valign='top' style='vertical-align:top;'>Pris pr. stk.:</td>
					<td style='font-weight:bold;'>";
					$sale_span = ($show['price_sale'] > 0 ? "style='text-decoration:line-through;'" : "");
					$sale_price = ($show['price_sale'] > 0 ? "<br /><span style='color:#ed6f79;'>".$show['price_sale'].",00 DKK</span>" : "");
					$final_price = ($show['price_sale'] > 0 ? $show['price_sale'] : $show['price']);
					echo "<span ".$sale_span.">".$show['price'].",00 DKK</span>".$sale_price;
					echo "
					<input type='hidden' id='price_amount' name='price_amount' value='".$final_price."'>
					</td>
					</tr>
					<tr>
					<td>Fragt:</td>
					<td style='font-weight:bold;'>".$settings['shipping_price'].",00 DKK</td>
					</tr>
					<tr>
					<td>";
					if($item_stock) {
					echo "<a href='Javascript:;' id='add_item' class='button_green' style='margin-top:15px;'>Læg i kurv</a>";
					} else {
					echo "<a href='Javascript:;' class='button_grey' style='margin-top:15px;'>Udsolgt</a>";
					}
					echo "
					</td>
					<td></td>
					</tr>
				</table>
			</div>
			<div style='clear:both;'></div>
			
			<div class='line_title'  style='width:200px;margin-top:50px;'>Beskrivelse</div>
			<div style='padding: 2px 9px;'>".$show['description']."</div>
			
			<div class='line_title'  style='width:200px;margin-top:50px;'>Specifikationer</div>
			<div style='padding: 2px 9px;'>".$show['specs']."</div>
			
			</form>";
		} else {
			echo "
			<script type='text/javascript'>
			   window.location = '?p=webshop'
			</script>";
		}
	} else {
		echo "
		<script type='text/javascript'>
           window.location = '?p=webshop'
	    </script>";
	}
	break;
	
	default:
	echo "
	<div class='line_title'  style='width:200px;'>Nyeste produkter</div>";
	
	$sql_newest = mysql_query("SELECT j_products.price, j_products.price_sale, j_products.product_id, j_products.name, j_products_images.image AS image FROM j_products
							   LEFT JOIN j_products_images ON j_products_images.product_id = j_products.product_id
							   WHERE j_products.cat_id != '99999' GROUP BY j_products.price, j_products.price_sale, j_products.product_id, j_products.name ORDER BY j_products.id DESC LIMIT 6") or die(mysql_error());
	while($show_newest = mysql_fetch_array($sql_newest)) {
		$image = ($show_newest['image'] != null ? $show_newest['image'] : "test.png");
		$price = ($show_newest['price_sale'] > 0 ? $show_newest['price_sale'] : $show_newest['price']);
		echo "
		<div class='product_front'>";
			if($show_newest['price_sale'] > 0) {
			echo "<div class='product_offer'>Tilbud!</div>";
			}
			echo "
			<div class='product_content'>
			<a href='?p=webshop&d=item&id=".$show_newest['product_id']."'>
			<img src='images/products/thumb/".$image."'><br />
			".$show_newest['name']."</a></div>
			<div class='product_price'>".$price.",00 ,-</div>
			<div class='product_add'><a href='?p=webshop&d=item&id=".$show_newest['product_id']."'>Mere info</a></div>
			<div style='clear:both;'></div>
		</div>";
	}
	echo "
	<div style='clear:both;'></div>
	
	<div class='line_title'  style='width:200px;margin-top:50px;'>Udvalgte produkter</div>
	
	<div style='width:100%;height:160px;padding-top:15px;position:re'>
	<div style='float:left;width:5%;'><a class='btn prev'><div id='arrow_prev'></div></a></div>
	
	<div style='float:left;width:90%;'>
	  <div id='slider' class='owl-carousel'>";
		
		$sql_highlight = mysql_query("SELECT j_products.product_id, j_products.name, j_products_images.image AS image FROM j_products
							   LEFT JOIN j_products_images ON j_products_images.product_id = j_products.product_id
							   WHERE j_products.highlight = '1' GROUP BY j_products.product_id, j_products.name") or die(mysql_error());
	    while($show_highlight = mysql_fetch_array($sql_highlight)) {
		$image = ($show_highlight['image'] != null ? $show_highlight['image'] : "test.png");
		echo "<a href='?p=webshop&d=item&id=".$show_highlight['product_id']."'><div class='item product_slide'><img src='images/products/thumb/".$image."'><div class='overlay'>".$show_highlight['name']."</div></div></a>";
		}
	echo "
	  </div>
	</div>
	
	<div style='float:right;width:5%;'><a class='btn next'><div id='arrow_next'></div></a></div>
	<div style='clear:both;'></div>
	</div>";
	break;
}


?>