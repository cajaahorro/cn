<?php
session_start();
extract($_GET);
extract($_POST);
extract($_SESSION);

/*
include("conex.php");
include("funciones.php");
*/
include_once 'dbconfig.php';

$suma=$reintegros=0;
// $suma='xx--'.$_GET['totalregistros'].'--xx';
$losregistros=$_GET['totalregistros'];
for ($i=0;$i<$losregistros;$i++)
{
//	${'cancelar'.$i};
//	$variable=${'cancelar'.$i};
	$variable='cancelar'.($i+1);
//	echo 'la variable '.$$variable;
	$sql="SELECT fecha, sum(cuota) as monto, count(fecha) as cuantos FROM ".$_SESSION['institucion']."sgcaamor where ((proceso = 1) and (semanal = 1)) and (fecha ='".$$variable."')  group by fecha";
//	echo $sql;
	$a_310=$db_con->prepare($sql);
	$a_310->execute();
	$r_310=$a_310->fetch(PDO::FETCH_ASSOC);
	$saldo+=$r_310['monto'];
	$cuantos+=$r_310['cuantos'];
}

header("Content-Type: text/xml");
echo '<?xml version="1.0" encoding="utf-8"?>';
echo "<resultados>";
// echo utf8_encode("<cuota>$cuota</cuota>");		// sirve asi y como esta abajo tambien
echo "<totalregistros>".number_format($cuantos,0,'.','')."</totalregistros>";
echo "<totalnominas>".number_format($saldo,2,'.',',')."</totalnominas>";
echo "</resultados>";


?>