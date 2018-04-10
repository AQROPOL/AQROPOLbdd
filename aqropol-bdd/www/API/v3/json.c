class requests
{
	protected $dbh;
	protected static $instance;

	private function __construct()
    	{
        	try {
			$dsn = 'mysql:=' . DB_Config::read('db.host') . ';dbname='  . DB_Config::read('db.name');
			$username = DB_Config::read('db.username');
			$password = DB_Config::read('db.password');
			$this->dbh = new PDO($dsn, $username, $password);
			$this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage();
			die();
		}
	}

	public static function getInstance()
	{
		if (!isset(self::$instance)) {
			$object = __CLASS__;
			self::$instance = new $object;
		}
		return self::$instance;
	}

	public static function readMesure()
	{
		$dbi = self::getInstance();

		try {
			$stmt_readMesure = $dbi->dbh->prepare('SELECT m.id,m.id_capteur,m.id_meta,m.valeur,c.type FROM mesures m,capteurs c WHERE m.id_capteur = c.id;');

		return $stmt_readMesure->execute();
		}
		catch (PDOException $e) {
			print "Error readMesure! :" . $e->getMessage();
			return '';
		}
	}

	public static function readMeta()
	{
		$dbi = self::getInstance();

                try {
			$stmt_readMeta = $dbi->dbh->prepare('SELECT id,id_hub,date,gps_lat,gps_long FROM meta_mesures;');

		return $stmt_readMeta->execute();
		}
		catch (PDOException $e) {
			print "Error readMeta! :" . $e->getMessage();
			return '';
		}
	}

	public static function readCapteur()
        {
                $dbi = self::getInstance();

                try {
                        $stmt_readCapteur = $dbi->dbh->prepare('SELECT id,id_hub,type FROM capteurs;');

                return $stmt_readCapteur->execute();
                }
                catch (PDOException $e) {
                        print "Error readCapteur! :" . $e->getMessage();
                        return '';
                }
	}

	public static function readHub()
        {
                $dbi = self::getInstance();

                try {
                        $stmt_readHub = $dbi->dbh->prepare('SELECT id,name FROM hubs;');

                return $stmt_readHub->execute();
                }
                catch (PDOException $e) {
                        print "Error readHub! :" . $e->getMessage();
                        return '';
                }
        }
        
        public static function insertMesures($id, $id_capteur, $id_meta, $valeur)
   		 {
        	$dbi = self::getInstance();

        	try {
       		$stmt_insertMesures = 
       		$dbi->dbh->prepare('INSERT INTO mesures (id, id_capteur, id_meta, valeur)
           	 					VALUES (:id, :id_capteur, :id_meta, :mesure);'
           	 					);
       
            $stmt_insertMesures ->bindParam(':id', $id);
            $stmt_insertMesures ->bindParam(':id_capteur', $id_capteur);
            $stmt_insertMesures ->bindParam(':id_meta', $id_meta);
            $stmt_insertMesures ->bindParam(':valeur', $mesure);

            return $stmt_insertMesures->execute();

        } catch (PDOException $e) {
            print "Error insertMesures " . $e->getMessage() ;
            return '';
        }
    }
    
    
          public static function insertMetaMesures ($id, $id_hub, $date, $gps_long, $gps_lat)
   		 {
        	$dbi = self::getInstance();

        	try {
       		$stmt_insertMetaMesures  = 
       		$dbi->dbh->prepare('INSERT INTO meta_mesures (id, id_hub, date, gps_long, gps_lat)
       							VALUES (:id, :id_hub, :date, :gps_long, :gps_lat);'
       							);
       
            $stmt_insertMetaMesures  ->bindParam(':id', $id);
            $stmt_insertMetaMesures  ->bindParam(':id_hub', $id_hub);
            $stmt_insertMetaMesures  ->bindParam(':date', date('Y-m-d G:I:S',$date);
            $stmt_insertMetaMesures  ->bindParam(':gps_long', $gps_long);
            $stmt_insertMetaMesures  ->bindParam(':gps_lat', $gps_lat);

            return  $stmt_insertMetaMesures->execute();

        } catch (PDOException $e) {
            print "Error insertMetaMesures " . $e->getMessage() ;
            return '';
        }
    }
    
         public static function insertCapteurs($id, $id_hub, $type)
   		 {
        	$dbi = self::getInstance();

        	try {
       		$stmt_insertCapteurs  = 
       		$dbi->dbh->prepare('INSERT INTO capteurs (id, id_hub, type) 
       							VALUES(:id, :id_hub, :type);'
       							);
       
            $stmt_insertCapteurs  ->bindParam(':id', $id);
            $stmt_insertCapteurs  ->bindParam(':id_hub', $id_hub);
            $stmt_insertCapteurs  ->bindParam(':$type', $type);

            return  $stmt_insertCapteurs->execute();

        } catch (PDOException $e) {
            print "Error insertCapteurs " . $e->getMessage() ;
            return '';
        }
    }
    
    
    public static function insertHubs($name)
   		 {
        	$dbi = self::getInstance();

        	try {
       		$stmt_insertHubs  = 
       		$dbi->dbh->prepare('INSERT INTO hubs (name) VALUES (:name);');
       
            $stmt_insertHubs  ->bindParam(':name', $name);

            return  $stmt_insertHubs->execute();

        } catch (PDOException $e) {
            print "Error insertHubs " . $e->getMessage() ;
            return '';
        }
    }
    
    
    
    
        
}
