<?php
	/*
	 * db_connect.php
	 * Fidelity
	 * 
	 * Created by Richard Degenne on 17/02/2014. CC by-nc-sa.
	 * 
	 * Ce script gère la connexion entre Fidelity et la base de données.
	 */
	
	/*
	 * Paramètres à adapter selon votre configuration
	 */
	
	$host		= "localhost";
	$dbname		= "fidelity";
	$username	= "root";
	$password	= "";
	
	try
	{
		$db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
	}
	catch (Exception e)
	{
		die("<p><strong>Erreur</strong> : Échec de la connexion à la base de données.<br />$e->getMessage()</p>");
	}
?>