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

	for($i = 0 ; $i < count($_SESSION['rezerwacje_potwierdzone']) ; $i++)
	{
			if(isset($_POST[$_SESSION['rezerwacje_potwierdzone'][$i]]))
			{
				$rezerwacja=$_SESSION['rezerwacje_potwierdzone'][$i];
				$sql2=@$polaczenie->query("SELECT id_egzemplarza FROM rezerwacje WHERE id=$rezerwacja");
				while($data=$sql2->fetch_assoc())
				{
					$egzemplarz=$data['id_egzemplarza'];
				}
				$sql= @$polaczenie->query("UPDATE rezerwacje SET id_status = 10 WHERE id=$rezerwacja");
				$sql3 = @$polaczenie->query("UPDATE egzemplarze SET statusy_id = 1 WHERE id = $egzemplarz");
				$_SESSION['ile']-=1;
				header('Location: aktualne_rezerwacje.php');
			}
		}

	 for($i = 0 ; $i < count($_SESSION['rezerwacje_niepotwierdzone']) ; $i++)
	 {
		 if(isset($_POST[$_SESSION['rezerwacje_niepotwierdzone'][$i]]))
		 {
			 $rezerwacja=$_SESSION['rezerwacje_niepotwierdzone'][$i];
			 $_SESSION['niepotwierdzona']=$rezerwacja;
			 if($_SESSION['ile'] < 3)
			 {
				 echo "<div class='myDiv3'>";
		 		 echo "Czy chcesz potwierdzić rezerwacje?".'</br>';
		 		 echo '<form method="post" action="potwierdzenie2.php"><input type="submit" name="tak" value="TAK"></form><form method="post" action="niepotwierdzenie2.php"><input type="submit" name="nie" value="NIE"></form>';
		 		 echo "</div>";
			 }
			 else
			 {
				 echo "<div class='myDiv4'>";
				 echo "Możliwa liczba rezerwacji wynosi 3";
				 echo "</div>";
				 header( "Refresh:3; url=http://localhost:8080/biblioteka/aktualne_rezerwacje.php", true, 303);
			 }
		 }
		}
	?>

</html>
