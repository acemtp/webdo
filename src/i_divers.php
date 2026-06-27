<?
require_once(__DIR__.'/i_sql.php');

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
	<link rel="preconnect" href="https://fonts.googleapis.com" />
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="crossorigin" />
	<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Patrick+Hand&display=swap" rel="stylesheet" />

		<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-combined.min.css" rel="stylesheet">
    <style>
	 :root {
		--bg-top: #fff7ec;
		--bg-bottom: #ffe4df;
		--surface: rgba(255,255,255,0.88);
		--surface-strong: #ffffff;
		--ink: #4e3b34;
		--ink-soft: #7b625a;
		--accent: #ee7a5f;
		--accent-dark: #d45e45;
		--accent-gold: #f0bf62;
		--line: rgba(116, 78, 68, 0.12);
		--shadow: 0 20px 45px rgba(122, 82, 62, 0.16);
		--shadow-soft: 0 10px 24px rgba(122, 82, 62, 0.10);
	 }
	 body {
        padding-top: 88px;
		background:
			radial-gradient(circle at top left, rgba(255,255,255,0.95), rgba(255,255,255,0) 36%),
			linear-gradient(180deg, var(--bg-top), var(--bg-bottom));
		color: var(--ink);
		font-family: 'Nunito', sans-serif;
		min-height: 100vh;
	}
	 .container {
		max-width: 1120px;
	}
	 .navbar-fixed-top .navbar-inner {
		background: rgba(255, 252, 248, 0.88);
		backdrop-filter: blur(14px);
		border: 0;
		box-shadow: 0 8px 28px rgba(112, 78, 62, 0.10);
	}
	 .navbar .brand {
		font-family: 'Patrick Hand', cursive;
		font-size: 34px;
		color: var(--accent-dark);
		text-shadow: none;
		padding-top: 16px;
	}
	 .navbar .nav > li > a,
	 .navbar .nav.pull-right > li > a {
		color: var(--ink);
		font-weight: 700;
		text-shadow: none;
		padding-top: 20px;
		padding-bottom: 20px;
	}
	 .navbar .nav > li > a:hover,
	 .navbar .nav.pull-right > li > a:hover {
		color: var(--accent-dark);
		background: transparent;
	}
	 .navbar .btn-navbar {
		background: linear-gradient(180deg, #ff9f86, #ef7b61);
		border: 0;
		border-radius: 999px;
		box-shadow: 0 10px 20px rgba(239, 123, 97, 0.24);
		margin-top: 14px;
		margin-right: 8px;
		padding: 10px 14px;
	}
	 .navbar .btn-navbar:hover {
		background: linear-gradient(180deg, #f68d71, #e0684d);
	}
	 .navbar .btn-navbar .icon-bar {
		background: #fff;
		box-shadow: none;
		height: 3px;
		border-radius: 999px;
		width: 20px;
	}
	 .app-shell {
		padding-bottom: 48px;
	}
	 .hero-unit,
	 .box {
		background: var(--surface);
		border: 1px solid rgba(255,255,255,0.65);
		box-shadow: var(--shadow);
		border-radius: 28px;
	}
	 .hero-unit {
		padding: 34px 36px;
		margin-bottom: 28px;
	}
	 .hero-unit h1 {
		font-family: 'Patrick Hand', cursive;
		font-size: 64px;
		line-height: 1;
		margin-bottom: 10px;
		color: var(--accent-dark);
		text-shadow: none;
	}
	 .hero-unit p {
		color: var(--ink-soft);
		font-size: 20px;
		margin-bottom: 0;
	}
	 h1, h2, h3 {
		color: var(--ink);
		text-shadow: none;
		font-weight: 800;
	}
	 h2 {
		font-size: 34px;
		letter-spacing: -0.02em;
	}
	 a {
		color: var(--accent-dark);
		text-shadow: none;
	}
	 a:hover {
		color: #b94d35;
		text-decoration: none;
	}
	 .box {
		padding: 24px 26px;
		margin-bottom: 22px;
	}
	 .box > * {
		margin-left: 0;
		margin-right: 0;
	}
	 .box.pink,
	 .box.violet {
		background:
			linear-gradient(180deg, rgba(255,255,255,0.94), rgba(255,248,243,0.95)),
			url("/bg_logo_annif.png") no-repeat right -16px bottom -18px / 180px;
		color: var(--ink);
	}
	 .btn,
	 input[type="submit"],
	 button {
		background: linear-gradient(180deg, #ff9f86, #ef7b61);
		border: 0;
		border-radius: 999px;
		box-shadow: 0 10px 20px rgba(239, 123, 97, 0.24);
		color: #fff;
		font-weight: 800;
		padding: 10px 18px;
		text-shadow: none;
	}
	 .btn:hover,
	 input[type="submit"]:hover,
	 button:hover {
		background: linear-gradient(180deg, #f68d71, #e0684d);
		color: #fff;
	}
	 input[type="text"],
	 input[type="password"],
	 input[type="file"],
	 select,
	 textarea {
		background: rgba(255,255,255,0.95);
		border: 1px solid var(--line);
		border-radius: 16px;
		box-shadow: inset 0 1px 2px rgba(80, 53, 44, 0.05);
		color: var(--ink);
		padding: 10px 12px;
	}
	 textarea {
		min-height: 120px;
	}
	 .table,
	 .gifts {
		background: rgba(255,255,255,0.82);
		border-radius: 20px;
		overflow: hidden;
		box-shadow: var(--shadow-soft);
	}
	 .table th,
	 .gifts th {
		background: #fff1dc;
		color: var(--ink);
		border-bottom: 1px solid var(--line);
	}
	 .table td,
	 .table th,
	 .gifts td,
	 .gifts th {
		padding: 14px 16px;
	}
	 .table th {
		white-space: nowrap;
	}
	 .table td.nw {
		white-space: nowrap;
	}
	 .table-striped tbody tr:nth-child(odd) td,
	 .table-striped tbody tr:nth-child(odd) th {
		background-color: rgba(255, 249, 243, 0.86);
	}
	 .thumbnail,
	 .tb,
	 .tbp {
		border-radius: 22px;
		box-shadow: var(--shadow-soft);
	}
	 .tbp {
		max-height: 400px;
		width: auto;
		object-fit: contain;
	 }
	 .error {
		background: rgba(244, 88, 88, 0.10);
		border: 1px solid rgba(244, 88, 88, 0.2);
		border-radius: 16px;
		color: #b54141;
		font-weight: 700;
		padding: 12px 14px;
	}
	 .label {
		background: #ffe2a8;
		border-radius: 999px;
		color: #8a5b00;
		padding: 4px 10px;
		text-shadow: none;
	}
	 .stacked-form p {
		margin-bottom: 16px;
	}
	 .stacked-form label {
		display: block;
		font-weight: 700;
		margin-bottom: 6px;
	}
	 .stacked-form input[type="text"],
	 .stacked-form input[type="password"],
	 .stacked-form input[type="file"],
	 .stacked-form select,
	 .stacked-form textarea {
		box-sizing: border-box;
		display: block;
		width: 100%;
	}
	 .stacked-form .priority-select {
		line-height: 1.4;
		min-height: 48px;
		padding-top: 12px;
		padding-bottom: 12px;
	}
	 .home-stat-grid {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
		gap: 16px;
		margin-top: 22px;
	}
	 .home-stat-card {
		background: rgba(255,255,255,0.82);
		border: 1px solid rgba(255,255,255,0.7);
		border-radius: 22px;
		box-shadow: var(--shadow-soft);
		padding: 18px;
	}
	 .home-stat-card strong {
		display: block;
		font-size: 32px;
		line-height: 1;
		margin-bottom: 8px;
		color: var(--accent-dark);
	}
	 .home-stat-card span {
		color: var(--ink-soft);
		font-size: 15px;
		font-weight: 700;
	}
	 .dashboard-hero {
		background:
			linear-gradient(135deg, rgba(255,255,255,0.96), rgba(255,246,238,0.92)),
			radial-gradient(circle at top right, rgba(240,191,98,0.22), rgba(240,191,98,0) 32%);
		display: grid;
		gap: 24px;
		grid-template-columns: minmax(0, 1.5fr) minmax(280px, 0.9fr);
		padding: 30px;
	}
	 .dashboard-hero h2 {
		font-size: 52px;
		line-height: 0.95;
		margin-bottom: 14px;
	}
	 .dashboard-hero p {
		color: var(--ink-soft);
		font-size: 19px;
		line-height: 1.6;
		margin-bottom: 0;
		max-width: 640px;
	}
	 .dashboard-badges {
		display: flex;
		flex-wrap: wrap;
		gap: 10px;
		margin-top: 20px;
	}
	 .dashboard-badge {
		background: rgba(255,255,255,0.9);
		border: 1px solid rgba(212, 94, 69, 0.12);
		border-radius: 999px;
		color: var(--ink);
		font-size: 14px;
		font-weight: 800;
		padding: 8px 14px;
	}
	 .dashboard-hero-side {
		background: linear-gradient(180deg, #fff6e7, #fffdf8);
		border: 1px solid rgba(240,191,98,0.28);
		border-radius: 26px;
		box-shadow: inset 0 1px 0 rgba(255,255,255,0.8);
		padding: 22px;
	}
	 .dashboard-kpi-label {
		color: var(--ink-soft);
		display: block;
		font-size: 13px;
		font-weight: 800;
		letter-spacing: 0.08em;
		margin-bottom: 8px;
		text-transform: uppercase;
	}
	 .dashboard-kpi-value {
		color: var(--accent-dark);
		display: block;
		font-size: 54px;
		font-weight: 800;
		line-height: 1;
		margin-bottom: 12px;
	}
	 .dashboard-kpi-note {
		color: var(--ink-soft);
		font-size: 15px;
		line-height: 1.5;
		margin-bottom: 0;
	}
	 .dashboard-grid {
		display: grid;
		gap: 20px;
		grid-template-columns: minmax(0, 1.3fr) minmax(0, 0.9fr);
		margin-top: 22px;
	}
	 .dashboard-panel {
		background: rgba(255,255,255,0.82);
		border: 1px solid rgba(255,255,255,0.72);
		border-radius: 28px;
		box-shadow: var(--shadow-soft);
		padding: 24px;
	}
	 .dashboard-panel h3 {
		font-size: 26px;
		margin-bottom: 8px;
	}
	 .dashboard-panel-intro {
		color: var(--ink-soft);
		font-size: 15px;
		margin-bottom: 18px;
	}
	 .dashboard-action-grid,
	 .dashboard-mini-grid {
		display: grid;
		gap: 14px;
		grid-template-columns: repeat(2, minmax(0, 1fr));
	}
	 .dashboard-action-card,
	 .dashboard-mini-card {
		background: linear-gradient(180deg, rgba(255,255,255,0.98), rgba(255,249,244,0.96));
		border: 1px solid rgba(116, 78, 68, 0.08);
		border-radius: 22px;
		display: block;
		min-height: 126px;
		padding: 18px 18px 16px;
	}
	 .dashboard-action-card:hover,
	 .dashboard-mini-card:hover {
		box-shadow: 0 16px 32px rgba(122, 82, 62, 0.14);
		transform: translateY(-1px);
	}
	 .dashboard-action-eyebrow,
	 .dashboard-mini-eyebrow {
		color: var(--ink-soft);
		display: block;
		font-size: 12px;
		font-weight: 800;
		letter-spacing: 0.08em;
		margin-bottom: 10px;
		text-transform: uppercase;
	}
	 .dashboard-action-value,
	 .dashboard-mini-value {
		color: var(--accent-dark);
		display: block;
		font-size: 38px;
		font-weight: 800;
		line-height: 1;
		margin-bottom: 10px;
	}
	 .dashboard-action-title,
	 .dashboard-mini-title {
		color: var(--ink);
		display: block;
		font-size: 18px;
		font-weight: 800;
		line-height: 1.3;
	}
	 .dashboard-action-text,
	 .dashboard-mini-text {
		color: var(--ink-soft);
		display: block;
		font-size: 14px;
		line-height: 1.45;
		margin-top: 6px;
	}
	 .dashboard-trend-list {
		display: grid;
		gap: 16px;
	}
	 .dashboard-trend-item {
		background: rgba(255,248,243,0.78);
		border: 1px solid rgba(116, 78, 68, 0.07);
		border-radius: 20px;
		padding: 16px 18px;
	}
	 .dashboard-trend-top {
		align-items: baseline;
		display: flex;
		gap: 12px;
		justify-content: space-between;
		margin-bottom: 10px;
	}
	 .dashboard-trend-label {
		color: var(--ink);
		font-size: 16px;
		font-weight: 800;
	}
	 .dashboard-trend-meta {
		color: var(--ink-soft);
		font-size: 14px;
		font-weight: 700;
	}
	 .dashboard-progress {
		background: rgba(116, 78, 68, 0.10);
		border-radius: 999px;
		height: 12px;
		overflow: hidden;
	}
	 .dashboard-progress > span {
		background: linear-gradient(90deg, #f2ba59, #ee7a5f);
		border-radius: inherit;
		display: block;
		height: 100%;
	}
	 .dashboard-list {
		display: grid;
		gap: 14px;
	}
	 .dashboard-list-card {
		background: linear-gradient(180deg, rgba(255,255,255,0.96), rgba(255,249,244,0.94));
		border: 1px solid rgba(116, 78, 68, 0.08);
		border-radius: 20px;
		display: block;
		padding: 16px 18px;
	}
	 .dashboard-list-card h4 {
		color: var(--ink);
		font-size: 18px;
		font-weight: 800;
		line-height: 1.35;
		margin: 0 0 6px;
		text-transform: none;
		letter-spacing: 0;
	}
	 .dashboard-list-meta,
	 .dashboard-empty {
		color: var(--ink-soft);
		font-size: 14px;
		line-height: 1.5;
		margin: 0;
	}
	 .dashboard-list-meta strong {
		color: var(--accent-dark);
	}
	 .dashboard-panel-footer {
		margin-top: 18px;
	}
	 .dashboard-link {
		font-size: 14px;
		font-weight: 800;
	}
	 .dashboard-wide-panel {
		grid-column: 1 / -1;
	}
	 .dashboard-chart-grid {
		display: grid;
		gap: 18px;
		grid-template-columns: minmax(0, 1.1fr) minmax(280px, 0.9fr);
	}
	 .dashboard-chart-card {
		background: linear-gradient(180deg, rgba(255,255,255,0.98), rgba(255,249,244,0.94));
		border: 1px solid rgba(116, 78, 68, 0.08);
		border-radius: 22px;
		padding: 18px;
	}
	 .dashboard-chart-title {
		color: var(--ink);
		display: block;
		font-size: 18px;
		font-weight: 800;
		margin-bottom: 14px;
	}
	 .dashboard-bars {
		align-items: end;
		display: grid;
		gap: 14px;
		grid-template-columns: repeat(7, minmax(0, 1fr));
		height: 190px;
		overflow: hidden;
	}
	 .dashboard-bar-col {
		align-items: center;
		display: flex;
		flex-direction: column;
		gap: 8px;
		height: 100%;
		justify-content: flex-end;
		min-width: 0;
	}
	 .dashboard-bar-value {
		color: var(--ink-soft);
		font-size: 11px;
		font-weight: 800;
		line-height: 1.2;
		text-align: center;
	}
	 .dashboard-bar-track {
		align-items: end;
		background: rgba(116, 78, 68, 0.08);
		border-radius: 18px;
		display: flex;
		flex-direction: column-reverse;
		height: 120px;
		justify-content: flex-start;
		overflow: hidden;
		padding: 6px;
		width: 100%;
	}
	 .dashboard-bar {
		border-radius: 14px;
		display: block;
		margin-top: 3px;
		min-height: 8px;
		width: 100%;
	}
	 .dashboard-bar.creates {
		background: linear-gradient(180deg, #ffb36b, #ef7b61);
	}
	 .dashboard-bar.reservations {
		background: linear-gradient(180deg, #ffd86e, #f2ba59);
	}
	 .dashboard-bar.purchases {
		background: linear-gradient(180deg, #8ed3b2, #57b58f);
	}
	 .dashboard-bar-label {
		color: var(--ink-soft);
		font-size: 12px;
		font-weight: 800;
		text-transform: uppercase;
	}
	 .dashboard-stacked-list {
		display: grid;
		gap: 14px;
	}
	 .dashboard-stacked-item {
		display: grid;
		gap: 8px;
	}
	 .dashboard-stacked-top {
		display: flex;
		font-size: 14px;
		font-weight: 800;
		justify-content: space-between;
	}
	 .dashboard-stacked-label {
		color: var(--ink);
	}
	 .dashboard-stacked-value {
		color: var(--ink-soft);
	}
	 .dashboard-stacked-bar {
		background: rgba(116, 78, 68, 0.08);
		border-radius: 999px;
		display: flex;
		height: 14px;
		overflow: hidden;
	}
	 .dashboard-stacked-bar span {
		display: block;
		height: 100%;
	}
	 .dashboard-stacked-bar .segment-own {
		background: linear-gradient(90deg, #ef7b61, #f39b7c);
	}
	 .dashboard-stacked-bar .segment-reserved {
		background: linear-gradient(90deg, #f2ba59, #f8d67b);
	}
	 .dashboard-stacked-bar .segment-bought {
		background: linear-gradient(90deg, #57b58f, #8ed3b2);
	}
	 .dashboard-table {
		border-collapse: separate;
		border-spacing: 0;
		width: 100%;
	}
	 .dashboard-table th,
	 .dashboard-table td {
		border-bottom: 1px solid rgba(116, 78, 68, 0.09);
		font-size: 14px;
		padding: 12px 8px;
		text-align: left;
	}
	 .dashboard-table th {
		color: var(--ink-soft);
		font-size: 12px;
		font-weight: 800;
		letter-spacing: 0.06em;
		text-transform: uppercase;
	}
	 .dashboard-table td:last-child,
	 .dashboard-table th:last-child {
		text-align: right;
	}
	 .dashboard-table tr:last-child td {
		border-bottom: 0;
	}
	 .dashboard-pill {
		background: rgba(255,226,168,0.65);
		border-radius: 999px;
		color: #8a5b00;
		display: inline-block;
		font-size: 12px;
		font-weight: 800;
		padding: 4px 9px;
	}
	 .dashboard-legend {
		display: flex;
		flex-wrap: wrap;
		gap: 10px 16px;
		margin-top: 14px;
	}
	 .dashboard-legend-item {
		align-items: center;
		color: var(--ink-soft);
		display: inline-flex;
		font-size: 13px;
		font-weight: 700;
		gap: 8px;
	}
	 .dashboard-legend-swatch {
		border-radius: 999px;
		display: inline-block;
		height: 10px;
		width: 10px;
	}
	 .dashboard-legend-swatch.creates { background: #ef7b61; }
	 .dashboard-legend-swatch.reservations { background: #f2ba59; }
	 .dashboard-legend-swatch.purchases { background: #57b58f; }
	 .profile-summary {
		align-items: center;
		display: grid;
		gap: 28px;
		grid-template-columns: 220px minmax(0, 1fr);
	}
	 .profile-summary-media {
		text-align: center;
	}
	 .profile-summary-media .tbp {
		display: inline-block;
		max-height: 220px;
		max-width: 100%;
	}
	 .profile-summary-copy h2 {
		margin-bottom: 14px;
	}
	 .profile-summary-copy p {
		color: var(--ink-soft);
		font-size: 17px;
		line-height: 1.7;
		margin-bottom: 0;
	}
	 .emoji-icon {
		display: inline-block;
		font-size: 1.1em;
		line-height: 1;
		vertical-align: -0.05em;
	}
	 .emoji-icon.with-gap {
		margin-right: 0.35em;
	}
	 .sort-indicator {
		color: #6fa8ff;
		white-space: nowrap;
		display: inline-block;
		font-size: 12px;
		font-weight: 800;
		margin-right: 6px;
		vertical-align: middle;
	}
	 .prio .emoji-icon {
		margin-right: 2px;
	}
	 @media (max-width: 767px) {
		body { padding-top: 0; }
		.navbar-fixed-top { position: static; margin-bottom: 16px; }
		.hero-unit { padding: 24px; }
		.hero-unit h1 { font-size: 48px; }
		.box { padding: 20px; }
		.profile-summary {
			grid-template-columns: 1fr;
			text-align: left;
		}
		.profile-summary-media {
			text-align: left;
		}
		.dashboard-hero,
		.dashboard-grid {
			grid-template-columns: 1fr;
		}
		.dashboard-hero h2 {
			font-size: 42px;
		}
		.dashboard-action-grid,
		.dashboard-mini-grid,
		.dashboard-chart-grid {
			grid-template-columns: 1fr;
		}
	 }
    </style>
		<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-responsive.min.css" rel="stylesheet">

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
			<? if(est_admin()) { ?><li><a href="admin.php">Admin</a></li><? } ?>
			<li><a href="profil.php">Mon profil</a></li>
			<li><a href="logout.php">Deconnexion</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
<? } ?>


<div class="container app-shell">


<? if(!isset($_SESSION['idUtilisateur'])) { ?>
 <div class="hero-unit">
  <div class="row">
  <div class="span6">
		  <img src="images/logo_<?= $Theme ?>.png" title="webdo" />
  </div>
  <div class="span4">
		   <h1>Webdo</h1>
	 <p>Le coin douceur pour preparer les cadeaux de la famille.</p>
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

		<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
		<script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/js/bootstrap.min.js"></script>

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

function est_admin() {
	if(!isset($_SESSION['idUtilisateur'])) {
		return false;
	}

	$res = sql_select("select admin from membre where id=".$_SESSION['idUtilisateur'], $nbRep);
	return $nbRep == 1 && intval($res[0]['admin']) == 1;
}

function verifieAdmin() {
	verifieUtilisateur();
	if(!est_admin()) {
		header("Location: home.php");
		exit;
	}
}

function aff_priorite($edit, $prio) {
	$prio_text = array('Doit avoir', 'Adorerais avoir', 'Aimerais avoir', 'J\'y pense', 'Suggestion');
	if($edit) {
		echo '<select class="priority-select" name="priorite">';
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
		for($i=0; $i<6-intval($prio); $i++) echo '<span class="emoji-icon" rel="tooltip" title="'.$prio_text[intval($prio)-1].'" aria-hidden="true">⭐</span>';
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
	$desc = preg_replace('/(\n|\r|^| )([a-zA-Z]+:\/\/[.a-zA-Z0-9_\/?&%=-]{0,40})([.a-zA-Z0-9_\/?&%=-]*)/', '\\1<a target="_blank" href="\\2\\3">\\2</a>', $desc);
	$desc = preg_replace('/(\n|\r|^| )(www\.[.a-zA-Z0-9_\/?&%=-]{0,50})([.a-zA-Z0-9_\/?&%=-]*)/', '\\1<a target="_blank" href="http://\\2\\3">\\2</a>', $desc);
	$desc = nl2br($desc);
	return $desc;
}

function get_param($key) {
	if(isset($_GET[$key])) $value = $_GET[$key];
	else if(isset($_POST[$key])) $value = $_POST[$key];
	else $value = '';
	return addslashes($value);
}

function get_param_int($key) {
	if(isset($_GET[$key])) $value = $_GET[$key];
	else if(isset($_POST[$key])) $value = $_POST[$key];
	else $value = 0;
	return intval($value);
}

function h($value) {
	return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function safe_url($url) {
	$url = trim((string)$url);
	if($url === '') return '';
	if(!preg_match('/^https?:\/\//i', $url) && !preg_match('/^mailto:/i', $url)) {
		return '';
	}
	return h($url);
}

function photo_storage_dir() {
	$photoDir = getenv('PHOTO_DIR');
	if($photoDir === false || $photoDir === '') {
		$candidates = array(
			__DIR__.'/photo',
			__DIR__.'/../photo',
		);

		foreach($candidates as $candidate) {
			if(is_dir($candidate) || file_exists($candidate)) {
				$photoDir = $candidate;
				break;
			}
		}

		if($photoDir === false || $photoDir === '') {
			$photoDir = __DIR__.'/photo';
		}
	}
	$resolved = realpath($photoDir);
	if($resolved !== false) {
		return rtrim($resolved, '/');
	}
	return rtrim($photoDir, '/');
}

function photo_public_url($filename) {
	$filename = basename((string)$filename);
	if($filename === '') return '';
	$url = 'photo.php?f='.rawurlencode($filename);
	$path = photo_storage_dir().'/'.$filename;
	if(is_file($path)) {
		$url .= '&v='.filemtime($path);
	}
	return $url;
}

function default_avatar_url($seed) {
	$seed = trim((string)$seed);
	if($seed === '') {
		$seed = 'webdo';
	}
	return 'https://api.dicebear.com/10.x/lorelei/svg?seed='.rawurlencode($seed);
}

function has_photo_file($filename) {
	$filename = basename((string)$filename);
	if($filename === '') return false;
	return is_file(photo_storage_dir().'/'.$filename);
}

function user_avatar_url($photo, $seed) {
	if(has_photo_file($photo)) {
		return photo_public_url($photo);
	}
	return default_avatar_url($seed);
}

function display_form_error($field)
{
	if(isset($_SESSION[$field.'ERR']))
	{
		echo '<div class="error">'.h($_SESSION[$field.'ERR']).'</div>';
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
