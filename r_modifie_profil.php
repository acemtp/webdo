<?
/** 
 * Modification de ses donn�es personnelles
 *		mise � jour en BDD : table MEMBRE
 * 	n�cessite l'identification
 *		redirection automatique vers son profil modifi�
 *	
 * @param prenom
 * 				nouveau prenom (login)
 * @param motPasse
 * 				nouveau motPasse (pas de confirmation)
 * @param anniversaire
 * 				optionnel - nouvelle date d'anniversairea au format jj/mm/aaaa (checkdate marche moyen)
 * @param presentation
 * 				optionnel - nouvelle presentation
 */

require_once('i_divers.php');

verifieUtilisateur();

$prenom = get_param('prenom');
$motPasse = get_param('motPasse');
$anniversaire = get_param('anniversaire');
$presentation = get_param('presentation');
$email = get_param('email');
$aime = get_param('aime');
$aimepas = get_param('aimepas');

$ok=true;

if ($prenom == "") {
	$ok=false;
	set_form_error('prenom', "Le prénom est obligatoire !");
}
if ($motPasse == "") {
	$ok=false;
	set_form_error('motPasse', "Le mot de passe est obligatoire !");
}

if ($anniversaire != "") {
	$t_anniversaire = explode("/",$anniversaire);
	if (! checkdate($t_anniversaire[1],$t_anniversaire[0],$t_anniversaire[2])) { 
		$ok=false;
		set_form_error('anniversaire', "La date n'est pas correcte ! format : jj/mm/aaaa");
	} else {
		$anniversaire = $t_anniversaire[2]."-".$t_anniversaire[1]."-".$t_anniversaire[0];
	}
}

if(is_uploaded_file($_FILES['photo']['tmp_name'])) {
	$updir = './photo';
	if(!is_dir($updir)) mkdir($updir);
	$ext = strtolower(strrchr($_FILES['photo']['name'], '.'));
	$upfile = $_SESSION['idUtilisateur'] . "_" . rand() . $ext;
	$uppath = $updir . "/" . $upfile;

	if (!move_uploaded_file($_FILES['photo']['tmp_name'], $uppath)) {
		$upfile = "";
	}
}

if ($ok) {
	$req = "update membre set prenom='$prenom', motPasse='$motPasse', anniversaire='$anniversaire', presentation='$presentation', email='$email', aime='$aime', aimepas='$aimepas'";
	if(isset($upfile) && $upfile != "") {
		$req .= ",photo=\"".$upfile."\"";
	}
	$req .= " where id = ".$_SESSION['idUtilisateur'];
	sql_update($req);
	set_form_error('general', "Modifications enregistrées.");
}

header("Location: profil.php");

?>