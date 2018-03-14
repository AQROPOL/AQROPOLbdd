<?php
	require 'db_access.php';
	header("Content-type: application/json");

	// Encoding JSON
	if ($_SERVER['REQUEST_METHOD'] === 'GET'){
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
				$infoMesureProperties["id_mesure"]	 = $uneMesure["id"];
				$infoMesureProperties["id_capteur"] = $uneMesure["id_capteur"];
				$infoMesureProperties["id_meta"] 	 = $uneMesure["id_meta"];
				$infoMesureProperties["valeur"]	 = $uneMesure["valeur"];
				$infoMesureGeometry = array();
				$infoMesureGeometry["type"] = "Point";
				$infoMesureGeometryCoordinates = array();
				foreach($tabMeta as &$uneMeta){
					if($uneMesure['id_meta'] == $uneMeta['id']){
						// Si on trouve la meta qui correspond a la mesure
						$infoMesureProperties["date"]= $uneMeta["date"];
						$infoMesureGeometryCoordinates[0] = $uneMeta["gps_long"];
						$infoMesureGeometryCoordinates[1] = $uneMeta["gps_lat"];
						break;
					}
				}
				foreach ($tabCapteur as &$unCapteur) {
					if($uneMesure['id_capteur'] == $unCapteur['id']){
						// Si on trouve le capteur qui correspond a la mesure
						$infoMesureProperties["type"]= $unCapteur["type"];
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
			echo json_encode($tabMatchWebMapping);
	}
<<<<<<< HEAD
	// DECODING JSON
	if ($_SERVER['REQUEST_METHOD'] === 'POST'){
=======
	else if ($_SERVER['REQUEST_METHOD'] === 'POST'){
>>>>>>> e40a5db78c11aece9a8872c862a908fe19971ddb
		
	}
?>
