<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
	<meta charset = "UTF-8">
	<link type = "text/css" rel = "stylesheet" href = "../css/style.css">
	<title>Edit Album</title>
</head>

	<?php
		require '../config.php';
		require 'connect.php';

		$message = '';
		$add = filter_input(INPUT_GET, 'add', FILTER_SANITIZE_NUMBER_INT); // get the added album id
		$deleteId = filter_input(INPUT_GET, 'deleteId', FILTER_SANITIZE_NUMBER_INT);
		$edit = filter_input(INPUT_GET, 'edit', FILTER_SANITIZE_NUMBER_INT);
		$editAlbum = filter_input(INPUT_POST, 'editAlbum', FILTER_SANITIZE_STRING);

		$query = 'SELECT * FROM albums';
		$query_image = 'SELECT p1.pURL, p1.file_name, p1.pID FROM pictures p1 WHERE p1.pID 
							NOT IN (SELECT DISTINCT p.pID FROM pictures p
							INNER JOIN albums_pictures ap ON p.pID = ap.pID WHERE ap.aID='.$add.')
							 UNION SELECT p2.pURL, p2.file_name, p2.pID FROM pictures p2 WHERE p2.pID NOT IN 
							(SELECT pID FROM albums_pictures)';
		$query_current_image = 'SELECT * FROM pictures p INNER JOIN albums_pictures ap ON p.pID = ap.pID WHERE ap.aID ='.$deleteId;
		$edit_query = 'SELECT * FROM albums WHERE aID='.$edit;

		$image_array = array();
		$current_image_array = array();

		// if remove button has clicked	
		if(!empty($_POST['submitDelete'])) {

			// get current images in this album
			$current_image = $mysqli->query($query_current_image);

			if(!$current_image) {
				$message.= 'Query error';
				die();
			} else {
				while($row = $current_image->fetch_assoc()) {
					$current_image_array[$row['pID']] = $row['pID'];
				}					
			} 

			// check if there are any images in the album
			if(!empty($current_image_array)) {

				$chosenImages = array(); // array of chosen images to be deleted
				$conut = 0; // key of chosenImage
				$image_keys = array_keys($current_image_array);
				foreach($image_keys as $image_key) {
					if(!empty($_POST[$image_key])) {
						$chosenImages[$count] = $_POST[$image_key];
						$count += 1;
					}
				}
				// check if any image is selected
				if(!empty($chosenImages)) {
					foreach($chosenImages as $chosenImage) {
						$delete_query = 'DELETE FROM albums_pictures WHERE aID='.$deleteId.' AND pID='.$chosenImage;
						$result = $mysqli->query($delete_query);
						if(!$result) {
							$message.= 'Query error';
							die();
						} else {
							$message.= 'You have delete picture '.$chosenImage.' from album '.$deleteId;
						}
					}
				} else {
					$message.= 'Please select an image.';
				}
			} else {
				$message.= 'No images in this album.';
			}
		}

		if(!empty($_POST['editButton'])) {

			$editResult = $mysqli->query("UPDATE albums SET aTitle = '$editAlbum' WHERE aID = $edit");

			if((!$editResult)) {
				$message.= 'Query error';
				die();
			} else {
				$message.='Edit sucess.';
			}
		}

	?>

<body>

	<?php
		require("header.php");
	?>

	<?php

	if(isset($_SESSION['loggedUser'])) {

		if(empty($add)&&empty($deleteId)&&empty($edit)) {

		print('<div class = "title">
			<h1>Edit albums</h1>
		</div>');			
			$result = $mysqli->query($query);
		
			if(!$result) {
				echo 'Query error';
				die();
			} else {
				print('<div id = "editAlbum-container"><table id = "editAlbum-table">');
				while($row = $result->fetch_assoc()) {
					print("<tr><td>{$row['aTitle']}</td>
						<td><a href=\"?edit={$row['aID']}\">Edit image</a></td>
						<td><a href=\"?add={$row['aID']}\">Add image</a></td>
						<td><a href=\"?deleteId={$row['aID']}\">Remove image</a></td>
						</tr>");
				}
				print('</table><p><a href="album.php">Back to album collections</a></p></div>');
			}
		}

		if($add) {

		print('<div class = "title">
			<h1>Add images</h1>
		</div>');			
			// get all images to add
			$result = $mysqli->query($query_image);

			if(!$result) {
				echo 'Query error';
				die();
			} else {
				// print image table
				$count = 0;
				print("<form method=\"POST\">");
				print('<div><table>');
				while($row = $result->fetch_assoc()) {
					$image_array[$row['pID']] = $row['pID']; // get an array of all images, with pID as both key and value
					if ($count%4 == 0) print("<tr>");
					print("<td><img class = \"imgSmall\" src=\"{$row['pURL']}{$row['file_name']}\" alt=\"image\">");
					print("<br><input type = \"checkbox\" name=\"{$row['pID']}\" value = \"{$row['pID']}\"></td>");
					$count += 1;
					if ($count%4 == 0) print("</tr>");
				}
				print('</div></table>');
				print("<p style = \"text-align:center\"><input class = \"button\" type = \"submit\" name = 'submitAdd' value = 'Add'></p>");
				print("</form><p style = \"text-align:center\"><a href=\"editAlbum.php\">Back to edit albums</a></p>");

				// if the add image submit button is clicked		
				if(!empty($_POST['submitAdd'])) {
			
					$chosenImages = array(); // define an empty array for chosen pID
					$chosenCount = 0; // key of chosen image in chosenImages
					$image_keys = array_keys($image_array); // get the keys of image_array

					foreach($image_keys as $image_key) {
						if(!empty($_POST[$image_key])) {
							$chosenImages[$chosenCount] = $_POST[$image_key];
							$chosenCount += 1;
						}
					}

					if(!empty($chosenImages)) {
						foreach($chosenImages as $chosenImage) {
							$add_image = "INSERT INTO albums_pictures (aID, pID) VALUES ($add, $chosenImage)";
							$result = $mysqli->query($add_image);
							if (!$result) {
								echo 'Query error';
								die();
							} else {
								echo 'Image '.$chosenImage.' has been added into album '.$add;
							}
						}
					} else {
						echo 'Please select image.';
					}
				}
			}
		}

		if($deleteId) {
			print('<div class = "title">
				<h1>Remove images</h1>
			</div>');			
			// get current images in this album
			$result = $mysqli->query($query_current_image);

			if(!$result) {
				echo 'Query error';
				die();
			} else {
				$count = 0;
				print("<form method = \"POST\"><div><table>");
				while($row = $result->fetch_assoc()) {
					$current_image_array[$row['pID']] = $row['pID'];
					if($count%4 ==0) print("<tr>");
					print("<td><img class=\"imgSmall\" src=\"{$row['pURL']}{$row['file_name']}\" alt=\"image\">");
					print("<br><input type=\"checkbox\" name=\"{$row['pID']}\" value=\"{$row['pID']}\"></td>");
					$count += 1;
					if($count%4 ==0) print("</tr>");
				}
				print("</table><div></form>");
				print("<p style=\"text-align:center\"><input class = \"button\" type = \"submit\" name = \"submitDelete\" value = \"Delete\"></p>");
				print("<p style = \"text-align:center\"><a href=\"editAlbum.php\">Back to edit albums</a></p>");
			}
		}

		if($edit) {

			print('<div class = "title">
				<h1>Edit Title</h1>
			</div>');			
			$result = $mysqli->query($edit_query);

			if(!$result) {
				echo 'Query error';
				die();
			} else {
				$row = $result->fetch_assoc();
				print("<div id = \"editAlbum-container\"><form method = \"POST\">");
				print("<p style = \"text-align:center\"><label>Title:</label><input type = \"text\" name = \"editAlbum\" value = \"{$row['aTitle']}\"></p>");
				print("<p style = \"text-align:center\"><input class = \"button\" type = \"submit\" name = \"editButton\"></p>");
				print("</form><p style = \"text-align:center\"><a href=\"editAlbum.php\">Back to edit albums</a></p></div>");
			}

		}
		print $message;
	} else {
		print('<p><a href = "logIn.php">Please log in</a><p>');
	}

	?>

</body>

</html>