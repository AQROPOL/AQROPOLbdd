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
}
