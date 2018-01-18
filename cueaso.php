<?php
include("home.php");
extract($_GET);
extract($_POST);
extract($_SESSION);
$readonly=" readonly='readonly'";
$ip = la_ip();
try
{
	if (!$codigo) 
	{
		$correcto=0;
	//    <form class="form-cuentas" method="post" id="form0-cuentas-asoc">
		echo '<div class="signin-form">';
		echo '<div class="container">';
		echo '<form class="form-inline" id="consulta" name="consulta" action="cueaso.php">'; // onsubmit="ConsultarCuentasAsoc(\'ConsultarCuentasAsoc.php\'); return false">';
		echo '<h2 class="form-signin-heading">Cuentas Asociadas</h2><hr />';
		echo '<div class="form-group">';
		echo '<input type="text" class="form-control" placeholder="C&oacute;digo del Socio" name="codigo" id="codigo" aria-describedby="help-nombre" size="10" maxlength="10" id="inputString5" onKeyUp="lookup7(this.value);" onBlur="fill7();" autocomplete="off"/>';
		echo '<span id="check-t"></span>';
		echo '</div>';
	//	echo "<form action='cueaso.php' name='form1' method='post'>";
	//	echo "<form enctype='multipart/form-data' method='post' name='form1'>";
	//	echo "C&oacute;digo del Socio <input type='text' name='codigo' size='10' maxlength='10' id='inputString5' onKeyUp='lookup7(this.value);' onBlur='fill7();' autocomplete='off' > \n";
		
	//	echo "<input type='text' size='20' tabindex='1' name='_unacedula' id='inputString5' onKeyUp='lookup5(this.value);' onBlur='fill5();' value ='$_unacedula' autocomplete='off'/>";
		echo '<div class="suggestionsBox5" id="suggestions5" style="display: none;">';
		echo '<img src="upArrow.png" style="position: relative; top: -12px; left: 70px; "  alt="upArrow" />';
		echo '<div class="suggestionList5" id="autoSuggestionsList5">';
		echo '</div>';
		echo '</div>';
		echo "<input type='submit' class=\"btn btn-primary\" id='Buscar' value='Buscar' />";
		echo '</form>';
	}
	if ($codigo) 
	{
		$codigo  = ceroizq($codigo,5);
		$sql="Select cod_prof, ape_prof, nombr_prof, ced_prof from ".$_SESSION['institucion']."sgcaf200 where cod_prof= :codigo";
		$result = $db_con->prepare($sql);
		$result->execute([":codigo"=>$codigo]);
		if ($result->rowCount() == 0) {
			mensaje(['tipo'=>'danger','titulo'=>'Aviso','texto'=>'<h2>C&oacute;digo $codigo no esta registrado<h2>']	);
		}
	    else 
		{
			$rsocio=$result->fetch(PDO::FETCH_ASSOC);
			$codigo  = substr($codigo,(4*-1));
			$sql="select cue_codigo, cue_saldo from ".$_SESSION['institucion']."sgcaf810 where right(cue_codigo,4)=:codigo order by cue_codigo";
			$a810=$db_con->prepare($sql);
			$a810->execute([":codigo"=>$codigo]);
	//		echo $sql;
			echo "<table class = 'table'>";
			echo '<tr><th>C&eacute;dula '.$rsocio['ced_prof'].' </th><th>Nombre '.$rsocio['ape_prof'].' '.$rsocio['nombr_prof'].' </th><th>Cuentas Asociadas <span class="badge">'.$a810->rowCount().'</span></th>';
			echo '<tr><th align="center" width="95">C&oacute;digo</th><th align="left" width="250">Cuenta</th><th align="center" width="100">Saldo Inicio</th><th align="center" width="100">Saldo Actual</th></tr>';
			while($r810=$a810->fetch(PDO::FETCH_ASSOC)) 
			{
				$lacuenta=$r810['cue_codigo'];
				$tamano=strlen(trim($r810['cue_codigo']))-5;
				$limitada=substr($lacuenta,0,$tamano);
				$sql="select cue_nombre from ".$_SESSION['institucion']."sgcaf810 where cue_codigo=:limitada";
				$a810_2=$db_con->prepare($sql);
				$a810_2->execute([":limitada"=>$limitada]);
				$r810_2=$a810_2->fetch(PDO::FETCH_ASSOC);
				$saldo_actual=buscar_saldo_f810($lacuenta, $db_con);
	//			echo $saldo_actual.'<br>';
				if (($r810['cue_saldo'] != 0) or ($saldo_actual != 0)) {
					echo "<tr>";
					echo "<td ><a target=\"_blank\" class='btn btn-info' href='extractoctas3.php?cuenta=".$lacuenta."'>";
					echo $limitada."</a></td>";
					echo "<td >";
					echo $r810_2['cue_nombre'].'</td>';
					echo "<td align='right' ";
					if ($r810['cue_saldo'] <=0)
						echo "class = 'danger'>";
					else 	echo "class = 'success'>";
				    echo number_format($r810['cue_saldo'],2,'.',',')."</td>";
					echo "<td align='right' ";
					if ($rsaldo_actual <=0)
						echo "class = 'danger'>";
					else 	echo "class = 'success'>";
					echo number_format($saldo_actual,2,'.',',')."</td>";
					echo "</td></tr>";
				}
			}
			echo "</table>";   
		}
	}
}
catch (PDOException $e) 
{
	mensaje(['tipo'=>'warning','titulo'=>'Aviso','texto'=>'<h2>Fallo llamado</h2>'.$sql.$e->getMessage()]);
	die('');
}

function buscar_saldo_f810($cuenta, $db_con)
{
	$sql_f810="select cue_saldo from ".$_SESSION['institucion']."sgcaf810 where cue_codigo=:cuenta";
	$lacuentas=$db_con->prepare($sql_f810); 
	// echo $sql_f810;
	$lacuentas->execute([":cuenta"=>$cuenta]);
	$lacuenta=$lacuentas->fetch(PDO::FETCH_ASSOC);
	$saldoinicial=$lacuenta['cue_saldo'];
//	echo $saldoinicial;
	if ($lacuentas->rowCount() > 0) 
	{
		$sql_f820="select com_monto1, com_monto2 from ".$_SESSION['institucion']."sgcaf820 where com_cuenta=:cuenta order by com_fecha";
//	echo $sql_f820;
		$lacuentas=$db_con->prepare($sql_f820);
		$lacuentas->execute([":cuenta"=>$cuenta]);
		while($lascuenta=$lacuentas->fetch(PDO::FETCH_ASSOC)) 
		{
			$saldoinicial+=$lascuenta['com_monto1'];
//		echo $saldoinicial.'<br>';
			$saldoinicial-=$lascuenta['com_monto2'];
//		echo $saldoinicial.'<br>';
		}
//	echo $saldoinicial.'<br>';
	}
return round($saldoinicial,2);
}
?>