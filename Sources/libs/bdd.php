<?php

// la librairie faciliant les requêtes SQL
include_once("maLibSQL.pdo.php");

/////////////////////////////////////// Fonction Users //////////////////////////////////////////


function AddUser($nom,$prenom,$login,$admin)
{
	$sql = "INSERT INTO users (Login,Name,Surname,Admin) VALUES('$login','$nom','$prenom','$admin')";
	SQLInsert($sql);
}

function ChgtUser($login,$val,$cible)
{
	$sql = "UPDATE users SET $cible = '$val' WHERE Login = '$login'";
	SQLUpdate($sql);
}

function listerUtilisateurs($classe = "both")
{
	$sql = "SELECT * FROM users WHERE Admin < 2";
	return parcoursRs(SQLSelect($sql));
}

function interdireUtilisateur($login)
{
	// cette fonction affecte le booléen "blacklist" à vrai 
	$SQL = "UPDATE users SET Blacklist = 1 WHERE Login='$login'";
	SQLUpdate($SQL);
}

function autoriserUtilisateur($login)
{
	// cette fonction affecte le booléen "blacklist" à faux 
	$SQL = "UPDATE users SET Blacklist = 0 WHERE Login='$login'";
	SQLUpdate($SQL);
}

function supprimerUtilisateur($login)
{
	// cette fonction supprime un utilisateur
	$SQL = "DELETE FROM users WHERE Login='$login'";
	SQLUpdate($SQL);
}

////////////////////////////////////////////////////////////////////////////////////////////////


///////////////////////////////////// Fonction Pieces //////////////////////////////////////////

function AddType($type)
{
	$sql="INSERT INTO type (Type) VALUES ('$type')";
	SqlInsert($sql);
}

function SuppType($type)
{
	$sql="DELETE FROM type WHERE Type = '$type'";
	SQLUpdate($sql);
}

function CreerEmp($emplacement,$x,$y)
{
	$sql="INSERT INTO emplacement (Emplacement,Gauche,Haut) VALUES ('$emplacement','$x','$y')";
	SqlInsert($sql);
}

function SupEmplacement($emplacement)
{
	$sql="DELETE FROM emplacement WHERE Emplacement = '$emplacement'";
	SQLUpdate($sql);
}

function AjouterReference($ref,$sap,$fournisseur,$type,$nom,$prio,$qtt,$emp,$stock)
{
	$sql="INSERT INTO pieces VALUES ('$ref','$sap','$type','$nom','$fournisseur','$prio','$qtt','$emp','$stock')";
	SqlInsert($sql);
}

function RetraitReference($cible)
{
	$sql="DELETE FROM pieces WHERE pieces.Reference='$cible'";
	SQLUpdate($sql);	
}

function Chgt($ref,$val,$cible,$table)
{

	$sql = "UPDATE $table SET $cible = '$val' WHERE Reference = '$ref'";
	SQLUpdate($sql);
}

function UpdateBdd($reference,$quantite,$statut)
{
	if($statut==0)
		$sql="UPDATE pieces SET Quantite=Quantite+'$quantite' WHERE Reference='$reference'";
	else
		$sql="UPDATE pieces SET Quantite=Quantite-'$quantite' WHERE Reference='$reference'";
	SQLUpdate($sql);
}

////////////////////////////////////////////////////////////////////////////////////////////////


//////////////////////////////// Fonction pour les Selects /////////////////////////////////////

function Recup($cible,$table,$order)
{
	$sql="SELECT $cible FROM $table ORDER BY $order ASC";
	return parcoursRs(SqlSelect($sql));
}

////////////////////////////////////////////////////////////////////////////////////////////////




//////////////////////// Fonction pour les labels carrés de couleur ////////////////////////////

function Recup2($critere)
{
	$sql="SELECT Reference,Type,Nom_Piece,Priorite,StockMini,Quantite,Emplacement,SAP FROM pieces WHERE Emplacement LIKE '$critere%' AND pieces.Quantite < pieces.StockMini ORDER BY Reference";
	return parcoursRs(SqlSelect($sql));
}

////////////////////////////////////////////////////////////////////////////////////////////////




/////////////////////// Fonction de recherche piece dans le magasin ////////////////////////////

function Rechercher($ref,$colonne)
{
	$sql = "SELECT pieces.Emplacement,pieces.StockMini,pieces.Reference,pieces.SAP,pieces.Fournisseur,pieces.Nom_Piece,pieces.Type,pieces.Quantite,emplacement.gauche,emplacement.haut FROM pieces,emplacement WHERE $colonne LIKE '$ref' AND pieces.Emplacement=emplacement.Emplacement";
	return parcoursRs(SqlSelect($sql));
}

////////////////////////////////////////////////////////////////////////////////////////////////


///////////////// Fonction qui recupere la dernier modif pour login.php ///////////////////////

function derniereModif()
{
	$sql = "SELECT Name,Surname,fiche_emargement.Reference,Date,Nombre,Nom_Piece 
	FROM fiche_emargement,pieces,users 
	WHERE fiche_emargement.Reference=pieces.Reference 
	AND fiche_emargement.idUser=users.idUser 
	ORDER BY idEmargemment DESC LIMIT 1";
	return parcoursRs(SQLSelect($sql));
}

////////////////////////////////////////////////////////////////////////////////////////////////


function Recherche($cible,$colonnecible,$colonne,$table)
{
	$sql="SELECT $colonne FROM $table WHERE $colonnecible='$cible'";
	return parcoursRs(SQLSelect($sql));
}

function BoutonType()
{
	$sql="SELECT * FROM Type";
	return parcoursRs(SqlSelect($sql));
}

//////////////////////////////// Fonction tableau audit.php ////////////////////////////////////

function audit($type,$nombre)
{
	//  WHERE EMPLACEMENT LIKE 'arm%'
	if($type == "Audit Annuelle")
		$sql="SELECT * FROM pieces ORDER BY Emplacement";
	else
		$sql="SELECT * FROM pieces ORDER BY rand() LIMIT $nombre"; 
	return parcoursRs(SQLSelect($sql));
}

////////////////////////////////////////////////////////////////////////////////////////////////



///////////////////////////////// Fonction Historique.php //////////////////////////////////////

function Historique($ordre,$recherche,$critere,$limit,$autre,$chaine)
{
	
	$sql="SELECT SAP,Name,Surname,Nom_Piece,fiche_emargement.Reference,fiche_emargement.Nombre,fiche_emargement.Date,pieces.Type,fiche_emargement.action,fiche_emargement.QttRest,pieces.StockMini
	FROM users,pieces,fiche_emargement
	WHERE $critere
	$chaine
	ORDER BY $recherche $ordre
	LIMIT $limit";

	return parcoursRs(SQLSelect($sql));
}

function supprimerhistorique($idUser)
{
	$sql="DELETE FROM fiche_emargement WHERE IdUser='$idUser'";
	SQLUpdate($sql);
}

function UpdateFiche($id,$nombre,$qttrest,$ref,$date,$action)
{
	$sql="INSERT INTO fiche_emargement (IdUser,Nombre,QttRest,Reference,fiche_emargement.Date,action) VALUES ('$id','$nombre','$qttrest','$ref','$date','$action')";
	SqlInsert($sql);
}

////////////////////////////////////////////////////////////////////////////////////////////////


//////////////////////////// Fonction tableau piece a commander ////////////////////////////////

function Acommander($ordre,$recherche,$critere,$plus)
{
	$sql="SELECT Reference,Nom_Piece,Quantite,StockMini,Priorite,Emplacement,Fournisseur,SAP
	FROM pieces 
	WHERE $critere $plus
	ORDER BY $recherche $ordre";
	return parcoursRs(SqlSelect($sql));

}

////////////////////////////////////////////////////////////////////////////////////////////////

///////////////////////////// Fonction sauvegarde de la BDD ////////////////////////////////////

function dumpMySQL($serveur, $login, $password, $base, $mode)
{
    $connexion = mysql_connect($serveur, $login, $password);
    mysql_select_db($base, $connexion);
 
    $entete = "-- ----------------------\n";
    $entete .= "-- dump de la base ".$base." au ".date("d-M-Y")."\n";
    $entete .= "-- ----------------------\n\n\n";
    $creations = "";
    $insertions = "\n\n";
 
    $listeTables = $result = mysql_query("SELECT table_name FROM INFORMATION_SCHEMA.TABLES WHERE table_schema = 'iga_magasin' ORDER BY table_name DESC");
    while($table = mysql_fetch_array($listeTables))
    {
        // si l'utilisateur a demandé la structure ou la totale
        if($mode == 1 || $mode == 3)
        {
            $creations .= "-- -----------------------------\n";
            $creations .= "-- creation de la table ".$table[0]."\n";
            $creations .= "-- -----------------------------\n";
            $listeCreationsTables = mysql_query("show create table ".$table[0], $connexion);
            while($creationTable = mysql_fetch_array($listeCreationsTables))
            {
              $creations .= $creationTable[1].";\n\n";
            }
        }
        // si l'utilisateur a demandé les données ou la totale
        if($mode > 1)
        {
            $donnees = mysql_query("SELECT * FROM ".$table[0]);
	        $numResults = mysql_num_rows($donnees); 
	        // Empeche les erreurs si la table est vide 
	        if($numResults != 0)
            {
	            $compteur = 0;
	            $insertions .= "-- -----------------------------\n";
	            $insertions .= "-- insertions dans la table ".$table[0]."\n";
	            $insertions .= "-- -----------------------------\n";
	            $insertions .= "INSERT INTO ".$table[0]." VALUES";
	            while($nuplet = mysql_fetch_array($donnees))
	            {
	                $insertions .=  "(";
	                for($i=0; $i < mysql_num_fields($donnees); $i++)
	                {
	                  if($i != 0)
	                     $insertions .=  ", ";
	                  if(mysql_field_type($donnees, $i) == "string" || mysql_field_type($donnees, $i) == "blob")
	                     $insertions .=  "'";
	                  $insertions .= addslashes($nuplet[$i]);
	                  if(mysql_field_type($donnees, $i) == "string" || mysql_field_type($donnees, $i) == "blob")
	                    $insertions .=  "'";
	                }
						$compteur++;
						if($compteur != $numResults)
							$insertions .=  "),\n";
						else
							$insertions .=  ");\n";
	            }
	        }
        }
    }
 
    mysql_close($connexion);
 
    $fichierDump = fopen("../bdd/db-backup.sql", "wb");
    fwrite($fichierDump, $entete);
    fwrite($fichierDump, $creations);
    fwrite($fichierDump, $insertions);
    fclose($fichierDump);
	if(!is_dir('E:\BackUp'))
		mkdir('E:\BackUp');
	$fichierDump = fopen("E:/BackUp/db-backup.sql", "wb");
    fwrite($fichierDump, $entete);
    fwrite($fichierDump, $creations);
    fwrite($fichierDump, $insertions);
    fclose($fichierDump);

}

////////////////////////////////////////////////////////////////////////////////////////////////

?>
