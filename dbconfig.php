<?php


	$db_host = "localhost";
	$db_name = "sicav2";
	$db_user = "jhernandez";
	$db_pass = "nene14";
//require("../final.php");
	try{
/*
		$link = @mysql_connect($Servidor,$Usuario, $Password,'',65536) or die ("<p /><br /><p /><div style='text-align:center'>Disculpe... En estos momentos no hay conexión con el servidor, estamos realizando modificaciones.... inténtalo más tarde. Gracias....</div>");
	mysql_select_db('sica', $link);
*/
		$db_con = new PDO("mysql:host={$db_host};dbname={$db_name};charset=utf8",$db_user,$db_pass);
		$db_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		// $db_con->execute("set names utf8");
		// $db_con->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
	}
	catch(PDOException $e){
		echo $e->getMessage();
		// echo 'Fallo la conexion';
	}

?>