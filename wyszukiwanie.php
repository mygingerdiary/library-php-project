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

<form class="search" method="post" action="wyszukiwanie.php">
			<select class="f1" name="f1">
				<option value="">Wybierz filtr</option>
				<option value="autor">Autor</option>
				<option value="dostepny">Dostępny</option>
			</select>
			<select class="f1" name="f2">
				<option value="">Wybierz kategorie</option>
				<option value="fantasy">Fantasy</option>
				<option value="dla dzieci">Dla dzieci</option>
				<option value="powiesc">Powieść</option>
				<option value="kryminal">Kryminał</option>
				<option value="lektury">Lektury</option>
			</select>
			<input type="text" name="napis" placeholder="Wyszukaj...">
			<button type="submit" name="submit"><i class="fa fa-search"></i></button>

</form>

<?php
if (isset($_POST['submit']))
{
	require_once "connect.php";

	$polaczenie=@new mysqli($host, $db_user, $db_password, $db_name);

	$napis = $polaczenie->real_escape_string($_POST['napis']);
	$f1 = $polaczenie->real_escape_string($_POST['f1']);
	$f2 = $polaczenie->real_escape_string($_POST['f2']);

	//////////// nic
	if($f1 == "" && $f2 == "" && strlen($napis) > 0)
	{
		$sql = @$polaczenie->query("SELECT pozycje.ISBN as ISBN, tytul, imie, nazwisko FROM pozycje JOIN pozycje_autorzy ON pozycje.ISBN = pozycje_autorzy.ISBN JOIN autorzy ON autorzy.id=pozycje_autorzy.autor WHERE tytul LIKE '%$napis%' OR imie LIKE '%$napis%' OR nazwisko LIKE '%$napis%' ORDER BY 3");
	}
	if($f1 == "" && $f2 == "" && strlen($napis) == 0)
	{
		$sql = @$polaczenie->query("SELECT * FROM pozycje WHERE 1 = 0");
	}
	//////////// autor
	if($f1 == "autor" && $f2 == "" && strlen($napis) == 0)
	{
		$sql = @$polaczenie->query("SELECT * FROM pozycje WHERE 1 = 0");
	}
	if($f1 == "autor" && $f2 == "" && strlen($napis) > 0)
	{
		$sql = @$polaczenie->query("SELECT pozycje.ISBN as ISBN, tytul, imie, nazwisko FROM pozycje JOIN pozycje_autorzy ON pozycje.ISBN = pozycje_autorzy.ISBN JOIN autorzy ON autorzy.id=pozycje_autorzy.autor WHERE imie LIKE '%$napis%' or nazwisko LIKE '%$napis%' ORDER BY 3");
	}
	///////////// dostepny
	if($f1 == "dostepny" && $f2 == "" && strlen($napis) == 0)
	{
		$sql = @$polaczenie->query("SELECT pozycje.ISBN as ISBN, tytul, imie, nazwisko FROM pozycje JOIN pozycje_autorzy ON pozycje.ISBN = pozycje_autorzy.ISBN JOIN autorzy ON autorzy.id=pozycje_autorzy.autor JOIN egzemplarze ON egzemplarze.pozycja_id=pozycje.ISBN JOIN statusy ON egzemplarze.statusy_id=statusy.id WHERE statusy.nazwa='dostepny' GROUP BY 1 ORDER BY 3");
	}
	if($f1 == "dostepny" && $f2 == "" && strlen($napis) > 0)
	{
		$sql = @$polaczenie->query("SELECT pozycje.ISBN as ISBN, tytul, imie, nazwisko FROM pozycje JOIN pozycje_autorzy ON pozycje.ISBN = pozycje_autorzy.ISBN JOIN autorzy ON autorzy.id=pozycje_autorzy.autor JOIN egzemplarze ON egzemplarze.pozycja_id=pozycje.ISBN JOIN statusy ON egzemplarze.statusy_id=statusy.id WHERE statusy.nazwa='dostepny' AND (tytul LIKE '%$napis%' OR imie LIKE '%$napis%' OR nazwisko LIKE '%$napis%') GROUP BY 1 ORDER BY 3");
	}
	///////////// fantasy
	if($f1 == "" && $f2 == "fantasy" && strlen($napis) == 0)
	{
		$sql = @$polaczenie->query("SELECT pozycje.ISBN as ISBN, tytul, imie, nazwisko, nazwa FROM pozycje JOIN pozycje_autorzy ON pozycje.ISBN = pozycje_autorzy.ISBN JOIN autorzy ON autorzy.id=pozycje_autorzy.autor JOIN pozycje_kategorie ON pozycje.ISBN = pozycje_kategorie.ISBN JOIN kategorie ON pozycje_kategorie.kategoria=kategorie.nazwa WHERE nazwa = 'fantasy' ORDER BY 3");
	}
	if($f1 == "" && $f2 == "fantasy" && strlen($napis) > 0)
	{
		$sql = @$polaczenie->query("SELECT pozycje.ISBN as ISBN, tytul, imie, nazwisko, nazwa FROM pozycje JOIN pozycje_autorzy ON pozycje.ISBN = pozycje_autorzy.ISBN JOIN autorzy ON autorzy.id=pozycje_autorzy.autor JOIN pozycje_kategorie ON pozycje.ISBN = pozycje_kategorie.ISBN JOIN kategorie ON pozycje_kategorie.kategoria=kategorie.nazwa WHERE nazwa = 'fantasy' AND (tytul LIKE '%$napis%' OR imie LIKE '%$napis%' OR nazwisko LIKE '%$napis%') ORDER BY 3 ");
	}
	////////// lektury
	if($f1 == "" && $f2 == "lektury" && strlen($napis) == 0)
	{
		$sql = @$polaczenie->query("SELECT pozycje.ISBN as ISBN, tytul, imie, nazwisko, nazwa FROM pozycje JOIN pozycje_autorzy ON pozycje.ISBN = pozycje_autorzy.ISBN JOIN autorzy ON autorzy.id=pozycje_autorzy.autor JOIN pozycje_kategorie ON pozycje.ISBN = pozycje_kategorie.ISBN JOIN kategorie ON pozycje_kategorie.kategoria=kategorie.nazwa WHERE nazwa = 'lektury' ORDER BY 3 ");
	}
	if($f1 == "" && $f2 == "lektury" && strlen($napis) > 0)
	{
		$sql = @$polaczenie->query("SELECT pozycje.ISBN as ISBN, tytul, imie, nazwisko, nazwa FROM pozycje JOIN pozycje_autorzy ON pozycje.ISBN = pozycje_autorzy.ISBN JOIN autorzy ON autorzy.id=pozycje_autorzy.autor JOIN pozycje_kategorie ON pozycje.ISBN = pozycje_kategorie.ISBN JOIN kategorie ON pozycje_kategorie.kategoria=kategorie.nazwa WHERE nazwa = 'lektury' AND (tytul LIKE '%$napis%' OR imie LIKE '%$napis%' OR nazwisko LIKE '%$napis%') ORDER BY 3 ");
	}
	///////////// dla dzieci
	if($f1 == "" && $f2 == "dla dzieci" && strlen($napis) == 0)
	{
		$sql = @$polaczenie->query("SELECT pozycje.ISBN as ISBN, tytul, imie, nazwisko, nazwa FROM pozycje JOIN pozycje_autorzy ON pozycje.ISBN = pozycje_autorzy.ISBN JOIN autorzy ON autorzy.id=pozycje_autorzy.autor JOIN pozycje_kategorie ON pozycje.ISBN = pozycje_kategorie.ISBN JOIN kategorie ON pozycje_kategorie.kategoria=kategorie.nazwa WHERE nazwa = 'dla dzieci' ORDER BY 3 ");
	}
	if($f1 == "" && $f2 == "dla dzieci" && strlen($napis) > 0)
	{
		$sql = @$polaczenie->query("SELECT pozycje.ISBN as ISBN, tytul, imie, nazwisko, nazwa FROM pozycje JOIN pozycje_autorzy ON pozycje.ISBN = pozycje_autorzy.ISBN JOIN autorzy ON autorzy.id=pozycje_autorzy.autor JOIN pozycje_kategorie ON pozycje.ISBN = pozycje_kategorie.ISBN JOIN kategorie ON pozycje_kategorie.kategoria=kategorie.nazwa WHERE nazwa = 'dla dzieci' AND (tytul LIKE '%$napis%' OR imie LIKE '%$napis%' OR nazwisko LIKE '%$napis%') ORDER BY 3 ");
	}
	////////// kryminał
	if($f1 == "" && $f2 == "kryminal" && strlen($napis) == 0)
	{
		$sql = @$polaczenie->query("SELECT pozycje.ISBN as ISBN, tytul, imie, nazwisko, nazwa FROM pozycje JOIN pozycje_autorzy ON pozycje.ISBN = pozycje_autorzy.ISBN JOIN autorzy ON autorzy.id=pozycje_autorzy.autor JOIN pozycje_kategorie ON pozycje.ISBN = pozycje_kategorie.ISBN JOIN kategorie ON pozycje_kategorie.kategoria=kategorie.nazwa WHERE nazwa = 'kryminal' ORDER BY 3 ");
	}
	if($f1 == "" && $f2 == "kryminal" && strlen($napis) > 0)
	{
		$sql = @$polaczenie->query("SELECT pozycje.ISBN as ISBN, tytul, imie, nazwisko, nazwa FROM pozycje JOIN pozycje_autorzy ON pozycje.ISBN = pozycje_autorzy.ISBN JOIN autorzy ON autorzy.id=pozycje_autorzy.autor JOIN pozycje_kategorie ON pozycje.ISBN = pozycje_kategorie.ISBN JOIN kategorie ON pozycje_kategorie.kategoria=kategorie.nazwa WHERE nazwa = 'kryminal' AND (tytul LIKE '%$napis%' OR imie LIKE '%$napis%' OR nazwisko LIKE '%$napis%') ORDER BY 3 ");
	}
	////////// powieść
	if($f1 == "" && $f2 == "powiesc" && strlen($napis) == 0)
	{
		$sql = @$polaczenie->query("SELECT pozycje.ISBN as ISBN, tytul, imie, nazwisko, nazwa FROM pozycje JOIN pozycje_autorzy ON pozycje.ISBN = pozycje_autorzy.ISBN JOIN autorzy ON autorzy.id=pozycje_autorzy.autor JOIN pozycje_kategorie ON pozycje.ISBN = pozycje_kategorie.ISBN JOIN kategorie ON pozycje_kategorie.kategoria=kategorie.nazwa WHERE nazwa = 'powiesc' ORDER BY 3 ");
	}
	if($f1 == "" && $f2 == "powiesc" && strlen($napis) > 0)
	{
		$sql = @$polaczenie->query("SELECT pozycje.ISBN as ISBN, tytul, imie, nazwisko, nazwa FROM pozycje JOIN pozycje_autorzy ON pozycje.ISBN = pozycje_autorzy.ISBN JOIN autorzy ON autorzy.id=pozycje_autorzy.autor JOIN pozycje_kategorie ON pozycje.ISBN = pozycje_kategorie.ISBN JOIN kategorie ON pozycje_kategorie.kategoria=kategorie.nazwa WHERE nazwa = 'powiesc' AND (tytul LIKE '%$napis%' OR imie LIKE '%$napis%' OR nazwisko LIKE '%$napis%') ORDER BY 3 ");
	}
	//////////// autor+fantasy
	if($f1 == "autor" && $f2 == "fantasy" && strlen($napis) == 0)
	{
		$sql=@$polaczenie->query("SELECT * FROM pozycje WHERE 1 = 0");
	}
	if($f1 == "autor" && $f2 == "fantasy" && strlen($napis) > 0)
	{
		$sql = @$polaczenie->query("SELECT pozycje.ISBN as ISBN, tytul, imie, nazwisko, nazwa FROM pozycje JOIN pozycje_autorzy ON pozycje.ISBN = pozycje_autorzy.ISBN JOIN autorzy ON autorzy.id=pozycje_autorzy.autor JOIN pozycje_kategorie ON pozycje.ISBN = pozycje_kategorie.ISBN JOIN kategorie ON pozycje_kategorie.kategoria=kategorie.nazwa WHERE nazwa = 'fantasy' AND (imie LIKE '%$napis%' OR nazwisko LIKE '%$napis%') ORDER BY 3 ");
	}
	///////////// autor+lektury
	if($f1 == "autor" && $f2 == "lektury" && strlen($napis) == 0)
	{
		$sql=@$polaczenie->query("SELECT * FROM pozycje WHERE 1 = 0");
	}
	if($f1 == "autor" && $f2 == "lektury" && strlen($napis) > 0)
	{
		$sql = @$polaczenie->query("SELECT pozycje.ISBN as ISBN, tytul, imie, nazwisko, nazwa FROM pozycje JOIN pozycje_autorzy ON pozycje.ISBN = pozycje_autorzy.ISBN JOIN autorzy ON autorzy.id=pozycje_autorzy.autor JOIN pozycje_kategorie ON pozycje.ISBN = pozycje_kategorie.ISBN JOIN kategorie ON pozycje_kategorie.kategoria=kategorie.nazwa WHERE nazwa = 'lektury' AND (imie LIKE '%$napis%' OR nazwisko LIKE '%$napis%') ORDER BY 3 ");
	}
	//////////// autor+kryminał
	if($f1 == "autor" && $f2 == "kryminal" && strlen($napis) == 0)
	{
		$sql=@$polaczenie->query("SELECT * FROM pozycje WHERE 1 = 0");
	}
	if($f1 == "autor" && $f2 == "kryminal" && strlen($napis) > 0)
	{
		$sql = @$polaczenie->query("SELECT pozycje.ISBN as ISBN, tytul, imie, nazwisko, nazwa FROM pozycje JOIN pozycje_autorzy ON pozycje.ISBN = pozycje_autorzy.ISBN JOIN autorzy ON autorzy.id=pozycje_autorzy.autor JOIN pozycje_kategorie ON pozycje.ISBN = pozycje_kategorie.ISBN JOIN kategorie ON pozycje_kategorie.kategoria=kategorie.nazwa WHERE nazwa = 'kryminal' AND (imie LIKE '%$napis%' OR nazwisko LIKE '%$napis%') ORDER BY 3 ");
	}
	///////////// autor+dla dzieci
	if($f1 == "autor" && $f2 == "dla dzieci" && strlen($napis) == 0)
	{
		$sql=@$polaczenie->query("SELECT * FROM pozycje WHERE 1 = 0");
	}
	if($f1 == "autor" && $f2 == "dla dzieci" && strlen($napis) > 0)
	{
		$sql = @$polaczenie->query("SELECT pozycje.ISBN as ISBN, tytul, imie, nazwisko, nazwa FROM pozycje JOIN pozycje_autorzy ON pozycje.ISBN = pozycje_autorzy.ISBN JOIN autorzy ON autorzy.id=pozycje_autorzy.autor JOIN pozycje_kategorie ON pozycje.ISBN = pozycje_kategorie.ISBN JOIN kategorie ON pozycje_kategorie.kategoria=kategorie.nazwa WHERE nazwa = 'dla dzieci' AND (imie LIKE '%$napis%' OR nazwisko LIKE '%$napis%') ORDER BY 3 ");
	}
	///////////// autor+powieść
	if($f1 == "autor" && $f2 == "powiesc" && strlen($napis) == 0)
	{
		$sql=@$polaczenie->query("SELECT * FROM pozycje WHERE 1 = 0");
	}
	if($f1 == "autor" && $f2 == "powiesc" && strlen($napis) > 0)
	{
		$sql = @$polaczenie->query("SELECT pozycje.ISBN as ISBN, tytul, imie, nazwisko, nazwa FROM pozycje JOIN pozycje_autorzy ON pozycje.ISBN = pozycje_autorzy.ISBN JOIN autorzy ON autorzy.id=pozycje_autorzy.autor JOIN pozycje_kategorie ON pozycje.ISBN = pozycje_kategorie.ISBN JOIN kategorie ON pozycje_kategorie.kategoria=kategorie.nazwa WHERE nazwa = 'powiesc' AND (imie LIKE '%$napis%' OR nazwisko LIKE '%$napis%') ORDER BY 3 ");
	}
	//////////// dostepny+fantasy
	if($f1 == "dostepny" && $f2 == "fantasy" && strlen($napis) == 0)
	{
		$sql = @$polaczenie->query("SELECT pozycje.ISBN as ISBN, tytul, imie, nazwisko FROM pozycje JOIN pozycje_autorzy ON pozycje.ISBN = pozycje_autorzy.ISBN JOIN autorzy ON autorzy.id=pozycje_autorzy.autor JOIN egzemplarze ON egzemplarze.pozycja_id=pozycje.ISBN JOIN statusy ON egzemplarze.statusy_id=statusy.id JOIN pozycje_kategorie ON pozycje.ISBN = pozycje_kategorie.ISBN JOIN kategorie ON pozycje_kategorie.kategoria=kategorie.nazwa WHERE statusy.nazwa='dostepny' AND kategorie.nazwa='fantasy' GROUP BY 1 ORDER BY 3 ");
	}
	if($f1 == "dostepny" && $f2 == "fantasy" && strlen($napis) > 0)
	{
		$sql = @$polaczenie->query("SELECT pozycje.ISBN as ISBN, tytul, imie, nazwisko FROM pozycje JOIN pozycje_autorzy ON pozycje.ISBN = pozycje_autorzy.ISBN JOIN autorzy ON autorzy.id=pozycje_autorzy.autor JOIN egzemplarze ON egzemplarze.pozycja_id=pozycje.ISBN JOIN statusy ON egzemplarze.statusy_id=statusy.id JOIN pozycje_kategorie ON pozycje.ISBN = pozycje_kategorie.ISBN JOIN kategorie ON pozycje_kategorie.kategoria=kategorie.nazwa WHERE statusy.nazwa='dostepny' AND kategorie.nazwa='fantasy' AND (tytul LIKE '%$napis%' OR imie LIKE '%$napis%' OR nazwisko LIKE '%$napis%') GROUP BY 1 ORDER BY 3 ");
	}
	////////// dostepny+lektury
	if($f1 == "dostepny" && $f2 == "lektury" && strlen($napis) == 0)
	{
		$sql = @$polaczenie->query("SELECT pozycje.ISBN as ISBN, tytul, imie, nazwisko FROM pozycje JOIN pozycje_autorzy ON pozycje.ISBN = pozycje_autorzy.ISBN JOIN autorzy ON autorzy.id=pozycje_autorzy.autor JOIN egzemplarze ON egzemplarze.pozycja_id=pozycje.ISBN JOIN statusy ON egzemplarze.statusy_id=statusy.id JOIN pozycje_kategorie ON pozycje.ISBN = pozycje_kategorie.ISBN JOIN kategorie ON pozycje_kategorie.kategoria=kategorie.nazwa WHERE statusy.nazwa='dostepny' AND kategorie.nazwa='lektury' GROUP BY 1 ORDER BY 3 ");
	}
	if($f1 == "dostepny" && $f2 == "lektury" && strlen($napis) > 0)
	{
		$sql = @$polaczenie->query("SELECT pozycje.ISBN as ISBN, tytul, imie, nazwisko FROM pozycje JOIN pozycje_autorzy ON pozycje.ISBN = pozycje_autorzy.ISBN JOIN autorzy ON autorzy.id=pozycje_autorzy.autor JOIN egzemplarze ON egzemplarze.pozycja_id=pozycje.ISBN JOIN statusy ON egzemplarze.statusy_id=statusy.id JOIN pozycje_kategorie ON pozycje.ISBN = pozycje_kategorie.ISBN JOIN kategorie ON pozycje_kategorie.kategoria=kategorie.nazwa WHERE statusy.nazwa='dostepny' AND kategorie.nazwa='lektury' AND (tytul LIKE '%$napis%' OR imie LIKE '%$napis%' OR nazwisko LIKE '%$napis%') GROUP BY 1");
	}
	///////// dostepny+kryminał
	if($f1 == "dostepny" && $f2 == "kryminal" && strlen($napis) == 0)
	{
		$sql = @$polaczenie->query("SELECT pozycje.ISBN as ISBN, tytul, imie, nazwisko FROM pozycje JOIN pozycje_autorzy ON pozycje.ISBN = pozycje_autorzy.ISBN JOIN autorzy ON autorzy.id=pozycje_autorzy.autor JOIN egzemplarze ON egzemplarze.pozycja_id=pozycje.ISBN JOIN statusy ON egzemplarze.statusy_id=statusy.id JOIN pozycje_kategorie ON pozycje.ISBN = pozycje_kategorie.ISBN JOIN kategorie ON pozycje_kategorie.kategoria=kategorie.nazwa WHERE statusy.nazwa='dostepny' AND kategorie.nazwa='kryminal' GROUP BY 1 ORDER BY 3 ");
	}
	if($f1 == "dostepny" && $f2 == "kryminal" && strlen($napis) > 0)
	{
		$sql = @$polaczenie->query("SELECT pozycje.ISBN as ISBN, tytul, imie, nazwisko FROM pozycje JOIN pozycje_autorzy ON pozycje.ISBN = pozycje_autorzy.ISBN JOIN autorzy ON autorzy.id=pozycje_autorzy.autor JOIN egzemplarze ON egzemplarze.pozycja_id=pozycje.ISBN JOIN statusy ON egzemplarze.statusy_id=statusy.id JOIN pozycje_kategorie ON pozycje.ISBN = pozycje_kategorie.ISBN JOIN kategorie ON pozycje_kategorie.kategoria=kategorie.nazwa WHERE statusy.nazwa='dostepny' AND kategorie.nazwa='kryminal' AND (tytul LIKE '%$napis%' OR imie LIKE '%$napis%' OR nazwisko LIKE '%$napis%') GROUP BY 1 ORDER BY 3 ");
	}
	/////////// dostepny+powieść
	if($f1 == "dostepny" && $f2 == "powiesc" && strlen($napis) == 0)
	{
		$sql = @$polaczenie->query("SELECT pozycje.ISBN as ISBN, tytul, imie, nazwisko FROM pozycje JOIN pozycje_autorzy ON pozycje.ISBN = pozycje_autorzy.ISBN JOIN autorzy ON autorzy.id=pozycje_autorzy.autor JOIN egzemplarze ON egzemplarze.pozycja_id=pozycje.ISBN JOIN statusy ON egzemplarze.statusy_id=statusy.id JOIN pozycje_kategorie ON pozycje.ISBN = pozycje_kategorie.ISBN JOIN kategorie ON pozycje_kategorie.kategoria=kategorie.nazwa WHERE statusy.nazwa='dostepny' AND kategorie.nazwa='powiesc' GROUP BY 1 ORDER BY 3 ");
	}
	if($f1 == "dostepny" && $f2 == "powiesc" && strlen($napis) > 0)
	{
		$sql = @$polaczenie->query("SELECT pozycje.ISBN as ISBN, tytul, imie, nazwisko FROM pozycje JOIN pozycje_autorzy ON pozycje.ISBN = pozycje_autorzy.ISBN JOIN autorzy ON autorzy.id=pozycje_autorzy.autor JOIN egzemplarze ON egzemplarze.pozycja_id=pozycje.ISBN JOIN statusy ON egzemplarze.statusy_id=statusy.id JOIN pozycje_kategorie ON pozycje.ISBN = pozycje_kategorie.ISBN JOIN kategorie ON pozycje_kategorie.kategoria=kategorie.nazwa WHERE statusy.nazwa='dostepny' AND kategorie.nazwa='powiesc' AND (tytul LIKE '%$napis%' OR imie LIKE '%$napis%' OR nazwisko LIKE '%$napis%') GROUP BY 1 ORDER BY 3 ");
	}
	////////// dostepny+dla dla_dzieci
	if($f1 == "dostepny" && $f2 == "dla dzieci" && strlen($napis) == 0)
	{
		$sql = @$polaczenie->query("SELECT pozycje.ISBN as ISBN, tytul, imie, nazwisko FROM pozycje JOIN pozycje_autorzy ON pozycje.ISBN = pozycje_autorzy.ISBN JOIN autorzy ON autorzy.id=pozycje_autorzy.autor JOIN egzemplarze ON egzemplarze.pozycja_id=pozycje.ISBN JOIN statusy ON egzemplarze.statusy_id=statusy.id JOIN pozycje_kategorie ON pozycje.ISBN = pozycje_kategorie.ISBN JOIN kategorie ON pozycje_kategorie.kategoria=kategorie.nazwa WHERE statusy.nazwa='dostepny' AND kategorie.nazwa='dla dzieci' GROUP BY 1 ORDER BY 3 ");
	}
	if($f1 == "dostepny" && $f2 == "dla dzieci" && strlen($napis) > 0)
	{
		$sql = @$polaczenie->query("SELECT pozycje.ISBN as ISBN, tytul, imie, nazwisko FROM pozycje JOIN pozycje_autorzy ON pozycje.ISBN = pozycje_autorzy.ISBN JOIN autorzy ON autorzy.id=pozycje_autorzy.autor JOIN egzemplarze ON egzemplarze.pozycja_id=pozycje.ISBN JOIN statusy ON egzemplarze.statusy_id=statusy.id JOIN pozycje_kategorie ON pozycje.ISBN = pozycje_kategorie.ISBN JOIN kategorie ON pozycje_kategorie.kategoria=kategorie.nazwa WHERE statusy.nazwa='dostepny' AND kategorie.nazwa='dla dzieci' AND (tytul LIKE '%$napis%' OR imie LIKE '%$napis%' OR nazwisko LIKE '%$napis%') GROUP BY 1 ORDER BY 3 ");
	}

	$_SESSION['ISBN']=array();

		echo "<div class='myDiv'>";

		if ($sql->num_rows > 0)
		{
			while ($data = $sql->fetch_assoc())
			{
				array_push($_SESSION['ISBN'], $data['ISBN']);
				echo $data['imie'].' '.$data['nazwisko'].' "'.$data['tytul'] . '" <form method="post" action="rezerwacja.php"><input type="submit" name="'.$data['ISBN'].'" value="Rezerwuj"></form>'.'</br></br>';
			}
		}
		else
				echo "Brak wyszukiwań!";

		echo "</div>";

}
?>

</body>
</html>
