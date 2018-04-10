<?php
	require "db_access.php";
	header("Content-type: application/json");
	echo "POST Request\n";
	if ($_SERVER["REQUEST_METHOD"] == "POST"){
			echo("POST Recu !\n");
			if(!empty($_POST["file"])){
				echo("Test Recu !\n");
				//$data=json_decode($json_data,true);
				$data=json_decode($_POST["file"],true);
				$id_nuc;
				$id_capteur;
				$id_meta;
				$nuc = $data["nuc"];
				//Insertion Hubs
				$stmt_insertHubs -> bindParam(":name",$nuc);
				$stmt_insertHubs -> execute();
				// Recuperer Id nuc
				$hub = $db->prepare('SELECT id FROM hubs where name = '.$nuc);
				$hub->execute();
				$id_nuc = $hub->fetchColumn();
				print("Id nuc = $id_nuc\n");
				//Tableau de Mesures
				$mesures=$data["mesures"];
				$max = sizeof($mesures);
				echo ("taille des mesures : ".$max."\n");

				//Insertion capteurs
				for ($i = 0; $i < $max; $i++) {
					$stmt_insertCapteurs -> bindParam(":id_hub",$id_nuc);
					$stmt_insertCapteurs -> bindParam(":name",$mesures[$i]["capteur"]);
					$stmt_insertCapteurs -> bindParam(":type",$mesures[$i]["type"]);
					$stmt_insertCapteurs -> execute();
					// Recuperer Id Capteur
					$capt = $db->prepare('SELECT Max(id) as "max" FROM capteurs');
					$capt->execute();
					$id_capteur = $capt->fetchColumn();
					print("Id capteur = $id_capteur\n");

					//Insertion Meta Mesures
					$stmt_insertMetaMesures ->bindParam(":id_hub",$id_nuc);
					$stmt_insertMetaMesures ->bindParam(":date",$mesures[$i]["date"]);
					$stmt_insertMetaMesures ->bindParam(":gps_lat",$mesures[$i]["lat"]);
					$stmt_insertMetaMesures ->bindParam(":gps_long",$mesures[$i]["long"]);
					$stmt_insertMetaMesures ->bindParam(":hash",$mesures[$i]["hash"]);
					$stmt_insertMetaMesures -> execute();
					//Recuperer Id Meta_Mesures
					$meta = $db->prepare('SELECT Max(id) as "max" FROM meta_mesures');
					$meta->execute();
					$id_meta = $meta->fetchColumn();
					print("Id Meta = $id_meta\n");
					//Insertion Mesures
					$stmt_insertMesures->bindParam(":id_capteur",$id_capteur);
					$stmt_insertMesures->bindParam(":id_meta",$id_meta);
					$stmt_insertMesures->bindParam(":valeur",$mesures[$i]["valeur"]);
					$stmt_insertMesures->execute();
				}
			}
				else {
					echo " POST File Empty !";
				}
		}
		else {
			if ($_SERVER["REQUEST_METHOD"] == "GET"){
				echo "GET Request \n";
				$hash_capt = $db->prepare('SELECT capteurs.id, meta_mesures.hash
																		FROM capteurs,meta_mesures,mesures
																		where capteurs.id = mesures.id_capteur
																		and meta_mesures.id = mesures.id_meta');
				$hash_capt->execute();
				//$id_meta = $meta->fetchColumn();
				$tabHash = $hash_capt->fetchAll(PDO::FETCH_ASSOC);
				$tabCapt_Hash = array();
				foreach ($tabHash as &$ligneHash) {
					$tabCapt_Hash['id_capt']=$ligneHash['id'];
					$tabCapt_Hash['hash']=$ligneHash['hash'];
			}
				echo json_encode($tabCapt_Hash, JSON_NUMERIC_CHECK);
		}

?>
