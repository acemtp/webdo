<?
/** 
 * Suppression logique d'un commentaire
 *		mise � jour en BDD : table commentaire
 * 	n�cessite l'identification
 *		redirection automatique vers le cadeaux
 *
 * @param id
 * 				identifiant du commentaire � supprimer
 * @param idKdo
 * 				identifiant du cadeau
 */

require_once('i_divers.php');

verifieUtilisateur();

$id = get_param('id');

if ($id == "") {
	die("j'ai perdu l'identifiant du commentaire !");
}

sql_update("update commentaire set supprime=1 where id=$id");

header('Location: '.$_SESSION['back']);

?>