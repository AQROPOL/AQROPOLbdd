<?php
	require 'db_access.php';
	header("Content-type: application/json");

	if ($_SERVER['REQUEST_METHOD'] === 'GET'){
			$meta_mesures = array();
			$stmt_readMesure->execute();
			$tabMesure = $stmt_readMesure->fetchAll(PDO::FETCH_ASSOC);
			$stmt_readMeta->execute();
			$tabMeta = $stmt_readMeta->fetchAll(PDO::FETCH_ASSOC);
			$i = 0;
			// Ici on lie chaque "mesures" a la "meta_mesures" qui lui correspond
			foreach($tabMeta as &$meta){
				$mesures = array();
				$i_mesure = 0;
				foreach($tabMesure as &$mesure){
					if($mesure['id_meta'] == $meta['id']){
						$mesures[$i_mesure++] = $mesure;
					}
				}
				$meta_mesures[$i] = $meta;
				$meta_mesures[$i]["mesures"] = $mesures;
				$i++;
			}
			//$result = array("meta_mesures" => $meta_mesures);
			////////////
			$tabMatchWebMapping = array();
			$tabMatchWebMapping["type"] = "FeatureCollection";
			$tabFeatures = array();
			foreach ($meta_mesures as &$mesure) {
				$tabFeatures["type"] = "Feature";

				$tabFeaturesProperties = array();
				$tabFeaturesProperties["id_mesure"]	= $mesure["mesures"]["id"];
				$tabFeaturesProperties["id_capteur"]	= $mesure["mesures"]["id_capteur"];
				$tabFeaturesProperties["valeur"]		= $mesure["mesures"]["valeur"];
				$tabFeaturesProperties["type"]		= $mesure["mesures"]["type"];
				$tabFeaturesProperties["id_meta"] 		= $mesure["id"];
				$tabFeaturesProperties["id_hub"] 		= $mesure["id_hub"];
				$tabFeaturesProperties["date"] 		= $mesure["date"];
				$tabFeatures["properties"] = $tabFeaturesProperties;

				$tabFeaturesGeometry = array();
				$tabFeaturesGeometry["type"] = "Point";
				$tabFeaturesGeometryCoordinates = array();
				$tabFeaturesGeometryCoordinates[0] 	= $mesure["gps_lat"];
				$tabFeaturesGeometryCoordinates[1] 	= $mesure["gps_long"];
				$tabFeaturesGeometry["coordinates"] = $tabFeaturesGeometryCoordinates;
				$tabFeatures["geometry"] = $tabFeaturesGeometry;

				//$i++;
			}
			$tabMatchWebMapping["features"] = $tabFeatures;
			echo json_encode($tabMatchWebMapping);
	}
?>
