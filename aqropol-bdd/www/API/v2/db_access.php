<?php
	/* Fichier relatif a la connexion a la base de donnees et aux requetes SQL preparees */

	require 'config.php';

	try {
		$db = new PDO($dsn,$root['username'],$root['password']);
	} catch (PDOException $e) {
		echo 'Connexion échouée : '.$e->getMessage();
	}
	if (isset($user)) {
		try {
			$db_read = new PDO($dsn,$user['username'],$user['password']);
		} catch (PDOException $e) {
			echo 'Connexion échouée : '.$e->getMessage();
		}
	}
	else {
		$db_read = $db;
	}
	/* Insertion de donnees dans la table mesures */
	$stmt_insertMesures = $db->prepare('INSERT INTO mesures (id_capteur, id_meta, valeur) VALUES (:id_capteur, :id_meta, :valeur);');
	
	/* Insertion de donnees dans la table meta_mesures */
	$stmt_insertMetaMesures = $db->prepare('INSERT INTO meta_mesures (id_hub, date, gps_long, gps_lat, hash) VALUES (:id_hub, :date, :gps_long, :gps_lat, :hash);');
	
	/* Insertion de donnees dans la table capteurs */
	$stmt_insertCapteurs = $db->prepare('INSERT INTO capteurs (id_hub, name, type) VALUES (:id_hub, :name, :type);');
	
	/* Insertion de donnees dans la table hubs */
	$stmt_insertHubs = $db->prepare('INSERT INTO hubs (name) VALUES (:name);');

	/* Lecture de la table mesures */
	$stmt_readMesure = $db_read->prepare('SELECT m.id,m.id_capteur,m.id_meta,m.valeur,c.type FROM mesures m,capteurs c WHERE m.id_capteur = c.id;');
	
	/* Lecture de la table meta_mesures */
	$stmt_readMeta = $db_read->prepare('SELECT id,id_hub,date,gps_lat,gps_long FROM meta_mesures;');
	
	/* Lecture de la table capteurs */
	$stmt_readCapteur = $db_read->prepare('SELECT id,id_hub,type FROM capteurs;');
	
	/* Lecture de la table hubs */
	$stmt_readHub = $db_read->prepare('SELECT id,name FROM hubs;');
	
	/* Lecture du dernier hash */
	$stmt_readLastHash = $db_read->prepare('SELECT id_hub, hash FROM meta_mesures m1 WHERE date = (SELECT max(date) FROM meta_mesures m2 WHERE m1.id_hub = m2.id_hub)');
?>
