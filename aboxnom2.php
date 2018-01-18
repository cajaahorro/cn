<?php
include("home.php");
extract($_GET);
extract($_POST);
extract($_SESSION);
?>
<script src="ajaxabo.js" type="text/javascript"></script>
<script language="javascript">
//Creo una función que imprimira en la hoja el valor del porcentanje asi como el relleno de la barra de progreso
function callprogress(vValor, vgeneral){
// document.getElementById("getprogress").innerHTML = vValor;
// document.getElementById("getProgressBarFill").innerHTML = '<div class="ProgressBarFill" style="width: '+vValor+'%;"></div>';
 document.getElementById("progress-txt").innerHTML = vValor;
 // alert('<div id="progress-bs" class="progress-bar" role="progressbar" style="width:'+vValor+'%; min-width:10%"></div>')
 document.getElementById("progress-txt").innerHTML = '<div class="progress-bar" role="progressbar" style="width:'+vValor+'%; min-width:10%">'+vValor+'%</div>';

 document.getElementById("progress-gral").innerHTML = vgeneral;
 // alert('<div id="progress-bs" class="progress-bar" role="progressbar" style="width:'+vValor+'%; min-width:10%"></div>')
 document.getElementById("progress-gral").innerHTML = '<div class="progress-bar" role="progressbar" style="width:'+vgeneral+'%; min-width:10%">'+vgeneral+'%</div>';
}
</script>
<style type="text/css">
/* Ahora creo el estilo que hara que aparesca el porcentanje y relleno del mismoo*/
      .ProgressBar     { width: 16em; border: 1px solid black; background: #eef; height: 1.25em; display: block; }
      .ProgressBarText { position: absolute; font-size: 1em; width: 16em; text-align: center; font-weight: normal; }
      .ProgressBarFill { height: 100%; background: #aae; display: block; overflow: visible; }
    </style>
</script>

<body <?php if (!$bloqueo) {echo $onload;}?>>

<?php

$readonly=" readonly='readonly'";
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
if (!$accion) {
	echo "<div id='div1'>";
	$sql="select DATE_SUB(NOW(),interval 90 day) as viejos";
	$a_sql=$db_con->prepare($sql);
	$a_sql->execute();
	$viejo=$a_sql->fetch(PDO::FETCH_ASSOC);
	$viejo=$viejo['viejos'];
	$sql="SELECT fecha, sum(cuota) as monto, count(fecha) as cuantos FROM ".$_SESSION['institucion']."sgcaamor where proceso = 1 and semanal = 1 and (fecha > '".$viejo."') group by fecha desc";
	$a_sql=$db_con->prepare($sql);
	$a_sql->execute();
//	echo $sql; 
	echo '<div class="body-container"';
		echo '<div class="container">';
			echo '<div id="archivos" class="col-xs-12 col-sm-12 col-md-6">';
				echo "<form action='aboxnom2.php?accion=Abonar' name='form1' method='post' enctype='multipart/form-data' onsubmit='return realizar_abono(form1)'>";
					echo '<fieldset><legend>Informaci&oacute;n Para Descuentos de Prestamos</legend>';

					echo 'Archivo de Devolucion del Viernes <input class="btn btn-primary" name="archivo[]" type="file" value="Examinar"><br>';
					echo 'Archivo de Devolucion del Sabado <input class="btn btn-warning" name="archivo[]" type="file" value="Examinar"><br>';
					echo 'Archivo de Devolucion del Domingo <input class="btn btn-danger" name="archivo[]" type="file" value="Examinar"><br>';
//	echo '<input type="submit" name="Submit" value="Procesar" />';
			echo '</div>';

			echo '<div id="archivos" class="col-xs-12 col-sm-12 col-md-6">';
				echo '<div class="table-responsive">';
					echo '<table align="center" class="table" width="500" border="1">';
						echo '<tr><th width="50">Fecha</th><th width="80">Monto</th><th width="80">Cantidad</th><th width="40">Procesar</th>';

						$registros=0;
						while($r=$a_sql->fetch(PDO::FETCH_ASSOC)) {
							echo '<tr>';
							echo '<td>'.convertir_fechadmy($r['fecha']).'</td>';
							echo '<td align="right">'.number_format($r['monto'],2,".",",").'</td>';
							echo '<td align="right">'.number_format($r['cuantos'],0,".",",").'</td>';
					//		echo '<td align="right">'.number_format(($r_310['monpre_sdp']-$r_310['monpag_sdp']),2,".",",").'</td>';
							$registros++;
							echo '<td class="centro azul"><input type="checkbox" id="cancelar'.$registros.'" name="cancelar'.$registros.'" value='.$r["fecha"] .' onClick="amor_cap()" ';
							echo '></td></tr>' ;
						}
						echo "<input type = 'hidden' value ='".$registros."' name='registros' id='registros'>";
					echo '</table>';
					echo '</legend>';
				echo '</div>';

//echo '<div title="sss" dir="ltr"  style="margin-right:auto">';
// echo "<div style='width:50%;float:left'>";
//echo 'listausu();';
//echo "</div>";


//echo "<div style='float:left;display:inline'>";
//echo 'divi3';
//echo "</div>";

				echo '<fieldset><legend>Resumen Para Descuentos de Prestamos</legend>';
				echo '<table align="center" class="table"  width="300" border="1">';
					echo '<tr><td>Total Nominas </td><td>';
						echo '<input type="text" name="totalnominas" id="totalnominas" size="8" maxlengt="8"  value=0.00 readonly="readonly"></td></tr>';
						echo '<tr><td>Total Registros</td><td>';
						echo '<input type="text" name="totalregistros" id="totalregistros" size="5" maxlengt="5"  value=0  readonly="readonly"></td></tr>';
				echo '</table>';
				echo '</legend>';
				echo '<input class="btn btn-success"  type="submit" name="Submit" value="Realizar Abono a Prestamos (Asientos Contables)" />';
			echo '</form>';
		echo '</div>';
	echo '</div>';
}	// if (!$accion) 

if ($accion=='Abonar') {
	$registros=$_POST['registros'];

	extract($_POST);
	// phpinfo();
	echo '<div class="row">';
		echo '<div class="col-md-4">';
			echo 'Proceso General <div id="progress-gral" class="progress progress-bar-info">';
				// echo '<div id="progress-bs" class="progress-bar" role="progressbar" style="width:30%; min-width:10%">';
				echo '<div class="progress-bar" role="progressbar" style="width:30%">';
					echo '0%';
				echo '</div>';
				echo '<span class="sr-only"></span>';
			echo '</div>';
		echo '</div>';
	echo '</div>';

	echo '<div class="row">';
		echo '<div class="col-md-4">';
			echo 'Proceso Interno <div id="progress-txt" class="progress  progress-bar-success">';
				// echo '<div id="progress-bs" class="progress-bar" role="progressbar" style="width:30%; min-width:10%">';
				echo '<div class="progress-bar" role="progressbar" style="width:30%">';
					echo '0%';
				echo '</div>';
				echo '<span class="sr-only"></span>';
			echo '</div>';
		echo '</div>';
	echo '</div>';
	//echo "<script>callprogress(8,55)</script>"; //llamo a la función JS(JavaScript) para actualizar el progreso
//  die('espero');
for ($veces=0;$veces<3;$veces++)	// recorrer los 3 archivos y procesar
{
		// primero reviso el archivo de indomiciliados  archivo del domingo
	$copiado = 'SI';		// cambiar a no y resolver este problema
	$elarchivo=$nom_arc[$veces];
//	phpinfo();
//	echo 'el archivo '.$elarchivo;
	if($_FILES['archivo']['name'][$veces]!=='') 
	{
		$salida='devoluciones/sica_'.$_FILES['archivo']['name'][$veces];
		//$archivosalida=fopen ($salida, "w+");
		// echo 'archivosalida'. $salida;
		$nueva_ruta='devoluciones/';
		$ruta_total = $_SERVER['DOCUMENT_ROOT'].$nueva_ruta;
		$ruta_total = $_SERVER['DOCUMENT_ROOT']."devoluciones/".$_FILES['archivo']['name'][$veces];
		$BASENAMES = basename( $_FILES['archivo']['name'][$veces]);
		$nuevo_nombre=$BASENAMES;
		// echo 'el archivo '.$elarchivo. ' / '.'$$elarchivo' .'<<<<<';
		// echo $_FILES['archivo']['name'][$veces]. '-->resultado '.(is_uploaded_file($_FILES['archivo']['tmp_name'][$veces]));
		try
		{
			//if (is_uploaded_file($HTTP_POST_FILES['archivo']['tmp_name'][$veces])) {
			if (is_uploaded_file($_FILES['archivo']['tmp_name'][$veces])) {
				// echo 'veces'.$HTTP_POST_FILES['archivo']['tmp_name'][$veces];
				// $destino='/xcajaweb/devoluciones/';
				$destino='devoluciones/'; // /xcajaweb/login/
				$destino.=$_FILES['archivo']['name'][$veces];
				// echo '<br>destino '.$destino.'<br>fuente '.$_FILES['archivo']['tmp_name'][$veces] . ' ---- '.$ruta_total;r
				if (move_uploaded_file($_FILES['archivo']['tmp_name'][$veces],$salida))
				// if (move_uploaded_file($ruta_total,$destino))
				{
					mensaje(array(
						"tipo"=>"success",
							"texto"=>"Se movi&oacute; el archivo correctamente el archivo ".$_FILES['archivo']['name'][$veces],
							));
					//echo "<p>Se movi&oacute; el archivo correctamente<br></p>";
				}
					else {
						// var_dump($_FILES['archivo']['tmp_name'][$veces], $destino);
						mensaje(array(
							"tipo"=>"danger",
							"texto"=>"Fallo la Copia",
							));
						die ('fallo copia');
					}
			} 
			else {
				mensaje(array(
					"tipo"=>"danger",
					"texto"=>"Posible attaque",
				));
			   	die ("Possible file upload attack. Filename: " . $HTTP_POST_FILES['archivo']['name'][$veces]);
			}
		}
		catch (PDOException $e) 
		{
		    die('rev2.'.$e->getMessage());
		    return false;
		}
			$archivo_name = $nuevo_nombre; 
			$original = $archivo_name;
			$extension = explode(".",$archivo_name);
			$num = count($extension)-1;
			if (1 == 1) { // (strtoupper($extension[$num]) == "TXT") {
				if($copiado = 'SI') { // $archivo_size < 60000) {
					// separar el archivo con los datos
					procesar($archivo_name,$fechaaporte,$ip,$archivosalida,$numerocuotas,$veces, $db_con);
				}
			else
				{ echo "el archivo supera los 60kb"; }
			}
		else
			{ echo "el formato de archivo no es valido, solo .txt => ".$original; }
		set_time_limit(30);
	}

	for ($i=0;$i<$registros;$i++)	// no es necesarios revisar el check si aparece es porq estan seleccionados para hacer el asiento 
	{
		$variable='cancelar'.($i+1);
		if (!empty($$variable)) 
		{


			$fecha=explode('-',$$variable);
			$b=$fecha[0].'-'.$fecha[1].'-'.$fecha[2];
			$fecha=$b;

			$nuevafecha="select date_add('$fecha',INTERVAL ".$veces." DAY) as fecha";
			$rsqln=$db_con->prepare($nuevafecha);
			$rsqln->execute();
			$asqln=$rsqln->fetch(PDO::FETCH_ASSOC);
			$otrafecha=($asqln['fecha']);

			$sql="select nropre from ".$_SESSION['institucion']."sgcaamor where fecha ='$fecha' and proceso = 1 and (semanal = 1) order by codsoc"; // limit 10";
//			echo $sql.'<br>';
			$a_amor=$db_con->prepare($sql);
			$a_amor->execute();
			$tiempoestimado=$a_amor->rowCount();
			$ValorTotal=$tiempoestimado;
			$cuantos=0;
			if ($tiempoestimado > 0) {
				$sql="select * from ".$_SESSION['institucion']."sgcaf360 order by cod_pres";
				$a360=$db_con->prepare($sql);
				$a360->execute();
				$posicion=0;
				while ($r360 = $a360->fetch(PDO::FETCH_ASSOC)){
					$posicion++;
					$capital[$posicion]=0;
					$interes[$posicion]=0;
					$tipoi[$posicion]=$r360['tipo'];
					$codigos[$posicion]=$r360['cod_pres'];
					$interesg[$posicion]=trim($r360['otro_int']);
				}

				$contarA = $testatutario = $thipotecario = $tcomercial = $testatutarioA =  0;
				$estnocobrado = $intestnocobrado = $comnocobrado = $intcomnocobrado = $hipnocobrado = $inthipnocobrado = 0;
	
				$referencia='';
				$ofecha=explode('-',$otrafecha);
				$b=$ofecha[0].'-'.$ofecha[1].'-'.$ofecha[2];
				$elasiento1=$ofecha[0].$ofecha[1].$ofecha[2].'001';
				$elasiento2=$ofecha[0].$ofecha[1].$ofecha[2].'002';
				$elasiento3=$ofecha[0].$ofecha[1].$ofecha[2].'003';
				$elasiento4=$ofecha[0].$ofecha[1].$ofecha[2].'004';
				$elasiento5=$ofecha[0].$ofecha[1].$ofecha[2].'005';
				$elasiento6=$ofecha[0].$ofecha[1].$ofecha[2].'006';
				$elasientof=$ofecha[0].$ofecha[1].$ofecha[2].'007';
				$elasiento8=$ofecha[0].$ofecha[1].$ofecha[2].'008';
//				$elasiento9=$ofecha[0].$ofecha[1].$ofecha[2].'009';
				crear_encabezado($elasiento1,$b,'por prestamos hipotecarios', $db_con);
				$ofecha=$b;
				crear_encabezado($elasiento2,$b,'para intereses hipotecarios', $db_con);
				crear_encabezado($elasiento3,$b,'por prestamos estatutarios', $db_con);
				crear_encabezado($elasiento4,$b,'para control interno', $db_con);
				crear_encabezado($elasiento5,$b,'por convenios comerciales', $db_con);
				crear_encabezado($elasiento6,$b,'para intereses diferidos', $db_con);
//				crear_encabezado($elasiento9,$b,'Cierre de Intereses');
				$tinteresest=0;

				for ($j=1;$j<5;$j++) {
//				for ($j=3;$j<5;$j++) { // para prueba eliminar

					if ($j==1) $aprocesar='Estatutario';
					else if ($j==2) $aprocesar='Comercial';
					else if ($j==3 )$aprocesar='Hipotecario';
					else $aprocesar='EstatutarioA';
					$sql="select * from ".$_SESSION['institucion']."sgcaamor where ((fecha ='$fecha') and (proceso = 1) and (semanal = 1) and (tipo ='$aprocesar')) order by codsoc"; // limit 10";
//					echo $sql.'<br>';
					echo "<h2>Procesando $aprocesar</h2><br>";
					try
					{
						$a_amor=$db_con->prepare($sql);
						$a_amor->execute();
					}
					catch (PDOException $e) 
					{
					    die('rev2.'.$e->getMessage());
					}

					$tiempoestimado=$a_amor->rowCount();
					if ($tiempoestimado < 30)
						$tiempoestimado=30;
					set_time_limit($tiempoestimado);
					while ($r_amor = $a_amor->fetch(PDO::FETCH_ASSOC)) {

						$cuantos++;
						$porcentaje = $cuantos * 100 / $ValorTotal; //saco mi valor en porcentaje
						echo "<script>callprogress(".round($porcentaje).",".(($veces/3)*100).")</script>"; //llamo a la función JS(JavaScript) para actualizar el progreso
//						flush(); //con esta funcion hago que se muestre el resultado de inmediato y no espere a terminar todo el bucle con los 25 registros para recien mostrar el resultado
//						ob_flush();

						// progress bootstrap

						// revisar si esta indomiciliado
						$mostrar=0;
//						$mostrar=0;
						$estado=indomiciliado($r_amor['cedula'], $ofecha,$motivo,$mostrar, $db_con);
	
						$cuentaprestamo=$r_amor['cuent_p'];
						$cuentadiferido=$r_amor['cuent_i'];
						$cuentainteres =$r_amor['cuent_d'];
						$referencia = $r_amor['nropre'];
						for ($k=1;count($codigos);$k++)
							if ($r_amor['codpre']==$codigos[$k]) {
								$posicion=$k;
								break; }
						if ($estado == 0) 
						{
							$capital[$posicion]+=$r_amor['capital'];
							$interes[$posicion]+=$r_amor['interes'];
						}
						////////  estatutario 
						if ($r_amor['tipo']=='Estatutario') 
						{
							$asientoa=$elasiento3;
							$asientob=$elasiento6;
							$debe=$r_amor['capital']; // -$r_amor['interes'];
							$abonoc=$debe;
							echo '1';
							agregar_f820($asientoa, $b, '-', $cuentaprestamo, 'Ret. Prest.Est. del '.convertir_fechadmy($r_amor['fecha']), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
							echo '2';
						echo 'estad'.$estado;
							if ($estado == 1)
								agregar_f820($asientoa, $b, '+', $cuentaprestamo, 'Ret. Dev. Prest.Est. del '.convertir_fechadmy($r_amor['fecha']).$motivo, $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
							$debe=$r_amor['interes'];
							$abonoi=$debe;
							agregar_f820($asientob, $b, '+', $cuentadiferido, 'Int.Dif.Prest.Est. del '.convertir_fechadmy($r_amor['fecha']), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
							if ($estado == 1)
								agregar_f820($asientob, $b, '-', $cuentadiferido, 'Int.Dif.Dev.Prest.Est. del '.convertir_fechadmy($r_amor['fecha']).$motivo, $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
							if ($estado == 0)
							{
								$testatutario+=$r_amor['capital'];
								$tinteresest+=$debe;
							}
							else 
							{
								$estnocobrado+=$r_amor['capital'];
								$intestnocobrado+=$debe;
							}
						}
						////////  fin estatutario 
						////////  comercial
						if ($r_amor['tipo']=='Comercial') 
						{
							$asientoa=$elasiento5;
							$asientob=$elasiento6;
							$debe=$r_amor['capital']; // +$r_amor['interes'];
							$debe=$r_amor['capital']-$r_amor['interes'];
							$abonoc=$debe;
							agregar_f820($asientoa, $b, '-', $cuentaprestamo, 'Ret.Conv. Comer. del '.convertir_fechadmy($r_amor['fecha']), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
							if ($estado == 1)
								agregar_f820($asientoa, $b, '+', $cuentaprestamo, 'Ret. Dev. Conv. Comer. del '.convertir_fechadmy($r_amor['fecha']).$motivo, $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
							if ($estado == 0)
							{
								$tcomercial+=$r_amor['capital'];
								$debe=$r_amor['interes'];
								$tcomercial-=$debe;
							}
							else 
							{
								$comnocobrado+=$r_amor['capital'];
								$intcomnocobrado+=$r_amor['interes'];
							}
							$abonoi=0;
						}
						////////  fin comercial
						////////  hipotecario
						if ($r_amor['tipo']=='Hipotecario') 
						{ // hipotecario
							$asientoa=$elasiento1;
							$debe=$r_amor['capital']; // -$r_amor['interes'];
							agregar_f820($asientoa, $b, '-', $cuentaprestamo, 'Ret.Prest.Hipot. del '.convertir_fechadmy($r_amor['fecha']), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
							if ($estado == 1)
								agregar_f820($asientoa, $b, '+', $cuentaprestamo, 'Ret. Dev. Prest.Hipot. del '.convertir_fechadmy($r_amor['fecha']).$motivo, $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
							if ($estado == 0)
							{
								$thipotecario+=$debe; // $r_amor['capital'];
								$abonoc=$debe;
								$abonoi=$r_amor['interes'];
							}
							else 
							{
								$hipnocobrado+=$r_amor['capital'];
								$inthipnocobrado+=$r_amor['interes'];
							}
						}
						////////  fin hipotecario
						// actualizo prestamo y amortizacion como procesada

						////////  estatutarioA
						if ($r_amor['tipo']=='EstatutarioA') 
						{ // Estatuarios Amortizados
							$contarA++;
							$asientoa=$elasiento3;
							$debe=$r_amor['capital']; // -$r_amor['interes'];
							agregar_f820($asientoa, $b, '-', $cuentaprestamo, 'Ret.Prest.Est. del '.convertir_fechadmy($r_amor['fecha']), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
//							echo $contarA.' >>> '.$cuentaprestamo. ' '.$referencia.'<br>';
							if ($estado == 1)
								agregar_f820($asientoa, $b, '+', $cuentaprestamo, 'Ret. Dev. Prest.Est. del '.convertir_fechadmy($r_amor['fecha']).$motivo, $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
//								echo 'o';
							if ($estado == 0)
							{
								$testatutarioA+=$debe; // $r_amor['capital'];
								$abonoc=$debe;
								$abonoi=$r_amor['interes'];
							}
							else 
							{
								$EstAnocobrado+=$r_amor['capital'];
								$intEstAnocobrado+=$r_amor['interes'];
							}
						}
						////////  estatutarioA

						// actualizo prestamo y amortizacion como procesada
						if ($estado == 0)
						{
							$upd_310="update ".$_SESSION['institucion']."sgcaf310 set monpag_sdp=monpag_sdp+$abonoc, monint=monint + $abonoi, ultcan_sdp=ultcan_sdp+1 where registro=".$r_amor['pos310'];
							$upd_310=$db_con->prepare($upd_310);
							$res=$upd_310->execute();
							if (! $res)
								echo $upd_310.'<br>';
							// revisar fiadores
							revisar_fiadores($r_amor['pos310']);
							// fin revisar fiadores
							$upd_amor="update ".$_SESSION['institucion']."sgcaamor set proceso = 2, abonado = now(), ip_abono = '$ip' where registro = ".$r_amor['registro'];
							$upd_amor=$db_con->prepare($upd_amor);
							$res=$upd_amor->execute();
							if (! $res)
								echo $upd_amor.'<br>';	
						}
						} 	// next
				
						// cierro asiento estatutarios
						$sql="select * from ".$_SESSION['institucion']."sgcaf000 where tipo='CtaPrexCobAmo'";
						$result=$db_con->prepare($sql); // or die ("<p />El usuario $usuario no pudo conseguir la cuenta x pagar<br>$sql);
						$result->execute();
						$cuentas=$result->fetch(PDO::FETCH_ASSOC);
						$cuenta_amortizacion=trim($cuentas['nombre']);
						$sql="select * from ".$_SESSION['institucion']."sgcaf000 where tipo='CtaPrexCobBco'";
						$result=$db_con->prepare($sql); 
						$result->execute();
						$cuentas=$result->fetch(PDO::FETCH_ASSOC);
						$cuentabanco=trim($cuentas['nombre']);			

						$sql="select * from ".$_SESSION['institucion']."sgcaf000 where tipo='IngPreBco'";
						$sql="select * from ".$_SESSION['institucion']."sgcaf000 where tipo='CtaDepTransito'";
						$result=$db_con->prepare($sql); 
						$result->execute();
						$cuentas=$result->fetch(PDO::FETCH_ASSOC);
						$cuentaingbanco=trim($cuentas['nombre']);			

						$referencia='';
						if ($j==1) {
							$debe=$testatutario; // -$tinteresest; //-$tinteresest;
							agregar_f820($elasiento3, $b, '+', $cuenta_amortizacion, 'Total Retenciones del '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 	
							agregar_f820($elasiento4, $b, '-', $cuentabanco, 'Amort. Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
							agregar_f820($elasiento4, $b, '+', $cuentaingbanco, 'Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 		


							$debe= $tinteresest;
//							agregar_f820($elasiento3, $b, '+', $cuenta_amortizacion, 'Total Retenciones del '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 	

//							agregar_f820($elasiento4, $b, '-', $cuentabanco, 'Amort. Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
//							agregar_f820($elasiento4, $b, '+', $cuentaingbanco, 'Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 		
							for ($k=1;$k<=count($codigos);$k++) 
								if ($tipoi[$k]=='Estatutario')
									if ($interesg[$k] != 'NO TIENE') {
									$cuenta1=$interesg[$k];
									$debe=$interes[$k];
									if ($debe > 0)
									agregar_f820($elasiento6, $b, '-', $cuenta1, 'Interes del '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
								}
						}
						// para los comerciales 
						if ($j==2) {
							$intlocal=0;
							for ($k=1;$k<=count($codigos);$k++)
								if ($tipoi[$k]=='Comercial') 
								if ($interesg[$k] != 'NO TIENE'){
									$cuenta1=$interesg[$k];
									$debe=$interes[$k];
									$intlocal+=$debe;
									if ($debe > 0)
										agregar_f820($elasiento5, $b, '-', $cuenta1, 'Interes del '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
								}
								$debe=$tcomercial;
								agregar_f820($elasiento5, $b, '+', $cuenta_amortizacion, 'Total Retenciones del '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
								agregar_f820($elasiento4, $b, '-', $cuentabanco, 'Amort. Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
								agregar_f820($elasiento4, $b, '+', $cuentaingbanco, 'Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 

								$debe=$intlocal;
								agregar_f820($elasiento5, $b, '+', $cuenta_amortizacion, 'Total Retenciones del '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
//								agregar_f820($elasiento4, $b, '-', $cuentabanco, 'Amort. Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
//								agregar_f820($elasiento4, $b, '+', $cuentaingbanco, 'Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
							}	
				
						// para los hipotecarios
						if ($j==3) {
							$tinteresh=0;
							for ($k=0;$k<count($codigos);$k++)
								if ($tipoi[$k]=='Hipotecario') 
								if ($interesg[$k] != 'NO TIENE'){
									$cuenta1=$interesg[$k];
									$debe=$interes[$k];
									$tinteresh+=$debe;
									if ($debe > 0)
										agregar_f820($elasiento1, $b, '-', $cuenta1, 'Interes del '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
								}
							$debe=$thipotecario; //+$tinteresh;
							agregar_f820($elasiento1, $b, '+', $cuenta_amortizacion, 'Total Retenciones del '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
							agregar_f820($elasiento2, $b, '-', $cuentabanco, 'Amort. Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
							agregar_f820($elasiento2, $b, '+', $cuentaingbanco, 'Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 


							$debe=$tinteresh;
							agregar_f820($elasiento1, $b, '+', $cuenta_amortizacion, 'Total Retenciones del '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
							agregar_f820($elasiento2, $b, '-', $cuentabanco, 'Amort. Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
							agregar_f820($elasiento2, $b, '+', $cuentaingbanco, 'Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
						}
						// para los Estatutarios A
						if ($j==4) {
							$tinteresh=0;
							for ($k=0;$k<count($codigos);$k++)
								if ($tipoi[$k]=='EstatutarioA') 
								if ($interesg[$k] != 'NO TIENE'){
									$cuenta1=$interesg[$k];
									$debe=$interes[$k];
									$tinteresh+=$debe;
									if ($debe > 0)
										agregar_f820($elasiento3, $b, '-', $cuenta1, 'Interes del '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
								}
							$debe=$testatutarioA; //+$tinteresh;
							agregar_f820($elasiento3, $b, '+', $cuenta_amortizacion, 'Total Retenciones del '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
							agregar_f820($elasiento4, $b, '-', $cuentabanco, 'Amort. Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
							agregar_f820($elasiento4, $b, '+', $cuentaingbanco, 'Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 


							$debe=$tinteresh;
							agregar_f820($elasiento3, $b, '+', $cuenta_amortizacion, 'Total Retenciones del '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
							agregar_f820($elasiento4, $b, '-', $cuentabanco, 'Amort. Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
							agregar_f820($elasiento4, $b, '+', $cuentaingbanco, 'Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
						}
					// falta actualizar el prstamo y el amortizacion
					}	// while 
					$sql="update ".$_SESSION['institucion']."sgcaf310 set stapre_sdp='C', renovado=1 where (codpre_sdp='046' and ultcan_sdp=nrocuotas)";
					$result=$db_con->prepare($sql);
					$res=$result->execute();
					if (! $res)
							echo $sql.'<br>';
			} // el registro esta marcado


	if ($veces>1) {
		crear_encabezado($elasientof,$b,'cierre de nominas fallidas', $db_con);
		crear_encabezado($elasiento8,$b,'p/r cierre deposito en transito', $db_con);
		$debe = $estnocobrado ;
		agregar_f820($elasientof, $b, '-', $cuentabanco, 'Amort. estnocobrado Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
		agregar_f820($elasientof, $b, '+', $cuenta_amortizacion, 'Prest. estnocobrado p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
		
//		$debe = $intestnocobrado ;
//		agregar_f820($elasientof, $b, '-', $cuentabanco, 'Amort. intestnocobrado Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
//		agregar_f820($elasientof, $b, '+', $cuenta_amortizacion, 'Prest.intestnocobrado p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
		$debe = $comnocobrado;
		agregar_f820($elasientof, $b, '-', $cuentabanco, 'Amort. comnocobrado Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
		agregar_f820($elasientof, $b, '+', $cuenta_amortizacion, 'Prest.comnocobrado p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
		
//		$debe = $intcomnocobrado ;
//		agregar_f820($elasientof, $b, '-', $cuentabanco, 'Amort. intcomnocobrado Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
//		agregar_f820($elasientof, $b, '+', $cuenta_amortizacion, 'Prest. intcomnocobrado p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 

		$debe = $hipnocobrado ;
		agregar_f820($elasientof, $b, '-', $cuentabanco, 'Amort. hipnocobrado Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
		agregar_f820($elasientof, $b, '+', $cuenta_amortizacion, 'Prest. hipnocobrado p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
		
//		$debe = $inthipnocobrado ;
//		agregar_f820($elasientof, $b, '-', $cuentabanco, 'Amort. inthipnocobrado Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
//		agregar_f820($elasientof, $b, '+', $cuenta_amortizacion, 'Prest. inthipnocobrado p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
		$sql="select * from ".$_SESSION['institucion']."sgcaf000 where tipo='CtaPrexCobBco'";
		$result=$db_con->prepare($sql); 
		$result->execute();
		$cuentas=$result->fetch(PDO::FETCH_ASSOC);
		$deposito=$cuentabanco;
		$cuentabanco=trim($cuentas['nombre']);

		$sql="select * from ".$_SESSION['institucion']."sgcaf000 where tipo='ComisionDom'";
		$result=$db_con->prepare($sql); 
		$result->execute();
		$cuentas=$result->fetch(PDO::FETCH_ASSOC);
		$comision=trim($cuentas['nombre']);
		$debe=0.01;
		agregar_f820($elasiento8, $b, '+', $cuentabanco, 'Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
		agregar_f820($elasiento8, $b, '+', $deposito, 'Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
		agregar_f820($elasiento8, $b, '-', $cuentabanco, 'Comision Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
		agregar_f820($elasiento8, $b, '-', $cuentabanco, 'Comision Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 

	}
/*
	// cerrar ingreso de intereses
	$sql="SELECT cuent_int, otro_int FROM ".$_SESSION['institucion']."sgcaf360 GROUP BY cuent_int ";
	$result=$db_con->prepare($sql);
	$result->execute();
	while ($r360 = $result->fetch(PDO::FETCH_ASSOC)){
		$lacuenta=trim($r360['cuent_int']);
		$reverso=trim($r360['otro_int']);
		$tamano=strlen($lacuenta);
		if (($tamano > 4) and ($tamano < 19))
		{
			$sql810="select cue_codigo from ".$_SESSION['institucion']."sgcaf810 where substr(cue_codigo,1,".$tamano.") = '$lacuenta' order by cue_codigo";
			$res810=$db_con->prepare($sql810);
			$res810->execute();
			while ($r810 = $res810->fetch(PDO::FETCH_ASSOC)){
				$cuenta=$r810['cue_codigo'];
				if (substr($cuenta,-4)!='0001')
				{
					$debe=buscar_saldo_f810($cuenta);
					if ($debe > 0) // hacer reverso
					{
						agregar_f820($elasiento9, $b, '-', $cuenta, 'Cierre de Interes '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
						agregar_f820($elasiento9, $b, '+', $reverso, 'Cierre de Interes '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
					}
				}
				
			}
		}
	}
*/

	echo "<script>callprogress(100,100)</script>"; 
	$comando = "update ".$_SESSION['institucion']."sgcaf8co set fechanominalunes= now()";
	$resultado=$db_con->prepare($comando);
	$resultado->execute();
	}

	else '<h2>Ya fue procesada anteriormente </h2>';
	}	// ciclo de registros marcados 
	set_time_limit(30);	
}	// if (!$accion=='Abonar')
}

function buscar_saldo_f810($cuenta)
{
	$sql_f810="select cue_saldo from ".$_SESSION['institucion']."sgcaf810 where cue_codigo='$cuenta'";
//	echo $sql_f810;
	$lacuentas=$db_con->prepare($sql_f810); //  or die ("<p />El usuario $usuario no pudo conseguir el saldo contable<br>".mysql_error()."<br>".$sql);
	$lacuentas->execute();
	$lacuenta=$lacuentas->fetch(PDO::FETCH_ASSOC);
	$saldoinicial=$lacuenta['cue_saldo'];
	
	$sql_f820="select com_monto1, com_monto2 from ".$_SESSION['institucion']."sgcaf820 where com_cuenta='$cuenta' order by com_fecha";
//	echo $sql_f820;
	$lacuentas=$db_con->prepare($sql_f820); //  or die ("<p />El usuario $usuario no pudo conseguir los movimientos contables<br>".mysql_error()."<br>".$sql);
	$lacuentas->execute();
	while($lascuenta=$lacuentas->fetch(PDO::FETCH_ASSOC)) {
		$saldoinicial+=$lascuenta['com_monto1'];
//		echo $saldoinicial.'<br>';
		$saldoinicial-=$lascuenta['com_monto2'];
//		echo $saldoinicial.'<br>';
	}
return $saldoinicial;
}

function revisar_fiadores($registro)
{
	$sqlp="select nropre_sdp, cuota_ucla, monpre_sdp from ".$_SESSION['institucion']."sgcaf310 where registro = '$registro'";
	$resp=$db_con->prepare($sqlp);
	$resp->execute();
//	if (!$db_con->prepare($sqlp)) die ("El usuario $usuario no tiene permiso para consultar prestamos.<br>".$sqlp);
	$apre=$resp->fetch(PDO::FETCH_ASSOC);
	$numero=$apre['nropre_sdp'];
	$sqlf="select sum(monto_fia) as totalfianza from ".$_SESSION['institucion']."sgcaf320 where nropre_fia='$numero' and tipmov_fia='F' group by nropre_fia";
	$resf=$db_con->prepare($sqlf);
	$resf->execute();
	$rfia = $resf->fetch(PDO::FETCH_ASSOC);
	$totalfianza=$rfia['totalfianza'];
	if ($rfia['totalfianza'] > 0) {
		$sqlf="select * from ".$_SESSION['institucion']."sgcaf320 where nropre_fia='$numero' and tipmov_fia='F' ";
		$resf=$db_con->prepare($sqlf);
		$resf->execute();
		while ($rfia = $resf->fetch(PDO::FETCH_ASSOC)){
			$regfia=$rfia['registro'];
			$proporcion=$rfia['monto_fia'];
			$proporcion = (($proporcion * 100) / $apre['monpre_sdp'] ) ;
			$abonofianza = ($apre['cuota_ucla'] * ($proporcion / 100));
			$ufia="update ".$_SESSION['institucion']."sgcaf320 set monlib_fia = monlib_fia + '$abonofianza'";
			if (($rfia['monlib_fia'] + $abonofianza) >= $rfia['monto_fia'])
				$ufia.=", tipmov_fia = 'L'";
			$ufia.=" where registro = '$regfia'";
			$resfia=$db_con->prepare($ufia);
			$resfia->execute();
		}
	}
}

function crear_encabezado($asiento,$fecha,$cuento, $db_con)
{
	echo "<p>Realizando Abonos / Registros contables del asiento <strong><a target=\"_blank\" href='editasi2.php?asiento=$asiento'>$asiento </a></strong> $cuento </p>";
	$sql = "INSERT INTO ".$_SESSION['institucion']."sgcaf830 (enc_clave, enc_fecha, enc_desco, enc_desc1, enc_debe, enc_haber, enc_item, enc_dif, enc_igual, enc_refer, enc_sw, enc_explic) VALUES ('$asiento', '$fecha', '$cuento','',0,0,0,0,0,0,0,'$cuento')"; 
	try
	{
		$res=$db_con->prepare($sql);
		$res->execute();
	}
	catch (PDOException $e) 
	{
	    die('rev2.'.$e->getMessage());
	    return false;
	}
	if (!$res)
	{
		mensaje(array(
			"tipo"=>"danger",
			"texto"=>"El usuario no tiene permisos para añadir asientos",
		));
		die ("El usuario $usuario no tiene permiso para añadir Asientos.<br>".$sql);
	}
}

function indomiciliado($cedula, $fecha, &$motivo, $mostrar, $db_con)
{
	$sqlin="select * from ".$_SESSION['institucion']."sgcaresb where cedula = '$cedula' and fechagen = '$fecha' and substr(cadena,1,4)='6210'";
	try
	{
		$res_in=$db_con->prepare($sqlin);
		$res_in->execute();
//	echo $sqlin;
	}
	catch (PDOException $e) 
	{
   		mensaje(['tipo'=>'warning','titulo'=>'Aviso','texto'=>'<h2>Fallo llamado</h2>'.$e->getMessage()]);
		die('Fallo call'. $e->getMessage());
	}
	$r_in=$res_in->fetch(PDO::FETCH_ASSOC);
//	echo 'estatus '.$r_in['estatus'].'<br>';
	$motivo='';
//	$mostrar = 1;

//6210J301781678V02541709       001010824575802000045090000000000146822010824501CGE0001 FONDOS INSUFICIENTES
//12345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890
//---------+---------+---------+---------+---------+---------+---------+---------+---------+---------+---------+
	if ($r_in['estatus'] == 'AUTORIZADO')
		return 0;
	else {
		if ($mostrar == 1) {
			echo $r_in['cadena'].'<br>';
			echo $sqlin.'<br>';
		}
		$motivo=substr($r_in['cadena'],86,10);
		return 1;
	}
}

function procesar($archivo_name,$fechaaporte,$ip,$archivosalida, $numerocuotas, $dias, $db_con)
{
// 123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890
//          1         2         3         4         5         6         7         8         9        10        11        12
// ---------+---------+---------+---------+---------+---------+---------+---------+---------+---------+---------+---------+
// 6110J301781678CAPPOUCLA                          201106142011061701082445190100023187VEF795000000APREST201.RECCASCAPPOUCLA
// 6210J301781678V12019714       001010824575102001406310000000000075012010824501CGE0001 FONDOS INSUFICIENTES
//echo 'valor '.$_POST['nominasemanal'];
$essemanal=($_POST['nominasemanal']==1?1:0);
//echo 'semanal '.$essemanal;
// echo 'Verificación de archivo <br>';
$lines = file('devoluciones/'.$archivo_name);
$faltoalguno=0;
set_time_limit($lines);
echo '<form action="aboxnom2.php" method="post" name="form1" enctype="multipart/form-data">';
echo "<input name='archivo' type='hidden' value='$archivo_name'>";
echo "<table class='basica 100 hover' width='100%'>";
$contadorgeneral=0;
$hoy = date("Y-m-d");

extract($_POST);
$registros=$_POST['registros'];
for ($i=0;$i<$registros;$i++)	// no es necesarios revisar el check si aparece es porq estan seleccionados para hacer el asiento 
{
	$variable='cancelar'.($i+1);
	if (!empty($$variable)) 
	{
		$fecha=explode('-',$$variable);
		$b=$fecha[0].'-'.$fecha[1].'-'.$fecha[2];

//		echo 'fecha de nomina '.$b;
//		exit;
	}
}
foreach ($lines as $line_num => $linea) {
	$datos = explode("|", $linea);
	if (substr($datos[0],0,3)=='611') {
		$fecha=substr($datos[0],49+8,8);
		$fecha=substr($fecha,0,4).'-'.substr($fecha,4,2).'-'.substr($fecha,6,2);
//		echo "<input name='fecha' type='hidden' value='$fecha'>";
		$nuevafecha="select date_add('$fecha',INTERVAL ".$dias." DAY) as fecha";
		try
		{
			$rsqln=$db_con->prepare($nuevafecha);
			$rsqln->execute();
			$asqln=$rsqln->fetch(PDO::FETCH_ASSOC);
			$fecha=($asqln['fecha']);
		}
		catch (PDOException $e) 
		{
		    die('rev2.'.$e->getMessage());
		}
//		echo 'Fecha de Proceso '.$fecha.'<br>';

//		echo '<br><input type="text" name="totalgeneral" id="totalgeneral" value=0 readonly="readonly">';
	}

	$cadena=$datos[0];
	$cedula=ceroizq(trim(substr($datos[0],15,8)),8);
	$cedula = 'V-'.substr($cedula,0,2).'.'.substr($cedula,2,3).'.'.substr($cedula,5,3);
	$monto=substr($datos[0],53,15);
	$monto = $monto / 100;
	$estatus = substr($datos[0],78,10);
	if (substr($datos[0],0,3)!='611') 
	try
	{	
		$sqlresbanco="insert into ".$_SESSION['institucion']."sgcaresb (cadena, fechagen, cedula, estatus, ip, fechaproc, fechanom, monto, abierta) values ('$cadena', '$fecha', '$cedula', '$estatus', '$ip', now(), '$b', '$monto', 1)";
		$ressql=$db_con->prepare($sqlresbanco);
		$ressql->execute();
	//		echo $sqlresbanco;
	}
	catch (PDOException $e) 
	{
	    die('rev2.'.$e->getMessage());
	}

}
}
?>

<?php // include("pie.php");?>

</body></html>

