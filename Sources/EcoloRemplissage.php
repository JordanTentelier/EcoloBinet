<?php
    session_start();
    if(!isset($_SESSION["connecte"]))
        header('Location:Homepage.php');
    var_dump($_SESSION);
?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap">
        <link rel="stylesheet" type="text/css" href="css/jquery-ui">
        <script type="text/javascript" src="javascript/tether-1.3.3/dist/js/tether"></script>
        <script type="text/javascript" src="javascript/jquery-3.2.1"></script>
        <script src="javascript/jquery-ui"></script>
        <link rel="stylesheet" href="bootstrap-3.3.7/dist/css/bootstrap.min.css">
        <script type="text/javascript" src="bootstrap/js/bootstrap"></script>
        <script type="text/javascript">
            $(document).ready(function(){

                $("#annuler").on('click',function(){
                    console.log($("#annuler"));
                    if($("#annuler")[0].value == 'Annuler') {
                        $("#annuler")[0].value = 'Reprendre';
                        compteur2 = compteur;
                        compteur = time;
                    } else {
                        $("#annuler")[0].value = 'Annuler';
                        compteur = compteur2;
                    }
                    
                });

                var time = 10;
                var compteur = 0;
                var compteur2 = 0;

                setInterval(function() {ProgressBar()},1000);

                function ProgressBar(){
                    if(compteur != time) {
                        compteur = compteur + 1;
                        var pourcentage = parseInt(compteur/time*100);

                        $("#progressbar").html(pourcentage+"%");
                        $("#progressbar").width(pourcentage+"%"); 
                    }
                                   
                }

            });

        </script>

        <!-- CSS -->
        <style type="text/css">
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

                .inputText {
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
                        <input type="text" class="inputText" disabled="disabled" style="display:inline-block;margin-left:-3px;"/>
                    </div>
                </div>

                <div class="row">
                    <div class="sous_titre">Temps restant :</div>
                    <input type="text" class="inputText" disabled="disabled">
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