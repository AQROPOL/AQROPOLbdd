<html>
	<head>
		<meta charset="UTF-8">
	</head>
	<body>
	<?php
		require 'db_access.php';

		echo "Type de requête : ".$_SERVER['REQUEST_METHOD']."<br />\n";

		/*if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			if (isset($_POST['insert'])) {
				if ($_POST['insert'] == 'mesures') {
					echo "POST bien reçu pour insertMesures.<br />\n";
					if (isset($_POST['id_capteur'], $_POST['id_meta'], $_POST['valeur'], $_POST['hash'])) {
						if ($stmt_insertMesures->execute(array(':id_capteur' => $_POST['id_capteur'], ':id_meta' => $_POST['id_meta'], ':valeur' => $_POST['valeur'])))
							echo "Insertion dans la base réussie.<br />\n";
						else
							echo "Insertion échouée.<br />\n";
					} else {
						echo "Erreur : 'id', 'idcapteur', 'id_meta' et 'valeur' nécessaires.<br />\n";
					}
				} else if ($_POST['insert'] == 'meta_mesures') {
					echo "POST bien reçu pour insertMetaMesures.<br />\n";
					if (isset($_POST['id'], $_POST['id_hub'], $_POST['date'], $_POST['gps_lat'], $_POST['gps_long'])) {
						if ($stmt_insertMetaMesures->execute(array('id' => $_POST['id'], ':id_hub' => $_POST['id_hub'], ':date' => $_POST['date'], ':gps_lat' => $_POST['gps_lat'], ':gps_long' => $_POST['gps_long'])))
							echo "Insertion dans la base réussie.<br />\n";
						else
							echo "Insertion échouée.<br />\n";
					} else {
						echo "Erreur : 'id', 'id_hub', 'date', 'gps_lat' et 'gps_long' nécessaires.<br />\n";
					}
				} else if ($_POST['insert'] == 'capteurs') {
					echo "POST bien reçu pour insertCapteurs.<br />\n";
					if (isset($_POST['id'], $_POST['id_hub'], $_POST['type'])) {
						if ($stmt_insertCapteurs->execute(array('id' => $_POST['id'], ':id_hub' => $_POST['id_hub'], ':type' => $_POST['type'])))
							echo "Insertion dans la base réussie.<br />\n";
						else
							echo "Insertion échouée.<br />\n";
					} else {
						echo "Erreur : 'id', 'id_hub' et 'type' nécessaires.<br />\n";
					}
				} else if ($_POST['insert'] == 'hubs') {
					echo "POST bien reçu pour insertHubs.<br />\n";
					if (isset($_POST['name'])) {
						if ($stmt_insertHubs->execute(array('name' => $_POST['name'])))
							echo "Insertion dans la base réussie.<br />\n";
						else
							echo "Insertion échouée.<br />\n";
					} else {
						echo "Erreur : 'name' nécessaire.<br />\n";
					}
				}
			}
		}
		else *//*if ($_SERVER['REQUEST_METHOD'] === 'GET') {
			echo "GET bien reçu.<br />\n";
			if ($stmt_read->execute()) {?>
		<table>
			<tr>
				<th>ID</th>
				<th>ID Capteur</th>
				<th>Mesure</th>
				<th>Date</th>
			</tr>
			<?php 
				while ($row = $stmt_read->fetch(PDO::FETCH_BOTH)) {
					echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[3]."</td></tr>";
				}
			?>
		</table>
		<?php	}
			else
				echo "Sélection échouée.<br />\n";
		}*/
		if ($_SERVER['REQUEST_METHOD'] === 'GET'){
			/*if($_GET['mesure']){*/
				$meta_mesures = array();
				$stmt_readMesure->execute();
				$tabMesure = $stmt_readMesure->fetchAll(PDO::FETCH_ASSOC);
				$stmt_readMeta->execute();
				$tabMeta = $stmt_readMeta->fetchAll(PDO::FETCH_ASSOC);
				$i = 0;
				//echo($tabMesure[0]);
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
			/*}*/
		}




		?>


	</body>
</html>
