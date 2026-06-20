<?
/**
 * Suggestion de cadeau (pour soit ou pour qq1)
 *  mise a jour en BDD : table KDO
 *  necessite l'identification
 *  redirection automatique vers la liste des cadeaux
 */

require_once('i_divers.php');
require_once('i_amazon.php');

verifieUtilisateur();

$pour = get_param_int('pour');
$titre = get_param('titre');
$description = get_param('description');
$url = amazon_normalise_url(get_param('url'));
$image = amazon_normalise_url(get_param('image'));
$priorite = get_param_int('priorite');

if ($url != '' && amazon_est_url($url)) {
	$amazon = amazon_fetch_product_data($url);
	if ($titre == '' && $amazon['titre'] != '') {
		$titre = $amazon['titre'];
	}
	if ($image == '' && $amazon['image'] != '') {
		$image = $amazon['image'];
	}
}

if ($titre == '' && $url != '') {
	$titre = amazon_titre_auto($url);
}

if ($titre == '') {
	set_form_error('titre', 'Le titre est obligatoire sauf si tu fournis un lien Amazon.');
	header('Location: nouveau_kdo.php?pour='.$pour);
	exit();
}

$titre = sql_escape($titre);
$description = sql_escape($description);
$url = sql_escape($url);
$image = sql_escape($image);

sql_insert("insert into kdo (pour, titre, description, url, image, creeLe, creePar, priorite) values ($pour, '$titre', '$description', '$url', '$image', '".date('Y-m-d')."', ".$_SESSION['idUtilisateur'].", $priorite)");

header('Location: les_kdos.php?pour='.$pour);

?>
