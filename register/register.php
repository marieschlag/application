<?php
session_start();
$titre="Enregistrement";
include("includes/configuration.php");
include("includes/header.php");
include("./index.php.php");
echo '<p><i>Vous êtes ici</i> : <a href="./index.php">Index du forum</a> --> Enregistrement';

if ($id!=0) erreur(ERR_IS_CO);
?>
