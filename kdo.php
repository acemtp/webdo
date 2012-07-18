<? 
/** 
 * Fiche d'un cadeau pour soi (=pour l'utilisateur)
 *		possibilit� de modification, suppression 
 *		gestion pr�c�dent/suivant dans la liste des cadeaux 
 * 	n�cessite l'identification
 *	
 * @param id
 * 				identifiant du cadeau � afficher
 */

require_once('i_divers.php');
require_once('i_commentaire.php');

verifieUtilisateur();

$id = get_param('id');
$view = get_param('view') == 1;

$res = sql_select("select * from kdo where id = ".$id, $nbRep);
if ($nbRep != 1) die("oups un problème avec ce cadeau ? <a href=les_kdos.php>Clique ici<a>");

$pour = $res[0]["pour"];
$titre = $res[0]["titre"];
$description = $res[0]["description"];
$image = $res[0]["image"];
$url = $res[0]["url"];
$creePar = $res[0]["creePar"];
$reservePar = $res[0]["reservePar"];
$achetePar = $res[0]["achetePar"];
$partage = $res[0]["partage"];
$supprime = $res[0]["supprime"];
$creeLe = explode("-",$res[0]["creeLe"]);
$reserveLe = explode("-",$res[0]["reserveLe"]);
$acheteLe = explode("-",$res[0]["acheteLe"]);
$prio = $res[0]["priorite"];

$edit = $creePar == $_SESSION['idUtilisateur'] && !$view;
$for_me = $pour == $_SESSION['idUtilisateur'];

aff_header();

echo '<div class="box pink">';

echo '<h2>'.$titre.' pour '.prenom($pour).'</h2>';

display_form_error('general');

if($edit)
{
	echo '<form name="KADO_FORM" method="post" action="r_modifie_kdo.php">';
	echo '<input type="hidden" name="id" value="'.$id.'" />';
}

if ($image) { echo '<p class="center"><img class="tbp" src="'.$image.'"></p>'; }

echo '<br/>';

if ($supprime == "1") {
	echo '<div class="error">ATTENTION CE CADEAU A ETE SUPPRIME !</div>';
} if ($supprime == "2") {
	echo '<div class="error">ATTENTION CE CADEAU A ETE ARCHIVE !</div>';
}

echo '<p>Date de création : '.$creeLe[2].'/'.$creeLe[1].'/'.$creeLe[0];
if(!$for_me) echo ' par '.prenom($creePar);
echo '</p>';

if($edit) {
	display_form_error('titre');
	echo '<p>Titre : <input type="text" size="100" name="titre" value="'.htmlentities ($titre, ENT_QUOTES, "UTF-8").'" /></p>';
}

if($edit) {
	display_form_error('url');
	echo '<p>Lien : <input type="text" size="100" name="url" value="'.$url.'" /></p>';
} else {
	if(strlen($url) > 50) {
		$urld = substr($url, 0, 50)."...";
	} else {
		$urld = $url;
	}
	echo "<p>Lien: <a target=\"_blank\" href=\"$url\">$urld</a></p>";
}

if($edit) {
	display_form_error('image');
	echo '<p>Image : <input type="text" size="100" name="image" value="'.$image.'" /></p>';
}

echo "Priorité : ";
aff_priorite($edit, $prio);

if($edit) {
	display_form_error('description');
	echo '<p>Détails :<br/><textarea name="description" cols="100" rows="20">'.$description.'</textarea></p>';
} else {
//	echo "<p>Détails : <br/></br><b>".embellir($description)."</b></p>";

	echo '<br/><br/>Détails:';
	echo '<div class="box violet">';
	echo "<p><b>".embellir($description)."</b></p>";
	echo '</div>';


}

if ($edit) {
	echo '<input type="submit" name="valider" value="Modifier" /></form>';
}

echo '<br/>';

if($creePar == $_SESSION['idUtilisateur']) {
	if($view) {
		echo '<p><a href="kdo.php?id='.$id.'">Editer ce cadeau</a></p>';
	} else {
		echo '<p><a href="kdo.php?id='.$id.'&view=1">Voir comme les autres</a></p>';
	}
}

if(!$for_me) {
	echo '<p>';
	if ($reservePar == "") {
		echo '<a href="r_reserve.php?idKdo='.$id.'&pour='.$pour.'">Je réserve ce cadeau.</a></p><p>';
	} else {
		echo "Reservé le ".$reserveLe[2]."/".$reserveLe[1]."/".$reserveLe[0];
		echo " par ".prenom($reservePar).".";
		if ($reservePar == $_SESSION['idUtilisateur']) {
			echo "</p><p><a href=\"r_annule_resa.php?idKdo=$id\">J'annule ma reservation de ce cadeau.</a>";
		}
	}
	echo '</p>';

	echo '<p>';
	if ($achetePar == "") {
		echo '<a href="r_achete.php?idKdo='.$id.'&pour='.$pour.'">J\'ai acheté ce cadeau.</a>';
	} else {
		echo "Acheté le ".$acheteLe[2]."/".$acheteLe[1]."/".$acheteLe[0];
		echo " par ".prenom($achetePar).".";
		if ($achetePar == $_SESSION['idUtilisateur']) {
			echo "</p><p><a href=\"r_annule_achat.php?idKdo=$id\">Finalement, c'est pas moi qui l'ai acheté et j'embête tout le monde</a>";
		}
	}
	echo "</p>";
	
}

if($supprime!="2") {
	echo '<p><a href="r_archive_kdo.php?id='.$id.'">Archiver ce cadeau</a></p>';
}

if($supprime!="1" && $creePar == $_SESSION['idUtilisateur']) {
	echo '<p><a href="r_restore_kdo.php?id='.$id.'">Remettre ce cadeau dans la liste</a></p>';
	echo '<p><a href="javascript:alertSup('.$id.');">Supprimer ce cadeau</a></p>';
}

if(!$for_me)
	echo '<p><a href="nouveau_kdo.php?pour='.$_SESSION['idUtilisateur'].'&id='.$id.'">Je veux le même !</a></p>';
else
	echo '<p><a href="nouveau_kdo.php?pour='.$_SESSION['idUtilisateur'].'&id='.$id.'">Dupliquer ce cadeau!</a></p>';

echo '</div>';

aff_commentaire($id, $pour, $pour != $_SESSION['idUtilisateur']);

aff_footer();

?>