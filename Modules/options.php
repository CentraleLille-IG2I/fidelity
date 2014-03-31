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
                
			case "charger":
				charger();
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
		<li><a href="?page=options&mode=sauvegarde">Sauvegarder les données</a></li><br />
<li><a href="?page=options&mode=charger">Charger des données</a></li><br />
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
 * Fonction :	sauvegarde
 * Paramètres :	Aucun
 * Retour :		Aucun
 * Description :	Génère un fichier de sauvegarde des données de la base.
 /*---------------------------*/
function sauvegarde()
{
?>
    <h2>Sauvegarder les données</h2>
    <a class="but" href="?page=options">Annuler</a>
    <p>Une sauvegarde sera créée sur le Bureau en tant que "fidelity.sql". Voulez-vous continuer ?</p>
    <form method="post" action="?page=options">
    <input type="hidden" name="submit" value="sauvegarde" />
    <input type="submit" value="Valider" />
    </form>
<?php
}


/*---------------------------*
 * Fonction :	charger
 * Paramètres :	Aucun
 * Retour :		Aucun
 * Description :	Génère un fichier de sauvegarde des données de la base.
/*---------------------------*/
function charger()
{
?>
    <h2>Charger des données</h2>
    <a class="but" href="?page=options">Annuler</a>
    <p>Charger des données ?</p>
    <form method="post" action="?page=options">
    <input type="hidden" name="submit" value="charger" />
    <input type="submit" value="Valider" />
    </form>
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
         * Sauvegarde
         */
		case 'sauvegarde':
            if(backup())
                echo "<p class='notification positif'>Sauvagarde effectutée</p>";
            else
                echo "<p class='notification negatif'>Échec de la sauvegarde</p>";
            break;
            
        /*
         * Charger
         */
		case 'charger':
            if(import())
                echo "<p class='notification positif'>Importation effectutée</p>";
            else
                echo "<p class='notification negatif'>Échec de l'importation</p>";
            break;
	}
}
?>