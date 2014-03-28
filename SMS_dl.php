<?php
header('Content-disposition: attachment; filename=Numero.txt');
header('Content-type: text/plain');

include_once("filenames.php"); // Inclusion des noms de fichier
include_once($filename["db_functions"]); // Inclusion du fichier de fonctions base de données

$fic = fopen("Numero.txt", "w");

$list=getAllClients();
foreach($list as $client)
{
	if ($client["aboSms"])
	{
		if ($client["telephone"][1]=='6' or $client["telephone"][1]=='7')
		{
			echo $client["telephone"];
			echo "\r\n";
		}
		if ($client["telephone2"][1]=='6' or $client["telephone2"][1]=='7')
		{
			echo $client["telephone2"];
			echo "\r\n";
		}
	}
}

fclose($fic);

?> 