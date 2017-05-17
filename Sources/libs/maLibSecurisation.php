<?php

include_once "maLibUtils.php";	// Car on utilise la fonction valider()
include_once "maLibSql.pdo.php";
include_once "maLibForms.php";

/**
 * @file login.php
 * Fichier contenant des fonctions de vérification de logins
 */

/**
 * Cette fonction vérifie si le login/passe passés en paramètre sont légaux
 * Elle stocke le pseudo de la personne dans des variables de session : session_start doit avoir été appelé...
 * Elle enregistre aussi une information permettant de savoir si l'utilisateur qui se connecte est administrateur ou non
 * Elle enregistre l'état de la connexion dans une variable de session "connecte" = true
 * @pre login et passe ne doivent pas être vides
 * @param string $login
 * @param string $password
 * @return false ou true ; un effet de bord est la création de variables de session
 */

function verifUser($login,$pwd)
{
$sql="SELECT * from users WHERE Login='$login' AND Password='$pwd' AND Blacklist = 0"; // on recup la base de donné dans la var sql
$resource = SQLSelect($sql);
$tabData = parcoursRS($resource); // tabData , tableau des réponses de resource
if($resource) // si resultat dans la recherche du login
	{
		$_SESSION["Login"]= $login; // La session ouverte prend pour pseudo le login 
		$_SESSION["Password"] = $pwd;
		$_SESSION["IdUser"] = $tabData[0]['Id'];
		$_SESSION["connecte"] = true;
		return true;
	}
return false;
}

/**
 * Fonction à placer au début de chaque page privée
 * Cette fonction redirige vers la page $urlBad en envoyant un message d'erreur 
	et arrête l'interprétation si l'utilisateur n'est pas connecté
 * Elle ne fait rien si l'utilisateur est connecté, et si $urlGood est faux
 * Elle redirige vers urlGood sinon
 */
function securiser($urlBad,$urlGood=false)
{
	if($_SESSION["connecte"] == true)
		{
			header('Location:'.$urlGood);
		}
	else
		{
			header('Location:'.$urlBad);
		}
}		

?>
