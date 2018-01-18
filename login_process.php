<?php
	session_start();
	require_once 'dbconfig.php';

	if(isset($_POST['btn-login']))
	{
		//$user_name = $_POST['user_name'];
		$user_email = trim($_POST['nombre_usuario']);
		$user_password = trim($_POST['password']);
		
		$password = ($user_password);
		// $password = ($user_password);
		// echo 'clave '.$password;
		
		try
		{	
		
			/*
			$stmt = $db_con->prepare("SELECT * FROM sgcapass WHERE user_email=:email");
			$stmt->execute(array(":email"=>$user_email));
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			$count = $stmt->rowCount();
			*/
/*
			$comando="SELECT * FROM sgcapass WHERE '$user_email'=alias and password('$password')=password";
			// echo $comando;
			$res=mysql_query($comando) or die(mysql_error($res). ' - '.$comando);
			$row=mysql_fetch_assoc($res);
			// if($row['user_password']==$password){
			if(mysql_num_rows($res) > 0){
*/

			$sql = "SELECT * FROM CAPPOUCLA_sgcapass WHERE alias=:email and password = sha1(:password)";
			$stmt = $db_con->prepare($sql);
			$stmt->execute(array(":email"=>$user_email, ":password"=>$password));
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			$count = $stmt->rowCount();
			if($count > 0){

				echo "ok"; // log in
				$_SESSION['user_session'] = $row['alias'];
				$_SESSION['institucion']='CAPPOUCLA_';
			}
			else{
				
				echo "Nombre de usuario o clave inv&aacute;lida"; // wrong details 
			}
				
		}
		catch(PDOException $e){
			echo $e->getMessage();
			// echo 'Algo ha fallado!';
		}
	}

?>