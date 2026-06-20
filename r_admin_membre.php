<?

require_once('i_divers.php');

verifieAdmin();

$action = get_param('action');
$id = intval(get_param('id'));
$prenom = trim(get_param('prenom'));
$motPasse = trim(get_param('motPasse'));
$email = trim(get_param('email'));
$ok = true;

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
else {
	set_form_error('general', 'Action inconnue.');
}

header('Location: admin.php');

?>
