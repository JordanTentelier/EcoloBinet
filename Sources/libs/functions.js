// Fonction qui fait apparaitre une bandelore en haut de l'ecran 
// et empeche de réappuyer sur le même bouton pendant 1.5s
function appears(id,text,classe,button){
	if($verif == 0)
	{
		var newbutton="#"+button;
		$(newbutton).prop("disabled","disabled");
		if($appears == 1)
		{
			$appears = 0;
			$($newid).hide();
		}

		$verif = 1;
		var string="<div id=";
		string+=id;
		string+=" class=";
		string+=classe;
		string+=">";
		string+=text;
		string+="</div>";
		$newid="#"+id;
		$("#appears").prepend(string);
		$($newid).hide();
		$($newid).slideDown("slow");
		$verif = 0;
		$appears = 1;
		$(newbutton).prop("disabled",false);
	}
};


// Envoie le login a data.php
// On rentre dans le case 'connexion'
// Si data.feedback n'est pas Ok , la connexion a echoué et on affiche l'erreur
// Sinon on s'est bien log, on affice la connexion et on redirige vers menu.php
function connexion(login){
	$.ajax({
			url : 'data.php',
			dataType : "json",
			data : {
					"action":"connexion",
					"login": login
					},
			type : 'post',
			success: function(data)
			{
				$("body").css({"cursor":"default"});
				if(data.feedback != "ok")
					{
						$("#deroulant").fadeOut("slow"); // range le menu deroulant
						$("#connexion").html("Scannez votre code : <input type='text' id='login'>");
						appears("LogFail"," * Erreur de connexion , code scanne non valide * ","Erreur","false");
					}
				else
					{
					// les variables de session sont enregistrées coté serveur 
					// on reload pour les obtenir								
						appears("LogIn"," * Connexion R&eacute;ussie * ","valide","none");
						if(data.admin == 2)
							setTimeout(function(){document.location.href="Admin.php?view=Accueil";},1500)
						else
							setTimeout(function(){document.location.href="menu.php?view=Accueil";},1500);												
					}
			},
			error:function(data)
			{
				$("body").css({"cursor":"default"});
				$("#deroulant").fadeOut("slow"); // range le menu deroulant
				$("#connexion").html("Scannez votre code : <input type='text' id='login'>");
				appears("LogFail"," * Erreur de connexion , code scanne non valide * ","Erreur","none");
			},
		});
};
			

// Lors du scan + "entrer"
// On affiche le gif du scan en cours ...
// puis au bout de 3sec on appelle la fonction de connexion 
function transaction(login){
	$("#deroulant").fadeIn("slow");
	$("#deroulant").empty();
	$("#deroulant").append('<img src="../images/loading3.gif" width="100%" height="250px">');
	$("#connexion").html("Scan effectu&eacute;");
	setTimeout(function() {connexion(login);} , 2000);
};

// Fonction qui recupère les info de la derniere action de la fiche d'emargemment
// puis les affiche dans la div info de la page login 
function lastupdate(){
	$.ajax({
			url : 'data.php',
			dataType : "json",
			data : {
					"action":"recuperation",
					},
			type : 'post',
			success: function(data)
			{
				$.each(data.infos, function() {
					$("#info").append("<u>Derni&egrave;re Modification :</u>");
					$("#info").append("<br><br>R&eacute;f&eacute;rence : "+this.Reference);
					$("#info").append("<br>Pi&egrave;ce :"+this.Nom_Piece);
					$("#info").append("<br> Quantite :"+this.Nombre);
					$("#info").append("<br> Nom :"+this.Name);
					$("#info").append("<br> Prenom:"+this.Surname);
					$("#info").append("<br> Date : "+this.Date);
				});
			},
			error:function(data)
			{
				appears("EcheclastUpdate"," * Erreur Inconnue , consulter le debogueur * ","Erreur","");
			}
		});
};


// Fonction qui permet la deconnexion d'une session 
function deconnexion(){
	$.ajax({
			url : 'data.php',
			dataType : "json",
			data : {
					"action":"logout",
					"login": "<?php $_SESSION['Login'] ?>"
					},
			type : 'post',
			success: function(data)
			{
				appears("deco"," * Deconnexion r&eacute;ussie , redirection en cours ... * ","valide","deco");
				setTimeout(function() {document.location.href="login.php";} , 2000);
			},
			error:function(data)
			{
				appears("EchecDeco"," * Erreur Inconnue , consulter le debogueur * ","Erreur","deco");
			}
		});
};

// Fonction qui affiche toute les infos sur une reference 
function RechercheAvance(Refobj)
{
	$("#pointeur").css({"display":"none"});
	$("#infoclick").html("");

		$.ajax({
				url : 'data.php',
				dataType : "json",
				data : {
					"action": "RechercheAvance",
					"piece": Refobj
				},
				type : 'post',
				success: function(data)
				{
					if(data.piece != "")
					{
						$left=data.piece[0].gauche-30;
						$top=parseInt(data.piece[0].haut);
						$top+=25;
						$("#pointeur").css({"left":$left,"top":$top,"display":"block"});
						$("#infoclick").html("");

						$("#infoclick").append("<h1> Informations </h1>");
						$("#infoclick").append("<table id='tab_infoclick' style=\"display:block;max-height:240px;overflow:auto;font-size:14px;background-color:white\" ></table>");
						$("#tab_infoclick").append("<tbody style='overflow-y:hidden;display:block;font-size:8px;'></tbody");
						$("#tab_infoclick tbody").append("<tr> <td style='min-width:80px;max-width:80px'>NOM</td><td style='min-width:268px;max-width:268px'>"+data.piece[0].Nom_Piece+"</td> </tr>");
						$("#tab_infoclick tbody").append("<tr> <td style='min-width:80px;max-width:80px'>REF</td><td style='min-width:268px;max-width:268px'>"+data.piece[0].Reference+"</td> </tr>");
						$("#tab_infoclick tbody").append("<tr> <td style='min-width:80px;max-width:80px'>SAP</td><td style='min-width:268px;max-width:268px'>"+data.piece[0].SAP+"</td> </tr>");
						$("#tab_infoclick tbody").append("<tr> <td style='min-width:80px;max-width:80px'>FOUR</td><td style='min-width:268px;max-width:268px'>"+data.piece[0].Fournisseur+"</td> </tr>");
						$("#tab_infoclick tbody").append("<tr> <td style='min-width:80px;max-width:80px'>TYPE</td><td style='min-width:268px;max-width:268px'>"+data.piece[0].Type+"</td> </tr>");
						$("#tab_infoclick tbody").append("<tr> <td style='min-width:80px;max-width:80px'>MIN</td><td style='min-width:268px;max-width:268px'>"+data.piece[0].StockMini+"</td> </tr>");
						$("#tab_infoclick tbody").append("<tr> <td style='min-width:80px;max-width:80px'>QTT</td><td style='min-width:268px;max-width:268px'>"+data.piece[0].Quantite+"</td> </tr>");
						$("#tab_infoclick tbody").append("<tr> <td style='min-width:80px;max-width:80px'>EMP</td><td style='min-width:268px;max-width:268px'>"+data.piece[0].Emplacement+"</td> </tr>");
						


						/*
						$("#infoclick").append("<h1> Informations </h1>");
						$("#infoclick").append("<u>Nom de la pièce:</u> "+data.piece[0].Nom_Piece);
						$("#infoclick").append("</br><u>Référence:</u> "+data.piece[0].Reference);
						$("#infoclick").append("</br><u>SAP:</u> "+data.piece[0].SAP);
						$("#infoclick").append("</br><u>Fournisseur:</u> "+data.piece[0].Fournisseur);
						$("#infoclick").append("</br><u>Type:</u> "+data.piece[0].Type);
						$("#infoclick").append("</br><u>StockMini:</u> "+data.piece[0].StockMini);
						$("#infoclick").append("</br><u>Nombre de pièce(s) disponible(s):</u> "+data.piece[0].Quantite);
						$("#infoclick").append("</br><u>Emplacement exact:</u> "+data.piece[0].Emplacement);
						*/

					}
					else
						RechercheRef(Refobj);
				},
				error:function(data)
				{
					appears("EchecRechercheRef"," * Erreur Inconnue , consulter le debogueur * ","Erreur","");
				}
			});
}

// Fonction qui permet de remplir le tableau avec les resultats de la recherche effectué
// Utilisée 3 fois car on recherche par Nom_Piece , Reference et SAP 
function donnee(obj)
{
	$.each(obj,function(){

		$verif=0;
		$nom = this.Nom_Piece;
		$ref = this.Reference;
		$sap = this.SAP;
		$qtt = this.Quantite;
		$stck = this.StockMini;

		$("#Resultats tbody tr").each(function(){
			if($ref == $(this).find(".ref").html())
			{
				$verif = 1;
			}
		});	

		if($verif == 0)
		{
			var string="<td style=\"width:151px;\"> "+$nom+"</td><td class=\"ref\" style=\"width:165px;max-width:165px;\"> <input type=\"button\" value=\""+$ref+"\" onclick=\"RechercheAvance(this.value)\"/></td><td style=\"width:145px;max-width:145px\">"+$sap+"</td>";
			$("#Resultats tbody").append("<tr>"+string+"</tr>");
			$compteur++;
		}
	})
}

// Fonction qui permet de rechercher une pièce 
// Recherche par reference , nom piece et SAP avec la fonction donnee()
// Compte et affiche le nombre de pièce trouvée par la recherche
function RechercheRef(ref){
	$("#pointeur").css({"display":"none"});
	$("#infoclick").html("");
	if(ref !="")
	{
		$.ajax({
				url : 'data.php',
				dataType : "json",
				data : {
					"action": "RecherchePiece",
					"piece": ref
				},
				type : 'post',
				success: function(data)
				{
					if(data.Nom != "" || data.Reference != "" || data.SAP != "")
					{
						$compteur=0;
						$("#infoclick").append("<table id=\"Resultats\" style=\"display:block;max-height:230px;font-size;14px;overflow:auto;background-color:white\" class=\"tabmagasin\"></table>")
						$("table").append("<thead style=\"display:block;padding-right:14px;\"><TR><td style=\"width:143px\"><a id=\"nom\">Nom</a></td> <td style=\"width:160px\"><a id=\"ref\">Référence</a></td> <td style=\"width:143px\"><a id=\"sap\">S.A.P</a></td> </TR> </thead><tbody style=\"display:block;overflow-y:scroll;overflow-x:hidden;font-size:12px\"></tbody>");
						$("#infoclick tbody").attr("style","max-height:175px");

						donnee(data.Nom);
						donnee(data.Ref);
						donnee(data.SAP);
						if($compteur == 0)
							$("#infoclick").html("");

						$("#infoclick").prepend("<h1> Résultats : "+$compteur+"</h1>");

					}
				},
				error:function(data)
				{
					appears("EchecRechercheRef"," * Erreur Inconnue , consulter le debogueur * ","Erreur","");
				}
			});
	}
};



// Fonction qui permet d'afficher/cacher la table 
// et remplace la valeur du bouton par > ou < 
function afficherdiv(btn){
	if(btn.value ==">")
	{
		$("#div"+btn.name).show("slow"); // affiche lentement la div
		$("#"+btn.id).val("<"); // on change la valeur du bouton
	}
	else
	{
		$("#div"+btn.name).hide("slow");
		$("#"+btn.id).val(">");
	}
}

// Fonction qui permet de créer la table 
function maketable(){
	$("table").append("<thead style=\"display:block;padding-right:14px;\"><TR><td style=\"width:130px\"><a id=\"nom\">Nom</a></td> <td style=\"width:106px\"><a id=\"ref\">Référence</a></td> <td style=\"width:88px\"><a id=\"sap\">S.A.P</a></td> <td style=\"width:34px\"><a id=\"qtt\">Qtt</a></td> <td style=\"width:34px\"><a id=\"stock\">Mini</a></td> </TR> </thead><tbody style=\"display:block;overflow-y:scroll;overflow-x:hidden;font-size:12px\"></tbody>");
}

// Fonction qui permet de recuperer et d'afficher les informations du rack cliqué
// et de remplir les tables de couleur prealablement créer 
function InfoRack(critere)
{
	$.ajax({
			url : 'data.php',
			dataType : "json",
			data : {
				"action" : "InfoRack",
				"critere" : critere
				},
			type : 'post',
			success : function(data)
			{
				maketable(); 
				var num = 0
				$.each(data.inforack,function(){
					if((critere == "A" && this.Emplacement != "armA" && this.Emplacement != "armB" && this.Emplacement != "armC" && this.Emplacement != "armD" && this.Emplacement != "armE") || critere != "A")
					{
						num++;
						var string="<td style=\"width:138px;max-width:138px\">"+this.Nom_Piece+"</td><td style=\"width:114px;max-width:114px\">"+this.Reference+"</td><td style=\"width:106px;max-width:96px\">"+this.SAP+"</td><td style=\"width:40px\">"+this.Quantite+"</td><td style=\"width:40px\">"+this.StockMini+"</td>";
						if(this.Priorite == 1)
							$("#divViolet tbody").append("<tr>"+string+"</tr>");
						
						if (this.Quantite == 0)
							$("#divRouge tbody").append("<tr>"+string+"</tr>");

						$("#divJaune tbody").append("<tr>"+string+"</tr>");
					}
				});
				if(num == 0 || num == 1 )
					$("#TitleRack").append(" ("+num+" pièce) ");
				else
					$("#TitleRack").append(" ("+num+" pièces) ");

				// On cache les divs en attendant la demande d'affichage
				$("#divJaune").hide(); 
				$("#divViolet").hide();
				$("#divRouge").hide();
			},
			error:function(data)
			{
				appears("EchecInfoRack"," * Erreur Inconnue , consulter le debogueur * ","Erreur","");
			},
		});
};


// Fonction qui remplit les carrés de couleur de l'image du magasin
// avec le nombre de pièces du rack représenté correspondant au critère
// et qui compte le nombre total de pièce pour l'afficher dans la div info 
function RemplirCarreCouleur(plus=""){
	$.ajax({
			url : 'data.php',
			dataType : "json",
			data : {
					"action":"StockCouleur",
					"plus":plus,
					},
			type : 'post',
			success: function(data) //nom ref quantite stock type
			{
				var nb;
				$stock=0; // compte le nb de piece sous le stock
				$prio=0; // compte le nb de piece en priorite
				$rupt=0; // compte le nombre de piece en rupture

				$.each(tableau,function(){
					$lettre = this.lettre;
					$id1 = this.id1; // carre violet
					$id2 = this.id2; // carre jaune
					$id3 = this.id3; // carre rouge
							

					$.each(data.stockcouleur, function() {
						if((this.Emplacement).indexOf($lettre) == 0)
						{
							if(this.Quantite == 0)
							{
								nb = $("#label_"+$id3).html();
								nb++;
								$rupt++;
								$("#label_"+$id3).html(nb);
							}
								
							if(this.Priorite == 1)
							{
								nb = $("#label_"+$id1).html();
								nb++;
								$prio++;
								$("#label_"+$id1).html(nb);
							}

							nb = $("#label_"+$id2).html();
							nb++;
							$stock++;
							$("#label_"+$id2).html(nb);
						}

						if($("#label_"+$id1).html() == "")
							$("#label_"+$id1).html("0");

						if($("#label_"+$id2).html() == "")
							$("#label_"+$id2).html("0");

						if($("#label_"+$id3).html() == "")
							$("#label_"+$id3).html("0");
					});	

				});	

				$("#prio").append($prio);
				$("#rupt").append($rupt);
				$("#stock").append($stock);

			},
			error:function(data)
			{
				appears("EchecCarreCouleur"," * Erreur Inconnue , consulter le debogueur * ","Erreur","");
			}

		});
};


// Fonction qui permet de réaliser l'ajout d'une reference dans la base de donnée
// on recupere toute les données du formulaire pour les transmettre a data.php
// Si la création de la ref est reussi ( data.feedback == " ajout reussi")
// On affiche le message de réussite et on reload la page au bout d'une seconde
// Sinon on affiche le message d'erreur
function AjoutReference(nom_piece,ref,fournisseur,sap,prio,qtt,stock,emp,type){
	$.ajax({
			url : 'data.php',
			dataType : "json",
			data : {
					"action":"ajoutref",
					"nom_piece": nom_piece,
					"ref":ref,
					"sap":sap,
					"prio":prio,
					"fournisseur":fournisseur,
					"qtt":qtt,
					"stock":stock,
					"emp":emp,
					"type":type
					},
			type : 'post',
			success: function(data)
			{
				appears("ajout"," * Ajout de la nouvelle r&eacute;f&eacute;rence R&eacute;ussie * ","valide","ValiderR");
				setTimeout(function() {location.reload();}, 1000);		
			},
			error:function()
			{
				appears("failajout"," * Erreur reference ou nom existant * ","Erreur","ValiderR");
			}			
		});
};

// Fonction qui permet de verifier que tout les champs de la création/suppression d'une référence sont remplis
// Permet d'eviter d'envoyer des champs vide 
function Verification(action){
	if(action=="creer")
	{
		if($("#name").val()=="" || $("#ref").val()=="" || 
			$("#qtt").val()=="" || $("#stock").val() =="" || 
			$("#type").val()=="" || $("emp").val()=="")
			return false;
		else
			if($("#qtt").val().charCodeAt(0) < 48 || $("#qtt").val().charCodeAt(0) > 57 || 
				$("#stock").val().charCodeAt(0) < 48 || $("#stock").val().charCodeAt(0) > 57)
				return false;

			else
				return true;
	}

	else
	{
		if($("#name").val()== null && $("#ref").val()== null)
			return false;

		else
			return true;
	}
				
}


// Fonction qui permet de recuperer toute les valeurs d'une colonne d'une table
// afin de créer des <select> <option> </option> </select>
// Evite de creer un emplacement a01 et a1 et A01 et A1
function Recup(cible,table,ordre=false,codebarre=false)
{

	$.ajax({
			url : 'data.php',
			dataType : "json",
			data : {
					"action":"Recup",
					"cible":cible,
					"table":table,
					"order":ordre
					},
			type : 'post',
			success: function(data)
			{
				var i=1;
				$.each(data.Recup,function(){
					if(codebarre == false)
					{
						if(cible == "Emplacement")
							$("#emp").append("<option>"+this.Emplacement+"</option>");

						else if (cible == "Type")
							$("#type").append("<option>"+this.Type+"</option>");

							else if (cible == "Nom_Piece")
								$("#name").append("<option>"+this.Nom_Piece+"</option>");

								else if (cible == "Reference")
									$("#ref").append("<option>"+this.Reference+"</option>");

									else if (cible == "Name,Surname")
										$("#u_nom").append("<option>"+this.Name+" "+this.Surname+"</option>");

										else
											$("#u_login").append("<option>"+this.Login+"</option>");

					}
					else
					{
						$("#cdb").append("<div style=\"background-color:white;width:272px;height:82px;min-width:272px;min-height:82px;border:1px solid black;display:inline-block;\" id="+i+"></div>")
										
										$("#"+i).barcode(this.Reference,"code128",{barWidth:1,barHeight:28,fontSize:12});
										$("#"+i).prepend("<div style=\"display:block;font-size:12;text-align:left;margin-bottom:5px\">"+this.Nom_Piece+"<div style=\"display:inline-block;float:right\">"+this.Emplacement+"</div></div>");
										
										i++;
					}
				$("body").css({"cursor":"default"});
				});
				
				$("#name").val("");
				$("#ref").val("");
			},
			error:function(data)
			{
				appears("EchecRecup"," * Erreur Inconnue , consulter le debogueur * ","Erreur","");
			}
		});
};

// Fonction qui retourne la date au format locale
// ici : Date en Français
function DateLocale(date)
{
	return date.toLocaleFormat();
}

// Fonction qui permet de réaliser les audits
// On clear le tableau d'audit
// puis on le remplit avec les données récuperer par requete SQL dans bdd.php fonction audit();
// puis on affecte a la variable $audit le type : soit " Audit Hebdomadaire " soit " Audit Annuelle "
function Audit(type,nombre){
	$.ajax({
			url:'data.php',
			dataType:"json",
			data: {
					"action":"audit",
					"type":type,
					"nombre":nombre,
				},
			type:"post",
			success : function(data)
			{
				$("tbody").html("");
				$.each(data.audit, function() {
					var string = "<TR><td style=\"width:229px;max-width:229px;height:20px\">";
					string += this.Nom_Piece;
					string += "</td>";	

					string += "<td style=\"width:126px;max-width:126px;height:20px\">";
					string += this.Reference;
					string += "</td>";	

					string += "<td style=\"width:118px;max-width:118px;height:20px\">";
					string += this.Fournisseur;
					string += "</td>";

					string += "<td style=\"width:64px;max-width:64px;height:20px\">";
					string += this.Quantite;
					string += "</td>";

					string += "<td style=\"width:64px;max-width:64px;height:20px\">";
					string += this.StockMini;
					string += "</td>";

					string += "<td style=\"width:64px;max-width:64px;height:20px\">";
					string += this.Emplacement;
					string += "</td>";

					string += "<td style=\"width:60px;max-width:60px;height:20px\">";
					string += "";
					string += "</td>";

					$("tbody").append(string);
				});

				$audit = type;
			},
			error:function(data)
			{
				appears("EchecAudit"," * Erreur Inconnue , consulter le debogueur * ","Erreur","");
			}

		});
};


// Fonction qui permet d'initialiser le tableau des pieces a commander
// on creer une a une les lignes du tableau en leur affectant les valeurs 
// et la couleur selon les critères
function InitTab(ordre,recherche,critere,plus="Tous"){
	$.ajax({
			url : 'data.php',
			dataType : "json",
			data : {
					"action":"rupture",
					"recherche":recherche,
					"ordre":ordre,
					"critere":critere,
					"plus":plus,
					},
			type : 'post',
			success: function(data) 
			{
				var i=0;
				$.each(data.stock, function() {
					if(this.Priorite == 1)
						var string="<TR style=\"background-color:#6C0277\"><td style=\"width:183px;max-width:183px;height:20px\">";

					else if (this.Quantite == 0)
						var string="<TR style=\"background-color:#FE1B00\"><td style=\"width:183px;max-width:183px;height:20px\">";
						
						else
							var string="<TR style=\"background-color:#FFD700\"><td style=\"width:183px;max-width:183px;height:20px\">";

					string += this.Nom_Piece + " / " + this.SAP;
					string += "</td>";	

					string += "<td id=\""+i+"\"style=\"width:330px;max-width:330px;min-width:330px;height:20px;background-color:white\">";
					string += "</td>";	

					string += "<td style=\"width:38px;max-width:38px;height:20px\">";
					string += this.Quantite;
					string += "</td>";	

					string += "<td style=\"width:38px;max-width:38px;height:20px\">";
					string += this.StockMini;
					string += "</td>";	  

					string += "<td style=\"width:42px;max-width:42px;height:20px\">";
					string += this.Emplacement;
					string += "</td>";

					string += "<td style=\"width:100px;max-width:100px;height:20px;\">";
					string += this.Fournisseur;
					string += "</td>";		

					$(".corps tbody").append(string);
					$("#"+i).barcode(this.Reference,"code128",{barWidth:1,barHeight:28,fontSize:12});
					i++;
				});
				
				$("#pieces").html(i+" pièces");
			},
			error:function(data)
			{
				appears("EchecInitTab"," * Erreur Inconnue , consulter le debogueur * ","Erreur","");
			}
		});
};

// Fonction qui permet d'initialiser le tableau historique
// on crée les lignes une par une en leur affectant les valeurs
// et la couleur selon l'action (ajout 0  ou retrait 1)
function InitTab1(ordre,recherche,critere,limit,autre=" "){
	$.ajax({
			url : 'data.php',
			dataType : "json",
			data : {
					"action":"historique",
					"recherche":recherche,
					"ordre":ordre,
					"critere":critere,
					"limit":limit,
					"autre":autre,
					},
			type : 'post',
			success: function(data) //nom ref quantite stock type
			{
				$.each(data.historique, function() {

					if(this.action == 0)
					{
						var string="<TR style=\"background-color:green\"><td style=\"width:183px;max-width:183px;height:20px\">";
						var string2=this.QttRest+" (+"+this.Nombre+")";
					}

					else if (this.action == 1)
					{
						var string="<TR style=\"background-color:#FE1B00\"><td style=\"width:183px;max-width:183px;height:20px\">";
						var string2=this.QttRest+" (-"+this.Nombre+")";
					}

					string += this.Nom_Piece +" + "+this.Reference;
					string += "</td>";	

					string += "<td style=\"width:148px;max-width:148px;height:20px\">";
					string += this.SAP
					string += "</td>";

					string += "<td style=\"width:68px;max-width:68px;height:20px\">";
					string += string2;
					string += "</td>";	

					string += "<td style=\"width:39px;max-width:39px;height:20px\">";
					string += this.StockMini
					string += "</td>";

					string += "<td style=\"width:144px;max-width:144px;height:20px\">";
					string += this.Name+" "+this.Surname;
					string += "</td>";	  

					string += "<td style=\"width:144px;max-width:144px;height:20px\">";
					string += this.Date;
					string += "</td>";	

					$(".corps tbody").append(string);
				});
			},
			error:function(data)
			{
				appears("EchecInitTab1"," * Erreur Inconnue , consulter le debogueur * ","Erreur","");
			}
		});
};

function Restart(statut)
{

	if(statut == 0)
	{
		var button = "#confirmer_ajout";
		var tab = "#ajoutpiece";
	}
	else
	{
		var button = "#confirmer_retrait";
		var tab = "#retraitpiece";
	}

	console.log(statut);
	console.log(button);
	console.log(tab);
	$(""+tab+"").hide();
	$(""+tab+" tbody").html("");
	$(""+button+"").css({"display":"none"});
	$("#effacer_saisie").css({"display":"none"});
	$("#scanning").prop("disabled",false);
	$("#scan").html("<img src='../images/loading.gif'><i> En attente de scan </i><img src='../images/loading.gif'>");
	$(".tableau div").css({"background":"url(../images/lecteur.gif)no-repeat",
							"background-position":"245px",
							"background-color":"white"});
	$("#scan").show();
	setTimeout(function(){deconnexion()},300000);
}


// Fonction qui permet de mettre a jour la base de donnée
// Sert lors de l'ajout ou du retrait d'une piece
// affiche un message d'erreur lors de l'echec 
// affiche un message de validation lors de la réussite
function UpdateBdd(qtt,ref,statut){
	$.ajax({
			url : 'data.php',
			dataType : "json",
			data : {
					"action":"UpdateBdd",
					"reference":ref,
					"quantite":qtt,
					"statut":statut,
					},
			type : 'post',
			success:function(data)
			{
				if(data.feedback == "Pas assez de piece")
					alert("la piece de reference :" +ref+ " n'a pas été retirée correctement ( stock < demande )");
						
				else if(data.feedback == "Retrait reussi")
					appears("RetraitOk"," * Retrait des r&eacute;f&eacute;rences r&eacute;ussi * ","valide","confirmer_retrait");

					else if(data.feedback == "Ajout reussi")
						appears("AjoutOk"," * Ajout des r&eacute;f&eacute;rences r&eacute;ussi * ","valide","confirmer_ajout");

						else if(statut == 0)
							appears("echec"," * Echec de l'ajout de r&eacute;f&eacute;rence * ","erreur","confirmer_ajout");
	
							else
								appears("echec"," * Echec de retrait de r&eacute;f&eacute;rence * ","erreur","confirmer_retrait");
				
				$statut=0;
				setTimeout(function(){Restart(statut);},2000);
			},
			error:function(data)
			{
				alert("* Erreur inconnue : consulter le debogueur *");
			}
		});
	
};

// Fonction qui permet d'ajouter la piece scanné dans le tableau de retrait de piece
// Si la quantité de la piece est de 0 ou que la reference est inexistante, on affiche un message d'erreur 
// Sinon on ajoute les infos de la piece dans le tableau et on affiche un message de validation de scan 
function AjoutDansTabR(reference){
	$.ajax({
			url : 'data.php',
			dataType : "json",
			data : {
					"action":"RechercheRef",
					"reference":reference,
					"statut":"Retrait"
					},
			type : 'post',
			success: function(data)
			{
				if(data.feedback == "Retrait impossible")
					appears("RuptureStock"," * Erreur : Stock 0 atteint * ","Erreur","confirmer_retrait");

				else if(data.feedback == "Pas de ref")
					appears("NonRef"," * Erreur : Reference non valide * ","Erreur","confirmer_retrait");

					else
					{
						if($statut == 0)
						{
							$statut=1;
							$("#scan").hide();
							$("#retraitpiece").css({"display":"block"});
							$(".button").show();
							$("#confirmer_retrait").css({"display":"inline-block"});
							$("#effacer_saisie").css({"display":"inline-block"});
						}

						$.each(data.reponse,function(){

						var string = "<TR><td style=\"width:200px;max-width:200px\">";
						string += this.Nom_Piece;
						string += "</td>";	

						string += "<td class=\"ref\" style=\"width:133px;max-width:133px\">";
						string += this.Reference;
						string += "</td>";	

						string += "<td style=\"width:133px;max-width:133px\">";
						string += this.SAP;
						string += "</td>";

						string += "<td style=\"width:88px;max-width:88px\"> <select class=\"qtt\" style=\"width:60px\">";
						string += "<option> 1 </option>"
						string += "<option> 2 </option>"
						string += "<option> 3 </option>"
						string += "<option> 4 </option>"
						string += "<option> 5 </option>"
						string += "<option> 10 </option>"
						string += "</select> </td>";	

						string += "<td style=\"width:98px;max-width:98px\">";
						string += this.Type;
						string += "</td>";
									
						string += "<td style=\"width:78px;max-width:78px\"><input class='ch' type='checkbox'/></td></TR>"
							$("tbody").append(string);	
							appears("RetraitOk"," * Scan de la R&eacute;f&eacute;rence r&eacute;ussi * ","valide","confirmer_retrait");
						});	
					}
			},
			error:function(data)
			{
				appears("EchecAjoutTabR"," * Erreur Inconnue , consulter le debogueur * ","Erreur","confirmer_retrait");
			}			
		});
};

// Fonction qui permet d'ajouter les infos de la pièce scannée dans le tableau d'ajout de pièce
// si la référence est inexistante alors on affiche un message d'erreur
// Sinon on ajoute les infos de la pièce dans le tableau d'ajout de pièce et on affiche un message de validation
function AjoutDansTabA(reference){
	$.ajax({
			url : 'data.php',
			dataType : "json",
			data : {
					"action":"RechercheRef",
					"reference":reference,
					"statut":"ajout"
					},
			type : 'post',
			success: function(data)
			{
				if(data.feedback == "Pas de ref")
					appears("NonRef"," * Erreur : Reference non valide * ","Erreur","confirmer_ajout");

				else
				{
					if($statut == 0)
					{
						$statut=1;
						$("#scan").hide();
						$("#ajoutpiece").css({"display":"block"});
						$(".button").show();
						$("#confirmer_ajout").css({"display":"inline-block"});
						$("#effacer_saisie").css({"display":"inline-block"});
					}

					$.each(data.reponse,function(){
						var string = "<TR><td style=\"width:200px;max-width:200px\">";
						string += this.Nom_Piece;
						string += "</td>";	

						string += "<td class=\"ref\" style=\"width:133px;max-width:133px\">";
						string += this.Reference;
						string += "</td>";	

						string += "<td style=\"width:133px;max-width:133px\">";
						string += this.SAP;
						string += "</td>";

						string += "<td style=\"width:88px;max-width:88px\"> <select class=\"qtt\" style=\"width:60px\">";
						string += "<option> 1 </option>"
						string += "<option> 2 </option>"
						string += "<option> 3 </option>"
						string += "<option> 4 </option>"
						string += "<option> 5 </option>"
						string += "<option> 10 </option>"
						string += "</select> </td>";	

						string += "<td style=\"width:98px;max-width:98px\">";
						string += this.Type;
						string += "</td>";
									
						string += "<td style=\"width:78px;max-width:78px\"><input class='ch' type='checkbox'/></td></TR>"
						$("tbody").append(string);	
						appears("AjoutOk"," * Scan de la R&eacute;f&eacute;rence r&eacute;ussi * ","valide","confirmer_ajout");
					});
				}
			},
		error:function(data)
		{
			appears("EchecAjoutTabA"," * Erreur Inconnue , consulter le debogueur * ","Erreur","confirmer_ajout");
		}
	});
};

// Fonction qui sert dans supprimer une reference
// Permet d'eviter la demande de suppression de 2 références en selectionnant
// un nom et une référence differente
// Soit on supp la ref par le nom de la piece soit par la ref
function reset(btn)
{
	if (btn.id == "name")
		$("#ref").val("");
	else
		$("#name").val("");
}

// Fonction qui permet de supprimer une reference
// Si la suppresion est reussie , on affiche un message de validation de suppression
// Sinon on affiche un message d'erreur 
function RetraitReference(nom_piece,ref){
	$.ajax({
			url : 'data.php',
			dataType : "json",
			data : {
					"action":"retraitref",
					"nom_piece": nom_piece,
					"ref":ref,
					},
			type : 'post',
			success: function(data)
			{
				if(data.feedback=="introuvable")							
					appears("error"," * Erreur Reference ou Nom de Piece non valide * ","Erreur","supprimer");		
				
				else
				{
					appears("supp"," * Suppression de la r&eacute;f&eacute;rence R&eacute;ussie * ","valide","supprimer");	
					setTimeout(function() {location.reload()} , 1500);
				}
			},
		error:function(data)
		{
			appears("EchecRetraitRef"," * Erreur Inconnue , consulter le debogueur * ","Erreur","supprimer");
		}

	});
};

//Fonction qui récupère les utilisateurs de la base de donnée et qui remplie les selects de class("nomprenom" && "a_log")
function GetUsers()
{
	$.ajax({
		url:'data.php',
		dataType:'json',
		data:{
			"action":"GetUsers",
		},
		type:'post',
		success: function(data)
		{
			$.each(data.users,function(){
				$(".nomprenom").append("<option>"+this.Name+" "+this.Surname+ "</option>");
				$(".a_log").append("<option>"+this.Login+"</option>");
			});
		},
		error:function(data)
		{
			appears("EchecGetUser"," * Erreur Inconnue lors du chargement des utilisateurs, consulter le debogueur * ","Erreur","");
		}
	});
}

// Fonction qui sert à changer le focus des select lors d'un changement d'index
// en gros tous les select visent la meme piece 
// peut changer le focus d'1 ou de 2 autre select
function forcerfocus(slave,master,slave2=false)
{

	document.getElementById(slave).selectedIndex = document.getElementById(master).selectedIndex;
	if(slave2 != false)
		document.getElementById(slave2).selectedIndex = document.getElementById(master).selectedIndex;

}

// Fonction qui cache les fieldsets des pages users et pieces 
// et la fleche "avant"
function CacherChamps()
{
	for(i=2;i<=11;i++)
	{
		$("#"+i).css({"display":"none"});
	}
	$("#avant").css({"visibility":"hidden"});
}

// Fonction qui permet d'ajouter un utilisateur à la bdd
// Fait apparaitre un message de réussite ou d'échec en fonction du feedback
function AddUser(nom,prenom,log,admin)
{
	$.ajax({
		url:'data.php',
		dataType:'json',
		data:{
			"action":"AddUser",
			"name":nom,
			"surname":prenom,
			"login":log,
			"admin":admin,
		},
		type:'post',
		success: function(data)
		{
			if(data.feedback == "ok")
			{
				appears("AddUser"," * Cr&eacute;ation de l'utilisateur r&eacute;ussie * ","valide","validerU");
				setTimeout(function() {location.reload();}, 1500);
			}
			else
				appears("EchecAddUser"," * Erreur : Login existant * ","Erreur","validerU");

		},
		error:function(data)
		{
			appears("EchecAddUser"," * Erreur Inconnue , consulter le debogueur * ","Erreur","validerU");
		}
	});
}

// Fonction qui permet de changer n'importe quel champ d'un utilisateur
// Fait apparaitre un message d'erreur ou de réussite en fontion du feedback
// Puis reload la page au bout d'1,5sec en cas de réussite
function ChgtUser(log,val,cible)
{
	$.ajax({
		url:'data.php',
		dataType:'json',
		data:{
			"action":"ChgtUser",
			"login":log,
			"val":val,
			"cible":cible,
		},
		type:'post',
		success: function(data)
		{
			if(data.feedback == "ok")
			{
				appears("ChgtAdmin"," * Modification r&eacute;ussie * ","valide","ValiderCA");
				setTimeout(function() {location.reload();}, 1500);
			}
			else if(data.feedback == "Login existant")
				appears("EchecChgtLogin"," * Erreur : Login existant * ","Erreur","ValiderCA");
			else
				appears("EchecChgtAdmin"," * Erreur : Echec de la modification * ","Erreur","ValiderCA");
		},
		error:function(data)
		{
			appears("EchecChgtAdmin"," * Erreur Inconnue , consulter le debogueur * ","Erreur","ValiderCA");
		}
	});
}

// Fonction qui permet de changer n'importe quel champ d'une piece
// Fait apparaitre un message de réussite ou d'échec en fonction du feedback
// Puis reload la page au bout d'1.5sec en cas de réussite
function ChgtPiece(ref,val,cible,table)
{
	$.ajax({
		url:'data.php',
		dataType:'json',
		data:{
			"action":"ChgtPiece",
			"ref" : ref,
			"val" : val,
			"cible" : cible,
			"table":table,
		},
		type:'post',
		success: function(data)
		{
			if(data.feedback == "ok")
			{
				appears("ChgtNom"," * Modification r&eacute;ussie * ","valide","validerN");
				setTimeout(function() {location.reload();}, 1500);
			}
			else
				appears("EchecChgtNom"," * Erreur : Echec de la modification * ","Erreur","validerN");
		},
		error:function(data)
		{
			appears("EchecChgtPiece"," * Erreur Inconnue , consulter le debogueur * ","Erreur","validerN");
		}
	});
}

// Fonction qui permet de récupérer toutes les informations à propo des pièces
// Puis remplit les selects avec ces données 
function GetPieces()
{
	$.ajax({
		url:'data.php',
		dataType:'json',
		data:{
			"action":"GetPieces",
		},
		type:'post',
		success: function(data)
		{
			$.each(data.pieces,function(){
				$(".ref").append("<option>"+this.Reference+"</option>");
				$(".nom").append("<option>"+this.Nom_Piece+"</option>");
				$(".stock").append("<option>"+this.StockMini+"</option>");
				$(".emp_P").append("<option>"+this.Emplacement+"</option>");
				$(".sap").append("<option>"+this.SAP+"</option>");
				$(".type_P").append("<option>"+this.Type+"</option>");
			});

			$.each(data.emp,function(){
				$(".emp").append("<option>"+this.Emplacement+"</option>");
			});

			$.each(data.type,function(){
				$(".type").append("<option>"+this.Type+"</option>");
			});
		},
		error:function(data)
		{
			appears("EchecGetPiece"," * Erreur Inconnue , consulter le debogueur * ","Erreur","");
		}
	});
}

function Confirmer(obj)
{
	if (obj == "Ajout")
	{
		var button = "#confirmer_ajout";
		var tab = "#ajoutpiece";
	}
	else
	{
		var button = "#confirmer_retrait";
		var tab = "#retraitpiece";
	}

	$(""+button+"").prop("disabled","disabled");
			$("#scanning").prop("disabled","disabled");
			$(""+tab+"").hide();
			$(".button").hide();
			$("#scan").html("<img src=\"../images/loading4.gif\">");
			$(".tableau div").css({"background":"transparent",
									"background-color":"#141414"});
			$("#scan").show();
			var quantite = "";
			var reference = "";
			$(""+tab+" tbody tr").each(function(){
				quantite+=$(this).find(".qtt").val()+"£";
				reference+=$(this).find(".ref").html()+"£";
			});
				
			var TabQuantite = quantite.split("£");
			var TabReference = reference.split("£");
			TabQuantite.pop();
			TabReference.pop();
			if(TabQuantite != "" && TabReference != "")
				UpdateBdd(TabQuantite,TabReference,$action);

			else
			{
				appears("TableauVide","* Erreur : Tableau des pièces vide *","Erreur","confirmer_ajout");
				setTimeout(function(){location.reload()},2500);
			}
}

// Fonction qui controle le mot rechercher avant traitement
// Permet d'effectuer des actions si le code scanner correspond
// a une valeur souhaité
// Permet de voyager entre les pages et d'effectuer des traitements directement avec la douchette
function Controleur(recherche)
{
	recherche = recherche.toUpperCase();

	switch(recherche){

		case 'AJOUTER':
			document.location.href="menu.php?view=AjouterPiece";
			return false;
		break;

		case 'RETIRER':
			document.location.href="menu.php?view=RetirerPiece";
			return false;
		break;

		case 'CONFIRMER':
			var quantite = "";
			var reference = "";
			$("#scanning").prop("disabled","disabled");
			if($action == 0) // Cas où on valide l'ajout
				Confirmer("Ajout");

			else if($action == 1)// Cas où on valide le retrait
				Confirmer("Retrait");
		
			return false;
		break;

		case 'EFFACER':
			$("table tr:last-child input").prop("checked",true);
			$("tr:has(input:checked)").remove();
			return false;
		break;

		case 'DECONNEXION':
			setTimeout(function(){deconnexion()},500);
			return false;
		break;

		case 'MAGASIN':
			document.location.href="menu.php?view=Accueil";
			return false;
		break;

		default :
			return true;
		break;
	}

}

// Fonction qui permet de supprimer un utilisateur
// Fait apparaitre un message de réussite ou d'échec en fonction du feedback
function DelUser(login)
{
	$.ajax({
		url:'data.php',
		dataType:'json',
		data:{
			"action":"DelUser",
			"login":login
		},
		type:'post',
		success: function(data)
		{
			if(data.feedback == "ok")
			{
				appears("DelUser"," * Suppression r&eacute;ussie * ","valide","validerSU");
				setTimeout(function() {location.reload();}, 1500);
			}
			else
				appears("EchecDelUser"," * Erreur : Echec de la modification * ","Erreur","validerSU");
		},
		error:function(data)
		{
			appears("EchecDelUser"," * Erreur Inconnue , consulter le debogueur * ","Erreur","validerSU");
		}
	});
}

// Permet de bloquer un utilisateur
function BlockUser(login,action)
{
	$.ajax({
		url:'data.php',
		dataType:'json',
		data:{
			"action":action,
			"login":login,
		},
		type:'post',
		success: function(data)
		{
			if(data.action == "BlockUser")
			{
				if(data.feedback == "ok")
				{
					appears("BlockUser"," * Bloquage r&eacute;ussie * ","valide","ValiderBU");
					setTimeout(function() {location.reload();}, 1500);
				}
				else
					appears("EchecBlockUser"," * Erreur : Echec du Bloquage * ","Erreur","ValiderBU");
			}	
			else
				if(data.feedback == "ok")
				{
					appears("unBlockUser"," * D&eacute;bloquage r&eacute;ussie * ","valide","ValiderDU");
					setTimeout(function() {location.reload();}, 1500);
				}
				else
					appears("EchecunBlockUser"," * Erreur : Echec du débloquage * ","Erreur","ValiderDU");
		},
		error:function(data)
		{
			appears("EchecunBlockUser"," * Erreur Inconnue , consulter le debogueur * ","Erreur","validerBU");
		}
	});
}

// Permet de créer un emplacement grace aux coordonnées après un click sur la map
function CreerEmp(emplacement,x,y)
{
	$.ajax({
		url:'data.php',
		dataType:'json',
		data:{
			"action":"CreerEmp",
			"emplacement":emplacement,
			"x":x,
			"y":y,
		},
		type:'post',
		success: function(data)
		{
			if(data.feedback == "ok")
			{
				appears("CreerEmp"," * Cr&eacute;ation r&eacute;ussie * ","valide","valider");
				setTimeout(function() {location.reload();}, 1500);
			}
			else
				appears("EchecCreerEmp"," * Erreur : Echec de la cr&eacute;ation * ","Erreur","valider");
		},
		error:function(data)
		{
			appears("EchecDelUser"," * Erreur Inconnue , consulter le debogueur * ","Erreur","valider");
		}
	});
}

// Fonction qui permet de supprimer un emplacement de la table emplacement 
function SupEmplacement(emplacement)
{
	$.ajax({
		url:'data.php',
		dataType:'json',
		data:{
			"action":"SupEmplacement",
			"emplacement":emplacement,
		},
		type:'post',
		success: function(data)
		{
			if(data.feedback == "ok")
			{
				appears("SupEmp"," * Suppression r&eacute;ussie * ","valide","validerS");
				setTimeout(function() {location.reload();}, 1500);
			}
			else
				appears("EchecSupEmp"," * Erreur : Echec de la suppression * ","Erreur","validerS");
		},
		error:function(data)
		{
			appears("EchecSupEmp"," * Erreur Inconnue , consulter le debogueur * ","Erreur","validerS");
		}
	});
}

// Fonction qui permet la sauvegarde de la BDD à 2 endroits
// Sur la clé Usb à l'emplacement : E:/BackUp/db-backup.sql
// Sur le disque Dur à l'emplacement : C:/wamp/www/IGA/templates/bdd/db-backup.sql
// Redirige vers la page d'accueil après l'enregistrement
function Sauvegarde()
{
		$.ajax({
		url:'data.php',
		dataType:'json',
		data:{
			"action":"Sauvegarde",
		},
		type:'post',
		success: function(data)
		{
			alert("Sauvegarde Réussie");
			setTimeout(function() {document.location.href="menu.php?view=Sauvegarde";}, 100);
			
		},
		error:function(data)
		{
			alert("Sauvegarde Réussie");
			setTimeout(function() {document.location.href="menu.php?view=Sauvegarde";}, 100);
		}
	});
}

// fonction qui permet de vérifier si la chaine de caractère passée en paramètre
// est composée uniquement de caractères normaux (aA-zZ // 0-9) ou " " ou " - "
function VerificationAscii(variable,type)
{
	$tab = variable.split("");
	if(type == "string")
	{
		for(i=0;i<$tab.length;i++)
		{
			$num = $tab[i].charCodeAt(0)
			if($num < 48 && $num != 32 && $num != 45 && $num != 43 && $num != 47 && $num != 46 && $num != 40 && $num != 41 || 
				$num > 122 || $num > 90 && $num < 97 || 
				$num > 57 && $num < 65)
				return false;
		}
		return true;
	}
	else
	{
		for(i=0;i<$tab.length;i++)
		{
			$num = $tab[i].charCodeAt(0)
			if($num < 48 || $num > 57)
				return false;
		}
		return true;
	}

}

// fonction qui permet de connaitre la limite de ligne pour l'historique
function limit()
{
	$(".corps tbody").empty();
	$limit = $("#limit").val();	
	if($limit == "Tous")
		$limit = 999999;
}

function AddType(type)
{
	$.ajax({
		url:'data.php',
		dataType:'json',
		data:{
			"action":"AddType",
			"type":type
		},
		type:'post',
		success: function(data){
			appears("AddType","* Ajout du Type réussi *","valide","ValiderT");
			setTimeout(function() {location.reload();}, 1500);
		},
		error: function(){
			appears("EchecAddType","* Erreur veuillez consulter le debogueur *","Erreur","ValiderT");
		}
	});
}

function SuppType(type){
	$.ajax({
		url:'data.php',
		dataType:'json',
		data:{
			"action":"SuppType",
			"type":type
		},
		type:'post',
		success: function(data){
			appears("AddType","* Suppression du Type réussi *","valide","ValiderST");
			setTimeout(function() {location.reload();}, 1500);
		},
		error: function(){
			appears("EchecAddType","* Erreur veuillez consulter le debogueur *","Erreur","ValiderST");
		}
	});
}


function BoutonType(){
	$.ajax({
		url:'data.php',
		dataType:'json',
		data:{
			"action":"BoutonType",
		},
		type:'post',
		success: function(data){
			$.each(data.type,function(){
				$("#Liste_Type").append("<input type=\"button\" value=\""+this.Type+"\" onclick=\"Rack_Print(this)\"/>");
			});
		},
		error: function(){
		}
	});
}

function Rack_Print(obj){
	$.ajax({
		url:'data.php',
		dataType:'json',
		data:{
			"action":"Recherchepartype",
			"type":obj.value
		},
		type:'post',
		success: function(data){
			var i = 0;
			var string="";
			$("#CahierRef tbody").empty();
			$.each(data.Type,function(){
				string="<tr><td style=\"max-width:201px;min-width:201px\">"+this.Nom_Piece+"</td>";
				string+="<td id="+i+" style=\"max-width:303px;min-width:303px\"> </td>";
				string+="<td style=\"max-width:133px;min-width:133px\">"+this.SAP+"</td>";
				string+="<td style=\"max-width:105px;min-width:105px\">"+this.Emplacement+"</td></tr>";
				$("#CahierRef").append(string);
				$("#"+i).barcode(this.Reference,"code128",{barWidth:1,barHeight:28,fontSize:12});
				i++;
			})

			// On récupère le contenu de la div d'id a 
	        var contents = $("#HiddenTab").html();
	        // On crée une date
	        var date = new Date();
	        // On la transforme en Date Francaise
	        var datefr = DateLocale(date);
	        // Entete
	        var industrie = "Industrie Grupo Antolin <br> Hénin-Beaumont - 62110 </br>";
	        // On ouvre une nouvelle page de titre Audit
			mywindow=window.open("","Pièces à commander");
			//On insère le code dans la nouvelle page + l'entete
			mywindow.document.write("<html xmlns='http://www.w3.org/1999/xhtml'> <header>  ");
			mywindow.document.write("<link href='../css/style-print.css' rel='stylesheet' type='text/css'/></header> ");
			mywindow.document.write("<body><div id='header'>");
			mywindow.document.write("<font face='title'> <div id='info'>"+industrie);
			mywindow.document.write("Pièces à commander du : \" " +datefr+"\"</div></font>");
			mywindow.document.write("<div id='logo'><img src='../images/miniaturelogo.jpg'/></div></div>");
			// On insère le contenu de la div a dans la nouvelle page
			mywindow.document.write("<h1>"+obj.value+"</h1>"+contents+"</body></html>");
			mywindow.document.close()
			// On attend 0.5sec puis on met le focus sur la nouvelle page 
			// On propose l'impression
			// On ferme la page
			setTimeout(function () {
			    mywindow.focus();
			    mywindow.print();
			    mywindow.close();
			}, 500);
		},
		error: function(){
		}
	});
}