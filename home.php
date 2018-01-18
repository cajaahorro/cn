<?php
session_start();

if(!isset($_SESSION['user_session']))
{
	header("Location: index.php");
}

include_once 'dbconfig.php';

/*
$stmt = $db_con->prepare("SELECT * FROM sgcapass WHERE alias=:uid");
$stmt->execute(array(":uid"=>$_SESSION['user_session']));
$row=$stmt->fetch(PDO::FETCH_ASSOC);
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1"> 
<title>.:| CAja de Ahorro y Prestamo Obreros UCLA |:.</title>
<!-- <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen"> 
<link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" media="screen"> 
<script type="text/javascript" src="jquery-1.11.3-jquery.min.js"></script> -->
<script language="Javascript" src="javascript.js" type='text/javascript'></script> 
<!-- link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" media="screen"> -->
<?php
/*
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
  <!-- link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"-->

<!-- link rel="stylesheet" type="text/css" href="bootstrap/css/daterangepicker.css" /> -->
    <link href="bootstrap/css/bootstrap-datetimepicker.min.css" rel="stylesheet">  
<!-- script type="text/javascript" src="bootstrap/js/daterangepicker.js"></script> -->
   <!-- script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> -->
<script type="text/javascript" src="bootstrap/js/jquery3.js"></script> 
	<script type="text/javascript" src="bootstrap/js/moment.min.js"></script>
    <script src="bootstrap/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="bootstrap/js/daterangepicker.js"></script> 
    <script type="text/javascript" src="bootstrap/js/validation.min.js"></script>
<link rel="stylesheet" type="text/css" href="bootstrap/css/daterangepicker.css" /> 
    <!-- script src="bootstrap/js/bootstrap-datetimepicker.es.js"></script> -->
*/
    ?>
<link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" media="screen"> 
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="bootstrap/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<script type="text/javascript" src="bootstrap/js/jquery.min.js"></script> 
<script type="text/javascript" src="bootstrap/js/moment.min.js"></script>
<script src="bootstrap/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="bootstrap/js/validation.min.js"></script>
<script type="text/javascript" src="bootstrap/js/daterangepicker.js"></script> 
<link rel="stylesheet" type="text/css" href="bootstrap/css/daterangepicker.css" /> 


<!-- los enlaces para menu multinivel -->
<!-- SmartMenus jQuery Bootstrap Addon CSS -->
<link href="bootstrap/css/jquery.smartmenus.bootstrap.css" rel="stylesheet">
<!-- SmartMenus jQuery plugin -->
<script type="text/javascript" src="bootstrap/js/jquery.smartmenus.js"></script>
<!-- SmartMenus jQuery Bootstrap Addon -->
<script type="text/javascript" src="bootstrap/js/jquery.smartmenus.bootstrap.js"></script>
<!-- fin de los enlaces para menu multinivel -->
<script src="bootstrap/js/bootstrap.min.js"></script>


<!-- link href="bootstrap/ccs/style.css" rel="stylesheet" media="screen"> -->
<script type="text/javascript" src="ConsultarCtasAsoc.js"></script>

<!--fechas --
<link href="bootstrap/css/bootstrap-datetimepicker.css" rel="stylesheet">
<script src="bootstrap/js/moment-with-locales.js"></script>
<script src="bootstrap/js/bootstrap-datetimepicker.js"></script>
 
-->
<script>
   $(document).ready(function()
   {
      $("#mostrarmodal").modal("show");
   });
</script>
</head>

<body>

<!--
<nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="http://www.codingcage.com">Coding Cage</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="http://www.codingcage.com/2015/11/ajax-login-script-with-jquery-php-mysql.html">Back to Article</a></li>
            <li><a href="http://www.codingcage.com/search/label/jQuery">jQuery</a></li>
            <li><a href="http://www.codingcage.com/search/label/PHP">PHP</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
			  <span class="glyphicon glyphicon-user"></span>&nbsp;Hi' <?php echo $_SESSION['user_session']; ?>&nbsp;<span class="caret"></span></a>
              <ul class="dropdown-menu">
                <!-- <li><a href="#"><span class="glyphicon glyphicon-user"></span>&nbsp;View Profile</a></li> --
                <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span>&nbsp;Salir</a></li>
              </ul>
            </li>
          </ul>
        </div><!--/.nav-collapse --
      </div>
    </nav>
	-->
	
<?php
//if (isset($_SESSION['empresa']))
{
	date_default_timezone_set('America/Caracas'); 
	include("funciones.php");
	include("revisarfallas2.php");
	$sql="SELECT *, NOW() as fechasistema FROM ".$_SESSION['institucion']."sgcaf8co LIMIT 1";
	try
	{
		$stmt=$db_con->prepare($sql);
		$stmt->execute();
		$afila=$stmt->fetch(PDO::FETCH_ASSOC);
		if ($afila)
		{
			$hoy=$afila['fechasistema'];
			// echo 'hoy'.$hoy;
			$hoy=substr($hoy,0,10);
			// echo 'dia '.ddls(). $registro['fechanominalunes'] .'---'.$hoy;
			if (algo_fallo($afila) == 0)
				if ((ddls($hoy) == "Monday") and ($afila['fechanominalunes'] < $hoy))
					menu_lunes();
				else
				//	if (ddls() == "Tuesday") //  "Wednesday")
					if ((ddls($hoy) == "Wednesday") and ($afila['fechanominamiercoles'] < $hoy))
						menu_miercoles();
					else menu_normal();
				// echo 'registro '.$registro['fechanominalunes']. ' hoy '.$hoy. ddls();
			}
		}
	catch(PDOException $e){
		echo $e->getMessage();
		// echo 'Algo ha fallado!';
	}
}
?>

<div class="body-container">
  <div class="container">
    <div class='alert alert-success'>
		  <button class='close' data-dismiss='alert'>&times;</button>
			 <strong>Bienvenido <?php echo $_SESSION['user_session']; ?></strong>.
    </div>
  </div>
<div class="container">

    </div>
</div>

</div>
</div>


</div>

</div>
<!--
</body>
</html>
-->
<?php

function buscarpermiso($valor,$permisomenu) {
	for ($i=0; $i<count($permisomenu);$i++) {
		if ($permisomenu[$i] == $valor) {
			return 1;}
	}
return 0;
}

function menu_normal()
{
/*
	echo '<ul>';
echo '<li><a href="?accion=1">Bienvenid@</a></li>';
//			if ((buscarpermiso(1100,$permisomenu)!=0)) {
echo '<li><a href="">Asociados</a>';
echo '<ul>';
echo '<li><a href="">Actualizar</a>';
echo '<ul>';
echo '<li><a href="regsocios.php">Socios</a></li>';
echo '<li><a href="regbenef.php">Beneficiarios</a></li>';
echo '<li><a href="retiros.php">Retiros</a></li>';
echo '<li><a href="aportes.php">Aportes </a></li>';
echo '</ul>';
echo '</li>';
echo '<li><a href="">Reportes</a>';
echo '<ul>';
echo '<li><a href="edocta.php">Estado de Cuenta</a></li>';
echo '<li><a href="hishab.php">Histórico Haberes</a></li>';
echo '<li><a href="">Listado de Socios</a>';
echo '<ul>';
echo '<li><a href="lissoc.php">Activos / Jubilados </a></li>';
echo '<li><a href="habsoc.php">Haberes</a></li>';
echo '<li><a href="lisgen.php">General</a></li>';
echo '<li><a href="lising.php">Ingreso</a></li>';
echo '</ul>';
echo '</li>';
echo '<li><a href="">Listado de Retirados</a>';
echo '<ul>';
echo '<li><a href="lisret.php">Socios</a></li>';
echo '<li><a href="lismr.php">Montos Retirados</a></li>';
echo '<li><a href="lisdr.php">Depositos</a></li>';
echo '</ul>';
echo '</li>';
echo '<li><a href="">Otros</a>';
echo '<ul>';
echo '<li><a href="lisfia.php">Fiadores</a></li>';
echo '<li><a href="lisvot.php">Votaciones</a></li>';
echo '<li><a href="pagosproyeccion.php">Proyeccion de Pagos</a></li>';
echo '<li><a href="carnet.php">Carnet</a></li>';
echo '</ul>';
echo '</li>';
echo '</ul>';
echo '</li>';
echo '</ul>';
echo '<li><a href="">Préstamos</a>';
echo '<ul>';
echo '<li><a href="">Actualizar</a>';
echo '<ul>';
echo '<li><a href="solpre.php">Solicitudes</a></li>';
echo '<li><a href="aboxnom2.php">Abonos x Nomina</a></li>';
echo '<li><a href="recing.php">Recibos de Ingreso</a></li>';
echo '<li><a href="tippre.php">Tipos de Prestamos</a></li>';
echo '<li><a href="">Prestamos Especiales</a>';
echo '<ul>';
echo '<li><a href="zapatos.php">Zapateria</a></li>';
echo '<li><a href="zapatosm.php">Zapateria (Al Mayor)</a></li>';
echo '<li><a href="celulares.php">Celulares</a></li>';
echo '<li><a href="motos.php">Motos</a></li>';
echo '<li><a href="">Viajes</a>';
echo '<ul>';
echo '<li><a href="viajes.php">Prestamo</a></li>';
echo '<li><a href="lviajes.php">Listado</a></li>';
echo '</ul>';
echo '</ul>';
echo '</li>';
echo '<li><a href="">Cargar Nominas Especiales</a>';
echo '<ul>';
echo '<li><a href="Funeraria.php">Funeraria (046)</a></li>';
echo '<li><a href="Farmacia.php">Farmacia (004)</a></li>';
echo '<li><a href="ayudasoli.php">Ayuda Solidaria (024)</a></li>';
echo '<li><a href="medical.php">Medical Assist Semanal (071)</a></li>';
echo '<li><a href="especial_farmacia.php">Dcto.Especial Farmacia (067)</a></li>';
echo '<li><a href="emi.php">EMI (005)</a></li>';
echo '<li><a href="mundolent.php">Lentes Anual (072)</a></li>';
echo '</ul>';
echo '</li>';
echo '</ul>';
echo '</li>';
echo '<li><a href="">Reportes</a>';
echo '<ul>';
echo '<li><a href="cuoban.php">Cuota a Banco</a></li>';
echo '<li><a href="depositobanco2.php">Deposito a Banco</a></li>';
echo '<li><a href="salpre.php">Saldos de Prestamos</a></li>';
echo '<li><a href="">Prestamos Otorgados</a></li>';
echo '<li><a href="cuocero.php">Cuotas en Cero</a></li>';
echo '<li><a href="monmut.php">Monte Pio/Mutuo Auxilio</a></li>';
echo '<li><a href="vernompre.php">Ver Nominas de Prestamos</a></li>';
echo '</ul>';
echo '</li>';
echo '<li><a href="devoluciones.php">Devoluciones</a></li>';
echo '<li><a href="regacta.php">Registrar Acta</a></li>';
echo '</ul>';
echo '<li><a href="">Contabilidad</a>';
echo '<ul>';
echo '<li><a href="">Asientos</a>';
echo '<ul>';
echo '<li><a href="altaasim.php">Simples</a></li>';
echo '<li><a href="altaasigral.php">Generales</a></li>';
echo '<li><a href="editasi2.php">Buscar/Editar</a></li>';
echo '</ul>';
echo '</li>';
echo '<li><a href="">Cuentas</a>';
echo '<ul>';
echo '<li><a href="cuentas.php">Alta</a></li>';
echo '<li><a href="reiniciar.php">Reiniciar</a></li>';
echo '<li><a href="precie.php">Pre-Cierre</a></li>';
echo '<li><a href="cam_fech.php">Cambio de Fecha</a></li>';
echo '</ul>';
echo '</li>';
echo '<li><a href="">Reportes</a>';
echo '<ul>';
echo '<li><a href="cueaso.php">Cuentas Asociadas</a></li>';
echo '<li><a href="">Balances</a>';
echo '<ul>';
echo '<li><a href="balcom.php">Comprobacion</a></li>';
echo '<li><a href="balgen.php">General</a></li>';
echo '<li><a href="sudeca-forma-a.php">SUDECA FORMA-A</a></li>';
echo '<li><a href="estres.php">Estado de Resultados</a></li>';
echo '<li><a href="resdia.php">Resumen de Diario</a></li>';
echo '</ul>';
echo '</li>';
echo '<li><a href="">Otros</a>';
echo '<ul>';
echo '<li><a href="diario.php">Diario</a></li>';
echo '<li><a href="asidescu.php">Comprobantes Diferidos</a></li>';
echo '<li><a href="">Otros</a>';
echo '<ul>';
echo '<li><a href="extractoctas3.php">Mayor Analitico (Año Actual)</a></li>';
echo '<li><a href="extractoctas_hist.php">Mayor Analitico (Años Anteriores)</a></li>';
echo '</ul>';
echo '</li>';
echo '</ul>';
echo '</li>';
echo '</ul>';
echo '</li>';
echo '</ul>';
echo '<li><a href="">Cheques</a>';
echo '<ul>';
echo '<li><a href="">Actualizar</a>';
echo '<ul>';
echo '<li><a href="cheact.php">Cheques</a>';
echo '<li><a href="chequeras.php">Chequeras</a></li>';
echo '<li><a href="bancos.php">Bancos</a></li>';
echo '<li><a href="conceptos.php">Conceptos</a></li>';
echo '<li><a href="che_verif.php">Verificación de Cheques</a></li>';
echo '</ul>';
echo '</li>';
echo '<li><a href="">Reportes</a>';
echo '<ul>';
echo '<li><a href="cheimpr.php">Impresion</a></li>';
echo '<li><a href="che_rel.php">Relacion</a></li>';
echo '<li><a href="che_compr.php">Generar Comprobantes</a></li>';
echo '<li><a href="conciliacion.php">Conciliacion</a></li>';
echo '</ul>';
echo '</li>';
echo '</ul>';
echo '<li><a href="">Activos Fijos</a>';
echo '<ul>';
echo '<li><a href="">Actualizar</a>';
echo '<ul>';
echo '<li><a href="lisact.php">Incorporación</a></li>';
echo '<li><a href="desact.php">Desincorporar</a></li>';
echo '<li><a href="depact.php">Depreciar</a></li>';
echo '</ul>';
echo '</li>';
echo '<li><a href="">Reportes</a>';
echo '<ul>';
echo '<li><a target=\"_blank\" href="lisactpdf.php">Activos Fijos</a></li>';
echo '<li><a target=\"_blank\" href="desactpdf.php">Desincorporados</a></li>';
echo '<li><a target=\"_blank\" href="listotpdf.php">Totalmente Depreciados</a></li>';
echo '</ul>';
echo '</li>';
echo '<li><a href="departamentos.php">Departamentos</a></li>';
echo '</ul>';
echo '</li>';
echo '</ul>';
*/
?>



<!-- Navbar -->
<div class="navbar navbar-default" role="navigation">
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="#">Project name</a>
  </div>
  <div class="navbar-collapse collapse">

    <!-- Left nav -->
    <ul class="nav navbar-nav"> <!-- navbar-right"> -->
	  <!-- menu socios -->
		<li><a href="#">Socios<span class="caret"></span></a>
			<ul class="dropdown-menu">
				<li><a href="#">Actualizar<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="regsocios.php">Socios</a></li>
						<li><a href="regbenef.php">Beneficiarios</a></li>
						<li><a href="retiros.php">Retiros</a></li>
						<li><a href="aportes.php">Aportes</a></li>
					</ul>
				</li>
				<li class="divider"></li>
				<li><a href="#">Reportes<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="edocta.php">Estado de Cuenta</a></li>
						<li><a href="hishab.php">Hist&oacute;rico Haberes</a></li>
						<li><a href="#">Listado de Socios<span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="lissoc.php">Activos / Jubilados </a></li>
								<li><a href="habsoc.php">Haberes</a></li>
								<li><a href="lisgen.php">General</a></li>
								<li><a href="lising.php">Ingreso</a></li>
							</ul>
						</li>
						<li><a href="#">Listado de Retirados<span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="lisret.php">Socios</a></li>
								<li><a href="lismr.php">Montos Retirados</a></li>
								<li><a href="lisdr.php">Depositos</a></li>
							</ul>
						</li>
						<li><a href="#">Otros<span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="lisfia.php">Fiadores</a></li>
								<li><a href="lisvot.php">Votaciones</a></li>
								<li><a href="pagosproyeccion.php">Proyeccion de Pagos</a></li>
								<li><a href="carnet.php">Carnet</a></li>
							</ul>
						</li>
					</ul>
				</li>
			</ul>
	  </li>
	  <!-- fin menu socios -->
	  <!-- prestamos -->
		<li><a href="#">Pr&eacute;stamos<span class="caret"></span></a>
			<ul class="dropdown-menu">
				<li><a href="#">Actualizar<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="solpre.php">Solicitudes</a></li>
						<li><a href="aboxnom2.php">Abonos x Nomina</a></li>
						<li><a href="recing.php">Recibos de Ingreso</a></li>
						<li><a href="tippre.php">Tipos de Prestamo</a></li>
						<li><a href="#">Pr&eacute;stamos Especiales<span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li class="disabled"><a class="disabled" href="zapatos.php">Zapateria</a></li>
								<li class="disabled"><a class="disabled" href="zapatosm.php">Zapateria (al Mayor)</a></li>
								<li class="disabled"><a class="disabled" href="motos.php">Motos</a></li>

								<li><a href="#">Viajes<span class="caret"></span></a>
									<ul class="dropdown-menu">
										<li><a href="viajes.php">Pr&eacute;stamo</a></li>
										<li><a href="lviajes.php">Listado</a></li>
									</ul>
								</li>
							</ul>
						</li>
						<li><a href="#">Cargar Nominas Especiales<span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="Funeraria.php">Funeraria (046)</a></li>
								<li><a href="Farmacia.php">Farmacia (004)</a></li>
								<li><a href="ayudasoli.php">Ayuda Solidaria (024)</a></li>
								<li><a href="medical.php">Medical Assist Semanal (071)</a></li>
								<li><a href="especial_farmacia.php">Dcto.Especial Farmacia (067)</a></li>
								<li><a href="emi.php">EMI (005)</a></li>
								<li><a href="mundolent.php">Lentes Anual (072)</a></li>
							</ul>
						</li>
					</ul>
				</li>
				<li class="divider"></li>
				<li><a href="#">Reportes<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="cuoban.php">Cuota a Banco</a></li>
						<li><a href="depositobanco2.php">Deposito a Banco</a></li>
						<li><a href="salpre.php">Saldos de Prestamos</a></li>
						<li class="disabled"><a class="disabled" href=".php">Prestamos Otorgados</a></li>
						<li><a href="cuocero.php">Cuotas en Cero</a></li>
						<li><a href="monmut.php">Monte Pio/Mutuo Auxilio</a></li>
						<li><a href="vernompre.php">Ver Nominas de Prestamos</a></li>
					</ul>
				<li class="divider"></li>
				<li><a href="devoluciones.php">Devoluciones</a></li>
				<li><a href="regacta.php">Registrar Acta</a></li>
				</li>
			</ul>
	  </li>
	  <!-- fin menu prestamos -->

	  <!-- contabilidad -->
		<li><a href="#">Contabilidad<span class="caret"></span></a>
			<ul class="dropdown-menu">
				<li><a href="#">Asientos<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="altaasim.php">Simples</a></li>
						<li><a href="altaasigral.php">Generales</a></li>
						<li><a href="editasi2.php">Buscar/Editar</a></li>
					</ul>
				</li>
				<li><a href="#">Cuentas<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="cuentas.php">Alta</a></li>
						<li><a href="reiniciar.php">Reiniciar</a></li>
						<li><a href="cam_fech.php">Cambio de Fecha</a></li>
						<li><a href="precie.php">Pre-Cierre</a></li>
					</ul>
				</li>
				<li class="divider"></li>
				<li><a href="#">Reportes<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="cueaso.php">Cuentas Asociadas</a></li>
						<li><a href="#">Balances<span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="balcom.php">Comprobaci&oacute;n</a></li>
								<li><a href="balgen.php">General</a></li>
								<li><a href="sudeca-forma-a.php">SUDECA FORMA-A</a></li>
								<li><a href="estres.php">Estado de Resultados</a></li>
								<li><a href="resdia.php">Resumen de Diario</a></li>
							</ul>
						</li>
						<li><a href="#">Otros<span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="diario.php">Diario</a></li>
								<li><a href="asidescu.php">Comprobantes Diferidos</a></li>
								<li><a href="#">Mayor Anal&iacute;tico<span class="caret"></span></a>
									<ul class="dropdown-menu">
										<li><a href="extractoctas3.php">A&nacute;o Actual</a></li>
										<li><a href="extractoctas_hist.php">A&nacute;os Anteriores</a></li>
									</ul>
								</li>
							</ul>
						</li>
					</ul>
				</li>
			</ul>
	  </li>
	  <!-- fin menu contabilidad -->

	  <!-- menu cheques -->
		<li><a href="#">Cheques<span class="caret"></span></a>
			<ul class="dropdown-menu">
				<li><a href="#">Actualizar<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="cheact.php">Cheques</a></li>
						<li><a href="chequeras.php">Chequeras</a></li>
						<li><a href="bancos.php">Bancos</a></li>
						<li><a href="conceptos.php">Conceptos</a></li>
						<li><a href="che_verif.php">Verificaci&oacute;n</a></li>
					</ul>
				</li>
				<li class="divider"></li>
				<li><a href="#">Reportes<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="cheimpr.php">Impresi&oacute;n</a></li>
						<li><a href="che_rel.php">Relaci&oacute;n</a></li>
						<li><a href="che_compr.php">Generar comprobantes</a></li>
						<li><a href="conciliacion.php">Conciliaci&oacute;n</a></li>
					</ul>
				</li>
			</ul>
	  </li>
	  <!-- fin menu cheques -->
	  
	  <!-- menu activos fijos  -->
		<li><a href="#">Activos Fijos<span class="caret"></span></a>
			<ul class="dropdown-menu">
				<li><a href="#">Actualizar<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="lisact.php">Incorporaci&oacute;n</a></li>
						<li><a href="desact.php">Desincorporar</a></li>
						<li><a href="depact.php">Depreciaci&oacute;n</a></li>
						<li><a href="departamentos.php">Departamentos</a></li>
					</ul>
				</li>
				<li class="divider"></li>
				<li><a href="#">Reportes<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a target=\"_blank\" href="lisactpdf.php">Activos Fijos</a></li>
						<li><a href="desactpdf.php">Desincorporados</a></li>
						<li><a href="listotpdf.php">Totalmente Depreciados</a></li>
					</ul>
				</li>
			</ul>
	  </li>
	  <!-- fin menu cheques -->
          <!-- <ul class="nav navbar-nav navbar-right"> -->
            
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
			  <span class="glyphicon glyphicon-user"></span>&nbsp;Hola <?php echo $_SESSION['user_session']; ?>&nbsp;<span class="caret"></span></a>
              <ul class="dropdown-menu">
                <!-- <li><a href="#"><span class="glyphicon glyphicon-user"></span>&nbsp;View Profile</a></li> -->
                <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span>&nbsp;Salir</a></li>
              </ul>
            </li>
          
</div>
	
<?php
}

function menu_miercoles()
{
/*
echo '<ul>';
echo '<li><a href="?accion=1">Bienvenid@</a></li>';
echo '<li><a href="">Préstamos</a>';
echo '<ul>';
echo '<li><a href="">Reportes</a>';
echo '<ul>';
echo '<li><a href="cuoban.php">Cuota a Banco</a></li>';
echo '</ul>';
echo '</li>';
*/
?>
<!-- Navbar -->
<div class="navbar navbar-default" role="navigation">
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="#">Project name</a>
  </div>
  <div class="navbar-collapse collapse">

    <!-- Left nav -->
    <ul class="nav navbar-nav"> <!-- navbar-right"> -->
	  <!-- menu socios --
		<li><a href="#">Socios<span class="caret"></span></a>
			<ul class="dropdown-menu">
				<li><a href="#">Actualizar<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="regsocios.php">Socios</a></li>
						<li><a href="regbenef.php">Beneficiarios</a></li>
						<li><a href="retiros.php">Retiros</a></li>
						<li><a href="aportes.php">Aportes</a></li>
					</ul>
				</li>
				<li class="divider"></li>
				<li><a href="#">Reportes<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="edocta.php">Estado de Cuenta</a></li>
						<li><a href="hishab.php">Hist&oacute;rico Haberes</a></li>
						<li><a href="#">Listado de Socios<span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="lissoc.php">Activos / Jubilados </a></li>
								<li><a href="habsoc.php">Haberes</a></li>
								<li><a href="lisgen.php">General</a></li>
								<li><a href="lising.php">Ingreso</a></li>
							</ul>
						</li>
						<li><a href="#">Listado de Retirados<span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="lisret.php">Socios</a></li>
								<li><a href="lismr.php">Montos Retirados</a></li>
								<li><a href="lisdr.php">Depositos</a></li>
							</ul>
						</li>
						<li><a href="#">Otros<span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="lisfia.php">Fiadores</a></li>
								<li><a href="lisvot.php">Votaciones</a></li>
								<li><a href="pagosproyeccion.php">Proyeccion de Pagos</a></li>
								<li><a href="carnet.php">Carnet</a></li>
							</ul>
						</li>
					</ul>
				</li>
			</ul>
	  </li>
	  <!-- fin menu socios -->
	  <!-- prestamos -->
		<li><a href="#">Pr&eacute;stamos<span class="caret"></span></a>
			<ul class="dropdown-menu">
				<li><a href="#">Actualizar<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li class="disabled"><a class="disabled" href="solpre.php">Solicitudes</a></li>
						<!--
						<li><a href="aboxnom2.php">Abonos x Nomina</a></li>
						<li><a href="recing.php">Recibos de Ingreso</a></li>
						<li><a href="tippre.php">Tipos de Prestamo</a></li>
						<li><a href="#">Pr&eacute;stamos Especiales<span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li class="disabled"><a class="disabled" href="zapatos.php">Zapateria</a></li>
								<li class="disabled"><a class="disabled" href="zapatosm.php">Zapateria (al Mayor)</a></li>
								<li class="disabled"><a class="disabled" href="motos.php">Motos</a></li>

								<li><a href="#">Viajes<span class="caret"></span></a>
									<ul class="dropdown-menu">
										<li><a href="viajes.php">Pr&eacute;stamo</a></li>
										<li><a href="lviajes.php">Listado</a></li>
									</ul>
								</li>
							</ul>
						</li>
						<li><a href="#">Cargar Nominas Especiales<span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="Funeraria.php">Funeraria (046)</a></li>
								<li><a href="Farmacia.php">Farmacia (004)</a></li>
								<li><a href="ayudasoli.php">Ayuda Solidaria (024)</a></li>
								<li><a href="medical.php">Medical Assist Semanal (071)</a></li>
								<li><a href="especial_farmacia.php">Dcto.Especial Farmacia (067)</a></li>
								<li><a href="emi.php">EMI (005)</a></li>
								<li><a href="mundolent.php">Lentes Anual (072)</a></li>
							</ul>
						</li>
					</ul>
				</li>
						-->
				<li class="divider"></li>
				<li><a href="#">Reportes<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="cuoban.php">Cuota a Banco</a></li>
						<!--
						<li><a href="depositobanco2.php">Deposito a Banco</a></li>
						<li><a href="salpre.php">Saldos de Prestamos</a></li>
						<li class="disabled"><a class="disabled" href=".php">Prestamos Otorgados</a></li>
						<li><a href="cuocero.php">Cuotas en Cero</a></li>
						<li><a href="monmut.php">Monte Pio/Mutuo Auxilio</a></li>
						<li><a href="vernompre.php">Ver Nominas de Prestamos</a></li>
				-->
					</ul>
				<li class="divider"></li>
				<li class="disabled"><a href="devoluciones.php">Devoluciones</a></li>
				<li class="disabled"><a href="regacta.php">Registrar Acta</a></li>
				</li>
			</ul>
	  </li>
	  <!-- fin menu prestamos -->

	  <!-- contabilidad --
		<li><a href="#">Contabilidad<span class="caret"></span></a>
			<ul class="dropdown-menu">
				<li><a href="#">Asientos<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="altaasim.php">Simples</a></li>
						<li><a href="altaasigral.php">Generales</a></li>
						<li><a href="editasi2.php">Buscar/Editar</a></li>
					</ul>
				</li>
				<li><a href="#">Cuentas<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="cuentas.php">Alta</a></li>
						<li><a href="reiniciar.php">Reiniciar</a></li>
						<li><a href="cam_fech.php">Cambio de Fecha</a></li>
						<li><a href="precie.php">Pre-Cierre</a></li>
					</ul>
				</li>
				<li class="divider"></li>
				<li><a href="#">Reportes<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="cueaso.php">Cuentas Asociadas</a></li>
						<li><a href="#">Balances<span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="balcom.php">Comprobaci&oacute;n</a></li>
								<li><a href="balgen.php">General</a></li>
								<li><a href="sudeca-forma-a.php">SUDECA FORMA-A</a></li>
								<li><a href="estres.php">Estado de Resultados</a></li>
								<li><a href="resdia.php">Resumen de Diario</a></li>
							</ul>
						</li>
						<li><a href="#">Otros<span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="diario.php">Diario</a></li>
								<li><a href="asidescu.php">Comprobantes Diferidos</a></li>
								<li><a href="#">Mayor Anal&iacute;tico<span class="caret"></span></a>
									<ul class="dropdown-menu">
										<li><a href="extractoctas3.php">A&nacute;o Actual</a></li>
										<li><a href="extractoctas_hist.php">A&nacute;os Anteriores</a></li>
									</ul>
								</li>
							</ul>
						</li>
					</cul>
				</li>
			</ul>
	  </li>
	  <!-- fin menu contabilidad -->

	  <!-- menu cheques --
		<li><a href="#">Cheques<span class="caret"></span></a>
			<ul class="dropdown-menu">
				<li><a href="#">Actualizar<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="cheact.php">Cheques</a></li>
						<li><a href="chequeras.php">Chequeras</a></li>
						<li><a href="bancos.php">Bancos</a></li>
						<li><a href="conceptos.php">Conceptos</a></li>
						<li><a href="che_verif.php">Verificaci&oacute;n</a></li>
					</ul>
				</li>
				<li class="divider"></li>
				<li><a href="#">Reportes<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="cheimpr.php">Impresi&oacute;n</a></li>
						<li><a href="che_rel.php">Relaci&oacute;n</a></li>
						<li><a href="che_compr.php">Generar comprobantes</a></li>
						<li><a href="conciliacion.php">Conciliaci&oacute;n</a></li>
					</ul>
				</li>
			</ul>
	  </li>
	  <!-- fin menu cheques -->
	  
	  <!-- menu activos fijos  --
		<li><a href="#">Activos Fijos<span class="caret"></span></a>
			<ul class="dropdown-menu">
				<li><a href="#">Actualizar<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="lisact.php">Incorporaci&oacute;n</a></li>
						<li><a href="desact.php">Desincorporar</a></li>
						<li><a href="depact.php">Depreciaci&oacute;n</a></li>
						<li><a href="departamentos.php">Departamentos</a></li>
					</ul>
				</li>
				<li class="divider"></li>
				<li><a href="#">Reportes<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a target=\"_blank\" href="lisactpdf.php">Activos Fijos</a></li>
						<li><a href="desactpdf.php">Desincorporados</a></li>
						<li><a href="listotpdf.php">Totalmente Depreciados</a></li>
					</ul>
				</li>
			</ul>
	  </li>
	  <!-- fin menu cheques -->
          <!-- <ul class="nav navbar-nav navbar-right"> -->
            
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
			  <span class="glyphicon glyphicon-user"></span>&nbsp;Hola <?php echo $_SESSION['user_session']; ?>&nbsp;<span class="caret"></span></a>
              <ul class="dropdown-menu">
                <!-- <li><a href="#"><span class="glyphicon glyphicon-user"></span>&nbsp;View Profile</a></li> -->
                <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span>&nbsp;Salir</a></li>
              </ul>
            </li>
          
</div>

<?php

}

function menu_lunes()
{
/*
echo '<ul>';
echo '<li><a href="?accion=1">Bienvenid@</a></li>';
echo '<li><a href="">Préstamos</a>';
echo '<ul>';
echo '<li><a href="">Actualizar</a>';
echo '<ul>';
echo '<li><a href="aboxnom2.php">Abonos x Nomina</a></li>';
echo '<ul>';
echo '</li>';

*/
?>
<!-- Navbar -->
<div class="navbar navbar-default" role="navigation">
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="#">Project name</a>
  </div>
  <div class="navbar-collapse collapse">

    <!-- Left nav -->
    <ul class="nav navbar-nav"> <!-- navbar-right"> -->
	  <!-- menu socios --
		<li><a href="#">Socios<span class="caret"></span></a>
			<ul class="dropdown-menu">
				<li><a href="#">Actualizar<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="regsocios.php">Socios</a></li>
						<li><a href="regbenef.php">Beneficiarios</a></li>
						<li><a href="retiros.php">Retiros</a></li>
						<li><a href="aportes.php">Aportes</a></li>
					</ul>
				</li>
				<li class="divider"></li>
				<li><a href="#">Reportes<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="edocta.php">Estado de Cuenta</a></li>
						<li><a href="hishab.php">Hist&oacute;rico Haberes</a></li>
						<li><a href="#">Listado de Socios<span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="lissoc.php">Activos / Jubilados </a></li>
								<li><a href="habsoc.php">Haberes</a></li>
								<li><a href="lisgen.php">General</a></li>
								<li><a href="lising.php">Ingreso</a></li>
							</ul>
						</li>
						<li><a href="#">Listado de Retirados<span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="lisret.php">Socios</a></li>
								<li><a href="lismr.php">Montos Retirados</a></li>
								<li><a href="lisdr.php">Depositos</a></li>
							</ul>
						</li>
						<li><a href="#">Otros<span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="lisfia.php">Fiadores</a></li>
								<li><a href="lisvot.php">Votaciones</a></li>
								<li><a href="pagosproyeccion.php">Proyeccion de Pagos</a></li>
								<li><a href="carnet.php">Carnet</a></li>
							</ul>
						</li>
					</ul>
				</li>
			</ul>
	  </li>
	  <!-- fin menu socios -->
	  <!-- prestamos -->
		<li><a href="#">Pr&eacute;stamos<span class="caret"></span></a>
			<ul class="dropdown-menu">
				<li><a href="#">Actualizar<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li class="disabled"><a class="disabled" href="solpre.php">Solicitudes</a></li>
						<li><a href="aboxnom2.php">Abonos x Nomina</a></li>
						<!--
						<li><a href="recing.php">Recibos de Ingreso</a></li>
						<li><a href="tippre.php">Tipos de Prestamo</a></li>
						<li><a href="#">Pr&eacute;stamos Especiales<span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li class="disabled"><a class="disabled" href="zapatos.php">Zapateria</a></li>
								<li class="disabled"><a class="disabled" href="zapatosm.php">Zapateria (al Mayor)</a></li>
								<li class="disabled"><a class="disabled" href="motos.php">Motos</a></li>

								<li><a href="#">Viajes<span class="caret"></span></a>
									<ul class="dropdown-menu">
										<li><a href="viajes.php">Pr&eacute;stamo</a></li>
										<li><a href="lviajes.php">Listado</a></li>
									</ul>
								</li>
							</ul>
						</li>
						<li><a href="#">Cargar Nominas Especiales<span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="Funeraria.php">Funeraria (046)</a></li>
								<li><a href="Farmacia.php">Farmacia (004)</a></li>
								<li><a href="ayudasoli.php">Ayuda Solidaria (024)</a></li>
								<li><a href="medical.php">Medical Assist Semanal (071)</a></li>
								<li><a href="especial_farmacia.php">Dcto.Especial Farmacia (067)</a></li>
								<li><a href="emi.php">EMI (005)</a></li>
								<li><a href="mundolent.php">Lentes Anual (072)</a></li>
							</ul>
						</li>
						-->
					</ul>
				</li>
				<!--
				<li class="divider"></li>
				<li><a href="#">Reportes<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="cuoban.php">Cuota a Banco</a></li>
						<li><a href="depositobanco2.php">Deposito a Banco</a></li>
						<li><a href="salpre.php">Saldos de Prestamos</a></li>
						<li class="disabled"><a class="disabled" href=".php">Prestamos Otorgados</a></li>
						<li><a href="cuocero.php">Cuotas en Cero</a></li>
						<li><a href="monmut.php">Monte Pio/Mutuo Auxilio</a></li>
						<li><a href="vernompre.php">Ver Nominas de Prestamos</a></li>
					</ul>
				<li class="divider"></li>
				<li><a href="devoluciones.php">Devoluciones</a></li>
				<li><a href="regacta.php">Registrar Acta</a></li>
				</li>
				-->
			</ul>
	  </li>
	  <!-- fin menu prestamos -->

	  <!-- contabilidad --
		<li><a href="#">Contabilidad<span class="caret"></span></a>
			<ul class="dropdown-menu">
				<li><a href="#">Asientos<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="altaasim.php">Simples</a></li>
						<li><a href="altaasigral.php">Generales</a></li>
						<li><a href="editasi2.php">Buscar/Editar</a></li>
					</ul>
				</li>
				<li><a href="#">Cuentas<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="cuentas.php">Alta</a></li>
						<li><a href="reiniciar.php">Reiniciar</a></li>
						<li><a href="cam_fech.php">Cambio de Fecha</a></li>
						<li><a href="precie.php">Pre-Cierre</a></li>
					</ul>
				</li>
				<li class="divider"></li>
				<li><a href="#">Reportes<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="cueaso.php">Cuentas Asociadas</a></li>
						<li><a href="#">Balances<span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="balcom.php">Comprobaci&oacute;n</a></li>
								<li><a href="balgen.php">General</a></li>
								<li><a href="sudeca-forma-a.php">SUDECA FORMA-A</a></li>
								<li><a href="estres.php">Estado de Resultados</a></li>
								<li><a href="resdia.php">Resumen de Diario</a></li>
							</ul>
						</li>
						<li><a href="#">Otros<span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="diario.php">Diario</a></li>
								<li><a href="asidescu.php">Comprobantes Diferidos</a></li>
								<li><a href="#">Mayor Anal&iacute;tico<span class="caret"></span></a>
									<ul class="dropdown-menu">
										<li><a href="extractoctas3.php">A&nacute;o Actual</a></li>
										<li><a href="extractoctas_hist.php">A&nacute;os Anteriores</a></li>
									</ul>
								</li>
							</ul>
						</li>
					</ul>
				</li>
			</ul>
	  </li>
	  <!-- fin menu contabilidad -->

	  <!-- menu cheques --
		<li><a href="#">Cheques<span class="caret"></span></a>
			<ul class="dropdown-menu">
				<li><a href="#">Actualizar<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="cheact.php">Cheques</a></li>
						<li><a href="chequeras.php">Chequeras</a></li>
						<li><a href="bancos.php">Bancos</a></li>
						<li><a href="conceptos.php">Conceptos</a></li>
						<li><a href="che_verif.php">Verificaci&oacute;n</a></li>
					</ul>
				</li>
				<li class="divider"></li>
				<li><a href="#">Reportes<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="cheimpr.php">Impresi&oacute;n</a></li>
						<li><a href="che_rel.php">Relaci&oacute;n</a></li>
						<li><a href="che_compr.php">Generar comprobantes</a></li>
						<li><a href="conciliacion.php">Conciliaci&oacute;n</a></li>
					</ul>
				</li>
			</ul>
	  </li>
	  <!-- fin menu cheques -->
	  
	  <!-- menu activos fijos  --
		<li><a href="#">Activos Fijos<span class="caret"></span></a>
			<ul class="dropdown-menu">
				<li><a href="#">Actualizar<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="lisact.php">Incorporaci&oacute;n</a></li>
						<li><a href="desact.php">Desincorporar</a></li>
						<li><a href="depact.php">Depreciaci&oacute;n</a></li>
						<li><a href="departamentos.php">Departamentos</a></li>
					</ul>
				</li>
				<li class="divider"></li>
				<li><a href="#">Reportes<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a target=\"_blank\" href="lisactpdf.php">Activos Fijos</a></li>
						<li><a href="desactpdf.php">Desincorporados</a></li>
						<li><a href="listotpdf.php">Totalmente Depreciados</a></li>
					</ul>
				</li>
			</ul>
	  </li>
	  <!-- fin menu cheques -->
          <!-- <ul class="nav navbar-nav navbar-right"> -->
            
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
			  <span class="glyphicon glyphicon-user"></span>&nbsp;Hola <?php echo $_SESSION['user_session']; ?>&nbsp;<span class="caret"></span></a>
              <ul class="dropdown-menu">
                <!-- <li><a href="#"><span class="glyphicon glyphicon-user"></span>&nbsp;View Profile</a></li> -->
                <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span>&nbsp;Salir</a></li>
              </ul>
            </li>
          
</div>

<?php
}


function ddls($hoy)
{
	$ddls= date('l', strtotime($hoy));
	return $ddls;
}
