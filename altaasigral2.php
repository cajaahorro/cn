<?php
/* *********** COMPROBACIÓN Nº ASIENTO **************** */
try
{
	$sql="SELECT enc_clave FROM ".$_SESSION['institucion']."sgcaf830 WHERE enc_clave = :asiento";
	// )) AND $_GET['n'] == 1;
	$result=$db_con->prepare($sql);
	$result->execute([":asiento"=>$asiento]);
	if ($result->rowCount() < 1){
	//	$mensaje = "No se ha añadido el Asiento: Asiento <span class='b'>$asiento</span> ya existe.<p />";
	}

	$ip = la_ip();
	if (trim($mensaje) == "" ) {	// AND $asiento <= 9999999000

	//	$a=explode("/",$fecha); 
	//	$b=$a[2]."-".$a[1]."-".$a[0];
		$b=$fecha;
		$nomatach = $_FILES['fich']['name'];
		if ($nomatach) {
			$tipo1    = $_FILES["fich"]["type"];
			$archivo1 = $_FILES["fich"]["tmp_name"];
			$tamanio1 = $_FILES["fich"]["size"];
			$fp = fopen($archivo1, "rb");
		    $contenido = fread($fp, $tamanio1);
		    $contenido = addslashes($contenido);
		    fclose($fp);
		}
		$sql="SELECT enc_clave FROM ".$_SESSION['institucion']."sgcaf830 WHERE enc_clave = :asiento";
		$result=$db_con->prepare($sql);
		$result->execute([":asiento"=>$asiento]);
		if ($result->rowCount() < 1){
			$sql = "INSERT INTO ".$_SESSION['institucion']."sgcaf830 (enc_clave, enc_fecha, enc_desco, enc_desc1, enc_debe, enc_haber, enc_item, enc_dif, enc_igual, enc_refer, enc_sw, enc_explic) VALUES ('$asiento', '$b', '$explicacion','',0,0,0,0,0,0,0,\"$explicacion\")"; 
	//		echo $sql;
			$result=$db_con->prepare($sql);
			$res=$result->execute();
			if (!$res)
			 die ("El usuario $usuario no tiene permiso para añadir Asientos.<br>".$sql);
		}

		if ($nomatach) {
			$sql="UPDATE ".$_SESSION['institucion']."sgcaf830 SET explicacion = \"$explicacion\" WHERE enc_clave = '$asiento'";
			$result=$db_con->prepare($sql);
			$result->execute();
		}

	$haber = $debe = 0;
	$debe = $elmonto;
	if (reviso($cuenta1, $db_con)) 
	{
		agregar_f820($asiento, $b, $elcargo, $cuenta1, $concepto, $debe, $haber, 0,$ip,0,$referencia,'','S',0, $db_con);
		$sql="UPDATE ".$_SESSION['institucion']."sgcaf8co SET con_ultfec = '$b'";
		$result=$db_con->prepare($sql);
		$result->execute();
		$anadido = 1;
	}
		else { echo '<h2> No se ha agregado información, una de las cuentas presenta problemas</h2>'; }
	}
}
catch (PDOException $e) 
{
	mensaje(['tipo'=>'warning','titulo'=>'Aviso','texto'=>'<h2>Fallo llamado</h2>'.$sql.$e->getMessage()]);
	die('');
}

function reviso($lacuenta, $db_con)
{
	$sql2="SELECT * FROM ".$_SESSION['institucion']."sgcaf810 where cue_codigo =:lacuenta";
	$salida=$db_con->prepare($sql2);
	$salida->execute(["lacuenta"=>$lacuenta]);
//	echo $sql2;
	$filas = $salida->rowCount();
	return ($filas == 0?false:true);
}
?>
