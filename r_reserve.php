<?
/** 
 * Reservation d'un cadeau pour qq1
 *		mise à jour en BDD : table KADO  
 * 	nécessite l'identification
 *		redirection automatique vers la liste des cadeaux de la personne
 *
 * @param idKdo
 * 				identifiant du cadeau partagé par l'utilisateur
 * @param pour
 * 				optionnel - identifiant de la personne à qui est destiné le cadeau
 */

require_once('i_divers.php');

verifieUtilisateur();

$idKdo = get_param('idKdo');

sql_update("update kdo set reservePar=".$_SESSION['idUtilisateur'].",reserveLe=\"".date('Y-m-d')."\" where id = ".$idKdo);

header('Location: '.$_SESSION['back']);

?>