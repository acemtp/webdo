<?

require_once(__DIR__.'/i_config.php');

function sql_bootstrap_schema() {
	return <<<SQL
CREATE TABLE IF NOT EXISTS commentaire (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  idKdo INTEGER NOT NULL DEFAULT 0,
  commentaire TEXT NOT NULL,
  creeLe TEXT NOT NULL DEFAULT '',
  creePar INTEGER NOT NULL DEFAULT 0,
  visible INTEGER NOT NULL DEFAULT 0,
  supprime INTEGER NOT NULL DEFAULT 0
);

CREATE TABLE IF NOT EXISTS evenement (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  date TEXT NOT NULL DEFAULT '',
  titre TEXT NOT NULL DEFAULT '',
  description TEXT,
  groupe INTEGER NOT NULL DEFAULT 0,
  supprime INTEGER NOT NULL DEFAULT 0
);

CREATE TABLE IF NOT EXISTS groupe (
  id INTEGER NOT NULL,
  idMembre INTEGER NOT NULL DEFAULT 0,
  nom TEXT DEFAULT NULL,
  PRIMARY KEY (id, idMembre)
);

CREATE TABLE IF NOT EXISTS kdo (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  pour INTEGER NOT NULL DEFAULT 0,
  titre TEXT NOT NULL DEFAULT '',
  description TEXT,
  url TEXT DEFAULT NULL,
  image TEXT DEFAULT NULL,
  creeLe TEXT NOT NULL DEFAULT '',
  creePar INTEGER NOT NULL DEFAULT 0,
  reserveLe TEXT DEFAULT NULL,
  reservePar INTEGER DEFAULT NULL,
  acheteLe TEXT DEFAULT NULL,
  achetePar INTEGER DEFAULT NULL,
  partage INTEGER NOT NULL DEFAULT 0,
  supprime INTEGER NOT NULL DEFAULT 0,
  priorite INTEGER NOT NULL DEFAULT 3
);

CREATE TABLE IF NOT EXISTS membre (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  motPasse TEXT NOT NULL DEFAULT '',
  prenom TEXT DEFAULT NULL,
  anniversaire TEXT DEFAULT NULL,
  presentation TEXT,
  email TEXT DEFAULT NULL,
  photo TEXT DEFAULT NULL,
  aime TEXT NOT NULL DEFAULT '',
  aimepas TEXT NOT NULL DEFAULT ''
);

CREATE TABLE IF NOT EXISTS partage (
  idKdo INTEGER DEFAULT 0,
  partagePar INTEGER NOT NULL DEFAULT 0,
  combien TEXT DEFAULT NULL,
  supprime INTEGER DEFAULT 0
);
SQL;
}

function sql_get_connection() {
	static $pdo = null;

	if ($pdo !== null) {
		return $pdo;
	}

	global $dbpath;

	if (!isset($dbpath) || $dbpath === '') {
		die("La configuration SQLite est invalide !");
	}

	$dbdir = dirname($dbpath);
	if (!is_dir($dbdir) && !mkdir($dbdir, 0777, true) && !is_dir($dbdir)) {
		die("Impossible de créer le dossier de la base SQLite !");
	}

	try {
		$pdo = new PDO('sqlite:'.$dbpath);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		$pdo->exec('PRAGMA foreign_keys = ON');
		$pdo->exec(sql_bootstrap_schema());
		sql_run_migrations($pdo);
	} catch (PDOException $e) {
		die("La connexion a échoué ! ".$e->getMessage());
	}

	return $pdo;
}

function sql_run_migrations($pdo) {
	$pdo->exec("
		INSERT INTO membre (prenom, motPasse, aime, aimepas)
		SELECT 'vianney', 'ploki', '', ''
		WHERE NOT EXISTS (
		  SELECT 1 FROM membre WHERE prenom = 'vianney'
		)
	");

	$columns = $pdo->query("PRAGMA table_info(membre)")->fetchAll();
	$hasAdmin = false;

	foreach($columns as $column) {
		if(isset($column['name']) && $column['name'] == 'admin') {
			$hasAdmin = true;
			break;
		}
	}

	if(!$hasAdmin) {
		$pdo->exec("ALTER TABLE membre ADD COLUMN admin INTEGER NOT NULL DEFAULT 0");
	}

	$pdo->exec("UPDATE membre SET admin = 1 WHERE id = 1");
}

function sql_normalize_query($requete) {
	$patterns = array(
		'/SUBDATE\(NOW\(\), "([0-9]+) days"\)/i' => "datetime('now', '-$1 days')",
		'/SUBDATE\(NOW\(\), \'([0-9]+) days\'\)/i' => "datetime('now', '-$1 days')",
	);

	$requete = preg_replace(array_keys($patterns), array_values($patterns), $requete);
	$requete = str_replace("\\'", "''", $requete);
	$requete = str_replace('\\"', '""', $requete);
	return $requete;
}

function sql_update($requete) {
	$pdo = sql_get_connection();
	$requete = sql_normalize_query($requete);

	try {
		$pdo->exec($requete);
	} catch (PDOException $e) {
		die("La requete sur la base a échoué : ".$requete." (".$e->getMessage().")");
	}
}

function sql_insert($requete) {
	$pdo = sql_get_connection();
	$requete = sql_normalize_query($requete);

	try {
		$pdo->exec($requete);
		return intval($pdo->lastInsertId());
	} catch (PDOException $e) {
		die("La requete sur la base a échoué : ".$requete." (".$e->getMessage().")");
	}
}

function sql_select($requete, &$nblignes) {
	$pdo = sql_get_connection();
	$requete = sql_normalize_query($requete);

	try {
		$statement = $pdo->query($requete);
		$res = $statement->fetchAll();
		$nblignes = count($res);
		return $res;
	} catch (PDOException $e) {
		die("La requete sur la base a échoué : ".$requete." (".$e->getMessage().")");
	}
}

function sql_escape($value) {
	$pdo = sql_get_connection();
	return substr($pdo->quote($value), 1, -1);
}

// retourne le prenom d'un id utilisateur
function prenom_simple($id) {
	$res = sql_select("select prenom from membre where id=$id", $nb);
	if($nb != 1) return "Inconnu$id";
	return $res[0]['prenom'];
}

// retourne le prenom d'un id utilisateur avec l'url pour avoir son profil
function prenom($id) {
	$pr = prenom_simple($id);
//	if(strchr($pr, "Inconnu") === FALSE)
//		return "<a href=\"profil.php?id=$id\" target=\"_blank\">".$pr."</a>";
//	else
		return $pr;
}

// retourne le nom de la photo d'un id utilisateur
function photo($id) {
	$res = sql_select("select photo from membre where id=$id", $nb);
	if($nb != 1) return "";
	return $res[0]['photo'];
}

?>
