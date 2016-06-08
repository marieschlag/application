<?php
session_start();

// inclure variables
require_once('includes/configuration.php');

// récupérer $connexion
require_once('includes/header.php');

// reception des variables get, post
$action 	= isset($_GET['action']) ? $_GET['action'] : NULL ;
$theme 		= isset($_GET['recherche']) ? $_GET['recherche'] : 'auteur' ;

$login 		= isset($_POST['login']) ? $_POST['login'] : NULL ;
$password 	= isset($_POST['password']) ? $_POST['password'] : NULL ;
$search 	= isset($_POST['search']) ? $_POST['search'] : NULL ;

$newLogin = isset($_POST['newLogin']) ? $_POST['newLogin'] : NULL;
$newPassword = isset($_POST['newPassword']) ? $_POST['newPassword'] : NULL;
$newMail = isset($_POST['newMail']) ? $_POST['newMail'] : NULL;


// Tentative de connexion
if ($login && $password)
	{
		$password = md5($password);
			$sql = "SELECT identifiant, email, niveau FROM utilisateur";
			$sql.= " WHERE identifiant = '$login' AND mot_de_passe = '$password' LIMIT 1";
			
			$sqlResult 	= mysqli_query($connexion, $sql);  
			$user 		= mysqli_fetch_assoc($sqlResult);
			
			if (isset($user) && $user) 
			{
				$_SESSION['user'] = $user;
			} else {
				$errorLogin = true;
			}
	}
	
// création utilisateur 
if ($newLogin && $newPassword && $newMail)
	{
		$newPassword = md5($newPassword);
			$sql = "INSERT INTO utilisateur VALUES ('', '$newLogin', '$newMail', '$newPassword', '2')";
			
			$sqlResult 	= mysqli_query($connexion, $sql);  
	}
	
// Déconnexion utilisateur
if ($action == 'logout') 
{
	unset($_SESSION['user']);
	session_destroy();
}

// chargement des fonction de creation des requetes sql
require_once('includes/sql.php');

// inclusion de la fonction table
require_once('includes/html/tables.php');

// inclusion entete HTML + Styles
require_once('template/header.php');

if (isset($_SESSION['user']) && $_SESSION['user']) 
{ 
	// création d'un menu + formulaire de recherche
	echo '<a href="index.php?recherche=auteur">Lister les auteurs</a>';
	echo '<a href="index.php?recherche=livre">Lister les livres</a>';
	echo '<br />';
	echo '<a href="index.php?action=logout">Se déconnecter</a>'; 

	?>

	<form action="index.php" method="post">
	    <input name="search" type="text"/>
	</form>

	<?php
	$sql        	= getSql($theme, $search, $connexion);
	$sqlResult 	= mysqli_query($connexion, $sql);
	$rowCount   	= mysqli_num_rows($sqlResult);

	// affichage des resultats de la recherche utilisateur
	if ( isset($rowCount) && $rowCount )  
	{
	    while($row = mysqli_fetch_assoc($sqlResult))
	    {
	        $result[] 	= $row;
	    }
	    echo getHtmlTable($result);
	} else {
	    echo "pas de résultats";
	}
} else {
	
	if (isset($errorLogin) && $errorLogin) 
	{
		echo "Ce couple de login et pass ne correspondent à aucun utilisateur enregistré";
	}

	?>
	<!-- Formulaire connexion -->
	<form class="form1" action="index.php" method="post">
	<fieldset> Se connecter : </fieldset><br>
	    <label>Login</label>
	    <input name="login" type="text"/>
	    <label>Mot de passe</label>	    
	    <input name="password" type="password"/>	
	    <input class="connexion" type="submit" value="Se connecter">
	</form>

	<!-- Formulaire inscription -->
	
	<form class="form2" action="index.php" method="post">
	<fieldset> S'inscrire : </fieldset><br>
	    <label>Login</label>
	    <input name="newLogin" type="text"/>
	    <label>Mot de passe</label>	    
	    <input name="newPassword" type="password"/>	
		<label>E-mail</label>
		<input name="newMail" type="email"/>
		<input class="w3-input w3-border w3-round-xxlarge" type="submit" value="S'enregistrer">
	</form>
	
	<?php
			// reception des variables get, post
		$new_pseudo = isset($_POST['new_pseudo']) ? $_POST['new_pseudo'] : NULL ;
		$new_email = isset($_POST['new_email']) ? $_POST['new_email'] : NULL ;
		$pass1 = isset($_POST['pass1']) ? $_POST['pass1'] : NULL ;
		
		// Tentative d'enregistrement
		if ($new_pseudo && $pass1 && $new_email)
		{
			$pass1 = md5($pass1);
			$sql = "SELECT identifiant, email, niveau FROM utilisateur";
			$sql.= " WHERE identifiant = '$new_pseudo' AND mot_de_passe = '$pass1' LIMIT 1 AND email = '$new_email'";
			
			$sqlResult 	= mysqli_query($connexion, $sql);  
			$user 		= mysqli_fetch_assoc($sqlResult);
		}
}
	?>
	
	<?php

// Inclusion fin HTML
require_once('template/footer.php');

// Footer PHP - Ferme la connexion
require_once('includes/footer.php');