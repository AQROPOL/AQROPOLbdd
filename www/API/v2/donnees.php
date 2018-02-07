<?php
	require 'db_access.php';

	if ($_SERVER['REQUEST_METHOD'] === 'GET'){
			$meta_mesures = array();
			$stmt_readMesure->execute();
			$tabMesure = $stmt_readMesure->fetchAll(PDO::FETCH_ASSOC);
			$stmt_readMeta->execute();
			$tabMeta = $stmt_readMeta->fetchAll(PDO::FETCH_ASSOC);
			$i = 0;
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
			echo json_encode($result);
	}
?>
