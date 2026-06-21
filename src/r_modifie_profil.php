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

require_once(__DIR__.'/i_divers.php');

verifieUtilisateur();

function photo_upload_error_message($errorCode) {
	switch($errorCode) {
		case UPLOAD_ERR_INI_SIZE:
			return 'La photo depasse la taille maximale autorisee par le serveur.';
		case UPLOAD_ERR_FORM_SIZE:
			return 'La photo depasse la taille maximale autorisee par le formulaire.';
		case UPLOAD_ERR_PARTIAL:
			return 'La photo n\'a ete envoyee que partiellement.';
		case UPLOAD_ERR_NO_TMP_DIR:
			return 'Le serveur n\'a pas de dossier temporaire pour les uploads.';
		case UPLOAD_ERR_CANT_WRITE:
			return 'Le serveur ne peut pas ecrire le fichier temporaire.';
		case UPLOAD_ERR_EXTENSION:
			return 'Une extension PHP a bloque l\'upload de la photo.';
		default:
			return 'L\'upload de la photo a echoue.';
	}
}

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

if(isset($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
	if($_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
		$ok = false;
		set_form_error('general', photo_upload_error_message($_FILES['photo']['error']));
	} elseif(is_uploaded_file($_FILES['photo']['tmp_name'])) {
		$updir = photo_storage_dir();
		if(!is_dir($updir) && !mkdir($updir, 0777, true) && !is_dir($updir)) {
			$ok = false;
			set_form_error('general', 'Impossible de creer le dossier des photos : '.$updir);
		} else {
			$ext = strtolower(strrchr($_FILES['photo']['name'], '.'));
			if($ext == '') $ext = '.jpg';
			$upfile = $_SESSION['idUtilisateur'] . "_" . time() . "_" . rand(1000, 9999) . $ext;
			$uppath = $updir . "/" . $upfile;

			if (!move_uploaded_file($_FILES['photo']['tmp_name'], $uppath)) {
				$ok = false;
				$upfile = "";
				set_form_error('general', 'Impossible d\'enregistrer la photo envoyee.');
			}
		}
	}
}

if ($ok) {
	$prenom = sql_escape($prenom);
	$motPasse = sql_escape($motPasse);
	$anniversaire = sql_escape($anniversaire);
	$presentation = sql_escape($presentation);
	$email = sql_escape($email);
	$aime = sql_escape($aime);
	$aimepas = sql_escape($aimepas);
	$req = "update membre set prenom='$prenom', motPasse='$motPasse', anniversaire='$anniversaire', presentation='$presentation', email='$email', aime='$aime', aimepas='$aimepas'";
	if(isset($upfile) && $upfile != "") {
		$req .= ",photo='".sql_escape($upfile)."'";
	}
	$req .= " where id = ".$_SESSION['idUtilisateur'];
	sql_update($req);
	set_form_error('general', "Modifications enregistrées.");
}

header("Location: profil.php");

?>
