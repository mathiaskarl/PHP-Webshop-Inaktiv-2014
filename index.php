<?php
session_start();
require "include/include.php";
require "include/menu.php";
require "include/cart.php";
require "include/settings.php";

$cart = new cart();
$menu = new menu();
$init_settings = new settings(); 
$settings = $init_settings->return_settings();
$current_page = $menu->return_current_page();
$webshop_css = ("webshop" == $current_page['url'] ? "<link rel='stylesheet' type='text/css' href='webshop.css'>" : "");
$front_css = ((("forside" == $current_page['url']) || ("" == $current_page['url'])) ? "<link rel='stylesheet' type='text/css' href='front.css'>" : "");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<ht<?php echo "m"; ?>l>
<head>
<title>Title</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" type="text/css" href="input.css">
	<link href="owl-carousel/owl.carousel.css" rel="stylesheet">
	<? echo $webshop_css;
	   echo $front_css; ?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="description" content="Description will come">
	<meta name="keywords" content="key words">
	<script src="jquery-1.11.1.min.js" type="text/javascript"></script>
	<script src="owl-carousel/owl.carousel.js"></script>
	<script src="scripts.js"></script>
</head>

<body>
	<div id="container">
		<div id="top">
			<div id="top_left"></div>
			<div id="top_right"></div>
			<div style="clear:both;"></div>
		</div>
		
		<div id="menu_container">
			<div id="menu">
				<ul id='nav'>
				<?php
				foreach($menu->return_menu() as $value) {
					echo "<li class='". $value[name] ."'><a href='?p=". $value['url'] ."' ";
					if($value['id'] == $current_page['id']) {
						echo "style='background-position:top center;'";
					}
					echo ">". $value['name'] ."</a></li>";
				}
				?>
				</ul>
			</div>
		</div>
		<div id="shadow_top"></div>
		
		<div id="content_top">
			<?php
			for ( $i = 1; $i < 6 ; $i++ ) {
				echo "<div id='bottom_".$i."'";
				if($current_page['id'] == $i) {
					echo " style='display:block;'";
				}
				echo "></div>";
			}
			?>
			<div id="content_splatter"></div>
		</div>
		
		<div id="content">
			<? 
			if(isset($_GET['p']) && $_GET['p'] == "webshop") {
				include('pages/categories.php');
				include('pages/user_bar.php');
			} ?>
			<div style="float:right;">
			<div id="postit_hidden" <?php echo $hide_post; ?>></div>
			</div>
			<div id="postit" <?php echo $display_post; ?>>
				<div id="postit_top"><div id="postit_kurv"></div><div id="paperclip"></div></div>
				<div id="postit_content">
					<div id="shop_cart">
					
						<div class="cart_item">
						<div class="cart_item_name">Maleri #5 - Farvet</div>
						<div class="cart_remove_item"></div>
						<div style="clear:both;"></div>
						<div class="cart_item_quantity">1 stk.</div>
						<div class="cart_item_price">499,00</div>
						<div style="clear:both;"></div>
						</div>
						
						<div class="cart_spacer"></div>
						
						<div class="cart_item">
						<div class="cart_item_name">Maleri #18 - Gråtonet</div>
						<div class="cart_remove_item"></div>
						<div style="clear:both;"></div>
						<div class="cart_item_quantity">1 stk.</div>
						<div class="cart_item_price">249,00</div>
						<div style="clear:both;"></div>
						</div>
						
						<div class="cart_spacer"></div>
						
						<div id="cart_total">
						<div class="cart_left">Varer ialt:</div>
						<div class="cart_right">748,00</div>
						<div style="clear:both;"></div>
						<div class="cart_left">Porto omk.</div>
						<div class="cart_right">35,00</div>
						<div style="clear:both;"></div>
						<div class="cart_left" style="font-weight:bold;">Total pris:</div>
						<div class="cart_right" style="font-weight:bold;">783,00</div>
						<div style="clear:both;"></div>
						</div>
					</div>
				</div>
				<div id="postit_bottom"></div>
			</div>
			
			
			<div id="content_text">
			<?php
			    if(!$menu->content_type_page()) {
				    echo $menu->show_content();
			    } else {
				    include("pages/".$current_page['url'].".php");
			    }
			    ?>
			</div>
		</div>
		
		<div id="content_bottom">
			<div id="page_footer"><? include("include/page_footer.php"); ?></div>
			<div id="search_container">
				<div style="margin-top:45px;margin-left:50px;">
				<input type="text" id="search_bar" name="search" value="Indtast produktnavn">
				<input type="submit" id="search_button" name="submit" value="&nbsp;">
				<div id="search_advanced"></div>
				</div>
			</div>
			<div style="clear:both;"></div>
		</div>
		<div id="shadow_bottom"></div>
		
		<div id="footer">
			<div id="footer_kontakt"></div>
			<div id="google"></div>
			<div id="twitter"></div>
			<div id="facebook"></div>
			
			<div id="footer_kontakt_text">
			<table>
			<tr><td style="width:50px;">Tlf:</td><td>+45 22334499</td></tr>
			<tr><td>Email:</td><td>email@gmail.com</td></tr>
			</table>
			</div>
			<div id="footer_info">
			Jacob Schöne <br />
			Rugvænget 19 <br />
			4100, Ringsted <br />
			CVR: 8829411231
			</div>
			<div style="clear:both;"></div>
		</div>
	</div>
</body>

</html>