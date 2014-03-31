<?php
	/*
	 * options.php
	 * Fidelity
	 * 
	 * Created by Richard Degenne on 31/3/2014. CC by-nc-sa.
	 * 
	 * Page de gestion des options du système
	 */
	 
echo "<h1>Options</h1>\n";
	
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
			
			case "supprimer":
				supprimer();
				break;
			
			case "sauvegarde":
				sauvegarde();
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
	echo "<script type='text/javascript' src='Scripts/jquery.js'></script>\n";
	
/*
 * Fonctions
 */


/*---------------------------*
 * Fonction :	liste
 * Paramètres :	Aucun
 * Retour :		Aucun
 * Description :	Génère l'affichage de la liste des options.
/*---------------------------*/
function liste()
{
?>
	<ul>
		<li><a href="?page=options&mode=sauvegarder">Sauvegarder les données</a></li><br />
		<li><a href="?page=options&mode=supprimer">Effacer toutes les données</a></li>
	</ul>
<?php
}


/*---------------------------*
 * Fonction :	supprimer
 * Paramètres :	Aucun
 * Retour :		Aucun
 * Description :	Demande la confirmation pour tout supprimer
/*---------------------------*/
function supprimer()
{
?>
	<h2>Effacer toutes les données</h2>
	<a class='but' href=\"?page=options\">Annuler</a>
	<p><strong>Attention !</strong> Cliquer sur le bouton ci-dessous entraînera la suppression de toutes les données du système, y compris les clients, leur cagnotte et leur historique.</p>
	<p>Êtes-vous sûr de vouloir procéder à la suppression ?</p>
	<form method="post" action="?page=options">
		<input type="hidden" name="submit" value="supprimer" />
		<input type="submit" value="Supprimer" />
	</form>
<?php
}


/*---------------------------*
 * Fonction :	sauvegarder
 * Paramètres :	Aucun
 * Retour :		Aucun
 * Description :	Génère un fichier de sauvegarde des données de la base.
/*---------------------------*/
function sauvegarder()
{
?>
	<h2>Sauvegarder les données</h2>
	<a class="but" href="?page=options">Annuler</a>
<?php
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
		 * Supprimer
		 */
		case 'supprimer':
			if(deleteAll())
				echo "<p class='notification positif'>Suppression effectutée</p>";
			else
				echo "<p class='notification negatif'>Échec lors de la suppression</p>";
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