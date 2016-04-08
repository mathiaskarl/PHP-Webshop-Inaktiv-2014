<?php
	if(isset($_GET['p']) && $_GET['p'] == "webshop") {
	echo "
	<div id='categories'>
		<div id='categories_top'></div>
		<div id='categories_content'>
			<div style='width:155px;margin-left:32px;'>
			<ul id='categories_menu'>";
			$sql = mysql_query("SELECT * FROM j_categories WHERE id != '99999' AND id != '99998' ORDER BY `order_id` ASC") or die(mysql_error());
			while($show = mysql_fetch_array($sql)) {
				echo "<li><a href='?p=webshop&d=categories&cid=".$show['id']."'>".$show['name']."</a></li>";
			}
			echo "
			</ul>
			<ul id='categories_menu' style='margin-top:10px;'>
				<li><a href='?p=webshop&d=categories&cid=99998'>Alle produkter</a></li>
				<li><a href='?p=webshop&d=categories&cid=99999'>Solgte produkter</a></li>
			</ul>
			</div>
		</div>
		<div id='categories_bottom'></div>
	</div>";
	}
?>