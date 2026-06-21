<?
/** 
 * Suppression logique d'un commentaire
 *		mise à jour en BDD : table commentaire
 * 	nécessite l'identification
 *		redirection automatique vers le cadeaux
 *
 * @param id
 * 				identifiant du commentaire à supprimer
 * @param idKdo
 * 				identifiant du cadeau
 */

require_once(__DIR__.'/i_divers.php');

verifieUtilisateur();

$id = get_param('id');

if ($id == "") {
	die("j'ai perdu l'identifiant du commentaire !");
}

sql_update("update commentaire set supprime=1 where id=$id");

header('Location: '.$_SESSION['back']);

?>