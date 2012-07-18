<?
/** 
 * Suggestion de cadeau (pour soit ou pour qq1)
 *		mise à jour en BDD : table KADO
 * 	nécessite l'identification
 *		redirection automatique vers la liste des cadeaux (pour soit ou pour qq1)
 *	
 * @param titre
 * 				nom du nouveau cadeau 
 * @param pour
 * 				identifiant de la personne à qui est destiné le cadeau
 * @param description
 * 				optionnel - description du nouveau cadeau
 * @param url
 * 				optionnel - lien du nouveau cadeau
 * @param image
 * 				optionnel - adresse de l'image du nouveau cadeau
 */

require_once('i_divers.php');

verifieUtilisateur();

$pour = get_param('pour');
$titre = get_param('titre');
$description = get_param('description');
$url = get_param('url');
$image = get_param('image');
$priorite = get_param('priorite');

if ($titre == "") {
	set_form_error('titre', 'Le titre est obligatoire !');
	header("Location: nouveau_kdo.php?pour=".$pour);
	exit();
}

sql_insert("insert into kdo (pour, titre, description, url, image, creeLe, creePar, priorite) values ($pour, '$titre', '$description', '$url', '$image', '".date('Y-m-d')."', ".$_SESSION['idUtilisateur'].", $priorite)");

header('Location: les_kdos.php?pour='.$pour);

?>