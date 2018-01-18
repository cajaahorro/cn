<?php
function algo_fallo($registro)
{
$fallo=0;
/*
$comando = "SELECT * FROM sgcaf8co";
$afila = mysql_query($comando);
$registro = mysql_fetch_assoc($afila);
*/
/*
$db_con->prepare("SELECT * FROM sgcaf8co LIMIT 1");
$registro=$db_con->fetch(PDO::FETCH_ASSOC);
*/
if ($registro['falladeluz'] == 1)
{
	echo '<div class="container-fluid">';
	echo '<div class="row">';
	echo '<div class="col-md-3">';
	echo '<div class="modal fade" id="mostrarmodal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">';
	echo '<div class="modal-dialog">';
	echo '<div class="modal-content">';
	echo '<div class="modal-header">';
	echo '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
	echo '<h3>Aviso!!!!</h3>';
	echo '</div>';
	echo '<div class="modal-body">';
	echo '<div class="col-md-4">';
	echo '<img src="imagenes/sefuelaluz.jpg" class="img-circle img-responsive" width="220" height="220" alt="Se fue la luz..." />';
	echo '</div>';
	echo '<h3>Saludos.... <br>Se presume que halla fallado la electricidad anoche por lo que el sistema estara un poco lento por la revision/sincronizacion de los discos duros</h3>';
	echo '</div>';
	echo '<div class="modal-footer">';
	echo '<a href="#" data-dismiss="modal" class="btn btn-warning">Cerrar</a>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	$fallo=1;
	/*
	echo '<div align="center">';
	echo '<table width="200" border="0" cellspacing="20" cellpadding="20">';
	echo '<tr>';
	echo '<td><img src="imagenes/sefuelaluz.jpg" width="320" height="320" alt="Se fue la luz..." /></td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td><h1>Saludos.... se presume que halla fallado la electricidad anoche por lo que el sistema estara un poco lento por la revision/sincronizacion de los discos duros</h1></td>';
	echo '</tr>';
	echo '</table>';
	echo '</div>';
	*/
}
if ($registro['respaldo'] == 1)
{
	echo '<div class="container-fluid">';
	echo '<div class="row">';
	echo '<div class="col-md-3">';
	echo '<div class="modal fade" id="mostrarmodal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">';
	echo '<div class="modal-dialog">';
	echo '<div class="modal-content">';
	echo '<div class="modal-header">';
	echo '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
	echo '<h3>Aviso!!!!</h3>';
	echo '</div>';
	echo '<div class="modal-body">';
	echo '<div class="col-md-4">';
	echo '<img src="imagenes/restringido.jpg" class="img-circle img-responsive" width="220" height="220" alt="Ejecutando respaldo..." />';
	echo '</div>';
	echo '<h3>Saludos.... <br>No se ha culminado el respaldo... intente en unos minutos</h3>';
	echo '</div>';
	echo '<div class="modal-footer">';
	echo '<a href="#" data-dismiss="modal" class="btn btn-warning">Cerrar</a>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '</div>';

	echo '<div class="container">';
    echo '<div class=\'alert alert-danger\'>';
	echo "<button class='close' data-dismiss='alert'>&times;</button>";
	echo "<strong>No se ha culminado el respaldo... intente en unos minutos</strong>";
    echo '</div>';
	echo '</div>';
	$fallo=1;
/*
	echo '<div align="center">';
	echo '<table width="200" border="0" cellspacing="20" cellpadding="20">';
	echo '<tr>';
	echo '<td><img src="imagenes/restringido.jpg" width="320" height="320" alt="Ejecutando respaldo..." /></td>';
	echo '</tr>';
	echo '<tr>';
	die('<td><h1>Saludos.... No se ha culminado el respaldo... intente en unos minutos</h1></td>');
	echo '</tr>';
	echo '</table>';
	echo '</div>';
*/
}

/*
$hoy="SELECT NOW() as fechasistema";
$fechasistema=mysql_query($hoy);
$hoy=mysql_fetch_assoc($fechasistema);
$completa = $hoy['fechasistema'];
*/
$hoy=$registro['fechasistema'];
$hoy=substr($hoy,0,10);
//echo 'hoy '.$completa;
$ddls= date('l', strtotime($hoy));

//si es lunes y si la fecha es menor a la del lunes no se ha hecho nomina
if ($ddls=="Monday") // si es lunes
	if (($registro['fechanominalunes'] < $hoy)) //  and ($registro['nominalunes'] == 0))
{
	echo '<div class="container-fluid">';
	echo '<div class="row">';
	echo '<div class="col-md-3">';
	echo '<div class="modal fade" id="mostrarmodal" tabindex="-1" role="dialog" aria-labelledby="basicModal" data-options="closeOnBackgroundClick:false" aria-hidden="true">';
	echo '<div class="modal-dialog">';
	echo '<div class="modal-content">';
	echo '<div class="modal-header">';
	echo '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
	echo '<h3>Aviso!!!!</h3>';
	echo '</div>';
	echo '<div class="modal-body">';
	echo '<div class="col-md-4">';
	echo '<img src="imagenes/restringido.jpg" class="img-circle img-responsive" width="220" height="220" alt="Nomina recibida del banco..." />';
	echo '</div>';
	echo '<h3>Saludos.... <br>No se ha procesado la nomina enviada por el banco... intente en unos minutos</h3>';
	echo '</div>';
	echo '<div class="modal-footer">';
	echo '<a href="#" data-dismiss="modal" class="btn btn-warning">Cerrar</a>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	$fallo=0;

	/*
	echo '<div align="center">';
	echo '<table width="200" border="0" cellspacing="20" cellpadding="20">';
	echo '<tr>';
	echo '<td><img src="imagenes/restringido.jpg" width="320" height="320" alt="Nomina recibida del banco..." /></td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td><h1>Saludos.... No se ha procesado la nomina enviada por el banco... intente en unos minutos</h1></td>';
	echo '</tr>';
	echo '</table>';
	echo '</div>';
	*/
}
//si es lunes y si la fecha es menor a la del lunes no se ha hecho nomina
if ($ddls=="Wednesday") // si es lunes
	if (($registro['fechanominamiercoles'] < $hoy)) //  and ($registro['nominalunes'] == 0))
{
/*
	echo '<div align="center">';
	echo '<table width="200" border="0" cellspacing="20" cellpadding="20">';
	echo '<tr>';
	echo '<td><img src="imagenes/restringido.jpg" width="320" height="320" alt="Nomina por enviar al banco..." /></td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td><h1>Saludos.... No se ha procesado la nomina que se debe enviar al banco... intente en unos minutos</h1></td>';
	echo '</tr>';
	echo '</table>';
	echo '</div>';
*/
	echo '<div class="container-fluid">';
	echo '<div class="row">';
	echo '<div class="col-md-3">';
	echo '<div class="modal fade" id="mostrarmodal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">';
	echo '<div class="modal-dialog">';
	echo '<div class="modal-content">';
	echo '<div class="modal-header">';
	echo '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
	echo '<h3>Aviso!!!!</h3>';
	echo '</div>';
	echo '<div class="modal-body">';
	echo '<div class="col-md-4">';
	echo '<img src="imagenes/restringido.jpg" class="img-circle img-responsive" width="220" height="220" alt="Nomina por enviar al banco...." />';
	echo '</div>';
	echo '<h3>Saludos.... <br>No se ha procesado la nomina que se debe enviar al banco... intente en unos minutos</h3>';
	echo '</div>';
	echo '<div class="modal-footer">';
	echo '<a href="#" data-dismiss="modal" class="btn btn-danger">Cerrar</a>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	$fallo=0;
}
if ((substr($hoy,6,2) == 1) or (substr($hoy,6,2) == 4) or (substr($hoy,6,2) == 7) or (substr($hoy,6,2) == 10) )
	if (($registro['fechareporte'] < $hoy)) //  and ($registro['nominalunes'] == 0))
{
/*
	echo '<div align="center">';
	echo '<table width="200" border="0" cellspacing="20" cellpadding="20">';
	echo '<tr>';
	echo '<td><img src="imagenes/restringido.jpg" width="320" height="320" alt="Nomina recibida del banco..." /></td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td><h1>Saludos.... No se han obtenido los saldos de prestamos... intente en unos minutos</h1></td>';
	echo '</tr>';
	echo '</table>';
	echo '</div>';
*/
	echo '<div class="container-fluid">';
	echo '<div class="row">';
	echo '<div class="col-md-3">';
	echo '<div class="modal fade" id="mostrarmodal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">';
	echo '<div class="modal-dialog">';
	echo '<div class="modal-content">';
	echo '<div class="modal-header">';
	echo '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
	echo '<h3>Aviso!!!!</h3>';
	echo '</div>';
	echo '<div class="modal-body">';
	echo '<div class="col-md-4">';
	echo '<img src="imagenes/restringido.jpg" class="img-circle img-responsive" width="220" height="220" alt="Saldo de Prestamos...." />';
	echo '</div>';
	echo '<h3>Saludos.... <br>No se han obtenido los saldos de prestamos... intente en unos minutos</h3>';
	echo '</div>';
	echo '<div class="modal-footer">';
	echo '<a href="#" data-dismiss="modal" class="btn btn-danger">Cerrar</a>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
}
return $fallo;
}
?>
