<?php
@session_start();

	require("dbconfig.php");
	if (0==1){ // (!$db_config) {
		// Show error if we cannot connect.
		echo 'ERROR: Could not connect to the database.';
	} else {
		// Is there a posted query string?
		if(isset($_POST['queryString'])) {
			$queryString = $_POST['queryString'];
//			$queryString = $db->real_escape_string($_POST['queryString']);
			
			// Is the string length greater than 0?
			
			if(strlen($queryString) >0) {
				// Run the query: We use LIKE '$queryString%'
				// The percentage sign is a wild-card, in my example of countries it works like this...
				// $queryString = 'Uni';
				// Returned data = 'United States, United Kindom';
				
				// YOU NEED TO ALTER THE QUERY TO MATCH YOUR DATABASE.
				// eg: SELECT yourColumnName FROM yourTable WHERE yourColumnName LIKE '$queryString%' LIMIT 10

				$filtro="SELECT cod_prof, ced_prof, ape_prof,nombr_prof FROM CAPPOUCLA_sgcaf200 WHERE ((cod_prof LIKE '$queryString%') or (ape_prof LIKE '$queryString%') or (nombr_prof LIKE '$queryString%')) order by cod_prof  LIMIT 10";
				
				$query = $db_con->prepare($filtro);
				$query->execute();
				if($query) {
					// While there are results loop through them - fetching an Object (i like PHP5 btw!).
					while ($result = $query->fetch(PDO::FETCH_ASSOC)) {
						// Format the results, im using <li> for the list, you can change it.
						// The onClick function fills the textbox with the result.
						
						// YOU MUST CHANGE: $result->value to $result->your_colum
	         			echo '<li onClick="fill7(\''.$result['cod_prof'].'\');">'.$result['cod_prof'].' - '.$result['ape_prof'].' - '.$result['nombr_prof'].'</li>';
	         		}
				} else {
					echo 'ERROR: There was a problem with the query.'.$filtro;
				}
			} else {
				// Dont do anything.
			} // There is a queryString.
		} else {
			echo 'There should be no direct access to this script!';
		}
	}
?>