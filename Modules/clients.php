<?php
	/*
	 * clients.php
	 * Fidelity
	 * 
	 * Created by Richard Degenne on 5/1/2014. CC by-nc-sa.
	 * 
	 * Page répertoire pour l'affichage, la création, la modification de la liste de clients.
	 */

$fields = array(
	"id","numeroCarte","civilite","nom","prenom","adresse","ville","codePostal","telephone","telephone2","mail","aboMail","aboSms","cagnotte","dateDeNaissance","interets"
	);

echo "<h1>Clients</h1>\n";
	
	// Si le flag "submit" est levé, alors il y a un accès bdd à faire. On se rend dans la fonction enregistrer.
	if(isset($_POST["submit"]))
	{
		enregistrer();
	}
	
	// Le switch permet d'accéder simplement aux différentes fonctionnalités.
	if(isset($_GET["mode"]))
	{
		switch($_GET["mode"])
		{
			case "liste":
				liste();
				break;
			
			case "fiche":
				fiche();
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
			
			default:
				liste();
				break;
		}
	}
	else
	{
		liste();
	}
	
	// Chargement du JavaScript
	echo "<script type='text/javascript' src='Scripts/jquery.js'></script>\n<script type='text/javascript' src='$filename[JSclients]'></script>\n";
	
/*
 * Fonctions
 */
 
/*---------------------------*
 * Fonction :	liste
 * Paramètres :	Aucun
 * Retour :		Aucun
 * Description :	Génère l'affichage de la liste des clients enregistrés.
/*---------------------------*/
function liste()
{
	$aboMail="non";
	$aboSms="non";
	echo "<h2>Liste des clients</h2>\n";
	echo "<div id='topPanel' ><a class=\"but\" href=\"index.php?page=clients&mode=ajouter\">Ajouter un client</a>\n";
	$clients = getAllClients();
	echo "<input type=\"text\" id=\"recherche\" placeholder=\"Rechercher\" /></div>";
	echo "<table id=\"liste\">\n<tr><th>N° de carte</th><th>Nom</th><th>Prénom</th><th>Cagnotte</th><th>Ville</th><th>Code Postal</th><th>Téléphone</th><th>Téléphone 2</th><th>Mail</th><th>Abonné mail</th><th>Abonné SMS</th></tr>\n";
	foreach($clients as $client)
	{
		if($client['aboMail']) // Gestion de l'affichage
			$aboMail="Oui";
		else
			$aboMail="Non";
			
		if($client['aboSms'])
			$aboSms="Oui";
		else
			$aboSms="Non";
		
		if($client['telephone'] == null)
			$telephone="—";
		else
			$telephone = $client['telephone'];
		
		if($client['telephone2'] == null)
			$telephone2="—";
		else
			$telephone2=$client['telephone2'];
			
		if($client['mail'] == null)
			$mail="—";
		else
			$mail=$client['mail'];
		
		echo "<tr class='ligne' id=\"$client[id]\" onclick=\"redirection($client[id])\"><td><a href=\"index.php?page=clients&mode=fiche&id=$client[id]\">$client[numeroCarte]</a></td><td>$client[nom]</td><td>$client[prenom]</td><td>$client[cagnotte]</td><td>$client[ville]</td><td>$client[codePostal]</td><td>$telephone</td><td>$telephone2</td><td>$mail</td><td>$aboMail</td><td>$aboSms</td></tr>\n";
	}
	echo "</table>";
}

/*---------------------------*
 * Fonction :	fiche
 * Paramètres :	id (GET) - Entier
 * Retour :		Aucun
 * Description :	Génère l'affichage de la fiche d'un client ou d'un message d'erreur, le cas échéant.
/*---------------------------*/
function fiche()
{
	echo "<h2>Fiche client</h2>\n";
	echo "<a class='but' href=\"index.php?page=clients&mode=liste\">Retour à la liste</a>\n";
	if(isset($_GET['id']) && !empty($_GET['id']))
	{
		$client = getClientById($_GET['id']) or die('Une erreur est survenue.');
		if($client['aboMail']==1) $aboMail="oui";     //plus claire pour le client
		else $aboMail="non";
		if($client['aboSms']==1) $aboSms="oui";
		else $aboSms="non";
	echo "<a class='but' href=\"index.php?page=clients&mode=modifier&id=$client[id]\">Modifier le client</a>\n";
	echo "<a  class='but' href=\"index.php?page=clients&mode=supprimer&id=$client[id]\">Supprimer le client</a>\n";
		echo "<table >\n
			<tr><th>Numéro de carte</th><td>$client[numeroCarte]</td></tr>\n
			<tr><th>Civilité</th><td>$client[civilite]</td></tr>\n
			<tr><th>Nom</th><td>$client[nom]</td></tr>\n
			<tr><th>Prénom</th><td>$client[prenom]</td></tr>\n
			<tr><th>Date de naissance</th><td>$client[dateDeNaissance]</td></tr>\n
			<tr><th>Cagnotte</th><td>$client[cagnotte]</td></tr>\n
			<tr><th>Adresse</th><td>$client[adresse], $client[codePostal] $client[ville]</td></tr>\n
			<tr><th>Téléphone</th><td>$client[telephone]</td></tr>\n
			<tr><th>Téléphone 2</th><td>$client[telephone2]</td></tr>\n
			<tr><th>Mail</th><td>$client[mail]</td></tr>\n
			<tr><th>Abonné mail</th><td>$aboMail</td></tr>\n
			<tr><th>Abonné SMS</th><td>$aboSms</td></tr>\n
			<tr><th>Centres d'intérêt</th><td>$client[interets]</td></tr>\n
			";
		echo "</table>";
	}
	else
	{
		echo "Une erreur est survenue.";
	}
}

/*---------------------------*
 * Fonction :	ajouter
 * Paramètres :	Aucun
 * Retour :		Aucun
 * Description :	Génère l'affichage du formulaire d'ajout, et appelle enregistrer pour réaliser l'insertion.
/*---------------------------*/
function ajouter()
{
	echo "<h2>Ajout d'un client</h2>\n";
	echo "<a class='but' href=\"index.php?page=clients&mode=liste\">Annuler</a>\n";
	?>
	<form method="post" action="index.php?page=clients">
	<input type="hidden" name="submit" value="ajouter" />
	<table >
		<tr><th>Numéro de carte *</th><td><input type="text" name="numeroCarte"  autocomplete="off"/></td></tr>
		<tr><th>Civilité *</th><td><input type="radio" id="mr" name="civilite" value="Mr"  autocomplete="off"/><label for="mr">Mr.</label><br />
		<input type="radio" name="civilite" id="mme" value="Mme"  autocomplete="off"/><label for="mme">Mme.</label><br />
		<input type="radio" name="civilite" id="mlle" value="Mlle" autocomplete="off" /><label for="mlle">Mlle.</label></td></tr>
		<tr><th>Nom *</th><td><input type="text" name="nom"  autocomplete="off"/></td></tr>
		<tr><th>Prénom *</th><td><input type="text" name="prenom"  autocomplete="off"/></td></tr>
		<tr><th>Adresse *</th><td><input type="text" name="adresse"  autocomplete="off"/></td></tr>
		<tr><th>Ville *</th><td><input type="text" name="ville"  autocomplete="off"/></td></tr>
		<tr><th>Code postal</th><td><input type="text" name="codePostal"  autocomplete="off"/></td></tr>
		<tr><th>Téléphone</th><td><input type="text" name="telephone"  autocomplete="off"/></td></tr>
		<tr><th>Téléphone 2</th><td><input type="text" name="telephone2"  autocomplete="off"/></td></tr>
		<tr><th>Mail</th><td><input type="text" name="mail"  autocomplete="off"/></td></tr>
		<tr><th>Abonnement mail *</th><td><input type="radio" id="mail0" name="aboMail" value="0" checked/><label for="mail0">Non</label><br />
		<input type="radio" name="aboMail" id="mail1" value="1" /><label for="mail1">Oui</label></td></tr>
		<tr><th>Abonnement SMS *</th><td><input type="radio" id="sms0" name="aboSms" value="0" checked/><label for="sms0">Non</label><br />
		<input type="radio" name="aboSms" id="sms1" value="1" /><label for="sms1">Oui</label></td></tr>
		<tr><th>Date de naissance</th><td><input type="date" name="dateDeNaissance"  autocomplete="off"/></td></tr>
		<tr><th>Intérêts</th><td><input type="text" name="interets"  autocomplete="off"/></td></tr>
	</table>
	<p>Les champs marqués d'un astérisque (*) sont obligatoires.</p>
	<input type="submit" value="Valider" />
	</form>
<?php
}

/*---------------------------*
 * Fonction :	modifier
 * Paramètres :	id(GET)
 * Retour :		Aucun
 * Description :	Génère l'affichage du formulaire de mise à jour, et appelle enregistrer pour réaliser l'update.
/*---------------------------*/
function modifier()
{
	echo "<h2>Modification d'un client</h2>\n";
	echo "<a class='but' href=\"index.php?page=clients&mode=liste\">Annuler</a>\n";
	
	if(isset($_GET['id']) && !empty($_GET['id']))
	{
		$client = getClientById($_GET['id']) or die('Une erreur est survenue.');
		echo "<form method='post' action='index.php?page=clients'>\n
		<input type='hidden' name='submit' value='modifier' />\n
		<input type='hidden' name='id' value='$client[id]' />\n
		<table >\n
			<tr><th>Numéro de carte</th><td><input type='text' name='numeroCarte' value='$client[numeroCarte]' /></td></tr>\n
			<tr><th>Civilité</th><td><input type='radio' name='civilite' id='mr' value='Mr' ";
		if($client['civilite'] == "Mr")
			echo "checked";
		echo "/><label for='mr'>Mr.</label><br />\n
			<input type='radio' name='civilite' id='mme' value='Mme' ";
		if($client['civilite'] == "Mme")
			echo "checked";
		echo "/><label for='mme'>Mme.</label><br />\n
			<input type='radio' name='civilite' id='mlle' value='Mlle' ";
		if($client['civilite'] == "Mlle")
			echo "checked";
		echo "/><label for='mlle'>Mlle.</label></td></th>\n
			<tr><th>Nom</th><td><input type='text' name='nom' value='$client[nom]' autocomplete='off' /></td></tr>\n
			<tr><th>Prénom</th><td><input type='text' name='prenom' value='$client[prenom]' autocomplete='off' /></td></tr>\n
			<tr><th>Adresse</th><td><input type='text' name='adresse' value='$client[adresse]' autocomplete='off' /></td></tr>\n
			<tr><th>Ville</th><td><input type='text' name='ville' value='$client[ville]' autocomplete='off' /></td></tr>\n
			<tr><th>Code postal</th><td><input type='text' name='codePostal' value='$client[codePostal]' autocomplete='off' /></td></tr>\n
			<tr><th>Téléphone</th><td><input type='text' name='telephone' value='$client[telephone]' autocomplete='off' /></td></tr>\n
			<tr><th>Téléphone 2</th><td><input type='text' name='telephone2' value='$client[telephone2]' autocomplete='off' /></td></tr>\n
			<tr><th>Mail</th><td><input type='text' name='mail' value='$client[mail]'/></td></tr>\n
			<tr><th>Abonnement mail</th><td><input type='radio' id='mail0' name='aboMail' value='0' ";
			if(!$client['aboMail']) // Coche la case si non-abonné aux mails
				echo "checked";
			echo "/><label for='mail0'>Non</label><br />\n
			<input type='radio' name='aboMail' id='mail1' value='1' ";
			if($client['aboMail'])
				echo "checked";
			echo"/><label for='mail1'>Oui</label></td></tr>\n
			<tr><th>Abonnement SMS</th><td><input type='radio' id='sms0' name='aboSms' value='0' ";
			if(!$client['aboSms'])
				echo "checked";
			echo "/><label for='sms0'>Non</label><br />\n
			<input type='radio' name='aboSms' id='sms1' value='1' ";
			if($client['aboSms'])
				echo "checked";
			echo "/><label for='sms1'>Oui</label></td></tr>\n
			<tr><th>Date de naissance</th><td><input type='date' name='dateDeNaissance' value='$client[dateDeNaissance]' autocomplete='off'/></td></tr>\n
			<tr><th>Intérêts</th><td><input type='text' name='interets' value='$client[interets]' autocomplete='off'/></td></tr>\n
		</table>\n
		<input type='submit' value='Valider' />\n
		</form>\n";
	}
	else
	{
		echo "Une erreur est survenue.";
	}
}

/*---------------------------*
 * Fonction :	supprimer
 * Paramètres :	Aucun
 * Retour :		Aucun
 * Description :	Génère l'affichage du formulaire de confirmation de la suppression, et appelle enregistrer pour réaliser la suppression.
/*---------------------------*/
function supprimer()
{
	echo "<h2>Suppression d'un client</h2>\n";
	echo "<a class='but' href=\"index.php?page=clients&mode=liste\">Annuler</a>\n";
	
	if(isset($_GET['id']) && !empty($_GET['id']))
	{
		$client = getClientById($_GET['id']) or die('Une erreur est survenue.');
		echo "<form method='post' action='index.php?page=clients'>\n
		<input type='hidden' name='submit' value='supprimer' />\n
		<input type='hidden' name='id' value='$client[id]' />\n
		<p>Êtes-vous sûr de vouloir supprimer $client[civilite]. $client[prenom] $client[nom] ?</p>\n
		<input type='submit' value='Valider' />\n
		</form>\n";
	}
	else
	{
		echo "Une erreur est survenue.";
	}
}

/*---------------------------*
 * Fonction :	enregistrer
 * Paramètres :	submit(POST)
 * Retour :		Aucun
 * Description :	Réalise les opérations de gestion de base données à l'appel du module.
/*---------------------------*/
function enregistrer()
{
	global $fields;
	switch($_POST['submit'])
	{
		/*
		 * Ajouter
		 */
		case 'ajouter':
			foreach($_POST as $key => $value)
			{
				if(in_array($key,$fields))
				{
					$toInsert[$key] = $value;
				}
			}
			if(newClient($toInsert))
				echo "<p class='notification positif'>Client ajouté !</p>";
			else
				echo "<p class='notification negatif'>Échec de l'ajout</p>";
			break;
		
		/*
		 * Modifier
		 */
		case 'modifier':
			foreach($_POST as $key => $value)
			{
				if(in_array($key,$fields))
				{
					$toInsert[$key] = $value;
				}
			}
			if(updateClient($_POST['id'],$toInsert))
				echo "<p class='notification positif'>Client modifié !</p>";
			else
				echo "<p class='notification negatif'>Échec de la modification.</p>";
			break;
		
		/*
		 * Supprimer
		 */
		case 'supprimer':
			if(deleteClient($_POST['id']))
				echo "<p class='notification positif'>Client supprimé !</p>";
			else
				echo "<p class='notification negatif'>Échec de la suppression.</p>";
			break;
	}
}
?>
