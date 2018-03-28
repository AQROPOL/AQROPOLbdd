<?php
	require 'db_access.php';
	header("Content-type: application/json");

	// Encoding JSON
	if ($_SERVER['REQUEST_METHOD'] === 'GET') {
		if ($_GET["query"] === 'all') {
				$meta_mesures = array();
				$stmt_readMesure->execute();
				$tabMesure = $stmt_readMesure->fetchAll(PDO::FETCH_ASSOC);
				$stmt_readMeta->execute();
				$tabMeta = $stmt_readMeta->fetchAll(PDO::FETCH_ASSOC);
				$stmt_readCapteur->execute();
				$tabCapteur = $stmt_readCapteur->fetchAll(PDO::FETCH_ASSOC);

				$tabMatchWebMapping = array();
				$tabMatchWebMapping["type"] = "FeatureCollection";
				$tabFeatures = array();
				$i = 0;
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
					foreach($tabMeta as &$uneMeta){
						if($uneMesure['id_meta'] == $uneMeta['id']){
							// Si on trouve la meta qui correspond a la mesure
							$infoMesureProperties["date"]		= $uneMeta["date"];
							$infoMesureGeometryCoordinates[0] 	= $uneMeta["gps_long"];
							$infoMesureGeometryCoordinates[1] 	= $uneMeta["gps_lat"];
							break;
						}
					}
					foreach ($tabCapteur as &$unCapteur) {
						if($uneMesure['id_capteur'] == $unCapteur['id']){
							// Si on trouve le capteur qui correspond a la mesure
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
				echo json_encode($tabMatchWebMapping, JSON_NUMERIC_CHECK);
		} else if ($_GET["query"] === 'filter') {
			$query = "SELECT * FROM mesures m, meta_mesures mt, capteurs c WHERE";
			$queryOptions = "";
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
				$queryOptions .= " mt.type = '".$_GET["type"]. "'";
				$ajout = true;
			}
			$query .= $queryOptions . ";";
			echo $query;
			$result = $db_read->prepare($query);
			$result->execute();
			$donnees = $result->fetchAll(PDO::FETCH_ASSOC);
			echo $donnees;
		}
	}
	// DECODING JSON
	else if ($_SERVER['REQUEST_METHOD'] === 'POST'){
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	/**/
?>
