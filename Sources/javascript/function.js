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
					document.location.href="EcoloInterface.phtml";
					
				} else {
					$("#connexionErreur").css("display","block");
				}
				
			},
			error:function(data)
			{
				
			},
		});
};		