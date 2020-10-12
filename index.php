<?php
		session_start();

		if((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']==true))
		{
			header('Location: panel.php');
			exit(); //opuszczamy plik, nie wykonujemy dalszej czesci kodu
		}
?>
<!DOCTYPE HTML>
<html lang='pl'>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1"/>
	<title>BIBLIOTEX</title>
	<link rel="stylesheet" href="style.css"></link>
</head>

<body>
	<form class="box" action="zaloguj.php" method="post">
		<h1>LOGOWANIE</h1>
		<input type="text" name="login" placeholder="Login"/>
		<input type="password" name="haslo" placeholder="Hasło"/>
		<input type="submit" value="Zaloguj się"/>

<?php
		if(isset($_SESSION['blad']))
		{
			echo "</br>".$_SESSION['blad'];
			unset($_SESSION['blad']);
		}
?>

</form>
</body>
</html>
