<?

// Chemin vers la base SQLite locale
$dbpath = getenv('DB_PATH');
if($dbpath === false || $dbpath === '') {
	$dbpath = __DIR__."/var/webdo.sqlite";
}

//$Theme="noel";	// Noel
$Theme="annif";	// Anniversaires

?>
