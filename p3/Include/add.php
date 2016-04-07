<!DOCTYPE html>
<html>

<head>
	<meta charset = "UTF-8">
	<link type = "text/css" rel = "stylesheet" href = "../css/style.css">
	<title>Add Album/Pictures</title>
</head>



<?php

	require("../config.php");
	require("settings.php");
	require("connect.php");
	$message = '';
	$id = filter_input( INPUT_GET, 'id' );
	
	// the add album button is clicked
	if(!empty($_POST['save_album'])) {

		if(empty( $_POST['title'])) {
			$message .= "<p>No empty inputs!</p>";
		}
		else {
			$fieldvalue = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
		
			if(empty($fieldvalue)) {
				$message .= "<p>Invalid Inputs!</p>";
			}
			else {
				$addAlbum_sql = "INSERT INTO albums (aTitle) VALUES ('$fieldvalue')";
			}

			if(!empty($addAlbum_sql)) {
				if($mysqli->query($addAlbum_sql)) {
					$message .= "<p>Album added successfully</p>";
				}
				else {
					$message .= "<p>Unsucessful adding!</p><p>$mysqli->error</p>";
				}
			}
		}		
	}

		// upload image to image folder
	if(!empty($_FILES['newphoto'])) {
		$newPhoto = $_FILES['newphoto'];
		$originName = $newPhoto['name'];
		if($newPhoto['error'] == 0) {
			$tempName = $newPhoto['tmp_name'];
			move_uploaded_file($tempName, "../image/$originName");
			//$_SESSION['photos'][] = $originName;
			$message .= "The file $originName was uploaded successfully.\n";
		} else {
			$message .= "Error: file was not uploaded.\n";
		}
	}

		// the add pictures button is clicked
	if(!empty($_POST['button'])) {

		$field_values = array();
		 // check input image information
		foreach($fields as $field) {
			$field_term = $field['term'];
			$filter = $field['filter'];

			if(!empty($_POST[$field_term])) {
				$field_value = filter_input(INPUT_POST, $field_term, $filter);
				if(empty($field_value)) {
					$message .= '<pre>Invalid Input!</pre>';
					exit();
				} else {
					$field_values[$field_term] = $field_value;
				}
			} else { $message .= '<p>Please type all fields!</p>'; }
		}

		// get chosen album
		$chooseAlbum_sql1 = "SELECT aTitle, aID FROM albums";
		$results1 = $mysqli->query($chooseAlbum_sql1);
		$album_values1 = array();
		while($row1 = $results1->fetch_row()) {
		$album_values[$row1[1]] = $row1[1]; }			
		$count = 0;
		$album_keys = array_keys($album_values);
		echo print_r($album_keys);
		$chosenAlbums = array();						
		foreach($album_keys as $album_key) {
			if(!empty($_POST[$album_key])) {
				$chosenAlbum = $_POST[$album_key];
				$chosenAlbums[$count] = $chosenAlbum;
				$count = $count + 1;
			} 
		}
 

		if (!empty($field_values) && !empty($_FILES['newphoto'])){
				

			// add url and file_name to fields
			$field_values['pURL'] = "../image/";
			$field_values['file_name'] = $originName;

			$field_keys = array_keys($field_values);
			$field_keys = implode(',', $field_keys);
			$field_values = implode("','", $field_values);

			$addPic_sql = "INSERT INTO pictures ($field_keys) VALUES ('$field_values')";
			echo $addPic_sql;
			if(!empty($addPic_sql)) {
				if($mysqli->query($addPic_sql)) {
					echo'<p>Pictures added successfully</p>';
				} else {
					echo'<p>Unsucessful adding</p><p>'.$mysqli->errno.'</p>';
				}
			}

			if(!empty($chosenAlbums)) {
				$pic_id = $mysqli->insert_id;
				foreach($chosenAlbums as $chosenAlbum) {
					$addRelation_sql = "INSERT INTO albums_pictures (aID, pID) VALUES ($chosenAlbum, $pic_id)";
					echo $addRelation_sql;
					//$mysqli->query($addRelation_sql);
					if( ! empty( $addRelation_sql ) ) {
						if( $mysqli->query($addRelation_sql) ) {
							echo'<p>Relation added.</p>';
						} else {
							echo"<p>Error adding relationship.</p><p>$mysqli->error</p>";
						}
					}
				}				
			} else { echo'you choose no album'; }
		} else { $message .= '<p>incorrect<p>'; }
	}

?>

<body>

	<?php
		require("header.php");
	?>

	<?php
		
		if($id == 'album') {
			// display add album form
			$action = "Albums";
			print("<div class = 'title'>");
			print("<h1>Add $action</h1>");
			print("</div>");
			print("<div id = 'addAlbum'><form method = 'post'>");
				print("<p><label>Title: </label><input type='text' name='title'></p>");
				print("<input type='submit' name='save_album' value='Save'>");
			print("</form>");
			print("<p><a href = album.php>View All Albums</a></p></div>");
		} 
		if($id == 'pic') {
			// diplay add pictures form
			$action = "Pictures";
			print("<div class = 'title'>");
			print("<h1>Add $action</h1>");
			print("</div>");
			print("<div id='addPic-container'><form method='post' enctype='multipart/form-data'>");
			foreach($fields as $field) {
				$term = $field['term'];
				$field_heading = $field['heading'];
				print("<p><label>$field_heading</label><input type='text' name='$term'></p>");
			}

			// get all titles in album table
			print("<label>Choose Albums</label><br>");
			$chooseAlbum_sql = "SELECT aTitle, aID FROM albums";
			$results = $mysqli->query($chooseAlbum_sql);
			while($row = $results->fetch_row()) {
				print("<label></label><input type='checkbox' name='$row[1]' value='$row[1]'>$row[0]<br>");
			}
			//print("<label></label><input type='checkbox' name='empty' value='empty'>No Album<br>");
			//echo print_r($album_values);

			print("<p><input type='file' name='newphoto'></p>");
			print("<input type='submit' name='button' value='Save'>");
			print("</form>");
			print("<p><a href = image.php>View All Pictures</a></p></div>");
		}
	?>

	<?php
		print $message;
	?>


</body>

</html>

