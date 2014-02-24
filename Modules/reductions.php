<?php
	/*
	 * reductions.php
	 * Fidelity
	 * 
	 * Created by Richard Degenne on 5/1/2014. CC by-nc-sa.
	 * 
	 * Page répertoire pour l'affichage, la création, la modification de la liste de réductions disponibles.
	 */

echo "<h1>Réductions</h1>\n";

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
	
function liste()
{
	echo "<h2>Liste des réductions</h2>\n";
	$reductions = getAllReductions();
	
	echo "<table border=\"1\">\n<tr><th>Description</th><th>Coût</th><th>Type</th><th>Valeur</th><th>Date de début</th><th>Date de fin</th></tr>\n";
	foreach($reductions as $reduction)
	{
		echo "<tr><td>$reduction[description]</td><td>$reduction[cout]</td><td>$reduction[type]</td><td>$reduction[valeur]</td><td>$reduction[debut]</td><td>$reduction[fin]</td></tr>\n";
	}
	echo "</table>";
}

function fiche()
{
	echo "<h2>Fiche réduction</h2>";
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