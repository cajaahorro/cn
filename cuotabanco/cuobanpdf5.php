<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
session_start();
extract($_GET);
extract($_POST);
extract($_SESSION);
include("../dbconfig.php");
define('FPDF_FONTPATH','../fpdf/font/');
/*
require('../fpdf/mysql_table.php');
include("../fpdf/comunes.php");
*/
require('../funciones.php');
require('../fpdf/fpdf.php');
class PDF extends FPDF
{
	// Cabecera de página
	function Header()
	{
	    // Logo
        // $this->Image('fpdf/logo/logo.jpg',10,0,20);
	    // Arial bold 15
	    $this->SetFont('Arial','B',15);
	    // Movernos a la derecha
	    $this->Cell(80);
	    // Título
	    //$this->Cell(30,10,'Title',1,0,'C');
	    // Salto de línea
	    $this->Ln(20);
	}

	// Pie de página
	function Footer()
	{
	    // Posición: a 1,5 cm del final
	    $this->SetY(-15);
	    // Arial italic 8
	    $this->SetFont('Arial','I',8);
	    // Número de página
	    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
}

$sql_amor="select *,descr_pres from ".$_SESSION['institucion']."sgcaamor, ".$_SESSION['institucion']."sgcaf360 where ('$fechadescuento' = fecha) and (proceso=1) and (codpre=cod_pres) order by codpre, codsoc"; //  limit 30"; //  limit 20";
// echo $sql_amor;
$a_amor=$db_con->prepare($sql_amor);
$a_amor->execute();
$columna=3;
$rpl=300; 	// registros por listado
$crl=0;		// contador de registros por listado
$col_listado=0;
$nuevoarchivo=false;
$condicion_sql='select codigo, cedula, nombre, nrocta, ';
$col_listado=0;
// $arrtitulo="'Lin.Nº','Código','Cédula','Apellidos y Nombres',";
$header[0]='Lin N°';
$header[1]='Codigo';
$header[2]='Cedula';
$header[3]='Apellidos y Nombres';
$header[4]='Nro Prest.';
$header[5]='Saldo Ant.';
$header[6]='Capital';
$header[7]='Saldo Actual';
$header[8]='Interes';
$header[9]='Cuota';
$alto=3;
$salto=$alto;
$w=array(8,13,20,50,25,25,25,25,25,25); // ,25,25,25,25,25,25);
$p[0]=20;
for ($posicion=1;$posicion<count($w);$posicion++) 
	$p[$posicion]=$p[$posicion-1]+$w[$posicion-1];
//$p=array(10,18,31,36,76,91,106,131,136,161,196,221,246);

$pdf=new PDF('L','mm','Letter');
$sintitulo=false;
$primeravez = true;
$santerior = $tcuota = $sactual = $tinteres = 0;
$gsanterior = $gtcuota = $gsactual = $gtinteres = 0;
$primeravez = $cont = 0;
/*
$r_amor = $a_amor->fetch(PDO::FETCH_ASSOC);
$res=json_encode($r_amor);
echo $res;
die('espero');
*/
while ($r_amor = $a_amor->fetch(PDO::FETCH_ASSOC))
{
	if ($primeravez == 0)
	{
		$elanterior=$r_amor['codpre'];
		$ctainteres=$r_amor['otro_int'];
		$nombreprestamo=$r_amor['descr_pres'];
		$primeravez = 1;
		$linea=encabeza_l_prestamos($header,$w,$p,$pdf,$salto,$alto,$fechadescuento,$r_amor['descr_pres']);
	}
	if ($elanterior == $r_amor['codpre']) {
		$linea+=$salto;
		$pdf->SetY($linea);
		$cont++;
		$pdf->SetX($p[0]);		$pdf->Cell($w[0],$alto,$cont,0,0,'LRTB',0);
		$pdf->SetX($p[1]);		$pdf->Cell($w[1],$alto,$r_amor["codsoc"],0,0,'LRTB',0); 
		$pdf->SetX($p[2]);		$pdf->Cell($w[2],$alto,$r_amor["cedula"],0,0,'LRTB',0);  
		$pdf->SetX($p[3]);		$pdf->Cell($w[3],$alto,$r_amor["nombre"],0,0,'LRTB');
		$pdf->SetX($p[4]);		$pdf->Cell($w[4],$alto,$r_amor["nropre"],0,0,'LRTB');
		$pdf->SetX($p[5]);		$pdf->Cell($w[5],$alto,number_format($r_amor["saldo"],2,'.',','),0,0,'R');
		$pdf->SetX($p[6]);		
		if ($r_amor["diferido"] == 0)
			$pdf->Cell($w[6],$alto,number_format($r_amor["capital"],2,'.',','),0,0,'R');
		else 
			$pdf->Cell($w[6],$alto,number_format($r_amor["capital"]-$r_amor["interes"],2,'.',','),0,0,'R');
		$pdf->SetX($p[7]);		$pdf->Cell($w[7],$alto,number_format($r_amor["saldo"]-$r_amor['cuota'],2,'.',','),0,0,'R');
		$pdf->SetX($p[8]);		$pdf->Cell($w[8],$alto,number_format($r_amor["interes"],2,'.',','),0,0,'R');
		$pdf->SetX($p[9]);		$pdf->Cell($w[9],$alto,number_format(($r_amor["interes"]+$r_amor['capital']),2,'.',','),0,0,'R');
		$ultimo=$r_amor['otro_int'];
		$santerior+=$r_amor["saldo"];
		if ($r_amor["diferido"] == 0)
			$tcuota+=$r_amor["capital"];
		else 
			$tcuota+=$r_amor["capital"]-$r_amor["interes"];
		$sactual+=$r_amor["saldo"]-$r_amor['cuota'];
		$tinteres+=$r_amor["interes"];

		$gsanterior+=$r_amor["saldo"];
		if ($r_amor["diferido"] == 0)
			$gtcuota+=$r_amor["capital"];
		else
			$gtcuota+=$r_amor["capital"]-$r_amor["interes"];
		$gsactual+=$r_amor["saldo"]-$r_amor['cuota'];
		$gtinteres+=$r_amor["interes"];
	}
	else {
		$linea+=$alto;
		$pdf->SetY($linea);
		$pdf->SetX($p[0]);
		$pdf->Cell(0,0,'  ',1,0,'L',0);
		$linea+=$alto;
		$pdf->SetY($linea);
		$pdf->SetX($p[3]);		$pdf->Cell($w[3]+$w[4],$alto,'Subtotal '.trim($nombreprestamo). ' ('.$ctainteres.')',0,0,'R');
		$pdf->SetX($p[5]);		$pdf->Cell($w[5],$alto,number_format($santerior,2,'.',','),0,0,'R');
		$pdf->SetX($p[6]);		$pdf->Cell($w[6],$alto,number_format($tcuota,2,'.',','),0,0,'R');
		$pdf->SetX($p[7]);		$pdf->Cell($w[7],$alto,number_format($sactual,2,'.',','),0,0,'R');
		$pdf->SetX($p[8]);		$pdf->Cell($w[8],$alto,number_format($tinteres,2,'.',','),0,0,'R');
		$pdf->SetX($p[9]);		$pdf->Cell($w[9],$alto,number_format($tcuota+$tinteres,2,'.',','),0,0,'R');

		$elanterior=$r_amor['codpre'];
		$ctainteres=$r_amor['otro_int'];
		$nombreprestamo=$r_amor['descr_pres'];
		$linea=encabeza_l_prestamos($header,$w,$p,$pdf,$salto,$alto,$fechadescuento,$r_amor['descr_pres']);
		$sintitulo=false;
		$santerior = $tcuota = $sactual = $tinteres = 0;
		// repito para imprimir el primero 

		$linea+=$salto;
		$pdf->SetY($linea);
		$cont++;
		$pdf->SetX($p[0]);		$pdf->Cell($w[0],$alto,$cont,0,0,'LRTB',0);
		$pdf->SetX($p[1]);		$pdf->Cell($w[1],$alto,$r_amor["codsoc"],0,0,'LRTB',0); 
		$pdf->SetX($p[2]);		$pdf->Cell($w[2],$alto,$r_amor["cedula"],0,0,'LRTB',0);  
		$pdf->SetX($p[3]);		$pdf->Cell($w[3],$alto,$r_amor["nombre"],0,0,'LRTB');
		$pdf->SetX($p[4]);		$pdf->Cell($w[4],$alto,$r_amor["nropre"],0,0,'LRTB');
		$pdf->SetX($p[5]);		$pdf->Cell($w[5],$alto,number_format($r_amor["saldo"],2,'.',','),0,0,'R');
		$pdf->SetX($p[6]);		
		if ($r_amor["diferido"] == 0)
			$pdf->Cell($w[6],$alto,number_format($r_amor["capital"],2,'.',','),0,0,'R');
		else 
			$pdf->Cell($w[6],$alto,number_format($r_amor["capital"]-$r_amor["interes"],2,'.',','),0,0,'R');
		$pdf->SetX($p[7]);		$pdf->Cell($w[7],$alto,number_format($r_amor["saldo"]-$r_amor['cuota'],2,'.',','),0,0,'R');
		$pdf->SetX($p[8]);		$pdf->Cell($w[8],$alto,number_format($r_amor["interes"],2,'.',','),0,0,'R');
//		$pdf->SetX($p[9]);		$pdf->Cell($w[9],$alto,number_format(($r_amor["interes"]+$r_amor['capital']),2,'.',','),0,0,'R');
		$pdf->SetX($p[9]);		$pdf->Cell($w[9],$alto,number_format(($r_amor["cuota"]),2,'.',','),0,0,'R');
		$santerior+=$r_amor["saldo"];
		if ($r_amor["diferido"] == 0)
			$tcuota+=$r_amor["capital"];
		else 
			$tcuota+=$r_amor["capital"]-$r_amor["interes"];
		$sactual+=$r_amor["saldo"]-$r_amor['cuota'];
		$tinteres+=$r_amor["interes"];

		$gsanterior+=$r_amor["saldo"];
		if ($r_amor["diferido"] == 0)
			$gtcuota+=$r_amor["capital"];
		else
			$gtcuota+=$r_amor["capital"]-$r_amor["interes"];
		$gsactual+=$r_amor["saldo"]-$r_amor['cuota'];
		$gtinteres+=$r_amor["interes"];

		//
	}
	if ($linea>=180) {
		$linea+=$alto;
		$pdf->SetY($linea);
		$pdf->SetX($p[0]);
		$pdf->Cell(0,0,'  ',1,0,'L',0);
		$linea=encabeza_l_prestamos($header,$w,$p,$pdf,$salto,$alto,$fechadescuento,$r_amor['descr_pres']);
		$sintitulo=false;
		}
}
$linea+=$alto;
$pdf->SetY($linea);
$pdf->SetX($p[0]);
$pdf->Cell(0,0,'  ',1,0,'L',0);
$linea+=$alto;
$pdf->SetY($linea);
$pdf->SetX($p[3]);		$pdf->Cell($w[3]+$w[4],$alto,'Subtotal '.trim($nombreprestamo). ' ('.$ctainteres.')',0,0,'R');
$pdf->SetX($p[5]);		$pdf->Cell($w[5],$alto,number_format($santerior,2,'.',','),0,0,'R');
$pdf->SetX($p[6]);		$pdf->Cell($w[6],$alto,number_format($tcuota,2,'.',','),0,0,'R');
$pdf->SetX($p[7]);		$pdf->Cell($w[7],$alto,number_format($sactual,2,'.',','),0,0,'R');
$pdf->SetX($p[8]);		$pdf->Cell($w[8],$alto,number_format($tinteres,2,'.',','),0,0,'R');
$pdf->SetX($p[9]);		$pdf->Cell($w[9],$alto,number_format($tcuota+$tinteres,2,'.',','),0,0,'R');


$linea+=$alto;
$pdf->SetY($linea);
$pdf->SetX($p[0]);
$pdf->Cell(0,0,'  ',1,0,'L',0);
$linea+=$alto;
$pdf->SetY($linea);
$pdf->SetX($p[3]);
$pdf->SetFont('Arial','B',7);
$pdf->Cell($w[4],$alto,'Total General',0,0,'L',0);
$pdf->SetX($p[5]);		$pdf->Cell($w[5],$alto,number_format($gsanterior,2,'.',','),0,0,'R');
$pdf->SetX($p[6]);		$pdf->Cell($w[6],$alto,number_format($gtcuota,2,'.',','),0,0,'R');
$pdf->SetX($p[7]);		$pdf->Cell($w[7],$alto,number_format($gsactual,2,'.',','),0,0,'R');
$pdf->SetX($p[8]);		$pdf->Cell($w[8],$alto,number_format($gtinteres,2,'.',','),0,0,'R');
$pdf->SetX($p[9]);		$pdf->Cell($w[9],$alto,number_format($gtcuota+$gtinteres,2,'.',','),0,0,'R');
$pdf->SetFont('Arial','',7);
$pdf->Output('F','../reportesprestamos/'.$fechadescuento.'amortizacion.pdf');
$pdf->Output();
set_time_limit(30);

////////////////////////////////////////////////////
function encabeza_l_prestamos($header,$w,$p,&$pdf,$salto,$alto,$fechadescuento,$nombreprestamo)
{
$pdf->AddPage();
$linea=25;
$pdf->SetY($linea);
$pdf->SetX(0);
$pdf->SetFont('Arial','B',14);
$pdf->MultiCell(0,0,"Amortizacion / Capital al ".convertir_fechadmy($fechadescuento),0,'C',0);
$pdf->SetY($linea);
$pdf->SetFont('Arial','',7);
$linea+=5;
$pdf->SetX(220);
$pdf->Cell(20,0,'Realizado el '.date('d/m/Y h:i A'),0,0,'L'); 
//Títulos de las columnas
$linea+=$alto;
$pdf->SetY($linea);
$pdf->SetFont('Arial','B',10);
$pdf->SetX($p[0]);
$pdf->Cell(0,$alto,$nombreprestamo,0,0,'L',0);
$pdf->SetFont('Arial','',7);
$linea+=5;
$pdf->SetY($linea);
//$header=array($$arrtitulo);
//Colores, ancho de línea y fuente en negrita
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',7);
//Cabecera
for($i=0;$i<count($w);$i++){
	$pdf->SetY($linea);
	$pdf->SetX($p[$i]);
	$pdf->Cell($w[$i],$alto,$header[$i],1,0,'C',1);
}
//Restauración de colores y fuentes
$pdf->SetFillColor(224,235,255);
$pdf->SetTextColor(0);
$pdf->SetFont('Arial','',7);
$linea+=$salto;
$linea+=$salto;
$pdf->SetY($linea);
$pdf->SetX($p[0]);
$pdf->Cell(0,0,'  ',1,0,'L',0);
return $linea;
}
?>
