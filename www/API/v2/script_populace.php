<?php

require 'db_access.php';

for ($i = 1; $i < 1000; $i++) {
     $aleaLat = rand(4802, 4820);
     $aleaLat = $aleaLat / 100;
     $aleaLong = rand(-144, -184);
     $aleaLong = $aleaLong / 100;

     //$date = new DateTime();
     //$dateTmp = $date->getTimestamp();
     $dateTmp = round(microtime(true) * 1000);
     $randIdHub = rand(1,2);
     $hash = hash('sha256', $dateTmp);
     $bool = $stmt_insertMetaMesures->execute(array(':id_hub' => $randIdHub, ':date' => $dateTmp, ':gps_long' => $aleaLong, ':gps_lat' => $aleaLat, ":hash" => $hash));
     
     if ($bool === true) echo "Success\n";
     else if ($bool === false) {
          echo "<br>PDO::errorInfo():";
          print_r($db->errorInfo());
     }


     $randIdCapteur = rand(1,4);
     $randValeur = rand(0,10000);

     $bool = $stmt_insertMesures->execute(array(':id_capteur' => $randIdCapteur, ':id_meta' => $i, ':valeur' => $randValeur));
     if ($bool === true) echo "Success\n";
     else if ($bool === false) {
          echo "<br>PDO::errorInfo():";
          print_r($db->errorInfo());
     }
}
 ?>
