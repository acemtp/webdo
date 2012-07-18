<?
/**
 * Liste de lien vers les listes de cadeaux pour les autres
 *		mise en session des prénoms
 *
 */

require_once('i_divers.php');

verifieUtilisateur();

$res = sql_select('select id from groupe where idMembre='.$_SESSION['idUtilisateur'], $nbRep);
$membres = array();

for ($i = 0; $i < $nbRep; $i++) {
	// pour chaque groupe
	$group=$res[$i]['id'];

	// on recherche les infos sur les utilisateurs du groupe sauf l'utilisateur en cours
	$resMb = sql_select("select m.id, m.prenom, m.photo from membre m, groupe g where m.id=g.idMembre and g.id=$group order by m.prenom", $nb_tmp);

	// mémorisation des infos du membre de ce groupe
	for ($j = 0; $j < $nb_tmp; $j++) {
		$id = $resMb[$j]["id"];
		if (! isset($membres[$id])) {
			$membres[$id]["prenom"] = $resMb[$j]["prenom"];
			$membres[$id]["photo"] = $resMb[$j]["photo"];
		}
	}
}

aff_header();

echo '<div class="box pink">';

echo "<h2>Les utilisateurs</h2>";

$liste = get_param('liste')=='1';

echo '<p><a class="btn" href="les_kdos.php?order=creeLe&way=desc">Voir les derniers cadeaux ajoutés</a></p>';
echo "<p><a class='btn' href=\"les_kdos.php\">Afficher les cadeaux de tout le monde</a></p>";

if($liste) {
	echo "<p><a class='btn' href=\"?liste=0\">Afficher les photos des utilisateurs</a></p>";
	echo "<ul>";
} else {
	echo "<p><a class='btn' href=\"?liste=1\">Afficher les noms des utilisateurs</a></p>";
}

echo '<div class="row">';
echo '<ul class="thumbnails">';

foreach ($membres as $id => $arr) {
	$photo=$arr["photo"];
	$prenom=$arr["prenom"];

	$url='les_kdos.php';

	if($liste) {
		echo "<li><a href=\"$url?pour=$id\">Liste de cadeaux pour $prenom</a></li>\n";
	} else {
		echo '<li class="span3">';
		echo "<a class=\"thumbnail\" rel='tooltip' href=\"$url?pour=$id\" title=\"$prenom\">";
		if($photo!="") {
			echo "<img class=\"tb\" src=\"photo/$photo\" alt=\"$prenom\" />";
		} else {
			echo "<img class='tb' src='anon_user.jpg' alt='$prenom'/>";
		}
		echo "</a>";
		echo "</li>";
	}
}

echo "</ul>";

echo "</div>";

echo '</div>';

aff_footer();

?>
