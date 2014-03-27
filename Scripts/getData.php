<?php
ini_set("display_errors", 0);

include_once("../filenames.php");
include_once("../Database/db_connect.php");
include_once("../Database/db_functions.php"); // Inclusion du fichier de fonctions base de données
if(isset($_GET['recup'])){
	if($_GET['recup']=='client'){
		$client = getClientByCard($_GET['card']);

		$reducs = getValidReductions($client["id"]);

		$data = array("client" =>$client, "reducs" =>$reducs);

		$toReturn = json_encode($data);
	}
	else if($_GET['recup']=='reduc'){
		$reducs = getReductionById($_GET['id']);
		$toReturn = json_encode($reducs);
	}
}

echo $toReturn;
?> 