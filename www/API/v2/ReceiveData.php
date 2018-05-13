<?php
	require "db_access.php";
	header("Content-type: application/json");

	if ($_SERVER["REQUEST_METHOD"] == "POST"){
		/* Gestion de la requete d'insertion des donnees venant d'Android
		 * Parser la variable du POST
		 * Utilisation du format JSON
		*/
		if (isset($_POST["file"])) {
			if(!empty($_POST["file"])){
				//Decode Json Android
				$data=json_decode($_POST["file"],true);
				$id_nuc;
				$id_capteur;
				$id_meta;
					//Tableau de Mesures
				$mesures=$data["_embedded"]["measures"];
				$Size_Mesures = sizeof($mesures);
				$b=1000;

				for($j=0 ; $j < $Size_Mesures/$b  ; $j++){
				for ($i = $j*$b; $i < $b*$j+$b; $i++) {

					//Insertion Hubs
					$nuc = $mesures[$i]["nuc"]["token"];
					$stmt_insertHubs -> bindParam(":name",$nuc);
					$stmt_insertHubs -> execute();

					// Recuperer Id nuc
					$hub = $db->prepare('SELECT id FROM hubs where name = "'.$nuc.'"');
					$hub->execute();
					$id_nuc = $hub->fetchColumn();

					//Insertion capteurs
					$stmt_insertCapteurs -> bindParam(":id_hub",$id_nuc);
					$stmt_insertCapteurs -> bindParam(":name",$mesures[$i]["sensor"]["name"]);
					$stmt_insertCapteurs -> bindParam(":type",$mesures[$i]["sensor"]["type"]);
					$stmt_insertCapteurs -> execute();

					// Recuperer Id Capteur
					$capt = $db->prepare('SELECT Max(id) as "max" FROM capteurs');
					$capt->execute();
					$id_capteur = $capt->fetchColumn();

					//Insertion Meta Mesures
					$stmt_insertMetaMesures ->bindParam(":id_hub",$id_nuc);
					$stmt_insertMetaMesures ->bindParam(":date",$mesures[$i]["timestamp"]);
					// Cord GPS : Information non requise actuellement
					$c=0;
					$stmt_insertMetaMesures ->bindParam(":gps_lat",$c);
					$stmt_insertMetaMesures ->bindParam(":gps_long",$c);
					$stmt_insertMetaMesures ->bindParam(":hash",$mesures[$i]["hash"]);
					$stmt_insertMetaMesures -> execute();

					//Recuperer Id Meta_Mesures
					$meta = $db->prepare('SELECT Max(id) as "max" FROM meta_mesures');
					$meta->execute();
					$id_meta = $meta->fetchColumn();

					//Insertion Mesures
					$stmt_insertMesures->bindParam(":id_capteur",$id_capteur);
					$stmt_insertMesures->bindParam(":id_meta",$id_meta);
					$stmt_insertMesures->bindParam(":valeur",$mesures[$i]["value"]);
					$stmt_insertMesures->execute();
					}
				}
				}
				else {
					echo " POST File Empty !";
				}
			}

		}
		else {
			/* Recuperation des derniers hash ainsi que leurs Id et les envoye vers Android
			*/
			if ($_SERVER["REQUEST_METHOD"] == "GET"){
				// Encode Json Dernier Hash/Capt
				$hash_capt = $db->prepare('SELECT hubs.id,meta_mesures.hash,Max(date)
																	from meta_mesures, hubs, mesures
																	where hubs.id = meta_mesures.id_hub
																	and meta_mesures.id=mesures.id_meta
																	group by hubs.id');
				$hash_capt->execute();
				$tabHash = $hash_capt->fetchAll();
				$tabCapt_Hash = array();
				$i=0;
				foreach ($tabHash as &$ligneHash) {
					$tabCapt_Hash[$i]['id_hub']=$ligneHash['id'];
					$tabCapt_Hash[$i]['hash']=$ligneHash['hash'];
					$i++;
					}
				echo json_encode($tabCapt_Hash, JSON_NUMERIC_CHECK);
			}
		}
?>
