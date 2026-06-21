<?
/** 
 * Suppression logique d'un cadeau (pour soit ou pour qq1 mais logiquement, qu'on a cr��)
 *		mise � jour en BDD : table KADO  
 * 	n�cessite l'identification
 *		redirection automatique vers la liste des cadeaux (pour soit ou pour qq1)
 *
 * @param id
 * 				identifiant du cadeau � supprimer
 * @param pour
 * 				optionnel - identifiant de la personne � qui est destin� le cadeau
 */

require_once(__DIR__.'/i_divers.php');

verifieUtilisateur();

$res = sql_select("select photo from membre where id = ".$_SESSION['idUtilisateur'], $nbl);

if($nbl == 1 && $res[0]['photo'] != "") {
	sql_update("update membre set photo=\"\" where id = ".$_SESSION['idUtilisateur']);
	$photoPath = photo_storage_dir()."/".basename($res[0]['photo']);
	if(file_exists($photoPath)) unlink($photoPath);
	set_form_error('general', "La photo a été retiré");
}

header("Location: profil.php");

?>
