<?
require_once('i_sql.php');

// affiche l'entete generique
function aff_header() {
	global $Theme;

	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
<head>
	<title>Webdo: Gestionnaire famillial de cadeaux</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="icon" type="image/png" href="favicon.png" />

		<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-combined.min.css" rel="stylesheet">
    <style>
	 body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
	}
    </style>
		<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-responsive.min.css" rel="stylesheet">

	<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

	<script type="text/javascript"> <!--
	function alertSup(id){
		msg = "Veux tu vraiment effacer ce cadeau ?";
		if ( confirm(msg) ) {
			window.location.replace("r_supprime_kdo.php?id="+id);
			return ;
		}
	}   //-->
	</script>
</head>
<body>


<? if(isset($_SESSION['idUtilisateur'])) { ?>
    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="home.php">Webdo</a>
          <div class="nav-collapse">
            <ul class="nav">
			<li><a href="les_kdos.php?pour=<?= $_SESSION['idUtilisateur']; ?>">Mes cadeaux</a></li>
			<li><a href="resa.php">Mes réservations et achats</a></li>
			<li><a href="kdos.php">Cadeaux des autres</a></li>
            </ul>
            <ul class="nav pull-right">
			<li><a href="">Je suis "<?= prenom_simple($_SESSION['idUtilisateur']) ?>"</a></li>
			<li><a href="profil.php">Mon profil</a></li>
			<li><a href="logout.php">Deconnexion</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
<? } ?>


<div class="container">


<? if(!isset($_SESSION['idUtilisateur'])) { ?>
 <div class="hero-unit">
  <div class="row">
  <div class="span6">
		  <img src="images/logo_<?= $Theme ?>.png" title="webdo" />
  </div>
  <div class="span4">
		   <h1>Webdo</h1>
	 <p>Gestionnaire Famillial de cadeaux</p>
  </div>
 </div>
 </div>
<? } ?>

<?
}

// affiche le bas de page generique
function aff_footer() {

?>

   <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->

		<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
		<script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
    <script src="http://twitter.github.com/bootstrap/assets/js/google-code-prettify/prettify.js"></script>

<script type="text/javascript">
		$(document).ready(function () {
				$("[rel=tooltip]").tooltip();
				$("[rel=popover]").popover();
			});
</script>

<?php


	echo "</body>\n";
	echo "</html>\n";

	if(isset($_SESSION['idUtilisateur']))
		$_SESSION['back'] = $_SERVER['REQUEST_URI'];

	unset_form_error();
}

// Verifie qu'une personne est bien identifié sinon on l'envoie sur le page de login
function verifieUtilisateur() {
	session_cache_expire(60*60*24*30);
	session_start();
	if ( ! isset($_SESSION['idUtilisateur']) ) {
		header("Location: login.php");
		exit;
	}
}

function aff_priorite($edit, $prio) {
	$prio_text = array('Doit avoir', 'Adorerais avoir', 'Aimerais avoir', 'J\'y pense', 'Suggestion');
	if($edit) {
		echo '<select name="priorite">';
		$i=1;
		foreach($prio_text as $val) {
			echo "<option value=\"$i\"";
			if($prio == $i) echo ' selected="selected"';
			echo ">".(6-$i)." étoiles - $val";
			$i++;
		}
		echo '</select>';
		echo "<br/><br/>";
	} else {
		echo '<span class="prio">';
		for($i=0; $i<6-intval($prio); $i++) echo '<img src="images/star.png" rel="tooltip" title="'.$prio_text[intval($prio)-1].'" alt="'.$prio_text[intval($prio)-1].'"/>';
		echo '</span>';
/*		$i=1;
		foreach($prio_text as $val) {
			if($i == $prio)
				echo "$i - $val<br>";
			$i++;
		}*/
	}
}

function aff_groupe($id, $edit, $value) {
	$res = sql_select("select id,nom from groupe where idMembre=$id", $nb);
	if($edit) {
		echo 'Groupe : <select name="groupe">';
		foreach($res as $val) {
			echo '<option value="'.$val['id'].'"';
			if($value == $val['id']) echo ' selected="selected"';
			echo '>'.$val['nom'];
		}
		echo '</select>';
		echo "<br/><br/>";
	} else {
		if(count($res) == 0) { echo "Aucun groupe"; }
		else {
			echo '<table class="gifts">';
			echo '<tr><th>Groupes</th></tr>';
			foreach($res as $g) echo '<tr><td>'.$g['nom'].'</td></tr>';
			echo '</table>';
		}
	}
}


// prend un texte tapp� au kilometre et le rend beau pour le browser
// remplace les \n en <br/>, remplace les url par un lien
function embellir($desc) {
	$desc = @ereg_replace("(\n|\r|^| )([a-zA-Z]+://[.a-zA-Z0-9_/?&%=-]{0,40})([.a-zA-Z0-9_/?&%=-]*)", "\\1<a target=\"_blank\" href=\"\\2\\3\">\\2</a>", $desc);
	$desc = @ereg_replace("(\n|\r|^| )(www.[.a-zA-Z0-9_/?&%=-]{0,50})([.a-zA-Z0-9_/?&%=-]*)", "\\1<a target=\"_blank\" href=\"http://\\2\\3\">\\2</a>", $desc);
	$desc = nl2br($desc);
	return $desc;
}

function get_param($key) {
	if(isset($_GET[$key])) $value = $_GET[$key];
	else if(isset($_POST[$key])) $value = $_POST[$key];
	else $value = '';
	return addslashes($value);
}

function display_form_error($field)
{
	if(isset($_SESSION[$field.'ERR']))
	{
		echo '<div class="error">'.$_SESSION[$field.'ERR'].'</div>';
	}
}

function unset_form_error()
{
	foreach($_SESSION as $key => $value)
	{
		if(substr($key, -3) == 'ERR')
		{
			unset($_SESSION[$key]);
		}
	}
}

function set_form_error($field, $text)
{
	$_SESSION[$field.'ERR'] = $text;
}

?>
