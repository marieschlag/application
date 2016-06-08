<?php 
$connexion	= mysqli_connect(SERVEUR, USER, PASSWORD, BDD);

// Check connection
if (mysqli_connect_errno())
{
	die("Impossible de se connecter:" . mysqli_connect_error());
}

mysqli_set_charset($connexion, 'utf8' );