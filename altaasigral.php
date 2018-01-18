<?php
include("home.php");
extract($_GET);
extract($_POST);
extract($_SESSION);
if ($_GET['emp'] == 1) {$_GET['n'] = 1;}
if (!$asiento) {
	$onload="onload=\"foco('asiento')\"";
	$sql="SELECT con_compr FROM ".$_SESSION['institucion']."sgcaf8co";
	$res=$db_con->prepare($sql);
	$res->execute();
	$fila = $res->fetch(PDO::FETCH_ASSOC);
	$asiento = $fila[0] + 1;
	$sql="UPDATE ".$_SESSION['institucion']."sgcaf8co SET con_compr = :asiento WHERE 1";
	$res=$db_con->prepare($sql);
	$res->execute([":asiento"=>$asiento]);
	// Cojo el valor de la fecha en que se hizo el último Asiento
	$sql="SELECT date_format(con_ultfec,'%d/%m/%y') AS ultfechax FROM ".$_SESSION['institucion']."sgcaf8co";
	$result = $db_con->prepare($sql);
	$result->execute();
	$row = $result->fetch(PDO::FETCH_ASSOC);
	$fecha = $row['ultfechax'];
} else {
	$onload="onload=\"foco('cuenta11')\"";
	$readonly=" readonly='readonly'";
	$asiento = $_POST['asiento'];
	$fecha = $_POST['daterange'];
	$lafecha = $_POST['fecha'];
	$tipo =$_POST['tipo'];
	$debcre= $_POST['debcre'];
	$cuenta1= $_POST['cuenta1'];
	$referencia =$_POST['referencia'];
	$elmonto=$_POST['elmonto'];
}

$sqlf="SELECT DATE_FORMAT(now(),'%Y-%m-%d') as hoy, CONCAT(SUBSTR(NOW(),1,5),'01-01') AS inicio, CONCAT(SUBSTR(NOW(),1,8),'01') AS minimo, DATE_FORMAT(DATE_ADD(now(),INTERVAL -1 DAY),'%Y-%m-%d') as ayer, DATE_SUB(NOW(), INTERVAL 7 DAY) AS sietedias, DATE_SUB(NOW(), INTERVAL 1 DAY) AS ayer, DATE_SUB(NOW(), INTERVAL 30 DAY) AS treintadias";
$stmt=$db_con->prepare($sqlf);
$stmt->execute();
$fechas=$stmt->fetch(PDO::FETCH_ASSOC);
echo '<body>';

if ($elmonto) {
	include ("altaasigral2.php");
//	$cuadre = totalapu($asiento);
}

?>

<form class ='form-inline' enctype='multipart/form-data' name='form1' action='altaasigral.php' method='post' onSubmit="return altaasigral(form1)">

<label>Asiento</label>
<input type='text' name='asiento' value="<?php echo $asiento;?>" maxlength="11" size="11" <?php echo $readonly;?> > 

<label>Fecha</label>
	
<?php
if (!$_POST['asiento']) 
{
	$temp = "Primer Registro:";
	$hoy = date("d/m/Y");
//	escribe_formulario(fecha, form1.fecha, 'd/m/yyyy', '', '', $hoy, '0', '10'); 
    $fechanueva=explode('/',$hoy);
	$fechanueva=$fechanueva[1].'/'.$fechanueva[0].'/'.$fechanueva[2];
	$sqlano="select substr(fech_ejerc,1,4) as ano from ".$_SESSION['institucion']."sgcaf100";
	$sqlfano=$db_con->prepare($sqlano);
	$sqlfano->execute();
	$sqlrano=$sqlfano->fetch(PDO::FETCH_ASSOC);
	$rango=$sqlrano['ano'];
	$sqlano='select substr(now(),1,4) as ano';
	$sqlfano=$db_con->prepare($sqlano);
	$sqlfano->execute();
	$sqlrano=$sqlfano->fetch(PDO::FETCH_ASSOC);
	if ($sqlrano['ano'] > $rango)
		$rango.=', '.$sqlrano['ano'];
	?>

	<label for ="fecha">Fecha</label>

                <div class='input-group date' id='datetimepicker1'>
                <input type="text" name="daterange" id="daterange" value="01/01/2015" />
                <span class="input-group-addon">
  		        	<span class="glyphicon glyphicon-calendar"></span>
                </span>
  
                <script type="text/javascript">
                $(function() {
                    $('input[name="daterange"]').daterangepicker(
                    {
                        "singleDatePicker": true,
                        "timePicker": false,
                        "timePicker24Hour": false,
                        // timePickerIncrement: 10,
                        "applyLabel": "Guardar",
                        "cancelLabel": "Cancelar",
                        locale: {
                            format: 'YYYY-MM-DD', // HH:mm',
                        daysOfWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi','Sa'],
                        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                        customRangeLabel: 'Personalizado',
                        applyLabel: 'Aplicar',
//                        fromLabel: 'Desde',
 //                       toLabel: 'Hasta',
                    },
                        startDate: "<?php echo $fechas['hoy']?>",
                        endDate:  "<?php echo $fechas['hoy']?>", 
                        minDate: "<?php echo $fechas['inicio']?>",
                        maxDate: "<?php echo $fechas['hoy']?>", 
                        "ranges": {
                            "Hoy": [
                                "<?php echo substr($fechas['hoy'],0,10).' 00:00'?>",
                                "<?php echo $fechas['hoy']?>"
                            ],
                            "Ayer": [
                                "<?php echo substr($fechas['ayer'],0,10).' 00:00'?>",
                                "<?php echo $fechas['hoy']?>"
                            ],
                            "Ultimos 7 Dias": [
                                "<?php echo substr($fechas['sietedias'],0,10).' 00:00'?>",
                                "<?php echo $fechas['hoy']?>"
                            ],
                            "Ultimos 30 Dias": [
                                "<?php echo substr($fechas['treintadias'],0,10).' 00:00'?>",
                                "<?php echo $fechas['hoy']?>"
                            ]
                        },
                    }
                    );
                });
                </script>
                </div>
	<?php
} 
else
{
	echo $fecha.'<p />';
	echo "<input type = 'hidden' value ='".$fecha."' name='daterange'>"; 
	$temp = "Siguiente Registro:";
	$sql="SELECT enc_explic FROM ".$_SESSION['institucion']."sgcaf830 WHERE enc_clave = '".$_POST['asiento']."'";
	$rs=$db_con->prepare($sql);
	$rs->execute();
	$expli = $rs->fetch(PDO::FETCH_ASSOC);
}
?>

<fieldset><legend><?php echo $temp;?></legend>

<?php
pantalla_asiento($fecha,$elcargo, $cuenta1, $concepto, $referencia, $elmonto);
echo "<label>Soporte Contable</label> <input type='file' name='fich' size='19' maxlength='19'>";
if ($_POST['asiento']) {echo " (Si el asiento ya tiene un soporte será sustituído)";}
echo "<br /><label>Explicaci&oacute;n</label> <textarea name='explicacion' rows='4' cols='90'>$expli[0]</textarea>";
echo "<p />";
if ($_GET['n'] == 1) {
	echo "<input class='btn btn-info' type='submit' name='boton' value=\"Guardar Asiento\" onclick='return compruebafecha(form1)'>";
} else {
	echo "<input class='btn btn-success' type='submit' name='boton' value=\"Guardar Registro\" onclick='return compruebafecha(form1)'>";
	if ($elmonto) {
		echo "&nbsp;&nbsp;&nbsp;<a href='altaasigral.php?n=1'";
		if ($cuadre) {echo " onclick=\"return confirm('Asiento descuadrado ¿Continuar con nuevo Asiento?')\"";}
		echo ">Crear nuevo Asiento</a>";
	}
}
?>
</fieldset><p style="clear:both"><p /> 
</form>

<?php

echo $mensaje;

//if ($anadido) 
{

	echo "<table class='table'>";
	cabasi(2);
	asiento($asiento,"1",$_SESSION['moneda'],$_SESSION['deci'],$_GET['bojust'], $db_con);
	echo "</table>";

}

?>

</div></body></html>

<?php 
function pantalla_asiento($fechax,$elcargo, $cuenta1, $concepto, $referencia, $elmonto)
{
// <th width="50">Fecha</th>
// <tr><td>
// <input type = 'text' maxlength='8' size='8' name='fecha' value='<?php echo $fechax;>' readonly='readonly' tabindex='3'>
// </td>
?>
<table class='table'>
<tr><th width="40"> </th><th width="100">Cuenta</th><th width="200">Concepto</th><th width="80">Referencia</th><th width="80">Monto</th></tr>
<td>
<?php 
$activar=' ';
if (($elcargo == '+')) {$activar='checked="checked"'; } else { $activar = ' '; }
//  || ($elcargo = 1)
// value="<?php echo $elcargo;>" 
?>
<input name="elcargo" class='form-control' type="checkbox" tabindex='4' <?php echo $activar;?> /> 
Cargo
</td><td>
<input type="text" size="20" tabindex='5' name='cuenta1' id="inputString" onKeyUp="lookup(this.value);" onBlur="fill();" value ="<?php echo $cuenta1;?>" autocomplete="off"/>
			<div class="suggestionsBox" id="suggestions" style="display: none;">
				<img src="upArrow.png" style="position: relative; top: -12px; left: 70px; "  alt="upArrow" />
				<div class="suggestionList" id="autoSuggestionsList">
				</div>
			</div>
		</div>

</td><td>
<input class='form-control' type = 'text' size='40' maxlength='60' name='concepto' tabindex='6' value ="<?php echo $concepto?>">
</td><td>
<input class='form-control' type = 'text' value ='<?php echo $referencia?>' size='10' maxlength='10' name='referencia' tabindex='7'>
</td><td>
<input class='form-control' type = 'text' size='11' maxlength='11' name='elmonto' value='<?php echo $elmonto;?>' tabindex='8'>
</td>
</tr>
<tr>
<?php
}
?>
