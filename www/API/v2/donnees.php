<?php
	require 'db_access.php';

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
			$result = array("meta_mesures" => $meta_mesures);
			////////////
			$tabMatchWebMapping = array();
			$tabMatchWebMapping["type"] = "FeatureCollection";
			$tabFeatures = array();
			$i = 0;
			foreach ($mesure as $result) {
				$tabFeatures["type"] = "Feature";

				$tabFeaturesProperties = array();
				$tabFeaturesProperties["id_mesure"]	= $result["meta_mesures"][$i++]["mesures"]["id"];
				$tabFeaturesProperties["id_capteur"]	= $result["meta_mesures"][$i++]["mesures"]["id_capteur"];
				$tabFeaturesProperties["valeur"]		= $result["meta_mesures"][$i++]["mesures"]["valeur"];
				$tabFeaturesProperties["type"]		= $result["meta_mesures"][$i++]["mesures"]["type"];
				$tabFeaturesProperties["id_meta"] 		= $result["meta_mesures"][$i++]["id"];
				$tabFeaturesProperties["id_hub"] 		= $result["meta_mesures"][$i++]["id_hub"];
				$tabFeaturesProperties["date"] 		= $result["meta_mesures"][$i++]["date"];
				$tabFeatures["properties"] = $tabFeaturesProperties;

				$tabFeaturesGeometry = array();
				$tabFeaturesGeometry["type"] = "Point";
				$tabFeaturesGeometryCoordinates = array();
				$tabFeaturesGeometryCoordinates[0] = $result["meta_mesures"][$i++]["gps_lat"];
				$tabFeaturesGeometryCoordinates[1] = $result["meta_mesures"][$i++]["gps_long"];
				$tabFeaturesGeometry["coordinates"] = $tabFeaturesGeometryCoordinates;
				$tabFeatures["geometry"] = $tabFeaturesGeometry;
			}
			$tabMatchWebMapping["features"] = $tabFeatures;
			echo json_encode($tabMatchWebMapping);
	}
?>
