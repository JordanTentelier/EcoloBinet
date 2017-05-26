function connexion(login,pwd){
	$.ajax({
			url : 'data.php',
			dataType : "json",
			data : {
					"action":"connexion",
					"login": login,
					"pwd" : pwd,
					},
			type : 'post',
			success: function(data)
			{
				if(data.feedback == "ok"){
					document.location.href="EcoloInterface.php";
					
				} else {
					$("#connexionErreur").css("display","block");
				}
				
			},
			error:function(data)
			{
				
			},
		});
};	

function electrovanne(flag){
	$.ajax({
			url : 'data.php',
			dataType : "json",
			data : {
					"action":"electrovanne",
					"flag": flag,
					},
			type : 'post',
			success: function(data)
			{},
			error:function(data)
			{},
		});
};		

function deconnexion(){
	$.ajax({
			url : 'data.php',
			dataType : "json",
			data : {
					"action":"deconnexion",
					"login": "<?php $_SESSION['Login'] ?>",
					"pwd" : "<?php $_SESSION['Password'] ?>",
					},
			type : 'post',
			success: function(data)
			{
				if(data.feedback == "Deconnection"){
					document.location.href="Homepage.php";
				} else {
					alert('error1');
				}
			},
			error:function(data)
			{
				alert('error2');
			},
		});
}

function remplir(temp,capacite,quantite,pourcentage){
	$.ajax({
		url : 'data.php',
		dataType : "json",
		data : {
			"action"		:"remplir",
			"temp"			: temp,
			"capacite" 		: capacite,
			"quantite"		: quantite,
			"pourcentage" 	: pourcentage
		},
		type : 'post',
		success: function(data)
		{
			if(data.feedback == "Remplir OK"){
				document.location.href="EcoloRemplissage.php";
			} else {
				alert('error1');
			}
		},
		error:function(data)
		{
			alert('error2');
		},
	});
}

function lock(message,divErreur,button=false){
	$(divErreur).html("<strong>Erreur :</strong> "+message);
	$(divErreur).show();
	if(button != false) {
		$(button).attr("disabled","true");
	}
}

function unlock(divErreur,button=false){
	$(divErreur).hide();
	if(button != false) {
		$(button).removeAttr("disabled");
	}
}

function verifierCondition(){
	var temp 		= $("#Temperature")[0].value;
	var Typetemp 	= $("#typeTemp")[0].value;
	var qtteau 		= $("#QttEau")[0].value;
	var capa 		= $("#capacitebaignoire")[0].value;
	var Typeqtt 	= $("#QttEauType")[0].value;
	var largeur		= $("#largeur")[0].value;
	var longueur	= $("#longueur")[0].value;
	var hauteur		= $("#hauteur")[0].value;
	var verifQttEau = qtteau;

	if(capa == ""){
		capa = (longueur*largeur*hauteur)/1000;
	}

	if(Typeqtt == "%"){
		verifQttEau = parseInt(qtteau/100*capa);
		$pourcentage = true;
	} else {
		$pourcentage = false;
	}

	if(!isNaN(temp) && temp != "" && !isNaN(qtteau) && qtteau != "" && !isNaN(capa) && capa != ""){
		if(verifQttEau <= capa){
			if(Typetemp == "°C"){
				if(temp > 37){
					lock("veuillez entrer une température inférieur à 37°C","#Erreur");
				} else {
					remplir(temp,capa,qtteau,$pourcentage);
				}
			} else if(Typetemp == "°F") {
				if(temp > 98.6){
					lock("veuillez entrer une température inférieur à 98.6°F","#Erreur");
				} else {
					remplir(temp,capa,qtteau,$pourcentage);
				}
			} else {
				lock("Unité de température non reconnue","#Erreur");
			}
		} else {
			lock("Quantité d'eau demandée > capacité de la baignoire","#Erreur");
		}
	} else {
		lock("Température / Quantite d'eau / Capacité : veuillez entrer un nombre","#Erreur");
	}

}

function updateCountDown (){
	$flag = 0;
    var today = new Date();
    today = today.getTime();
    today += ($time-$compteur)*1000;
    today = new Date(today);
    var day = today.getDate();
    var month = today.getMonth()+1; 
    var year = today.getFullYear();
    var hour = today.getHours();
    var minutes = today.getMinutes();
    var secondes = today.getSeconds();

    if(day<10){
        day='0'+day;
    } 
    if(month<10){
        month='0'+month;
    } 
    today = year+'/'+month+'/'+day+' '+hour+':'+minutes+':'+secondes;
    $('#getting-started').countdown(today);
}

function updateSession(time,compteur,litreEcoule) {
	$.ajax({
		url : 'data.php',
		dataType : "json",
		data : {
			"action"		:"updateSession",
			"time"			:time,
			"compteur"		:compteur,
			"litreEcoule"		:litreEcoule,
		},
		type : 'post',
		async : false,
	});
}
