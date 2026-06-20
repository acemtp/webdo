<?

require_once('i_divers.php');

verifieAdmin();

$action = get_param('action');
$id = get_param_int('id');
$prenom = trim(get_param('prenom'));
$motPasse = trim(get_param('motPasse'));
$email = trim(get_param('email'));
$ok = true;

function get_group_member_ids() {
	$membres = array();
	if(isset($_POST['group_members']) && is_array($_POST['group_members'])) {
		foreach($_POST['group_members'] as $idMembre) {
			$idMembre = intval($idMembre);
			if($idMembre > 0) $membres[$idMembre] = $idMembre;
		}
	}
	return array_values($membres);
}

function validate_group_data($nom, $membres) {
	$ok = true;
	if($nom == '') {
		$ok = false;
		set_form_error('group_nom', 'Le nom du groupe est obligatoire.');
	}
	if(count($membres) == 0) {
		$ok = false;
		set_form_error('group_membres', 'Choisis au moins un membre.');
	}
	return $ok;
}

if($action == 'add') {
	if($prenom == '') {
		$ok = false;
		set_form_error('admin_prenom', 'Le prénom est obligatoire.');
	}
	if($motPasse == '') {
		$ok = false;
		set_form_error('admin_motPasse', 'Le mot de passe est obligatoire.');
	}

	$res = sql_select("select id from membre where prenom = '$prenom'", $nbRep);
	if($nbRep > 0) {
		$ok = false;
		set_form_error('admin_prenom', 'Ce prénom existe déjà.');
	}

	if($ok) {
		$prenom = sql_escape($prenom);
		$motPasse = sql_escape($motPasse);
		$email = sql_escape($email);
		sql_insert("insert into membre (prenom, motPasse, email, aime, aimepas) values ('$prenom', '$motPasse', '$email', '', '')");
		set_form_error('general', 'La personne a été ajoutée.');
	}
}
elseif($action == 'update_password') {
	if($id <= 0) {
		$ok = false;
		set_form_error('general', 'Membre invalide.');
	}
	if($motPasse == '') {
		$ok = false;
		set_form_error('admin_motPasse', 'Le mot de passe est obligatoire.');
	}

	if($ok) {
		$motPasse = sql_escape($motPasse);
		sql_update("update membre set motPasse='$motPasse' where id=$id");
		set_form_error('general', 'Le mot de passe a été modifié.');
	}
}
elseif($action == 'toggle_admin') {
	if($id <= 0 || $id == 1) {
		$ok = false;
		set_form_error('general', 'Modification du rôle admin impossible.');
	}

	if($ok) {
		$admin = get_param('admin') == '1' ? 1 : 0;
		sql_update("update membre set admin=$admin where id=$id");
		set_form_error('general', 'Le rôle admin a été mis à jour.');
	}
}
elseif($action == 'delete') {
	if($id <= 0 || $id == $_SESSION['idUtilisateur']) {
		$ok = false;
		set_form_error('general', 'Suppression impossible.');
	}

	if($ok) {
		sql_update("delete from commentaire where creePar=$id");
		sql_update("delete from groupe where idMembre=$id");
		sql_update("delete from membre where id=$id");
		sql_update("update kdo set reservePar=null, reserveLe=null where reservePar=$id");
		sql_update("update kdo set achetePar=null, acheteLe=null where achetePar=$id");
		sql_update("update kdo set creePar=".$_SESSION['idUtilisateur']." where creePar=$id");
		sql_update("update kdo set pour=".$_SESSION['idUtilisateur']." where pour=$id");
		set_form_error('general', 'La personne a été retirée.');
	}
}
elseif($action == 'create_group') {
	$groupNom = trim(get_param('group_nom'));
	$groupMembers = get_group_member_ids();
	$ok = validate_group_data($groupNom, $groupMembers);

	if($ok) {
		$resGroup = sql_select('select max(id) as maxId from groupe', $nbRep);
		$groupId = intval($resGroup[0]['maxId']) + 1;
		$groupNom = sql_escape($groupNom);
		foreach($groupMembers as $groupMemberId) {
			sql_insert("insert into groupe (id, idMembre, nom) values ($groupId, $groupMemberId, '$groupNom')");
		}
		set_form_error('general', 'Le groupe a été créé.');
	}
}
elseif($action == 'update_group') {
	$groupId = get_param_int('group_id');
	$groupNom = trim(get_param('group_nom'));
	$groupMembers = get_group_member_ids();

	if($groupId <= 0) {
		$ok = false;
		set_form_error('general', 'Groupe invalide.');
	} else {
		$ok = validate_group_data($groupNom, $groupMembers);
	}

	if($ok) {
		$groupNom = sql_escape($groupNom);
		sql_update("delete from groupe where id=$groupId");
		foreach($groupMembers as $groupMemberId) {
			sql_insert("insert into groupe (id, idMembre, nom) values ($groupId, $groupMemberId, '$groupNom')");
		}
		set_form_error('general', 'Le groupe a été mis à jour.');
	}
}
elseif($action == 'delete_group') {
	$groupId = get_param_int('group_id');
	if($groupId <= 0) {
		$ok = false;
		set_form_error('general', 'Groupe invalide.');
	}

	if($ok) {
		sql_update("delete from groupe where id=$groupId");
		set_form_error('general', 'Le groupe a été supprimé.');
	}
}
else {
	set_form_error('general', 'Action inconnue.');
}

header('Location: admin.php');

?>
