<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
	<meta charset = "UTF-8">
	<link type = "text/css" rel = "stylesheet" href = "../css/style.css">
	<title>Search Pictures</title>
</head>

	<?php
		$display = 0;
		$message='';
		$keyword = filter_input(INPUT_POST, 'searchField', FILTER_SANITIZE_STRING);
		$caption = filter_input(INPUT_POST, 'caption', FILTER_SANITIZE_STRING);
		$album = filter_input(INPUT_POST, 'album', FILTER_SANITIZE_STRING);

		require '../config.php';
		require 'connect.php';

		//$field = $mysqli->query("SELECT * FROM pictures");
		
		if(!empty($_POST['searchSubmit'])) {
			$display = 1;
			if(empty($keyword)) {
				$message.= 'No empty input';
			} else {

				$result = $mysqli->query("SELECT * FROM pictures WHERE pCaption LIKE '%".$keyword."%'
									OR pURL LIKE '%".$keyword."%' OR file_name LIKE '%".$keyword."%'
									OR pDesc LIKE '%".$keyword."%' OR pCredit LIKE '%".$keyword."%'");			
			}
		}

		if(!empty($_POST['multiSearch'])) {

			$display = 1;
			if(empty($caption)&&empty($album)) {
				$message.= 'No empty input';
			} else if (!empty($caption)&&empty($album)) {
				$result = $mysqli->query("SELECT * FROM pictures WHERE pCaption='$caption'");			
			} else if (empty($caption)&&!empty($album)) {
			echo $album;				
				$result = $mysqli->query("SELECT * from pictures where pictures.pID in (SELECT albums_pictures.pID from albums_pictures where albums_pictures.aID = (SELECT albums.aID from albums where albums.aTitle = '$album'))");
			} else {
				$result = $mysqli->query("SELECT * from pictures where pictures.pCaption = '$caption' AND pictures.pID in (SELECT albums_pictures.pID from albums_pictures where albums_pictures.aID = (SELECT albums.aID from albums where albums.aTitle = '$album'))");
			}
		}

	?>

<body>
	<?php
		require 'header.php';
	?>

	<?php

		if($display ==0){
			print("<div class = 'title'>");
			print("<h1>Search Pictures</h1></div>");
			print("<div class = searchContainer>");
			print("<div id = 'partialSearch'><form method = 'POST'><p>Partial Search</p>");
			print("<p><label>Input key word: </label><input type = 'text' name = 'searchField'></p>");
			print("<p><input class = 'button' type = 'submit' name = 'searchSubmit' value = 'Submit'>");
			print("</form></div>");
			print("<div id = 'multiSearch'><form method = 'POST'><p>Multi Search</p>");
			print("<p><label>Caption: </label><input type = 'text' name = 'caption'></p>");
			print("<p><label>Album: </label><input type = 'text' name = 'album'></p>");
			print("<p><input class = 'button' type = 'submit' name = 'multiSearch' value = 'Submit'>");
			print("</form>");
			print("</div></div>");			
		}

		if($display ==1){
			if(!empty($result)){
				$count = 0;
				print("<table>");
				while($row = $result->fetch_assoc()){
					if($count%3 ==0) {print("<tr>");}
					print("<td><img class = 'imgSmall' src=\"{$row['pURL']}{$row['file_name']}\" alt ='image'></td>");
					$count += 1;
					if($count%3 ==0) {print("</tr>");}
				}
				print("</table>");
			}
			print("<p style = 'text-align:center'><a href='search.php'>Back to search</a></p>");
			print $message;
		}
	?>

</body>

</html>