<?php
	/*
	 * caisse.php
	 * Fidelity
	 * 
	 * Created by Richard Degenne on 5/1/2014. CC by-nc-sa.
	 * 
	 * Menu de caisse. Affichage et gestion des paniers.
	 */
	if(isset($_POST["submit"])){
		foreach($_POST as $key => $value){
			if($key=="cagnotte"){
				
			}
			if($key=="check"){

			}
		}
	}
?>
<h1>Caisse</h1>

<form action="index.php?page=caisse" method="post">
	<input type="hidden" name="submit" value="reducter" />
	<input id="idUser" type="hidden" name="idUser" value="" />
	<input id="cagnotteF" type="hidden" name="cagnotte" value="" />

	Numéro de carte: <input id="numeroCarte" onkeydown="verifEntree()"autofocus />
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
		<input type="text" id="preReduc" placeholder="Montant" onkeyup="transfertMontant()" /><br />
		<input type="text" id="postReduc" placeholder="Montant post-réduction" style=display:none /><br />
	</div>
	
	<input type="submit" id="appliqueReduc" value="Appliquer la réduction" style=display:none />

	<script type='text/javascript' src='Scripts/jquery.js'></script>
	<script type='text/javascript' src='Scripts/caisse.js'></script>
</form>