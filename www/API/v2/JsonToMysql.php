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
		//echo ("Insertion Hubs\n");
 		//$lastId = $db->lastInsertId();
		//print($lastId);
		//$last_id = $stmt_insertHubs >PDOStatement::fetchAll('SELECT LAST_INSERT_ID() as last_id');

	//	$last_id = intval($last_id[0]['last_id']);
		//print($last_id);
		$sth = $db->prepare('SELECT Max(id) as "max" FROM hubs');
		$sth->execute();
			print" sth 1 \n";
		//print $sth["max"];
		print" sth 2 \n";
		print($sth);
		$res = $db->query('SELECT Max(id) as "max" FROM hubs');
		print" Res 1 \n";
		print($res);
		print" Res 2 \n";
		print $res["max"];
		/*
		$id_nuc = $db->exec("SELECT Max(id) FROM hubs");
		echo "Recuperation1 \n";
		print($id_nuc);

		foreach  ($db->exec($sql) as $row) {
			echo "Recuperation2 \n";
			 print $row['max'] . "\t";
		 }

//		$result = $stmt_insertHubs->fetch();
	  //$id_nuc = $result[0];
		//echo "Recuperation Id nuc : \n".$id_nuc."\n";

		//echo "Recuperation \n";
		//print($result[0]);
		//while($row = $stmt_insertHubs->fetch(PDO::FETCH_ASSOC)){
			//print_r($row);
			//print("\n");
			//$id_nuc = $row["id"];
			//echo "Recuperation Id nuc : \n".$id_nuc."\n";
	//	}
*/
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
