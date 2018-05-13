<?php

require 'db_access.php';

/* Script PHP pour remplir la base de donnees pour des tests */

for ($i = 1; $i < 100; $i++) {

     $aleaLat = rand(480200, 482000);
     $aleaLat = $aleaLat / 10000;
     $aleaLong = rand(-14400, -18400);
     $aleaLong = $aleaLong / 10000;
     $aleaDayRange = rand(0, 31);
     $date = date("Y-m-d");
     $aleaDate = date('Y-m-d', strtotime('-'.$aleaDayRange.' day', strtotime($date)));
     $randIdHub = rand(1,2);
     $hash = hash('sha256', $randIdHub.$date.$aleaLat.$aleaLong);
     $bool = $stmt_insertMetaMesures->execute(array(':id_hub' => $randIdHub, ':date' => $aleaDate, ':gps_long' => $aleaLong, ':gps_lat' => $aleaLat, ":hash" => $hash));

     $randIdCapteur = rand(1,4);
     $randValeur = rand(0,10000);

     $bool = $stmt_insertMesures->execute(array(':id_capteur' => $randIdCapteur, ':id_meta' => $i, ':valeur' => $randValeur));

}
 ?>
