<!DOCTYPE html>
<html>

<head>
	<meta charset = "UTF-8">
	<link type = "text/css" rel = "stylesheet" href = "../css/style.css">
	<title>Search Pictures</title>
</head>
	<?php
		$keyword = filter_input(INPUT_POST, 'searchField', FILTER_SANITIZE_STRING);
		echo $keyword;
		if(empty($keyword)) {
			echo 'invalid input';
		} else {
			require '../config.php';
			require 'connect.php';
			echo "SELECT * FROM pictures WHERE pCaption LIKE '%".$keyword."%'
								OR pURL LIKE '%".$keyword."%' OR file_name LIKE '%".$keyword."%'
								OR pDesc LIKE '%".$keyword."%' OR pCredit LIKE '%".$keyword."%'";
			$result = $mysqli->query("SELECT * FROM pictures WHERE pCaption LIKE '%".$keyword."%'
								OR pURL LIKE '%".$keyword."%' OR file_name LIKE '%".$keyword."%'
								OR pDesc LIKE '%".$keyword."%' OR pCredit LIKE '%".$keyword."%'");			
		}

	?>

<body>
	<?php
		require 'header.php';
	?>

	<?php
		print("<div class = 'title'>");
		print("<h1>Search Pictures</h1>");
		print("</div>");
		print("<div id = 'searchPic'><form method = 'POST'>");
		print("<p><input type = 'text' name = 'searchField'></p>");
		print("<p><input type = 'submit' name = 'searchSubmit' value = 'Submit'>");
		print("</form></div>");

		if(!empty($result)){
			$count = 0;
			print("<table>");
			while($row = $result->fetch_assoc()){
				if($count%3 ==0) {print("<tr>");}
				print("<td><img class = 'image' src=\"{$row['pURL']}{$row['file_name']}\" alt ='image'></td>");
				$count += 1;
				if($count%3 ==0) {print("</tr>");}
			}
			print("</table>");
		}
	?>
</body>

</html>