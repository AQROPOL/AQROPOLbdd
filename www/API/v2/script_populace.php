<?php

require 'db_access.php';

for ($i = 0; $i < 1000; $i++) {
     $aleaLat = rand(4802, 4820);
     $aleaLat = $aleaLat / 100;
     $aleaLong = rand(-144, -184);
     $aleaLong = $aleaLong / 100;

     echo "lat = ". $aleaLat . " long = " . $aleaLong;
     $date = new DateTime();
     $dateTmp = $date->getTimestamp();
     $rand2 = rand(1,2);
     $stmt_insertMetaMesures->execute(array(':id_hub' => $rand2, ':date' => $dateTmp, ':gps_long' => $aleaLong, ':gps_lat' => $aleaLat));
}
 ?>
