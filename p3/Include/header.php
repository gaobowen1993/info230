<nav>
		<ul>
			<li><a href = "index.php">Home</a></li>
			<li><a href = "album.php">Ablums</a></li>
			<li><a href = "image.php">Image</a></li>
			<li><a href = 'search.php'>Search</a></li>
			<li><a href = 'add.php'>Add</a></li>
			<?php
			if(isset($_SESSION['loggedUser'])){
				print('<li class = "dropdown" style = "float:right"><a href = "#" class = "dropbtn">'.$_SESSION["loggedUser"].'</a><div class = "dropdown-content"><a href = "logIn.php?state=0" class = "logOut">Log Out</a></div></li>');
			} else print('<li style = "float:right"><a href = "logIn.php?state=1">Log In</a></li>');
			?>
		</ul>
</nav>