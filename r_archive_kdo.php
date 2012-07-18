<?
/** 
 * Archivage d'un cadeau (pour soit ou pour qq1 mais logiquement, qu'on a créé)
 *		mise à jour en BDD : table kado
 * 	nécessite l'identification
 *		redirection automatique vers la liste des cadeaux (pour soit ou pour qq1)
 *
 * @param id
 * 				identifiant du cadeau à supprimer
 * @param pour
 * 				optionnel - identifiant de la personne à qui est destiné le cadeau
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