<?php
require_once 'php/db.php';

	// Display 10 most recent search items
	$query = "SELECT * FROM live_table c INNER JOIN query_data q ON c.name = q.name ORDER BY querycount DESC LIMIT 2";
	
	// The search
	$result = $test_db->query($query);
	while($results = $result->fetch_array()) {
		$result_array[] = $results;
	}
	
	foreach ($result_array as $result) {
			// The output
			echo '<tr>';			
			echo '<td class="small">'.$result['name'].'</td>';
			echo '<td class="small">'.$result['company'].'</td>';
			echo '<td class="small">'.$result['zip'].'</td>';
			echo '<td class="small">'.$result['city'].'</td>';
			echo '</tr>';	
	}
	
?>	