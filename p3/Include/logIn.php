<?php session_start();?>
<!DOCTYPE html>
<html>

<head>
	<meta charset = "UTF-8">
	<link type = "text/css" rel = "stylesheet" href = "../css/style.css">
	<title>Log In Page</title>
</head>

	<?php
		$message='';
		$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
		$password = filter_input(INPUT_POST, 'password');
		require '../config.php';
		require 'connect.php';
		$query = "SELECT * FROM users WHERE username = '$username'";
		$state = filter_input(INPUT_GET, 'state', FILTER_SANITIZE_NUMBER_INT);

		if(!empty($_POST['logSubmit'])) {

			$result = $mysqli->query($query);
			if($result&&$result->num_rows ==1) {
				$row = $result->fetch_assoc();
				$db_password = $row['hashpassword'];
			}
			$valid_password = password_verify($password, $db_password);
		
			if($valid_password) {
				$_SESSION['loggedUser'] = $username;
				$message.= 'Log in success. User navigation bar to navigate';
			} else {
				$message.= "<p>You do not sucessfully log in.</p>";
				$message.= "<p><a href = 'logIn.php?state=1'>Please log in</a></p>";
			}
		}

		if(!empty($_POST['logout'])) {
			unset($_SESSION['loggedUser']);
			$state =1;
		}
	?>

<body>
	<?php
		require('header.php');
		if($state == 1){
			if(empty($username) || empty($password)){
				print("<form method = \"POST\">");
				print("<p><label>Username: </label><input type = \"text\" name = \"username\"></p>");
				print("<p><label>Password: </label><input type =\"password\" name = \"password\"></p>");
				print("<input type = \"submit\" name = \"logSubmit\">");
				print("</form>");
			}			
		}  
		if($state == 0){
			print("<form method = 'POST'>");
			print("<p>Log Out? <input type='submit' name = 'logout'></p>");
			print("</form>");
		}
		print $message;
	?>
</body>

</html>