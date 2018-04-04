<?php
	require 'db_access.php';
	header("Content-type: application/json");

	// Encoding JSON
	if ($_SERVER['REQUEST_METHOD'] === 'GET') {
		if ($_GET["query"] === 'all' || !isset($_GET["query"])) {
				
			/* Gestion de la requete qui recupere toutes les infos de chaque mesure de la DB
			 * et l'affiche sous format JSON pour que le WebMapping puisse l'utiliser. */
			
			/* Executions de requetes SQL preparees dans db_access.php */
			$stmt_readMesure->execute(); /* Contient toutes les mesures */
			$tabMesure = $stmt_readMesure->fetchAll(PDO::FETCH_ASSOC);
			$stmt_readMeta->execute(); /* Contient toutes les meta_mesures */
			$tabMeta = $stmt_readMeta->fetchAll(PDO::FETCH_ASSOC);
			$stmt_readCapteur->execute(); /* Contient toutes les capteurs */
			$tabCapteur = $stmt_readCapteur->fetchAll(PDO::FETCH_ASSOC);

			/* Construction du JSON conformement aux specs du Webmapping */
			$tabMatchWebMapping = array();
			$tabMatchWebMapping["type"] = "FeatureCollection";
			$tabFeatures = array();
			/* $i represente le numero de la mesure actuelle */
			$i = 0;
			/* On parcourt toutes les mesures une à une */
			foreach ($tabMesure as &$uneMesure) {
				$infoMesure = array();
				$infoMesure["type"] = "Feature";
				$infoMesureProperties = array();
				$infoMesureProperties["id_mesure"]	= $uneMesure["id"];
				$infoMesureProperties["id_capteur"] = $uneMesure["id_capteur"];
				$infoMesureProperties["id_meta"] 	= $uneMesure["id_meta"];
				$infoMesureProperties["valeur"]	 	= $uneMesure["valeur"];
				$infoMesureGeometry = array();
				$infoMesureGeometry["type"] = "Point";
				$infoMesureGeometryCoordinates = array();
				/* On parcourt toutes les meta_mesures une à une */
				foreach ($tabMeta as &$uneMeta) {
					if($uneMesure['id_meta'] == $uneMeta['id']) {
						// Si on trouve la meta_mesure qui match avec la mesure
						$infoMesureProperties["date"]		= $uneMeta["date"];
						$infoMesureGeometryCoordinates[0] 	= $uneMeta["gps_long"];
						$infoMesureGeometryCoordinates[1] 	= $uneMeta["gps_lat"];
						break;
					}
				}
				/* On parcourt toutes les capteurs un à un */
				foreach ($tabCapteur as &$unCapteur) {
					if ($uneMesure['id_capteur'] == $unCapteur['id']) {
						// Si on trouve le capteur qui match avec la mesure
						$infoMesureProperties["type"] = $unCapteur["type"];
						break;
					}
				}
				$infoMesure["properties"] = $infoMesureProperties;
				$infoMesureGeometry["coordinates"] = $infoMesureGeometryCoordinates;
				$infoMesure["geometry"] = $infoMesureGeometry;
				$tabFeatures[$i] = $infoMesure;
				$i++;
			}
			$tabMatchWebMapping["features"] = $tabFeatures;
			/* On transforme le tableau final en JSON, en precisant JSON_NUMERIC_CHECK pour
			 * ne pas avoir de guillemets autour des valeurs de type Int */
			echo json_encode($tabMatchWebMapping, JSON_NUMERIC_CHECK);
		} else if ($_GET["query"] === 'filter') {
		
			/* Gestion d'une requete avec des arguments pour filtrer les donnees */
		
			foreach($_GET as $key => $value) {
				if($key != "valavg" && 
				$key != "id_mesure" && 
				$key != "id_capteur" && 
				$key != "id_meta" && 
				$key != "valmin" && 
				$key != "valmax" && 
				$key != "date" && 
				$key != "type") {
					trigger_error("Parametre de filtre non supporte : ".$key, E_USER_ERROR);
				}
			}
		
			$query = "SELECT m.id, m.id_capteur, m.id_meta, m.valeur, mt.date, mt.gps_lat, mt.gps_long, c.type FROM mesures m, meta_mesures mt, capteurs c WHERE";
			/* $queryOptions va venir se concatener a la fin de $query, dependemment des
			 *  arguments fournis */
			$queryOptions = "";
			/* booleen $ajout permettant de savoir s'il y a besoin d'un AND (2 ou + arguments) */
			$ajout = false;
			if (isset($_GET["valavg"])) {
				$query = "SELECT AVG(m.valeur) FROM mesures m, meta_mesures mt, capteurs c WHERE";
			}
			if (isset($_GET["id_mesure"])) {
				if ($ajout) {
					$queryOptions .= " AND";
				}
				$queryOptions .= " m.id =".$_GET["id_mesure"];
				$ajout = true;
			}
			if (isset($_GET["id_capteur"])) {
				if ($ajout) {
					$queryOptions .= " AND";
				}
				$queryOptions .= " c.id = ".$_GET["id_capteur"];
				$ajout = true;
			}
			if (isset($_GET["id_meta"])) {
				if ($ajout) {
					$queryOptions .= " AND";
				}
				$queryOptions .= " mt.id = ".$_GET["id_meta"];
				$ajout = true;
			}
			if (isset($_GET["valmin"])) {
				if ($ajout) {
					$queryOptions .= " AND";
				}
				$queryOptions .= " m.valeur >= ".$_GET["valmin"];
				$ajout = true;
			}
			if (isset($_GET["valmax"])) {
				if ($ajout) {
					$queryOptions .= " AND";
				}
				$queryOptions .= " m.valeur <= ".$_GET["valmax"];
				$ajout = true;
			}
			if (isset($_GET["date"])) {
				if ($ajout) {
					$queryOptions .= " AND";
				}
				$queryOptions .= " mt.date = ".$_GET["date"];
				$ajout = true;
			}
			if (isset($_GET["type"])) {
				if ($ajout) {
					$queryOptions .= " AND";
				}
				$queryOptions .= " c.type = '".$_GET["type"]. "'";
				$ajout = true;
			}
			$query .= $queryOptions . " AND m.id_meta = mt.id AND m.id_capteur = c.id;";
			//echo $query;
			/* Execution SQL */
			$stmt = $db_read->prepare($query);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			/* Formattage JSON pour la meme finalite qu'en haut */
			$tabMatchWebMapping 					= array();
			$tabMatchWebMapping["type"] 			= "FeatureCollection";
			$tabFeatures 							= array();
			$i = 0;
			foreach ($result as &$uneLigne) {
				$infoMesure 						= array();
				$infoMesure["type"] 				= "Feature";
				$infoMesureProperties 				= array();
				$infoMesureProperties["id_mesure"]	= $uneLigne["id"];
				$infoMesureProperties["id_capteur"] = $uneLigne["id_capteur"];
				$infoMesureProperties["id_meta"] 	= $uneLigne["id_meta"];
				$infoMesureProperties["valeur"]	 	= $uneLigne["valeur"];
				$infoMesureGeometry 				= array();
				$infoMesureGeometry["type"] 		= "Point";
				$infoMesureGeometryCoordinates 		= array();
				$infoMesureProperties["date"]		= $uneLigne["date"];
				$infoMesureGeometryCoordinates[0] 	= $uneLigne["gps_long"];
				$infoMesureGeometryCoordinates[1] 	= $uneLigne["gps_lat"];
				$infoMesureProperties["type"] 		= $uneLigne["type"];
				$infoMesure["properties"] 			= $infoMesureProperties;
				$infoMesureGeometry["coordinates"] 	= $infoMesureGeometryCoordinates;
				$infoMesure["geometry"] 			= $infoMesureGeometry;
				$tabFeatures[$i] 					= $infoMesure;
				$i++;
			}
			$tabMatchWebMapping["features"] = $tabFeatures;
			/* On transforme le tableau final en JSON, en precisant JSON_NUMERIC_CHECK pour
			 * ne pas avoir de guillemets autour des valeurs de type Int */
			echo json_encode($tabMatchWebMapping, JSON_NUMERIC_CHECK);
		}
	}
?>
	
	
	
	

