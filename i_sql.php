<?

require_once('i_config.php');

function sql_update($requete) {
	global $dbhost, $dblogin, $dbpass, $dbname;
	$id_connexion = mysql_connect($dbhost, $dblogin, $dbpass) or die("La connexion a échoué ! ".mysql_error());
	mysql_select_db($dbname, $id_connexion) or die("La selection de la base a échoué ! ".mysql_error());
	$id_requete = mysql_query($requete, $id_connexion) or die("La requete sur la base a échoué : ".$requete);
	mysql_close($id_connexion);
}

function sql_insert($requete) {
	global $dbhost, $dblogin, $dbpass, $dbname;
	$id_connexion = mysql_connect($dbhost, $dblogin, $dbpass) or die("La connexion a échoué ! ".mysql_error());
	mysql_select_db($dbname, $id_connexion) or die("La selection de la base a échoué ! ".mysql_error());
	$id_requete = mysql_query($requete, $id_connexion) or die("La requete sur la base a échoué : ".$requete);
	$id = mysql_insert_id();
	mysql_close($id_connexion);
	return $id;
}

function sql_select($requete, &$nblignes) {
	global $dbhost, $dblogin, $dbpass, $dbname;
	$id_connexion = mysql_connect($dbhost, $dblogin, $dbpass) or die("La connexion a échoué ! ".mysql_error());
	mysql_select_db($dbname, $id_connexion) or die("La selection de la base a échoué ! ".mysql_error());
	$id_requete = mysql_query($requete, $id_connexion) or die("La requete sur la base a échoué : ".$requete);
	$nblignes = mysql_num_rows($id_requete);
	$res = array();
	for($i = 0; $i < $nblignes; $i++) {
		$res[$i] = mysql_fetch_assoc($id_requete);
	}
	mysql_close($id_connexion);
	return $res;
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
