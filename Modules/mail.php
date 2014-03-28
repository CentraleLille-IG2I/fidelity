<?php
	/*
	 * mail.php
	 * Fidelity
	 *
	 * Cette page va permettre l'envoi de mails groupé ainsi que l'édition de fichiers pour le service d'envoi groupé de SMS.
	 *
	 */
?>


<?php

echo "<h1 >Mail & SMS</h1>";

	if(isset($_POST["SubButton"]))
	{
		switch($_POST["SubButton"])
		{
			case "Envoyer le Mail":
				if(isset($_POST["conf"]))
				{
					switch($_POST["conf"])
					{
						case "Confirmer":
							SendMail();
							break;
						case "Retour":
							saisi($_POST["Message"]);
							smsBerk();
							break;
						default:
							echo "Error.";
							break;
					}
				}
				else
					MAIL_conf();
				break;
			case "Obtenir la liste des numéros":
				
				break;
			default:
				echo "Seuls les usages de Sms et Mail sont possibles.<br /> ";
				break;
		}
	}
	else
	{
		saisi("");
		smsBerk();
	}

	
function smsBerk(){
	echo "<div id='Smsmenu'>";
	echo "<form action=\"./SMS_dl.php\" method=\"post\">";
	echo "<span> Liste des numeros de telephone</span><br/><input id='butsms' type=\"submit\" name=\"SubButton\" value=\"Obtenir la liste des numéros\" />";
	echo "</form>";
	echo "</div>";
}


function MAIL_conf(){
	global $outNames;
	
	$expr="/(\\[(";
	$test = false;   
	foreach($outNames as $valu){
		if ($test)
			$expr.='|';
		else
			$test=true;
		$expr.=$valu;
	}
	$expr.=")\\])/";
	echo "<form id='exemple' action=\"\" method=\"post\">";
	echo "<div class='categorie'>";
	echo "<h3>Objet : </h3>";
	echo $_POST["objet"];
	echo "</div> <br/>";
	echo "<div class='categorie'>";
	echo "<h3>Message : </h3>	<p class='categorie'>";
	echo preg_replace($expr, "<span style='color:red;'>$1</span>", $_POST["Message"]);
	
	echo "	</p> </div> <br/> <div class='categorie'> <h3>Exemple : </h3>
		<p class='categorie'>";
		
	$shit=getAllClients();
	echo str_Replace("\n", "<br />", RenderMail($_POST["Message"], $shit[0]));
	
	echo "		</p> </div> <br/>
		<input type=\"submit\" name=\"conf\" value=\"Confirmer\" />
		<input type=\"submit\" name=\"conf\" value=\"Retour\" />
		<input type=\"hidden\" name=\"SubButton\" value=\"Mail\" />
		<input type=\"hidden\" name=\"Message\" value=\"".$_POST["Message"]."\" />
		<input type=\"hidden\" name=\"objet\" value=\"".$_POST["objet"]."\" /> 
	</form>";
}

function RenderMail($mess, $varClient)
{
	global $outNames;
	$patterns = array();
	$repla = array();
	foreach ($varClient as $key => $val)
	{
		$patterns[]='/\\['.$key.'\\]/';
		$repla[]=$val;
	}
	return preg_replace($patterns, $repla, $mess);
}

function SendMail()
{
	$nbr = 0;
	$fnbr = 0;
	$list=getAllClients();
	foreach($list as $client)
	{
		if($client["aboMail"]==1)
		{
			$to      = $client["mail"];
			$subject = $_POST["objet"];
			$message = RenderMail($_POST["Message"], $client);
			$headers = 'From: sauron@mordor.tm' . "\r\n" .
			'Reply-To: webmaster@example.com' . "\r\n" .
			'X-Mailer: PHP/' . phpversion();
			if (mail($to, $subject, $message, $headers))
			{
				$nbr++;
			}
			else
			{
				$fnbr++;
			}
		}
	}
	if ($nbr>0)
	{
		echo "<div class=\"notification positive\">Mails envoyés</div>";
		echo "$nbr mail(s) envoyés avec succes.<BR />$fnbr mail(s) n'ont pas pu être envoyés";
	}
	else
	{
		echo "<div class=\"notification negative\">Erreur</div>";
		echo "Aucun mails n'a pu être envoyé ! Verifiez votre connexion internet et ";
	}
}
function saisi($default){

	global $outNames;


	echo "	<script>
		function scanText()
		{
			var ch = document.getElementById(\"ChampMessage\");
			var reg = new RegExp(\"(\\\\[(";  
	$test = false;  
	foreach($outNames as $valu){
		if ($test)
			echo '|';
		else
			$test=true;
		echo $valu;
	}
	echo ")\\\\])\", \"g\");
			//alert(ch.value);
			var out = document.getElementById(\"outputMess\");
			var t = ch.value.replace(reg, \"<span style='color:red;'>$1</span>\");
			out.innerHTML = t.replace(/(\\n|\\n\\r|\\r\\n)/g, \"<BR />\")
		}
		
		

		function insertAtCursor( myValue) {
			var myField = document.getElementById(\"ChampMessage\");
			//IE support
			if (document.selection) {
				myField.focus();
				sel = document.selection.createRange();
				sel.text = myValue;
			}
			//MOZILLA and others
			else if (myField.selectionStart || myField.selectionStart == '0') {
				var startPos = myField.selectionStart;
				var endPos = myField.selectionEnd;
				myField.value = myField.value.substring(0, startPos)
					+ myValue
					+ myField.value.substring(endPos, myField.value.length);
			} else {
				myField.value += myValue;
			}
			scanText();
			myField.focus();
			myField.setSelectionRange(startPos+myValue.length, startPos+myValue.length);
		}
	</script>";

	echo "
	<form action=\"\" method=\"post\">
		<div class=\"MenuMailer element\">
	";
		echo"<div class='Liste' >";
	foreach($outNames as $valu){
		if ($valu!="interets")
		echo "<input  class='boption' type=\"button\" onclick=\"insertAtCursor('[$valu]');\" value=\"[$valu]\" /><br />";
		else
		echo "<input  class='boption' type=\"button\" onclick=\"insertAtCursor('[$valu]');\" value=\"[$valu]\" />";	
	}
	echo"</div>";
	echo "	</div>
		
		<div class=\"MenuMailer\">
			<p id='zoneobjet'>
			<label for=\"objet\" >Objet :</label>
			<input type=\"text\" name=\"objet\" id=\"objet\" /><br />
			</p>
			<textarea onkeydown=\"scanText();\" onkeyup=\"scanText();\" id=\"ChampMessage\" rows = \"20\" cols = \"100\" name=\"Message\">".$default."</textarea>
			
		
		<br />
		<div class=\"BouttonsMailer\">
			<input type=\"submit\" id='butenvoimail' name=\"SubButton\" value=\"Envoyer le Mail\" />
		</div>
		</div>
	</form>";
}