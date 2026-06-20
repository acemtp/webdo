<?
/**
 * Modification de cadeau existant.
 */

require_once('i_divers.php');
require_once('i_amazon.php');

verifieUtilisateur();

$ok = true;

$id = get_param_int('id');
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

if ($titre == '') {
	$ok = false;
	set_form_error('titre', 'Le titre est obligatoire sauf si tu fournis un lien Amazon.');
}

if ($ok) {
	$titre = sql_escape($titre);
	$description = sql_escape($description);
	$url = sql_escape($url);
	$image = sql_escape($image);
	sql_update("update kdo set titre='$titre',url='$url',image='$image',description='$description',priorite=$priorite where id = $id");
	set_form_error('general', 'Modifications enregistrées.');
}

header('Location: '.$_SESSION['back']);

?>
