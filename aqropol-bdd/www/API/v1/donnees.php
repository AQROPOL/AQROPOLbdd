<?php
	include bdd_donnees.php;

	if ($_SERVER['REQUEST_METHOD'] === 'POST'){
		$stmt_insert->execute(array(':idCapt' => $_POST['idCapt'], ':date' => $_POST['date'], ':mesure' => $_POST['mesure']));
	}
	else if ($_SERVER['REQUEST_METHOD'] === 'GET'){
		print "GET";
		$result = $stmt_select->execute()->fetch(PDO::FETCH_OBJ);
		print $result;
	}
?>
