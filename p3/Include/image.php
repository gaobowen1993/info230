<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
	<meta charset = "UTF-8">
	<link type = "text/css" rel = "stylesheet" href = "../css/style.css">
	<title>Image Page</title>
</head>

<?php
		require("../config.php");
		// open database
		require("connect.php");

		$result = $mysqli->query("SELECT * FROM pictures");
		// get id for details display
		$pID = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
		// get id for editing
		$edit = filter_input(INPUT_GET, 'edit', FILTER_SANITIZE_NUMBER_INT);
		// get id for deleting
		$delete = filter_input(INPUT_GET, 'delete', FILTER_SANITIZE_NUMBER_INT);

		// validate user input
		$caption = filter_input(INPUT_POST, 'caption', FILTER_SANITIZE_STRING);
		$description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
		$credit = filter_input(INPUT_POST, 'credit', FILTER_SANITIZE_STRING);

		$message='';

		// save button is clicked
		if(!empty($_POST['saveEdit'])) {

			$result_one = $mysqli->query("UPDATE pictures SET pCaption = '$caption' WHERE pID = $edit");
			$result_two = $mysqli->query("UPDATE pictures SET pDesc = '$description' WHERE pID = $edit");
			$result_three = $mysqli->query("UPDATE pictures SET pCredit = '$credit' WHERE pID = $edit");

			if((!$result_one)||(!$result_two)||(!$result_three)) {
				$message.= 'Query error';
				die();
			} else {
				$message.= 'Edit sucess.';
			}
		}

		// delete button is clicked
		if(!empty($_POST['delete'])) {

			$delete_query = 'DELETE FROM pictures WHERE pID='.$delete;
			$delete_result = $mysqli->query($delete_query);

			if(!$delete_result) {
				$message.= 'Query error';
				die();
			} else {
				$message.= 'Delete sucess';
			}
		}		

?>

<body>

	<?php
		require("header.php");
		//print("<script src='https://code.jquery.com/jquery-1.10.2.js'></script>");
		//print("<script src='../js/picture.js'></script>");
	?>

	<div class = "title">
		<h1>Image Collections</h1>
	</div>

	<?php
		if(empty($pID)&&empty($edit)&&empty($delete)){

			print("<table id='images'>");
				$count = 0;
				while($row = $result->fetch_assoc()) {
					if($count%4 == 0) print("<tr>");
					print("<td><a href=\"?id={$row['pID']}\"><img class= \"imgSmall\" src=\"{$row['pURL']}{$row['file_name']}\" alt=\"image\"></a>
						<br><p><label style = \"width:50px;\"><a href =\"?edit={$row['pID']}\">Edit</a></label>
						<label style = \"width:100px;\"><a href =\"?delete={$row['pID']}\">Delete</a></label></p>
						</td>");
					$count += 1;
					if($count%4 == 0) print("</tr>");
				}

				print("</table>");
			print("</table>");			
		}
		if(!empty($pID)) {

			// get records for details display
			$imgDetail = $mysqli->query("SELECT * FROM pictures WHERE pID=$pID");

			if(!$imgDetail) {
				echo 'Query error';
				die();
			} else {
				$row = $imgDetail->fetch_assoc();

				// get the album of this image
				$imgAlbum = $mysqli->query("SELECT a.aTitle FROM albums a INNER JOIN albums_pictures ap ON a.aID=ap.aID WHERE ap.pID=$pID");

				$belongAlbum; // albums that image belongs to

				// get albums image belongs to
				while($row_album = $imgAlbum->fetch_assoc()){
					$belongAlbum .= $row_album['aTitle'].'<br>';
				}					

				// set the ablum field to None if the image belongs to no album	
				if(empty($belongAlbum)) $belongAlbum = 'No album';

				print("<table id='imgDetail'><th>Caption</th><th>Description</th><th>Credit</th><th>Album</th>");
				print("<tr><td>{$row['pCaption']}</td><td>{$row['pDesc']}</td><td>{$row['pCredit']}</td><td>$belongAlbum</td></tr>");
				print("</table>");
				print("<img class=\"imgBig-container\" src=\"{$row['pURL']}{$row['file_name']}\" alt=\"image\">");
				print("<p style=\"margin:auto;text-align:center\"><a href=\"image.php\">Back to image collections</a></p>");
			}

		}
		if(!empty($edit)) {
			if(isset($_SESSION['loggedUser'])) {

				// get records for image editing
				$imgEdit = $mysqli->query("SELECT * FROM pictures WHERE pID=$edit");
				
				if(!$imgEdit) {
					echo 'Query error';
					die();
				}

				$row = $imgEdit->fetch_assoc();

				print("<div id = \"editImage-container\"><form method = \"POST\">");
				print("<p><label>Caption: </label><input type = \"text\" name= \"caption\" value= \"{$row['pCaption']}\"></p>");
				print("<p><label>Description: </label><input type = \"text\" name=\"description\" value= \"{$row['pDesc']}\"></p>");
				print("<p><label>Credit: </label><input type = \"text\" name=\"credit\" value=\"{$row['pCredit']}\"></p>");
				print("<p><input class = \"button\" type = \"submit\" name = \"saveEdit\" value = \"Save\"></p>");
				print("</form><p><a href=\"image.php\">Back to image collections</a></p></div>");
			}
			else {
				echo '<p><a href="logIn.php">Please log in</a></p>';
			}
		}

		if(!empty($delete)) {
			if(!empty($_SESSION['loggedUser'])) {

				if(empty($message)) {
					$query = 'SELECT file_name FROM pictures WHERE pID='.$delete;
					$fileName = $mysqli->query($query);

					if(!$fileName) {
						echo 'Query error';
						die();
					}

					$row = $fileName->fetch_assoc();
					print("<div id = \"deleteImage-container\"><p>Are you sure you want to delete image {$row['file_name']} ?</p>");
					print("<form method = 'POST'>");
					print("<p><input class = \"button\" type = \"submit\" name = \"delete\" value = \"Yes\"><p>");
					print("<p>No I want to go back to image collections <a href = \"image.php\">Click here</a></p>");
					//<input class = \"button\" type = \"submit\" name = \"notDelete\" value = \"No\">
					print("</form></div>");
				}
			} else {
				echo '<p><a href="logIn.php">Please log in</a></p>';
			}
		}

		print $message;
 	?>

</body>

</html>

	