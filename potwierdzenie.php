<?php

		session_start();

		require_once "connect.php";
		$polaczenie=@new mysqli($host, $db_user, $db_password, $db_name);

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
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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

p {
	width: 1100px;
	padding: 100px;
	position: absolute;
	top: 50%;
	left: 50%;
	transform: translate(-50%, -50%);
	color:  #009933;
	background-color: #eee6ff;
	text-align: center;
	font-size: 40px;
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
</body>

<?php
$id_egzemplarza=$_SESSION['id_egzemplarza'];
$id_osoby=$_SESSION['id'];
$sql2=@$polaczenie->query("INSERT INTO rezerwacje (id, data_odbioru, data_rezerwacji, id_osoba, id_status, id_egzemplarza) VALUES (NULL, CURDATE() + INTERVAL 3 DAY, CURDATE(), $id_osoby, 8, $id_egzemplarza)");

$res=@$polaczenie->query("SELECT max(id) AS id FROM rezerwacje");
while($data3=$res->fetch_assoc())
			{
				$lastID=$data3['id'];
			}
$_SESSION['id_rezerwacji']=$lastID;

$sql3=@$polaczenie->query("UPDATE egzemplarze SET statusy_id = 3 WHERE id = $id_egzemplarza");

//$sql4=@$polaczenie->query("UPDATE rezerwacje SET id_status = 8 WHERE id_egzemplarza = $id_egzemplarza AND id=$id_rezerwacji");

$today = date("d.m.y");
$odbior = date('d.m.y', strtotime('+3 days'));
echo "<p>Udało się dokonać rezerwacji. Czas na odbiór pozycji do ".$odbior."</p>";
header( "Refresh:3; url=http://localhost:8080/biblioteka/wyszukiwanie.php", true, 303);
 ?>

</html>
