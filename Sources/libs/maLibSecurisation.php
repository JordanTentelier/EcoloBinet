<?php

include_once "maLibUtils.php";	// Car on utilise la fonction valider()
include_once "maLibSql.pdo.php";
include_once "maLibForms.php";

/**
 * @file login.php
 * Fichier contenant des fonctions de v�rification de logins
 */

/**
 * Cette fonction v�rifie si le login/passe pass�s en param�tre sont l�gaux
 * Elle stocke le pseudo de la personne dans des variables de session : session_start doit avoir �t� appel�...
 * Elle enregistre aussi une information permettant de savoir si l'utilisateur qui se connecte est administrateur ou non
 * Elle enregistre l'�tat de la connexion dans une variable de session "connecte" = true
 * @pre login et passe ne doivent pas �tre vides
 * @param string $login
 * @param string $password
 * @return false ou true ; un effet de bord est la cr�ation de variables de session
 */

function verifUser($login,$pwd)
{
$sql="SELECT * from users WHERE Login='$login' AND Password='$pwd' AND Blacklist = 0"; // on recup la base de donn� dans la var sql
$resource = SQLSelect($sql);
$tabData = parcoursRS($resource); // tabData , tableau des r�ponses de resource


if($resource) // si resultat dans la recherche du login
	{
		$_SESSION["Login"]= $login; // La session ouverte prend pour pseudo le login
		$_SESSION["Password"] = $pwd;
		$_SESSION["IdUser"] = $tabData[0]['Id'];
		$id = $_SESSION["IdUser"];
		$_SESSION["connecte"] = true;

		$sql="SELECT * from parametres WHERE id_user='$id'"; // on recup la base de donn� dans la var sql
		$resource = SQLSelect($sql);
		$tabData = parcoursRS($resource);

		$_SESSION["capa"] = $tabData[0]['capacite'];
		$_SESSION["temp"] = $tabData[0]['temperature'];
		$_SESSION["pourcentage"] = $tabData[0]['pourcentage'];
		return true;
	}
return false;
}

/**
 * Fonction � placer au d�but de chaque page priv�e
 * Cette fonction redirige vers la page $urlBad en envoyant un message d'erreur 
	et arr�te l'interpr�tation si l'utilisateur n'est pas connect�
 * Elle ne fait rien si l'utilisateur est connect�, et si $urlGood est faux
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
