<?php
include("home.php");

echo '<body>';
$deci=2;	

try
{
	$nivelseleccionado = $_POST['nivelseleccionado'];
	if (!isset($nivelseleccionado)) {

	echo '<fieldset><legend>Datos para el reporte</legend>';

	echo "<form enctype='multipart/form-data' name='form1' action='balcom.php' method='post'>";

	echo "Balance de Comprobaci&oacute;n al mes de: ";
	$sql="SELECT date_format(fech_ejerc,'%d/%m/%Y') AS ultfechax FROM ".$_SESSION['institucion']."sgcaf100";
		$result = $db_con->prepare($sql);
		$result->execute();
		$row = $result->fetch(PDO::FETCH_ASSOC);
		$fecha = $row['ultfechax'];
		$fecha=convertir_fecha($fecha);
		$fecha=strtotime($fecha);
	    $mesdecomprobantes=date('m',$fecha);
		echo '<select name="messeleccionado" size="1">';
		for ($elmes=1; $elmes < 13; $elmes++) {
			echo '<option value="'.$elmes.'" '.(($elmes==$mesdecomprobantes)?'selected':'').'>'.nombremes($elmes).'</option>';}
	  	echo '</select> ';
		$anoseleccionado=date('Y',$fecha);
		echo ' del a&ntilde;o '."<input type='text' name='anoseleccionado' size='10' maxlength='10' readonly='readonly' value='".$anoseleccionado."'/> <br>";
		echo '<input name="elejercicio" type="checkbox" id="elejercicio" value="1" checked>Al Ejercicio<br>';
		echo '<input name="encero" type="checkbox" id="encero" value="1" checked>No mostrar movimientos en cero (0)<br>';
		$sql="SELECT * from ".$_SESSION['institucion']."sgcafniv order by con_nivel";
		$result = $db_con->prepare($sql);
		$result->execute();
		$row = $result->fetch(PDO::FETCH_ASSOC);
		$filas = $result->rowCount()+1;
		echo 'Nivel de detalle <select name="nivelseleccionado" size="1">';
		for ($elmes=1; $elmes < $filas; $elmes++) {
			echo '<option value="'.$elmes.'" '.(($elmes==($filas-2))?'selected':'').'>'.$elmes.'</option>';}
	  	echo '</select> <br>';
		echo "<input class='btn btn-info' type='submit' name='boton' value=\"Procesar\"'>";

	echo '</fieldset><p style="clear:both"><p /> ';
	echo '</form>';
	}
	else
		{
			$messeleccionado=$_POST['messeleccionado'];
			$nivelseleccionado=$_POST['nivelseleccionado'];
			$elejercicio=$_POST['elejercicio'];
			$encero=$_POST['encero'];
			$anoseleccionado=$_POST['anoseleccionado'];


			$con_sp=1;
			if ($con_sp==0)
			{
				$codigo="SELECT cue_codigo, cue_nombre, cue_nivel, cue_saldo, ";
				for ($elmes=1; $elmes < $messeleccionado; $elmes++) {
					$sumar='';
					if ($elmes<10) {$sumar='0'.$elmes; }else {$sumar=$elmes;}
					$codigo.='cue_deb'.$sumar;
					if ($elmes < ($messeleccionado-1)) $codigo.='+';
				}
				if ($messeleccionado == 1) $codigo.='0 as danterior, ';
				else $codigo.=" as danterior, ";
				for ($elmes=1; $elmes < $messeleccionado; $elmes++) {
					$sumar='';
					if ($elmes<10) $sumar='0'.$elmes; else $sumar=$elmes;
					$codigo.='cue_cre'.$sumar;
					if ($elmes < ($messeleccionado-1)) $codigo.='+';
				}
				if ($messeleccionado < 10) $messeleccionado='0'.$messeleccionado;
				$debeactual='cue_deb'.$messeleccionado;
				$haberactual='cue_cre'.$messeleccionado;
				if ($messeleccionado == 1) $codigo.='0 as hanterior, ';
				else $codigo.=" as hanterior, ";
				$codigo.=$debeactual." as debe, ".$haberactual ." as haber from ".$_SESSION['institucion']."sgcaf810 where (cue_nivel<='$nivelseleccionado') order by cue_codigo, cue_nivel"; // limit 250";
				$_SESSION['comando']=$codigo; // ['con_nivel'];
		 		echo $codigo."<br>";
				$result2= $db_con->prepare($codigo);
				$result2->execute();
			}
			else 
			{
				$codigo='';
				$codigo="call sp_blc_comprobacion(".$messeleccionado.", 0, '')";
				$_SESSION['comando']=$codigo; // ['con_nivel'];
		 		echo $codigo."<br>";
				$result2= $db_con->prepare($codigo);
				$result2->execute();
				// $result->closeCursor();

			}

/*
		$fila=$result2->fetchall();
		print_r($fila);

/*
		$fila=$result->fetchall();
		$json_string = json_encode($fila);
		print_r($fila);
		echo $json_string;
		echo 'sali';
*/

			//  or die('Error 810-5');
		echo '<h1>Balance de Comprobaci&oacute;n a '.	nombremes($elmes).'/'.$anoseleccionado.'.</h1> Nivel Detalle '.$nivelseleccionado;
		// .'(En Prueba)...problemas con los subtotales...solucionados los subtotales...falta el total balance....';
	//	echo "<a href='javascript:print()'>  Imprimir igual que en pantalla </a>";
		echo "<a target=\"_blank\" href=\"balcompdf.php?elmes=$elmes&nivelseleccionado=$nivelseleccionado&anoseleccionado=$anoseleccionado&elejercicio=$elejercicio&encero=$encero&\" onClick=\"info.html\', \'\',\'width=250, height=190\')\">Imprimir PDF</a><br><br>"; 
		echo '<table class="table" width="800" border="0">';
		echo '<tr>';
	    echo '<th width="100">Codigo</th>';
	    echo '<th width="200">Cuenta</th>';
	    echo '<th width="100">Saldo Anterior </th>';
	    echo '<th width="100">D&eacute;bitos</th>';
	    echo '<th width="100">Cr&eacute;ditos</th>';
	    echo '<th width="100">Saldo Actual </th>';
		echo '</tr>';
		$nivelactual='1';
		$ultimonivel='';
		$posicion=$tdebe=$thaber=0;

	//		$sql="select count(con_nivel) as niveles from ".$_SESSION['institucion']."sgcafniv";
			$sql="select con_nivel from ".$_SESSION['institucion']."sgcafniv";
			$losniveles=$db_con->prepare($sql);
			$losniveles->execute();
			// or die('Error 810-6');
	//		$nivel=$losniveles->fetch(PDO::FETCH_ASSOC);
			$nivel=$losniveles->rowCount();
		for ($i=0; $i<$nivel['niveles']; $i++) 
			{$arreglo[$i]='';}
		$nivel=$nivel['niveles'];

//		while ($fila = $result2->fetch(PDO::FETCH_ASSOC)) {
		$filas=$result2->fetchall();
//		$result2->closeCursor();

		for ($f=0; $f<count($filas); $f++)
		{
			// ($fila = $result2->fetch(PDO::FETCH_ASSOC)) {
			$fila=$filas[$f];
			if ($fila['cue_nivel']>$nivelactual) 
				$nivelactual=$fila['cue_nivel'];
			$arreglo[$nivelactual-1]=$fila['cue_codigo']; //; $posicion;
			if ($nivelactual=='1')
				echo '<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>';

		///----------------------
			if ($fila['cue_nivel'] < $nivelactual) 
			{
				$pos_actual=$posicion;
				$elnivel=$fila['cue_nivel'];
				for ($i=($ultimonivel-1); $i>=($elnivel); $i--) 
				{
					$fila=data_seek($arreglo[($i-1)], $db_con, $messeleccionado, $filas);
					/* 
					echo '<td> busco '.$arreglo[($i-1)].'</td>';
					echo '<td> '.$arreglo[($i-1)].'vengo';
					print_r($fila);
					echo '</td>';
*/
					if ($encero==1) 
					{
						if (($fila["cue_saldo"]!=0) || ($fila["danterior"]!=0) || ($fila['debe']!=0) || ($fila["hanterior"]!=0) || ($fila['haber']!=0))
						{
							echo '<tr> </tr><tr align="right" style="font-style:italic" ><td colspan=2> Total: '.$fila['cue_nombre'].'</td>';
							$actual=$fila["cue_saldo"]+($fila["danterior"]+$fila['debe'])-($fila["hanterior"]+$fila['haber']);
							if ($elejercicio == 1) 
							{
								echo '<td align="right">'.number_format($fila["cue_saldo"],$deci,".",",").'</td>';
								echo '<td align="right">'.number_format(($fila["danterior"]+$fila["debe"]),$deci,".",",").'</td>';
								echo '<td align="right">'.number_format(($fila["hanterior"]+$fila["haber"]),$deci,".",",").'</td>';
								echo '<td align="right">'.number_format($actual,$deci,".",",").'</td><tr><td>&nbsp;</td></tr>';
							}
							else 
							{
								echo '<td align="right">'.number_format(($fila["cue_saldo"]+$fila["danterior"]-$fila["hanterior"]),$deci,".",",").'</td>';
								echo '<td align="right">'.number_format(($fila["debe"]),$deci,".",",").'</td>';
								echo '<td align="right">'.number_format($fila["haber"],$deci,".",",").'</td>';
								echo '<td align="right">'.number_format($actual,$deci,".",",").'</td><tr><td>&nbsp;</td></tr>';
							}
						}
					}
					else 		
					{
						echo '<tr> </tr><tr align="right" style="font-style:italic" ><td colspan=2> Total: '.$fila['cue_nombre'].'</td>';
						$actual=$fila["cue_saldo"]+($fila["danterior"]+$fila['debe'])-($fila["hanterior"]+$fila['haber']);
						if ($elejercicio == 1) 
						{
							echo '<td align="right">'.number_format($fila["cue_saldo"],$deci,".",",").'</td>';
							echo '<td align="right">'.number_format(($fila["danterior"]+$fila["debe"]),$deci,".",",").'</td>';
							echo '<td align="right">'.number_format(($fila["hanterior"]+$fila["haber"]),$deci,".",",").'</td>';
							echo '<td align="right">'.number_format($actual,$deci,".",",").'</td><tr><td>&nbsp;</td></tr>';
						}
						else 
						{
							echo '<td align="right">'.number_format(($fila["cue_saldo"]+$fila["danterior"]-$fila["hanterior"]),$deci,".",",").'</td>';
							echo '<td align="right">'.number_format(($fila["debe"]),$deci,".",",").'</td>';
							echo '<td align="right">'.number_format($fila["haber"],$deci,".",",").'</td>';
							echo '<td align="right">'.number_format($actual,$deci,".",",").'</td><tr><td>&nbsp;</td></tr>';
						}
					}
				} // for
				// // data_seek($pos_actual, $db_con, $messeleccionado);		// vuelvo a la posicion original
					// $fila=$result->fetch(PDO::FETCH_ASSOC);
				$nivelactual=$fila['cue_nivel'];
				$arreglo[$nivelactual-1]=$fila['cue_codigo']; // $posicion;
				// echo '<td>'.$fila['cue_codigo'].'</td>';
			} // < $nivelactual


	///----------------------

			if ($encero==1) 
			{
				if (($fila["cue_saldo"]!=0) || ($fila["danterior"]!=0) || ($fila['debe']!=0) || ($fila["hanterior"]!=0) || ($fila['haber']!=0)) 
				{
					echo "<tr><td><a href=\"extractoctas3.php?cuenta=".$fila["cue_codigo"]."&datos='no'\" target='_blank'>".$fila["cue_codigo"]."</a></td>";
					echo '<td>'.$fila["cue_nombre"].'</td>';
					if ($fila['cue_nivel'] == $nivelseleccionado) 
					{
						$actual=$fila["cue_saldo"]+($fila["danterior"]+$fila['debe'])-($fila["hanterior"]+$fila['haber']);
						$ultimonivel=$fila['cue_nivel'];
						if ($elejercicio == 1) 
						{
							echo '<td align="right">'.number_format($fila["cue_saldo"],$deci,".",",").'</td>';
							echo '<td align="right">'.number_format(($fila["danterior"]+$fila["debe"]),$deci,".",",").'</td>';
							echo '<td align="right">'.number_format(($fila["hanterior"]+$fila["haber"]),$deci,".",",").'</td>';
							echo '<td align="right">'.number_format($actual,$deci,".",",").'</td></tr>';
							$tdebe+=($fila["danterior"]+$fila["debe"]);
							$thaber+=($fila["hanterior"]+$fila["haber"]);
						}
						else
						{
							echo '<td align="right">'.number_format(($fila["cue_saldo"]+$fila["danterior"]-$fila["hanterior"]),$deci,".",",").'</td>';
							echo '<td align="right">'.number_format(($fila["debe"]),$deci,".",",").'</td>';
							echo '<td align="right">'.number_format($fila["haber"],$deci,".",",").'</td>';
							echo '<td align="right">'.number_format($actual,$deci,".",",").'</td></tr>';
							$tdebe+=$fila["debe"];
							$thaber+=$fila["haber"];
						}
					} 
				}
			}
			else 
			{
				echo '<tr><td>'.$fila["cue_codigo"].'</td>';
				echo '<td>'.$fila["cue_nombre"].'</td>';
				if ($fila['cue_nivel'] == $nivelseleccionado) 
				{
					$actual=$fila["cue_saldo"]+($fila["danterior"]+$fila['debe'])-($fila["hanterior"]+$fila['haber']);
					$ultimonivel=$fila['cue_nivel'];
					if ($elejercicio == 1) 
					{
						echo '<td align="right">'.number_format($fila["cue_saldo"],$deci,".",",").'</td>';
						echo '<td align="right">'.number_format(($fila["danterior"]+$fila["debe"]),$deci,".",",").'</td>';
						echo '<td align="right">'.number_format(($fila["hanterior"]+$fila["haber"]),$deci,".",",").'</td>';
						echo '<td align="right">'.number_format($actual,$deci,".",",").'</td></tr>';
						$tdebe+=($fila["danterior"]+$fila["debe"]);
						$thaber+=($fila["hanterior"]+$fila["haber"]);
					}
					else
					{
						echo '<td align="right">'.number_format(($fila["cue_saldo"]+$fila["danterior"]-$fila["hanterior"]),$deci,".",",").'</td>';
						echo '<td align="right">'.number_format(($fila["debe"]),$deci,".",",").'</td>';
						echo '<td align="right">'.number_format($fila["haber"],$deci,".",",").'</td>';
						echo '<td align="right">'.number_format($actual,$deci,".",",").'</td></tr>';
						$tdebe+=$fila["debe"];
						$thaber+=$fila["haber"];
					}
				} 
			}
			$posicion++;
		}	// while
	// final
		$elnivel=1;
		for ($i=($ultimonivel-1); $i>=($elnivel); $i--) 
		{
			// $fila=data_seek($arreglo[($i-1)], $db_con, $messeleccionado, $filas);
		//				$fila=$result->fetch(PDO::FETCH_ASSOC);
	//					echo '<tr> </tr><tr align="right" style="font-style:italic" ><td colspan=2> Total: '.$fila['cue_codigo'].'-'.$fila['cue_nombre'].'</td>';
			echo '<tr> </tr><tr align="right" style="font-style:italic" ><td colspan=2> Total: '.$fila['cue_nombre'].'</td>';
			$actual=$fila["cue_saldo"]+($fila["danterior"]+$fila['debe'])-($fila["hanterior"]+$fila['haber']);
			if ($elejercicio == 1) 
			{
				echo '<td align="right">'.number_format($fila["cue_saldo"],$deci,".",",").'</td>';
				echo '<td align="right">'.number_format(($fila["danterior"]+$fila["debe"]),$deci,".",",").'</td>';
				echo '<td align="right">'.number_format(($fila["hanterior"]+$fila["haber"]),$deci,".",",").'</td>';
				echo '<td align="right">'.number_format($actual,$deci,".",",").'</td><tr><td>&nbsp;</td></tr>';
			}
			else 
			{
				echo '<td align="right">'.number_format(($fila["cue_saldo"]+$fila["danterior"]-$fila["hanterior"]),$deci,".",",").'</td>';
				echo '<td align="right">'.number_format(($fila["debe"]),$deci,".",",").'</td>';
				echo '<td align="right">'.number_format($fila["haber"],$deci,".",",").'</td>';
				echo '<td align="right">'.number_format($actual,$deci,".",",").'</td><tr><td>&nbsp;</td></tr>';
			}
		}

	// total general
		echo '<tr> </tr><tr align="right" style="font-style:oblique" ><td colspan=2> Total General: </td>';	
		echo '<td ></td>';
		echo '<td align="right">'.number_format($tdebe,$deci,".",",").'</td>';
		echo '<td align="right">'.number_format($thaber,$deci,".",",").'</td>';
		echo '<td>&nbsp;</td><tr><td>&nbsp;</td></tr>';

	echo '</table>';
	echo '<br><br>';

	/*
	$f = fopen("datos.csv","w");
	$sep = ";";

	data_seek($result,0);
		while($reg = mysql_fetch_array($result) ) {
			$linea = $reg['cue_codigo'] . $sep . $reg['cue_nombre'] . $sep . $reg['cue_saldo']. $sep . $reg['danterior']. $sep . $reg['hanterior']. $sep . $reg['debe']. $sep . $reg['haber']; //pones cada campo separado con $sep.
		fwrite($f,$linea);
		}
	fclose($f); 
	$fichero = "datos.csv";

	 header("Content-type: application/vnd.ms-excel") ;
	   //header("Content-Disposition: attachment; filename=kssa.xls" ;
	   header ("filename=datos.csv") ;
	   /*
	header("Content-Description: File Transfer");
	header( "Content-Disposition: filename=".basename($fichero) );
	header("Content-Length: ".filesize($fichero));
	header("Content-Type: application/force-download");
	@readfile($fichero);
	*/


	//	echo "<form enctype='multipart/form-data' name='form1' action='balcom.php' method='post'>";
	////	echo '<input type = 'submit'name = 'formu' value = 'Añadir' tabindex='10' onclick='return compruebafecha(form1)'>
	//	echo "<input type='submit' name='formu' value=\"Generar archivo CSV\" onclick='return exporta()>";
	//	echo "</form>";
		}	// else
}
catch (PDOException $e) 
{
	mensaje(['tipo'=>'warning','titulo'=>'Aviso','texto'=>'<h2>Fallo llamado</h2>'.$sql.$e->getMessage()]);
	die('');
}

echo '</body></html>';

function mostrar($valor)
{
	echo '<td align="right">'.number_format($valor,2,".",",").'</td>';
}

function data_seek($arreglo, $db_con, $messeleccionado, $filas)
{
	try
	{

		// $sql="select * from ".$_SESSION['institucion']."sgcaf810 where cue_codigo=:arreglo";
		// echo $sql.$arreglo;
/*
		$sql="call sp_blc_comprobacion(".$messeleccionado.", 1, '".$arreglo."'	)";
		echo $sql;
		$res=$db_con->prepare($sql);
		$res->execute(); // [":arreglo"=>$arreglo]);
		$rs=$res->fetch(PDO::FETCH_ASSOC);
		return $rs;
*/

		// $indice=array_search($arreglo, $filas); //, true);
		for ($m=0; $m < count($filas); $m++)
			if ($filas[$m]['cue_codigo'] == $arreglo)
			{
//				echo 'posicion '.$m;
//				print_r($filas[$m]);
//				die('esper');
				return $filas[$m];
			}

		// echo ($arreglo).'<br>';
		 // print_r($filas[$indice]);

		return $filas[0];

	}	 
	catch (PDOException $e) 
	{
		mensaje(['tipo'=>'warning','titulo'=>'Aviso','texto'=>'<h2>Fallo llamado</h2>'.$sql.$e->getMessage()]);
		die('');
	}
}
?>
</p>
