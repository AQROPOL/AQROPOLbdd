<html>
	<head>
		<meta charset="UTF-8">
	</head>
	<body>
	<?php
		require 'db_access.php';

		if ($_SERVER['REQUEST_METHOD'] === 'GET'){
				$meta_mesures = array();
				$stmt_readMesure->execute();
				$tabMesure = $stmt_readMesure->fetchAll(PDO::FETCH_ASSOC);
				$stmt_readMeta->execute();
				$tabMeta = $stmt_readMeta->fetchAll(PDO::FETCH_ASSOC);
				$i = 0;
				$tab = array();
				foreach($tabMeta as &$meta){
					foreach($tabMesure as &$mesure){
						$tab[$i]["type"]="Feature";
						$tab[$i]["properties"]["name"]=$mesure['id'];
						$tab[$i]["properties"]["value"]=$mesure['valeur'];
						$tab[$i]["properties"]["date"]=$meta['date'];
						$tab[$i]["geometry"]["type"]="Point";
						$tmp = array();
						$tmp[0] = $meta['gps_long'];
						$tmp[1] = $meta['gps_lat'];
						$tab[$i]["geometry"]["coordinates"] = $tmp;
						$i++;
					}
				}
				//$result = array("json" => $tab);
				$file = '/sources/ressources/all_data.geojson';
				file_put_contents($file, json_encode($tab));
				header('Location: '.$file);
		}
		?>
	</body>
</html>
