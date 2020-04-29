<?php
session_start();

$bdd = new PDO('mysql:host=127.0.0.1;dbname=espace_membre2', 'root', '');

if(isset($_SESSION['id']))
{
	$requser = $bdd->prepare("SELECT * FROM membre WHERE id = ?");
	$requser->execute(array($_SESSION['id']));
	$user = $requser->fetch();

	if(isset($_POST['newpseudo']) AND !empty($_POST['newpseudo']) AND $_POST['newpseudo'] != $user['pseudo'])
	{
		$newpseudo = htmlspecialchars($_POST['newpseudo']);
		$insertpseudo = $bdd->prepare("UPDATE membre SET pseudo = ? WHERE id = ?");
		$insertpseudo->execute(array($newpseudo, $_SESSION['id']));
		header('Location: profil.php?id='.$_SESSION['id']);
	}

	if(isset($_POST['newmail']) AND !empty($_POST['newmail'])  AND $_POST['newmail'] != $user['mail'])
	{
		$newmail = htmlspecialchars($_POST['newmail']);
		$insertmail = $bdd->prepare("UPDATE membre SET mail = ? WHERE id = ?");
		$insertmail->execute(array($newmail, $_SESSION['id']));
		header("Location: profil.php?id=".$_SESSION['id']);
	}

	if(isset($_POST['newfillière']) AND !empty($_POST['newfillière']) AND $_POST['newfillière'] != $user['fillière'])
	{
		$newfillière = htmlspecialchars($_POST['newfillière']);
		$insertfillière = $bdd->prepare("UPDATE membre SET fillière = ? WHERE id = ?");
		$insertfillière->execute(array($newfillière, $_SESSION['id']));
		header('Location: profil.php?id='.$_SESSION['id']);
	}

	if(isset($_POST['newmdp1']) AND !empty($_POST['newmdp1']) AND isset($_POST['newmdp1']) AND !empty($_POST['newmdp2']))
	{
		$mdp1 = sha1($_POST['newmdp1']);
		$mdp2 = sha1($_POST['newmdp2']);

		if($mdp1 == $mdp2)
		{
			$insertmdp = $bdd->prepare("UPDATE membre SET mot de passe = ? WHERE id = ?");
			$insertmdp->execute(array($mdp1, $_SESSION['id']));
			header('Location: profil.php?id='.$_SESSION['id']);
		}
		else
		{
			$msg = "Vos deux mot de passes de correspondent pas !";
		}
	}

	if(isset($_FILES['avatar']) AND !empty($_FILES['avatar']['name']))
	{
		$tailleMax = 2097152;
		$extensionsValides = array('jpg', 'jpeg', 'gif', 'png');
		if($_FILES['avatar']['size'] <= $tailleMax)
		{
			$extensionUpload = strtolower(substr(strrchr($_FILES['avatar']['name'], '.'), 1));
			if(in_array($extensionUpload, $extensionsValides))
			{
				$chemin = "membres/avatars/".$_SESSION['id'].".".$extensionUpload;
				$resultat = move_uploaded_file($_FILES['avatar']['tmp_name'], $chemin);
				if($resultat)
				{
					$updateavatar = $bdd->prepare('UPDATE membre SET avatar = :avatar WHERE id = id:');
					$updateavatar->execute(array('avatar' => $_SESSION['id'].".".$extensionUpload, 'id' => $_SESSION['id']));
					header('Location: profil.php?id='.$_SESSION['id']);
				}
				else
				{
					$msg = "Erreur lors de l'importation de la photo";
				}
			}
			else
			{
				$msg = "Votre photo de profil doit être au format jpg, jpeg, gif ou png !";
			}
		}
		else
		{
			$msg = "Votre photos de profil ne doit pas dépasser 2 Mo ! Veuillez en choisir une autre.";
		}
	}



?>
<!DOCTYPE html>
<html>
	<head>
		<title>Edition du profil</title>
		<meta charset="utf-8">
	</head>
	<body>
		<div align="center">
			<style type="text/css"> h1{color:red ;} </style>
			<style type="text/css"> body {background-color:#C5F6CA ;}</style>
			<style type="text/css"> p {color:#547F80 </style>
			<style type="text/css"> a { color: blue; } </style>

			<h1>Edition de mon profil</h1>
			<div align="left">
			<br>
				<form method="POST" action="" enctype="multipart/form-data">
					<label>Pseudo:</label>
					<input type="text" name="newpseudo" placeholder="Pseudo" value="<?php echo $user['pseudo'];?>"><br /><br />
					<label>Fillière:</label>
					<input type="text" name="newfillière" placeholder="Fillière" value="<?php echo $user['fillière'];?>"><br /><br />
					<label>Mail:</label>
					<input type="text" name="newmail" placeholder="Mail" value="<?php echo $user['mail']; ?>"><br /><br />
					<label>Mot de passe:</label>
					<input type="password" name="newmdp1" placeholder="Mot de passe"><br /><br />
					<label>Confirmation du mot de passe:</label>
					<input type="password" name="newmdp2" placeholder="Confirmation du mot de passe"><br /><br />
					<label>Photo de de profil:</label>
					<input type="file" name="avatar"><br /><br />
					<input type="submit" value="Mettre à jour mon profil."><br /><br />
				</form>
				<?php if(isset($msg))  { echo $msg; } ?>
			</div>
		</div>
	</body>
</html>
<?php
}
?>