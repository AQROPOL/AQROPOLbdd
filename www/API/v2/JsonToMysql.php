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
		echo ("Insertion Hubs\n");

		$row = $stmt_insertHubs->fetch(PDO::FETCH_ASSOC);
		print($row);
		print("\n")
		$id_nuc = $row["id"];
		echo "Recuperation Id nuc : \n".$id_nuc."\n";


		$mesures=$data["mesures"];
		$max = sizeof($mesures);
		echo ("taille des mesures : ".$max."\n");


	for ($i = 0; $i < $max; $i++) {

		$stmt_insertCapteurs -> bindParam(":id_hub",$id_nuc);
		$stmt_insertCapteurs -> bindParam(":name",$mesures[$i]["capteur"]);
		$stmt_insertCapteurs -> bindParam(":type",$mesures[$i]["type"]);
		$stmt_insertCapteurs -> execute();
		echo "Insertion capteurs\n";

	while ($row = $stmt_insertCapteurs ->fetch(PDO::FETCH_ASSOC)) {
		$id_capteur = $row["id"];
		echo "Recuperation Id capteur : \n".$id_capteur."\n";
		}


		$stmt_insertMetaMesures ->bindParam(":id_hub",$id_nuc);
		$stmt_insertMetaMesures ->bindParam(":date",$mesures[$i]["date"]);
		$stmt_insertMetaMesures ->bindParam(":gps_lat",$mesures[$i]["lat"]);
		$stmt_insertMetaMesures ->bindParam(":gps_long",$mesures[$i]["long"]);
		$stmt_insertMetaMesures ->bindParam(":hash",$mesures[$i]["hash"]);
		$stmt_insertMetaMesures -> execute();
		echo "Insertion Meta Mesures\n";

	 while ($row = $stmt_insertMetaMesures ->fetch(PDO::FETCH_ASSOC)) {
		$id_meta = $row["id"];
			echo "Recuperation Id meta : \n".$id_meta."\n";

		}



		$stmt_insertMesures->bindParam(":id_capteur",$id_capteur);
		$stmt_insertMesures->bindParam(":id_meta",$id_meta);
		$stmt_insertMesures->bindParam(":valeur",$mesures[$i]["valeur"]);
		$stmt_insertMesures->execute();
		echo "Insertion Mesures\n";

	//}
}
	}

?>
