<?php
session_start();
extract($_GET);
extract($_POST);
extract($_SESSION);

include("dbconfig.php");
$p_interes=$_GET["p_interes"];
$num_cuotas=$_GET["num_cuotas"];
$montoprestamo=$_GET["montoprestamo"];
$divisible=$_GET["divisible"];
$p_interes=$_GET["p_interes"];
$tipo_interes=strtoupper($_GET["tipo_interes"]);
$descontar_interes=$_GET["descontar_interes"];
$monto_futuro=$_GET["monto_futuro"];
if (($tipo_interes)=='NOAPLICA') {
	$cuota = number_format(($montoprestamo/$num_cuotas),2,'.',''); 
	$interes = 0.00;
}
else if (($tipo_interes)=='DIRECTOFUTURO') {
//	$interes = number_format(directo($p_interes,$num_cuotas,$montoprestamo,$divisible),2,'.','');
	$interes= number_format(($montoprestamo*($p_interes/100)),2,'.','');
	$cuota = number_format((($montoprestamo+$interes)/$num_cuotas),2,'.',''); 
}
else if (($tipo_interes)=='DIRECTO') {
	$cuota = number_format(($montoprestamo/$num_cuotas),2,'.',''); 
//	$interes = number_format(directo($p_interes,$num_cuotas,$montoprestamo,$divisible),2,'.','');
	$interes= number_format(calint($montoprestamo,$p_interes,$num_cuotas,$divisible,$cuota),2,'.','');
}
else if (($tipo_interes)=='AMORTIZADA') {
	$cuota = number_format(cal2int($p_interes,$num_cuotas,$montoprestamo,$divisible),2,'.','');
	$interes= number_format(calint($montoprestamo,$p_interes,$num_cuotas,$divisible),2,'.','');
}
else {
	$cuota = number_format(cal2int($p_interes,$num_cuotas,$montoprestamo,$divisible),2,'.','');
	$interes= number_format(calint($montoprestamo,$p_interes,$num_cuotas,$divisible),2,'.','');
}
if ((($descontar_interes == 0) and ($tipo_interes)=='AMORTIZADA') or (($descontar_interes == 0) and ($tipo_interes)=='NO APLICA'))
	$interes = 0.00;

// $cuota = number_format(cal2int($p_interes,$num_cuotas,$montoprestamo,$divisible),2,'.','');
// $interes= number_format(calint($montoprestamo,$p_interes,$num_cuotas,$divisible),2,'.','');
$gtoadm=restaradministrativos($montoprestamo, $db_con);
$neto=($montoprestamo-$interes)-$gtoadm;
if (($tipo_interes)=='DIRECTOFUTURO') {
	$interes = 0.00;
	$neto=(($montoprestamo)-$gtoadm);
}
//echo '<?xml version="1.0">'; //  encoding="utf-8">';
// echo '<?xml version="1.0" encoding="ISO-8859-1">';
// echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?".">"; 
header("Content-Type: text/xml");
echo '<?xml version="1.0" encoding="utf-8"?>';
echo "<resultados>";
echo utf8_encode("<cuota>$cuota</cuota>");		// sirve asi y como esta abajo tambien
//echo "<cuota>".$interes."</cuota>";
echo "<interes_diferido>".$interes."</interes_diferido>";
echo "<montoneto>".$neto."</montoneto>";
echo "<gastosadministrativos>".$gtoadm."</gastosadministrativos>";
echo "</resultados>";

	function cal2int($interes,$mcuotas,$mmonpre_sdp,$factor_divisible = 12,$z=0,$i2=0)
	{
		if ($interes > 0) {
			$i = ((($interes / 100)) / $factor_divisible);
//			echo 'i = '.$i.'<br>';
			$i2 = $i;
//			$_SESSION['i2']=$i2;
			$i_ = 1 + $i;
//			echo 'i_ = '.$i_.'<br>';
			$i_ = pow($i_,$mcuotas); 	// exponenciacion 
			$i_ = 1 / $i_;
			$i__ = 1 - $i_;
//			echo 'i__ = '.$i__.'<br>';
			$i___ = $i / $i__;
//			echo 'i___ = '.$i___.'<br>';
			$z = $mmonpre_sdp * $i___;
			}
		if ($interes ==0)
			$z = $mmonpre_sdp / $mcuotas;

//	    ((1 + i)^n) - 1
//	i =-----------------
//	           i
		return $z;
	}

	function directo($interes,$mcuotas,$mmonpre_sdp,$factor_divisible = 12)
	{
		if ($interes > 0) {
			$_interes=$mmonpre_sdp * ($interes / 100);
			$z = ($mmonpre_sdp + $_interes) / $mcuotas; 
			}
		if ($interes ==0)
			$z = $mmonpre_sdp / $mcuotas;
		return $z;
	}

	function revertido($interes,$mcuotas,$mmonpre_sdp,$factor_divisible = 12)
	{
		if ($interes > 0) {
			$_interes=$mmonpre_sdp / ($interes / 100);
			$z = ($mmonpre_sdp + $_interes) / $mcuotas; 
			}
		if ($interes ==0)
			$z = $mmonpre_sdp / $mcuotas;
		return $z;
	}

	function calint($monto, $interes, $mcuotas,$factor_divisible = 12,$cuota2=0)
	{
		$y=cal2int($interes, $mcuotas, $monto, $factor_divisible, $z, $i2);
		if ($cuota2 != 0) $z=$cuota2;
//		echo $z.'------------'. $i2.'<br>';
		$k = $ia = $cu22 = $ac = $tc = $ta = 0;
		$_c1 = $monto;
		$i1 = $interes;
		$n = $mcuotas;
//		echo $z.'<br>';
		for ($k=0;$k<$n;$k++)
		{
			$i1 = $_c1*$i2;
			$cu22 = $z - $i1;
			$_c1 = $_c1-$cu22;
			$ia = $ia + $i1;
			$ac = $ac + $cu22;
			$ta = $ta+ $z;
//			echo $_c1.' - '.$ac.' - '.$ta.' - '.$i1.' - '.$ia.' - '.$ac.'<br>';
		}
		return $ia;
	}
	
function restaradministrativos($montoprestamo, $db_con)
{
	$sql_deduccion="select * from sgcaf311 where activar = 1";
	$a_deduccion=$db_con->prepare($sql_deduccion);
	$a_deduccion->execute();
	$d_obligatorias=0;
	while($r_deduccion=$a_deduccion->fetch(PDO::FETCH_ASSOC)) {
		if ($r_deduccion['porcentaje'] == 0)
			$monto_deduccion=$r_deduccion['monto'];
		else $monto_deduccion=($montoprestamo)*($r_deduccion['porcentaje']/100);
		$d_obligatorias+=$monto_deduccion;
		}
	return $d_obligatorias;
}
	

//			$calcular='$this->'.$this->queryParam["f_ajax"].'($this->queryParam["p_interes"],$this->queryParam["num_cuotas"],$this->queryParam["montoprestamo"],$this->queryParam["divisible"])';
//			$funcionllamar=$this->queryParam["f_ajax"];
//			call_user_func($funcionllamar,$this->queryParam["p_interes"],$this->queryParam["num_cuotas"],$this->queryParam["montoprestamo"],$this->queryParam["divisible"]);

//			$calcular="'".$calcular."'"; 
//			echo $calcular;
//			$calcular(); // number_format($calcular,2,'.','');
//						                  $this->cal2int($this->queryParam["p_interes"],$this->queryParam["num_cuotas"],$this->queryParam["montoprestamo"],$this->queryParam["divisible"])

?>