<?php
session_start();
/*
error_reporting(E_ALL);
ini_set('display_errors','1');
/*-----------------------
Autor: Obed Alvarado
http://www.obedalvarado.pw
Fecha: 12-06-2015
Version de PHP: 5.6.3
----------------------------*/

	# conectare la base de datos
	include_once('../funciones.php');
	$mensajes = $errors []= "";
	include_once('../dbconfig.php');
	$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	$action='ajax';
	if($action == 'ajax'){
		include '../pagination.php'; //incluir el archivo de paginación
		//las variables de paginación
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		$per_page = 10	; //la cantidad de registros que desea mostrar
		$adjacents  = 4; //brecha entre páginas después de varios adyacentes
		$offset = ($page - 1) * $per_page;
		//Cuenta el número total de filas de la tabla*/
		// $count_query   = mysqli_query($con,"SELECT count(*) AS numrows FROM countries ");
		$estatus_pendiente=1;
		$consulta="select count(cue_codigo) as numrows from ".$_SESSION[institucion]."sgcaf810 ";
		// echo $consulta;
		$con=$db_con->prepare($consulta);
		$count_query   = $con->execute();
		// if ($row= mysqli_fetch_array($count_query)){$numrows = $row['numrows'];}
		$numrows=$con->fetch(PDO::FETCH_ASSOC);
		$numrows=$numrows['numrows'];
		$total_pages = ceil($numrows/$per_page);
		$reload = 'cuentas.php';
		//consulta principal para recuperar los datos
		$paginas="select cue_codigo, cue_nombre, cue_saldo, cue_nivel, codsudeca, nomsudeca, (cue_saldo-(cue_cre01+cue_cre02+cue_cre03+cue_cre04+cue_cre05+cue_cre06+cue_cre07+cue_cre08+cue_cre09+cue_cre10+cue_cre11+cue_cre12)+(cue_deb01+cue_deb02+cue_deb03+cue_deb04+cue_deb05+cue_deb06+cue_deb07+cue_deb08+cue_deb09+cue_deb10+cue_deb11+cue_deb12)) as cue_actual FROM ".$_SESSION[institucion]."sgcaf810 ORDER BY cue_codigo LIMIT $offset,$per_page ";
		$con=$db_con->prepare($paginas);
		$query = $con->execute();
		// echo $paginas;
		
		if ($numrows>0){

			?>
<div id="resultado"></div>

					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<form class="form-inline" name="search" role="form" method="POST" onkeypress="return event.keyCode != 13;">
									<!-- form-horizontal div class="input-group col-sm-11"> -->
										<label for="busqueda">Su busqueda</label>
										<input id="busqueda" name="busqueda" type="text" class="form-control" placeholder="Quiero buscar..." autocomplete="off"/>
										<!-- <span class="input-group-btn"> -->
											<button type="button" class="btn btn-default btnSearch">
												<span class="glyphicon glyphicon-search"> </span>
											</button> 
										<!-- </span>-->
									<!-- </div> -->
								</form>
						</div>

					</div>
					<script>$(".tablesearch").hide();</script>
<!--
					<div class="row mt">
						<div class="col-lg-12">
							<div class="content-panel tablesearch">

								<section id="unseen">
									<table id="resultTable" class="table table-bordered table-hover table-condensed">
										<thead>
											<tr>
												<th class="small">C&oacute;digo</th>
												<th class="small">Descripci&oacute;n</th>
												<th class="small">Cod.SUDECA</th>
												<th class="small">Desc.SUDECA</th>
											</tr>
										</thead>
										<tbody></tbody>
									</table>
								</section>

-->							</div><!-- /content-panel -->
						</div><!-- /col-lg-4 -->
					</div><!-- /row -->

  <script type="text/javascript">
    $(document).ready(function()
    {
/*
    
    $('input#name').keyup(function(e){
	var query_value = $('input#name').val();
//	alert(query_value);
	if(query_value !== ''){
		$.ajax({
			type: "POST",
			url: "js_cuentas/php/search.php",
			data: { query: query_value },
			cache: false,
//			beforeSend: function(){
//				$("#resultado").html("<img src='cargando.gif' />");
//            },
			error: function(){
                    alert("error petición ajax");
            },
			success: function(html){
				$("table#resultTable tbody").html(html);
			}
		});
	}return false;    
	});

	$("input#name").live("keyup", function(e) {
		// Set Timeout
		clearTimeout($.data(this, 'timer'));
		
		// Set Search String
		var search_string = $(this).val();
		
		// Do Search
		if (search_string == '') {
			$(".tablesearch").fadeOut(300);
		}else{
			$(".tablesearch").fadeIn(300);
			$(this).data('timer', setTimeout(search, 100));
		};
	});
        var consulta;
        //hacemos focus al campo de búsqueda
        $("#busqueda").focus();
                                                                                                      
*/
        //comprobamos si se pulsa una tecla
        $("#busqueda").keyup(function(e){
                                       
              //obtenemos el texto introducido en el campo de búsqueda
              consulta = $("#busqueda").val();
              //hace la búsqueda                                                                
              $.ajax({
                    type: "POST",
			url: "js_cuentas/php/search.php",
			data: { query: consulta },
                    dataType: "html",
                    beforeSend: function(){
                    //imagen de carga
		//				$(".tablesearch").fadeOut(300);
                    	$("#resultado").html("<img src='loader.gif' />");
                    },
                    error: function(){
                    alert("error petición ajax");
                    },
                    success: function(data){                                                    
					//	$(".tablesearch").fadeIn(300);
					//	$(this).data('timer', setTimeout(search, 100));

	                    $("#resultado").empty(); $("#resultado").append(data);
		                    //seleccionamos de la lista
	                    var lista = $('div#resultado');
	                    lista.bind("mousedown", function (e) {
	                    e.metaKey = false;
	                    }).selectable({
		                    stop: function () {
			                    var result = $("input#busqueda");
			                    var fakeText = $('p.hidden-tips-text').empty();
			                    $(".ui-selected", this).each(function () {
				                    var index = $(this).text();
				                    fakeText.append((index) + "");
				                });
			                    result.val(fakeText.text());
	        				}
    					});          
                  	}
              	});       
        	});                                                    
    });
  </script>


		<table id="datos_cuentas" class="table-bordered table-hover table-condensed">
		
			  <thead>
				<tr>
				  <th class="small">C&oacute;digo</th>
				  <th class="small">Descripci&oacute;n</th>
				  <th class="small">Saldo Inicial</th>
				  <th class="small">Saldo Actual</th>
				  <th class="small">Codigo SUDECA</th>
				  <th class="small">Nombre SUDECA</th>
				  <th class="small"></th>
				</tr>
			</thead>
			<tbody>
			<?php
			while($row = $con->fetch(PDO::FETCH_ASSOC)){
				?>
				<tr>
					<td class="small"><?php echo $row['cue_codigo'];?></td>
					<td class="small"><?php echo substr($row['cue_nombre'],0,40);?></td>
					<td class="small"><?php echo number_format($row['cue_saldo'],2,'.',',');?></td>
					<td class="small"><?php echo number_format($row['cue_actual'],2,'.',',');?></td>
					<td class="small"><?php echo $row['cuesudeca'];?></td>
					<td class="small"><?php echo substr($row['cuesudeca'],0,40);?></td>
					<td>
						<button type="button" title="Modificar" class="btn btn-info btn-sm" data-toggle="modal" data-target="#dataUpdate" data-id="<?php echo $row['cue_codigo']?>" data-codigo="<?php echo $row['cue_codigo']?>" data-nombre="<?php echo $row['cue_nombre']?>" data-codigosudeca="<?php echo $row['codigosudeca']?>" data-nombresudeca="<?php echo $row['nombresudeca']?>"><i class='glyphicon glyphicon-edit'></i> </button>
						<button type="button" title="Eliminar" class="btn btn-danger  btn-sm" data-toggle="modal" data-target="#dataDelete" data-id="<?php echo $row['cue_codigo']?>" data-codigo="<?php echo $row['cue_codigo']?>" data-nombre="<?php echo $row['cue_nombre']?>" ><i class='glyphicon glyphicon-trash'></i></button>
						<?php
						if ($row['cue_nivel'] == 7)
						{
						?>
						<button type="button" title="Mayor Anal&iacute;ico" class="btn btn-default  btn-sm" data-toggle="modal" data-target="#dataPrint" data-id="<?php echo $row['cue_codigo']?>" data-codigo="<?php echo $row['cue_codigo']?>" data-nombre="<?php echo $row['cue_nombre']?>" ><i class='glyphicon glyphicon-print'></i> </button>
						<?php
						}
						?>
					</td>
						<?php 
						?>
					</td>
				</tr>
				<?php
			}
			?>
			</tbody>
		</table>
<!--
	<form action="opciones.php" method="POST">
		<div class="col-md-6">
		<button class="btn btn-danger" value="Regresar" name="Regresar">Regresar al Men&uacute;</button>
		</div>
	</form>
-->
		<div class="table-pagination pull-left">
			<h3 class='text-right'>		
				<button type="button" class="btn btn-default" data-toggle="modal" data-target="#dataRegister"><i class='glyphicon glyphicon-plus'></i> Agregar</button>
			</h3>
		</div>
		<div class="table-pagination pull-right">
			<?php echo paginate($reload, $page, $total_pages, $adjacents);?>
		</div>
		
			<?php
			
		} else {
			mensaje(array(
				"titulo"=>"Aviso!!!",
				"tipo"=>"warning",
				"texto"=>"<h4>Aviso!!!</h4> No hay datos para mostrar",
				));
		}
	}
		
?>
