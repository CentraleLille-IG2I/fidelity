<?php
	/*
	 * db_functions.php
	 * Fidelity
	 * 
	 * Created by Richard Degenne on 17/02/2014. CC by-nc-sa.
	 * 
	 * Ce fichier regroupe l'ensemble des fonctions liées à la base de données.
	 */
	
	include_once($filename['db_connect']);
	
	$db = db_connect();
	
	/* == plop
	   == je me perme d'inserer ma shitstorm ici, puisse qu'elle est relative aux noms utiliser dans la bdd
	*/ 
	// donc, un tableau qui contient les noms utiliser pour les sorties de ces fonctions et qui seront utilisable ds les mail
	// sa offre plus de dinamisme et facilite les modifications
	$outNames = array("civilite", "nom", "prenom", "numeroCarte",	"adresse", "ville", "codePostal", "telephone", "telephone2", "mail", "cagnotte", "dateDeNaissance", "interets");
	
	
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
			$toReturn[] = $data;
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
			return $data;
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
			return $data;
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
				$toReturn[] = $data;
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
		$toReturn = array();
		while($data = $request->fetch())
		{
			$toReturn[] = $data;
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
			return $data;
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
		$request = $db->prepare("SELECT * from `Reductions` WHERE `debut` <= CURDATE() AND `fin` >= CURDATE() AND `cout` <= $cagnotte");
		$request->execute();
		
		$toReturn = array();
		while($data = $request->fetch())
		{
			$toReturn[] = $data;
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
				$toReturn[] = $data;
			}
			$request->closeCursor();
			return $toReturn;
		}
		else
		{
			return E_ERROR;
		}
	}
	
	// Historique
	
	/*---------------------------*
	 * Fonction :	getTodayTotal
	 * Paramètres :	aucun
	 * Retour :		Tableau associatif
	 * Description :	Récupère les totaux vendus et réduits d'aujourd'hui
	/*---------------------------*/
	function getTodayTotal()
	{
		global $db;
		
		$date = date("Y-m-d");
		$request = $db->prepare("SELECT SUM(`total`) AS total, SUM(`reduction`) AS reduction FROM HISTORIQUE WHERE `date`=?");
		$request->execute(array($date));
		if($data = $request->fetch())
		{
			$request->closeCursor();
			return $data;
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
	 * Description :	Insère un nouveau client en vérifiant l'intégrité des champs avant insertion. Renvoie un booléen fonction de la réussite de l'insertion.
	/*---------------------------*/
	function newClient($toInsert)
	{
		global $db;
		if(isset($toInsert['numeroCarte']) && isset($toInsert['civilite']) && isset($toInsert['nom']) && isset($toInsert['prenom']) && isset($toInsert['adresse']) && isset($toInsert['ville']) && isset($toInsert['aboMail']) && isset($toInsert['aboSms'])) // Test de la présence des champs obligatoire
		{
			if(!empty($toInsert['numeroCarte']) && !empty($toInsert['civilite']) && !empty($toInsert['nom']) && !empty($toInsert['prenom']) && !empty($toInsert['adresse']) && !empty($toInsert['ville']) && ($toInsert['aboMail'] == 0 || $toInsert['aboMail'] == 1) && ($toInsert['aboSms'] == 0 || $toInsert['aboSms'] == 1)) // Si tous les champs obligatoires ne sont pas vides (condition différente pour les booléens : empty renvoie true si $entier = 0)
			{
				$fields = "INSERT INTO `Clients`(`cagnotte`"; // On crée le début de la requête
				$values = "VALUES(0.0";
				
				foreach($toInsert as $key => $value) // Ensuite, pour chaque champ, on concatène la chaine existante
				{
					if(isset($toInsert[$key]) && !empty($toInsert[$key]) || $toInsert[$key] == 0)
					{
						$fields = $fields.",`".$key."`";
						$values = $values.",:".$key;
						$valuesArray[$key] = mysql_real_escape_string($value);
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
	 * Paramètres :	id — Entier
	 				toUpdate - Tableau
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
				$valuesArray[$key] = mysql_real_escape_string($value);
			}
		}
		$toRequest = $toRequest." WHERE id = :id";
		$valuesArray["id"] = $id;
		
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
	
	/*---------------------------*
	 * Fonction :	deleteClient
	 * Paramètres :	id — Entier
	 * Retour :		Booléen
	 * Description :	Supprime un client (id). 
	/*---------------------------*/
	function deleteClient($id)
	{
		global $db;
		
		$request = $db->prepare("DELETE FROM `Clients` WHERE `id`=? LIMIT 1");
		$request->execute(array($id));
		
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
						$valuesArray[$key] = mysql_real_escape_string($value);
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
				$valuesArray[$key] = mysql_real_escape_string($value);
			}
		}
		$toRequest = $toRequest." WHERE id = :id";
		$valuesArray["id"] = $id;
		
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
	
	/*---------------------------*
	 * Fonction :	deleteReduction
	 * Paramètres :	id — Entier
	 * Retour :		Booléen
	 * Description :	Supprime une réduction (id). 
	/*---------------------------*/
	function deleteReduction($id)
	{
		global $db;

		$request = $db->prepare("DELETE FROM `Reductions` WHERE `id`=? LIMIT 1");
		$request->execute(array($id));

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
	
	// Historique
	
	/*---------------------------*
	 * Fonction :	addHistory
	 * Paramètres :	toAdd - Tableau
	 * Retour :		Booléen
	 * Description :	Mets à jour les données du client après un achat et crée les entrées correspondantes dans la table Historique. 
	/*---------------------------*/
	function addHistory($toAdd)
	{
		global $db;
		if(isset($toAdd['idUser']) && isset($toAdd['cagnotte']) && isset($toAdd['valeurInitiale']) && isset($toAdd['valeurFinale']))
		{
			if((!empty($toAdd['idUser']) || $toAdd['idUser'] == 0) && (!empty($toAdd['cagnotte']) || $toAdd['cagnotte'] == 0) && (!empty($toAdd['valeurInitiale']) || $toAdd['valeurInitiale'] == 0) && (!empty($toAdd['valeurFinale']) || $toAdd['valeurFinale'] == 0))
			{
				$request = $db->prepare("UPDATE `Clients` SET `cagnotte`=?+? WHERE `id`=? LIMIT 1");
				if($toAdd['valeurFinale']=="")
				{
                    $request->execute(array($toAdd['cagnotte'],$toAdd['valeurInitiale'],$toAdd['idUser']));
                }
                else
                {
                    $request->execute(array($toAdd['cagnotte'],$toAdd['valeurFinale'],$toAdd['idUser']));
                }
                if($request->rowCount() == 0)
                {
                    $request->closeCursor();
                    return false;
                }
                
                if(isset($toAdd['check'])) // S'il y a des réductions à appliquer
                {
                    foreach($toAdd['check'] as $idReduc) // Pour chaque réduction
                    {
                        $request = $db->prepare("INSERT INTO `Historique` (`idClient`,`idReduction`,`total`,`reduction`,`date`) VALUES (?,?,?,?,CURDATE())");
                        $request->execute(array($toAdd['idUser'],$idReduc,$toAdd['valeurInitiale'],$toAdd['valeurInitiale']-$toAdd['valeurFinale']));
                        if($request->rowCount() == 0)
                        {
                            $request->closeCursor();
                            return false;
                        }
                    }
                }
                else
                {
                    $request = $db->prepare("INSERT INTO `Historique` (`idClient`,`total`,`reduction`,`date`) VALUES (?,?,0,CURDATE())");
                    $request->execute(array($toAdd['idUser'],$toAdd['valeurInitiale']));
                    if($request->rowCount() == 0)
                    {
                        $request->closeCursor();
                        return false;
                    }
                }
            }
            else
            {
                $request->closeCursor();
                return false;
            }
        }
        else
        {
            return false;
        }
        return true;
}
	
	// Toutes tables
	
	/*---------------------------*
	 * Fonction :	deleteAll
	 * Paramètres :	
	 * Retour :		Booléen
	 * Description :	Supprime l'intégralité des données
	/*---------------------------*/
	function deleteAll()
	{
		global $db;
		$request = $db->prepare("DELETE FROM `Clients` WHERE 1");
		$request->execute(array());
		$request = $db->prepare("DELETE FROM `Reductions` WHERE 1");
		$request->execute(array());
		return true;
	}
	
    
	/*---------------------------*
	 * Fonction :	import
	 * Paramètres : POST
	 * Retour :		Booléen
	 * Description :	Réinitialise la base de données et importe une nouvelle version
     /*---------------------------*/
	function import()
	{
		global $db;
		$hostname	= "localhost";
		$dbname		= "fidelity";
		$username	= "root";
		$password	= "root";
        $dumpfile   = "~/Desktop/fidelity.sql";
        
        // Nom de la commande à changer selon le système
        $command = "/Applications/MAMP/Library/bin/mysql --host=$hostname --database=$dbname --user=$username --execute=$dumpfile ";
        if($password)
            $command.= "--password=$password";
        echo $command;
        exec($command,$null,$toReturn);
        return ($toReturn == 0);
    }
	
    
	/*---------------------------*
	 * Fonction :	backup
	 * Paramètres :
	 * Retour :		Booléen
	 * Description :	Sauvegarde un fichier SQL contenant l'intégralité de la BDD (structure & contenu)
     /*---------------------------*/
	function backup()
	{
		global $db;
		$hostname	= "localhost";
		$dbname		= "fidelity";
		$username	= "root";
		$password	= "root";
        $dumpfile   = "~/Desktop/fidelity.sql";
        
        // Nom de la commande à changer selon le système
        $command = "/Applications/MAMP/Library/bin/mysqldump --host=$hostname --user=$username ";
        if($password)
            $command.= "--password=$password --skip-comments ";
        $command .= $dbname." > $dumpfile";
        echo "<pre>$command</pre>";
        exec($command,$null,$toReturn);
        return ($toReturn == 0);
    }
?>