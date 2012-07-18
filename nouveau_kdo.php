<? 
/** 
 * Formulaire d'ajout de cadeau (pour soit ou pour qq1)
 * 	nÈcessite l'identification
 * 				
 * @param titreERR
 * 				optionnel - erreur sur le titre
 * @param urlERR
 * 				optionnel - erreur sur le lien (not used)
 * @param imageERR
 * 				optionnel - erreur sur l'image (not used)
 * @param descriptionERR
 * 				optionnel - erreur sur la description (not used)
 * @param generalERR
 * 				optionnel - erreur gÈnÈrale ou message de confirmation de modification
 */

require_once('i_divers.php');

verifieUtilisateur();

aff_header();

echo '<div class="box pink">';

$id = get_param('id');
$pour = get_param('pour');

// si il y a un id, c'est qu'on veut utiliser le meme cadeau que l'id comme base (copie de cadeau)

$titre = '';
$url = '';
$image = '';
$desc = '';

if($id)
{
	$res = sql_select("select * from kdo where id=".$id, $nbRep);
	$titre = $res[0]['titre'];
	$url = $res[0]['url'];
	$image = $res[0]['image'];
	$desc = $res[0]['description'];
}

?>
<form name="KADO_FORM" method="post" action="r_ajoute_kdo.php">
<h2>Un nouveau cadeau pour <? echo prenom($pour); ?></h2>
<? display_form_error('general'); ?>
<div>
<input type="hidden" name="pour" value="<? echo $pour ?>" />
<? display_form_error('titre'); ?>
<p>Titre : <input type="text" name="titre" size="100" value="<?=$titre?>" /></p>
<? display_form_error('url'); ?>
<p>Lien : <input type="text" name="url" size="100" value="<?=$url?>" /></p>
<? display_form_error('image'); ?>
<p>Image : <input type="text" name="image" size="100" value="<?=$image?>" /></p>
<p>Priorit√© : 
<?
if ($pour == $_SESSION['idUtilisateur']) {
	aff_priorite(true, 3);
} else {
	aff_priorite(true, 5);
}
echo '</p>';
display_form_error('description'); ?>
<p>D√©tails :<br/><textarea name="description" cols="100" rows="20" /><?=$desc?></textarea></p>
<input type="submit" name="valider" value="Ajouter" />
</div>
</form>
<p>
<?

aff_footer();
?>
