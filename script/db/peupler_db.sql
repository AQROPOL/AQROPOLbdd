/* On clear la DB /*
SET foreign_key_checks = 0;
TRUNCATE TABLE mesures;
TRUNCATE TABLE meta_mesures;
TRUNCATE TABLE capteurs;
TRUNCATE TABLE hubs;
/* ALTER TABLE mesures AUTO_INCREMENT = 1;
ALTER TABLE meta_mesures AUTO_INCREMENT = 1;
ALTER TABLE capteurs AUTO_INCREMENT = 1;
ALTER TABLE hubs AUTO_INCREMENT = 1; */
SET foreign_key_checks = 1;

/* On peuple hubs */
INSERT INTO hubs(name) VALUES("centre_rennes");
INSERT INTO hubs(name) VALUES("istic");

/* On peuple capteurs */
INSERT INTO capteurs(id_hub,type) VALUES(1,"particules");
INSERT INTO capteurs(id_hub,type) VALUES(2,"particules");
INSERT INTO capteurs(id_hub,type) VALUES(1,"meteo");
INSERT INTO capteurs(id_hub,type) VALUES(2,"meteo");

/* On peuple meta_mesures */
INSERT INTO meta_mesures(id_hub,date,gps_lat,gps_long,hash) VALUES(1, 1508666400,    48.1151495,    -1.6383743000000095,     00000000000000000000000000000000);
INSERT INTO meta_mesures(id_hub,date,gps_lat,gps_long,hash) VALUES(1, 1508676039,    48.1151495,    -1.6383743000000095,     11111111111111111111111111111111);
INSERT INTO meta_mesures(id_hub,date,gps_lat,gps_long,hash) VALUES(1, 1508666400,    48.862725,     2.257592000000018,       22222222222222222222222222222222);
INSERT INTO meta_mesures(id_hub,date,gps_lat,gps_long,hash) VALUES(1, 1508676039,    48.862725,     2.257592000000018,       33333333333333333333333333333333);
INSERT INTO meta_mesures(id_hub,date,gps_lat,gps_long,hash) VALUES(1, 1521034928,    48.1151495,    -1.6383743000000095,     44444444444444444444444444444444);
INSERT INTO meta_mesures(id_hub,date,gps_lat,gps_long,hash) VALUES(1, 1521034928,    48.1151495,    -1.6383743000000095,     55555555555555555555555555555555);
INSERT INTO meta_mesures(id_hub,date,gps_lat,gps_long,hash) VALUES(1, 1521034928,    48.862725,     2.257592000000018,       66666666666666666666666666666666);
INSERT INTO meta_mesures(id_hub,date,gps_lat,gps_long,hash) VALUES(1, 1521034928,    48.862725,     2.257592000000018,       77777777777777777777777777777777);

/* On peuple mesures */
INSERT INTO mesures(id_capteur,id_meta,valeur) VALUES(1,1,156.6556);
INSERT INTO mesures(id_capteur,id_meta,valeur) VALUES(1,2,498.165);
INSERT INTO mesures(id_capteur,id_meta,valeur) VALUES(2,1,1.418);
INSERT INTO mesures(id_capteur,id_meta,valeur) VALUES(2,2,21.65);
INSERT INTO mesures(id_capteur,id_meta,valeur) VALUES(1,1,521.555);
INSERT INTO mesures(id_capteur,id_meta,valeur) VALUES(1,2,54.886);
INSERT INTO mesures(id_capteur,id_meta,valeur) VALUES(2,1,9.56);
INSERT INTO mesures(id_capteur,id_meta,valeur) VALUES(2,2,875.1);
INSERT INTO mesures(id_capteur,id_meta,valeur) VALUES(1,1,356.532);
INSERT INTO mesures(id_capteur,id_meta,valeur) VALUES(1,2,48);
INSERT INTO mesures(id_capteur,id_meta,valeur) VALUES(2,1,18);
INSERT INTO mesures(id_capteur,id_meta,valeur) VALUES(2,2,65);
INSERT INTO mesures(id_capteur,id_meta,valeur) VALUES(1,1,982.99);
INSERT INTO mesures(id_capteur,id_meta,valeur) VALUES(1,2,875);
INSERT INTO mesures(id_capteur,id_meta,valeur) VALUES(2,1,23.718);
INSERT INTO mesures(id_capteur,id_meta,valeur) VALUES(2,2,911);
INSERT INTO mesures(id_capteur,id_meta,valeur) VALUES(1,1,666.556);
INSERT INTO mesures(id_capteur,id_meta,valeur) VALUES(1,2,463.1);
INSERT INTO mesures(id_capteur,id_meta,valeur) VALUES(2,1,141.8);
INSERT INTO mesures(id_capteur,id_meta,valeur) VALUES(2,2,21.587);
INSERT INTO mesures(id_capteur,id_meta,valeur) VALUES(1,1,689.650);
INSERT INTO mesures(id_capteur,id_meta,valeur) VALUES(1,2,850.365);
INSERT INTO mesures(id_capteur,id_meta,valeur) VALUES(2,1,757.498);
INSERT INTO mesures(id_capteur,id_meta,valeur) VALUES(2,2,756.6);
