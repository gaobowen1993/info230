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
		// get edit for editing
		$edit = filter_input(INPUT_GET, 'edit', FILTER_SANITIZE_NUMBER_INT);

		$imgDetail = $mysqli->query("SELECT * FROM pictures WHERE pID=$pID");
		$imgAlbum = $mysqli->query("SELECT a.aTitle FROM albums a INNER JOIN albums_pictures ap ON a.aID=ap.aID WHERE ap.pID=$pID");
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
		if(empty($pID)&&empty($edit)){

			print("<table id='images'>");
				$count = 0;
				while($row = $result->fetch_assoc()) {
					if($count%4 == 0) print("<tr>");
					print("<td><img class= \"imgSmall\" src=\"{$row['pURL']}{$row['file_name']}\" alt=\"image\">
						<br><p style=\"float:left\"><a href=\"?id={$row['pID']}\">View Details</a></p><p style=\"float:right\"><a href =\"?edit={$row['pID']}\">Edit</a></p>
						</td>");
					//print("<td><img id= \"imgBig\" src=\"\" alt=\"\"></td>");
					$count += 1;
					if($count%4 == 0) print("</tr>");
				}

				print("</table>");
				print("<p><a href = \"album.php\">View All Albums</a></p>");
			print("</table>");			
		}
		if(!empty($pID)) {
			if(!$imgDetail) {
				echo 'Query error';
				die();
			} else {
				$row = $imgDetail->fetch_assoc();
				$belongAlbum;
				while($row_album = $imgAlbum->fetch_assoc()){
					$belongAlbum .= $row_album['aTitle'].'<br>';
				}

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
				echo 'success';
			} else {
				echo '<p><a href="logIn.php">Please log in</a></p>';
			}
		}
 	?>

</body>

</html>

	