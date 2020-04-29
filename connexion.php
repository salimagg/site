<?php
session_start();

$bdd = new PDO('mysql: host=127.0.0.1;dbname=espace_membre2', 'root', '');

if(isset($_POST['formconnexion']))
{
	$mailconnect = htmlspecialchars($_POST['mailconnect']);
	$mdpconnect = sha1($_POST['mdpconnect']);
	if(!empty($mailconnect) AND !empty($mdpconnect))
	{
		$requser = $bdd->prepare("SELECT * FROM membre WHERE mail = ? AND motdepasse = ?");
		$requser->execute(array($mailconnect, $mdpconnect));
		$userexist = $requser->rowCount();
		if($userexist == 1)
		{
			$userinfo = $requser->fetch();
			$_SESSION['id'] = $userinfo['id'];
			$_SESSION['pseudo'] = $userinfo['pseudo'];
			$_SESSION['mail'] ^$userinfo['mail'];
			header("Location: profil.php?id=".$_SESSION['id']);
		}
		else
		{
			$erreur = "Mauvais mail ou mot de passe ! Recommencez !";
		}
	}
	else
	{
		$erreur= "Tous les champs doivent être complétés !";
	}
}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Connexion</title>
		<meta charset="utf-8">
		<style type="text/css"> h1{color:red ;}</style>
		<style type="text/css"> body {background-color:#C5F6CA ;}</style>
		<style type="text/css"> p {color:#547F80 </style>
		<style type="text/css"> a { color: blue; } </style>
	</head>
	<body>
		<div align="center">
			<h1>Connexion</h1>
			<br /><br />
			<form method="POST" action="">
				<input type="email" name="mailconnect" placeholder="mail">
				<input type="password" name="mdpconnect" placeholder="Mot de passe">
				<input type="submit" name="formconnexion" value="Se connecter !">
			</form>
			<a href="index.html"> Retour au menu.</a>
			<?php
			if(isset($erreur))
			{
				echo '<font color="red">'.$erreur."</font>";
			}
			?>
		</div>
</body>
</html>