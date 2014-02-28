<?php
	/*
	 * mail.php
	 * Fidelity
	 * 
	 * Created by Robin COurgeon on one day during the year 2014. CC by-nc-sa.
	 * 
	 * Cette page va permettre l'envoi de mails groupé ainsi que l'édition de fichiers pour le service d'envoi groupé de SMS.
	 */
?>

<h1>Mail & SMS</h1>

<?php
	if(isset($_POST["SubButton"]))
	{
		switch($_POST["SubButton"])
		{
			case "Mail":
				if(isset($_POST["conf"]))
				{
					switch($_POST["conf"])
					{
						case "Confirmer":
							//SPAMMMM
							SendMail();
							break;
						case "Retour":
							saisi($_POST["Message"]);
							break;
						default:
							echo "Look at my horse, my horse is amazing...";
							break;
					}
				}
				else
					MAIL_conf();
				break;
			case "SMS":
				break;
			default:
				echo "Non, tu utilise 'Mail' ou 'SMS', rien d'autre.<br /> Et t'a perdu en plus!!";
				break;
		}
	}
	else
	{
		saisi("");
	}

function MAIL_conf(){
	global $outNames;
	
	$expr="/(\\[(";
	$test = false;   //c'est degueu
	foreach($outNames as $valu){
		if ($test)
			$expr.='|';
		else
			$test=true;
		$expr.=$valu;
	}
	$expr.=")\\])/";
	
	echo "<form action=\"\" method=\"post\">
	<h2>Objet</h2>";
	echo $_POST["objet"];
	
	echo "<h2>Message</h2>	<p>";
	echo preg_replace($expr, "<span style='color:red;'>$1</span>", $_POST["Message"]);
	//echo $expr;
	echo "	</p><h2>Exemple</h2>
		<p>";
	//tab=getAllClients()[0]   //minimum 1
	echo str_Replace("\n", "<br />", RenderMail($_POST["Message"], getAllClients()[0]));
	echo "		</p>
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
	//muhahahahaha
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
			mail($to, $subject, $message, $headers);
		}
	}
	//puis on affiche un ok
	echo "ok";
}
	
function saisi($default){

	global $outNames;

	//temporaire
	echo "
	<style>
		textarea
		{ 
			resize:none;
		}
		.MenuMailer{
			float: left;
		}
		.BouttonsMailer{
			float: left;
		}
		.MailerOuput{
			width:800px;
			display:block;
		}
	</style>
	";

	echo "	<script>
		function scanText()
		{
			var ch = document.getElementById(\"ChampMessage\");
			var reg = new RegExp(\"(\\\\[(";   //kek le quadruple antislash

	$test = false;   //c'est degueu
	foreach($outNames as $valu){
		if ($test)
			echo '|';
		else
			$test=true;
		echo $valu;
	}
	echo ")\\\\])\", \"g\");
			//alert(ch.value);
			document.getElementById(\"outputMess\").innerHTML = ch.value.replace(reg, \"<span style='color:red;'>$1</span>\");
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
		}
	</script>";

	echo "
	<form action=\"\" method=\"post\">
		<div class=\"MenuMailer\">
	";

	foreach($outNames as $valu){
		echo "<input type=\"button\" onclick=\"insertAtCursor('[$valu]');\" value=\"[$valu]\" /><br />";
	}

	echo "	</div>
		
		<div class=\"MenuMailer\">
			<label for=\"objet\" >Objet :</label>
			<input type=\"text\" name=\"objet\" id=\"objet\" /><br />
			<textarea onkeydown=\"scanText();\" onkeyup=\"scanText();\" id=\"ChampMessage\" rows = \"20\" cols = \"100\" name=\"Message\">".$default."</textarea>
			<p id=\"outputMess\" class=\"MailerOuput\"></p>
		</div>
		<br />
		<div class=\"BouttonsMailer\">
			<input type=\"submit\" name=\"SubButton\" value=\"Mail\" />
			<input type=\"submit\" name=\"SubButton\" value=\"SMS\" />
		</div>
	</form>";
}