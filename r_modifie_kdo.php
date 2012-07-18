<?
/** 
 * Modification de cadeau existant (pour soit ou pour qq1)
 *		mise à jour en BDD : table KADO
 * 	nécessite l'identification
 *		redirection automatique vers la description du cadeau modifié (pour soit ou pour qq1)
 *	
 * @param titre
 * 				nouveau nom du cadeau 
 * @param description
 * 				optionnel - nouvelle description du cadeau
 * @param url
 * 				optionnel - nouveau lien du cadeau
 * @param image
 * 				optionnel - adresse de la nouvelle image du cadeau
 */

require_once('i_divers.php');

verifieUtilisateur();

$ok=true;

$id = get_param('id');
$pour = get_param('pour');
$titre = get_param('titre');
$description = get_param('description');
$url = get_param('url');
$image = get_param('image');
$priorite = get_param('priorite');

if ($titre == "") {
	$ok=false;
	set_form_error('titre', 'Le titre est obligatoire !');
}

if ($url != "" ) {
	// il faut 'http' pour que lien fonctionne directement
	if ( ! @eregi("^http",$url) ) 
		$url = "http://".$url;
}

if ($image != "" ) {
	// il faut 'http' pour que lien fonctionne directement
	if ( ! @eregi("^http",$image) ) 
		$image = "http://".$image;
}

if ($ok) {
	sql_update("update kdo set titre='$titre',url='$url',image='$image',description='$description',priorite=$priorite where id = $id");
	
	set_form_error('general', 'Modifications enregistrées.');
}

header('Location: '.$_SESSION['back']);

?>