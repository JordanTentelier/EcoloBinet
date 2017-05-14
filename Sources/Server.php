<?php
	$GLOBALS['cases'] = array(
		1 => "Erreur : champ action non défini",
		2 => "Erreur : champ action non valide"
	);

	$GLOBALS['actions'] = array(
		'Connexion','Inscription'
	);

	$data = verifierInformation($_POST);

	if($data == 0) {
		$action = $_POST['action'];

		switch ($action) {
			case 'Connexion':
				$login = $_POST['action'];
				$pwd = $_POST['pwd'];
				$pwd = salt($pwd);
				$pwd = sha1($pwd);

				break;
			
			case 'Inscription':

				break;

			default:
				break;
		}
	} else {
		echo json_encode($GLOBALS['cases'][$data]);
	}

	
	
	function verifierInformation($data) {
		if(!isset($data['action']) || empty($data['action'])) {
			return 1;
		} 

		if(!in_array($data['action'], $GLOBALS['actions'])) {
			return 2;
		}
	}

	function salt($pwd) {
		$password = "*/[-";
		$password += $pwd;
		$password +="+([#";
		return $password;
	}
?>