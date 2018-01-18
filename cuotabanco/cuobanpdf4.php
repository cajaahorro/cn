<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
session_start();
// include("fpdf/a_cookies.php");
extract($_GET);
extract($_POST);
extract($_SESSION);
include_once '../dbconfig.php';
define('FPDF_FONTPATH','../fpdf/font/');
// require('../fpdf/mysql_table.php');
//include("../fpdf/comunes.php");
require('../funciones.php');
require('../fpdf/fpdf.php');
/*
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
*/
// $_SESSION['institucion']='CAPPOUCLA_';
$sql_360="select * from ".$_SESSION['institucion']."sgcaf360 where (dcto_sem=1) order by cod_pres"; //  limit 30"; //  limit 20";
$a_360=$db_con->prepare($sql_360);
$a_360->execute();
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
$header[4]='Nro Cuenta';
$max_cols=$a_360->rowCount();
$arrtitulo='';
while ($r360 = $a_360->fetch(PDO::FETCH_ASSOC))
{
	$col_listado++;
	$columna++;
//	if (($col_listado >= 1) and ($col_listado <= $max_cols)){
//		$arrtitulo.=$r360['desc_cor'];
	if (trim($r360['desc_cor'])!='') ;// $header[$columna]=$r360['desc_cor'] ;
	else ; // $header[$columna]=substr($r360['descr_pres'],0,12);
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
// $columna++;
$header[5]='Total Dcto';
$alto=3;
$salto=$alto;
$w=array(8,13,20,50,40,25); // ,25,25,25,25,25,25);
$p[0]=30;
for ($posicion=1;$posicion<count($w);$posicion++) 
	$p[$posicion]=$p[$posicion-1]+$w[$posicion-1];
//$p=array(10,18,31,36,76,91,106,131,136,161,196,221,246);

$pdf=new FPDF('P','mm','Letter');
//$pdf->Open();
$fechadescuento=$_GET['fechadescuento'];
$linea=encabeza_l_prestamos($header,$w,$p,$pdf,$salto,$alto,$fechadescuento);
$sql_nopr=$condicion_sql." from ".$_SESSION['institucion']."sgcanopr where (:fechadescuento = fecha) order by cedula "; //  limit 20";
// echo $sql_nopr;
$a_nopr=$db_con->prepare($sql_nopr);
$a_nopr->bindParam(":fechadescuento",$fechadescuento);
$a_nopr->execute();

$registros=$a_nopr->rowCount();
set_time_limit($registros);
// echo $registros;
$cont=0;
$lascolumnas=$a_nopr->columnCount()-4;
while ($r_nopr = $a_nopr->fetch(PDO::FETCH_ASSOC))
{
	$linea+=$salto;
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
	$pdf->SetX($p[4]);
	$pdf->Cell($w[4],$alto,$r_nopr["nrocta"],0,0,'LRTB');
	$posicion=3;
	$t1=0;
	for ($prestamos=1;$prestamos<=$lascolumnas;$prestamos++) {		// sumatoria de los prestamos
		$item='colpre'.$prestamos;
		$t1+=$r_nopr[$item];
		$totales[$prestamos]+=$r_nopr[$item];
	}
	$pdf->SetY($linea);
	$pdf->SetX($p[5]);
	$pdf->Cell($w[5],$alto,number_format($t1,2,".",","),0,0,'R',0);
	if ($linea>=250) {
		$linea+=$alto;
		$pdf->SetY($linea);
		$pdf->SetX($p[0]);
		$pdf->Cell(0,0,'  ',1,0,'L',0);
		$linea=encabeza_l_prestamos($header,$w,$p,$pdf,$salto,$alto,$fechadescuento);
		}
}
$general=0;
for ($i=1;$i<=count($totales);$i++)
	if ($totales[$i]!=0) {
		$general+=$totales[$i];
	}
$linea+=$alto;
$pdf->SetY($linea);
$pdf->SetX($p[4]);
$pdf->SetFont('Arial','B',10);
$pdf->Cell($w[4],$alto,'Total General',0,0,'L',1);
$pdf->SetX($p[5]);
$pdf->Cell($w[5],$alto,number_format($general,2,".",","),0,0,'R',1);
$pdf->SetFont('Arial','',7);
$pdf->Output('F','../reportesprestamos/'.$fechadescuento.'banco.pdf');
$pdf->Output();
$sql="select now() as fechagen";
$res=mysql_query($sql);
$r1=mysql_fetch_assoc($res);
$fechagen=$r1['fechagen'];
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
$sql="insert into ".$_SESSION['institucion']."sgcafnob (fechanom, fechagen, registros, ipgen) values ('$fechadescuento','$fechagen','$cont','$ip')";
$res=mysql_query($sql);
set_time_limit(30);

////////////////////////////////////////////////////

function encabeza_l_prestamos($header,$w,$p,&$pdf,$salto,$alto,$fechadescuento)
{
$pdf->AddPage();
$linea=25;
$pdf->SetFont('Arial','B',14);
$pdf->SetY($linea);
$pdf->SetX(0);
$pdf->Cell(200,0,"Descuento de Préstamos (Banco) al ".convertir_fechadmy($fechadescuento),0,0,'C',0);
$pdf->SetY($linea);
$pdf->SetFont('Arial','',7);
$linea+=5;
$pdf->SetX(170);
$pdf->Cell(20,0,'Realizado el '.date('d/m/Y h:i A'),0,0,'L'); 
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
for($i=0;$i<6;$i++){
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
