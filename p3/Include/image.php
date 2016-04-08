<!DOCTYPE html>
<html>

<head>
	<meta charset = "UTF-8">
	<link type = "text/css" rel = "stylesheet" href = "../css/style.css">
	<script src='https://code.jquery.com/jquery-1.10.2.js'></script>
	<script src='../js/picture.js'></script>
	<title>Image Page</title>
</head>

<body>

	<?php
		require("header.php");
		//add_versioned_file( '../js/picture.js', 'JavaScript' );
		print("<script src='https://code.jquery.com/jquery-1.10.2.js'></script>");
		print("<script src='../js/picture.js'></script>");
	?>

	<div class = "title">
		<h1>Image Collections</h1>
	</div>

	<?php
		print("<div id='addPic'><form action='add.php?id=pic' method='post'>");
		print("<p>Click the button to add picture: <input type='submit' name='save_picture' value='Add Picture'></p>");
		print("</form></div>");
	?>

	<?php
		require("../config.php");
		// open database
		require("connect.php");		
		$result = $mysqli->query("SELECT * FROM pictures");

/*		print("<div class = \"table-container\"><table id='images'><thead><tr><th>Pic</th><th>Caption</th><th>Description</th><th>Credit</th></thead><tbody>");

		while($row = $result->fetch_assoc()){
				$caption = $row['pCaption'];
				$pURL = $row['pURL'];
				$file_name = $row['file_name'];
				$description = $row['pDesc'];
				$credit = $row['pCredit'];
				print("<tr>
						<td><img class = \"image\" src = \"$pURL$file_name\" alt = \"image\"></td>
						<td>$caption</td>
						<td>$description</td>
						<td>$credit</td>
						</tr>");
		}
		print("</tbody></table></div>");
*/
		print("<table id='images'>");
			$count = 0;
			while($row = $result->fetch_assoc()) {
				if($count%4 == 0) print("<tr>");
				print("<td><a href=\"details.php?id={$row['pID']}\"><img class= \"imgSmall\" src=\"{$row['pURL']}{$row['file_name']}\" alt=\"image\"></a></td>");
				//print("<td><img id= \"imgBig\" src=\"\" alt=\"\"></td>");
				$count += 1;
				if($count%4 == 0) print("</tr>");
			}

			print("</table>");
			print("<p><a href = \"album.php\">View All Albums</a></p>");
		print("</table>");

	?>

</body>

</html>

	