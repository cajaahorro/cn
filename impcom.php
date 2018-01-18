<?php
include("home.php");
extract($_GET);
extract($_POST);
extract($_SESSION);
?>
<script language="javascript">
function abrirVentana(asiento)
{
window.open("impcompdf.php?asiento="+asiento,"parte1","top=0,left=395,status=no,toolbar=no,scrollbar=yes,location=no,type=fullWindow,fullscreen");
}
</script>

<?php
$accion=$_GET['accion'];
if ((!$asiento) and (!$accion)) {
	echo 'entre ';
	echo "<div id='div1'>";
	echo "<form method='post' name='form1' action='impcom.php?accion=Revisar'>\n";
	echo '<fieldset><legend>Indique Tipo de Impresion para el asiento '.$asiento.'</legend>';
	echo "Asiento: <input type='text' name='asiento'>\n";
	echo "<input type='submit' name = 'formu' value='Buscar Asiento'>\n";
	echo "<h2><input class='checkbox' type='checkbox' name='formato' checked>Impresion en Hoja Blanca</h2>";
	echo "<h2><input type='checkbox' name='agrupar' checked>Agrupado por Cuentas </h2><br>";
	echo '</fieldset>';
	echo "</form>\n";
	echo "</div></body></html>";
//	exit;
}


if (($asiento) and (!$accion)) {
	echo "<div id='div1'>";
	echo "<form action='impcom.php?accion=Listo' name='form1' method='post'>"; //  onsubmit='return realizo_abono(form1)'>";
	$asiento=$_GET['asiento'];
	$result = mysql_query("SELECT enc_clave, enc_explic FROM sgcaf830 WHERE enc_clave = '$asiento'");
	if (mysql_num_rows($result) == 0) {
		echo "<p />Asiento <span class='b'>$asiento</span> inexistente o Apunte Huérfano.</div></body></html>";
		exit;
	}
	echo '<fieldset><legend>Indique Tipo de Impresion para el asiento '.$asiento.'</legend>';
	echo "<input type='hidden' name='asiento' value='$asiento'>";
	echo '<div class="form-group">';
	echo "<h2><input type='checkbox' name='formato' checked>Impresion en Hoja Blanca</h2>";
	echo "<h2><input class='check' type='checkbox' name='agrupar' checked>Agrupado por Cuentas </h2><br>";
	echo "<input type='submit' class=\"btn btn-success\" id='Buscar' value='Continuar' />";
	echo '</div>';
	echo '</legend>';
	echo '</fieldset>';
	echo '</form>';
	echo '</div>';	
//	$fila = mysql_fetch_array($result);
}
if (($asiento) and ($accion=='Revisar')) {
	echo "<div id='div1'>";
	echo "<form action='impcom.php?accion=Listo' name='form1' method='post'>"; //  onsubmit='return realizo_abono(form1)'>";
	$asiento=$_POST['asiento'];
	$result = mysql_query("SELECT enc_clave, enc_explic FROM sgcaf830 WHERE enc_clave = '$asiento'");
	if (mysql_num_rows($result) == 0) {
		echo "<p />Asiento <span class='b'>$asiento</span> inexistente o Apunte Huérfano.</div></body></html>";
		exit;
	}
	echo '<fieldset><legend>Indique Tipo de Impresion para el asiento '.$asiento.'</legend>';
	echo "<input type='hidden' name='asiento' value='$asiento'>";
	echo "<input type='hidden' name='formato' ".(isset($formato)?'1':'0').'>';
	echo "<input type='hidden' name='agrupar' ".(isset($agrupar)?'1':'0').'>';
	echo "<input type='submit' class=\"btn btn-success\" id='Buscar' value='Continuar' />";
//	echo '<input type="submit" name="Submit" value="Continuar">';
	echo '</legend>';
	echo '</fieldset>';
	echo '</form>';
	echo '</div>';	
//	$fila = mysql_fetch_array($result);
}

if ((asiento) and ($accion=='Listo')) {
	echo "<div id='div1'>";
	extract($_POST);
	echo '<fieldset><legend>Imprimir el asiento '.$asiento.'</legend>';
	
	echo "<input class=\"btn btn-success\" id=\'imprimir\' type='submit' name='Submit' value='Impresi&oacute;n de Asiento' ";
	echo 'onClick="abrirVentana(';
	echo "'";
	echo $asiento;
	echo "&hoja=";
	echo (isset($formato)?'1':'0');
	echo "&agrupar=";
	echo (isset($agrupar)?'1':'0');
	echo "'";
	echo ');">  ';


	
	echo '</legend>';
	echo '</fieldset>';
//	echo '</form>';
	echo '</div>';	
}
?>
