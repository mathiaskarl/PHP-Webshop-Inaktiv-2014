<?php
	if(isset($_GET['p']) && $_GET['p'] == "webshop") {
	echo "
	<div id='user'>
		<div id='user_top'></div>
		<div id='user_content'>
			<div style='width:155px;margin-left:32px;'>
			<ul id='user_menu'>
				<li><a href='?p=webshop'>Mine order</a></li>
				<li><a href='?p=webshop'>Brugeroplysninger</a></li>
				<li><a href='?p=webshop'>Log af</a></li>
			</ul>
			</div>
		</div>
		<div id='user_bottom'></div>
	</div>";
	}
?>