<?php
session_start();
	///////////// Librairie de fonctions ///////////////////////
	/**/	include_once "libs/maLibUtils.php";			/**/
	/**/	include_once "libs/maLibSQL.pdo.php";		/**/
	/**/	include_once "libs/maLibSecurisation.php"; 	/**/
	/**/	include_once "libs/bdd.php"; 				/**/
	////////////////////////////////////////////////////////////

	$data["connecte"] = valider("connecte","SESSION");
	$data["action"] = valider("action");

	if (isset($_POST["PHPSESSID"])) {
  		session_id($_POST["PHPSESSID"]);
 	}

	if (!$data["action"])
	{
		// On ne doit rentrer dans le switch que si on y est autorisé
		$data["feedback"] = "Entrez connexion(login,passe)";
	}
	else 
	{
		$data["feedback"] = "ok";

		// si pas connecte et action n'est pas connexion ou recuperation, on refuse
		if ( (!valider("IdUser","SESSION")) && ($data["action"] != "connexion" ) &&($data["action"] != "recuperation") ) {
			$data["feedback"] = "Entrez connexion(login,passe) (eg 'user','user')";
		}

		else {
			date_default_timezone_set('Europe/Paris');
			switch($data["action"]){
				case "connexion" :
					$login = valider("login");
					$pwd = valider('pwd');

					if 	( !($login) || !($pwd) ){
						$data["feedback1"] = "Inconnu";
					}

					$pass = "[$^";
					$pass .= $pwd;
					$pass .= "#m%ù";
					$pwd = sha1($pass);

					$data['pwd'] = $pwd;
					if(!($data["connecte"] = verifUser($login,$pwd))){
						$data["feedback"] = "Connexion impossible";
					} 
				break;

				case "deconnexion" :
					session_destroy();
					$data["feedback"] = "Deconnection";
				break;
				case "electrovanne" :
					$flag = valider("flag");
					if($flag =="true")
						shell_exec('sudo python Electrovanneup.py');
					else
						shell_exec('sudo python Electrovannedown.py');
				break;
				case "remplir" :
                    $temp = valider("temp");
                    $quantite = valider("quantite");
                    $capacite = valider("capacite");
                    $pourcentage = valider("pourcentage");

                    if(!$pourcentage){
                    	$pourcentage = intval($capacite/$quantite);
                    } else {
                    	$pourcentage = $quantite;
                    	$quantite = intval(($pourcentage/100)*$capacite);
                    }
                    
                    $id = $_SESSION['IdUser'];

                    $sql = "SELECT id_user FROM parametres WHERE id_user = '$id'";
                    $resource = SQLSelect($sql);

                    if($resource){
                    	$sql = "UPDATE parametres SET id_user = '$id', capa = '$capacite', temp = '$temp', pourcentage = '$pourcentage'";
                    	SQLUpdate($sql);
                    } else {
                    	$sql = "INSERT INTO parametres (id_user,capa,temp,pourcentage,eau)  VALUES('$id','$capacite','$temp','$pourcentage')";
                   	 	SQLInsert($sql);	
                	}

                    $_SESSION["temp"]= $temp;
                    $_SESSION["capa"] = $capacite;
            		$_SESSION["pourcentage"] = $pourcentage;

            		// TODO : calculer le debit de la valve && en deduire le temps de replissage
			shell_exec('sudo python Electrovanneup.py');
			sleep(1);			
			$flow = shell_exec('sudo python waterFlowSensor.py');
			$_SESSION["flow"] = $flow;
			$_SESSION["litreDemande"] = $capacite*($pourcentage/100);
			$_SESSION["litreEcoule"] = 3 * $_SESSION["flow"];
            		$_SESSION["time"] = intval(($capacite*($pourcentage/100))/$flow) ;
			$_SESSION["compteur"] = 0;
            		$_SESSION["fin"] = date('Y/m/d H:i:s',strtotime("+".$_SESSION["time"]." seconds"));
                    $data["feedback"] = "Remplir OK";
				break;

				case "updateSession" : 
					$compteur = valider("compteur");
					$time = valider("time");
					$litreEcoule = valider("litreEcoule");
					$_SESSION["time"] = $time;
					$_SESSION["litreEcoule"] = $litreEcoule;
            		$_SESSION["compteur"] = $compteur;
            		$_SESSION["fin"] = date('Y/m/d H:i:s',strtotime("+".$time-$compteur." seconds"));
				break;
			}
		}
		
	}
	 
	echo json_encode($data);
?>
