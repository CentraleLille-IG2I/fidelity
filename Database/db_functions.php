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
				"telephone2" => $data['telephone2'],
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
		$request = $db->prepare("SELECT * from `Clients` WHERE `id` = ? LIMIT 1");
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
				"telephone2" => $data['telephone2'],
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
	 * Fonction :	getClientByCard
	 * Paramètres :	card - Entier
	 * Retour :		Tableau ou E_ERROR
	 * Description :	Recherche un client à partir de son numéro de carte (card).
	 						- Si trouvé, renvoie un tableau associatif contenant ses informations personnelles.
	 						- Si non trouvé, renvoie E_ERROR.
	/*---------------------------*/
	function getClientByCard($card)
	{
		global $db;
		$request = $db->prepare("SELECT * from `Clients` WHERE `numeroCarte` = ? LIMIT 1");
		$request->execute(array($card));
		
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
				"telephone2" => $data['telephone2'],
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
					"telephone2" => $data['telephone2'],
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
		$request->closeCursor();
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
	
	/*---------------------------*
	 * Fonction :	getValidReductions
	 * Paramètres :	id - Entier
	 * Retour :		Tableau ou E_ERROR
	 * Description :	Recherche toutes les réductions disponibles pour un client donné
	/*---------------------------*/
	function getValidReductions($id)
	{
		global $db;
		$client = getClientById($id);
		$cagnotte = $client['cagnotte'];
		echo "SELECT * from `Reductions` WHERE `debut` <= CURDATE() AND `fin` >= CURDATE() AND `cout` <= $cagnotte\n";
		$request = $db->prepare("SELECT * from `Reductions` WHERE `debut` <= CURDATE() AND `fin` >= CURDATE() AND `cout` <= $cagnotte");
		$request->execute();
		
		$toReturn = array();
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
		$request->closeCursor();
		return $toReturn;
	}
	
	/*---------------------------*
	 * Fonction :	searchReduction
	 * Paramètres :	pattern - Mixte
	 				field - Chaîne
	 * Retour :		Tableau à 2 dimensions
	 * Description :	Recherche des réduction à partir du champ $field et de la valeur $pattern.
	 						- Renvoie un tableau contenant toutes les réductions trouvés sous forme de tableaux associatifs.
	 						- Renvoie E_ERROR si la requête échoue.
	/*---------------------------*/
	function searchReduction($pattern,$field)
	{
		global $db;
		if($request = $db->query("SELECT * FROM `Reductions` WHERE ".$field." LIKE ".$db->quote("%".$pattern."%")))
		{
			$toReturn = array();
			while($data = $request->fetch())
			{
				$toReturn[] = array(
					"id" => $data['id'],
					"description" => $data['description'],
					"cout" => $data['cout'],
					"type" => $data['type'],
					"valeur" => $data['valeur'],
					"debut" => $data['debut'],
					"fin" => $data['fin']
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
	
	
	/*
	 * Écriture
	 */
	
	// Clients
	
	/*---------------------------*
	 * Fonction :	newClient
	 * Paramètres :	toInsert - Tableau
	 * Retour :		Booléen
	 * Description :	Insère un nouveau client en vérifiant l'intégrité des champs avant insertion. Renvoie un booléen fonction de la réussite de l'insertion.
	/*---------------------------*/
	function newClient($toInsert)
	{
		global $db;
		if(isset($toInsert['numeroCarte']) && isset($toInsert['nom']) && isset($toInsert['prenom']) && isset($toInsert['adresse']) && isset($toInsert['ville']) && isset($toInsert['aboMail']) && isset($toInsert['aboSms'])) // Test de la présence des champs obligatoire
		{
			if(!empty($toInsert['numeroCarte']) && !empty($toInsert['nom']) && !empty($toInsert['prenom']) && !empty($toInsert['adresse']) && !empty($toInsert['ville']) && ($toInsert['aboMail'] == 0 || $toInsert['aboMail'] == 1) && ($toInsert['aboSms'] == 0 || $toInsert['aboSms'] == 1)) // Si tous les champs obligatoires ne sont pas vides (condition différente pour les booléens : empty renvoie true si $entier = 0)
			{
				$fields = "INSERT INTO `Clients`(`cagnotte`"; // On crée le début de la requête
				$values = "VALUES(0.0";
				
				foreach($toInsert as $key => $value) // Ensuite, pour chaque champ, on concatène la chaine existante
				{
					if(isset($toInsert[$key]) && !empty($toInsert[$key]) || $toInsert[$key] == 0)
					{
						$fields = $fields.",`".$key."`";
						$values = $values.",:".$key;
						$valuesArray[$key] = $value;
					}
				}
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
		return false;
	}
	
	/*---------------------------*
	 * Fonction :	updateClient
	 * Paramètres :	toUpdate - Tableau
	 * Retour :		Booléen
	 * Description :	Met un jour un client (id) en vérifiant l'intégrité de chaque valeur du tableau toUpdate.
	/*---------------------------*/
	function updateClient($id,$toUpdate)
	{
		global $db;
		$toRequest = "UPDATE `Clients` SET id=id"; // Amorce de la requête
		
		foreach($toUpdate as $key => $value) // Ensuite, pour chaque champ, on concatène la chaine existante
		{
			if(isset($toUpdate[$key]) && !empty($toUpdate[$key]) || $toUpdate[$key] == 0)
			{
				$toRequest = $toRequest.", ".$key." = :$key";
				$valuesArray[$key] = $value;
			}
		}
		$toRequest = $toRequest." WHERE id = :id";
		$valuesArray["id"] = $id;
		
		echo "<pre>\n";
		print_r($valuesArray);
		echo $toRequest;
		echo "</pre>";
		$request = $db->prepare($toRequest);
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
	
	// Réductions
	
	/*---------------------------*
	 * Fonction :	newReduction
	 * Paramètres :	toInsert - Tableau
	 * Retour :		Booléen
	 * Description :	Insère une nouvelle réduction en vérifiant l'intégrité des champs avant insertion. Renvoie un booléen fonction de la réussite de l'insertion.
	/*---------------------------*/
	function newReduction($toInsert)
	{
		global $db;
		if(isset($toInsert['description']) && isset($toInsert['cout']) && isset($toInsert['type']) && isset($toInsert['valeur']) && isset($toInsert['debut']) && isset($toInsert['fin'])) // Test de la présence des champs obligatoire
		{
			if(!empty($toInsert['description']) && !empty($toInsert['cout']) && !empty($toInsert['type']) && !empty($toInsert['valeur']) && !empty($toInsert['debut']) && !empty($toInsert['fin'])) // Si tous les champs obligatoires ne sont pas vides
			{
				$fields = "INSERT INTO `Reductions`(`id`"; // On crée le début de la requête
				$values = "VALUES(''";
				
				foreach($toInsert as $key => $value) // Ensuite, pour chaque champ, on concatène la chaine existante
				{
					if(isset($toInsert[$key]) && !empty($toInsert[$key]))
					{
						$fields = $fields.",`".$key."`";
						$values = $values.",:".$key;
						$valuesArray[$key] = $value;
					}
				}
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
		return false;
	}
	
	/*---------------------------*
	 * Fonction :	updateReduction
	 * Paramètres :	toUpdate - Tableau
	 * Retour :		Booléen
	 * Description :	Met un jour une réduction (id) en vérifiant l'intégrité de chaque valeur du tableau toUpdate.
	/*---------------------------*/
	function updateReduction($id,$toUpdate)
	{
		global $db;
		$toRequest = "UPDATE `Reductions` SET id=id"; // Amorce de la requête
		
		foreach($toUpdate as $key => $value) // Ensuite, pour chaque champ, on concatène la chaine existante
		{
			if(isset($toUpdate[$key]) && !empty($toUpdate[$key]) || $toUpdate[$key] == 0)
			{
				$toRequest = $toRequest.", ".$key." = :$key";
				$valuesArray[$key] = $value;
			}
		}
		$toRequest = $toRequest." WHERE id = :id";
		$valuesArray["id"] = $id;
		
		echo "<pre>\n";
		print_r($valuesArray);
		echo $toRequest;
		echo "</pre>";
		$request = $db->prepare($toRequest);
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
?>