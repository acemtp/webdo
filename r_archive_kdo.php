<?
/** 
 * Archivage d'un cadeau (pour soit ou pour qq1 mais logiquement, qu'on a cr��)
 *		mise � jour en BDD : table kado
 * 	n�cessite l'identification
 *		redirection automatique vers la liste des cadeaux (pour soit ou pour qq1)
 *
 * @param id
 * 				identifiant du cadeau � supprimer
 * @param pour
 * 				optionnel - identifiant de la personne � qui est destin� le cadeau
 */

require_once('i_divers.php');

verifieUtilisateur();

$id = get_param('id');

if ($id == "") {
	die("j'ai perdu l'identifiant du cadeau !");
}

// supprime=2 veut dire archiver
sql_update("update kdo set supprime=2 where id = ".$id);

header('Location: '.$_SESSION['back']);

?>