<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
session_start();
// header('Content-type: application/pdf');
// include("fpdf/a_cookies.php");
extract($_GET);
extract($_POST);
extract($_SESSION);
$fechadescuento=$_GET['fechadescuento'];
ini_set("memory_limit","30M");
include_once '../dbconfig.php';

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

$sql_360="select * from ".$_SESSION['institucion']."sgcaf360 where (dcto_sem=1) order by cod_pres"; //  limit 30"; //  limit 20";
// echo $sql_360;
$a_360=$db_con->prepare($sql_360);
$a_360->execute();
$columna=3;
$rpl=300; 	// registros por listado
$crl=0;		// contador de registros por listado
$col_listado=0;
$nuevoarchivo=false;
$condicion_sql='select codigo, cedula, nombre, ';
$col_listado=0;
// $arrtitulo="'Lin.Nº','Código','Cédula','Apellidos y Nombres',";
$header[0]='Lin N°';
$header[1]='Codigo';
$header[2]='Cedula';
$header[3]='Apellidos y Nombres';
$arrtitulo='';
$max_cols=$a_360->rowCount();
/*
print_r($a_360);
 die('laoo');
*/
 while ($r360 = $a_360->fetch(PDO::FETCH_ASSOC))
{
	$col_listado++;
	$columna++;
//	if (($col_listado >= 1) and ($col_listado <= $max_cols)){
//		$arrtitulo.=$r360['desc_cor'];
	if (trim($r360['desc_cor'])!='') $header[$columna]=$r360['desc_cor'] ;
	else $header[$columna]=substr($r360['descr_pres'],0,12);
	$totales[$col_listado]=0;
	$campo='colpre'.$col_listado;
	$condicion_sql.=' colpre'.$col_listado;
	if ($col_listado != $max_cols) {
		$arrtitulo.=', ';
		$condicion_sql.=', ';
		}
//	}
//	else break;
}
$columna++;
$header[$columna]='Total Dcto';
$alto=3;
$salto=$alto;
$w=array(8,13,15,40,25,25,25,25,25,25,25);
$p[0]=10;
for ($posicion=1;$posicion<=count($w);$posicion++) 
	$p[$posicion]=$p[$posicion-1]+$w[$posicion-1];
$cont=0;
//$p=array(10,18,31,36,76,91,106,131,136,161,196,221,246);
$pdf=new PDF('L','mm','Letter');
//$pdf->Open();

$linea=encabeza_l_prestamos($header,$w,$p,$pdf,$salto,$alto,$fechadescuento);


$sql_nopr=$condicion_sql." from ".$_SESSION['institucion']."sgcanopr where (fecha = :fechadescuento) order by cedula"; // limit 0,500";
$a_nopr=$db_con->prepare($sql_nopr);
$a_nopr->bindParam(":fechadescuento",$fechadescuento);
$a_nopr->execute();
$registros=$a_nopr->rowCount();
set_time_limit($registros);
//echo $registros. '/'.$fechadescuento;
$lascolumnas=$a_nopr->columnCount()-3;

while ($r_nopr = $a_nopr->fetch(PDO::FETCH_ASSOC))
{
	$t1=0;
	for ($prestamos=1;$prestamos<=$lascolumnas;$prestamos++) {		// sumatoria de los prestamos
		$item='colpre'.$prestamos;
		$t1+=$r_nopr[$item];
//		$totales[$prestamos]+=$r_nopr[$item];
	}
	if ($t1 > 0) 
	{
//	$linea+=$salto;
		$pdf->SetY($linea);
		$cont++;
		$pdf->SetX($p[0]);
		$pdf->Cell($w[0],$alto,$cont,0,0,'LRTB',0);
		$pdf->SetX($p[1]);
		$pdf->Cell($w[1],$alto,$r_nopr["codigo"],0,0,'LRTB',0); 
		$pdf->SetX($p[2]);
		$pdf->Cell($w[2],$alto,$r_nopr["cedula"],0,0,'LRTB',0);  
		$pdf->SetX($p[3]);
		$pdf->Cell($w[3],$alto,$r_nopr["nombre"],0,0,'LRTB');
	//	echo $r_nopr["nombre"];
		$posicion=3;
		$t1=0;
		for ($prestamos=1;$prestamos<=$lascolumnas;$prestamos++) 
		{
			$posicion++;
			$pdf->SetY($linea);
			$pdf->SetX($p[$posicion]);
	//		$item='$r_nopr["colpre'.$prestamos.'"]';
			$item='colpre'.$prestamos;
	//		echo $r_nopr[$oitem];
	//		$eitem=$$item;
			$t1+=$r_nopr[$item];
			$totales[$prestamos]+=$r_nopr[$item];
			$pdf->Cell($w[4],$alto,number_format($r_nopr[$item],2,".",","),0,0,'R',0);
			if ($posicion > 8) 
			{
				$posicion=0;
				$linea+=$salto;
			}
		}
		$pdf->SetX($p[10]);
		$pdf->Cell($w[10],$alto,number_format($t1,2,".",","),0,0,'R',0);
		$linea+=$salto;
		$pdf->SetY($linea);
		$pdf->SetX($p[0]);
		$pdf->Cell(0,0,'  ',1,0,'L',0);
		$crl++;
		if ($linea>=180)
			$linea=encabeza_l_prestamos($header,$w,$p,$pdf,$salto,$alto,$fechadescuento);
	}
}

$linea+=10;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$general=0;
for ($i=1;$i<count($totales);$i++)
	if ($totales[$i]!=0) {
		$linea+=$alto;
		$pdf->SetY($linea);
		$pdf->SetX(10);
		$pdf->Cell(20,$alto,$header[$i+3],0,0,'R',0);
		$pdf->SetX(40);
		$pdf->Cell(20,$alto,number_format($totales[$i],2,".",","),0,0,'R',0);
		$general+=$totales[$i];
		if ($linea>=180)
			$linea=encabeza_l_prestamos($header,$w,$p,$pdf,$salto,$alto,$fechadescuento);
	}
$linea+=$alto;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(20,$alto,'Total General',0,0,'R',1);
$pdf->SetX(40);
$pdf->Cell(20,$alto,number_format($general,2,".",","),0,0,'R',1);
$pdf->Output('F','../reportesprestamos/'.$fechadescuento.'cuotas.pdf');
//$pdf->Output('F','zcuotas.pdf');
$pdf->Output();
set_time_limit(30);

////////////////////////////////////////////////////
function encabeza_l_prestamos($header,$w,$p,$pdf,$salto,$alto,$fechadescuento)
{

$pdf->AddPage();
$linea=25;
$pdf->SetY($linea);
$pdf->SetX(0);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,0,"Descuento de Préstamos al ".convertir_fechadmy($fechadescuento),0,0,'C',0);
//$pdf->Cell(0,0,"Descuento de Préstamos al ".convertir_fechadmy($fechadescuento),0,0,'C');
$pdf->SetY($linea);
$pdf->SetFont('Arial','',7);
$linea+=5;
$pdf->SetX(240);
$pdf->Cell(20,0,'Realizado el '.date('d/m/Y h:i A'),0,0,'C'); 
//Títulos de las columnas
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
for($i=0;$i<4;$i++){
	$pdf->SetY($linea);
	$pdf->SetX($p[$i]);
	$pdf->Cell($w[$i],$alto,$header[$i],1,0,'C',1);
}
$micolumna=4;
for($i=4;$i<count($header)-1;$i++){
	$pdf->SetY($linea);
	$pdf->SetX($p[$micolumna]);
	$pdf->Cell($w[$micolumna],$alto+2,$header[$i],1,0,'C',1);
	$micolumna++;
	if ($micolumna > 9) {
		$linea+=5;
		$micolumna=1;
	}
//	echo ($p[$i]). ' - '.$header[$i];
}
$pdf->SetY($linea);
$pdf->SetX($p[10]);
$pdf->Cell($w[10],$alto+2,$header[$i],1,0,'C',1);

//	$pdf->Cell($w[$i],7,$header[$i],1,0,'C',1);
// $pdf->Ln();
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
