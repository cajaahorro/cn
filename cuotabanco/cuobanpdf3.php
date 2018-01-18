<?php
/*  
     This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
include("head.php");
if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}
	*/ 
// $link = @mysql_connect("localhost","root", "",'',65536) or die ("<p /><br /><p /><div style='text-align:center'>En estos momentos no hay conexión con el servidor, inténtalo más tarde.</div>");
// mysql_select_db($_POST['sica'], $link);
session_start();
// include("fpdf/a_cookies.php");
extract($_GET);
extract($_POST);
extract($_SESSION);
include("conex.php");
if (!$link OR !$_SESSION['empresa']) {
    include("head.php");
	//header("location: noempresa.php");
	exit;
}
 define('FPDF_FONTPATH','fpdf/font/');
require('fpdf/mysql_table.php');
include("fpdf/comunes.php");
require('funciones.php');
$sql_360="select * from sgcaf360 where (dcto_sem) order by cod_pres"; //  limit 30"; //  limit 20";
$a_360=mysql_query($sql_360);
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
$max_cols=mysql_num_rows($a_360);
while ($r360 = mysql_fetch_assoc($a_360))
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
for ($posicion=1;$posicion<count($w);$posicion++) 
	$p[$posicion]=$p[$posicion-1]+$w[$posicion-1];
//$p=array(10,18,31,36,76,91,106,131,136,161,196,221,246);

$pdf=new PDF('L','mm','Letter');
$pdf->Open();
$linea=encabeza_l_prestamos($header,$w,$p,$pdf,$salto,$alto,$fechadescuento);
$sql_nopr=$condicion_sql." from sgcanopr where ('$fechadescuento' = fecha) order by cedula ";
// echo $sql_nopr;
$a_nopr=mysql_query($sql_nopr);
$registros=mysql_num_rows($a_nopr);
set_time_limit($registros);
//echo $registros;
$lascolumnas=mysql_num_fields($a_nopr)-3;
// echo $lascolumnas;
//	mysql_data_seek ($a_nopr, 0);		// volver al principio de la busqueda
while ($r_nopr = mysql_fetch_assoc($a_nopr)){
/*
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
*/
	$posicion=3;
	$t1=0;
	for ($prestamos=1;$prestamos<=$lascolumnas;$prestamos++) {
		$posicion++;
/*
		$pdf->SetY($linea);
		$pdf->SetX($p[$posicion]);
//		$item='$r_nopr["colpre'.$prestamos.'"]';
*/
		$item='colpre'.$prestamos;
//		echo $r_nopr[$oitem];
//		$eitem=$$item;
		$t1+=$r_nopr[$item];
		$totales[$prestamos]+=$r_nopr[$item];
//		$pdf->Cell($w[4],$alto,number_format($r_nopr[$item],2,".",","),0,0,'R',0);
/*
		if ($posicion > 8) {
			$posicion=3;
			$linea+=$salto;
		}
*/
	}
/*
	$pdf->SetX($p[10]);
	$pdf->Cell($w[10],$alto,number_format($t1,2,".",","),0,0,'R',0);
	$linea+=$salto;
	$pdf->SetY($linea);
	$pdf->SetX($p[0]);
	$pdf->Cell(0,0,'  ',1,0,'L',0);
	$crl++;
	if ($linea>=190)
		$linea=encabeza_l_prestamos($header,$w,$p,$pdf,$salto,$alto,$fechadescuento);
*/
};
$linea+=10;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$general=0;
for ($i=1;$i<=count($totales);$i++)
	if ($totales[$i]!=0) {
		$linea+=$alto;
		$pdf->SetY($linea);
		$pdf->SetX(10);
		$pdf->Cell(20,$alto,$header[$i+3],0,0,'R',0);
		$pdf->SetX(40);
		$pdf->Cell(20,$alto,number_format($totales[$i],2,".",","),0,0,'R',0);
		$general+=$totales[$i];
		if ($linea>=190)
			$linea=encabeza_l_prestamos($header,$w,$p,$pdf,$salto,$alto,$fechadescuento);
	}
$linea+=$alto;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(20,$alto,'Total General',0,0,'R',1);
$pdf->SetX(40);
$pdf->Cell(20,$alto,number_format($general,2,".",","),0,0,'R',1);
$pdf->Output();
set_time_limit(30);


////////////////////////////////////////////////////
function encabeza_l_prestamos($header,$w,$p,&$pdf,$salto,$alto,$fechadescuento)
{
$pdf->AddPage();
$linea=25;
$pdf->SetY($linea);
$pdf->SetX(0);
$pdf->MultiCell(0,0,"Descuento de Préstamos al ".convertir_fechadmy($fechadescuento),0,C,0);
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
		$micolumna=4;
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
