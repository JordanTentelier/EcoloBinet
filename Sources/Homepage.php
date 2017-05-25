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

				$(document).keypress(function(e) {
				    if(e.which == 13) {
				        $("#connexion").trigger('click');
				    }
				});

				$("#type").on('click', function(){

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
					if(this.value > 100 && $("#type")[0].value == '%'){
						$('#QttEauErreur').show();
					} else {
						$('#QttEauErreur').hide();
					}
				})

				$("#connexion").on('click',function(){
					$("#loginErreur").css("display","none");
					$("#pwdErreur").css("display","none");
					$("#connexionErreur").css("display","none");

					$flag = 0;
					$login = $("#login").val();
					$pwd = $('#pwd').val();
			
					if($login == ""){
						$("#loginErreur").css("display","block");
						$flag = 1;
					}

					if($pwd == ""){
						$("#pwdErreur").css("display","block");
						$flag = 1;
					}
					
					if($flag != 1){
						connexion($login,$pwd);
					}
				})

			});

		</script>

		<style type="text/css">
		    .container {
                background-color: white;
                border-radius: 10px;
            }

            .center-div
{
  position: absolute;
  margin: auto;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  width: 100px;
  height: 100px;
  background-color: #ccc;
  border-radius: 3px;
}

            body {
                background-image: url(img/wallpaper.jpg);
            }

			.row {
				margin-top: 5px;
			}

			.btn-primary {
				cursor: pointer;
			}

			label {
				margin-right: 5px;
				width:150px;
				text-align:center;
			}

			input {
				width:200px;
			}

			.center {
				margin:auto;
				margin-top:10px;
				width:360px;
			}

			.bigButton {
				height:35px;
				width:200px;
				margin-left: auto;
				margin-right: auto;
			}

			.erreur {
				text-align:center;
				margin-bottom:0px;
				margin-top:5px; 
				padding:0px; 
				margin-left:auto;
				margin-right:auto; 
				width:95%;
			}

			/* @media Query */
			@media (max-width: 500px) {
				.container {
					width:100% !important;
				}
			}

			@media (max-width: 425px) {
				.bigButton {
					width:90%;
					margin-bottom: 5px;
				}

				#login, #pwd , label, .center, img{
					width: 100% !important;
				}
			}

		</style>
	</head>

	<body>

		<div class="container" style="width:500px;border: 1px solid black;padding-bottom: 10px">

			<div class="col-md-12" style="text-align:center;margin-bottom:20px;">
				<img src="img/logo.png" style="height:200px;width:300px;margin:auto"/>
			</div>

			<div class="row center">
				<label> Login : </label>
				<input type="text" id="login" />
				<div class="alert alert-danger erreur" id="loginErreur" style="display:none">
  					<strong>Erreur :</strong> veuillez entrer un login
				</div>
			</div>

			<div class="row center">
				<label> Mot de passe : </label>
				<input type="password" id="pwd" />
				<div class="alert alert-danger erreur" id="pwdErreur" style="display:none">
  					<strong>Erreur :</strong> veuillez entrer un mot de passe
				</div>
			</div>

			<div class="row" style="margin-top:20px">
				<input type="button" class="btn btn-primary bigButton" id="connexion" value="Connexion"/>
				<!-- <input type="button" class="btn btn-primary bigButton" value="Inscription"/> -->
				<div class="alert alert-danger erreur" id="connexionErreur" style="display:none">
  					<strong>Erreur :</strong> Login et/ou mot de passe incorrect
				</div>
			</div>
			
		</div>
	</body>
	
</html>