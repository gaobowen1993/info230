<?php session_start() ?>
<html>

<head>
	<meta charset = "UTF-8">
	<link type = "text/css" rel = "stylesheet" href = "../css/style.css">
	<title>Delete Album</title>
</head>

	<?php
		require '../config.php';
		require 'connect.php';

		// delete button is clicked
		if(!empty($_POST['delete_submit'])) {

			$message=''; // message to display for users
			$albums_array = array(); // empty array to contain all albums id

			$result = $mysqli->query('SELECT aID FROM albums');
			if(!$result) {$message .= 'Query error'; die();}
			else {
				while($row=$result->fetch_assoc()) {
					$albums_array[$row['aID']] = $row['aID'];
			 	}
				$array_keys = array_keys($albums_array);

				$chosenAlbums = array(); // empty array to contain chosen deleting album id
				$count = 0; // key of chosenArray
				foreach($array_keys as $array_key) {
					if(!empty($_POST[$array_key])) {
						$chosenAlbums[$count] = $_POST[$array_key];
						$conut += 1;
					}
				}
				if(!empty($chosenAlbums)) {
					foreach($chosenAlbums as $chosenAlbum) {
						$delete_query = 'DELETE FROM albums WHERE aID='.$chosenAlbum;
						$delete_result = $mysqli->query($delete_query);
						if(!$delete_result) {$message .= 'Query error'; die();}
						else {
							$message.='Delete success';
						}
					}
				} else {
					$message.='Please choose albums to delete';
				}
			}
		}
	?>

<body>
	<?php 
		require 'header.php';
	?>

	<?
		if(isset($_SESSION['loggedUser'])) {

			$result = $mysqli->query('SELECT * FROM albums');
			if(!$result) {
				echo 'Query error';
				die();

			} else {
				print("<div><form method = 'POST'>");
				while($row = $result->fetch_assoc()) {
					print("<p><label style=\"width:180px\">{$row['aTitle']}</label><input type = \"checkbox\" name = \"{$row['aID']}\" value= \"{$row['aID']}\" ></p>");
				}
				print("<input type = 'submit' name = 'delete_submit' value = 'Delete'>");
				print("</form></div>");
				print $message;
			}

		} else {
			echo "<p><a href = 'logIn.php'>Please log in</a></p>";
		}
	?>

</body>

</html>