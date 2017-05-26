<?php
    session_start();
    if(!isset($_SESSION["connecte"]))
        header('Location:Homepage.php');
     var_dump($_SESSION);
	function updateFlow()
	{
		$flow = shell_exec('sudo python waterFlowSensor.py');
		echo $flow;
	}
?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
        <script type="text/javascript" src="javascript/tether-1.3.3/dist/js/tether.js"></script>
        <script type="text/javascript" src="javascript/jquery-3.2.1.js"></script>
        <script src="javascript/jquery-ui.js"></script>
        <link rel="stylesheet" href="bootstrap-3.3.7/dist/css/bootstrap.min.css">
        <script type="text/javascript" src="bootstrap/js/bootstrap.js"></script>
        <script type="text/javascript" src="jquery.countdown-2.2.0/jquery.countdown.js"></script>
        <script type="text/javascript" src="javascript/function.js"></script>

        <script type="text/javascript">
            $(document).ready(function(){
		$time=-1;
                $compteur = parseInt("<?php echo $_SESSION['compteur'] ?>");
                $fin = "<?php echo $_SESSION['fin']; ?>";
		$flow = <?php echo $_SESSION['flow']; ?>;
		$litreEcoule = <?php echo $_SESSION['litreEcoule']; ?>;
		$litreDemande= <?php echo $_SESSION['litreDemande']; ?>;
		$flag = 0;

		$(window).bind('beforeunload',function(){
			updateSession($time,$compteur,$litreEcoule);
		});

                $("#getting-started").countdown($fin, function(event) {
                    $tempRestant = event.strftime('%H:%M:%S');
                    $(this).text($tempRestant);				 		
		if($time==-1)
                {
		$splittime = $tempRestant.split(':');		     
		$time = parseInt($splittime[1])*60+parseInt($splittime[2]);
			console.log($time);
		}		
		});

                $("#annuler").on('click',function(){
                    if($("#annuler")[0].value == 'Annuler') {
                        $("#annuler")[0].value = 'Reprendre';
                        $flag = 1;
                        $('#getting-started').countdown('stop');
			electrovanne(false);
                    } else {
                        $("#annuler")[0].value = 'Annuler';
			if($litreEcoule!=$litreDemande)
				electrovanne(true);                   
			 updateCountDown();
                    }
                    
                });

		setInterval(function() {ProgressBar()},1000);
                
		function ProgressBar(){		
                    if($flag == 0) {
				$litreEcoule = $litreEcoule+$flow;
			var pourcentage = parseInt(($litreEcoule / $litreDemande)*100);
			if(pourcentage<100)
		        {
				//var pourcentage = parseInt($compteur/$time*100);
				$("#eauUtilise").val($litreEcoule.toFixed(2));
			}
			else
		    	{
				electrovanne(false);
				$litreEcoule = $litreDemande;	
				$("#eauUtilise").val($litreDemande);
				pourcentage=100;			
			}
                    	
			$("#progressbar").html(pourcentage+"%");
                        $("#progressbar").width(pourcentage+"%"); 
                    }
		}
				
            });

        </script>

        <!-- CSS -->
        <style type="text/css">
            #getting-started {
                width:150px;
            }
            .inputText, #getting-started {
                text-align: center;
            }

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
                margin-bottom: 10px;
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

            /* media query */
            @media (max-width: 820px) {
                .block {
                    width:100% !important;
                }

                .block:first-child {
                    margin-bottom: 10px;
                }

                .container {
                    width:100% !important;
                }
            }

            @media (max-width: 450px) {
                .sous_titre {
                    width:100%;
                    margin-left: 0px !important;
                    text-align: center;
                }

                .inputText , #getting-started{
                    width:90%;
                    margin:auto !important;
                    display: block !important;
                }
            }

        </style>

    </head>

    <body>
        <div class="container" style="width:900px;border: 1px solid black;padding-bottom: 10px">
                <div class="row" style="border-bottom: 1px solid black">
                    <label style="margin:auto"> Remplissage de la baignoire </label>
                </div>

                <div class="row">
                    <div class="sous_titre">T° Actuel du bain :</div>
                    <input type="text" class="inputText" disabled="disabled">
                </div>
                <div class="row">
                    <div class="sous_titre">T° Demandé :</div>
                    <input type="text" class="inputText" disabled="disabled" value="<?php echo $_SESSION["temp"];?> °C">
                </div>
                <div class="row">
                    <div class="sous_titre">T° Ambiante :</div>
                    <input type="text" class="inputText" disabled="disabled">
                </div>

                <div class="row">
                    <div class="block" style="display:inline-block;width:50%">
                        <div class="sous_titre" style="display:inline-block">Temps écoulé :</div>
                        <input type="text" class="inputText" disabled="disabled" style="display:inline-block;margin-left: -3px;"/>
                    </div>
               
                    <div class="block" style="display:inline-block;width:50%;">
                        <div class="sous_titre" style="display:inline-block">Litre(s) d'eau utilisé(s) :</div>
                        <input id="eauUtilise"type="text" class="inputText" disabled="disabled" style="display:inline-block;margin-left:-3px;"/>
                    </div>
                </div>

                <div class="row">
                    <div class="sous_titre">Temps restant :</div>
                    <div id="getting-started"> </div>
                </div>

                <div class="row">
                    <label style="margin-left:25px;font-size: 20px;font-weight:bold"> Progression : </label>
                </div>

                
                <div class="progress" style="margin-bottom: 30px;">
                    <div id="progressbar" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:0%"> 0% </div>
                </div>
                

                <div class="row">
                    <input type="button" class="bigButton btn btn-primary" value="Annuler" id="annuler" />
                </div>
            </div>
        
        </div>
    </body>
    
</html>
