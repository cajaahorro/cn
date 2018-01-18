<?php
header('Content-type: application/pdf');
date_default_timezone_set('America/Caracas'); 
require('fpdf/pdf_js.php');

class PDF_AutoPrint extends PDF_JavaScript
{
function AutoPrint($dialog=false)
{
	//Open the print dialog or start printing immediately on the standard printer
	$param=($dialog ? 'true' : 'false');
	$script="print($param);";
	$this->IncludeJS($script);
}

function AutoPrintToPrinter($server, $printer, $dialog=false)
{
	//Print on a shared printer (requires at least Acrobat 6)
	$script = "var pp = getPrintParams();";
	if($dialog)
		$script .= "pp.interactive = pp.constants.interactionLevel.full;";
	else
		$script .= "pp.interactive = pp.constants.interactionLevel.automatic;";
	$script .= "pp.printerName = '\\\\\\\\".$server."\\\\".$printer."';";
	$script .= "print(pp);";
	$this->IncludeJS($script);
}
}


session_start();
extract($_GET);
extract($_POST);
extract($_SESSION);
include("conex.php");
define('FPDF_FONTPATH','fpdf/font/');
require('fpdf/mysql_table.php');
include("fpdf/comunes.php");
require('funciones.php');
$asiento=$_GET['asiento'];
$formato=$_GET['hoja'];
$agrupar=$_GET['agrupar'];

 // 01052009003
// echo 'formato '.$formato; 
if ($agrupar == 1) 
	$sql="select com_debcre, left(com_cuenta,16) as lacuenta, com_descri, com_refere, sum(com_monto1) as com_monto1, sum(com_monto2) as com_monto2, enc_desco, enc_desc1, enc_fecha from sgcaf820, sgcaf830 where com_nrocom='$asiento' and (com_nrocom=enc_clave) group by com_nrocom, com_debcre, lacuenta order by com_nrocom, com_debcre, com_cuenta, com_refere "; // . " limit 30"; //  limit 20";
else 
//	$sql="select *, com_cuenta as lacuenta from sgcaf820, sgcaf830 where com_nrocom='$asiento' and (com_nrocom=enc_clave) order by com_nrocom, com_debcre, lacuenta, com_refere"; // . " limit 30"; //  limit 20";
	$sql="select *, com_cuenta as lacuenta from sgcaf820 where com_nrocom='$asiento' order by com_nrocom, com_debcre, lacuenta, com_refere"; // . " limit 30"; //  limit 20";
// echo $sql;
$columna=3;
$rpl=($formato==1?60:30); 	// registros por listado
$rp=0;		// registros por pagina
$crl=0;		// contador de registros por listado
$col_listado=0;
$header[0]='Linea';
$header[1]='Cuenta';
$header[2]='Descripcion';
$header[3]='Referencia';
$header[4]='Debito';
$header[5]='Credito';
$alto=($formato==1?3:6);
$salto=$alto;
$w=array(10,30,80,20,20,20); 
$p[0]=10;
for ($posicion=1;$posicion<count($w);$posicion++) 
	$p[$posicion]=$p[$posicion-1]+$w[$posicion-1];

if ($formato == 1)
	$pdf=new PDF('P','mm','Letter');
else 
$pdf=new PDF_AutoPrint();
$pdf->Open();
$sintitulo=false;
$primeravez = true;
$tsaldo = $tfianza = 0;
// echo $sql;
$aasiento=mysql_query($sql);
$rasiento= mysql_fetch_assoc($aasiento);
$linea=encabeza_l_asientos($header,$w,$p,$pdf,$salto,$alto,$formato,$asiento,$rasiento);
mysql_data_seek($aasiento,0);
$debe = $haber = $cont=0;
while ($rasiento = mysql_fetch_assoc($aasiento)){
	$cont++;
	$rp++;
	if ($formato == 1) {
		$pdf->SetX($p[0]);		$pdf->Cell($w[0],$alto,ceroizq($cont,4),0,0,'C');
		$pdf->SetX($p[1]);		$pdf->Cell($w[1],$alto,$rasiento["lacuenta"],0,0,'C');
		$pdf->SetX($p[2]);		$pdf->Cell($w[2],$alto,$rasiento["com_descri"],0,0,'L');
		$pdf->SetX($p[3]);		$pdf->Cell($w[3],$alto,$rasiento["com_referen"],0,0,'L');
		if ($rasiento["com_debcre"] == '+') {
				$pdf->SetX($p[4]);		$pdf->Cell($w[4],$alto,number_format($rasiento["com_monto1"],2,'.',','),0,0,'R'); 
				$debe+=$rasiento["com_monto1"];
				}
		else {
		$pdf->SetX($p[5]);		$pdf->Cell($w[5],$alto,number_format($rasiento["com_monto2"],2,'.',','),0,0,'R'); 
				$haber+=$rasiento["com_monto2"];
		}
	}
	else {
		$pdf->SetX(20);		$pdf->Cell($w[0],$alto,$rasiento["lacuenta"],0,0,'C');
		$pdf->SetX($p[2]);		$pdf->Cell($w[2],$alto,$rasiento["com_descri"],0,0,'L');
		if ($rasiento["com_debcre"] == '+') {
				$pdf->SetX($p[3]);		$pdf->Cell($w[3],$alto,number_format($rasiento["com_monto1"],2,'.',','),0,0,'R'); 
				$debe+=$rasiento["com_monto1"];
				}
		else {
		$pdf->SetX($p[5]);		$pdf->Cell($w[5],$alto,number_format($rasiento["com_monto2"],2,'.',','),0,0,'R'); 
				$haber+=$rasiento["com_monto2"];
		}
	}
	$linea+=$salto;
	$pdf->SetY($linea);
	if ($rp >= $rpl) 
	{
//		$linea+=$alto;
		$pdf->SetY($linea);
		$pdf->SetX($p[0]);
		$pdf->Cell(0,0,'  ',1,0,'L',0);
		$linea=encabeza_l_asientos($header,$w,$p,$pdf,$salto,$alto,$formato,$asiento,$rasiento);
		$sintitulo=false;
//		$linea-=$alto;
		$rp=0;
		}
}

if ($formato == 1)
{
	$linea+=$alto;
	$pdf->SetY($linea);
	$pdf->SetX($p[0]);
	$pdf->Cell(0,0,'  ',1,0,'L',0);
}
$linea+=$alto;
$pdf->SetY($linea);
if ($formato == 1) {
	$pdf->SetX($p[03]);		$pdf->Cell($w[03],$alto,'Total',0,0,'R');
	$pdf->SetX($p[04]);		$pdf->Cell($w[04],$alto,number_format($debe,2,'.',','),0,0,'R');
}
else {
	$pdf->SetX($p[02]);		$pdf->Cell($w[02],$alto,'Total',0,0,'R');
	$pdf->SetX($p[03]);		$pdf->Cell($w[03],$alto,number_format($debe,2,'.',','),0,0,'R');
}
$pdf->SetX($p[05]);		$pdf->Cell($w[05],$alto,number_format($haber,2,'.',','),0,0,'R');
$pdf->Output();
set_time_limit(30);

////////////////////////////////////////////////////
function encabeza_l_asientos($header,$w,$p,&$pdf,$salto,$alto,$formato,$asiento,$rasiento)
{
if ($formato==1) {
	$pdf->AddPage();
	$linea=25;
	$pdf->SetY($linea);
	$pdf->SetFont('Arial','',7);
	$linea+=alto;
	$pdf->SetY($linea);
	$pdf->SetX(150);
	$hoy = date("d")."-".date('m')."-".date("Y"); 
	$pdf->Cell(20,0,'Impreso '.date('d/m/Y h:i A'),0,0,'L'); 
	$linea+=alto;
	$linea+=alto;
	$linea+=alto;
	$pdf->SetY($linea);
	$pdf->SetX(0);
	$pdf->SetFont('Arial','B',14);
	$sql2="select * from sgcaf830 where enc_clave='$asiento' limit 1";
	$encabeza=mysql_query($sql2);
	$rencabeza= mysql_fetch_assoc($encabeza);
	
	$pdf->MultiCell(0,$alto*3,"Comprobante de Diario ".$asiento .' de fecha '.convertir_fechadmy($rencabeza['enc_fecha']),0,C,0);
	$linea+=$alto;
	$linea+=$alto;
	$pdf->SetY($linea);
	$pdf->SetFont('Arial','',10);
//	$pdf->MultiCell(0,$alto*3,"Concepto ".trim($rencabeza['enc_desco']).trim($rencabeza['enc_desc1']),0,L,0);
//	$pdf->MultiCell(0,$alto*3,trim($rasiento['enc_desco']).trim($rasiento['enc_desc1']),0,L,0);
	$pdf->MultiCell(0,$alto*3,trim($rencabeza['enc_explic']),0,L,0);

	$linea+=$alto;
	$linea+=$alto;
	$pdf->SetY($linea);
	$linea+=$alto;
	$pdf->SetY($linea);
	//Colores, ancho de línea y fuente en negrita
	$pdf->SetFillColor(200,200,200);
	$pdf->SetTextColor(0);
	$pdf->SetDrawColor(0,0,0);
	$pdf->SetLineWidth(.2);
	$pdf->SetFont('Arial','B',7);
	//Cabecera  
	$linea+=$alto;
	$pdf->SetY($linea);
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
}
else {
	$pdf->AddPage();
	$linea=50;

	$sql2="select * from sgcaf830 where enc_clave='$asiento' limit 1";
	$encabeza=mysql_query($sql2);
	$rencabeza= mysql_fetch_assoc($encabeza);

	$pdf->SetY($linea);
	$pdf->SetFont('Arial','',7);
	$linea+=alto;
	$pdf->SetY($linea);
	$pdf->SetX(150);
	$hoy = date("d")."-".date('m')."-".date("Y"); 
	$pdf->Cell(20,0,'Impreso '.date('d/m/Y h:i A'),0,0,'L'); 
	$linea+=(alto*5);
	$pdf->SetY($linea);
	$pdf->SetX(30);
	$pdf->SetFont('Arial','B',14);
	$pdf->Cell(20,0,convertir_fechadmy($rencabeza['enc_fecha']),0,0,'L'); 
	$pdf->SetX(100);
	$pdf->Cell(60,0,$asiento,0,0,'L'); 
	$linea+=($alto*5);
	$pdf->SetY($linea);
	$pdf->SetFont('Arial','',10);
//	$pdf->MultiCell(0,$alto*3,trim($rencabeza['enc_desco']).trim($rencabeza['enc_desc1']),0,L,0);
	$pdf->MultiCell(0,$alto*3,trim($rencabeza['enc_explic']),0,L,0);
	$linea+=($alto*9);
	$pdf->SetY($linea);
/*
	//Colores, ancho de línea y fuente en negrita
	$pdf->SetFillColor(200,200,200);
	$pdf->SetTextColor(0);
	$pdf->SetDrawColor(0,0,0);
	$pdf->SetLineWidth(.2);
	$pdf->SetFont('Arial','B',7);
	//Cabecera  
	$linea+=$alto;
	$pdf->SetY($linea);
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
*/
}
return $linea;
}
?>
