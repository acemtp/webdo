<?

function aff_commentaire($id, $pour, $visible) {
	if($pour == $_SESSION['idUtilisateur']) {
		$res = sql_select("select * from commentaire where idKdo=$id and visible=1 and supprime=0 order by creeLe", $nbl);
	} else {
		$res = sql_select("select * from commentaire where idKdo=$id and supprime=0 order by creeLe", $nbl);
	}
	for($i = 0; $i < $nbl; $i++) {
		$commentId = $res[$i]['id'];
		$creeLe = $res[$i]['creeLe'];
		$creePar = $res[$i]['creePar'];
		$creeParNom  = prenom($creePar);
		$comment = $res[$i]['commentaire'];
		echo "<div class=\"box pink comment\"><h3>Commentaire de $creeParNom</h3><p>Le $creeLe</p><br/><p>".embellir($comment)."<br/><br/></p>";
		if($creePar == $_SESSION['idUtilisateur']) echo "<p class=\"small\"><a href=\"r_supprime_commentaire.php?id=$commentId\">Supprimer ce commentaire</a></p>";
		echo "</div>";
	}

	?>

	<form name="COMMENT_FORM" method="post" action="r_ajoute_commentaire.php">
	<h3>Ajouter un commentaire</h3>
	<div>
	<input name="id" value="<? echo $id; ?>" type="hidden">
	<input name="pour" value="<? echo $pour; ?>" type="hidden">
	<textarea name="commentaire" cols="100" rows="5"></textarea><br/>
	<? if($visible) { ?>
	Est ce que ce commentaire sera lisible par <? echo prenom($pour); ?>?
	<input name="visible" value="1" type="radio"> Oui
	<input name="visible" checked="checked" value="0" type="radio"> Non<br/>
	<? } else { ?>
	<input name="visible" value="1" type="hidden">
	<? } ?>
	<input name="valider" value="Ajouter" type="submit">
	</div>
	</form>
<?
}

?>