<?
/** 
 * Achat d'un cadeau pour qq1
 *		mise � jour en BDD : table KADO
 * 	n�cessite l'identification
 *		redirection automatique vers la liste des cadeaux de la personne
 *
 * @param idKdo
 * 				identifiant du cadeau achet� par l'utilisateur
 * @param pour
 * 				optionnel - identifiant de la personne � qui est destin� le cadeau
 */

require_once('i_divers.php');

require_once('i_sql.php');

verifieUtilisateur();

$idKdo = get_param('idKdo');

sql_update("update kdo set achetePar=".$_SESSION['idUtilisateur'].",acheteLe=\"".date('Y-m-d')."\" where id = ".$idKdo);

header('Location: '.$_SESSION['back']);

?>