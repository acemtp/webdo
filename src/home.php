<? 
/** 
 * Page d'accueil après login - présentation du menu
 * 	nécessite l'identification
 *
 */

require_once(__DIR__.'/i_divers.php');

verifieUtilisateur();

$moi = intval($_SESSION['idUtilisateur']);
$toi = prenom($moi);

function home_percent($value, $total) {
	if(intval($total) <= 0) {
		return 0;
	}

	return intval(round((intval($value) / intval($total)) * 100));
}

function home_short_date($date) {
	if(!$date) {
		return '';
	}

	$parts = explode('-', substr($date, 0, 10));
	if(count($parts) !== 3) {
		return $date;
	}

	return $parts[2].'/'.$parts[1];
}

function home_day_label($date) {
	$map = array(
		'Mon' => 'Lun',
		'Tue' => 'Mar',
		'Wed' => 'Mer',
		'Thu' => 'Jeu',
		'Fri' => 'Ven',
		'Sat' => 'Sam',
		'Sun' => 'Dim',
	);

	$ts = strtotime($date);
	if($ts === false) {
		return '';
	}

	$key = date('D', $ts);
	return isset($map[$key]) ? $map[$key] : $key;
}

aff_header();

$res = sql_select('SELECT count(*) as nb FROM kdo WHERE supprime=0 AND pour='.$moi.' AND creePar='.$moi, $nbRep);
$nbDansMaListe = intval($res[0]['nb']);

$res = sql_select('SELECT count(*) as nb FROM kdo WHERE supprime=0 AND reservePar='.$moi.' AND achetePar is null', $nbRep);
$nbReservesEnCours = intval($res[0]['nb']);

$res = sql_select('SELECT count(*) as nb FROM kdo WHERE supprime=0 AND achetePar='.$moi, $nbRep);
$nbAchetes = intval($res[0]['nb']);

$res = sql_select('SELECT count(*) as nb FROM kdo WHERE supprime=0 AND pour='.$moi.' AND reservePar is not null', $nbRep);
$nbCadeauxReservesPourMoi = intval($res[0]['nb']);

$res = sql_select('SELECT count(*) as nb FROM kdo WHERE supprime=0 AND pour='.$moi.' AND achetePar is not null', $nbRep);
$nbCadeauxAchetesPourMoi = intval($res[0]['nb']);

$res = sql_select('SELECT count(*) as nb FROM kdo WHERE creeLe > SUBDATE(NOW(), "7 days") AND supprime=0', $nbRep);
$nbRecents = intval($res[0]['nb']);

$res = sql_select('SELECT count(*) as nb FROM kdo WHERE reserveLe > SUBDATE(NOW(), "7 days") AND supprime=0', $nbRep);
$nbReservesRecents = intval($res[0]['nb']);

$res = sql_select('SELECT count(*) as nb FROM kdo WHERE acheteLe > SUBDATE(NOW(), "7 days") AND supprime=0', $nbRep);
$nbAchetesRecents = intval($res[0]['nb']);

$res = sql_select('SELECT count(*) as nb FROM kdo WHERE supprime=0 AND pour!='.$moi, $nbRep);
$nbIdeesAutres = intval($res[0]['nb']);

$res = sql_select('SELECT count(*) as nb FROM kdo WHERE supprime=0', $nbRep);
$nbTotal = intval($res[0]['nb']);

$res = sql_select('SELECT count(*) as nb FROM commentaire WHERE supprime=0 AND creeLe > SUBDATE(NOW(), "7 days")', $nbRep);
$nbCommentairesRecents = intval($res[0]['nb']);

$res = sql_select('SELECT id, titre, creeLe, priorite FROM kdo WHERE supprime=0 AND pour='.$moi.' AND creePar='.$moi.' ORDER BY priorite asc, creeLe desc LIMIT 3', $nbRep);
$mesPriorites = $res;

$res = sql_select('SELECT id, titre, pour, creeLe FROM kdo WHERE supprime=0 AND pour!='.$moi.' ORDER BY creeLe desc LIMIT 4', $nbRep);
$ideesRecentes = $res;

$res = sql_select('SELECT id, titre, pour, reserveLe FROM kdo WHERE supprime=0 AND reservePar='.$moi.' AND achetePar is null ORDER BY reserveLe desc LIMIT 3', $nbRep);
$mesResas = $res;

$prioStats = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0);
$res = sql_select('SELECT priorite, count(*) as nb FROM kdo WHERE supprime=0 AND pour='.$moi.' AND creePar='.$moi.' GROUP BY priorite ORDER BY priorite asc', $nbRep);
foreach($res as $row) {
	$prio = intval($row['priorite']);
	if(isset($prioStats[$prio])) {
		$prioStats[$prio] = intval($row['nb']);
	}
}

$days = array();
for($i = 6; $i >= 0; $i--) {
	$date = date('Y-m-d', strtotime('-'.$i.' days'));
	$days[$date] = array(
		'label' => home_day_label($date),
		'creates' => 0,
		'reservations' => 0,
		'purchases' => 0,
	);
}

$res = sql_select('SELECT creeLe as day, count(*) as nb FROM kdo WHERE supprime=0 AND creeLe > SUBDATE(NOW(), "7 days") GROUP BY creeLe', $nbRep);
foreach($res as $row) {
	$day = substr($row['day'], 0, 10);
	if(isset($days[$day])) {
		$days[$day]['creates'] = intval($row['nb']);
	}
}

$res = sql_select('SELECT reserveLe as day, count(*) as nb FROM kdo WHERE supprime=0 AND reserveLe > SUBDATE(NOW(), "7 days") GROUP BY reserveLe', $nbRep);
foreach($res as $row) {
	$day = substr($row['day'], 0, 10);
	if(isset($days[$day])) {
		$days[$day]['reservations'] = intval($row['nb']);
	}
}

$res = sql_select('SELECT acheteLe as day, count(*) as nb FROM kdo WHERE supprime=0 AND acheteLe > SUBDATE(NOW(), "7 days") GROUP BY acheteLe', $nbRep);
foreach($res as $row) {
	$day = substr($row['day'], 0, 10);
	if(isset($days[$day])) {
		$days[$day]['purchases'] = intval($row['nb']);
	}
}

$maxActivity = 1;
foreach($days as $day) {
	$maxActivity = max($maxActivity, $day['creates'], $day['reservations'], $day['purchases']);
}

$overviewRows = array(
	array('label' => 'Mes cadeaux', 'mine' => $nbDansMaListe, 'reserved' => $nbCadeauxReservesPourMoi, 'bought' => $nbCadeauxAchetesPourMoi),
	array('label' => 'Mes suivis', 'mine' => $nbReservesEnCours + $nbAchetes, 'reserved' => $nbReservesEnCours, 'bought' => $nbAchetes),
	array('label' => 'Famille', 'mine' => $nbTotal, 'reserved' => $nbReservesRecents, 'bought' => $nbAchetesRecents),
);

$pctReservesPourMoi = home_percent($nbCadeauxReservesPourMoi, $nbDansMaListe);
$pctAchetesPourMoi = home_percent($nbCadeauxAchetesPourMoi, $nbDansMaListe);
$pctAchetesParMoi = home_percent($nbAchetes, max($nbAchetes + $nbReservesEnCours, 1));

echo '<div class="box dashboard-hero">';
echo '<div>';
echo '<h2>Bienvenue '.$toi.' !</h2>';
echo '<p>Le tableau de bord rassemble ce qui bouge dans les listes, les cadeaux a surveiller et les raccourcis utiles pour preparer les prochaines surprises.</p>';
echo '<div class="dashboard-badges">';
echo '<span class="dashboard-badge">'.$nbRecents.' nouvelles idees cette semaine</span>';
echo '<span class="dashboard-badge">'.$nbReservesRecents.' reservations recentes</span>';
echo '<span class="dashboard-badge">'.$nbCommentairesRecents.' commentaires en 7 jours</span>';
echo '</div>';
echo '</div>';
echo '<div class="dashboard-hero-side">';
echo '<span class="dashboard-kpi-label">Panorama famille</span>';
echo '<span class="dashboard-kpi-value">'.$nbTotal.'</span>';
echo '<p class="dashboard-kpi-note">cadeaux actifs dans Webdo, dont <strong>'.$nbIdeesAutres.'</strong> idees a explorer chez les autres membres et <strong>'.$nbCadeauxReservesPourMoi.'</strong> deja reperes pour toi.</p>';
echo '</div>';
echo '</div>';

echo '<div class="dashboard-grid">';

echo '<div class="dashboard-panel dashboard-wide-panel">';
echo '<h3>Vue d\'ensemble</h3>';
echo '<p class="dashboard-panel-intro">Un mix de graphiques et de tableau pour voir comment les listes evoluent cette semaine.</p>';
echo '<div class="dashboard-chart-grid">';
echo '<div class="dashboard-chart-card">';
echo '<span class="dashboard-chart-title">Activite sur 7 jours</span>';
echo '<div class="dashboard-bars">';
foreach($days as $day) {
	$createHeight = $day['creates'] > 0 ? max(8, intval(round(($day['creates'] / $maxActivity) * 100))) : 0;
	$resaHeight = $day['reservations'] > 0 ? max(8, intval(round(($day['reservations'] / $maxActivity) * 100))) : 0;
	$buyHeight = $day['purchases'] > 0 ? max(8, intval(round(($day['purchases'] / $maxActivity) * 100))) : 0;
	echo '<div class="dashboard-bar-col">';
	echo '<span class="dashboard-bar-value">'.$day['creates'].' / '.$day['reservations'].' / '.$day['purchases'].'</span>';
	echo '<div class="dashboard-bar-track">';
	if($buyHeight > 0) echo '<span class="dashboard-bar purchases" style="height: '.$buyHeight.'%;"></span>';
	if($resaHeight > 0) echo '<span class="dashboard-bar reservations" style="height: '.$resaHeight.'%;"></span>';
	if($createHeight > 0) echo '<span class="dashboard-bar creates" style="height: '.$createHeight.'%;"></span>';
	echo '</div>';
	echo '<span class="dashboard-bar-label">'.$day['label'].'</span>';
	echo '</div>';
}
echo '</div>';
echo '<div class="dashboard-legend">';
echo '<span class="dashboard-legend-item"><span class="dashboard-legend-swatch creates"></span>Idees ajoutees</span>';
echo '<span class="dashboard-legend-item"><span class="dashboard-legend-swatch reservations"></span>Reservations</span>';
echo '<span class="dashboard-legend-item"><span class="dashboard-legend-swatch purchases"></span>Achats</span>';
echo '</div>';
echo '</div>';
echo '<div class="dashboard-chart-card">';
echo '<span class="dashboard-chart-title">Poids de mes priorites</span>';
echo '<div class="dashboard-stacked-list">';
for($prio = 1; $prio <= 5; $prio++) {
	$count = $prioStats[$prio];
	$stars = 6 - $prio;
	$share = $nbDansMaListe > 0 ? max(6, home_percent($count, $nbDansMaListe)) : 0;
	echo '<div class="dashboard-stacked-item">';
	echo '<div class="dashboard-stacked-top"><span class="dashboard-stacked-label">'.$stars.' etoiles</span><span class="dashboard-stacked-value">'.$count.' cadeau'.($count > 1 ? 'x' : '').'</span></div>';
	echo '<div class="dashboard-progress"><span style="width: '.$share.'%;"></span></div>';
	echo '</div>';
}
echo '</div>';
echo '</div>';
echo '</div>';
echo '<div class="dashboard-chart-card" style="margin-top:18px;">';
echo '<span class="dashboard-chart-title">Tableau de synthese</span>';
echo '<table class="dashboard-table">';
echo '<tr><th>Bloc</th><th>Base</th><th>Reserves</th><th>Achetes</th></tr>';
foreach($overviewRows as $row) {
	echo '<tr>';
	echo '<td>'.$row['label'].'</td>';
	echo '<td><span class="dashboard-pill">'.$row['mine'].'</span></td>';
	echo '<td>'.$row['reserved'].'</td>';
	echo '<td>'.$row['bought'].'</td>';
	echo '</tr>';
}
echo '</table>';
echo '</div>';
echo '</div>';

echo '<div class="dashboard-panel">';
echo '<h3>Mes raccourcis</h3>';
echo '<p class="dashboard-panel-intro">Les actions et compteurs qui servent tout de suite.</p>';
echo '<div class="dashboard-action-grid">';
echo '<a class="dashboard-action-card" href="les_kdos.php?pour='.$moi.'"><span class="dashboard-action-eyebrow">Ma liste</span><span class="dashboard-action-value">'.$nbDansMaListe.'</span><span class="dashboard-action-title">Cadeaux ajoutes pour moi</span><span class="dashboard-action-text">Retrouver mes envies et ajuster mes priorites.</span></a>';
echo '<a class="dashboard-action-card" href="resa.php"><span class="dashboard-action-eyebrow">Reservations</span><span class="dashboard-action-value">'.$nbReservesEnCours.'</span><span class="dashboard-action-title">Cadeaux reserves en attente</span><span class="dashboard-action-text">Voir ce qu\'il reste a acheter ou a suivre.</span></a>';
echo '<a class="dashboard-action-card" href="resa.php"><span class="dashboard-action-eyebrow">Achats</span><span class="dashboard-action-value">'.$nbAchetes.'</span><span class="dashboard-action-title">Cadeaux deja achetes</span><span class="dashboard-action-text">Garder un oeil sur ce qui est boucle.</span></a>';
echo '<a class="dashboard-action-card" href="kdos.php"><span class="dashboard-action-eyebrow">Inspiration</span><span class="dashboard-action-value">'.$nbIdeesAutres.'</span><span class="dashboard-action-title">Idees a parcourir chez les autres</span><span class="dashboard-action-text">Parfait pour trouver vite une bonne piste.</span></a>';
echo '</div>';
echo '</div>';

echo '<div class="dashboard-panel">';
echo '<h3>Le rythme du moment</h3>';
echo '<p class="dashboard-panel-intro">Une lecture simple de ce qui avance dans les listes.</p>';
echo '<div class="dashboard-trend-list">';
echo '<div class="dashboard-trend-item"><div class="dashboard-trend-top"><span class="dashboard-trend-label">Cadeaux deja reserves pour moi</span><span class="dashboard-trend-meta">'.$nbCadeauxReservesPourMoi.' sur '.$nbDansMaListe.'</span></div><div class="dashboard-progress"><span style="width: '.$pctReservesPourMoi.'%;"></span></div></div>';
echo '<div class="dashboard-trend-item"><div class="dashboard-trend-top"><span class="dashboard-trend-label">Cadeaux deja achetes pour moi</span><span class="dashboard-trend-meta">'.$nbCadeauxAchetesPourMoi.' sur '.$nbDansMaListe.'</span></div><div class="dashboard-progress"><span style="width: '.$pctAchetesPourMoi.'%;"></span></div></div>';
echo '<div class="dashboard-trend-item"><div class="dashboard-trend-top"><span class="dashboard-trend-label">Ce que j\'ai mene jusqu\'a l\'achat</span><span class="dashboard-trend-meta">'.$pctAchetesParMoi.'% de mes suivis</span></div><div class="dashboard-progress"><span style="width: '.$pctAchetesParMoi.'%;"></span></div></div>';
echo '</div>';
echo '<div class="dashboard-panel-footer"><a class="dashboard-link" href="resa.php">Voir toutes mes reservations et achats</a></div>';
echo '</div>';

echo '<div class="dashboard-panel">';
echo '<h3>Les cadeaux a surveiller</h3>';
echo '<p class="dashboard-panel-intro">Les priorites les plus fortes dans ma liste.</p>';
echo '<div class="dashboard-list">';
if(count($mesPriorites) === 0) {
	echo '<p class="dashboard-empty">Aucun cadeau dans ta liste pour le moment. Tu peux en ajouter un en quelques secondes.</p>';
} else {
	foreach($mesPriorites as $gift) {
		$prioLabel = 6 - intval($gift['priorite']);
		echo '<a class="dashboard-list-card" href="kdo.php?id='.$gift['id'].'">';
		echo '<h4>'.h($gift['titre']).'</h4>';
		echo '<p class="dashboard-list-meta"><strong>'.$prioLabel.' etoiles</strong> • ajoute le '.home_short_date($gift['creeLe']).'</p>';
		echo '</a>';
	}
}
echo '</div>';
echo '<div class="dashboard-panel-footer"><a class="dashboard-link" href="les_kdos.php?pour='.$moi.'">Ouvrir ma liste complete</a></div>';
echo '</div>';

echo '<div class="dashboard-panel">';
echo '<h3>Ca bouge chez les autres</h3>';
echo '<p class="dashboard-panel-intro">Les idees les plus recentes a consulter pour trouver l\'inspiration.</p>';
echo '<div class="dashboard-list">';
if(count($ideesRecentes) === 0) {
	echo '<p class="dashboard-empty">Aucune idee recente a afficher pour l\'instant.</p>';
} else {
	foreach($ideesRecentes as $gift) {
		echo '<a class="dashboard-list-card" href="kdo.php?id='.$gift['id'].'">';
		echo '<h4>'.h($gift['titre']).'</h4>';
		echo '<p class="dashboard-list-meta">Pour <strong>'.h(prenom($gift['pour'])).'</strong> • ajoute le '.home_short_date($gift['creeLe']).'</p>';
		echo '</a>';
	}
}
echo '</div>';
echo '<div class="dashboard-panel-footer"><a class="dashboard-link" href="kdos.php">Parcourir toutes les listes</a></div>';
echo '</div>';

echo '<div class="dashboard-panel">';
echo '<h3>Mes suivis en cours</h3>';
echo '<p class="dashboard-panel-intro">Les reservations encore ouvertes qui meritent un petit check.</p>';
echo '<div class="dashboard-mini-grid">';
echo '<div class="dashboard-mini-card"><span class="dashboard-mini-eyebrow">Cette semaine</span><span class="dashboard-mini-value">'.$nbAchetesRecents.'</span><span class="dashboard-mini-title">Achats confirmes</span><span class="dashboard-mini-text">Les cadeaux vraiment boucles sur les 7 derniers jours.</span></div>';
echo '<div class="dashboard-mini-card"><span class="dashboard-mini-eyebrow">Cette semaine</span><span class="dashboard-mini-value">'.$nbReservesRecents.'</span><span class="dashboard-mini-title">Reservations prises</span><span class="dashboard-mini-text">Un bon signal pour voir si les listes avancent.</span></div>';
echo '</div>';
echo '<div class="dashboard-list" style="margin-top:14px;">';
if(count($mesResas) === 0) {
	echo '<p class="dashboard-empty">Tu n\'as aucune reservation en attente pour le moment.</p>';
} else {
	foreach($mesResas as $gift) {
		echo '<a class="dashboard-list-card" href="kdo.php?id='.$gift['id'].'">';
		echo '<h4>'.h($gift['titre']).'</h4>';
		echo '<p class="dashboard-list-meta">Pour <strong>'.h(prenom($gift['pour'])).'</strong> • reserve le '.home_short_date($gift['reserveLe']).'</p>';
		echo '</a>';
	}
}
echo '</div>';
echo '</div>';

echo '</div>';

aff_footer();
?>
