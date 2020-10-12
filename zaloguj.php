<?php

	session_start();

	if ((!isset($_POST['login'])) || (!isset($_POST['haslo'])))
	{
		header('Location: index.php'); //konczymy wykonywanie kodu
		exit();
	}

	require_once "connect.php";

	$polaczenie=@new mysqli($host, $db_user, $db_password, $db_name);

	if($polaczenie->connect_errno!=0)
	{
		echo "ERROR: ".$polaczenie->connect_errno;
	}
	else
	{
		$login = $_POST['login'];
		$haslo = $_POST['haslo'];

		$login = htmlentities($login, ENT_QUOTES, "UTF-8");
		$haslo = htmlentities($haslo, ENT_QUOTES, "UTF-8"); //zamieniamy na encje, aby ktos nie mogl sie wlamac na konto

		//$sql = "SELECT * FROM osoby WHERE BINARY login='$login' AND BINARY haslo='$haslo'";

		if ($rezultat = @$polaczenie->query(
		sprintf("SELECT * FROM osoby WHERE BINARY login='%s' AND BINARY haslo='%s'",
		mysqli_real_escape_string($polaczenie,$login),
		mysqli_real_escape_string($polaczenie,$haslo)))) //literowka w zapytaniu ; funkcja napisana do zabezpieczenia przed wstrzykiwaniem mysql
		{
			$ilu_userow=$rezultat->num_rows;
			if($ilu_userow>0)
			{
				$_SESSION['zalogowany']=true; //flaga, ze jestesmy zalogowani

				$wiersz=$rezultat->fetch_assoc();
				$_SESSION['id']=$wiersz['id'];
				$_SESSION['login']=$wiersz['login'];
				$_SESSION['imie']=$wiersz['imie'];
				$_SESSION['nazwisko']=$wiersz['nazwisko'];
				$_SESSION['email']=$wiersz['email'];
				$_SESSION['nr_tel']=$wiersz['nr_tel'];

				unset($_SESSION['blad']);
				$rezultat->free_result();
				header('Location: panel.php');
			}
			else
			{
				$_SESSION['blad']='<span style="color:red">Nieprawidlowy login lub hasło!</span>';
				header('Location: index.php');
			}
		}

		$polaczenie->close(); //zamykanie polaczenia z baza
	}

?>
