<?

// Chemin vers la base SQLite
$dbpath = getenv('DB_PATH');
if($dbpath === false || $dbpath === '') {
	$externalDbDir = __DIR__."/../var";
	$internalDbDir = __DIR__."/var";

	if(is_dir($externalDbDir) || file_exists($externalDbDir."/webdo.sqlite")) {
		$dbpath = $externalDbDir."/webdo.sqlite";
	} else {
		$dbpath = $internalDbDir."/webdo.sqlite";
	}
}

//$Theme="noel";	// Noel
$Theme="annif";	// Anniversaires

?>
