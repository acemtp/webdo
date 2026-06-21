<?

require_once(__DIR__.'/i_divers.php');

$filename = isset($_GET['f']) ? basename($_GET['f']) : '';
if($filename === '') {
	http_response_code(404);
	exit;
}

$path = photo_storage_dir().'/'.$filename;
if(!is_file($path)) {
	http_response_code(404);
	exit;
}

$mime = mime_content_type($path);
if($mime) {
	header('Content-Type: '.$mime);
}
header('Content-Length: '.filesize($path));
readfile($path);

?>
