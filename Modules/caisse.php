<?php
	/*
	 * caisse.php
	 * Fidelity
	 * 
	 * Created by Richard Degenne on 5/1/2014. CC by-nc-sa.
	 * 
	 * Menu de caisse. Affichage et gestion des paniers.
	 */

echo "<h1>Caisse</h1>";

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
			case "caisse":
				caisse();
				break;
			
			case "total":
				total();
				break;
				
			default:
				caisse();
				break;
		}
	}
	else
	{
		caisse();
	}
	
	// Chargement du JavaScript
	echo "<script type='text/javascript' src='Scripts/jquery.js'></script>\n<script type='text/javascript' src='$filename[JScaisse]'></script>\n";
	
/*
 * Fonctions
 */
 
/*---------------------------*
 * Fonction :	caisse
 * Paramètres :	Aucun
 * Retour :		Aucun
 * Description :	Génère l'affichage du menu principal de la caisse.
/*---------------------------*/
function caisse()
{
?>

<a class="but" href="index.php?page=caisse&mode=total">Voir les totaux de la journée</a>
<form id="caisselist"action="index.php?page=caisse" method="post">
	<input type="hidden" name="submit" value="reducter" />
	<input id="idUser" type="hidden" name="idUser" value="" />
	<input id="cagnotteF" type="hidden" name="cagnotte" value="" />

	Numéro de carte: <input type="text" id="numeroCarte" onkeydown="verifEntree()" autofocus />
	<input type="button" onClick="getClient()" value="Valider" /><br />
	Nom: <span id="nom"></span><br />
	Prénom: <span id="prenom"></span><br />
	Ville: <span id="ville"></span><br />
	Code Postal: <span id="codePostal"></span><br />
	Numéro de téléphone: <span id="numeroTel"></span><br />
	Numéro de téléphone 2 : <span id="numeroTel2"></span><br />
	Adresse mail: <span id="adresseMail"></span><br />
	Cagnotte: <span id="cagnotte"></span><br />

	<div id="reducs"></div>
	
	<div id="prix">
		<input type="text" id="preReduc" name="valeurInitiale" placeholder="Montant" onkeyup="transfertMontant();checkSubmit();" /><br />
		<input type="text" id="postReduc" name="valeurFinale" placeholder="Montant post-réduction" style=display:none /><br />
	</div>
	
	<input type="submit" id="appliqueReduc" value="Valider la vente" />
</form>

	<script type='text/javascript' src='Scripts/jquery.js'></script>
	<script type='text/javascript' src='Scripts/caisse.js'></script>
<?php
}


function total()
{
	$values = getTodayTotal();
	echo "<a class=\"but\" href=\"index.php?page=caisse\">Annuler</a>\n";
	echo "<table ><tr><th>Chiffre d'affaire</th><td>";
	if(empty($values['total']))
		echo "0";
	else
		echo $values['total'];
	echo "&nbsp;€</td></tr>\n
	<th>Réductions</th><td>";
	if(empty($values['reduction']))
		echo "0";
	else
		echo $values['reduction'];
	echo "&nbsp;€</td></tr>\n</table>";
}

function enregistrer()
{
	if(addHistory($_POST))
	{
		echo "<p class='notification positif'>Achat validé !</p>";
	}
	else
	{
		echo "<p class='notification negatif'>Erreur lors de l'enregistrement</p>";
	}
}
?>