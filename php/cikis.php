<?php 
	session_start();
	session_unset();
	session_destroy();
	header("Location: /kutuphane/giris.html");
?>