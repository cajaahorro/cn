<?php
try 
{
	
include('../dbconfig.php');

// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;


$columns = array( 
// datatable column index  => database column name
	0 =>'cue_codigo', 
	1 => 'cue_nombre',
	2 => 'cue_saldo'
);

// getting total number records without any search
$sql = "SELECT cue_codigo, cue_nombre, cue_saldo ";
$sql.=" FROM CAPPOUCLA_sgcaf810";
// $query=mysqli_query($conn, $sql) or die("employee-grid-data.php: get employees");
$query=$db_con->prepare($sql);
$query->execute();
//$totalData = mysqli_num_rows($query);
$totalData = $query->rowCount();
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "SELECT cue_codigo, cue_nombre, cue_saldo ";
$sql.=" FROM CAPPOUCLA_sgcaf810 WHERE 1=1";
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND (cue_codigo LIKE '".$requestData['search']['value']."%' ";    
	$sql.=" OR cue_nombre LIKE '".$requestData['search']['value']."%' ";

	$sql.=" OR cue_saldo LIKE '".$requestData['search']['value']."%' )";
}
//$query=mysqli_query($conn, $sql) or die("employee-grid-data.php: get employees");
$query=$db_con->prepare($sql);
$query->execute();
//$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$totalFiltered = $query->rowCount(); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
//$query=mysqli_query($conn, $sql) or die("employee-grid-data.php: get employees");
$query=$db_con->prepare($sql);
$query->execute();

$data = array();
while( $row=$query->fetch(PDO::FETCH_ASSOC))  
{  // preparing an array
	$nestedData=array(); 

	$nestedData[] = $row["cue_codigo"];
	$nestedData[] = $row["cue_nombre"];
	$nestedData[] = $row["cue_saldo"];
	
	$data[] = $nestedData;
}



$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
			);
echo json_encode($json_data);  // send data as json format
} 
catch (Exception $e) 
{
	die('fallo comando'.$sql);	
}

?>
