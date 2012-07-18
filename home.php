<? 
/** 
 * Page d'accueil apr�s login - pr�sentation du menu
 * 	n�cessite l'identification
 *
 */

require_once('i_divers.php');

verifieUtilisateur();

if ($_SESSION['idUtilisateur']=='1')
	$toi = "mamounette";
elseif ($_SESSION['idUtilisateur']=='3')
	$toi = "mon lapin";
elseif ($_SESSION['idUtilisateur']=='2'||$_SESSION['idUtilisateur']=='5'||$_SESSION['idUtilisateur']=='6'||$_SESSION['idUtilisateur']=='8')
	$toi = "ma puce";
elseif ($_SESSION['idUtilisateur']=='4'||$_SESSION['idUtilisateur']=='7')
	$toi = "mon lapin";
else
	$toi = prenom($_SESSION['idUtilisateur']);

aff_header();

echo '<div class="box pink">';

echo '<h2>Bienvenue '.$toi.' !</h2>';

$res = sql_select('SELECT count(*) as nb FROM kdo WHERE supprime=0 AND pour='.$_SESSION['idUtilisateur'].' AND creePar='.$_SESSION['idUtilisateur'], $nbRep);
echo '<p><a href="les_kdos.php?pour='.$_SESSION['idUtilisateur'].'">Nombre de cadeaux dans ta liste: '.$res[0]['nb'].'</a></p>';

$res = sql_select('SELECT count(*) as nb FROM kdo WHERE supprime=0 AND reservePar='.$_SESSION['idUtilisateur'], $nbRep);
echo '<p><a href="resa.php">Nombre de cadeaux que tu as reservé: '.$res[0]['nb'].'</a></p>';

$res = sql_select('SELECT count(*) as nb FROM kdo WHERE supprime=0 AND achetePar='.$_SESSION['idUtilisateur'], $nbRep);
echo '<p><a href="resa.php">Nombre de cadeau que tu as acheté: '.$res[0]['nb'].'</a></p>';

$res = sql_select('SELECT count(*) as nb FROM kdo WHERE creeLe > SUBDATE(NOW(), "7 days")', $nbRep);
echo '<p><a href="kdos.php?latest=1">Nombre de cadeaux ajoutés ces 7 dernièrs jours: '.$res[0]['nb'].'</a></p>';

$res = sql_select('SELECT count(*) as nb FROM kdo WHERE reserveLe > SUBDATE(NOW(), "7 days")', $nbRep);
echo '<p>Nombre de cadeaux reservés ces 7 dernièrs jours: '.$res[0]['nb'].'</p>';

$res = sql_select('SELECT count(*) as nb FROM kdo WHERE acheteLe > SUBDATE(NOW(), "7 days")', $nbRep);
echo '<p>Nombre de cadeaux achetés ces 7 dernièrs jours: '.$res[0]['nb'].'</p>';

$res = sql_select('SELECT count(*) as nb FROM kdo WHERE supprime=0', $nbRep);
echo '<p>Nombre de cadeaux dans le webdo: '.$res[0]['nb'].'</p>';


echo '</div>';

aff_footer();
?>