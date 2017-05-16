<?php


/*
Ce fichier définit diverses fonctions permettant de faciliter la production de mises en formes complexes : 
tableaux, formulaires, ...
*/


function mkLigneEntete($tabAsso,$listeChamps=false)
{
	echo "<tr>";

	if ($listeChamps) {
		// On affiche seulement les champs demandés
		foreach ($listeChamps as $nomChamp) {
			echo "<th> $nomChamp </th>";
		}
	}
	else {
		// On affiche tous les champs disponibles
		foreach($tabAsso as $nomChamp => $valChamp) {
			echo "<th> $nomChamp </th>";
		}
	}
	echo "</tr>";
	/* $tabAsso
		Array
        (
            [id] => 3
            [pseudo] => Tom
            [blacklist] => 0
            [connecte] => 0
            [couleur] => orange
        )
	*/
}

function mkLigne($tabAsso,$listeChamps=false)
{
	echo "<tr>";

	if ($listeChamps) {
		// On affiche seulement les champs demandés
		foreach ($listeChamps as $nomChamp) {
			echo "<td> $tabAsso[$nomChamp] </td>";
		}
	}
	else {
		foreach($tabAsso as $nomChamp => $valChamp) {
			echo "<td>";
			echo "$valChamp";
			echo "</td>";
		}
	}
	echo "</tr>";
}

function mkTable($tabData,$listeChamps=false)
{
	// que faire si le tableau est vide ? 
	if (count($tabData) ==0) {
		echo "Pas de donnees";
		return; 
	}

	// Ici, on est sûr qu'il y a au moins une ligne
	echo "<table border=\"1\">";
	// pour afficher l'entête, 
	// on passe la premiere cellule à mkLigneEntete()
	mkLigneEntete($tabData[0],$listeChamps);
	// afficher une ligne : mkLigne($tabData[0]);
	// afficher toutes les lignes 

/*	for ($i=0;$i<count($tabData);$i++) {
		$data = $tabData[$i];
		mkLigne($data);
	} */

	foreach ($tabData as $data) {
		mkLigne($data,$listeChamps);
	}

	echo "</table>";

/* $tabData : 
Array
(
    [0] => Array
        (
            [id] => 3
            [pseudo] => Tom
            [blacklist] => 0
            [connecte] => 0
            [couleur] => orange
        )
    [1] => Array
        (
            [id] => 6
            [pseudo] => toto
            [blacklist] => 0
            [connecte] => 0
            [couleur] => black
        )
)
*/
}


// Appel typique : 
// mkSelect("idUser",$users,"id","pseudo")
function mkSelect($nomChampSelect, $tabData,$champValue, $champLabel,$selected=false,$champLabel2=false)
{
	echo "<select name=\"$nomChampSelect\">";

	foreach ($tabData as $data)
	{
		echo '<option value="' 
					. $data[$champValue] . '"';

	// si l'utilisateur courant correspond à l'utiliseur
	// dont l'attribut value est passé en paramètre
	// dans l'argument "$selected", 
	// on le sélectionne par défaut
		if (($selected) 
			&& ($selected == $data[$champValue]))
			echo " selected ";

		echo ">"; // chevron fermant de <option>
		echo  $data[$champLabel];
		if ($champLabel2) 
			echo 	" - " . $data[$champLabel2];
		echo "</option>\n"; 
	}

	echo "</select>";


	// Produit un menu déroulant portant l'attribut name = $nomChampSelect
	// TNE: Si cette variable se termine par '[]', il faudra affecter l'attribut multiple à la balise select

	// Produire les options d'un menu déroulant à partir des données passées en premier paramètre
	// $champValue est le nom des cases contenant la valeur à envoyer au serveur
	// $champLabel est le nom des cases contenant les labels à afficher dans les options
	// $selected contient l'identifiant de l'option à sélectionner par défaut
	// si $champLabel2 est défini, il indique le nom d'une autre case du tableau 
	// servant à produire les labels des options
}

function mkForm($action="",$method="get"){
	// Produit une balise de formulaire NB : penser à la balise fermante !!
	echo "<form 	action=\"$action\" 
						method=\"$method\"	>";
}
function endForm(){
	// produit la balise fermante
	echo "</form>";
}

function mkInput($type,$name,$value=""){
	// Produit un champ formulaire
	echo "<input 	type=\"$type\" 
						name=\"$name\" 
						value=\"$value\"	/>";
}


















