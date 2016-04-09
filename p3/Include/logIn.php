<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
	<meta charset = "UTF-8">
	<link type = "text/css" rel = "stylesheet" href = "../css/style.css">
	<script src='https://code.jquery.com/jquery-1.10.2.js'></script>
	<script src='../js/logIn.js'></script>
	<title>Log In Page</title>
</head>

	<?php
		if(!empty($_POST['logSubmit'])) {
			$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
			$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
		}
	?>

<body>
	<?php
		require('header.php');
		if(empty($username) || empty($password)){
			print("<form method = \"POST\">");
			print("<p><label>Username: </label><input type = \"text\" name = \"username\"></p>");
			print("<p><label>Password: </label><input type =\"password\" name = \"password\"></p>");
			print("<input type = \"submit\" name = \"logSubmit\">");
			print("</form>");
			if($username == 'bg428' && $password == 'bg428') {
				$_SESSION['loggedUser'] = $username;
			} else {
				echo "<p>You do not sucessfully log in.</p>";
				echo "<p>Please log via <a href = 'logIn.php'></a></p>";
			}
		}
	?>
</body>

</html>