<?php

$rootDir = __DIR__;
$srcDir = realpath($rootDir.'/src');
$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($requestPath === false || $requestPath === null) {
    $requestPath = '/';
}

$publicFile = realpath($rootDir.'/'.ltrim($requestPath, '/'));
if ($publicFile !== false && str_starts_with($publicFile, $rootDir) && is_file($publicFile)) {
    return false;
}

$srcPublicFile = realpath($rootDir.'/src/'.ltrim($requestPath, '/'));
if ($srcPublicFile !== false && $srcDir !== false && str_starts_with($srcPublicFile, $srcDir) && is_file($srcPublicFile) && pathinfo($srcPublicFile, PATHINFO_EXTENSION) !== 'php') {
    $mime = mime_content_type($srcPublicFile);
    if ($mime) {
        header('Content-Type: '.$mime);
    }
    readfile($srcPublicFile);
    return true;
}

if ($requestPath === '/') {
    $requestPath = '/index.php';
}

$srcFile = realpath($rootDir.'/src/'.ltrim($requestPath, '/'));
if ($srcFile !== false && $srcDir !== false && str_starts_with($srcFile, $srcDir) && is_file($srcFile)) {
    require $srcFile;
    return true;
}

http_response_code(404);
echo 'Not Found';
