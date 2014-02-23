<?php
	/*
	 * clients.php
	 * Fidelity
	 * 
	 * Created by Richard Degenne on 5/1/2014. CC by-nc-sa.
	 * 
	 * Page répertoire pour l'affichage, la création, la modification de la liste de clients.
	 */

echo "<h1>Clients</h1>\n";

	if(isset($_GET["mode"]))
	{
		switch($_GET["mode"])
		{
			case "liste":
				liste();
				break;
			
			case "ajouter":
				ajouter();
				break;
			
			case "modifier":
				modifier();
				break;
			
			case "supprimer":
				supprimer();
				break;
			
			case "clients":
				include_once($filename["clients"]);
				break;
			
			case "mail":
				include_once($filename["mail"]);
				break;
			
			case "reductions":
				include_once($filename["reductions"]);
				break;
			
			default:
				liste();
				break;
		}
	}
	else
	{
		liste();
	}
	
function liste()
{
	echo "<h2>Liste des clients</h2>\n";
	$clients = getAllClients();
	
	echo "<table border=\"1\">\n<tr><th>N° de carte</th><th>Nom</th><th>Prénom</th><th>Cagnotte</th><th>Ville</th><th>Code Postal</th><th>Téléphone</th><th>Mail</th><th>Abonné mail</th><th>Abonné SMS</th></tr>\n";
	foreach($clients as $client)
	{
		echo "<tr><td>$client[numeroCarte]</td><td>$client[nom]</td><td>$client[prenom]</td><td>$client[cagnotte]</td><td>$client[ville]</td><td>$client[codePostal]</td><td>$client[telephone]</td><td>$client[mail]</td><td>$client[aboMail]</td><td>$client[aboSms]</td></tr>\n";
	}
	echo "</table>";
}

function ajouter()
{
	echo "<h2>Ajout d'un client</h2>\n";
}

function modifier()
{
	echo "<h2>Modification d'un client</h2>\n";
}

function supprimer()
{
	echo "<h2>Suppression d'un client</h2>\n";
}
?>