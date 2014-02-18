<?php
	/*
	 * db_functions.php
	 * Fidelity
	 * 
	 * Created by Richard Degenne on 17/02/2014. CC by-nc-sa.
	 * 
	 * Ce fichier regroupe l'ensemble des fonctions liées à la base de données.
	 */
	
	include_once("db_connect.php");
	
	$db = db_connect();
	
	/*
	 * Lecture
	 */
	
	// Clients
	
	function getAllClients()
	{
		global $db;
		$request = $db->prepare('SELECT * from `Clients`');
		$request->execute();
		while($data = $request->fetch())
		{
			$toReturn[] = array(
				"id" => $data['id'],
				"nom" => $data['nom'],
				"prenom" => $data['prenom'],
				"numeroCarte" => $data['numeroCarte'],
				"adresse" => $data['adresse'],
				"ville" => $data['ville'],
				"codePostal" => $data['codePostal'],
				"telephone" => $data['telephone'],
				"mail" => $data['mail'],
				"aboMail" => $data['aboMail'],
				"aboSms" => $data['aboSms'],
				"cagnotte" => $data['cagnotte']
				);
		}
		
		$response->closeCursor();
		return $toReturn;
	}
	
	function getClientById($id)
	{
		global $db;
		$request = $db->prepare("SELECT * from `Clients` WHERE `id` = ?");
		$request->execute(array($id));
		
		if($data = $request->fetch())
		{
			$request->closeCursor();
			return array(
				"id" => $data['id'],
				"nom" => $data['nom'],
				"prenom" => $data['prenom'],
				"numeroCarte" => $data['numeroCarte'],
				"adresse" => $data['adresse'],
				"ville" => $data['ville'],
				"codePostal" => $data['codePostal'],
				"telephone" => $data['telephone'],
				"mail" => $data['mail'],
				"aboMail" => $data['aboMail'],
				"aboSms" => $data['aboSms'],
				"cagnotte" => $data['cagnotte']
				);
		}
		else
			return E_ERROR;
	}
	
	// Réductions
		
	
	/*
	 * Écriture
	 */
	
	// Clients
	
	// Réductions
	
	
?>