<?php

require_once('i_divers.php');
require_once('i_gifts.php');

verifieUtilisateur();


aff_header();

echo '<div class="box pink">';

$requete = 'select * from kdo
			where reservePar='.$_SESSION['idUtilisateur'].' and achetePar is null and supprime=0 order by creeLe desc';
$res = sql_select($requete, $nbRep);
display_gifts('Les cadeaux que j\'ai réservé et pas encore acheté', $res, array('reserveLe', 'titre', 'pour', 'priorite', 'achetePar'));

echo '</div>';

echo '<div class="box pink">';

$requete = 'select * from kdo
			where reservePar='.$_SESSION['idUtilisateur'].' and achetePar='.$_SESSION['idUtilisateur'].' and supprime=0 order by creeLe desc';
$res = sql_select($requete, $nbRep);
display_gifts('Les cadeaux que j\'ai réservé et acheté', $res, array('reserveLe', 'titre', 'pour', 'priorite', 'archive'));
echo '</div>';

$requete = 'select * from kdo
			where (reservePar!='.$_SESSION['idUtilisateur'].' or reservePar is null ) and achetePar='.$_SESSION['idUtilisateur'].' and supprime=0 order by creeLe desc';
$res = sql_select($requete, $nbRep);
if(count($res))
{
	echo '<div class="box red">';
	display_gifts('Les cadeaux que j\'ai acheté alors que je ne les ai pas reservés avant', $res, array('reserveLe', 'titre', 'pour', 'priorite', 'reservePar'));
	echo '</div>';
}

aff_footer();
?>
