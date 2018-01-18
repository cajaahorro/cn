<?php
try 
{
	require('../../dbconfig.php');
	// Output HTML formats
	$html1='<div class="row mt">';
	$html1.='<div class="col-lg-12">';
	$html1.='<div class="content-panel tablesearch">';
	$html1.='<section id="unseen">';
	$html1.='<table id="resultTable" class="table table-bordered table-hover table-condensed">';
	$html1.='<thead>';
	$html1.='<tr>';

	$html1.='<th class="small">C&oacute;digo</th>';
	$html1.='<th class="small">Descripci&oacute;n</th>';
	$html1.='<th class="small">Cod.SUDECA</th>';
	$html1.='<th class="small">Desc.SUDECA</th>';

/*
	$html1 .= '<td class="small">nameString</td>';
	$html1 .= '<td class="small">compString</td>';
	$html1 .= '<td class="small">zipString</td>';
	$html1 .= '<td class="small">cityString</td>';
	$html1 .= '<td class="small">opciones</td>';
*/
	$html1.='</tr>';
	$html1.='</thead>';
	$html1.='<tbody>';
	/*
	$html1.=</tbody>';
	$html1.='</table>';
	$html1.='</section>';
*/
	$html = '<tr>';
	$html .= '<td class="small">nameString</td>';
	$html .= '<td class="small">compString</td>';
	$html .= '<td class="small">zipString</td>';
	$html .= '<td class="small">cityString</td>';
	$html .= '<td class="small">opciones</td>';
	$html .= '</tr>';

/*	$o = str_replace('nameString', 'C&oacute;digo', $html1);
	$o = str_replace('compString', 'Descripci&oacute;n', $o);
	$o = str_replace('zipString', 'Cod.SUDECA', $o);
	$o = str_replace('cityString', 'Desc.SUDECA', $o);

//	$html = $o.$html;
*/
	echo $html1;
	// Get the Search
	$search_string = preg_replace("/[^A-Za-z0-9]-/", " ", $_POST['query']);
	// $search_string = $db_con->real_escape_string($search_string);

	// Check if length is more than 1 character
	if (strlen($search_string) >= 3 && $search_string !== ' ') {
		// Query
		$query = 'SELECT cue_codigo, cue_nombre, cue_nivel, codsudeca, nomsudeca  FROM CAPPOUCLA_sgcaf810 WHERE (cue_nombre LIKE "%'.$search_string.'%") OR (cue_codigo LIKE "%'.$search_string.'%") ORDER BY cue_codigo LIMIT 10';
// echo $query;
		$result = $db_con->prepare($query);
		$result->execute();
		while($results = $result->fetch(PDO::FETCH_ASSOC)) {
			$result_array[] = $results;
		}
		
		// Check for results
		if (isset($result_array)) {
			// $o = str_replace('nameString', $html, $html1);
			foreach ($result_array as $result) {
			// Output strings and highlight the matches
			 $d_name = preg_replace("/".$search_string."/i", "<b>".$search_string."</b>", $result['cue_codigo']);
			 $d_comp = preg_replace("/".$search_string."/i", "<b>".$search_string."</b>", $result['cue_nombre']);
			 //$d_comp = $result['cue_nombre'];
			 $d_zip = $result['codsudeca'];
			 $d_city = $result['nomsudeca'];
			// Replace the items into above HTML
			$o = '<td>'.str_replace('nameString', $d_name, $html).'</td>';
			$o = '<td>'.str_replace('compString', $d_comp, $o).'</td>';
			$o = '<td>'.str_replace('zipString', $d_zip, $o).'</td>';
			$o = '<td>'.str_replace('cityString', $d_city, $o).'</td>';
			$op_modificar = '<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#dataUpdate" data-id="'.$result['cue_codigo'].'" data-codigo="'.$result['cue_codigo'].'" data-nombre="'.$result['cue_nombre'].'" data-codigosudeca="'.$result['codigosudeca'].'" data-nombresudeca="'.$result['nombresudeca'].'"><i class="glyphicon glyphicon-edit"></i> Modificar</button>';
			$o = '<td>'.str_replace('opciones', $op_modificar, $o).'</td>';
/*
						<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#dataDelete" data-id="<?php echo $row['cue_codigo']?>" data-codigo="<?php echo $row['cue_codigo']?>" data-nombre="<?php echo $row['cue_nombre']?>" ><i class='glyphicon glyphicon-trash'></i> Eliminar</button>
						<?php
						if ($row['cue_nivel'] == 7)
						{
						?>
						<button type="button" class="btn btn-default" data-toggle="modal" data-target="#dataPrint" data-id="<?php echo $row['cue_codigo']?>" data-codigo="<?php echo $row['cue_codigo']?>" data-nombre="<?php echo $row['cue_nombre']?>" ><i class='glyphicon glyphicon-print'></i> Anal&iacute;tico</button>
						<?php
						}
						?>
*/
//			$o.='</td>';
			// Output it
			echo($o);
				}
			}else{
			// Replace for no results
			$o = str_replace('nameString', '<span class="label label-danger">No Names Found</span>', $html);
			$o = str_replace('compString', '', $o);
			$o = str_replace('zipString', '', $o);
			$o = str_replace('cityString', '', $o);
			// Output
			echo($o);
		}
	}
} 
catch (Exception $e) 
{
	die('fallo '.$e->getMessage());
}

?>