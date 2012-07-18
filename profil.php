<? 
/** 
 * Fiche d'un profil 
 *		possibilité de modification si c'est le sien, sinon consultation
 * 	nécessite l'identification
 *	
 * @param id
 * 				optionnel - identifiant du profil à afficher (par defaut, celui de l'utilisateur)
 */

require_once('i_divers.php');

verifieUtilisateur();

$id = get_param('id');
if($id == '') $id = $_SESSION['idUtilisateur'];

$view = get_param('view');

$edit = $id == $_SESSION['idUtilisateur'] && !$view;

$res = sql_select("select * from membre where id = ".$id, $nbRep);
if ($nbRep != 1) {
	die("Je t'ai perdu. Qui es-tu ? <a href=login.php>Clique ici<a>");
}

$motPasse = $res[0]["motPasse"];
$prenom = $res[0]["prenom"];
$anniversaire = explode("-",$res[0]["anniversaire"]);
$presentation = $res[0]["presentation"];
$email = $res[0]["email"];
$photo = $res[0]["photo"];
$aime = $res[0]["aime"];
$aimepas = $res[0]["aimepas"];

aff_header();

echo '<div class="box pink">';

if($edit) echo '<form name="PROFIL_FORM" enctype="multipart/form-data" method="post" action="r_modifie_profil.php">';

echo '<h2>';
if($edit) { echo "Mes informations"; } else { echo "Les informations de $prenom"; }
echo '</h2>';

display_form_error('general');

if($photo!="") {
	echo '<p class="center"><img class="tbp" src="photo/'.$photo.'"/></p>';
	if($edit) echo '<p><a href="r_supprime_photo.php">Retirer la photo</a></p>';
}
if ($edit) {
	echo '<p><input type="hidden" name="MAX_FILE_SIZE" value="300000" />';
	echo '<p>Changer la photo : <input name="photo" type="file" /></p>';
}

if ($edit) {
	display_form_error('prenom');
	echo '<p>Prénom : <input type="text" name="prenom" size="30" value="'.$prenom.'" />';
} else { 
	echo '<p>Prénom : '.$prenom;
}
echo '</p>';

if ($edit) {
	display_form_error('motPasse');
	echo '<p>Mot de passe : <input type="text" size="30" name="motPasse" value="'.$motPasse.'" /> (Les mots de passe ne sont pas cryptés, mettez en un qui ne risque rien)</p>';
}

if ($edit) {
	display_form_error('anniversaire');
	echo '<p>Date d\'anniversaire (jj/mm/aaaa) : ';
	echo '<input type="text" name="anniversaire" size="10" value="'.$anniversaire[2].'/'.$anniversaire[1]."/".$anniversaire[0].'" />';
} else { 
	echo '<p>Date d\'anniversaire : '.$anniversaire[2]."/".$anniversaire[1]."/".$anniversaire[0];
}
echo '</p>';

echo '<p>Email : ';
if ($edit) {
	echo '<input type="text" name="email" size="50" value="'.$email.'" />';
} else { 
	echo "<a href=\"mailto:$email\">$email</a>";
} 
echo '</p>';

if ($edit) {
	display_form_error('presentation');
	echo '<p>Présentation :<br/>';
	echo '<textarea name="presentation" cols="100" rows="10">'.$presentation.'</textarea>';
} else {
	echo '<p>Présentation : '.embellir($presentation);
}
echo '</p>';

if ($edit) {
	display_form_error('aime');
	echo '<p>Aime :<br/>';
	echo '<textarea name="aime" cols="100" rows="10">'.$aime.'</textarea>';
} else {
	echo '<p><table class="gifts">';
	echo '<tr><th><img src="images/happy.png"/>  Aime</th></tr>';
	echo '<tr><td>'.embellir($aime).'</td></tr>';
	echo '</table>';
}
echo '</p>';

if ($edit) {
	display_form_error('aimepas');
	echo '<p>Aime pas :<br/>';
	echo '<textarea name="aimepas" cols="100" rows="10">'.$aimepas.'</textarea>';
} else {
	echo '<p><table class="gifts">';
	echo '<tr><th><img src="images/unhappy.png"/> Aime pas</th></tr>';
	echo '<tr><td>'.embellir($aimepas).'</td></tr>';
	echo '</table>';
}
echo '</p>';

if ($edit) {
	echo '<input type="submit" name="valider" value="Sauver le profil" /></form>';
}

echo '<p>';
aff_groupe($id, false, 0);
echo '</p>';

echo '<p><a href="les_kdos.php?pour='.$id.'">Liste des cadeaux</a></p>';

if($id == $_SESSION['idUtilisateur']) {
	if($view) {
		echo '<p><a href="profil.php">Editer mon profil</a></p>';
	} else {
		echo '<p><a href="profil.php?view=1">Voir comme les autres</a></p>';
	}
}

echo '</div>';

aff_footer();

?>