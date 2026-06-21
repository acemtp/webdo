<?
/** 
 * Annulation de réservation d'un cadeau pour qq1
 *		mise à jour en BDD : table KADO
 * 	nécessite l'identification
 *		redirection automatique vers la liste des cadeaux de la personne
 *
 * @param idKdo
 * 				identifiant du cadeau acheté par l'utilisateur
 * @param pour
 * 				optionnel - identifiant de la personne à qui est destiné le cadeau
 */

require_once(__DIR__.'/i_divers.php');

verifieUtilisateur();

$idKdo = get_param('idKdo');

sql_update("update kdo set reservePar=null where id = ".$idKdo);

header('Location: '.$_SESSION['back']);

?>