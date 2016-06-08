<?php 

/**
 * Retourne la requete SQL de recherche formée par les infos utilisateurs
 * @param  string $theme     theme de recherche / table
 * @param  string $search    recherche textuelle
 * @param  connexion $connexion conexion à la base de donnée
 * @return string            requete sql
 */
function getSql($theme, $search, $connexion)
{
    $listeNoire = array('utilisateur');
    $sql 		= "SELECT * FROM auteur";
    if($theme && !in_array($theme, $listeNoire)) {

        $checktable = mysqli_query($connexion, "SHOW TABLES LIKE '$theme'");
        $table      = mysqli_num_rows($checktable) ? $theme : 'auteur';
        $sql 		= "SELECT * FROM $table";      
    }
    if ($search) {
        $sql.= " WHERE nom LIKE '%$search%'";
    }
    return $sql;
}