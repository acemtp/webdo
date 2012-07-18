<?
/** 
 * ajoute un commentaire
 *		mise � jour en BDD : table commentaire
 * 	n�cessite l'identification
 *		redirection automatique vers le cadeau
 *	
 * @param id
 * 				id du cadeau 
 * @param pour
 * 				identifiant de la personne � qui est destin� le cadeau
 * @param commentaire
 * 				commentaire
 * @param visible
 * 				est ce le destinateur du cadeau peut voir le message
 */

require_once('i_divers.php');

verifieUtilisateur();

$id = get_param('id');
$pour = get_param('pour');
$commentaire = get_param('commentaire');
$visible = get_param('visible');

if ($commentaire == "") {
	header('Location: '.$_SESSION['back']);
	exit();
}

sql_insert("insert into commentaire (idKdo, commentaire, visible, creeLe, creePar) values (".$id.", \"".$commentaire."\", ".$visible.", \"".date('Y-m-d G:i:s')."\", ".$_SESSION['idUtilisateur'].")");

$res = sql_select("select creePar from kdo where id=".$id, $nbl);
$creePar = $res[0]['creePar'];

// envoie email
if($pour != $creePar && $creePar != $_SESSION['idUtilisateur'] || $pour == $creePar && $visible == 1 && $pour != $_SESSION['idUtilisateur']) {
	$destid = $creePar;
}
if(isset($destid) && $destid != "") {
	$prenom = prenom_simple($_SESSION['idUtilisateur']);
	$res = sql_select("select email from membre where id=$destid", $nbl);
	if($nbl == 1 && $res[0]['email'] != "") {
		$sujet = "[Webdo] $prenom a écrit un commentaire sur un de tes cadeaux";
		$message = "Un nouveau commentaire a été fait par $prenom sur un des cadeaux que tu as proposé sur Webdo.\r\n\r\n";
		$message .= "Clique sur le lien ci dessous pour voir le cadeau et le commentaire :\r\n\r\n";
		$message .= "http://webdo.ploki.info/kdo.php?id=".$id."\r\n\r\n";
		$message .= "Le commentaire de $prenom est le suivant :\r\n\r\n";
		$message .= $commentaire."\r\n\r\n";
		$from = "Webdo <noreply@webdo.ploki.info>";
		//echo "envoie email a ".$res[0]['email']." from $from<br/>";
		// marche pas mail($res[0]['email'], $sujet, $message, "Content-Type: text/plain;\nFrom: $from\nX-Mailer: PHP/" . phpversion(), "-f $from");
		mail($res[0]['email'], $sujet, $message, "From: $from");
	}
}

header('Location: '.$_SESSION['back']);

?>