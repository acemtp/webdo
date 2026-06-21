<?

//
// NE MODIFIEZ PAS CE FICHIER
//
// Copiez ce fichier en i_config.php et modifiez i_config.php avec le bon chemin de base SQLite
// ou fournissez DB_PATH dans l'environnement de prod.
//

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
