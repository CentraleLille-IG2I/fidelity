var cagnotteInit;

function getClient()
	{
			var code = document.getElementById("numeroCarte").value;
			var data;
			initPage();

			if (code=="") return;

			if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
			  xmlhttp=new XMLHttpRequest();
			}
			else
			{// code for IE6, IE5
			  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			
			xmlhttp.onreadystatechange=function()
				{
				if (xmlhttp.readyState==4 && xmlhttp.status==200) //succes requete ajax
					{
						var kek = xmlhttp.responseText;
						data = eval("("+kek+")");
						if(data["client"]["id"]!=undefined)
                            document.getElementById("idUser").value = data["client"]["id"];
						if(data["client"]["nom"]!=undefined) // si le nom est definie, on remplie les donnees dans l'HTML
                            document.getElementById("nom").innerHTML = data["client"]["nom"]; // remplissage
						if(data["client"]["prenom"]!=undefined)
                            document.getElementById("prenom").innerHTML = data["client"]["prenom"];
						if(data["client"]["ville"]!=undefined)
                            document.getElementById("ville").innerHTML = data["client"]["ville"];
						if(data["client"]["codePostal"]!=undefined)
                            document.getElementById("codePostal").innerHTML = data["client"]["codePostal"];
						if(data["client"]["telephone"]!=undefined)
                            document.getElementById("numeroTel").innerHTML = data["client"]["telephone"];
						if(data["client"]["telephone2"]!=undefined)
                            document.getElementById("numeroTel2").innerHTML = data["client"]["telephone2"];
						if(data["client"]["mail"]!=undefined)
                            document.getElementById("adresseMail").innerHTML = data["client"]["mail"];
						if(data["client"]["cagnotte"]!=undefined)
                        {
                            document.getElementById("cagnotte").innerHTML = data["client"]["cagnotte"];
                            document.getElementById("cagnotteF").value = data["client"]["cagnotte"];
                            cagnotteInit=data["client"]["cagnotte"];
						}
						ajoutReducHtml(data);
					}
				}
				
			xmlhttp.open("GET","Scripts/getData.php?card="+code+"&recup=client",true);
			xmlhttp.send();
	}

function initPage ()
{  // on supprime toutes les donnees HTML
	document.getElementById("idUser").value ="";
	document.getElementById("nom").innerHTML ="";
	document.getElementById("prenom").innerHTML ="";
	document.getElementById("ville").innerHTML ="";
	document.getElementById("codePostal").innerHTML ="";
	document.getElementById("numeroTel").innerHTML ="";
	document.getElementById("numeroTel2").innerHTML ="";
	document.getElementById("adresseMail").innerHTML ="";
	document.getElementById("cagnotte").innerHTML ="";
	document.getElementById("preReduc").placeholder="Montant";
    document.getElementById("postReducSpan").innerHTML="";
	document.getElementById("reducs").innerHTML="";
	document.getElementById("cagnotteF").value="";
}

function ajoutReducHtml (toto)
{
    
    document.getElementById("postReducSpan").style.display="block"; // on affiche un span pour le montant post reduc
    document.getElementById("preReduc").placeholder="Montant pré-réduction"; // on change le text de 'montant' a 'montant pré-reduction'
    document.getElementById("appliqueReduc").style.display="block"; //affiche le div contenant le bouton de validation de la reduction
	if(toto["reducs"]!=undefined && toto["reducs"].length>0) // si il y a au moins une reduc pour le client selected
	{
		for(var i=0;i<toto["reducs"].length;i++)
		{
			if(toto["reducs"][i]!=undefined)
			{
				var label=document.createElement('label'); // on cree un label avec la description de la reduction
				label.innerHTML=toto["reducs"][i]["description"]+" : ";
				var input= document.createElement('input'); // on cree une checkbox pour valider ou pas la reduction
				input.type="checkbox";
				input.name="check[]";
				input.id=toto["reducs"][i]["id"]; // la checkbox prend comme id, l'id de la reduction
				input.value=toto["reducs"][i]["id"]; 
				$(input).on("click",function(){RecupReduc(this.id);}); // fonction appeler on click de l'input
				document.getElementById("reducs").appendChild(label); // on ajoute le label dans le div reducs
				document.getElementById("reducs").appendChild(input); // on ajoute l'input dans le div reducs					document.getElementById("reducs").appendChild(document.createElement('br')); // on fait un retour a la ligne pour la prochaine eventuelle reduction
			}
		}
	}
}

function RecupReduc(id)
{
	if (parseFloat(document.getElementById('preReduc').value)){ // si il n'y a pas de de nombre dans le montant
		$.ajax({
			type: 'GET', // Le type de ma requete
			url: "Scripts/getData.php", // L'url vers laquelle la requete sera envoyee
			data:
			{
				recup: 'reduc', // Les donnees que l'on souhaite envoyer au serveur au format JSON
				id: id
			},
			success: function(data, textStatus, jqXHR)
			{
	    		// La reponse du serveur est contenu dans data
	    		data = eval("("+data+")");
				appliquerReduc(data,id);
			}	
		});
	}
	else
	{
		document.getElementById(id).checked=false; // decoche automatiquement l'input
		alert("Veuillez d'abord entrer le montant");
	}
}

function appliquerReduc(data, id)
{
	var montantInit=document.getElementById('postReduc').value;
	var montantTT=document.getElementById('preReduc').value;
	var montantPost=montantInit;
	var point=parseFloat(document.getElementById("cagnotte").innerHTML);
	var cout=parseFloat(data["cout"]);
	if(document.getElementById(id).checked)
	{ // on applique la reduction
		if(point>cout)
		{// le client a assez de point pour utiliser la reduction
			if(data["type"]=="brut")
			{
				montantPost=parseFloat(montantInit)-parseFloat(data["valeur"]); //retire la valeur brut de la reduction
			}
			else
			{
				montantPost=(parseFloat(montantInit)-parseFloat(montantTT)*parseFloat(data["valeur"])/100); // on retire la valeur en pourcentage de la reduction du montant de depart 
			}
			point-=cout;
		}
		else
		{
			document.getElementById(id).checked=false;
			alert("Le client n'a pas assez de point pour consommer cette reduction");
		}
	}
	else
	{ // on desapplique la reduction
		if(data["type"]=="brut")
		{
			montantPost=parseFloat(montantInit)+parseFloat(data["valeur"]); //ajoute la valeur brut de la reduction
		}
		else
		{
			montantPost=parseFloat(montantInit)+parseFloat(montantTT)*parseFloat(data["valeur"])/100; // on ajoute la valeur en pourcentage du montant de depart de la reduction
		}
		point+=cout;
	}
	montantPost=Math.round(montantPost*100)/100;
	point=Math.round(point*100)/100;
	document.getElementById('postReduc').value=montantPost.toString();
	document.getElementById("postReducSpan").innerHTML=montantPost.toString();
	document.getElementById("cagnotte").innerHTML=point.toString();
	document.getElementById("cagnotteF").value=point.toString();		
}

function transfertMontant()
{
	if(event.keyCode != 13)
	{
		document.getElementById('postReduc').value=document.getElementById('preReduc').value; // on recopie la valeur de pre reduc dans post reduc
		document.getElementById("postReducSpan").innerHTML=document.getElementById('preReduc').value; // on recopie la valeur de pre reduc dans post reduc
			var check = $("#reducs").find(':checkbox'); // recupere tous les checkbox du div reducs
			check.attr('checked', false); //decoche tous les checkbox
			document.getElementById("cagnotte").innerHTML = cagnotteInit;
			document.getElementById("cagnotteF").value = cagnotteInit;
		}
	}

function verifEntree (argument)
{
	if(event.keyCode == 13)
	{
		getClient();
	}
}

function checkSubmit()
{
	oBut = document.getElementById('appliqueReduc');
	oVal = document.getElementById('preReduc');
	var pattern = new RegExp("^[0-9]+\.[0-9]{0,2}$");
	console.log(oVal.value);
	if(pattern.test(oVal.value))
	{
		oBut.disabled = false;
	}
	else
	{
		oBut.disabled = true;
	}
}