<?

require_once(__DIR__.'/i_divers.php');

verifieAdmin();

$res = sql_select('select id, prenom, motPasse, email, admin from membre order by prenom asc', $nbRep);
$resGroupes = sql_select('select id, idMembre, nom from groupe order by nom asc, idMembre asc', $nbGroupes);

$membresParId = array();
foreach($res as $membre) {
	$membresParId[$membre['id']] = $membre;
}

$groupes = array();
foreach($resGroupes as $groupe) {
	$gid = $groupe['id'];
	if(!isset($groupes[$gid])) {
		$groupes[$gid] = array(
			'id' => $gid,
			'nom' => $groupe['nom'],
			'membres' => array(),
		);
	}
	$groupes[$gid]['membres'][] = $groupe['idMembre'];
}

aff_header();

echo '<div class="box pink">';
echo '<h2>Administration des membres</h2>';

display_form_error('general');
display_form_error('admin_prenom');
display_form_error('admin_motPasse');
display_form_error('admin_email');

echo '<h3>Ajouter une personne</h3>';
echo '<form method="post" action="r_admin_membre.php">';
echo '<input type="hidden" name="action" value="add" />';
echo '<p>Prénom : <input type="text" name="prenom" size="30" /></p>';
echo '<p>Mot de passe : <input type="text" name="motPasse" size="30" /></p>';
echo '<p>Email : <input type="text" name="email" size="50" /></p>';
echo '<p><input type="submit" value="Ajouter la personne" /></p>';
echo '</form>';

echo '<h3>Membres existants</h3>';

if($nbRep == 0) {
	echo '<p>Aucun membre.</p>';
} else {
	echo '<table class="table table-striped">';
	echo '<tr><th>Prénom</th><th>Email</th><th>Admin</th><th>Mot de passe</th><th>Actions</th></tr>';
	foreach($res as $membre) {
		echo '<tr>';
		echo '<td>'.$membre['prenom'].'</td>';
		echo '<td>'.$membre['email'].'</td>';
		echo '<td>';
		if($membre['id'] == 1) {
			echo 'Oui (premier user)';
		} else {
			echo '<form method="post" action="r_admin_membre.php" style="margin:0;">';
			echo '<input type="hidden" name="action" value="toggle_admin" />';
			echo '<input type="hidden" name="id" value="'.$membre['id'].'" />';
			echo '<label class="checkbox" style="margin-bottom:0;">';
			echo '<input type="checkbox" name="admin" value="1"';
			if(intval($membre['admin']) == 1) echo ' checked="checked"';
			echo ' onchange="this.form.submit();" />';
			echo ' Admin';
			echo '</label>';
			echo '</form>';
		}
		echo '</td>';
		echo '<td>';
		echo '<form method="post" action="r_admin_membre.php" style="margin:0;">';
		echo '<input type="hidden" name="action" value="update_password" />';
		echo '<input type="hidden" name="id" value="'.$membre['id'].'" />';
		echo '<input type="text" name="motPasse" size="20" value="'.$membre['motPasse'].'" />';
		echo '</td>';
		echo '<td>';
		echo '<input type="submit" value="Modifier le mdp" />';
		echo '</form>';
		if($membre['id'] != $_SESSION['idUtilisateur']) {
			echo '<form method="post" action="r_admin_membre.php" style="margin-top:8px;">';
			echo '<input type="hidden" name="action" value="delete" />';
			echo '<input type="hidden" name="id" value="'.$membre['id'].'" />';
			echo '<input type="submit" value="Retirer" onclick="return confirm(\'Retirer cette personne du webdo ?\');" />';
			echo '</form>';
		} else {
			echo 'Administrateur courant';
		}
		echo '</td>';
		echo '</tr>';
	}
	echo '</table>';
}

echo '<hr/>';
echo '<h2>Administration des groupes</h2>';

display_form_error('group_nom');
display_form_error('group_membres');

echo '<h3>Créer un groupe</h3>';
echo '<form method="post" action="r_admin_membre.php">';
echo '<input type="hidden" name="action" value="create_group" />';
echo '<p>Nom du groupe : <input type="text" name="group_nom" size="40" /></p>';
echo '<p>Membres :</p>';
foreach($res as $membre) {
	echo '<label class="checkbox">';
	echo '<input type="checkbox" name="group_members[]" value="'.$membre['id'].'" /> '.h($membre['prenom']);
	echo '</label>';
}
echo '<p><input type="submit" value="Créer le groupe" /></p>';
echo '</form>';

echo '<h3>Groupes existants</h3>';

if(count($groupes) == 0) {
	echo '<p>Aucun groupe.</p>';
} else {
	foreach($groupes as $groupe) {
		echo '<div class="well">';
		echo '<form method="post" action="r_admin_membre.php" style="margin:0;">';
		echo '<input type="hidden" name="action" value="update_group" />';
		echo '<input type="hidden" name="group_id" value="'.$groupe['id'].'" />';
		echo '<p><b>Nom du groupe :</b> <input type="text" name="group_nom" size="40" value="'.h($groupe['nom']).'" /></p>';
		echo '<p><b>Membres :</b></p>';
		foreach($res as $membre) {
			echo '<label class="checkbox">';
			echo '<input type="checkbox" name="group_members[]" value="'.$membre['id'].'"';
			if(in_array($membre['id'], $groupe['membres'])) echo ' checked="checked"';
			echo ' /> '.h($membre['prenom']);
			echo '</label>';
		}
		echo '<p><input type="submit" value="Enregistrer le groupe" /></p>';
		echo '</form>';
		echo '<form method="post" action="r_admin_membre.php" style="margin-top:8px;">';
		echo '<input type="hidden" name="action" value="delete_group" />';
		echo '<input type="hidden" name="group_id" value="'.$groupe['id'].'" />';
		echo '<input type="submit" value="Supprimer ce groupe" onclick="return confirm(\'Supprimer ce groupe ?\');" />';
		echo '</form>';
		echo '</div>';
	}
}

echo '</div>';

aff_footer();

?>
