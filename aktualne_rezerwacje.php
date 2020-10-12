<?php

		session_start();

		if(!isset($_SESSION['zalogowany'])) //to wkleic do kazdego skryptu, gdzie moze byc tylko zalogowany user
		{
			header('Location: index.php');
			exit(); //konczymy wykonywanie kodu
		}

		require_once "connect.php";

		$polaczenie=@new mysqli($host, $db_user, $db_password, $db_name);

		$id=$_SESSION['id'];
		$sql  = @$polaczenie->query("SELECT rezerwacje.id as id_rezerwacji, id_osoba, data_rezerwacji, data_odbioru, nazwa, tytul, CONCAT(imie,' ', nazwisko) as autor FROM rezerwacje JOIN statusy ON rezerwacje.id_status=statusy.id JOIN egzemplarze ON rezerwacje.id_egzemplarza=egzemplarze.id JOIN pozycje ON egzemplarze.pozycja_id=pozycje.ISBN JOIN pozycje_autorzy ON pozycje.ISBN=pozycje_autorzy.ISBN JOIN autorzy ON pozycje_autorzy.autor=autorzy.id WHERE id_osoba='$id' ORDER BY data_rezerwacji DESC");

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

table, td, th {
  border: 1px solid black;
  border-collapse: collapse;
	margin-left: auto;
	margin-right: auto;
	margin-top: auto;
	text-align: center;
}
th {
	background-color: #eee6ff;
}

h1{
	margin-left: auto;
	margin-right: auto;
	margin-top: 20px;
	text-align: center;
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

<h1>REZERWACJE</h1>
<table style="width:1100px">
  <tr>
    <th>Autor</th>
    <th>Tytuł</th>
    <th>Data rezerwacji</th>
		<th>Max. data odbioru</th>
		<th>Status</th>
  </tr>
	<?php
	$_SESSION['rezerwacje_potwierdzone']=array();
	$_SESSION['rezerwacje_niepotwierdzone']=array();
	if ($sql->num_rows > 0)
	{
		while ($data = $sql->fetch_assoc())
		{
			if($data['nazwa']=='potwierdzony')
			{
				array_push($_SESSION['rezerwacje_potwierdzone'], $data['id_rezerwacji']);
				echo "<tr><td>".$data['autor']."</td><td>".$data['tytul']."</td><td>".$data['data_rezerwacji']."</td><td>".$data['data_odbioru']."</td><td>".$data['nazwa']."</td><td>".'<form method="post" action="anulowanie.php"><input type="submit" name="'.$data['id_rezerwacji'].'" value="anuluj"></form>'."</td></tr>";
				continue;
			}
			if($data['nazwa']=='niepotwierdzony')
			{
				array_push($_SESSION['rezerwacje_niepotwierdzone'], $data['id_rezerwacji']);
				echo "<tr><td>".$data['autor']."</td><td>".$data['tytul']."</td><td>".$data['data_rezerwacji']."</td><td>".$data['data_odbioru']."</td><td>".$data['nazwa']."</td><td>".'<form method="post" action="anulowanie.php"><input type="submit" name="'.$data['id_rezerwacji'].'" value="potwierdź"></form>'."</td></tr>";
				continue;
			}
			else
			{
				echo "<tr><td>".$data['autor']."</td><td>".$data['tytul']."</td><td>".$data['data_rezerwacji']."</td><td>".$data['data_odbioru']."</td><td>".$data['nazwa']."</td><td></td></tr>";
			}
		}
		echo "</table>";
	}
	else {
		echo "<div class='myDiv2'>";
		echo "Brak historii rezerwacji";
		echo "</div>";
	}
	?>

</table>

</body>

</html>
