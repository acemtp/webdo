<?
/** 
 * Identification de l'utilisateur
 *		r�initialisation de la session  
 *		redirection automatique vers la page d'accueil
 *
 * @param login
 * 				pr�nom
 * @param pwd
 * 				mot de passe
 */

require_once('i_divers.php');

session_cache_expire(60*60*24*30);
session_start();
session_unset();
$ok=true;

$login = get_param('login');
$pwd = get_param('pwd');

if ($login == "") {
	$ok=false;
	set_form_error('login', "Le prénom est obligatoire !");
}
if ($pwd == "") {
	$ok=false;
	set_form_error('pwd', "Le mot de passe est obligatoire ! Si tu l'as perdu, tant pis! pas de cadeaux pour toi cette année !");
}	

if (!$ok) {
	header("Location: login.php");
	exit();
}

$res = sql_select("select * from membre where prenom = '$login' and motPasse = '$pwd'", $nbRep);
if ($nbRep == 1) {
	$_SESSION['idUtilisateur'] = $res[0]['id'];
	header("Location: home.php");
} else {
	set_form_error('general', "Mauvaise réponse, encore une mauvaise réponse et pas de cadeaux pour toi cette année !");
	header("Location: login.php");
}

?>