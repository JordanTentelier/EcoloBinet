<?php
	session_start();
	if(!isset($_SESSION["connecte"]))
		header('Location:Homepage.php');
	$file = fopen("temp.txt","r");
	$temp = fgets($file);
	fclose($file);
?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css">
		<script type="text/javascript" src="javascript/tether-1.3.3/dist/js/tether.js"></script>
		<script type="text/javascript" src="javascript/jquery-3.2.1.js"></script>
		<script type="text/javascript" src="bootstrap/js/bootstrap.js"></script>
		<script type="text/javascript" src="javascript/function.js"></script>

		<script type="text/javascript">
			$(document).ready(function(){

				$("#QttEauType").on('click', function(){
					$Eau = $("#QttEau")[0].value;
					$Capacite = $("#capacitebaignoire")[0].value;

					if(this.value == '%') {
						this.value = 'L';
						if(($Eau != "" && $Capacite != "")) {
							$("#QttEau")[0].value =(($Eau)/100)*$Capacite;
							unlock("#QttEauErreur","#QttEauType");
						}
					} else {
						this.value = '%';
						if(($("#QttEau")[0].value != "" && $Capacite != "")) {
							$Eau = (($Eau)/$Capacite)*100;
							$("#QttEau")[0].value = $Eau;
						}
					}

					if(this.value == '%' && $Eau > 100){
						lock("veuillez entrer un nombre inférieur à 100","#QttEauErreur","#QttEauType");
					}
				})

				$("#capacitebaignoire").keyup(function(){
					if(isNaN(this.value)){
						lock("veuillez entrer un nombre","#capaciteErreur");
					} else {
						unlock("#capaciteErreur");
						if($("#QttEau")[0].value != "" && this.value != ""){
							if(this.value < parseInt($("#QttEau")[0].value )) {
								lock("La capacite de votre baignoire est inférieur à la quantité d'eau souhaitée","#capaciteErreur");
							} else {
								unlock("#QttEauErreur","#QttEauType");
							}
						} 
					} 
					
				})

				$("#QttEau").keyup(function() {

					if($("#QttEauType")[0].value == "%"){
						
						if(this.value > 100 && $("#QttEauType")[0].value == '%'){
							lock("veuillez entrer un nombre inférieur à 100","#QttEauErreur","#QttEauType");
						} else {
							unlock("#QttEauErreur","#QttEauType");
						}
					} else{
					
						if($("#capacitebaignoire")[0].value != ""){
							if(parseInt($("#capacitebaignoire")[0].value) < this.value){
								lock("veuillez entrer un nombre inférieur à la capacité de votre baignoire","#QttEauErreur","#QttEauType");
							} else {
								unlock("#QttEauErreur","#QttEauType");
								unlock("#capaciteErreur");
							}
						}
					}
					
				});

				$(".surface").keyup(function() {
					if(isNaN(this.value)) {
						lock("veuillez entrer un nombre","#"+this.name);
					} else {
						unlock("#"+this.name);
					}
				});


				$("#Temperature").keyup(function() {

					if($("#typeTemp")[0].value == '°C'){
						$temp = 37;
						$type = '°C';
					} else {
						$temp = 98.6;
						$type= '°F';
					}

					if(isNaN(this.value)) {
						lock("veuillez entrer un nombre","#TempErreur","#typeTemp");
					} else {
						unlock("#TempErreur","#typeTemp");

						if(this.value > $temp) {
							lock("veuillez entrer une température inférieur à "+$temp+$type,"#TempErreur","#typeTemp");
						} else {
							unlock("#TempErreur","#typeTemp");
						}
					}
		
				});

				$("#typeTemp").on('click', function(){
					$temperature = $("#Temperature")[0].value;

					if(this.value == '°C') {
						this.value = '°F';
						if($temperature != "" && $temperature <= 37){
							$("#Temperature")[0].value = Math.round((($temperature * 1.8)+32)*100)/100;
						}
					} else {
						this.value = '°C';
						if($temperature != "" && $temperature <= 98.6){
							$("#Temperature")[0].value = Math.round(($temperature -32)*(5/9)*100)/100;
						}
					}
				});

				$("#ButtonSurfCapa").on('click',function(){
					if(this.value == 'Surface')
					{
						$('#Capacite').hide();
						$('#Surface').show();
						this.value = 'Capacité';
					} 
					else
					{
						$('#Surface').hide();
						$('#Capacite').show();
						this.value = 'Surface';
					}
				});

				$("#deconnexion").on('click', function(){
					deconnexion();
				});

				$("#Remplissage").on('click', function(){
					verifierCondition();
				});

			});

		</script>

		<!-- CSS -->
		<style type="text/css">
		    .container {
                background-color: white;
                border-radius: 10px;
            }

            body {
                background-image: url(img/wallpaper.jpg);
            }

			.sous_titre {
				font-size : 16px;
				margin-left: 50px;
				width: 200px;
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

				.bigButton {
					width:90%;
					margin-bottom: 5px;
				}

				.divInput {
					width:100%;
					text-align:center;
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
			<div class="row" id="Capacite">
				<div class="sous_titre"> * Capacité de la baignoire :</div>
				<div class="divInput">
					<input id="capacitebaignoire" type="text" class="inputText" value="<?php if(isset($_SESSION["capa"])) echo $_SESSION["capa"];?>"/>
					<input type="button" value="L" disabled="disabled" />
				</div>
				<div class="alert alert-danger erreur" id="capaciteErreur" style="display:none">
				</div>
			</div>

			<div id="Surface" style="display:none;">
			
					<div class="row">
						<div class="sous_titre"> * Longueur intérieur : </div>
						<div class="divInput">
							<input type="text" class="inputText surface" id="longueur" name="longueurErreur"/>
							<input type="button" value="cm" disabled="disabled" />
						</div>
						<div class="alert alert-danger erreur" id="longueurErreur" style="display:none">
						</div>
					</div>

					<div class="row">
						<div class="sous_titre"> * Largeur intérieur : </div>
						<div class="divInput">
							<input type="text" class="inputText surface" id="largeur" name="largeurErreur"/>
							<input type="button" value="cm" disabled="disabled" />
						</div>
						<div class="alert alert-danger erreur" id="largeurErreur" style="display:none">
						</div>
					</div>

					<div class="row">
						<div class="sous_titre"> * Hauteur intérieur : </div>
						<div class="divInput">
							<input type="text" class="inputText surface" id="hauteur" name="hauteurErreur"/>
							<input type="button" value="cm" disabled="disabled" />
						</div>
						<div class="alert alert-danger erreur" id="hauteurErreur" style="display:none">
						</div>
					</div>
		
			</div>

			<div class="row" style="margin-top:10px;">
				<input class="btn btn-primary bigButton" id="ButtonSurfCapa" type="button" value="Surface"/>
			</div>


			<h4> Bain : </h4>


			<div class="row">
				<div class="sous_titre"> * Température : </div>
				<div class="divInput">
					<input type="text" class="inputText" id="Temperature" value="<?php if(isset($_SESSION["temp"])) echo $_SESSION["temp"];?>"/>
					<input type="button" value="°C" class="btn-primary" id="typeTemp"/>
				</div>
				<div class="alert alert-danger erreur" id="TempErreur" style="display:none">
				</div>
			</div>

			<div class="row">
				<div class="sous_titre"> T° Ambiante : </div>
				<div class="divInput">
					<input type="text" class="inputText" id="TempAmbiante" disabled="disabled" value="<?php echo $temp?>"/>
					<input type="button" value="°C" disabled="disabled" />
				</div>
			</div>

			<div class="row">
				<div class="sous_titre"> ** Quantité d'eau :</div>
				<div class="divInput">
					<input type="text" class="inputText" id="QttEau" value="<?php if(isset($_SESSION["pourcentage"])) echo $_SESSION["pourcentage"];?>"/>
					<input type="button" value="%" class="btn-primary" id="QttEauType"/>
				</div>
				<div class="alert alert-danger erreur" id="QttEauErreur" style="display:none">
				</div>
			</div>

			<div class="row" style="margin-top: 25px;">
				<input type="button" value="Remplissage" class="btn btn-primary bigButton" id="Remplissage"/>
				<input type="button" value="Nettoyage" class="btn btn-primary bigButton" id="Nettoyage"/>
			</div>

			<div class="alert alert-danger erreur" id="Erreur" style="display:none">
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


</html>
