<?php
/*
include("head.php");
include("paginar.php");

if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}
*/
if ($accion == 'Anadir') 
	$onload="onload=\"foco('lacedula')\""; 
else
	$onload="onload=\"foco('elcodigo')\"";
/*
	//$result = mysql_query("SELECT max(asiento) FROM asientos");
	//$row = mysql_fetch_row($result);
	//$asiento = $row[0] + 1;
	$fila = mysql_fetch_array(mysql_query("SELECT con_compr FROM sgcaf8co"));
	$asiento = $fila[0] + 1;
	mysql_query("UPDATE sgcaf8co SET con_compr = '$asiento' WHERE 1");
	// Tomo el valor de la fecha en que se hizo el último Asiento
	$result = mysql_query("SELECT date_format(con_ultfec,'%d/%m/%y') AS ultfechax FROM sgcaf8co");
	$row = mysql_fetch_array($result);
	$fecha = $row[0];
} else {
	$onload="onload=\"foco('cuenta11')\"";
	$readonly=" readonly='readonly'";
	$asiento = $_POST['asiento'];
	$fecha = $_POST['fecha'];
	$tipo =$_POST['tipo'];
	$referencia =$_POST['referencia'];
}
*/
// <script language="Javascript" src="selec_fecha_pasado.js" type='text/javascript'></script>
?>

<body <?php if (!$bloqueo) {echo $onload;}?>>


<?php

$readonly=" readonly='readonly'";
include("arriba.php");
$menu61=1;
include("menusizda.php");
$cedula = $_GET['cedula'];
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
if ($accion == 'Buscar')  {
	extract($_POST);
	$elcodigo = trim($_POST['codigo']);
	$lacedula = trim($_POST['cedula']);
	echo $lacedula. ' - ' .$elcodigo . ' - '.$accion;
	if ($elcodigo) { //  != ' ') {
	    $sql="SELECT * FROM sgcaf200 where cod_prof = '$elcodigo'";
		$result=mysql_query($sql);
		$row= mysql_fetch_assoc($result);
		echo "<input type = 'hidden' value ='".$row['cod_prof']."' name='codigo'>"; 
		$cedula=$row['ced_prof'];
		$accion = 'Editar'; } 
	else if ($lacedula) { 
		$cedula=$lacedula;
		$sql="SELECT * FROM sgcaf200 where ced_prof = '$lacedula'";
		$result=mysql_query($sql);
		$row= mysql_fetch_assoc($result);
		echo "<input type = 'hidden' value ='".$row['ced_prof']."' name='cedula'>"; 
		$cedula=$row['ced_prof'];
		$accion = 'Editar'; }
		else $accion = '';
}
if ($accion == 'Anadir1') {
	extract($_POST);
	$codigo = $_POST['elcodigo'];
	$lafechaingresomysql=convertir_fecha($lafechaingreso);
	$fejubilacionmysql=convertir_fecha($fejubilacion);
	$lafechanacmysql=convertir_fecha($lafechanac);
	$fing_uclamysql=convertir_fecha($fing_ucla);
	$lafecharetiromysql=convertir_fecha($lafecharetiro);
	$numerocedula=$lacedula;
	$lacedula=$laletraced.'-'.$lacedula;
	$eltelefonoh=$elareatelefonoh.$eltelefonoh ;
	$elcelular1=$elareacelular1.$elcelular1;
	$elcelular2=$elareacelular2.$elcelular2;
	$eltelefonot=$elareatelefonot.$eltelefonot;
	$elfax=$elareafax.$elfax;
	if ($codigo) {
		$sql = "select * from sgcaf200 where cod_prof = '$elcodigo'";
		$result=mysql_query($sql);
		if (mysql_num_rows($result) > 0)
			die ('No se puede asignar este código ya esta registrada a '.$row['ape_prof'].' '.$row['nombr_prof']);
		$sql = "select * from sgcaf200 where ced_prof = '$lacedula'";
		$result=mysql_query($sql);
		if (mysql_num_rows($result) > 0)
			die ('No se puede registrar a '.$row['ape_prof'].' '.$row['nombr_prof'].' ya existe ');
		if ($elemail) {
			$sql = "SELECT * FROM sgcaf200 WHERE mail_prof = '$elemail'";
			$result=mysql_query($sql);
			if (mysql_num_rows($result) > 1)
				die ('No se puede asignar esta dirección de email, ya esta registrada ');
			while($row=mysql_fetch_assoc($result)) {
				if ($row['ced_prof'] <> $lacedula)
					die ('No se puede asignar esta dirección de email, ya esta registrada a nombre de '.$row['ape_prof'].' '.$row['nombr_prof']);
			}
		}
		
		$sql="INSERT INTO sgcaf200 (
			cod_prof, ced_prof, nombr_prof, ape_prof, dire1_prof, dire2_prof, telf_prof, 
			escu_prof, dept_prof, ubic_prof, f_ing_ucla, tipo_prof, sueld_prof, f_ing_capu, 
			f_ret_capu, statu_prof, jubilado, escuela, cargo, tipo_cuenta, nro_cuenta, cta_nva, 
			dirn1_prof, dirn2_prof, teln_prof, celn_prof, cel2n_prof,
			ofin_prof, ctan_prof, lnaci_prof, fnaci_prof, 
			libre_prof, nombr_empr, dire1_empr, dire2_empr,
			tele_empr, ext_empr, fax_empr, mail_prof, sexo_prof, 
			condi_prof, categ_prof, refer_reti, motiv_reti, aport_empr, aport_prof, aport_extr, 
			hab_f_empr, hab_f_prof, hab_f_extr, hab_f_capi, total_pres, total_gara, total_fian,
			tipo_socio, disp_prof, vivo, cotiza, retirado, clave, 
			ultap_prof, ultap_emp, ultap_extr, ultap_div, 
			ultapm_prof, ultapm_emp, ultapm_extr, ultapm_div, civil_prof, ip_prof,
			cedn_prof
			) 
		VALUES (
			'$elcodigo', '$lacedula', '$elnombre', '$elapellido', '$ladireccionh1', '$ladireccionh2', '$eltelefonoh', 
			'$laescuela', '$eldpto', '$laubicacion', '$fing_uclamysql', '$eltipoafiliado', '$elsueldo', '$lafechaingresomysql', 
			'$lafecharetiromysql', '$elestatus', '$fejubilacionmysql', '$laescuela', '$elcargo', '$eltipocuenta', '$elnrocuenta', '$elnrocuenta', 
		 	'$ladireccionh1', '$ladireccionh2', '$eltelefonoh',  '$elcelular1',  '$elcelular2',
			'$eltelefonot', '$elnrocuenta', '$ellugar', '$lafechanacmysql',
		 	'$elejercicio', '$laempresa',  '$ladirecciont1', '$ladirecciont2',
		 	'$eltelefonot', '$laextension',  '$elfax',  '$elemail',  '$optsexo', 
			'', '', '', '', '$por_socio', '$por_patrono',0,
			0,0,0,0,0,0,0,
			'P',0, 0,0,0,'',
			'0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00',
			0, 0, 0, 0, '$elcivil', '$ip',
			'$numerocedula'
			)";
// 		echo $sql;
		mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
		$sql="update sgcaf100 set ultcod_uso ='$elcodigo' limit 1";
		mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
 
		$accion="";
		}
}

if ($accion == 'Editar1') {
	extract($_POST);
	$codigo = $_POST['elcodigo'];
	$laletraced = $_POST['laletraced'];
	$num = 1;
	$lafechanac=$_POST['lafechanac'];
	$lafechaingresomysql=convertir_fecha($lafechaingreso);
	$fejubilacionmysql=convertir_fecha($fejubilacion);
	$lafechanacmysql=convertir_fecha($lafechanac);
/*
	echo 'nacimiento '.$lafechanac.'<br>';
	echo 'lafechanacmysql '.$lafechanacmysql;
	die('espero0');
*/
	$fing_uclamysql=convertir_fecha($fing_ucla);
	$lafecharetiromysql=convertir_fecha($lafecharetiro);
	$lacedula=$laletraced.'-'.$lacedula;
	$eltelefonoh=$elareatelefonoh.$eltelefonoh ;
	$elcelular1=$elareacelular1.$elcelular1;
	$elcelular2=$elareacelular2.$elcelular2;
	$eltelefonot=$elareatelefonot.$eltelefonot;
	$elfax=$elareafax.$elfax;
	if ($elemail) {
		$sql = "SELECT * FROM sgcaf200 WHERE mail_prof = '$elemail'";
		$result=mysql_query($sql);
		if (mysql_num_rows($result) > 1)
			die ('No se puede asignar esta dirección de email, ya esta registrada ');
		while($row=mysql_fetch_assoc($result)) {
			if ($row['ced_prof'] <> $lacedula)
				die ('No se puede asignar esta dirección de email, ya esta registrada a nombre de '.$row['ape_prof'].' '.$row['nombr_prof']);
		}
	}
	$hoy = date("Y-m-d");
	if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
	$sql="UPDATE sgcaf200 SET nombr_prof = '$elnombre', ape_prof = '$elapellido', dire1_prof = '$ladireccionh1',  
		dire2_prof = '$ladireccionh2', telf_prof = '$eltelefonoh', escu_prof = '$laescuela', dept_prof = '$eldpto', 
		ubic_prof = '$laubicacion',  f_ing_ucla = '$fing_uclamysql', tipo_prof='$eltipoafiliado', sueld_prof='$elsueldo',
		f_ing_capu = '$lafechaingresomysql', f_ret_capu= '$lafecharetiromysql', statu_prof = '$elestatus', 
		jubilado = '$fejubilacionmysql', escuela = '$laescuela',
		cargo = '$elcargo', tipo_cuenta = '$eltipocuenta', nro_cuenta = '$elnrocuenta', dirn1_prof='$ladireccionh1',  
		dirn2_prof = '$ladireccionh2', teln_prof = '$eltelefonoh', celn_prof = '$elcelular1', cel2n_prof = '$elcelular2',
		ofin_prof = '$eltelefonot', ctan_prof = '$elnrocuenta', lnaci_prof = '$ellugar', fnaci_prof = '$lafechanacmysql',
		nacimiento = '$lafechanacmysql', 
		libre_prof = '$elejercicio', nombr_empr = '$laempresa', dire1_empr = '$ladirecciont1', dire2_empr = '$ladirecciont2',
		tele_empr = '$eltelefonot', ext_empr = '$laextension', fax_empr = '$elfax', mail_prof = '$elemail', sexo_prof = '$optsexo',
		ubic_prof = '$laubicacion', cta_nva = '$elnrocuenta' , civil_prof = '$elcivil', 
		aport_prof = '$por_socio', aport_empr = '$por_patrono',
		ultima_act = '$hoy', ip_modifica = '$ip'
	WHERE ced_prof = '$lacedula'";
//	echo $sql;
	mysql_query($sql) or die ("<p />El usuario $usuario no tiene permisos para modificar Clientes <br>".mysql_error()."<br>".$sql);
	$accion='';
}

if ($accion == 'Borrar') {
	extract($_POST);
	$codigo = $_POST['codigo'];
	mysql_query("DELETE FROM sgcaf810 WHERE cue_codigo = $codigo") or die ("<p />El usuario $usuario no tiene permisos para borrar Cuentas");

}


// <table class='basica 100 hover' width='100%'>
?>
<?php 
if (!$accion) {
//	echo "<div id='div1'>";
	echo "<form action='regsocios.php?accion=Buscar' id='form1' name='form1' enctype='multipart/form-data' method='post'>";
    echo 'C&oacute;digo ';
	echo '<input name="codigo" type="text" id="codigo" value=""  size="6" maxlength="6" />';
    echo '  C&eacute;dula ';
	echo '<input name="cedula" type="text" id="cedula" value=""  size="10" maxlength="10" />';
	echo "<input type = 'submit' value = 'Buscar'>";
	echo '</form>';

	echo "<table class='basica 100 hover' width='100%'><tr>";
	echo '<th><a href=?ord=cod_prof>Código</a></th><th><a href=?ord=ced_prof>Cédula</a></th><th><a href=?ord=ape_prof>Nombre</a>';
	echo '[ <a href="regsocios.php?accion=Anadir">           Nuevo Socio</a> ]</th></tr>';
	$ord = $_GET['ord'];
	if (!$ord) $ord='cod_prof';
//	echo 'orden '.$orden;
//	echo 'ord '.$ord;
//	$ord= $orden;
	$conta = $_GET['conta'];
	if (!$_GET['conta']) {
		$conta = 1;
	}
$sql = "SELECT COUNT(cod_prof) AS cuantos FROM sgcaf200";
	$rs = mysql_query($sql);
	$row= mysql_fetch_array($rs);
	$numasi = $row[cuantos]; 
	
	
	$sql = "SELECT cod_prof, ced_prof, ape_prof, nombr_prof FROM sgcaf200 ORDER BY $ord "." LIMIT ".($conta-1).", 20";
	$rs = mysql_query($sql);
//	echo $sql;

	if (pagina($numasi, $conta, 20, "Asociados", $ord)) {$fin = 1;}

// bucle de listado

	while($row=mysql_fetch_array($rs)) {
		echo "<tr>";
		echo "<td class='centro'>";
		echo "<a href='regsocios.php?accion=Editar&codigo=".$row['cod_prof']."'>";
		echo $row['cod_prof']."</a></td>";
		echo "<td class='centro'>";
		echo "<a href='regsocios.php?accion=Editar&cedula=".$row['ced_prof']."'>";
		echo $row['ced_prof']."</a></td>";
		echo "<td class='centro'>";
		echo "<a href='regsocios.php?accion=Editar&cedula=".$row['ced_prof']."'>";
		echo trim($row['ape_prof']). ' '.trim($row['nombr_prof'])."</a></td>";
		echo "</tr>";
	}

	echo "</table>";

	pagina($numasi, $conta, 20, "Asociados", $ord);
//	echo "</div>";
}
?>

<?php

/*
if ($accion == "Anadir2") {
	extract($_POST);
	$codigo = $_POST['codigo'];
	if ($codigo) {
		$sql="SELECT * FROM sgcaf810 WHERE cue_codigo = '$codigo'";
//		$sql="call sp_qry_cuenta('$codigo')";
		$rs=mysql_query($sql) or die ("<p />El usuario $usuario no tiene permisos para modificar cuentas");
		$fila = mysql_fetch_array($rs);
		if (mysql_num_rows($rs) > 0) {
			echo "<label>Descripción</label><br /><input type = 'text' value ='".$fila['cue_nombre']."' size='40' maxlength='40' name='nombre' readonly='readonly'><br />";
			echo "<label>Saldo Inicial</label><br /><input type = 'text' size='40' maxlength='15' name='saldoi' readonly='readonly' value ='".number_format($fila['cue_saldo'],$_SESSION['deci'],'.',',')."'<br />";
		}
	else {
		echo "<form action='cuentas.php?accion=Anadir1' name='form1' method='post' onsubmit='return gccli(form1)'>";		
		echo "<form action='cuentas.php?accion=Anadir2' name='form1' method='post'>";
		echo "<label>Código de Cuenta</label><br /><input type = 'text' size='40' maxlength='40' name='codigo'><br />";
		echo "<label>Descripción </label><br /><input type = 'text' size='40' maxlength='40' name='nombre'><br />";
		echo "<label>Saldo Inicial</label><br /><input type = 'text' size='40' maxlength='15' name='saldoi'><br />";
		echo "<input type = 'submit' value = 'Añadir'>";
		echo "</form>\n";
		}
	}
}
*/
if ($accion == "Anadir") {
	echo '<div id="div1">';
	echo "<form action='regsocios.php?accion=Anadir1' name='form1' id='form1' enctype='multipart/form-data' method='post' onsubmit='return valsoc(form1)'>";
	$sql='SELECT * FROM sgcaf200 WHERE ced_prof= "xx"';
	$result = mysql_query($sql) or die ('Error 200-1 <br>'.$sql.'<br>'.mysql_error());
	pantalla_socio($result,$accion);
	echo "<input type = 'submit' value = 'Grabar Datos'>";
	echo '</div>';
// 	echo "</form>\n";

}

if ($accion == "Editar") {
	echo '<div id="div1">';
	$sql='SELECT * FROM sgcaf200 WHERE ced_prof= "'.$cedula.'"';
	$result = mysql_query($sql) or die ('Error 200-1 <br>'.$sql.'<br>'.mysql_error());
	$temp = "";
	echo "<form enctype='multipart/form-data' action='regsocios.php?accion=Editar1' name='form1' id='form1' method='post' onsubmit='return valsoc(form1)'>";
	pantalla_socio($result,$accion);
	echo "<br><input type = 'submit' value = 'Confirmar cambios'></form>\n";
	echo "<a href='regbenef.php?cedula=".$cedula."'>Actualizar Beneficiarios";
	echo '</div>';
/*	if (!$temp) {
		echo "<p /><form action='cuentas.php?accion=Borrar' name='form2' method='post'>\n";
		echo "<input type='hidden' name='codigo' value=".$codigo.">\n";
		echo "<input type='submit' value='Borrar Cuenta' onclick='return borrar_cuenta()'></form>\n";
	}
*/
}
?>

<?php include("pie.php");?></body></html>


<?php
function pantalla_socio($result,$accion)
{
	$deci=$_SESSION['deci'];
	$sep_decimal=$_SESSION['sep_decimal'];
	$sep_miles=$_SESSION['sep_miles'];
	$fila = mysql_fetch_assoc($result);
	echo "<input type = 'hidden' value ='".$fila['ced_prof']."' name='cedula'>";
	if ($accion == 'Editar') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
	if ($accion == 'Anadir') {
		$elcodigo=nuevo_codigo(); 
		$ingreso=date("d/m/Y", time());
		}
	else  $elcodigo=$fila['cod_prof'];
?>	
  <fieldset><legend>Información Personal </legend>
  <table width="639" border="1">
    <tr><td width="200" >C&oacute;digo</td>
    <td width="163" class="rojo"><input name="elcodigo" type="text" id="elcodigo" readonly = "readonly" value="<?php echo $elcodigo; ?>"  size="6" maxlength="6" />*</td>
    <td width="138">Cédula</td>
      <td width="173" class="rojo"> <?php $letracedula=substr($fila['ced_prof'],0,1); ?>
	 <?php 
			if ($accion != 'Editar') {
				echo '<select id="laletraced" name="laletraced" size="1" '.$activada.'>';
				echo '<option value=V'.($letracedula==substr($fila["ced_prof"],0,1)?' selected':'').'>V</option>';
				echo '<option value=E'.($letracedula==substr($fila["ced_prof"],0,1)?' selected':'').'>E</option>';
			 	echo '</select> '; 
			}
			else {
				  ?> <input name="laletraced" type="hidden" id="laletraced" value="<?php echo substr($fila['ced_prof'],0,1); ?>" <?php echo $lectura; ?> size="8" maxlength="8" />
				<?php echo $letracedula.'-';
			}
	?>

	  <input name="lacedula" type="text" id="lacedula" value="<?php echo substr($fila['ced_prof'],2,10); ?>" <?php echo $lectura; ?> size="8" maxlength="8" />*</td></tr>
    <tr><td width="127">Apellido(s)</td>
      <td width="173" class="rojo"><input name="elapellido" type="text" id="elapellido" size="25" maxlength="25" onChange="conMayusculas(this)" value="<?php echo $fila['ape_prof'] ?>" />*</td>
      <td width="138">Nombre(s)</td>
      <td width="173" class="rojo"><input name="elnombre" type="text" id="elnombre" size="25" maxlength="25" onChange="conMayusculas(this)" value="<?php echo $fila['nombr_prof'] ?>" />*</td></tr>
 
    <tr>
      <td>Dirección</td>
      <td class="rojo" colspan="5">
	  <input name="ladireccionh1" type="text" id="ladireccionh1" size="28" maxlength="30" onChange="conMayusculas(this)" value="<?php echo $fila['dire1_prof'] ?>" />
      <input name="ladireccionh2" type="text" id="ladireccionh2" size="32" maxlength="30" onChange="conMayusculas(this)" value="<?php echo $fila['dire2_prof'] ?>" />*</td></tr>
    <tr>
      <td width="127">Tel&eacute;fono</td>
      <td width="173">
	  <input name="elareatelefonoh" type="text" id="elareatelefonoh" size="4" maxlength="4" value="<?php echo substr($fila['telf_prof'],0,4) ?>" onChange="valida_codarea(elareatelefonoh)" /> - 
	  <input name="eltelefonoh" type="text" id="eltelefonoh" size="7" maxlength="7" value="<?php echo substr($fila['telf_prof'],4,10) ?>" /></td>
	  
      <td width="138">Celular(es)</td>
      <td width="173" colspan="3">
	  <input name="elareacelular1" type="text" id="elareacelular1" size="4" maxlength="4" value="<?php echo substr($fila['celn_prof'],0,4) ?>" /> - 
	  <input name="elcelular1" type="text" id="elcelular1" size="7" maxlength="7" onSubmit ="valida_telefono(this)" value="<?php echo substr($fila['celn_prof'],4,7) ?>" /><br>
	  	  <input name="elareacelular2" type="text" id="elareacelular2" size="4" maxlength="4" value="<?php echo substr($fila['cel2n_prof'],0,4) ?>" /> -
      <input name="elcelular2" type="text" id="elcelular2" size="7" maxlength="7" value="<?php echo substr($fila['cel2n_prof'],4,7) ?>" /></td></tr>
    <tr>
      <td>E-mail</td>
      <td colspan="5"><input name="elemail" type="text" id="elemail" size="80" maxlength="80" onChange="isEmailAddress(this)" value="<?php echo $fila['mail_prof'] ?>" /></td></tr>
    <tr>
      <td>Lugar de Nacimiento </td>
      <td><input name="ellugar" type="text" id="ellugar" size="20" maxlength="20" onChange="conMayusculas(this)" value="<?php echo $fila['lnaci_prof'] ?>" /></td>
      <td>Fecha de Nacimiento</td>
	<input type="hidden" name="lafechanac" id="lafechanac" value=" <?php  echo convertir_fechadmy($fila['fnaci_prof']); ?>"/>
<td align="left">
   <span style="background-color: #ff8; cursor: default;"
         onmouseover="this.style.backgroundColor='#ff0';"
         onmouseout="this.style.backgroundColor='#ff8';"
         id="show_d2" 
   ><?php  echo convertir_fechadmy($fila['fnaci_prof']); ?></span> </td></tr>
<script type="text/javascript">
    Calendar.setup({
//		showAt(220, 250)
//		position       : 	{100,300},
        inputField     :    "lafechanac",     // id of the input field
        ifFormat       :    "%d/%m/%Y",     // format of the input field (even if hidden, this format will be honored)
        displayArea    :    "show_d2",       // ID of the span where the date is to be shown
        daFormat       :    "%A, %B %d, %Y",// format of the displayed date
        align          :    "Tl",           // alignment (defaults to "Bl")
        singleClick    :    true,
		weekNumbers    :    false, 

// desactivacion de 18 años pa' tras

		dateStatusFunc :    function (date) { // disable weekend days (Saturdays == 6 and Subdays == 0)
						var today = new Date();
						return (
							  (date.getTime() > today.getTime()-((365*18)*24*60*60*1000))
							  ) ? true : false;  }
    });
</script>

    <tr>
      <td>Sexo</td>
		<?php $elsexo=$fila['sexo_prof']; ?>
      <td>  <input type="radio" name="optsexo" value="1" <?php if ($elsexo < 2) echo " checked"?>/> Masculino 
	  <input type="radio" name="optsexo" value="2" <?php if ($elsexo == 2) echo " checked"?>/> Femenino</td>  <td colspan="1">Estado Civil </td><td>
		<?php
		$elcivil=$fila['civil_prof'];
		echo '<select name="elcivil" size="1">';
		$sql="select nombre from sgcaf000 where tipo='Civil' order by nombre";
		$resultado=mysql_query($sql);
		while ($fila2 = mysql_fetch_assoc($resultado)) {
			echo '<option value="'.$fila2['nombre'].'" '.(($elcivil==$fila2['nombre'])?'selected':'').'>'.$fila2['nombre'].'</option>';}
		 	echo '</select> '; 
		?> &nbsp;</td> </tr>
  </table>

  </fieldset>
  <fieldset><legend>Información Profesional</legend>
  
  <tr></tr>
    <table width="639" border="1">
      <tr>
        <td width="105" >Ejercicio</td>
		<?php $elejercicio=$fila['libre_prof']; ?>
        <td width="106"><input name="elejercicio" type="radio" value="1" <?php if ($elejercicio < 2) echo " checked"?>/>
        Libre <br />
        <input name="elejercicio" type="radio" value="2" <?php if ($elejercicio == 2) echo " checked"?>/>
        Dependiente</td>
        <td width="58" >Dependencia / Empresa</td>
        <td colspan="5"><input name="laempresa" type="text" id="laempresa" size="50" maxlength="60" onChange="conMayusculas(this)" value="<?php echo $fila['nombr_empr']?>"/></td>
      </tr>
      <tr>
        <td>Direcci&oacute;n Empresa </td>
        <td colspan="7"><input name="ladirecciont1" type="text" id="ladirecciont1" size="25" maxlength="25" onChange="conMayusculas(this)" value="<?php echo $fila['dire1_empr']?>"/>
        <input name="ladirecciont2" type="text" id="ladirecciont2" size="40" maxlength="40" onChange="conMayusculas(this)" value="<?php echo $fila['dire2_empr']?>"/></td>
      </tr>
      <tr>
        <td>Tel&eacute;fono<br>Ext.</td>
        <td>
		  <input name="elareatelefonot" type="text" id="elareatelefonot" size="4" maxlength="4" value="<?php echo substr($fila['tele_empr'],0,4) ?>" /> -
		<input name="eltelefonot" type="text" id="eltelefonot" size="7" maxlength="7" value="<?php echo substr($fila['tele_empr'],4,7)?>" /><br>
        <input name="laextension" type="text" id="laextension" size="4" maxlength="6" value="<?php echo $fila['ext_empr']?>"/></td>
        <td width="26">Fax</td>
        <td width="77">
		  <input name="elareafax" type="text" id="elareafax" size="4" maxlength="4" value="<?php echo substr($fila['fax_empr'],0,4) ?>" />
		<input name="elfax" type="text" id="elfax" size="7" maxlength="7" value="<?php echo substr($fila['fax_empr'],4,7)?>"/></td>
        <td width="70">Condici&oacute;n</td>
        <td width="132"><input name="lacondicion" type="text" id="lacondicion" readonly value="" size="20" /></td>
        <tr><td>Cargo</td>
        <td class="rojo"><input name="elcargo" align="center" type="text" id="elcargo" onChange="conMayusculas(this)" value="<?php echo $fila['cargo'] ?>" size="20" maxlength="12" />*</td>
		<td>Dependencia </td><td class="rojo" colspan="3">
		<?php
			$elcescuela=$fila['escuela'];
			echo '<select name="laescuela" size="1">';
			$sql="select codigo, nombre from escuelas order by nombre";
			$resultado=mysql_query($sql);
			while ($fila2 = mysql_fetch_assoc($resultado)) {
				echo '<option value="'.$fila2['codigo'].'" '.(($elcescuela==$fila2['codigo'])?'selected':'').'>'.$fila2['nombre'].'</option>';}
	 	echo '</select> '; 
		?>*

		</td>
		</tr>
		<tr>
		<td>Departamento </td><td colspan="3" class="rojo">
		<?php
			// <input name="eldpto" type="text" id="eldpto" value="<?php echo $fila['cargo'] >" size="12" maxlength="12" />
			$elcdpto=$fila['dept_prof'];
			echo '<select name="eldpto" size="1">';
			$sql="select escdpto, escuela from sgcafeyd order by escuela";
			$resultado=mysql_query($sql);
			while ($fila2 = mysql_fetch_assoc($resultado)) {
				echo '<option value="'.$fila2['escdpto'].'" '.(($elcdpto==$fila2['escdpto'])?'selected':'').'>'.$fila2['escuela'].'</option>';}
	
	 	echo '</select> '; 
		?>*
		</td>
		<td>Ubicación</td><td class="rojo">
			<input name="laubicacion" type="text" id="laubicacion" value="<?php echo $fila['ubic_prof'] ?>" size="12" maxlength="12" />
		</td>
		</tr>
		<tr>
		<td>Fecha de Ingreso</td><td colspan="5" class="rojo">

	<input type="hidden" name="fing_ucla" id="fing_ucla" value=" <?php  echo convertir_fechadmy($fila['f_ing_ucla']); ?>"/>
   <span style="background-color: #ff8; cursor: default;"
         onmouseover="this.style.backgroundColor='#ff0';"
         onmouseout="this.style.backgroundColor='#ff8';"
         id="show_d3" 
   ><?php  echo convertir_fechadmy($fila['f_ing_ucla']); ?></span> *</td></tr>
<script type="text/javascript">
    Calendar.setup({
//		showAt(220, 250)
//		position       : 	{100,300},
        inputField     :    "fing_ucla",     // id of the input field
        ifFormat       :    "%d/%m/%Y",     // format of the input field (even if hidden, this format will be honored)
        displayArea    :    "show_d3",       // ID of the span where the date is to be shown
        daFormat       :    "%A, %B %d, %Y",// format of the displayed date
        align          :    "Tl",           // alignment (defaults to "Bl")
        singleClick    :    true,
		weekNumbers    :    false, 

// desactivacion de 18 años pa' tras


		dateStatusFunc :    function (date) { // disable weekend days (Saturdays == 6 and Subdays == 0)
						var today = new Date();
						return (
//							  (date.getTime() < today.getTime()-((365*18)*24*60*60*1000))
							  (date.getTime() > today.getTime()-(1*24*60*60*1000)) 
							  // || date.getTime() > today.getTime()+(10*24*60*60*1000))	date.getDay() == 0 || 
							  ) ? true : false;  }
    });
</script>
	</td>
     </tr>
  </table>
</fieldset>
<fieldset>
<legend>Información Administrativa de la Instituci&oacute;n </legend>
<table width="639" border="1">
  <tr>
    <td width="119" scope="col">Tipo de Afiliado</td>
    <td width="155" scope="col" class="rojo">
	<?php
			$eltipo=$fila['tipo_prof'];
			echo '<select name="eltipoafiliado" size="1">';
			$sql="select nombre from sgcaf000 where tipo='Afiliado' order by nombre";
			$resultado=mysql_query($sql);
			while ($fila2 = mysql_fetch_assoc($resultado)) {
				echo '<option value="'.$fila2['nombre'].'" '.(($eltipo==$fila2['nombre'])?'selected':'').'>'.$fila2['nombre'].'</option>';}
	 	echo '</select> '; 
		?>*
    <td width="127" scope="col">Fecha de Ingreso</td>
    <td class="rojo" width="210" scope="col">

	<input type="hidden" name="lafechaingreso" id="lafechaingreso" value=" <?php  echo convertir_fechadmy($fila['f_ing_capu']); ?>"/>
   <span style="background-color: #ff8; cursor: default;"
         onmouseover="this.style.backgroundColor='#ff0';"
         onmouseout="this.style.backgroundColor='#ff8';"
         id="show_ingcapu" 
   ><?php  echo convertir_fechadmy($fila['f_ing_capu']); ?></span> *
<script type="text/javascript">
    Calendar.setup({
//		showAt(220, 250)
//		position       : 	{100,300},
        inputField     :    "lafechaingreso",     // id of the input field
        ifFormat       :    "%d/%m/%Y",     // format of the input field (even if hidden, this format will be honored)
        displayArea    :    "show_ingcapu",       // ID of the span where the date is to be shown
        daFormat       :    "%A, %B %d, %Y",// format of the displayed date
        align          :    "Tl",           // alignment (defaults to "Bl")
        singleClick    :    true,
		weekNumbers    :    false, 

// desactivacion de 18 años pa' tras


		dateStatusFunc :    function (date) { // disable weekend days (Saturdays == 6 and Subdays == 0)
						var today = new Date();
						return (
//							  (date.getTime() < today.getTime()-((365*18)*24*60*60*1000))
							  (date.getTime() > today.getTime()-(1*24*60*60*1000)) 
							  // || date.getTime() > today.getTime()+(10*24*60*60*1000))	date.getDay() == 0 || 
							  ) ? true : false;  }
    });
</script>
	</td>
  </tr>
  <tr>
    <td>Fecha de Retiro</td>
    <td class='rojo'>
    
	<input type="hidden" name="lafecharetiro" id="lafecharetiro" value=" <?php  echo convertir_fechadmy($fila['f_ret_capu']); ?>"/>
   <span style="background-color: #ff8; cursor: default;"
         onmouseover="this.style.backgroundColor='#ff0';"
         onmouseout="this.style.backgroundColor='#ff8';"
         id="show_lafecharetiro" 
   ><?php  echo convertir_fechadmy($fila['f_ret_capu']); ?></span> *
<script type="text/javascript">
    Calendar.setup({
//		showAt(220, 250)
//		position       : 	{100,300},
        inputField     :    "lafecharetiro",     // id of the input field
        ifFormat       :    "%d/%m/%Y",     // format of the input field (even if hidden, this format will be honored)
        displayArea    :    "show_lafecharetiro",       // ID of the span where the date is to be shown
        daFormat       :    "%A, %B %d, %Y",// format of the displayed date
        align          :    "Tl",           // alignment (defaults to "Bl")
        singleClick    :    true,
		weekNumbers    :    false, 

// desactivacion de 18 años pa' tras


		dateStatusFunc :    function (date) { // disable weekend days (Saturdays == 6 and Subdays == 0)
						var today = new Date();
						return (
//							  (date.getTime() < today.getTime()-((365*18)*24*60*60*1000))
							  (date.getTime() > today.getTime()-(1*24*60*60*1000)) 
							  // || date.getTime() > today.getTime()+(10*24*60*60*1000))	date.getDay() == 0 || 
							  ) ? true : false;  }
    });
</script>
	</td>
    <td>Estatus</td>
    <td class="rojo">
	<?php
		if (($_SERVER['REMOTE_ADDR']=='192.168.1.9') OR ($_SERVER['REMOTE_ADDR']=='192.168.1.96') OR ($_SERVER['REMOTE_ADDR']=='192.168.1.3') or ($_ENV['COMPUTERNAME']=='JCHB-DM1'))	// icono para modificar socios
		{
			//	jc / mendez viejo / mendez nuevo 
			$elstatus=substr(strtoupper($fila['statu_prof']),0,6);
//			echo 'el estatus '.$elstatus;
			echo '<select name="elestatus" size="1">';
			$sql="select SUBSTR(upper(nombre),1,6) as nombre from sgcaf000 where tipo='Estatus' order by nombre";
			$resultado=mysql_query($sql);
			while ($fila2 = mysql_fetch_assoc($resultado)) {
				echo '<option value="'.strtoupper($fila2['nombre']).'" '.(($elstatus==strtoupper($fila2['nombre']))?'selected':'').'>'.$fila2['nombre'].'</option>';}
		 	echo '</select> '; 
		}
		else {
			echo $fila['statu_prof'];
			echo '<input type="hidden" id="elestatus" name="elestatus" value="'.$fila['statu_prof'].'">';
		}
		?>*
	</td>
  </tr>
  <tr>
    <td>Tipo de Cuenta</td><td class="rojo">
	<?php
			$eltipocta=$fila['tipocuenta'];
			echo '<select name="eltipocuenta" size="1">';
			$sql="select nombre from sgcaf000 where tipo='Cuentas' order by nombre";
			$resultado=mysql_query($sql);
			while ($fila2 = mysql_fetch_assoc($resultado)) {
				echo '<option value="'.$fila2['nombre'].'" '.(($eltipocta==$fila2['nombre'])?'selected':'').'>'.$fila2['nombre'].'</option>';}  
	 	echo '</select> *'; 
	echo '</td>';
	
	echo '<td>Nro. Cuenta</td>';
	if (($_SERVER['REMOTE_ADDR']=='192.168.1.9') OR ($_SERVER['REMOTE_ADDR']=='192.168.1.96') OR ($_SERVER['REMOTE_ADDR']=='192.168.1.3') or ($_ENV['COMPUTERNAME']=='JCHB-DM1'))	// icono para modificar socios
	{
		echo '<td class="rojo"><input name="elnrocuenta" type="text" size="20" maxlength="20" id="elnrocuenta" value="'.$fila['ctan_prof'] .'" />*';
	}
	else {
		echo '<td class="rojo">'.$fila['ctan_prof'].'*';
		echo '<input type="hidden" id="elnrocuenta" name="elnrocuenta" value="'.$fila['ctan_prof'].'">';
	}
	echo '</td>';
?>	
  </tr>
  <tr>
    <td>Jubilación</td>
	<td class='rojo'>

	<input type="hidden" name="fejubilacion" id="fejubilacion" value=" <?php  echo convertir_fechadmy($fila['jubilado']); ?>"/>
   <span style="background-color: #ff8; cursor: default;"
         onmouseover="this.style.backgroundColor='#ff0';"
         onmouseout="this.style.backgroundColor='#ff8';"
         id="show_fejubilacion" 
   ><?php  echo convertir_fechadmy($fila['f_ret_capu']); ?></span> *
<script type="text/javascript">
    Calendar.setup({
//		showAt(220, 250)
//		position       : 	{100,300},
        inputField     :    "fejubilacion",     // id of the input field
        ifFormat       :    "%d/%m/%Y",     // format of the input field (even if hidden, this format will be honored)
        displayArea    :    "show_fejubilacion",       // ID of the span where the date is to be shown
        daFormat       :    "%A, %B %d, %Y",// format of the displayed date
        align          :    "Tl",           // alignment (defaults to "Bl")
        singleClick    :    true,
		weekNumbers    :    false, 

// desactivacion de 18 años pa' tras


		dateStatusFunc :    function (date) { // disable weekend days (Saturdays == 6 and Subdays == 0)
						var today = new Date();
						return (
//							  (date.getTime() < today.getTime()-((365*18)*24*60*60*1000))
							  (date.getTime() > today.getTime()-(1*24*60*60*1000)) 
							  // || date.getTime() > today.getTime()+(10*24*60*60*1000))	date.getDay() == 0 || 
							  ) ? true : false;  }
    });
</script>


</td>
	</td>
    <td>Remuneración</td>
	<td class="rojo"><input name="elsueldo" type="text" id="elsueldo" value="<?php echo $fila['sueld_prof'] ?>" />*</td>
  </tr>
</table>
</fieldset> 
<?php 
if ($accion!='Anadir')
	pida_financiera($elcodigo);
}

function nuevo_codigo()
{
	$sql="select ultcod_uso from sgcaf100 limit 1";
	$resulta2=mysql_query($sql);
	$fila2 = mysql_fetch_assoc($resulta2);
	$ultimo=$fila2['ultcod_uso'];
	$contador = 0;
	$digitos=5;
	while ($contador < 120) {
//		echo '  -  '.$ultimo;
		$contador++;
		$ultimo++;
		$ultimo=ceroizq($ultimo,$digitos);
		$sql = "select cod_prof from sgcaf200 where cod_prof = '$ultimo'";
		$resulta2=mysql_query($sql);
		if (mysql_num_rows($resulta2) < 1) // consegui uno vacio y rompo el ciclo 
			return $ultimo;
	}
}
return $ultimo;

function pida_financiera($elcodigo)
{
?>
<fieldset><legend>Información Financiera</legend>
<?php 
	$deci=$_SESSION['deci'];
	$sep_decimal=$_SESSION['sep_decimal'];
	$sep_miles=$_SESSION['sep_miles'];
	$ahorros=ahorros($fila['ced_prof']);
	$afectan=afectan($fila['ced_prof']);
	$noafectan=noafectan($fila['ced_prof']);
	$fianzas=fianzas($fila['cod_prof']);
	$sql="SELECT * FROM sgcaf200 WHERE cod_prof= '$elcodigo'";
	$result = mysql_query($sql) or die ('Error 200-1 <br>'.$sql.'<br>'.mysql_error());
	$fila = mysql_fetch_assoc($result);
  	$disponible=disponibilidad($ahorros,$afectan,$noafectan,$fianzas); ?>
<table width="639" border="1">
  <tr>
    <th width="86" scope="col">Descripci&oacute;n</th>
    <th width="33" scope="col">%</th>
    <th width="90" scope="col">Último Aporte </th>
    <th width="75" scope="col">Monto Último Aporte</th>
    <th width="87" scope="col">Haberes</th>
    <th width="82" scope="col">Pr&eacute;stamos</th>
    <th width="140" scope="col">Saldo</th>
  </tr>
  <tr>
    <th scope="row">Socio</th>
    <td>
      <input name="por_socio" align="right" type="text" id="por_socio" size="5" maxlength="5" value="<?php echo $fila['aport_prof'] ?>"/>
    </td>
    <td align="center">
        <input name="fa_socio" type="text" id="fa_socio" size="10" maxlength="10" readonly = "readonly" value="<?php echo $fila['ultap_prof']; ?>"/>
    </td>
    <td align="right"><?php echo number_format($fila['ultapm_prof'],$deci,$sep_decimal,$sep_miles) ?></td>
    <td align="right">
      <input name="hab_socio" align="right" type="text" id="hab_socio" size="14" maxlength="14" readonly = "readonly" value="<?php echo number_format($fila['hab_f_prof'],$deci,".",",");?> "/> 
    </td>
    <td><strong>Afectan Disp. </strong></td>
    <td align="right">
	<?
      // <input name="pres_afectan" align="right" type="text" id="pres_afectan" size="14" maxlength="14" />
	   echo number_format($afectan,$deci,$sep_decimal,$sep_miles); ?>
    </td>
  </tr>
  <tr>
    <th scope="row">Patrono</th>
    <td><input name="por_patrono" align="right" type="text" id="por_patrono" size="5" maxlength="5" value="<?php echo $fila['aport_empr'] ?>" /></td>
    <td align="center">
      <input name="fa_patrono" type="text" id="fa_patrono" size="10" maxlength="10" readonly = "readonly" value="<?php echo $fila['ultap_emp']; ?>" />
	</td>
    <td align="right"><?php echo number_format($fila['ultapm_emp'],$deci,$sep_decimal,$sep_miles) ?></td>
    <td align="right"><input name="hab_patrono" align="right" type="text" id="hab_patrono" size="14" maxlength="14" readonly = "readonly"  value="<?php echo number_format($fila['hab_f_empr'],$deci,".",","); ?>"/></td>
    <td><strong>No Afectan Disp.</strong></td>
    <td align="right"> <? echo number_format($noafectan,$deci,$sep_decimal,$sep_miles); ?>
    </td>
  </tr>
  <tr>
    <th scope="row">Voluntario</th>
    <td><input name="por_voluntario" align="right" type="text" id="por_voluntario" size="5" maxlength="5" value="<?php echo $fila['aport_extr'] ?>"/></td>
    <td align="center">
      <input name="fa_voluntario" type="text" id="fa_voluntario" size="10" maxlength="10" readonly = "readonly" value="<?php echo $fila['ultap_extr']; ?>"/>
    </td>
    <td align="right"><?php echo number_format($fila['ultapm_extr'],$deci,$sep_decimal,$sep_miles) ?></td>
    <td align="right"><input name="hab_voluntario" align="right" type="text" id="hab_voluntario" size="14" maxlength="14" readonly = "readonly"  value="<?php echo number_format($fila['hab_f_extr'],$deci,".",","); ?>"/></td>
    <td><strong>Fianzas Otorgadas</strong></td>
    <td align="right">
      <? // <input name="fianzas" align="right" type="text" id="fianzas" size="14" maxlength="14" />
	  	   echo number_format($fianzas,$deci,$sep_decimal,$sep_miles); ?>
    </td>
  </tr>
  <tr>
    <th scope="row">Capitalizable</th>
    <td>&nbsp;</td>
    <td align="center">
      <input name="fa_capitalizable" type="text" id="fa_capitalizable" size="10" maxlength="10" readonly = "readonly" value="<?php echo $fila['ultap_div'];?>"/>
    </td>
    <td align="right"><?php echo number_format($fila['ultapm_div'],$deci,$sep_decimal,$sep_miles) ?></td>
    <td align="right"><input name="hab_capital" align="right" type="text" id="hab_capital" size="14" maxlength="14"readonly = "readonly" value="<?php echo number_format($fila['hab_f_capi'],$deci,".",","); ?>"/></td>
    <td align="center" colspan="2"><strong> Disponibilidad Neta</strong></td>
    </tr>
  <tr>
    <th scope="row">Total</th>
    <td align="right"><?php echo number_format($fila['aport_prof']+$fila['aport_empr']+$fila['aport_extr'],$deci,$sep_decimal,$sep_miles) ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right"><?php // echo number_format($fila[hab_f_prof]+$fila[hab_f_empr]+$fila[hab_f_extr]+$fila[hab_f_capi],$deci,$sep_decimal,$sep_miles); 
	echo number_format($ahorros,$deci,$sep_decimal,$sep_miles);?> </td>
    <td align="center" colspan="2" class="<?php echo ($disponible<=0)?'rojo':'azul' ?>" >
      <? // <input name="disponible" align="right" type="text" id="disponible" size="14" maxlength="14" <?php echo $lectura;  />
	  		if ($disponible<=0)
				{
					$imagen='24-em-cross.png';
					$cuento_mostrar='Disponibilidad Negativa';
					$cuento_interno='disp_neg';
				}
			else {
					$imagen='24-em-check.png';
					$cuento_mostrar='Disponibilidad Positiva';
					$cuento_interno='disp_pos';
			}
		   echo '<img src="imagenes/'.$imagen.'" width="22" height="19" alt="'.$cuento_mostrar.'" longdesc="'.$cuento_interno.'" />';
	  	   echo number_format($disponible,$deci,$sep_decimal,$sep_miles); 
		   echo '<img src="imagenes/'.$imagen.'" width="22" height="19" alt="'.$cuento_mostrar.'" longdesc="'.$cuento_interno.'" />'?>
    </td>
    </tr>
</table>
</fieldset>
<?php
}
?>