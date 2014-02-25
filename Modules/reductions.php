<?php
	/*
	 * reductions.php
	 * Fidelity
	 * 
	 * Created by Richard Degenne on 5/1/2014. CC by-nc-sa.
	 * 
	 * Page répertoire pour l'affichage, la création, la modification de la liste de réductions disponibles.
	 */

$fields = array(
	"description","cout","type","valeur","debut","fin"
	);

echo "<h1>Réductions</h1>\n";

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

	echo "<script type='text/javascript' src='Scripts/jquery.js'></script>\n<script type='text/javascript' src='$filename[JSreductions]'></script>\n";
	
/*
 * Fonctions
 */

/*---------------------------*
 * Fonction :	liste
 * Paramètres :	Aucun
 * Retour :		Aucun
 * Description :	Génère l'affichage de la liste des réductions enregistrées.
/*---------------------------*/
function liste()
{
	echo "<h2>Liste des réductions</h2>\n";
	echo "<a href=\"index.php?page=reductions&mode=ajouter\">Ajouter une réduction</a>\n";
	
	$reductions = getAllReductions();
	echo "<input type=\"text\" id=\"recherche\" placeholder=\"Rechercher\" />";
	echo "<table id=\"liste\" border=\"1\">\n<tr><th>Description</th><th>Coût</th><th>Valeur</th><th>Date de début</th><th>Date de fin</th></tr>\n";
	foreach($reductions as $reduction)
	{
		echo "<tr id=\"$reduction[id]\"><td><a href=\"index.php?page=reductions&mode=fiche&id=$reduction[id]\">$reduction[description]</a></td><td>$reduction[cout]</td><td>$reduction[valeur] ";
		if($reduction['type'] == "brut") // Affichage du symbole "%" ou "€" en fonction du type
			echo "€";
		else
			echo "%";
		echo "</td><td>$reduction[debut]</td><td>$reduction[fin]</td></tr>\n";
	}
	echo "</table>";
}

/*---------------------------*
 * Fonction :	fiche
 * Paramètres :	id (GET) - Entier
 * Retour :		Aucun
 * Description :	Génère l'affichage de la fiche d'une réduction ou d'un message d'erreur, le cas échéant.
/*---------------------------*/
function fiche()
{
	echo "<h2>Fiche réduction</h2>";
	echo "<a href=\"index.php?page=reductions&mode=liste\">Retour à la liste</a>\n";
	if(isset($_GET['id']) && !empty($_GET['id']))
	{
		$reduction = getReductionById($_GET['id']) or die('Une erreur est survenue.');
		echo "<a href=\"index.php?page=reductions&mode=modifier&id=$reduction[id]\">Modifier la réduction</a>\n";
		echo "<a href=\"index.php?page=reductions&mode=supprimer&id=$reduction[id]\">Supprimer la réduction</a>\n";
		echo "<table border=\"1\">\n
			<tr><th>Description</th><td>$reduction[description]</td></tr>\n
			<tr><th>Coût</th><td>$reduction[cout]</td></tr>\n
			<tr><th>Valeur</th><td>$reduction[valeur] ";
		if($reduction['type'] == "brut")
			echo "€";
		else
			echo "%";
		echo "<tr><th>Validité</th><td>Du $reduction[debut] au $reduction[fin]</td></tr>\n";
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
	echo "<h2>Ajout d'une réduction</h2>\n";
	echo "<a href=\"index.php?page=reductions&mode=liste\">Annuler</a>\n";
	?>
	<form method="post" action="index.php?page=reductions">
	<input type="hidden" name="submit" value="ajouter" />
	<table border="1">
		<tr><th>Description</th><td><input type="text" name="description" /></td></tr>
		<tr><th>Coût</th><td><input type="text" name="cout" /></td></tr>
		<tr><th>Valeur</th><td><input type="text" name="valeur" /> <select name="type">
			<option value="pourcent">%</option>
			<option value="brut">€</option></select></td></tr>
		<tr><th>Date de début</th><td><input type="date" name="debut" /></td></tr>
		<tr><th>Date de fin</th><td><input type="date" name="fin" /></td></tr>
	</table>
	
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
	echo "<h2>Modification d'une réduction</h2>\n";
	echo "<a href=\"index.php?page=reductions&mode=liste\">Annuler</a>\n";

	if(isset($_GET['id']) && !empty($_GET['id']))
	{
		$reduction = getReductionById($_GET['id']) or die('Une erreur est survenue.');
		echo "<form method='post' action='index.php?page=reductions'>\n
		<input type='hidden' name='submit' value='modifier' />\n
		<input type='hidden' name='id' value='$reduction[id]' />\n
		<table border='1'>\n
		<tr><th>Description</th><td><input type='text' name='description' value='$reduction[description]' /></td></tr>\n
		<tr><th>Coût</th><td><input type='text' name='cout' value='$reduction[cout]' /></td></tr>\n
		<tr><th>Valeur</th><td><input type='text' name='valeur' value='$reduction[valeur]' /> <select name='type'>\n
			<option value='pourcent' ";
			if($reduction['type'] == "pourcent")
				echo "selected";
			echo ">%</option>\n
			<option value='brut' ";
			if($reduction['type'] == "brut")
				echo "selected";
			echo ">€</option></select></td></tr>\n
		<tr><th>Date de début</th><td><input type='date' name='debut' value='$reduction[debut]' /></td></tr>\n
		<tr><th>Date de fin</th><td><input type='date' name='fin' value='$reduction[fin]' /></td></tr>\n
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
	echo "<h2>Suppression d'une réduction</h2>\n";
	echo "<a href=\"index.php?page=reductions&mode=liste\">Annuler</a>\n";

	if(isset($_GET['id']) && !empty($_GET['id']))
	{
		$reduction = getReductionById($_GET['id']) or die('Une erreur est survenue.');
		echo "<form method='post' action='index.php?page=reductions'>\n
		<input type='hidden' name='submit' value='supprimer' />\n
		<input type='hidden' name='id' value='$reduction[id]' />\n
		<p>Êtes-vous sûr de vouloir supprimer la réduction \"$reduction[description]\" ?</p>\n
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
			if(newReduction($toInsert))
				echo "<p class='notification'>Réduction ajoutée !</p>";
			else
				echo "<p class='notification'>Échec de l'ajout</p>";
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
			if(updateReduction($_POST['id'],$toInsert))
				echo "<p class='notification'>Réduction modifiée !</p>";
			else
				echo "<p class='notification'>Échec de la modification.</p>";
			break;

		/*
		 * Supprimer
		 */
		case 'supprimer':
			if(deleteReduction($_POST['id']))
				echo "<p class='notification'>Réduction supprimée !</p>";
			else
				echo "<p class='notification'>Échec de la suppression.</p>";
			break;
	}
}
?>