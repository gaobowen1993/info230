<?php session_start(); print_r($_SESSION)?>
<!DOCTYPE html>
<html>

<head>
	<meta charset = "UTF-8">
	<link type = "text/css" rel = "stylesheet" href = "../css/style.css">
	<title>Image Page</title>
</head>

<body>

	<?php
		//if()
		require("header.php");
	?>

	<div class = "title">
		<h1>Architecture</h1>
	</div>

	<div id = "background-container">
		<img id = "background" src = "../image/bird_nest.jpg" alt = "bird_nest">
	</div>

	<p id = "source">ImageSource:blog.sina.com.cn</p>

</body>

</html>