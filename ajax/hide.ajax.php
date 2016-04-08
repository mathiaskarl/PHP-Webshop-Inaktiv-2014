<?php
session_start();
$_SESSION['hide'] = ($_SESSION['hide'] == 1 ? 0 : 1);
?>
