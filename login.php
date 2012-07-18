<?
session_cache_expire(60*60*24*30);
session_start();
/**
 * Formulaire d'identification
 *
 * @param loginERR
 * 				optionnel - erreur sur le prenom
 * @param pwdERR
 * 				optionnel - erreur sur le mot de passe
 * @param generalERR
 * 				optionnel - erreur générale
 */

require_once('i_divers.php');

aff_header();

echo '<div class="box pink">';

?>

	<form method="post" action="r_test_login.php" class="well">
	<h2>Identification</h2>

	<? display_form_error('general'); ?>

	<? display_form_error('login'); ?>
	<label>Prénom</label><input type="text" class="span3" name="login" />

	<? display_form_error('pwd'); ?>
	<label>Mot de passe</label><input type="password" class="span3" name="pwd" />
<hr/>
	<input type="submit" name="valider" class="btn" value="Entrer" />
	</form>

<?
echo '</div>';

aff_footer();

?>