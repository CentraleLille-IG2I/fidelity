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
	
	/*---------------------------*
	 * Fonction :	getAllClients
	 * Paramètres :	Aucun
	 * Retour :		Tableau à 2 dimensions
	 * Description :	Renvoie un tableau contenant tous les clients sous forme de tableaux associatifs.
	/*---------------------------*/
	function getAllClients()
	{
		global $db;
		$request = $db->prepare('SELECT * from `Clients`');
		$request->execute();
		while($data = $request->fetch())
		{
			$toReturn[] = array( // Le tableau est ajouté à l'ancien $toReturn
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
				"cagnotte" => $data['cagnotte'],
				"dateDeNaissance" => $data['dateDeNaissance'],
				"interets" => $data['interets']
				);
		}
		$request->closeCursor();
		return $toReturn;
	}
	
	/*---------------------------*
	 * Fonction :	getClientById
	 * Paramètres :	id - Entier
	 * Retour :		Tableau ou E_ERROR
	 * Description :	Recherche un client à partir de sa clé primaire (id).
	 						- Si trouvé, renvoie un tableau associatif contenant ses informations personnelles.
	 						- Si non trouvé, renvoie E_ERROR.
	/*---------------------------*/
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
				"cagnotte" => $data['cagnotte'],
				"dateDeNaissance" => $data['dateDeNaissance'],
				"interets" => $data['interets']
				);
		}
		else
		{
			$request->closeCursor();
			return E_ERROR;
		}
	}
	
	/*---------------------------*
	 * Fonction :	searchClient
	 * Paramètres :	pattern - Mixte
	 				field - Chaîne
	 * Retour :		Tableau à 2 dimensions
	 * Description :	Recherche des clients à partir du champ $field et de la valeur $pattern.
	 						- Renvoie un tableau contenant tous les clients trouvés sous forme de tableaux associatifs.
	 						- Renvoie E_ERROR si la requête échoue.
	/*---------------------------*/
	function searchClient($pattern,$field)
	{
		global $db;
		if($request = $db->query("SELECT * FROM `Clients` WHERE ".$field." LIKE ".$db->quote("%".$pattern."%")))
		{
			$toReturn = array();
			while($data = $request->fetch())
			{
				$toReturn[] = array( // Le tableau est ajouté à l'ancien $toReturn
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
					"cagnotte" => $data['cagnotte'],
					"dateDeNaissance" => $data['dateDeNaissance'],
					"interets" => $data['interets']
					);
			}
			$request->closeCursor();
			return $toReturn;
		}
		else
		{
			return E_ERROR;
		}
	}
	// Réductions
	
	/*---------------------------*
	 * Fonction :	getAllReductions
	 * Paramètres :	Aucun
	 * Retour :		Tableau à 2 dimensions
	 * Description :	Renvoie un tableau contenant toutes les réductions sous forme de tableaux associatifs.
	/*---------------------------*/
	function getAllReductions()
	{
		global $db;
		$request = $db->prepare('SELECT * from `Reductions`');
		$request->execute();
		while($data = $request->fetch())
		{
			$toReturn[] = array( // Le tableau est ajouté à l'ancien $toReturn
				"id" => $data['id'],
				"description" => $data['description'],
				"cout" => $data['cout'],
				"type" => $data['type'],
				"valeur" => $data['valeur'],
				"debut" => $data['debut'],
				"fin" => $data['fin']
				);
		}
		$response->closeCursor();
		return $toReturn;
	}
	
	/*---------------------------*
	 * Fonction :	getReductionById
	 * Paramètres :	id - Entier
	 * Retour :		Tableau ou E_ERROR
	 * Description :	Recherche une réduction à partir de sa clé primaire (id).
	 						- Si trouvée, renvoie un tableau associatif contenant les informations de la réduction.
	 						- Si non trouvée, renvoie E_ERROR.
	/*---------------------------*/
	function getReductionById($id)
	{
		global $db;
		$request = $db->prepare("SELECT * from `Reductions` WHERE `id` = ?");
		$request->execute(array($id));
		
		if($data = $request->fetch())
		{
			$request->closeCursor();
			return array(
				"id" => $data['id'],
				"description" => $data['description'],
				"cout" => $data['cout'],
				"type" => $data['type'],
				"valeur" => $data['valeur'],
				"debut" => $data['debut'],
				"fin" => $data['fin']
				);
		}
		else
		{
			$request->closeCursor();
			return E_ERROR;
		}
	}
	
	
	/*
	 * Écriture
	 */
	
	// Clients
	
	/*---------------------------*
	 * Fonction :	newClient
	 * Paramètres :	toInsert - Tableau
	 * Retour :		Booléen
	 * Description :	Insère un nouveau client en Vérifie l'intégrité des champs avant insertion. Renvoie un booléen fonction de la réussite de l'insertion.
	/*---------------------------*/
	function newClient($toInsert)
	{
		global $db;
		if(isset($toInsert['numeroDeCarte']) && isset($toInsert['nom']) && isset($toInsert['prenom']) && isset($toInsert['adresse']) && isset($toInsert['ville']) && isset($toInsert['aboMail']) && isset($toInsert['aboSms'])) // Test de la présence des champs obligatoire
		{
			echo "All set.\n";
			if(!empty($toInsert['numeroDeCarte']) && !empty($toInsert['nom']) && !empty($toInsert['prenom']) && !empty($toInsert['adresse']) && !empty($toInsert['ville']) && ($toInsert['aboMail'] == 0 || $toInsert['aboMail'] == 1) && ($toInsert['aboSms'] == 0 || $toInsert['aboSms'] == 1)) // Si tous les champs obligatoires ne sont pas vides (condition différente pour les booléens : empty renvoie true si $entier = 0)
			{
				echo "All not empty.\n";
				$fields = "INSERT INTO `Clients`(`cagnotte`"; // On crée le début de la requête
				$values = "VALUES('0.0'";
				
				foreach($toInsert as $key => $value) // Ensuite, pour chaque champ, on concatène la chaine existante
				{
					if(isset($toInsert[$key]) && !empty($toInsert[$key]) || $toInsert[$key] == 0)
					{
						echo "Added ".$key." => ".$value."\n";
						$fields = $fields.",`".$key."`";
						$values = $values.",:".$key;
						$valuesArray[$key] = $value;
					}
				}
				echo "\nRequest : ".$fields.") ".$values.")\n";
				echo "Parameters :<br /><pre>";
				print_r($valuesArray);
				echo "</pre>";
				$request = $db->prepare($fields.") ".$values.")");
				$request->execute($valuesArray);
				
				if($request->rowCount())
				{
					$request->closeCursor();
					return true;
				}
				else
				{
					$request->closeCursor();
					return false;
				}
			}
		}
		else
		{
		
		}return false; 

		$request = $db->prepare("SELECT * from `Clients` WHERE `id` = ?");
	}
	
	// Réductions
	
	
?>