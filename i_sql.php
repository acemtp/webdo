<?

require_once('i_config.php');

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
		sql_run_migrations($pdo);
	} catch (PDOException $e) {
		die("La connexion a échoué ! ".$e->getMessage());
	}

	return $pdo;
}

function sql_run_migrations($pdo) {
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

	return preg_replace(array_keys($patterns), array_values($patterns), $requete);
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
