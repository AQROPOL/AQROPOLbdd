/*
 * On drop toutes les tables 
 */
DROP TABLE IF EXISTS mesures,meta_mesures,capteurs,hubs;

/* 
 * Table des hubs
 * Il s'agit de la liste des supports sur lesquels sont branchés les capteurs. Il s'agit de ce qui déclenche les capteurs, stocke les valeurs et les communiquent aux téléphones mobiles qui s'y connectent.
 * Cette table est actuellement relativement vide, mais elle est nécessaire pour savoir à quel hub faire remonter l'information quand la base de donnée valide la réception des données et en autorise la suppression.
 */
CREATE TABLE hubs (
id INT AUTO_INCREMENT,
name VARCHAR(255) UNIQUE NOT NULL,
PRIMARY KEY (id)
);

/* 
 * Table des capteurs
 * Permet de déterminer le type des mesures effectuées et d'en connaître la provenance.
 */
CREATE TABLE capteurs (
id INT AUTO_INCREMENT,
id_hub INT NOT NULL,
name VARCHAR(255) NOT NULL,
type VARCHAR(255) NOT NULL,
PRIMARY KEY (id)
);

/*
 * Table des informations sur les meures
 * Il s'agit de la table contenant l'ensemble des données coimmunes à un jeu de mesures provenant d'un même hub et prises à un même moment.
 * Les coordonnées GPS sont inscrites ici mais peuvent aussi être placées dans le hub si on considère que celui-ci est fixe, ou être traitées comme n'importe quel autre capteur et placées dans mesures.
 */
CREATE TABLE meta_mesures (
id INT AUTO_INCREMENT,
id_hub INT NOT NULL,
date TIMESTAMP NOT NULL,
-- coordonnées GPS, remplaçables par un unique champ de texte
gps_lat FLOAT NOT NULL, -- latitude
gps_long FLOAT NOT NULL, -- longitude
hash BINARY(32) NOT NULL,
PRIMARY KEY (id)
);

/*
 * Table des mesures
 * Contient l'ensemble des mesures, stockées dans un BLOB, un type de donnée non formaté.
 * Ainsi pas besoin de créer de nouvelles tables si on ajoute un nouveau type de capteur. On a pour l'instant des nombres à virgule flottante, mais on peut très bien ajouter une capture d'image ou un autre type de donnée non numérique.
 */
CREATE TABLE mesures (
id INT AUTO_INCREMENT,
id_capteur INT NOT NULL, -- référence une entrée de la table "capteurs"
id_meta INT NOT NULL,
valeur BLOB NOT NULL, -- donnée non typée
PRIMARY KEY (id)
);

/* On ajoute toutes les contraintes */
ALTER TABLE capteurs ADD CONSTRAINT fk_capteur_hub FOREIGN KEY (id_hub) REFERENCES hubs(id)
ON DELETE CASCADE; -- Si on supprime un hub on supprime les capteurs associés

ALTER TABLE meta_mesures ADD CONSTRAINT fk_meta_mesure_hub FOREIGN KEY (id_hub) REFERENCES hubs(id)
ON DELETE CASCADE; -- Si on supprime un hub on supprime les mesures associées (cascade sur les mesures elles-mêmes avec les clés suivantes)

ALTER TABLE mesures ADD CONSTRAINT fk_mesure_capteur FOREIGN KEY (id_capteur) REFERENCES capteurs(id)
ON DELETE CASCADE; -- Si on supprime un capteur on supprime les mesures associées (une mesure ne peut pas exister sans le capteur car celle-ci n'aurait plus de type)

ALTER TABLE mesures ADD CONSTRAINT fk_mesure_meta FOREIGN KEY (id_meta) REFERENCES meta_mesures(id)
ON DELETE CASCADE; -- Si on supprime un ensemble de mesures on supprime les mesures associées

ALTER TABLE meta_mesures ADD CONSTRAINT uq_hash_meta UNIQUE (hash,id_hub); -- Contrainte d'unicité sur le couple hash/id_hub

ALTER TABLE capteurs ADD CONSTRAINT uq_hub_capteur_name UNIQUE (id_hub,name); -- Contrainte d'unicité sur le couple id_hub/name
