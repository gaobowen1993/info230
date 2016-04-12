<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
	<meta charset = "UTF-8">
	<link type = "text/css" rel = "stylesheet" href = "../css/style.css">
	<title>Album Page</title>
</head>

<?php
		require("../config.php");
		require("settings.php");
		$album_id = filter_input( INPUT_GET, 'aID', FILTER_SANITIZE_NUMBER_INT );
		//open database
		require("connect.php");
		$sql_album = 'SELECT * FROM albums';
		$albums = $mysqli->query($sql_album);
		$sql_pic = 'SELECT * FROM pictures, albums_pictures WHERE albums_pictures.pID = pictures.pID';
?>

<body>

	<?php
		require("header.php");
	?>

	<div class = "title">
		<h1>Albums</h1>
	</div>

	<div id = "album-container">

	<?php

		// display all albums in a three-column table
		if(empty($album_id)) {
			$count = 0;
			print("<table id = \"albums\">");
				while($row = $albums->fetch_assoc()){
					$title = $row['aTitle'];
					$aID = $row['aID'];
					if($count%3 == 0) { print("<tr>"); }				
					$count += 1; 
					print("<td><a href='?aID=$aID'>$title</a></td>");
					if($count%3 == 0) { print("</tr>"); }
				}
				print("<tr><td><a href='editAlbum.php'>Edit Album</a></td><td><a href='deleteAlbum.php'>Delete Album</a></td></tr>");
			print("</table>");
		}
		else {
			// display selected album
			$sql_pic .= ' AND albums_pictures.aID = '.$album_id;
			$pic = $mysqli->query($sql_pic);

			print("<table id='album'>");
			//$html_safe_sql = htmlentities( $sql_pic );
			//print( "<p>Showing movies using the SQL query <br>$html_safe_sql</p>");
/*				print("<thead><tr>");
					print("<th>Pic</th>");
					foreach($fields as $field) {
						$field_heading = $field['heading'];
						print("<th>$field_heading</th>");
					}
				print("</tr></thead>");
				
				while($row = $pic->fetch_assoc()) {
					print("<tr>");
						print("<td><img class = \"image\" src=\"{$row['pURL']}{$row['file_name']}\" alt=\"image\"></td>");
						print("<td>{$row['pCaption']}</td>");
						print("<td>{$row['pDesc']}</td>");
						print("<td>{$row['pCredit']}</td>");
					print("</tr>");
				}
*/				
			$count = 0;
			while($row = $pic->fetch_assoc()) {
				if($count%3 == 0) print("<tr>");
				print("<td><img class= \"imgSmall\" src=\"{$row['pURL']}{$row['file_name']}\" alt=\"image\"></td>");
				$count += 1;
				if($count%3 == 0) print("</tr>");
			}

			print("</table>");
			print("<p><a href = \"album.php\">View All Albums</a></p>");
		}
	?>

	</div>

</body>

</html>

	