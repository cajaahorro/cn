<?php
include("home.php");
extract($_GET);
extract($_POST);
extract($_SESSION);
if (!$cuenta) {
	echo '<div class="signin-form">';
	echo '<div class="container">';
	echo "<form enctype='multipart/form-data' method='post' name='form1'>Cuenta: ";
	echo '<h2 class="form-signin-heading">Mayor Anal&iacute;tico</h2><hr />';
	$sqlf="SELECT DATE_FORMAT(now(),'%Y-%m-%d %H:%i') as hoy, CONCAT(SUBSTR(NOW(),1,5),'01-01') AS inicio, CONCAT(SUBSTR(NOW(),1,8),'01') AS minimo, DATE_FORMAT(DATE_ADD(now(),INTERVAL -1 DAY),'%Y-%m-%d') as ayer, DATE_SUB(NOW(), INTERVAL 7 DAY) AS sietedias, DATE_SUB(NOW(), INTERVAL 1 DAY) AS ayer, DATE_SUB(NOW(), INTERVAL 30 DAY) AS treintadias";
	$stmt=$db_con->prepare($sqlf);
	$stmt->execute();
	$fechas=$stmt->fetch(PDO::FETCH_ASSOC);

	echo '<div class="form-group form-inline">';
		echo '<input class="form-control" name="cuenta" aria-describedby="help-nombre" placeholder="C&oacute;digo de Cuenta" size="20" maxlength="20" id="inputString" onKeyUp="lookup(this.value);" onBlur="fill();" autocomplete="off"/>';
	// echo '</div>';
	echo '<div class="suggestionsBox" id="suggestions" style="display: none;">';
	echo '<img src="imagenes/upArrow.png" style="position: relative; top: -12px; left: 70px; "  alt="upArrow" />';
	echo '<div class="suggestionList" id="autoSuggestionsList">';
	echo '</div>';
	echo '</div>';

?>
                <label for="rango">Indique lapso de fecha para reporte</label>
                <input type="text" name="daterange" id="daterange" value="01/01/2015 - 01/31/2015" />

                <script type="text/javascript">
                $(function() {
                    $('input[name="daterange"]').daterangepicker(
                    {
                        "timePicker": false,
                        "timePicker24Hour": true,
                        // timePickerIncrement: 10,
                        "applyLabel": "Guardar",
                        "cancelLabel": "Cancelar",
                        "fromLabel": "Desde",
                        "toLabel": "Hasta",
                        locale: {
                            format: 'YYYY-MM-DD HH:mm',
                        daysOfWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi','Sa'],
                        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                        customRangeLabel: 'Personalizado',
                        applyLabel: 'Aplicar',
                        fromLabel: 'Desde',
                        toLabel: 'Hasta',
                    },
                        startDate: "<?php echo $fechas['minimo']?>",
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
<?php 
	echo "<input type='submit' class=\"btn btn-primary\" id='Buscar' value='Buscar/Generar' />";
//	echo "<input type='submit' value='Buscar/Generar'></form> \n";
	echo '</form>';
//	include("pie.php");
//	echo "</div></body></html>";
//	exit;
}
echo '</div>';

$datos1= 'si';
if ($datos AND $datos = 'no') {
	$datos1 = '';
}


$sql="SELECT DISTINCT * FROM ".$_SESSION['institucion']."sgcaf810 WHERE cue_codigo = :cuenta";
$result = $db_con->prepare($sql); 
$result->execute(array(":cuenta"=>$cuenta));

if ($result->rowCount() == 0) {

		echo "<p /><br /><p />No existe el Nº de Cuenta <span class='b'>$cuenta</span> en la tabla Cuentas.";
		exit;

}
$fechai=$_POST['date3'];
$fechaf=$_POST['date4'];
$cuento=($fechai?' Desde '.$fechai.' Hasta '.$fechaf:'');
while ($fila = $result->fetch(PDO::FETCH_ASSOC))
	if ($fila['cue_codigo'] = $cuenta) {
		echo "<h2>Cuenta: ".$cuenta." ".$fila['cue_nombre'].$cuento." ";
	echo "<a target=\"_blank\" class='btn btn-info' href=\"mayorpdf.php?cuenta=$cuenta&fechai=$fechai&fechaf=$fechaf&encero=$encero&\" onClick=\"info.html\', \'\',\'width=250, height=190\')\">Imprimir </a>"."</h2>"; 
		break;
	}
$misaldo=calcular_saldo($fila,$fechai);
if (!$fechai) 
	$sql="SELECT * FROM ".$_SESSION['institucion']."sgcaf820 WHERE com_cuenta = '$cuenta' ORDER by com_fecha, com_refere";
else {
	$lfi=convertir_fecha($fechai);
	$lff=convertir_fecha($fechaf);
	$sql="SELECT * FROM ".$_SESSION['institucion']."sgcaf820 WHERE com_cuenta = '$cuenta' AND ((com_fecha >= '$lfi') AND (com_fecha <= '$lff')) ORDER by com_fecha, com_refere";
	}
$result = $db_con->prepare($sql);
$result->execute();

if ($result->rowCount() == 0) {

	echo "<p /><br /><p />No hay registros para el Nº de Cuenta <span class='b'>$cuenta</span>.";
	exit;

}

/* ****************** CABECERA ************************* */
	
echo "<table class = 'table'>"; 
echo '<tr><th width="40">Item</th><th width="80">Fecha</th><th width="100">Asiento</th><th width="450">Concepto</th><th width="150">Referencia</th><th width="150">Debe</th><th width="150">Haber</th><th width="150">Saldo</th>';
// echo '<tr><th>&nbsp;<th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>';
echo '<tr><td align="right" colspan="7">Saldo Inicial <td align="right" class = "'.($misaldo <=0?'danger':'success').'">';
echo number_format($misaldo,2,'.',',');
echo '</th></td>';
$debe=$haber=$item=0;
$debem=$haberm=$mesactual=0;
/* ****************** APUNTES ************************** */
while ($fila = $result->fetch(PDO::FETCH_ASSOC))
{
	if ($mesactual!=$a[1]) {
		echo "<tr><td align='right' colspan='5'>Totales Mes : ".$mesactual.'/'.$a[0].' '."</td><td align='right'>".number_format($debem,2,'.',',')."</td><td align='right'>".number_format($haberm,2,'.',',')."</td></tr>\n";
		$mesactual=$a[1];
		$debem=$haberm=0;
	}

	$a=explode("-",$fila["com_fecha"]); 
	if (($mesactual!=$a[1]) && ($mesactual != 0)) {
		echo "<tr><td align='right' colspan='5'>Totales Mes : ".$mesactual.'/'.$a[0].' '."</td><td align='right'>".number_format($debem,2,'.',',')."</td><td align='right'>".number_format($haberm,2,'.',',')."</td></tr>\n";
		$mesactual=$a[1];
		$debem=$haberm=0;
	}

	$item++;
	echo "<tr><td>".$item."</td>";
	echo "<td>".$a[2]."/".$a[1]."/".substr($a[0],2,2)."</td>";
	if ($mesactual==0) $mesactual=$a[1];
	echo "<td><a target=\"_blank\" class='btn btn-warning' href='editasi2.php?asiento=".$fila["com_nrocom"]."'>". $fila["com_nrocom"]."</a><td>".$fila["com_descri"]."</td>";	
	echo "<td>".$fila["com_refere"]."</td><td align='right' >";
	if ($fila["com_monto1"] == 0)
	{
		echo "&nbsp;";
	} else {
		echo number_format($fila["com_monto1"],2,'.',',');
		$misaldo+=$fila[com_monto1];
		$debe+=$fila[com_monto1];
		$debem+=$fila[com_monto1];
	}
	echo "</td><td align='right'>";
	if ($fila["com_monto2"] == 0)
	{
		echo "&nbsp;";
	} else {
		echo number_format($fila["com_monto2"],2,'.',',');
		$misaldo-=$fila[com_monto2];
		$haber+=$fila[com_monto2];
		$haberm+=$fila[com_monto2];
	}

	echo "</td><td align='right' class = ".($misaldo <=0?'danger':'success').">";
	echo number_format($misaldo,2,'.',',');
	echo "</td></tr> \n";
}
// fin de mes 
echo "<tr><td align='right' colspan='5'>Totales Mes : ".$mesactual.'/'.$a[0].' '."</td><td align='right' >".number_format($debem,2,'.',',')."</td><td align='right'>".number_format($haberm,2,'.',',')."</td></tr>\n";

/* ****************** SUMAS Y FIN DE TABLA*************** */


echo "<tr><td align='right' colspan='5'>Totales : "."</td><td align='right' >".number_format($debe,2,'.',',')."</td><td align='right'>".number_format($haber,2,'.',',')."</td></tr>\n";

echo "</table><p /> \n"; 


$codigo='0'.substr($cuenta,-4);
$sql="select ced_prof from ".$_SESSION['institucion']."sgcaf200 where cod_prof='$codigo'";
// echo $sql;
$result=$db_con->prepare($sql);
$result->execute();
$registro=$result->fetch(PDO::FETCH_ASSOC);
$cedula=$registro['ced_prof'];

echo '<br>Numero de cedula '.$cedula;


// include("pie.php");
?>
</body></html>
