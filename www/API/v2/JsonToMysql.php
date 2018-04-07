<?php
	require "db_access.php";
//Mon URL http://pilic27.irisa.fr/API/v2/JsonToMySql.php

	header("Content-type: application/json");
echo "POST Request\n";
	if ($_SERVER["REQUEST_METHOD"] == "POST"){
echo("POST Recu !\n");
		$json_data = file_get_contents("json_data_test.json");
//if(!empty($_POST["file"])){
echo("Test Recu !\n");
		$data=json_decode($json_data,true);

		$id_nuc;
		$id_capteur;
		$id_meta;
		$nuc = $data["nuc"];
		$stmt_insertHubs -> bindParam(":name",$nuc);
		$stmt_insertHubs -> execute();
/*
		$hub = $db->prepare('SELECT Max(id) as "max" FROM hubs');
		$hub->execute();
		$result = $hub->fetchColumn();
		$id_nuc=$result;
		print("Id nuc = $result et $id_nuc\n");
*/
		$result = $stmt_insertHubs->fetchColumn();
		$id_nuc=$result;
		print("Id nuc = $result et $id_nuc\n");

		$mesures=$data["mesures"];
		$max = sizeof($mesures);
		echo ("taille des mesures : ".$max."\n");


	for ($i = 0; $i < $max; $i++) {

		$stmt_insertCapteurs -> bindParam(":id_hub",$id_nuc);
		$stmt_insertCapteurs -> bindParam(":name",$mesures[$i]["capteur"]);
		$stmt_insertCapteurs -> bindParam(":type",$mesures[$i]["type"]);
		$stmt_insertCapteurs -> execute();
		echo "Insertion capteurs\n";
		/*
		$capt = $db->prepare('SELECT Max(id) as "max" FROM capteurs');
		$capt->execute();
		$result_capt = $capt->fetchColumn();
		$id_capteur = $result_capt;
		print("Id capteur = $id_capteur\n");
		*/
		$result_capt =$stmt_insertCapteurs->fetchColumn();
		$id_capteur = $result_capt;
		print("Id capteur = $id_capteur\n");

		$stmt_insertMetaMesures ->bindParam(":id_hub",$id_nuc);
		$stmt_insertMetaMesures ->bindParam(":date",$mesures[$i]["date"]);
		$stmt_insertMetaMesures ->bindParam(":gps_lat",$mesures[$i]["lat"]);
		$stmt_insertMetaMesures ->bindParam(":gps_long",$mesures[$i]["long"]);
		$stmt_insertMetaMesures ->bindParam(":hash",$mesures[$i]["hash"]);
		$stmt_insertMetaMesures -> execute();
		echo "Insertion Meta Mesures\n";
		/*$meta = $db->prepare('SELECT Max(id) as "max" FROM meta_mesures');
		$meta->execute();
		$result_meta = $meta->fetchColumn();
		$id_meta = $result_meta;
		print("Id Meta = $id_meta\n");
		*/
		$result_meta = $stmt_insertMetaMesures->fetchColumn();
		$id_meta = $result_meta;
		print("Id Meta = $id_meta\n");


		$stmt_insertMesures->bindParam(":id_capteur",$id_capteur);
		$stmt_insertMesures->bindParam(":id_meta",$id_meta);
		$stmt_insertMesures->bindParam(":valeur",$mesures[$i]["valeur"]);
		$stmt_insertMesures->execute();
		echo "Insertion Mesures\n";

	//}
}
	}

?>
