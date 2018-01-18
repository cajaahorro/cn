<?php
/*
SELECT nropre_sdp, monpre_sdp, monpag_sdp, cuota_ucla, codpre_sdp, concat(trim(cuent_pres),'-',substr(cod_prof,1,4)) as cuent_p, concat(trim(cuent_int),'-',substr(cod_prof,1,4)) as cuent_d, otro_int as cuent_d, ced_prof, cod_prof, concat(ape_prof,' ',nombr_prof) as nombre, descr_pres from sgcaf310, sgcaf200, sgcaf360 where (codpre_sdp=cod_pres) and (dcto_sem) and (codsoc_sdp=cod_prof) and (stapre_sdp='A' and ! renovado) order by cod_pres, ced_prof limit 5
2.9199 seg.

SELECT nropre_sdp, monpre_sdp, monpag_sdp, cuota_ucla, codpre_sdp, concat(trim(cuent_pres),'-',substr(cod_prof,1,4)) as cuent_p, concat(trim(cuent_int),'-',substr(cod_prof,1,4)) as cuent_d, otro_int as cuent_d, ced_prof, cod_prof, concat(ape_prof,' ',nombr_prof) as nombre from sgcaf310, sgcaf200, sgcaf360 where (codpre_sdp=cod_pres) and (dcto_sem) and (codsoc_sdp=cod_prof) and (stapre_sdp='A' and ! renovado) order by cod_pres, ced_prof limit 5
2.8321 seg.

SELECT nropre_sdp, monpre_sdp, monpag_sdp, cuota_ucla, codpre_sdp, concat(trim(cuent_pres),'-',substr(cod_prof,1,4)) as cuent_p, concat(trim(cuent_int),'-',substr(cod_prof,1,4)) as cuent_d, otro_int as cuent_d, ced_prof, cod_prof, concat(ape_prof,' ',nombr_prof) as nombre from sgcaf310, sgcaf200, sgcaf360 where (codpre_sdp=cod_pres) and (dcto_sem) and (codsoc_sdp=cod_prof) and (stapre_sdp='A') order by ced_prof, cod_pres limit 5

delimiter //

mysql> 
CREATE PROCEDURE simpleproc (OUT fechanomina DATE, OUT dcto_sem BOOL)
BEGIN

   SELECT COUNT(*) INTO param1 FROM t;
END

esto no se puede utilizar todavia porque no devuelve una tabla
delimiter //
create procedure sp_items_a_descontar (IN lacedula varchar(20), IN fechadescuento varchar(10), OUT archivosalida INT)
begin
	select * from sgcaf310 where (stapre_sdp='A') and (cedsoc_sdp=lacedula) and (f_1cuo_sdp <= fechadescuento) order by codpre_sdp into archivosalida;
end
//
delimiter ;

call sp_items_a_descontar('V-09.377.388','2012-10-02');


sin_sp
inicio: 20:23:11
final : 20:27:42 -> 00:04:31
//

*/
include("home.php");
extract($_GET);
extract($_POST);
extract($_SESSION);
/*
$comando="select DATE_FORMAT(now(),'%m/%d/%Y') as hoy";
$res=mysql_query($comando);
$res=mysql_fetch_assoc($res);
$res=$res['hoy'];
*/
$sql="select DATE_FORMAT(now(),'%m/%d/%Y') as hoy, DATE_ADD(NOW(), INTERVAL 24 MONTH) AS futuro, DATE_SUB(NOW(), INTERVAL 6 WEEK) AS pasado";
$stmt=$db_con->prepare($sql);
$stmt->execute();
$res=$stmt->fetch(PDO::FETCH_ASSOC);
$hoy=$res['hoy'];
$pasado=$res['pasado'];
$futuro=$res['futuro'];
?>

<script language="javascript">
function abrir2Ventanas(fechadescuento)
{
// window.open("06_Inventario_actuallist.asp","prueba1", "width=385,height=180,top=0,left=0',status,toolbar =1,scrollbars,location");
// window.open("leftmenu.htm","prueba2","width=385,he ight=180,top=0,left=395,status,toolbar=1,scrollbar s,location");
window.open("cuotabanco/cuobanpdf1.php?fechadescuento="+fechadescuento,"parte1","top=0,left=395,status=no,toolbar=no,scrollbar=yes,location=no,type=fullWindow,fullscreen");	// los primeros 500 socios	width=385,height=180,
// window.open("cuobanpdf2.php?fechadescuento="+fechadescuento,"parte2","top=0,left=395,status=no,toolbar=no,scrollbar=yes,location=no,type=fullWindow,fullscreen");
// "width=385,height=180,top=0,left=395,status,toolbar=1,scrollbar s,location");	// los demas
// window.open("cuobanpdf3.php?fechadescuento="+fechadescuento,"resumen","top=0,left=395,status=no,toolbar=no,scrollbar=yes,location=no,type=fullWindow,fullscreen");
//,"width=385,height=180,top=0,left=395,status,toolbar=1,scrollbar s,location");	// resumen de los montos
window.open("cuotabanco/cuobanpdf4.php?fechadescuento="+fechadescuento,"banco","top=0,left=395,status=no,toolbar=no,scrollbar=yes,location=no,type=fullWindow,fullscreen");
// "width=385,height=180,top=0,left=395,status,toolbar=1,scrollbar s,location");	// el listado a banco
window.open("cuotabanco/cuobanpdf5.php?fechadescuento="+fechadescuento,"amortiza","top=0,left=395,status=no,toolbar=no,scrollbar=yes,location=no,type=fullWindow,fullscreen");
// "width=385,height=180,top=0,left=395,status,toolbar=1,scrollbar s,location");	// amortizacion / capital
//window.open("cuobanpdf6.php?fechadescuento="+fechadescuento,"descargar","top=0,left=395,status=no,toolbar=no,scrollbar=yes,location=no,type=fullWindow,fullscreen");
// "width=385,height=180,top=0,left=395,status,toolbar=1,scrollbar s,location");	// amortizacion / capital
}
</script>
<script language="javascript">
//Creo una función que imprimira en la hoja el valor del porcentanje asi como el relleno de la barra de progreso
function callprogress(vValor){
 //document.getElementById("getprogress").innerHTML = vValor;
 //document.getElementById("getProgressBarFill").innerHTML = '<div class="ProgressBarFill" style="width: '+vValor+'%;"></div>';
 // document.getElementById("getprogress").innerHTML = vValor;
// document.getElementById("getProgressBarFill").innerHTML = '<div class="ProgressBarFill" style="width: '+vValor+'%;"></div>';
 document.getElementById("progress-txt").innerHTML = vValor;
 // alert('<div id="progress-bs" class="progress-bar" role="progressbar" style="width:'+vValor+'%; min-width:10%"></div>')
 document.getElementById("progress-txt").innerHTML = '<div class="progress-bar" role="progressbar" style="width:'+vValor+'%; min-width:10%">'+vValor+'%</div>';

 /*
 document.getElementById("progress-gral").innerHTML = vgeneral;
 // alert('<div id="progress-bs" class="progress-bar" role="progressbar" style="width:'+vValor+'%; min-width:10%"></div>')
 document.getElementById("progress-gral").innerHTML = '<div class="progress-bar" role="progressbar" style="width:'+vgeneral+'%; min-width:10%">'+vgeneral+'%</div>';
*/
}
</script>
<style type="text/css">
/* Ahora creo el estilo que hara que aparesca el porcentanje y relleno del mismoo*/
/*
      .ProgressBar     { width: 16em; border: 1px solid black; background: #eef; height: 1.25em; display: block; }
      .ProgressBarText { position: absolute; font-size: 1em; width: 16em; text-align: center; font-weight: normal; }
      .ProgressBarFill { height: 100%; background: #aae; display: block; overflow: visible; }
*/
</style>
<?php
/*
 echo 'accion = '.$accion;
 echo 'nominas '.$_POST['nominasnormales'];
*/
// recordar bloquear la base de datos durante este proceso y luego liberarla
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
if (!$accion) 
{
	echo "<div id='div1'>";
	echo "<form action='cuoban.php?accion=ListadoDeCuotas' name='form1' method='post' class='form-inline'>";
	echo '<fieldset><legend>Informaci&oacute;n Para Descuentos de Pr&eacute;stamos</legend>';
	echo '<label for="fechapago">Fecha en que se realiza el descuento: </label>';
?>
    <div class='input-group date' id='fechapago'>
    	<input type='text' id="fechapago" name="fechapago" class="form-control"  readonly/>
        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
        </span>
    </div>

   <script type="text/javascript">
     $('#fechapago').datetimepicker({
          // format: 'YYYY-MM-DD HH:mm'       
//            language: "en-GB",
          format: 'YYYY-MM-DD',
          locale: 'es',
            // format mes dia año
            defaultDate: "<?php echo $res; ?>", // "10/16/2016",
            startDate: "<?php echo $pasado; ?>", // "10/16/2016",
            endDate: "<?php echo $futuro; ?>", // "10/16/2016",
            // "startDate": "10/11/2016",
            // "endDate": "10/17/2016",
            // "singleDatePicker": true, 
             "autoUpdateInput": true,
              "showWeekNumbers": true,
             "autoApply": true,
             "autoclose": true,
             "showDropdowns": true

             /*
            disabledDates: [
//               "10/01/2016",
                new Date(2016, 11 - 1, 31),
//                "10/22/2016"
            ]
*/
            /*
            disabledDates: [
               moment("12/25/2016"),
                new Date(2016, 11 - 1, 21),
                "09/22/2016"
            ]
            */
      });
      </script>

<?php
	echo 'N&oacute;minas Normales <input class="checkbox" type="checkbox" id="nominasnormales" name="nominasnormales" value = "on" checked align="right"/>';
	echo '<input type="submit" class="btn btn-success" name="Submit" value="Enviar" />';
	echo '</form>';
	echo '</div>';
}	// !$accion

if (($accion=='ListadoDeCuotas') and ($nominasnormales == 'on')) 
{
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
	$todobien=true;
	$registros_socios=0;

	if (revision01($db_con) == true)
		$todobien=(revision02($db_con, $registros_socios));
	if ($todobien == false)
	{
		mensaje(['tipo'=>'warning','titulo'=>'Aviso','texto'=>'<h2>Las siguiente informaci&oacute;n tiene problemas en pr&eacute;stamo, corregir</h2>']);
		exit('');
		die ('<h2> Las siguiente informaci&oacute;n tiene problemas en pr&eacute;stamo, corregir');
	}
	else
	{
		// mensaje(['tipo'=>'info','titulo'=>'Información','texto'=>'<h2>No se han conseguido inconvenientes con los datos principales</h2>']);
	}

	// die('probando');
	$fechadescuento=($_POST['fechapago']);
	$fechaarchivo=explode('-',$fechadescuento);
	$fechaarchivo=$fechaarchivo[0].$fechaarchivo[1].$fechaarchivo[2];
	$nombre_archivo = 'nompre/'.$_SESSION['institucion'].'_'.$fechaarchivo.'domiciliacion.txt';
	$contenido = $nombre;
	if (fopen($nombre_archivo, 'w')) echo ''; 
	else 
	{
		mensaje(['tipo'=>'warning','titulo'=>'Aviso','texto'=>'<h2>No se puede crear el archivo, revise permisos</h2>']);
		exit('');
	}
	// Asegurarse primero de que el archivo existe y puede escribirse sobre el.
	$registros=$registros_socios; // $a_200->rowCount();
	// echo 'registro socios limit '.$registros;
	if ($registros < 30)
		$registros = 50;
	set_time_limit($registros);
	if (is_writable($nombre_archivo)) {

		// En nuestro ejemplo estamos abriendo $nombre_archivo en modo de adicion.
		// El apuntador de archivo se encuentra al final del archivo, asi que
		// alli es donde ira $contenido cuando llamemos fwrite().
		if (!$gestor = fopen($nombre_archivo, 'a')) {
			mensaje(['tipo'=>'warning','titulo'=>'Error!','texto'=>'<h2>No se puede abrir el archivo ($nombre_archivo) revise permisologia</h2>']);
			exit('');
//			echo "<h2>No se puede abrir el archivo ($nombre_archivo) revise permisologia</h2>";
//			exit;
		}
		else 
		{

			echo "<div id='div1'>";
			echo "<form action='cuoban.php?accion=Abonar' name='form1' method='post' onsubmit='return realizo_abono(form1)'>";
			echo '<input type="hidden" name="nombre_archivo" value = "'.$nombre_archivo.'"/>';
			echo '<input type="hidden" name="nominasnormales" value = "on"/>';
			// $fechadescuento=$_POST['fechadelpago'];
			mensaje(['tipo'=>'info','titulo'=>'Información','texto'=>'Recopilando informaci&oacute;n Para Descuentos de Pr&eacute;stamos al '.convertir_fechadmy($fechadescuento)]);
//			$fechadescuento=convertir_fecha($fechadescuento);
			$sql_360="select * from ".$_SESSION[institucion]."sgcaf360 where (dcto_sem=1) order by cod_pres";
			$a_360=$db_con->prepare($sql_360, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL)); // ordernado para recorrer hacia adelante
			$a_360->execute();
			$arreglo_360=$a_360->fetchall();
			// print_r($arreglo_360);

/*
//			busqueda en el arreglo 
			$x=-1;
			foreach($arreglo_360 as $key=>$value){
				$x++;
				$indice=array_search("004", $value, true);
				if ($indice)
				{
					echo 'el indice'.$indice.'<br>';
//					echo $value[$indice];
					echo 'x'.$x;
					echo $arreglo_360[$x]['descr_pres'];
				}
			}
*/
//			$sql_200="select cod_prof, ced_prof, concat(ape_prof, ' ', nombr_prof) as nombre, ctan_prof from sgcaf200 where (ucase(statu_prof) != 'RETIRA') and (tipo_socio='P') and (ced_prof='V-03855577' or ced_prof='V-16770549'  or ced_prof='V-17572385') order by ced_prof";
			$sql_200="select cod_prof, ced_prof, concat(ape_prof, ' ', nombr_prof) as nombre, ctan_prof from ".$_SESSION[institucion]."sgcaf200 where (ucase(statu_prof) != :status) and (tipo_socio=:tipo) order by ced_prof";
			$a_200=$db_con->prepare($sql_200);
			$a_200->bindValue(":status","RETIRA");
			$a_200->bindValue(":tipo","P");
			$a_200->execute();
			$registros = $a_200->rowCount();
			$ValorTotal=$registros;
			$cuantos=0;
			while ($r200 = $a_200->fetch(PDO::FETCH_ASSOC))
			{
				$cedula=$r200['ced_prof'];
				$micedula=substr($cedula,0,4).'.'.substr($cedula,4,3).'.'.substr($cedula,7,4);
				
				$cuantos++;
				$porcentaje = $cuantos * 100 / $ValorTotal; //saco mi valor en porcentaje

				echo "<script>callprogress(".round($porcentaje).")</script>"; //llamo a la función JS(JavaScript) para actualizar el progreso
				flush(); //con esta funcion hago que se muestre el resultado de inmediato y no espere a terminar todo el bucle con los 25 registros para recien mostrar el resultado
				ob_flush();


				// original sin sp_
				$sql_310="select * from ".$_SESSION[institucion]."sgcaf310 where (stapre_sdp='A') and (cedsoc_sdp='$micedula') and (f_1cuo_sdp <= :fechadescuento) order by codpre_sdp";
				$a_310=$db_con->prepare($sql_310);
				$a_310->bindValue(":fechadescuento",$fechadescuento);
				$a_310->execute();
				// echo 'nro '.$cuantos;
				// revisar_prestamo($r200, $a_310, $a_360, $fechadescuento, $micedula, $ip, $gestor, $db_con);
				revisar_prestamo($r200, $a_310, $arreglo_360, $fechadescuento, $micedula, $ip, $gestor, $db_con);
//				echo $sql_310.'<br>';
				// fin original sin sp_

			} // ($r200 = mysql_fetch_assoc($a_200))
			echo '<input type="hidden" name="fechadescuento" value="'.$fechadescuento.'">';
			echo '<h2>Información Lista...</h2><br>';
			echo '<h2>Se ha generado el archivo '.$nombre_archivo.'<br> para su procesamiento a banco</h2>';
			echo '<input type="submit" class="btn btn-info" name="Submit" value="Realizar Impresi&oacute;n de Listados" onClick="abrir2Ventanas(';
			echo "'";
			echo $fechadescuento;
			echo "'";
//			echo "'".'&downloadfile='.$nombre_archivo.'&';
			echo ');">  ';
//			echo '<input type="submit" name="Submit" value="Realizar Abono " />';
			echo '</legend>';
			echo '</form>';
			echo '</div>';	
		}
		fclose($gestor);
	}
	else {
		echo "<h2>No se puede crear el archivo ($nombre_archivo) revise permisologia</h2>";
		exit;
	}
	echo 'La fecha final de hoy es: '. date("d/m/Y"). ' Hora local del servidor es: '. date("G:i:s").'<br>'; 
	set_time_limit(30);
}	// ($accion=='ListadoDeCuotas')

/*
if (($accion=='Abonar')) { // and ($nominasnormales == 'on')) {
// if ($nominasnormales == 'on') {
	$fechadescuento=$_POST['fechadescuento'];
	$nombre_archivo=$_POST['nombre_archivo'];
//	echo '<input type="hidden" name="nombre_archivo" value = "'.$nombre_archivo.'"/>';
	echo "<div id='div1'>";
	
	echo '<h2>Puede proceder luego de la impresi&oacute;n de los listados a <br>realizar el abono a pr&eacute;stamos y el asiento contable y';
	echo '<br>recuerde obtener descargar el archivo </h2><h1>'.$nombre_archivo.'</h1><h2> para enviar al banco</h2>';

	echo "<form action='cuoban.php?accion=Asiento' name='form1' method='post' onsubmit='return realiza_asiento_montepio(form1)'>";
	$fechadescuento=$_POST['fechadescuento'];
	echo '<input type="hidden" name="fechadescuento" value = "'.$fechadescuento.'">';
	echo '<input type="hidden" name="nombre_archivo" value = "'.$nombre_archivo.'"/>';
	echo '<input type="submit" name="procesar" value="Generar Asiento Contable" />';
	echo '</form>';
	echo '</div>';
}	// ($accion=='ImpresionListados') 
if (($accion=='Asiento')) { 
/////
	$fechadescuento=$_POST['fechadescuento'];
	$sql_360="select * from sgcaf360 where (dcto_sem) order by cod_pres"; //  limit 30"; //  limit 20";
	$a_360=mysql_query($sql_360);
	$columna=3;
	$rpl=300; 	// registros por listado
	$crl=0;		// contador de registros por listado
	$col_listado=0;
	$nuevoarchivo=false;
	$condicion_sql='select codigo, cedula, nombre, nrocta, ';
	$col_listado=0;
	$max_cols=mysql_num_rows($a_360);
	echo 'Realizando Calculo<br>';
	while ($r360 = mysql_fetch_assoc($a_360))
	{
		$col_listado++;
		$columna++;
		if (trim($r360['desc_cor'])!='') ;// $header[$columna]=$r360['desc_cor'] ;
		else ; // $header[$columna]=substr($r360['descr_pres'],0,12);
		$totales[$col_listado]=0;
		$campo='colpre'.$col_listado;
		$condicion_sql.=' colpre'.$col_listado;
		if ($col_listado != $max_cols) 
		{
			$arrtitulo.=', ';
			$condicion_sql.=', ';
		}
	}
	$sql_nopr=$condicion_sql." from sgcanopr where ('$fechadescuento' = fecha) order by nombre "; //  limit 20";
	$a_nopr=mysql_query($sql_nopr);
	$registros=mysql_num_rows($a_nopr);
	set_time_limit($registros);
	$lascolumnas=mysql_num_fields($a_nopr)-4;
	while ($r_nopr = mysql_fetch_assoc($a_nopr)){
		$t1=0;
		for ($prestamos=1;$prestamos<=$lascolumnas;$prestamos++) {		// sumatoria de los prestamos
			$item='colpre'.$prestamos;
			$t1+=$r_nopr[$item];
			$totales[$prestamos]+=$r_nopr[$item];
		}
	}
	$general=0;
	for ($i=1;$i<count($totales);$i++)
		if ($totales[$i]!=0) {
			$general+=$totales[$i];
	}
	set_time_limit(30);

	$b=$fechadescuento;
	$b2 = date("Y-m-d");
	$c=explode('-',$b2);
	$asiento=$c[0].$c[1].$c[2].'001';
	echo "Generado Asiento Contable <strong><a target=\"_blank\" href='editasi2.php?asiento=$asiento'>$asiento </a></strong> <br>";
	$cuento='Nomina por cobrar al Banco de fecha '.convertir_fechadmy($b);
	$sql = "INSERT INTO sgcaf830 (enc_clave, enc_fecha, enc_desco, enc_desc1, enc_debe, enc_haber, enc_item, enc_dif, enc_igual, enc_refer, enc_sw, enc_explic) VALUES ('$asiento', '$b', '$cuento','',0,0,0,0,0,0,0,'$cuento')"; 
	if (!mysql_query($sql)) die ("El usuario $usuario no tiene permiso para añadir Asientos.<br>".$sql);

	$sql="select * from sgcaf000 where tipo='CtaPrexCobAmo'";
	$result=mysql_query($sql); 
	$cuentas=mysql_fetch_assoc($result);
	$cuenta_amortizacion=trim($cuentas['nombre']);
	$sql="select * from sgcaf000 where tipo='CtaPrexCobBco'";
	$result=mysql_query($sql); 
	$cuentas=mysql_fetch_assoc($result);
	$cuentabanco=trim($cuentas['nombre']);			
	$referencia='';
	$debe=$general;
	agregar_f820($asiento, $b2, '+', $cuentabanco, 'Amort. Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
	agregar_f820($asiento, $b2, '-', $cuenta_amortizacion, 'Total Retenciones del '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 	

	$nombre_archivo=$_POST['nombre_archivo'];
	echo '<form action="depositotxt.php" method="post" name="form1" enctype="multipart/form-data">';
	echo '<input type="hidden" name="archivo" value = "'.$nombre_archivo.'">';
	echo '<input type="submit" name="procesar" value="Descargar Archivo '.$nombre_archivo.'" />';
	echo '</form>';

	$comando = "update sgcaf8co set fechanominamiercoles= now()";
	$resultado=mysql_query($comando);
/////
}

*/

function revisar_prestamo($r200,$a_310,$a_360,$fechadescuento,$micedula,$ip,$gestor, $db_con)
{
	$primeravez=0;
	$totalxsocio=0;
//	echo 'entrada : '. date("G:i:s").''; 
	while ($r310 = $a_310->fetch(PDO::FETCH_ASSOC))
	{
		if (! $r310['renovado'])
			if ($r310['stapre_sdp'] == 'A')
				acumular($r200,$r310,$a_360,$fechadescuento,$micedula,$primeravez,$ip,$totalxsocio, $db_con) ;
			else ;
		else 
			if ($r310['stapre_sdp'] == 'A')
				if ($r310['paga_hasta'] >= $fechadescuento)
					acumular($r200,$r310,$a_360,$fechadescuento,$micedula,$primeravez,$ip,$totalxsocio, $db_con);
	}
//	echo 'salida : '. date("G:i:s").'<br>'; 
	if ($totalxsocio > 0){	// meterlo en el listado a banco
		listadotxt($r200,$totalxsocio,$gestor);
	}
}


function acumular($r200,$r310,$a_360,$fechadescuento,$micedula,&$primeravez,$ip,&$totalxsocio, $db_con)
{
try
{
	if ($r310['cuota_ucla'] == 0) {
		/*
		$actualiza="update sgcaf310 set cuota_ucla=".$r310['cuota']." where registro =".$r310['registro'];
		$ractualiza=mysql_query($actualiza);
		*/
		$actualiza="update ".$_SESSION['institucion']."sgcaf310 set cuota_ucla=:lacuota where registro =".$r310['registro'];
		$act=$db_con->prepare($actualiza);
		$lacuota = $r310['cuota'];
		$act->bindValue(":lacuota",$r310['cuota']);
		$act->execute();
	}
	else $lacuota = $r310['cuota_ucla'];
//	mysql_data_seek ($a_360, 0);		// volver al principio de la busqueda
	$nombre=trim($r200['nombre']);
	$codigo=$r310['codsoc_sdp'];
	$codigoprestamo=$r310['codpre_sdp'];
	$posicion=0;
	// echo 'el codigo'.$codigoprestamo;
	// $a_360->execute();
	// while ($r360 = $a_360->fetch(PDO::FETCH_ASSOC)) // FETCH_NUM, PDO::FETCH_ORI_NEXT))
// busco el codigo en el arreglo
	$x=-1;
	$encontre=false;
	// print_r($a_360);
	foreach($a_360 as $key=>$value)
	{
		$x++;
		$indice=array_search($codigoprestamo, $value, true);
		if ($indice)
		{
			// echo 'el indice'.$indice.'<br>';
//					echo $value[$indice];
			// echo 'x'.$x;
			// echo $a_360[$x]['descr_pres'];
			$encontre=true;
			$posicion=$x;
		}
	}
	if (1==1) 
	{
		$posicion++;
//			echo $r310['nropre_sdp'].' - ' .$r310['codpre_sdp'].' ' . ' - '.$r360['cod_pres'].'<br>';
		if ($encontre == true) // ($r310['codpre_sdp']==$r360['cod_pres']) {
		{
			$lacolumnapres='colpre'.$posicion;
			$lacolumnanro='colnro'.$posicion;
			$proceso=1;
			$nrocuenta=$r200['ctan_prof'];
			$elnumero=$r310['nropre_sdp'];
			if ($primeravez == 0) 
			{
				$sql_pre="insert into ".$_SESSION['institucion']."sgcanopr (fecha, cedula, codigo, nombre, ".$lacolumnapres.", ".$lacolumnanro.", proceso, ip, nrocta) values (:fechadescuento, :micedula, :codigo, :nombre, :lacuota, :elnumero, :proceso, :ip, :nrocuenta)";
				// $sql_pre="insert into ".$_SESSION['institucion']."sgcanopr (fecha, cedula, codigo, nombre, ".$lacolumnapres.", ".$lacolumnanro.", proceso, ip, nrocta) values (':fechadescuento', ':micedula', ':codigo', ':nombre', ':lacuota', ':elnumero', ':proceso', ':ip', ':nrocuenta')";
				$db_con->setAttribute(PDO::ATTR_EMULATE_PREPARES,false); 
				$pre=$db_con->prepare($sql_pre);
				$pre->bindValue(":fechadescuento",$fechadescuento);
				$pre->bindValue(":micedula",$micedula);
				$pre->bindValue(":codigo",$codigo);
				$pre->bindValue(":nombre",$nombre);
				$pre->bindValue(":elnumero",$elnumero);
				$pre->bindValue(":ip",$ip);
				$pre->bindValue(":nrocuenta",$nrocuenta);
				$pre->bindValue(":lacuota",$lacuota);
				$pre->bindValue(":proceso",$proceso);
				$pre->bindValue(":nrocuenta",$nrocuenta);
				$primeravez = 1;
//				echo $fechadescuento.'/'.$micedula.'/'.$codigo.'/'.$nombre.'/'. $lacuota.'/'. $elnumero. '/'.$proceso.'/'. $ip.'/'. $nrocuenta;
			}
			else 
			{
				// $sql_pre="update sgcanopr set ".$lacolumnapres." = ".$lacolumnapres."+'$lacuota', ".$lacolumnanro." = '$elnumero' where (cedula='$micedula')" ;
				$sql_pre="update ".$_SESSION['institucion']."sgcanopr set ".$lacolumnapres." = ".$lacolumnapres."+:lacuota, ".$lacolumnanro." = :elnumero where (cedula=:micedula)" ;
				$pre=$db_con->prepare($sql_pre);
				 echo '<br>update '.$micedula.'-'.$elnumero.'-'.$lacuota.$sql_pre;
				try
				{
					$pre->bindValue(":micedula",$micedula);
					$pre->bindValue(":elnumero",$elnumero);
					$pre->bindValue(":lacuota",$lacuota);
				}
				catch (PDOException $e) 
				{
				    die('Fallo up2'. $e->getMessage());
				}
//		echo '3';
			}
//			if ($micedula=='V-11.267.811')  echo $sql_pre;
//			echo $sql_pre;
			$res=$pre->execute();
			if ($res) {
				$saldo=$r310['monpre_sdp']-$r310['monpag_sdp'];
				$tipo=$r310['codpre_sdp'];
				$capital=$lacuota;
				$coriginal=$lacuota;
				$totalxsocio+=$capital;
				$i2=0;
//				echo $r360['i_max_pres']. ' - ' .$r310['nrocuotas'].' - '.$saldo.' - '.'<br>';
				//$interes=cal_int($r360['i_max_pres'],$r310['nrocuotas'],$saldo,$factor_divisible = 52,$z=0,$i2);
				$interes=cal_int($a_360[$posicion]['i_max_pres'],$r310['nrocuotas'],$saldo,$factor_divisible = 52,$z=0,$i2);
//				echo $i2;
				$interes=round(($saldo*$i2),2);
				$cu=round($lacuota,2) - $interes;
				$la_cuota=$interes+$cu;
				if ($saldo <= 0) {
					$interes=0;
					$capital=$coriginal;
//					echo 'entre '.$capital;
				}
				/*
				$cuent_p=trim($r360['cuent_pres']).'-'.substr($codigo,1,4);
				$cuent_i=trim($r360['cuent_int']).'-'.substr($codigo,1,4);
				$cuent_d=trim($r360['otro_int']);
				*/
				$cuent_p=trim($a_360[$posicion]['cuent_pres']).'-'.substr($codigo,1,4);
				$cuent_i=trim($a_360[$posicion]['cuent_int']).'-'.substr($codigo,1,4);
				$cuent_d=trim($a_360[$posicion]['otro_int']);

				$nrocuota=$r310['ultcan_sdp']+1;
				// $tipoprestamo=$r360['tipo'];
				$tipoprestamo=$a_360[$posicion]['tipo'];
				$pos310=$r310['registro'];
				$sql_amor="insert into ".$_SESSION['institucion']."sgcaamor 
				(fecha, codsoc, nropre, cedula, nombre, saldo, capital, 
				interes, cuota, codpre, cuent_p, cuent_i, cuent_d, ip, 
				nrocuota, proceso, tipo, pos310, semanal, diferido, abonado,
				nrocta, ip_abono) values 
				(:fechadescuento, :codigo, :elnumero, :micedula, :nombre, :saldo, :capital, 
				:interes, :la_cuota, :tipo, :cuent_p, :cuent_i, :cuent_d, :ip, 
				:nrocuota, :proceso, :tipoprestamo, :pos310, :semanal, :diferido, :abonado,
				:nrocta, :ip_abono)";

				// $db_con->setAttribute(PDO::ATTR_EMULATE_PREPARES,false); 
				$amortizacion=$db_con->prepare($sql_amor);
				// echo $fechadescuento.'-'.$codigo.'-'.$elnumero.'-'.$micedula.'-'.$nombre.'-'.$saldo.'-'.$capital.'-'.$interes.'-'.$la_cuota.'-'.$tipo.'-'.$cuent_p.'-'.$cuent_i.'-'.$cuent_d.'-'.$ip.'-'.$nrocuota.'-'.$proceso.'-'.$tipoprestamo.'-'.$pos310.'-'.$proceso.'-'.$proceso.'-'.$nrocuenta; // .'<br>'.$sql_amor;

				$amortizacion->bindParam(":fechadescuento", $fechadescuento);
				$amortizacion->bindParam(":codigo", $codigo);
				$amortizacion->bindParam(":elnumero", $elnumero);
				$amortizacion->bindParam(":micedula", $micedula);
				$amortizacion->bindParam(":nombre", $nombre);
				try
				{
					$amortizacion->bindParam(":saldo", $saldo);
					$amortizacion->bindParam(":capital", $capital);
					$amortizacion->bindParam(":interes", $interes);
					$amortizacion->bindParam(":la_cuota", $la_cuota);
					$amortizacion->bindParam(":tipo", $tipo);
				}	
				catch (PDOException $e) 
				{
				    die('Fallo asg 1'. $e->getMessage());
				}
				try
				{
					$amortizacion->bindParam(":cuent_p", $cuent_p);
					$amortizacion->bindParam(":cuent_i", $cuent_i);
					$amortizacion->bindParam(":cuent_d", $cuent_d);
					$amortizacion->bindParam(":ip", $ip);
				}
				catch (PDOException $e) 
				{
				    die('Fallo asg 2'. $e->getMessage());
				}
				try
				{
					$amortizacion->bindParam(":nrocuota", $nrocuota);
					$amortizacion->bindParam(":proceso", $proceso);
					$amortizacion->bindParam(":semanal", $proceso);
					$amortizacion->bindParam(":diferido", $proceso);
					$amortizacion->bindParam(":tipoprestamo", $tipoprestamo);
					$amortizacion->bindParam(":pos310", $pos310);
					$amortizacion->bindParam(":abonado", $fechadescuento);
				}
				catch (PDOException $e) 
				{
				    die('Fallo asg 3'. $e->getMessage());
				}
				try
				{
					$amortizacion->bindParam(":nrocta", $nrocuenta);
					$amortizacion->bindParam(":ip_abono", $ip);
				}
				catch (PDOException $e) 
				{
				    die('Fallo asg 4'. $e->getMessage());
				}

				
				// die('espero');
				// echo $fechadescuento;

				/*
				if ($r360['tipo'] == 'Comercial')
					$amortizacion->bindParam(":capital", $capital);
				else 
				{
					if ($r360['int_dif'] == 0)
					{
						$resto =($capital-$interes);
						$amortizacion->bindParam(":capital", $resto);
					}
					if ($r360['int_dif'] == 1)
						$amortizacion->bindParam(":capital", $capital);
				}
				*/
				if ($a_360[$posicion]['tipo'] == 'Comercial')
					$amortizacion->bindParam(":capital", $capital);
				else 
				{
					if ($a_360[$posicion]['int_dif'] == 0)
					{
						$resto =($capital-$interes);
						$amortizacion->bindParam(":capital", $resto);
					}
					if ($a_360[$posicion]['int_dif'] == 1)
						$amortizacion->bindParam(":capital", $capital);
				}
				
//		echo '6';
				try
				{
					$result2=$amortizacion->execute();
				}
			catch (PDOException $e) 
			{
			    die(' '.$sql_amor.'<br>'.'Fallo amx'. $e->getMessage());
			}//				echo $sql_amor.'<br>';
//		echo '7';
//				echo $sql_amor.'<br>';
			}
//			echo $sql_pre.'----'.$posicion.'<br>';
		}	// ($r360 = mysql_fetch_assoc($a_360))
	} // ($r310 = mysql_fetch_assoc($a_310))
	else 
	{
		mensaje(['tipo'=>'error','titulo'=>'Aviso!!!','texto'=>'<h2>No se ha encontrado este codigo '.$codigoprestamo.' puede haber problemas en el proceso</h2>']);
	}
}
catch (PDOException $e) 
{
    echo 'Fallo=> ' . $e->getMessage();
}
}

function revision01($db_con)
{
	try
	{
		echo 'La fecha del d&iacute;a de hoy es: '. date("d/m/Y"). ' Hora local del servidor es: '. date("G:i:s").'<br>'; 
		// $fechadescuento=convertir_fecha($_POST['fechadelpago']);		// revisar que no hayan nominas con esa fecha
		$fechadescuento=($_POST['fechapago']);		// revisar que no hayan nominas con esa fecha
		$sql="delete from ".$_SESSION['institucion']."sgcanopr where fecha = :fechadescuento";
//		exit($sql);
//		echo $sql.$fechadescuento;
		$stmt=$db_con->prepare($sql);
		$stmt->bindParam(":fechadescuento",$fechadescuento);
		$stmt->execute();
		if ($stmt)
		{
//			echo 'paso 1';
			$sql_amor="delete from  ".$_SESSION['institucion']."sgcaamor where (:fechadescuento = fecha)"; //  limit 30"; //  limit 20";
			$resultado=$db_con->prepare($sql_amor);
			$resultado->bindParam(":fechadescuento",$fechadescuento);
			$resultado->execute();
		//	echo $sql;
			if ($resultado)
			{
//				echo 'paso 2';
				$sql="select count(fecha) as cuantos, ip from  ".$_SESSION['institucion']."sgcanopr where fecha = :fechadescuento group by fecha, ip";
				$resultado=$db_con->prepare($sql);
				$resultado->bindParam(":fechadescuento",$fechadescuento);
				$resultado->execute();
				if ($resultado->rowCount()>0) 
				{
					$registro=$resultado->fetch(PDO::FETCH_ASSOC);
					$registro=$resultado['cuantos'];
					mensaje(['tipo'=>'warning','titulo'=>'Aviso','texto'=>'<h2>No se puede procesar esta nomina existe una ya realizada con '.$registro['cuantos'].' registro generada desde la IP '.$registro['ip'].'</h2>']);
					return false;
				}
				return true;
			}
		}
	}
	catch (PDOException $e) {
	    die('rev1.'.$e->getMessage());
	}
}


function revision02($db_con, &$registros_socios)
{
	$todobien = true;
	try
	{
		// verificar codigos contra cedulas 
		$sql="select cod_prof, ced_prof from sgcaf200 where ced_prof='V-03855577' order by cod_prof";
		$sql="select cod_prof, ced_prof from ".$_SESSION['institucion']."sgcaf200 order by cod_prof";
		// $sql="select cod_prof, ced_prof from ".$_SESSION['institucion']."sgcaf200 order by cod_prof LIMIT :limit ";
//		echo $sql;
		$maximo=20;
		$a_200=$db_con->prepare($sql);
		// $a_200->bindValue(':limit', (int) $maximo, PDO::PARAM_INT);
		$a_200->execute();
		$registros=$a_200->rowCount();
		$registros_socios=$registros;

		$ValorTotal=$registros;
		if ($registros < 30)
			$registros = 50;
		$cuantos=0;
		set_time_limit($registros);
// 		echo $registros;
		// $a_200=$a_310->fetchall(PDO:FETCH_ASSOC);
		while ($r200 = $a_200->fetch(PDO::FETCH_ASSOC))
		{
			$cobuscar=$r200['cod_prof'];
			$cebuscar=$r200['ced_prof'];
//			echo $cobuscar;

			$sql2="select codsoc_sdp, cedsoc_sdp, nropre_sdp from  ".$_SESSION['institucion']."sgcaf310 where (codsoc_sdp=:cobuscar) AND ((stapre_sdp=:estado) AND (renovado = :renovado)) ";
//			echo $sql2;
			$a_310=$db_con->prepare($sql2);
			$a_310->bindValue(":estado",'A');
			$a_310->bindValue(":renovado",0);
			$a_310->bindValue(":cobuscar",$cobuscar);
			$a_310->execute();
			// $a_310=mysql_query($sql2);
//			$r310=$a_310->fetch(PDO:FETCH_ALL);

			while ($r310 = $a_310->fetch(PDO::FETCH_ASSOC))
			{
	//			echo $cebuscar .' / '.$r310['cedsoc_sdp'].'<br>';
	//			$estacedula=substr($r310['cedsoc_sdp'],0,4).substr($r310['cedsoc_sdp'],4,3).substr($r310['cedsoc_sdp'],7,3);
				$estacedula=substr($cebuscar,0,4).'.'.substr($cebuscar,4,3).'.'.substr($cebuscar,7,3);
	//			die($estacedula);
//	 echo $estacedula. ' '.$r310['cedsoc_sdp'].'<br>';
				if ($estacedula != $r310['cedsoc_sdp'])
				{
					echo $cobuscar. ' - '.$estacedula. ' - '.$r310['cedsoc_sdp']. ' - '.$r310['nropre_sdp'].'<br>';
					$todobien=false;
					return $todobien;
				}
			}
		}
		return $todobien;

	}
	catch (PDOException $e) 
	{
	    die('rev2.'.$e->getMessage());
	    return false;
	}
	echo "</div>";
}

function listadotxt($r200,$totalxsocio,$gestor)
{
//0201082457570200015888V07333526        00000000000008937ABARCA DE G.TERESA G.                                                 00CAPPOUCL              *
//0201082457510200129328V16770549        00000000000000010Xx  CARRASCO R. TONDIS MIGUEL                                         00CAPPOUCL              *
	$cadena='02'.$r200['ctan_prof'];
	$cadena.=substr($r200['ced_prof'],0,1).substr($r200['ced_prof'],2,8).replicate(' ',8);
	$monto=$totalxsocio*100;
//	$monto=explode('.',$totalxsocio);
	// quito el punto
	$sinpunto='';


	for ($i=0;$i<strlen($monto);$i++)
		if (substr($monto,$i,1)!= '.')
			$sinpunto.=substr($monto,$i,1);

	$monto=ceroizq($sinpunto,17);
	$cadena.=$monto;
	$nombre=trim($r200['nombre']);
	if ($nombre == '') $nombre='CAPPOUCLA - REVISAR';
	$nombre=substr(trim($nombre),0,40);
	$rellenar=replicate(' ',40-strlen($nombre));
	$cadena.=$nombre.$rellenar;
	$cadena.=replicate(' ',30).'00'.'CAPPOUCL'.replicate(' ',14).'*'.chr(13).chr(10);

//echo $cadena.'<br>';
	if (fwrite($gestor, $cadena) === FALSE) {
		echo "No se puede escribir al archivo ($nombre_archivo)";
		exit;
	}
/*
	if (($totalsocio == 37.50)) //  or ($totalsocio == 56.60) or ($totalsocio == 30))
	{
		echo $monto.'<br>';
		die($cadena);
	}
*/
	
}

function replicate($caracterarepetir,$cantidaddeveces)
{
	$resultado='';
	for ($i=0;$i<$cantidaddeveces;$i++)
		$resultado.=$caracterarepetir;
	return $resultado;
}


?>

<?php // include("pie.php");?>

</body>
</html>

<?php
/*
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colnro1` `colnro1` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '\'\'';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colnro2` `colnro2` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '\'\'';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colnro3` `colnro3` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '\'\'';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colnro4` `colnro4` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '\'\'';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colnro5` `colnro5` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '\'\'';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colnro6` `colnro6` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '\'\'';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colnro7` `colnro7` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '\'\'';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colnro8` `colnro8` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '\'\'';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colnro9` `colnro9` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '\'\'';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colnro10` `colnro10` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '\'\'';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colnro11` `colnro11` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '\'\'';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colnro12` `colnro12` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '\'\'';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colnro13` `colnro13` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '\'\'';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colnro14` `colnro14` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '\'\'';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colnro15` `colnro15` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '\'\'';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colnro16` `colnro16` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '\'\'';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colnro17` `colnro17` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '\'\'';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colnro18` `colnro18` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '\'\'';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colnro19` `colnro19` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '\'\'';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colnro20` `colnro20` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '\'\'';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colnro21` `colnro21` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '\'\'';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colnro22` `colnro22` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '\'\'';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colnro23` `colnro23` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '\'\'';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colnro24` `colnro24` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '\'\'';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colnro25` `colnro25` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '\'\'';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colnro26` `colnro26` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '\'\'';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colnro27` `colnro27` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '\'\'';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colnro28` `colnro28` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '\'\'';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colnro29` `colnro29` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '\'\'';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colnro30` `colnro30` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '\'\'';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colnro31` `colnro31` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '\'\'';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colnro32` `colnro32` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '\'\'';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colnro33` `colnro33` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '\'\'';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colnro34` `colnro34` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '\'\'';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colnro35` `colnro35` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '\'\'';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colnro36` `colnro36` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '\'\'';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colnro37` `colnro37` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '\'\'';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colnro38` `colnro38` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '\'\'';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colnro39` `colnro39` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '\'\'';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colnro40` `colnro40` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '\'\'';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colnro41` `colnro41` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '\'\'';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colnro42` `colnro42` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '\'\'';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colnro43` `colnro43` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '\'\'';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colnro44` `colnro44` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '\'\'';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colnro45` `colnro45` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '\'\'';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colnro46` `colnro46` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '\'\'';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colnro47` `colnro47` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '\'\'';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colnro48` `colnro48` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '\'\'';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colnro49` `colnro49` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '\'\'';

ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colpre1` `colpre1` DECIMAL(12,2) NOT NULL DEFAULT '0';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colpre2` `colpre2` DECIMAL(12,2) NOT NULL DEFAULT '0';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colpre3` `colpre3` DECIMAL(12,2) NOT NULL DEFAULT '0';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colpre4` `colpre4` DECIMAL(12,2) NOT NULL DEFAULT '0';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colpre5` `colpre5` DECIMAL(12,2) NOT NULL DEFAULT '0';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colpre6` `colpre6` DECIMAL(12,2) NOT NULL DEFAULT '0';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colpre7` `colpre7` DECIMAL(12,2) NOT NULL DEFAULT '0';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colpre8` `colpre8` DECIMAL(12,2) NOT NULL DEFAULT '0';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colpre9` `colpre9` DECIMAL(12,2) NOT NULL DEFAULT '0';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colpre10` `colpre10` DECIMAL(12,2) NOT NULL DEFAULT '0';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colpre11` `colpre11` DECIMAL(12,2) NOT NULL DEFAULT '0';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colpre12` `colpre12` DECIMAL(12,2) NOT NULL DEFAULT '0';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colpre13` `colpre13` DECIMAL(12,2) NOT NULL DEFAULT '0';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colpre14` `colpre14` DECIMAL(12,2) NOT NULL DEFAULT '0';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colpre15` `colpre15` DECIMAL(12,2) NOT NULL DEFAULT '0';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colpre16` `colpre16` DECIMAL(12,2) NOT NULL DEFAULT '0';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colpre17` `colpre17` DECIMAL(12,2) NOT NULL DEFAULT '0';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colpre18` `colpre18` DECIMAL(12,2) NOT NULL DEFAULT '0';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colpre19` `colpre19` DECIMAL(12,2) NOT NULL DEFAULT '0';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colpre20` `colpre20` DECIMAL(12,2) NOT NULL DEFAULT '0';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colpre21` `colpre21` DECIMAL(12,2) NOT NULL DEFAULT '0';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colpre22` `colpre22` DECIMAL(12,2) NOT NULL DEFAULT '0';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colpre23` `colpre23` DECIMAL(12,2) NOT NULL DEFAULT '0';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colpre24` `colpre24` DECIMAL(12,2) NOT NULL DEFAULT '0';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colpre25` `colpre25` DECIMAL(12,2) NOT NULL DEFAULT '0';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colpre26` `colpre26` DECIMAL(12,2) NOT NULL DEFAULT '0';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colpre27` `colpre27` DECIMAL(12,2) NOT NULL DEFAULT '0';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colpre28` `colpre28` DECIMAL(12,2) NOT NULL DEFAULT '0';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colpre29` `colpre29` DECIMAL(12,2) NOT NULL DEFAULT '0';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colpre30` `colpre30` DECIMAL(12,2) NOT NULL DEFAULT '0';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colpre31` `colpre31` DECIMAL(12,2) NOT NULL DEFAULT '0';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colpre32` `colpre32` DECIMAL(12,2) NOT NULL DEFAULT '0';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colpre33` `colpre33` DECIMAL(12,2) NOT NULL DEFAULT '0';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colpre34` `colpre34` DECIMAL(12,2) NOT NULL DEFAULT '0';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colpre35` `colpre35` DECIMAL(12,2) NOT NULL DEFAULT '0';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colpre36` `colpre36` DECIMAL(12,2) NOT NULL DEFAULT '0';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colpre37` `colpre37` DECIMAL(12,2) NOT NULL DEFAULT '0';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colpre38` `colpre38` DECIMAL(12,2) NOT NULL DEFAULT '0';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colpre39` `colpre39` DECIMAL(12,2) NOT NULL DEFAULT '0';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colpre40` `colpre40` DECIMAL(12,2) NOT NULL DEFAULT '0';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colpre41` `colpre41` DECIMAL(12,2) NOT NULL DEFAULT '0';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colpre42` `colpre42` DECIMAL(12,2) NOT NULL DEFAULT '0';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colpre43` `colpre43` DECIMAL(12,2) NOT NULL DEFAULT '0';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colpre44` `colpre44` DECIMAL(12,2) NOT NULL DEFAULT '0';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colpre45` `colpre45` DECIMAL(12,2) NOT NULL DEFAULT '0';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colpre46` `colpre46` DECIMAL(12,2) NOT NULL DEFAULT '0';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colpre47` `colpre47` DECIMAL(12,2) NOT NULL DEFAULT '0';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colpre48` `colpre48` DECIMAL(12,2) NOT NULL DEFAULT '0';
ALTER TABLE `CAPPOUCLA_sgcanopr` CHANGE `colpre49` `colpre49` DECIMAL(12,2) NOT NULL DEFAULT '0';

ALTER TABLE `CAPPOUCLA_sgcaamor` CHANGE `abonado` `abonado` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE `CAPPOUCLA_sgcaamor` CHANGE `nrocta` `nrocta` VARCHAR(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;
ALTER TABLE `CAPPOUCLA_sgcaamor` CHANGE `ip_abono` `ip_abono` VARCHAR(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;

INSERT INTO `CAPPOUCLA_sgcaamor` (`fecha`, `codsoc`, `nropre`, `cedula`, `nombre`, `saldo`, `capital`, `interes`, `cuota`, `codpre`, `cuent_p`, `cuent_i`, `cuent_d`, `ip`, `nrocuota`, `proceso`, `nrocta`, `registro`, `abonado`, `ip_abono`, `tipo`, `pos310`, `semanal`, `diferido`) VALUES
('2017-03-16', '00102', '00102109', 'V-13.785.046', 'ANGULO P.            FRANCISCO JOSE      ', '10000.00', '9800.00', '34.62', '9800.00', '058', '1-01-02-01-08-04-0102', '2-02-02-01-01-04-0102', '4-02-02-07-01-01-0001', '192.168.1.122', 1, 1, '', 878654, '2017-04-08 10:30:24', '', 'Comercial', 195218, 0, 0);


*/
?>
