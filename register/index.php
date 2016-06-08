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

// Tentative de connexion
if ($login && $password)
{
	$password = md5($password);
        $sql = "SELECT identifiant, email, niveau FROM utilisateur";
        $sql.= " WHERE identifiant = '$login' AND mot_de_passe = '$password' LIMIT 1";
        
        $sqlResult 	= mysqli_query($connexion, $sql);  
        $user 		= mysqli_fetch_assoc($sqlResult);
        
        if (isset($user) && $user) {
        	$_SESSION['user'] = $user;
        } else {
        	$errorLogin = true;
        }
}

// Déconnexion utilisateur
if ($action == 'logout') {
	unset($_SESSION['user']);
	session_destroy();
}

// chargement des fonction de creation des requetes sql
require_once('includes/sql.php');

// inclusion de la fonction table
require_once('includes/html/tables.php');

// inclusion entete HTML + Styles
require_once('template/header.php');

if (isset($_SESSION['user']) && $_SESSION['user']) { // User connecté

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
	if ( isset($rowCount) && $rowCount )  {
	    while($row = mysqli_fetch_assoc($sqlResult))
	    {
	        $result[] 	= $row;
	    }
	    echo getHtmlTable($result);
	} else {
	    echo "pas de résultats";
	}

} else { // User non connecté
	
	if (isset($errorLogin) && $errorLogin) {
		echo "Ce couple de login et pass ne correspondent à aucun utilisateur enregistré";
	}
	?>
	
	<form action="index.php" method="post">
	    <label>Login</label>
	    <input name="login" type="text"/>
	    <label>Mot de passe</label>	    
	    <input name="password" type="password"/>	
	    <input type="submit" value="Se connecter">
	</form>

	<form action="index.php" method="post">
	    <label>Login</label>
	    <input name="login" type="text"/>
	    <label>Mot de passe</label>	    
	    <input name="password" type="password"/>	
	    <input type="submit" value="S'enregistrer">
		<label>E-mail</label>
		<input name="E-mail" type="email"/>
	</form>
	
	<?php
			// reception des variables get, post
		$new_pseudo = isset($_POST['new_pseudo']) ? $_POST['new_pseudo'] : NULL ;
		$new_email = isset($_POST['new_email']) ? $_POST['new_email'] : NULL ;
		$password = isset($_POST['password']) ? $_POST['password'] : NULL ;
		
	?>
	
	<?php
}

// Inclusion fin HTML
require_once('template/footer.php');

// Footer PHP - Ferme la connexion
require_once('includes/footer.php');