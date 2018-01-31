<?php
	require 'config.php';

	try {
		$db = new PDO($dsn,$username,$password);
	} catch (PDOException $e) {
		echo 'Connexion échouée : '.$e->getMessage();
	}
	$stmt_insertMesures = $db->prepare('INSERT INTO mesures (id, id_capteur, id_meta, valeur) VALUES (:id, :id_capteur, :id_meta, :mesure);');
	$stmt_insertMetaMesures = $db->prepare('INSERT INTO meta_mesures (id, id_hub, date, gps_long, gps_lat) VALUES (:id, :id_hub, :date, :gps_long, :gps_lat);');
	$stmt_insertCapteurs = $db->prepare('INSERT INTO capteurs (id, id_hub, type) VALUES (:id, :id_hub, :type);');
	$stmt_insertHubs = $db->prepare('INSERT INTO hubs (name) VALUES (:name);');

	$stmt_readMesure = $db->prepare('SELECT m.id,m.id_capteur,m.id_meta,m.valeur,c.type FROM mesures m,capteurs c WHERE m.id_capteur = c.id;');
	$stmt_readMeta = $db->prepare('SELECT id,id_hub,date,gps_lat,gps_long FROM meta_mesures;');
	$stmt_readCapteur = $db->prepare('SELECT id,id_hub,type FROM capteurs;');
	$stmt_readHub = $db->prepare('SELECT id,name FROM hubs;');
	$stmt_readLastHash = $db->prepare('SELECT id_hub, hash FROM meta_mesures m1 WHERE date = (SELECT max(date) FROM meta_mesures m2 WHERE m1.id_hub = m2.id_hub)');
?>

