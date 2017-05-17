<?php
	session_start();
	if(!isset($_SESSION["connecte"]))
		header('Location:Homepage.php');
	$file = fopen("temp.txt","r");
?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap">
		<script type="text/javascript" src="javascript/tether-1.3.3/dist/js/tether"></script>
		<script type="text/javascript" src="javascript/jquery-3.2.1"></script>
		<script type="text/javascript" src="bootstrap/js/bootstrap"></script>
		<script type="text/javascript" src="javascript/function.js"></script>

		<script type="text/javascript">
			$(document).ready(function(){

				$(".erreur").hide();

				$("#QttEauType").on('click', function(){

					if(this.value == '%') {
						this.value = 'L';
						$('#QttEauErreur').hide();
					} else {
						this.value = '%';
					}

					if(this.value == '%' && $("#QttEau")[0].value > 100){
						$('#QttEauErreur').show();
					}
				})

				$("#QttEau").keyup(function() {
					if(isNaN(this.value)){
						var Eau = this.value;
						Eau = Eau.substring(0,Eau.length-1);
						this.value = Eau;
					}
					if(this.value > 100 && $("#QttEauType")[0].value == '%'){
						$('#QttEauErreur').show();
					} else {
						$('#QttEauErreur').hide();
					}
				})

				$("#typeTemp").on('click', function(){
					if(this.value == '°C') {
						this.value = '°F'
						// TODO
						// ACTUALISER LA VALEUR DE #Temperature
					} else {
						this.value = '°C';
						// ACTUALISER LA VALEUR DE #Temperature
					}
				});

				$("#ButtonSurfCapa").on('click',function(){
					if(this.value == 'Surface')
					{
						$('#Capacite').hide();
						$('#Surface').show();
						this.value = 'Capacite'
					}
					else
					{
						$('#Surface').hide();
						$('#Capacite').show();
						this.value = 'Surface'
					}
				});

				$("#deconnexion").on('click', function(){
					deconnexion();
				});

			});

		</script>

		<!-- CSS -->
		<style type="text/css">
			.sous_titre {
				font-size : 16px;
				margin-left: 50px;
				width: 200px;
			}

			.sous_sous_titre {
				font-size : 16px;
				margin-left: 50px;
				margin-right: 15px;
				width: 150px;
			}

			.row {
				margin-top: 5px;
			}

			h4 {
				margin-top: 10px;
				text-decoration: underline
			}

			.bigButton {
				height:35px;
				width:200px;
				margin-left: auto;
				margin-right: auto;
			}

			.btn-primary {
				cursor: pointer;
			}

			.erreur {
				text-align:center;
				margin-bottom:0px;
				margin-top:5px;
				padding:0px;
				margin-left:auto;
				margin-right:auto;
				width:90%;
			}

			.info {
				margin:auto;
				font-size: 10px;
			}
			/* @media Query */
			@media (max-width: 500px) {
				.container {
					width:100% !important;
				}

				h4 {
					text-align:center;
				}

				.sous_titre {
					margin :0px;
					width:100%;
					text-align:center;
				}

				.inputText {
					width:80%;

				}

				.sous_sous_titre {
					margin :0px;
					width:100%;
					text-align:center;
				}

				.bigButton {
					width:90%;
					margin-bottom: 5px;
				}

				.divInput {
					width:100%;
					text-align:center;
				}

				#divSousSousTitre {
					width:80%;
					margin:auto !important;
				}

			}
		</style>

	</head>

	<body>
		<div class="container" style="width:500px;border: 1px solid black;padding-bottom: 10px">
			<div class="row" style="border-bottom: 1px solid black">
                    <label style="margin:auto"> Paramètrage </label>
                </div>


			<h4> Paramètres : </h4>
			<input class="btn btn-primary" id="ButtonSurfCapa" type="button" value="Surface">
			<div class="row" id="Capacite">
				<div class="sous_titre"> Capacité de la baignoire :</div>
				<div class="divInput">
					<input type="text" class="inputText"/>
					<input type="button" value="L" disabled="disabled" />
				</div>
			</div>

			<div class="row" id="Surface" style="display:none;">
			<div class="sous_titre"> Surface : </div>
				<div id="divSousSousTitre" style="margin-left: 50px">

					<div class="row">
						<div class="sous_sous_titre"> * Longueur intérieur : </div>
						<div class="divInput">
							<input type="text" class="inputText" />
							<input type="button" value="cm" disabled="disabled" />
						</div>
					</div>

					<div class="row">
						<div class="sous_sous_titre"> * Largeur intérieur : </div>
						<div class="divInput">
							<input type="text" class="inputText"/>
							<input type="button" value="cm" disabled="disabled" />
						</div>
					</div>

					<div class="row">
						<div class="sous_sous_titre"> * Hauteur intérieur : </div>
						<div class="divInput">
							<input type="text" class="inputText"/>
							<input type="button" value="cm" disabled="disabled" />
						</div>
					</div>
				</div>
			</div>


			<h4> Bain : </h4>


			<div class="row">
				<div class="sous_titre"> * Température : </div>
				<div class="divInput">
					<input type="text" class="inputText" id="Temperature" />
					<input type="button" value="°C" class="btn-primary" id="typeTemp"/>
				</div>
			</div>

			<div class="row">
				<div class="sous_titre"> T° Ambiante : </div>
				<div class="divInput">
					<input type="text" class="inputText" id="TempAmbiante" disabled="disabled" value="<?php echo fgets($file);?>"/>
					<input type="button" value="°C" disabled="disabled" />
				</div>
			</div>

			<div class="row">
				<div class="sous_titre"> ** Quantité d'eau :</div>
				<div class="divInput">
					<input type="text" class="inputText" id="QttEau"/>
					<input type="button" value="%" class="btn-primary" id="QttEauType"/>
				</div>
				<div class="alert alert-danger erreur" id="QttEauErreur">
  					<strong>Erreur :</strong> veuillez entrer un nombre inférieur à 100
				</div>
			</div>

			<div class="row" style="margin-top: 25px;">
				<input type="button" value="Remplissage" class="btn btn-primary bigButton"/>
				<input type="button" value="Nettoyage" class="btn btn-primary bigButton"/>
			</div>

			<div class="row" style="margin-top:25px;">
				<input type="button" value="Deconnexion" class="btn btn-primary bigButton" style="width:150px;" id="deconnexion"/>
			</div>

			<div class="row">
				<label class="info"> * : champ obligatoire pour remplissage </label>
			</div>
			<div class="row">
				<label class="info"> ** : champ obligatoire pour les deux options </label>
			</div>
		</div>

	</body>

<?php fclose($file); ?>
</html>