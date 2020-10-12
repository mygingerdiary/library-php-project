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

form{
	display: inline-block;
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

</body>

<?php
require_once "connect.php";
$polaczenie=@new mysqli($host, $db_user, $db_password, $db_name);
$id_osoby=$_SESSION['id'];

$resultat=@$polaczenie->query("SELECT count(*) AS ile FROM rezerwacje WHERE id_osoba=$id_osoby AND id_status='8'");
while($data2=$resultat->fetch_assoc())
{
	$ile=$data2['ile'];
}

for($i = 0 ; $i < count($_SESSION['ISBN']) ; $i++)
{
		if(isset($_POST[$_SESSION['ISBN'][$i]]))
		{
			$ISBN=$_SESSION['ISBN'][$i];
			$sql=@$polaczenie->query("SELECT tytul, id, statusy_id FROM pozycje JOIN egzemplarze ON pozycje.ISBN=egzemplarze.pozycja_id WHERE ISBN=$ISBN AND statusy_id='1'");
			break;
		}
}

$_SESSION['ile']=$ile;

if($_SESSION['ile'] < 3)
{
	if ($sql->num_rows > 0)
	{
		while($data = $sql->fetch_assoc())
		{
			$_SESSION['id_egzemplarza']=$data['id'];
		}
		echo "<div class='myDiv3'>";
		echo "Czy chcesz potwierdzić rezerwacje?".'</br>';
		echo '<form method="post" action="potwierdzenie.php"><input type="submit" name="tak" value="TAK"></form><form method="post" action="niepotwierdzenie.php"><input type="submit" name="nie" value="NIE"></form>';
		echo "</div>";
	}
	else
	{
		echo "<div class='myDiv4'>";
		echo "Brak dostępnych egzemplarzy";
		echo "</div>";
		header( "Refresh:3; url=http://localhost:8080/biblioteka/wyszukiwanie.php", true, 303);
	}
}
else {
	echo "<div class='myDiv4'>";
	echo "Możliwa liczba rezerwacji wynosi 3";
	echo "</div>";
	header( "Refresh:3; url=http://localhost:8080/biblioteka/wyszukiwanie.php", true, 303);
}

?>
