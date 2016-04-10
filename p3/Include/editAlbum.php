<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
	<meta charset = "UTF-8">
	<link type = "text/css" rel = "stylesheet" href = "../css/style.css">
	<title>Album Page</title>
</head>

	<?php
		require '../config.php';
		require 'connect.php';
		$query = 'SELECT * FROM albums';
		$add = filter_input(INPUT_GET, 'add', FILTER_SANITIZE_NUMBER_INT); // get the added album id
		$deleteId = filter_input(INPUT_GET, 'deleteId', FILTER_SANITIZE_NUMBER_INT);
		$query_image = 'SELECT * FROM pictures';
		$query_current_image = 'SELECT * FROM pictures p INNER JOIN albums_pictures ap ON p.pID = ap.pID WHERE ap.aID ='.$deleteId;

		$image_array = array();
		$current_image_array = array();

	?>

<body>

	<?php
		require("header.php");
	?>

	<?php

	if(isset($_SESSION['loggedUser'])) {

		if(empty($add)&&empty($deleteId)) {
			$result = $mysqli->query($query);
		
			if(!$result) {
				echo 'Query error';
				die();
			} else {
				print('<div><table>');
				while($row = $result->fetch_assoc()) {
					print("<tr><td>{$row['aTitle']}</td>
						<td><a href=\"?add={$row['aID']}\">Add image</a></td>
						<td><a href=\"?deleteId={$row['aID']}\">Remove image</a></td>
						</tr>");
				}
				print('</table></div>');
			}
		}

		if($add) {
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
				print("<p style = \"text-align:center\"><input type = 'submit' name = 'submitAdd' value = 'Add'></p>");
				print("</form>");

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
				print("<p style=\"text-align:center\"><input type = \"submit\" name = \"submitDelete\" value = \"Delete\"></p>");
			}

			if(!empty($_POST['submitDelete'])) {

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
								echo 'Query error';
								die();
							} else {
								echo 'You have delete picture '.$chosenImage.' from album '.$deleteId;
							}
						}
					} else {
						echo 'Please select an image.';
					}
				} else {
					echo 'No images in this album.';
				}
			}

		}

	} else {
		print('<p><a href = "logIn.php">Please log in</a><p>');
	}

	?>

</body>

</html>