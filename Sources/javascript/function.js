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

function remplir(temp,quantite){
	$.ajax({
		url : 'data.php',
		dataType : "json",
		data : {
			"action":"remplir",
			"temp": temp,
			"quantite": quantite,
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