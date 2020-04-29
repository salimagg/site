<?php

$bdd = new PDO('mysql: host=127.0.0.1;dbname=espace_membre2', 'root', '');

if(isset($_POST['forminscription']))
{
	$pseudo = htmlspecialchars($_POST['pseudo']);
	$fillière = htmlspecialchars($_POST['fillière']);
	$groupe = htmlspecialchars($_POST['groupe']);
	$mail = htmlspecialchars($_POST['mail']);
	$mail2 = htmlspecialchars($_POST['mail2']);
	$mdp = sha1($_POST['mdp']);
	$mdp2 = sha1($_POST['mdp2']);

	if(!empty($_POST['pseudo']) AND !empty($_POST['mail']) AND !empty($_POST['mail2']) AND !empty($_POST['mdp']) AND !empty($_POST['mdp2']))
	{
		$pseudolength = strlen($pseudo);
		if($pseudolength <= 255)
		{
			if($mail == $mail2)
			{
				if(filter_var($mail, FILTER_VALIDATE_EMAIL))
				{
					$reqmail = $bdd->prepare("SELECT * FROM membre WHERE mail = ?");
					$reqmail->execute(array($mail));
					$mailexist = $reqmail->rowCount();
					if($mailexist ==0)
					{
						if($mdp == $mdp2)
						{
							$insertmbr = $bdd->prepare("INSERT INTO membre(pseudo, mail, motdepasse) VALUES(?, ?, ?)");
							$insertmbr->execute(array($pseudo, $mail, $mdp ));
							$erreur = "Votre compte à bien été créé! Merci!";
						}
						else
						{
							$erreur = "Vos mot de passe ne correspondent pas!";
						}
					}
					else
					{
						$erreur = "Désolé mais cette adresse mail est déjà utilisé pour un autre compte déjà existant :'(";
					}
				}
				else
				{
					$erreur = " Votre adresse mail n'est pas valide!";
				}
			}
			else
			{
				$erreur = "Vos adresse mail ne correspondent pas!";
			}
		}
		else
		{
			$erreur = "Votre pseudo comporte trop de carctère!";
		}
	}
	else
	{
		$erreur = "Tous les champs doivent êtres complétés!";
	}
}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Inscription</title>
		<meta charset="utf-8">
		<style type="text/css"> h1{color:red ;}</style>
		<style type="text/css"> body {background-color:#C5F6CA ;}</style>
		<style type="text/css"> p {color:#547F80 </style>
		<style type="text/css"> a { color: blue; } </style>
	</head>
	<body>
		<div align="center">
			<h1>Inscription</h1>
			<br /><br />
			<form method="POST" action="">
				<table>
					<tr>
						<td align="right">
							<label for="pseudo">Nom:</label>
						</td>
						<td>
							<input type="text" placeholder="Votre nom" id="pseudo" name="pseudo" value="<?php if(isset($pseudo)) { echo $pseudo; } ?>" />
						</td>
					</tr>
					<tr>
						<td align="right">
							<label for="mail">Adresse mail:</label>
						</td>
						<td align="right">
							<input type="email" placeholder="Votre adresse mail" id="mail" name="mail" value="<?php if(isset($mail)) { echo $mail; } ?>"/>
						</td>
					</tr>
					<tr>
						<td align="right">
							<label for="mail">Confirmation de l'adresse mail:</label>
						</td>
						<td align="right">
							<input type="email" placeholder="Votre adresse mail" id="mail2" name="mail2" value="<?php if(isset($mail2)) { echo $mail2; } ?>"/>
						</td>
						<tr>
						<td align="right">
							<label for="fillière">Fillière:</label>
						</td>
						<td align="right">
							<input type="text" placeholder="Votre fillière" id="" name="fillière" value="<?php if(isset($fillière)) { echo $fillière; } ?>"/>
						</td>
						<tr>
						<td align="right">
							<label for="groupe">Groupe TD:</label>
						</td>
						<td align="right">
							<input type="text" placeholder="Votre groupe" id="groupe" name="groupe" value="<?php if(isset($groupe)) { echo $groupe; } ?>"/>
						</td>
					</tr>
					<tr>
						<td align="right">
							<label for="mdp">Mot de passe:</label>
						</td>
						<td align="right">
							<input type="password" placeholder="Votre mot de passe" id="mdp" name="mdp" />
						</td>
					</tr>
					<tr>
						<td align="right">
							<label for="mdp2">Confirmation du mot de passe:</label>
						</td>
						<td align="right">
							<input type="password" placeholder="Votre mot de passe" id="mdp2" name="mdp2" />
						</td>
					</tr>
					<tr>
						<td></td>
						<td>
							<input type="submit" name="forminscription" value="Je m'inscris!">
						</td>
					</tr>
				</table>
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