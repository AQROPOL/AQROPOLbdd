<?php
	require 'db_access.php';
//Mon URL http://pilic27.irisa.fr/API/v2/JsonToMySql.php

	header("Content-type: application/json");

	if ($_SERVER['REQUEST_METHOD'] === 'POST'){

		//$json_data = file_get_contents('data.json');
if(!empty($_POST['test'])){
echo("Test Recu !");
		$data=json_decode($_POST['test'],true);

		$id_nuc=0;
		$id_capteur=0;
		$id_meta=0;
		$nuc = $data[nuc];
		$stmt_insertHubs -> bindParam(':name',$nuc);
		$stmt_insertHubs -> execute();
		echo ("Insertion Hubs");

	while ($row = $stmt_insertHubs->fetch(PDO::FETCH_ASSOC)) {
		$id_nuc = $row['id'];
		echo "Recuperation Id nuc";
		}

		$mesures=$data["mesures"];
		$max = sizeof($mesures);


	for ($i = 0; $i < $max; $i++) {

		$stmt_insertCapteurs -> bindParam(':id_hub',$id_nuc);
		$stmt_insertCapteurs -> bindParam(':name',$mesures[i]["capteur"]);
		$stmt_insertCapteurs -> bindParam(':type',$mesures[i]["type"]);
		$stmt_insertCapteurs -> execute();
		echo "Insertion capteurs";

	while ($row = $stmt_insertCapteurs ->fetch(PDO::FETCH_ASSOC)) {
		$id_capteur = $row['id'];
		}

		$stmt_insertMetaMesures ->bindParam(':id_hub',$id_nuc);
		$stmt_insertMetaMesures ->bindParam(':date',$mesures[i]["date"]);
		$stmt_insertMetaMesures ->bindParam(':gps_lat',$mesures[i]["lat"]);
		$stmt_insertMetaMesures ->bindParam(':gps_long',$mesures[i]["long"]);
		$stmt_insertMetaMesures ->bindParam(':hash',$mesures[i]["hash"]);
		$stmt_insertMetaMesures -> execute();
		echo "Insertion Meta Mesures";

	 while ($row = $stmt_insertMetaMesures ->fetch(PDO::FETCH_ASSOC)) {
		$id_meta = $row['id'];

		}



		$stmt_insertMesures->bindParam(':id_capteur',$id_capteur);
		$stmt_insertMesures->bindParam(':id_meta',$id_meta);
		$stmt_insertMesures->bindParam(':valeur',$mesures[i]["valeur"]);
		$stmt_insertMesures->execute();
		echo "Insertion Mesures";

	}
}
	}

?>
