<?php

require_once(__DIR__.'/i_divers.php');
require_once(__DIR__.'/i_gifts.php');

verifieUtilisateur();

$arch = get_param('arch') == '1';

$pour = get_param('pour');

$order = get_param('order');
if($order=='') $order = 'priorite';

$way = get_param('way');
if($way =='') $way = 'asc';

$mine = $pour == $_SESSION['idUtilisateur'];
$prenom = $pour ? prenom($pour) : '';

$requete = "select id, image, creeLe, titre, pour, priorite, creePar, reservePar, reserveLe, achetePar, acheteLe, partage from kdo where ";

if ($pour) {
	$requete .= "pour=$pour";

	if($mine) $requete .= " and creePar=$pour";
} else {
	$mes_groupes = sql_select("select id from groupe where idMembre=".$_SESSION['idUtilisateur'], $nbRep);
	$req = "select idMembre from groupe where ";
	foreach ($mes_groupes as $grp) {
		$req .= "id=".$grp['id']." or ";
	}
	$req .= "0 group by idMembre";
	$membres = sql_select($req, $nbRep);

	$requete .=  "(";
	foreach ($membres as $m) {
		if($arch || $m['idMembre'] != $_SESSION['idUtilisateur'])
			$requete .= "pour=".$m['idMembre']." or ";
	}
	$requete .= "0) ";
}
if($arch) {
	$requete .= ' and supprime=2';
	$requete .= ' order by pour, achetePar, priorite';
} else {
	$requete .= ' and supprime=0';

	switch($order)
	{
	case 'titre': $requete .= ' order by titre '.$way; break;
	case 'creeLe': $requete .= ' order by creeLe '.$way; break;
	case 'pour': $requete .= ' order by pour '.$way.',priorite'; break;
	case 'reservePar': $requete .= ' order by reservePar '.$way.',priorite'; break;
	case 'achetePar': $requete .= ' order by achetePar '.$way.',priorite'; break;
	default:
	case 'priorite':
		if($mine) $requete .= ' order by priorite '.$way.',creeLe';
		else $requete .= ' order by pour,achetePar,reservePar,priorite'; 
		break;
	}
}

$res = sql_select($requete, $nbRep);

aff_header();

//echo $requete;

if($mine)
{
	echo "<h2>Votre profile</h2>";
	if($arch) $title = 'Ma liste de cadeaux archivé';
	else $title = 'Ma liste de cadeaux';
}
else
{
	if($arch) $title = 'La liste de cadeaux archivé pour ';
	else $title = 'La liste de cadeaux pour ';

	if($pour) 
	{
		echo "<h2>Profile de $prenom</h2>";

	}
	else $prenom = 'tout le monde';

	$title .= $prenom;
}

	
$supo = false;

if($pour)
{
	echo '<div class="box violet profile-summary">';
	echo '<div class="profile-summary-media">';
	$photo = photo($pour);
	echo '<img class="tbp" src="'.user_avatar_url($photo, $prenom).'" alt="'.h($prenom).'" />';
	echo '</div>';
	echo '<div class="profile-summary-copy">';
	$res2 = sql_select('select aime, aimepas, presentation from membre where id ='.$pour, $nbRep);
	echo '<h2>Informations</h2>';
	echo '<p>';
	if($res2[0]['presentation'] != '')
	{
		echo embellir($res2[0]['presentation']);
	} else {
		echo "aucune information n'a était remplie :/";
			
		}
	echo '</p>';
	echo '</div>';
	echo '</div>';

	if($res2[0]['aime'] != '' || $res2[0]['aimepas'] != '')
	{
		echo '<div class="row">';
		echo '<div class="span6">';
		if($res2[0]['aime'] != '')
		{
			echo '<p><table class="gifts" style="width:100%">';
			echo '<tr><th><span class="emoji-icon with-gap" aria-hidden="true">🙂</span>Aime toujours</th></tr>';
			echo '<tr><td>'.embellir($res2[0]['aime']).'</td></tr>';
			echo '</table></p>';
		}
		echo '</div>';

		echo '<div class="span6">';
		if($res2[0]['aimepas'] != '')
		{
			echo '<p><table class="gifts" style="width:100%">';
			echo '<tr><th><span class="emoji-icon with-gap" aria-hidden="true">🙃</span>A Eviter</th></tr>';
			echo '<tr><td>'.embellir($res2[0]['aimepas']).'</td></tr>';
			echo '</table></p>';
		}
		echo '</div>';
		echo '</div>';
 	}

	
	if(!$arch)
	{
		echo '<p><a class="btn btn-primary" href="nouveau_kdo.php?pour='.$pour.'"><b>';
		if($mine) echo 'Ajouter un cadeau';
		else echo 'Suggerer un cadeau pour '.$prenom;
		echo '</b></a></p>';
	}
}

$display = array();
$display[] = $arch?'supprime':'archive';
$display[] = 'creeLe';
$display[] = 'titre';
$display[] = (!$pour)?'pour':'';
$display[] = 'priorite';
if(!$mine || $arch)
{
	$display[] = 'reservePar';
	$display[] = 'achetePar';
}

$url = 'les_kdos.php?';
if($pour) $url .= 'pour='.$pour;

display_gifts($title, $res, $display, $order, $way, $url);

echo '<p><a class="btn" href="les_kdos.php?';
if($pour) echo '&pour='.$pour;
if(!$arch) echo '&arch=1';
echo '">';
if($mine) echo 'Voir mes cadeaux';
else echo 'Voir les cadeaux';
if(!$arch) echo ' archivés';
if(!$mine) echo ' de '.$prenom;
echo '</a></p>';

aff_footer();

?>
