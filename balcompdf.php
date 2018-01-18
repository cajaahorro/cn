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
	header("location: noempresa.php");
	exit;
}


define('FPDF_FONTPATH','fpdf/font/');
require('fpdf/mysql_table.php');
include("fpdf/comunes.php");
// include ("conex.php"); 

$pdf=new PDF();
$pdf->Open();
$pdf->AddPage();

$alto=3;
ini_set("memory_limit","120M");

/*
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',14);
$pdf->SetY(50);
$pdf->SetX(0);
$pdf->MultiCell(220,6,"Planilla de Pre-Inscripción",0,C,0);//

$pdf->Ln();    
*/
$linea=25;
$pdf->SetY($linea);
$pdf->SetX(0);
$pdf->SetFont('Arial','B',11);
$pdf->MultiCell(0,0,"Balance de Comprobación ".nombremes($elmes).'/'.$anoseleccionado.'. (Nivel Detalle '.$nivelseleccionado.')',0,C,0);
$pdf->SetY($linea);
$pdf->SetFont('Arial','',8);
$linea+=$alto;
$pdf->SetX(165);
$pdf->Cell(20,0,'             '.date('d/m/Y h:i A'),0,0,'C'); 
$linea+=$alto;
$pdf->SetY($linea);
$pdf->SetX(10);
//Colores, ancho de línea y fuente en negrita
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',8);
	
//Cabecera
$header=array('Cod.Cuenta','Nombre Cuenta','Saldo Anterior','Débitos','Créditos','Saldo Actual');
$w=array(20,70, 25, 25, 25, 25);
$p=array(10,40,100,125,150,175);
for($j=0;$j<count($header);$j++)  {
	$pdf->SetX($p[$j]);
//	echo $pos[$j];
    $pdf->Cell($w[$j],$alto,$header[$j],0,0,'C',0); }
$pdf->Ln();
	
//Restauración de colores y fuentes

$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0);
$pdf->SetFont('Arial','',7);

//Buscamos y listamos 

/*--------------------------------------------*/
$nivelactual='1';
$ultimonivel='';
$posicion=$tdebe=$thaber=0;
$sql="select count(con_nivel) as niveles from sgcafniv";
$losniveles=mysql_query($sql) or die('Error 810-6');
$nivel=mysql_fetch_assoc($losniveles);
$primera=0;
for ($i=0; $i<$nivel['niveles']; $i++) 
	{$arreglo[$i]=0;}
$nivel=$nivel['niveles'];
$codigo=$_SESSION['comando'];
$result= mysql_query($codigo) or die('Error 810-5');
$maxlinea=240;
while ($fila = mysql_fetch_assoc($result)) {
	if ($linea > $maxlinea)
	{
		$linea = 10;
		$pdf->AddPage();
// ---------------------------
$linea=25;
$pdf->SetY($linea);
$pdf->SetX(0);
$pdf->SetFont('Arial','B',11);
$pdf->MultiCell(0,0,"Balance de Comprobación ".nombremes($elmes).'/'.$anoseleccionado.'. (Nivel Detalle '.$nivelseleccionado.')',0,C,0);
$pdf->SetY($linea);
$pdf->SetFont('Arial','',8);
$linea+=$alto;
$pdf->SetX(165);
$pdf->Cell(20,0,'             '.date('d/m/Y h:i A'),0,0,'C'); 
$linea+=$alto;
$pdf->SetY($linea);
$pdf->SetX(10);
//Colores, ancho de línea y fuente en negrita
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',8);
	
//Cabecera
$header=array('Cod.Cuenta','Nombre Cuenta','Saldo Anterior','Débitos','Créditos','Saldo Actual');
$w=array(20,70, 25, 25, 25, 25);
$p=array(10,40,100,125,150,175);
for($j=0;$j<count($header);$j++)  {
	$pdf->SetX($p[$j]);
//	echo $pos[$j];
    $pdf->Cell($w[$j],$alto,$header[$j],0,0,'C',0); }
$pdf->Ln();
	
//Restauración de colores y fuentes

$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0);
$pdf->SetFont('Arial','',7);
// ---------------------------				
	}
	if ($fila['cue_nivel']>$nivelactual) $nivelactual=$fila['cue_nivel'];
		$arreglo[$nivelactual-1]=$posicion;
		if ($nivelactual=='1') 
			if ($primera == 1) 
			{
				$pdf->AddPage();$linea=10; $pdf->SetY($linea); 
				}
			else $primera = 1;

///----------------------
		if ($fila['cue_nivel'] < $nivelactual) 
		{
			$pos_actual=$posicion;
			$elnivel=$fila['cue_nivel'];
			for ($i=($ultimonivel-1); $i>=($elnivel); $i--) 
			{
				mysql_data_seek($result,$arreglo[($i-1)]);
				$fila=mysql_fetch_assoc($result);
		if ($encero==1) {
			if (($fila["cue_saldo"]!=0) || ($fila["danterior"]!=0) || ($fila['debe']!=0) || ($fila["hanterior"]!=0) || ($fila['haber']!=0))
			{
				$linea+=$alto;
				$pdf->SetY($linea);
				$pdf->SetX($p[1]);
	  			$pdf->Cell($w[1],$alto,'Total: '.$fila['cue_nombre'],0,0,'R',0);
				$actual=$fila["cue_saldo"]+($fila["danterior"]+$fila['debe'])-($fila["hanterior"]+$fila['haber']);
				if ($elejercicio == 1) 
				{
					$pdf->SetX($p[2]);
		  			$pdf->Cell($w[2],$alto,number_format($fila["cue_saldo"],$deci,".",","),0,0,'R',0);
					$pdf->SetX($p[3]);
  					$pdf->Cell($w[3],$alto,number_format(($fila["danterior"]+$fila["debe"]),$deci,".",","),0,0,'R',0);
					$pdf->SetX($p[4]);
		  			$pdf->Cell($w[4],$alto,number_format(($fila["hanterior"]+$fila["haber"]),$deci,".",","),0,0,'R',0);
					$pdf->SetX($p[5]);
  					$pdf->Cell($w[5],$alto,number_format($actual,$deci,".",","),0,0,'R',0);
					$linea+=$alto;
					$pdf->SetY($linea);
				}
				else 
				{
/*
					echo '<td align="right">'.number_format(($fila["cue_saldo"]+$fila["danterior"]-$fila["hanterior"]),$deci,".",",").'</td>';
					echo '<td align="right">'.number_format(($fila["debe"]),$deci,".",",").'</td>';
					echo '<td align="right">'.number_format($fila["haber"],$deci,".",",").'</td>';
					echo '<td align="right">'.number_format($actual,$deci,".",",").'</td><tr><td>&nbsp;</td></tr>';
*/
					$pdf->SetX($p[2]);
		  			$pdf->Cell($w[2],$alto,number_format(($fila["cue_saldo"]+$fila["danterior"]-$fila["hanterior"]),$deci,".",","),0,0,'R',0);
					$pdf->SetX($p[3]);
  					$pdf->Cell($w[3],$alto,number_format($fila["debe"],$deci,".",","),0,0,'R',0);
					$pdf->SetX($p[4]);
		  			$pdf->Cell($w[4],$alto,number_format($fila["haber"],$deci,".",","),0,0,'R',0);
					$pdf->SetX($p[5]);
  					$pdf->Cell($w[5],$alto,number_format($actual,$deci,".",","),0,0,'R',0);
					$linea+=$alto;
					$pdf->SetY($linea);
				}
			}
			}
			else 		{
//					echo '<tr> </tr><tr align="right" style="font-style:italic" ><td colspan=2> Total: '.$fila['cue_codigo'].'-'.$fila['cue_nombre'].'</td>';
//					echo '<tr> </tr><tr align="right" style="font-style:italic" ><td colspan=2> Total: '.$fila['cue_nombre'].'</td>';
					$linea+=$alto;
					$pdf->SetY($linea);
					$pdf->SetX($p[1]);
		  			$pdf->Cell($w[1],$alto,'Total: '.$fila['cue_nombre'],0,0,'R',0);
					$actual=$fila["cue_saldo"]+($fila["danterior"]+$fila['debe'])-($fila["hanterior"]+$fila['haber']);
					if ($elejercicio == 1) {
						$pdf->SetX($p[2]);
			  			$pdf->Cell($w[2],$alto,number_format($fila["cue_saldo"],$deci,".",","),0,0,'R',0);
						$pdf->SetX($p[3]);
  						$pdf->Cell($w[3],$alto,number_format(($fila["danterior"]+$fila["debe"]),$deci,".",","),0,0,'R',0);
						$pdf->SetX($p[4]);
					  	$pdf->Cell($w[4],$alto,number_format(($fila["hanterior"]+$fila["haber"]),$deci,".",","),0,0,'R',0);
						$pdf->SetX($p[5]);
  						$pdf->Cell($w[5],$alto,number_format($actual,$deci,".",","),0,0,'R',0);
						$linea+=$alto;
						$pdf->SetY($linea);
/*
						echo '<td align="right">'.number_format($fila["cue_saldo"],$deci,".",",").'</td>';
						echo '<td align="right">'.number_format(($fila["danterior"]+$fila["debe"]),$deci,".",",").'</td>';
						echo '<td align="right">'.number_format(($fila["hanterior"]+$fila["haber"]),$deci,".",",").'</td>';
						echo '<td align="right">'.number_format($actual,$deci,".",",").'</td><tr><td>&nbsp;</td></tr>';
*/
						}
					else {
						$pdf->SetX($p[2]);
			  			$pdf->Cell($w[2],$alto,number_format(($fila["cue_saldo"]+$fila["danterior"]-$fila["hanterior"]),$deci,".",","),0,0,'R',0);
						$pdf->SetX($p[3]);
  						$pdf->Cell($w[3],$alto,number_format($fila["debe"],$deci,".",","),0,0,'R',0);
						$pdf->SetX($p[4]);
			  			$pdf->Cell($w[4],$alto,number_format($fila["haber"],$deci,".",","),0,0,'R',0);
						$pdf->SetX($p[5]);
  						$pdf->Cell($w[5],$alto,number_format($actual,$deci,".",","),0,0,'R',0);
						$linea+=$alto;
						$pdf->SetY($linea);
/*
						echo '<td align="right">'.number_format(($fila["cue_saldo"]+$fila["danterior"]-$fila["hanterior"]),$deci,".",",").'</td>';
						echo '<td align="right">'.number_format(($fila["debe"]),$deci,".",",").'</td>';
						echo '<td align="right">'.number_format($fila["haber"],$deci,".",",").'</td>';
						echo '<td align="right">'.number_format($actual,$deci,".",",").'</td><tr><td>&nbsp;</td></tr>';
*/
					}
				}

				}
				
				mysql_data_seek($result,$pos_actual);		// vuelvo a la posicion original
				$fila=mysql_fetch_assoc($result);
				$nivelactual=$fila['cue_nivel'];
				$arreglo[$nivelactual-1]=$posicion;
			} // < $nivelactual


///----------------------

//		echo '<tr><td>('.$posicion.')'.$fila["cue_codigo"].'</td>';
		if ($encero==1) 
		{
		if (($fila["cue_saldo"]!=0) || ($fila["danterior"]!=0) || ($fila['debe']!=0) || ($fila["hanterior"]!=0) || ($fila['haber']!=0)) 
		{
//			echo '<tr><td>'.$fila["cue_codigo"].'</td>';
//			echo '<td>'.$fila["cue_nombre"].'</td>';
			$linea+=$alto;
			$pdf->SetY($linea);
			$pdf->SetX($p[0]);
  			$pdf->Cell($w[0],$alto,$fila['cue_codigo'],0,0,'L',0);
			$pdf->SetX($p[1]);
  			$pdf->Cell($w[1],$alto,$fila['cue_nombre'],0,0,'L',0);
			if ($fila['cue_nivel'] == $nivelseleccionado) 
			{
				$actual=$fila["cue_saldo"]+($fila["danterior"]+$fila['debe'])-($fila["hanterior"]+$fila['haber']);
				$ultimonivel=$fila['cue_nivel'];
				if ($elejercicio == 1) 
				{
/*
					echo '<td align="right">'.number_format($fila["cue_saldo"],$deci,".",",").'</td>';
					echo '<td align="right">'.number_format(($fila["danterior"]+$fila["debe"]),$deci,".",",").'</td>';
					echo '<td align="right">'.number_format(($fila["hanterior"]+$fila["haber"]),$deci,".",",").'</td>';
					echo '<td align="right">'.number_format($actual,$deci,".",",").'</td></tr>';
*/
					$pdf->SetX($p[2]);
		  			$pdf->Cell($w[2],$alto,number_format($fila["cue_saldo"],$deci,".",","),0,0,'R',0);
					$pdf->SetX($p[3]);
					$pdf->Cell($w[3],$alto,number_format(($fila["danterior"]+$fila["debe"]),$deci,".",","),0,0,'R',0);
					$pdf->SetX($p[4]);
				  	$pdf->Cell($w[4],$alto,number_format(($fila["hanterior"]+$fila["haber"]),$deci,".",","),0,0,'R',0);
					$pdf->SetX($p[5]);
					$pdf->Cell($w[5],$alto,number_format($actual,$deci,".",","),0,0,'R',0);
					$tdebe+=($fila["danterior"]+$fila["debe"]);
					$thaber+=($fila["hanterior"]+$fila["haber"]);
//					$linea+=$alto;
					$pdf->SetY($linea);
					}
				else
				{
/*
					echo '<td align="right">'.number_format(($fila["cue_saldo"]+$fila["danterior"]-$fila["hanterior"]),$deci,".",",").'</td>';
					echo '<td align="right">'.number_format(($fila["debe"]),$deci,".",",").'</td>';
					echo '<td align="right">'.number_format($fila["haber"],$deci,".",",").'</td>';
					echo '<td align="right">'.number_format($actual,$deci,".",",").'</td></tr>';
*/
					$pdf->SetX($p[2]);
		  			$pdf->Cell($w[2],$alto,number_format(($fila["cue_saldo"]+$fila["danterior"]-$fila["hanterior"]),$deci,".",","),0,0,'R',0);
					$pdf->SetX($p[3]);
  					$pdf->Cell($w[3],$alto,number_format(($fila["debe"]),$deci,".",","),0,0,'R',0);
					$pdf->SetX($p[4]);
		  			$pdf->Cell($w[4],$alto,number_format($fila["haber"],$deci,".",","),0,0,'R',0);
					$pdf->SetX($p[5]);
  					$pdf->Cell($w[5],$alto,number_format($actual,$deci,".",","),0,0,'R',0);

					$tdebe+=$fila["debe"];
					$thaber+=$fila["haber"];
//					$linea+=$alto;
					$pdf->SetY($linea);
				}
			} 
		}
		}
		else {
//		echo '<tr><td>'.$fila["cue_codigo"].'</td>';
//		echo '<td>'.$fila["cue_nombre"].'</td>';
		$pdf->SetX($p[0]);
		$pdf->Cell($w[0],$alto,$fila['cue_codigo'],0,0,'L',0);
		$pdf->SetX($p[1]);
		$pdf->Cell($w[1],$alto,$fila['cue_nombre'],0,0,'L',0);
		if ($fila['cue_nivel'] == $nivelseleccionado) {
			$actual=$fila["cue_saldo"]+($fila["danterior"]+$fila['debe'])-($fila["hanterior"]+$fila['haber']);
			$ultimonivel=$fila['cue_nivel'];
			if ($elejercicio == 1) {
/*
				echo '<td align="right">'.number_format($fila["cue_saldo"],$deci,".",",").'</td>';
				echo '<td align="right">'.number_format(($fila["danterior"]+$fila["debe"]),$deci,".",",").'</td>';
				echo '<td align="right">'.number_format(($fila["hanterior"]+$fila["haber"]),$deci,".",",").'</td>';
				echo '<td align="right">'.number_format($actual,$deci,".",",").'</td></tr>';
*/
				$pdf->SetX($p[2]);
	  			$pdf->Cell($w[2],$alto,number_format($fila["cue_saldo"],$deci,".",","),0,0,'R',0);
				$pdf->SetX($p[3]);
				$pdf->Cell($w[3],$alto,number_format(($fila["danterior"]+$fila["debe"]),$deci,".",","),0,0,'R',0);
				$pdf->SetX($p[4]);
			  	$pdf->Cell($w[4],$alto,number_format(($fila["hanterior"]+$fila["haber"]),$deci,".",","),0,0,'R',0);
				$pdf->SetX($p[5]);
  				$pdf->Cell($w[5],$alto,number_format($actual,$deci,".",","),0,0,'R',0);
				$tdebe+=($fila["danterior"]+$fila["debe"]);
				$thaber+=($fila["hanterior"]+$fila["haber"]);
				$linea+=$alto;
				$pdf->SetY($linea);
				}
			else
			{
/*
				echo '<td align="right">'.number_format(($fila["cue_saldo"]+$fila["danterior"]-$fila["hanterior"]),$deci,".",",").'</td>';
				echo '<td align="right">'.number_format(($fila["debe"]),$deci,".",",").'</td>';
				echo '<td align="right">'.number_format($fila["haber"],$deci,".",",").'</td>';
				echo '<td align="right">'.number_format($actual,$deci,".",",").'</td></tr>';
*/
				$pdf->SetX($p[2]);
	  			$pdf->Cell($w[2],$alto,number_format(($fila["cue_saldo"]+$fila["danterior"]-$fila["hanterior"]),$deci,".",","),0,0,'R',0);
				$pdf->SetX($p[3]);
				$pdf->Cell($w[3],$alto,number_format(($fila["debe"]),$deci,".",","),0,0,'R',0);
				$pdf->SetX($p[4]);
	  			$pdf->Cell($w[4],$alto,number_format($fila["haber"],$deci,".",","),0,0,'R',0);
				$pdf->SetX($p[5]);
  				$pdf->Cell($w[5],$alto,number_format($actual,$deci,".",","),0,0,'R',0);
				$tdebe+=$fila["debe"];
				$thaber+=$fila["haber"];
				$linea+=$alto;
				$pdf->SetY($linea);
				}
			} 
			}
			
		$posicion++;
		}	// while
// final
				$elnivel=1;
				for ($i=($ultimonivel-1); $i>=($elnivel); $i--) 
				{
					mysql_data_seek($result,$arreglo[($i-1)]);
					$fila=mysql_fetch_assoc($result);
//					echo '<tr> </tr><tr align="right" style="font-style:italic" ><td colspan=2> Total: '.$fila['cue_codigo'].'-'.$fila['cue_nombre'].'</td>';
//					echo '<tr> </tr><tr align="right" style="font-style:italic" ><td colspan=2> Total: '.$fila['cue_nombre'].'</td>';
					$pdf->SetX($p[1]);
		  			$pdf->Cell($w[1],$alto,'Total: '.$fila['cue_nombre'],0,0,'R',0);
					$actual=$fila["cue_saldo"]+($fila["danterior"]+$fila['debe'])-($fila["hanterior"]+$fila['haber']);
					if ($elejercicio == 1) {
/*
						echo '<td align="right">'.number_format($fila["cue_saldo"],$deci,".",",").'</td>';
						echo '<td align="right">'.number_format(($fila["danterior"]+$fila["debe"]),$deci,".",",").'</td>';
						echo '<td align="right">'.number_format(($fila["hanterior"]+$fila["haber"]),$deci,".",",").'</td>';
						echo '<td align="right">'.number_format($actual,$deci,".",",").'</td><tr><td>&nbsp;</td></tr>';
*/
						$pdf->SetX($p[2]);
			  			$pdf->Cell($w[2],$alto,number_format($fila["cue_saldo"],$deci,".",","),0,0,'R',0);
						$pdf->SetX($p[3]);
  						$pdf->Cell($w[3],$alto,number_format(($fila["danterior"]+$fila["debe"]),$deci,".",","),0,0,'R',0);
						$pdf->SetX($p[4]);
					  	$pdf->Cell($w[4],$alto,number_format(($fila["hanterior"]+$fila["haber"]),$deci,".",","),0,0,'R',0);
						$pdf->SetX($p[5]);
  						$pdf->Cell($w[5],$alto,number_format($actual,$deci,".",","),0,0,'R',0);
						$linea+=$alto;
						$pdf->SetY($linea);
						}
					else {
/*
						echo '<td align="right">'.number_format(($fila["cue_saldo"]+$fila["danterior"]-$fila["hanterior"]),$deci,".",",").'</td>';
						echo '<td align="right">'.number_format(($fila["debe"]),$deci,".",",").'</td>';
						echo '<td align="right">'.number_format($fila["haber"],$deci,".",",").'</td>';
						echo '<td align="right">'.number_format($actual,$deci,".",",").'</td><tr><td>&nbsp;</td></tr>';
*/
					$pdf->SetX($p[2]);
		  			$pdf->Cell($w[2],$alto,number_format(($fila["cue_saldo"]+$fila["danterior"]-$fila["hanterior"]),$deci,".",","),0,0,'R',0);
					$pdf->SetX($p[3]);
 					$pdf->Cell($w[3],$alto,number_format(($fila["debe"]),$deci,".",","),0,0,'R',0);
					$pdf->SetX($p[4]);
		  			$pdf->Cell($w[4],$alto,number_format($fila["haber"],$deci,".",","),0,0,'R',0);
					$pdf->SetX($p[5]);
  					$pdf->Cell($w[5],$alto,number_format($actual,$deci,".",","),0,0,'R',0);
					$linea+=$alto;
					$pdf->SetY($linea);
					}
				}

// total general
/*
				echo '<tr> </tr><tr align="right" style="font-style:oblique" ><td colspan=2> Total General: </td>';	
				echo '<td ></td>';
				echo '<td align="right">'.number_format($tdebe,$deci,".",",").'</td>';
				echo '<td align="right">'.number_format($thaber,$deci,".",",").'</td>';
				echo '<td>&nbsp;</td><tr><td>&nbsp;</td></tr>';
*/
				$pdf->SetX($p[1]);
	  			$pdf->Cell($w[1],$alto,'Total General ',0,0,'R',0);
				$pdf->SetX($p[3]);
				$pdf->Cell($w[3],$alto,number_format($tdebe,$deci,".",","),0,0,'R',0);
				$pdf->SetX($p[4]);
	  			$pdf->Cell($w[4],$alto,number_format($thaber,$deci,".",","),0,0,'R',0);


$pdf->Output();

function nombremes($numeromes) {

$nmes = $numeromes;
$mes[1] = "Enero";
$mes[2] = "Febrero";
$mes[3] = "Marzo";
$mes[4] = "Abril";
$mes[5] = "Mayo";
$mes[6] = "Junio";
$mes[7] = "Julio";
$mes[8] = "Agosto";
$mes[9] = "Septiembre";
$mes[10] = "Octubre";
$mes[11] = "Noviembre";
$mes[12] = "Diciembre";

return $mes[$nmes];
}

?> 
