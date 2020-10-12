<?php

		session_start();

		if(!isset($_SESSION['zalogowany'])) //to wkleic do kazdego skryptu, gdzie moze byc tylko zalogowany user
		{
			header('Location: index.php');
			exit(); //konczymy wykonywanie kodu
		}
?>

<!DOCTYPE HTML>
<html lang='pl'>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1"/>
	<title>BIBLIOTEX</title>
	<link rel="stylesheet" href="style2.css"></link>
</head>

<style>
ul {
list-style-type: none;
margin: 0;
padding: 0;
overflow: hidden;
background-color: #666699;
}

li {
float: left;
}

li a {
display: block;
color: white;
text-align: center;
padding: 16px;
text-decoration: none;
}

li:last-child{float: right;}

li a:not(.noclick):hover {
background-color: #483D8B;
}
</style>
</head>

<body>
<ul>
<li><a class="noclick">MENU</a></li>
<li><a href="panel.php">Strona główna</a></li>
<li><a href="wyszukiwanie.php">Wyszukiwanie</a></li>
<li><a href="aktualne_rezerwacje.php">Moje rezerwacje</a></li>
<li><a href="wyloguj.php">Wyloguj się</a></li>
</ul>

<form class="box">
	<h1>
		<?php
		echo "<p>Witaj, <b>".$_SESSION['imie'].' :) </b> </p>';
		?>
	</h1>
	<img src="books.png" width="600"
         height="500">
</form>





</body>
</html>
