<?php

namespace ClimbingLogbook;

require __DIR__ . '/vendor/autoload.php';

use ClimbingLogbook\Controller\CSVController;
use ClimbingLogbook\Controller\DatabaseController;
use ClimbingLogbook\Controller\UploadController;
use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;
use Dotenv;
use Exception;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$appname = $_ENV['APPNAME'];
$version = $_ENV['VERSION'];

$bootstrap_css = $_ENV['BOOTSTRAP_CSS'];
$bootstrap_js = $_ENV['BOOTSTRAP_JS'];
$main_css = $_ENV['MAIN_CSS'];

if (isset($_FILES['csvfile'])) {
    UploadController::validate($_FILES['csvfile']);
    $localFileURI = UploadController::save($_FILES['csvfile']);

    $fileHandle = fopen($localFileURI, "r");

    if (!$fileHandle) {
        throw new Exception("Could not open file!");
    }

    $logentries = CSVController::extract($fileHandle);

    DatabaseController::save($logentries);
}

$mustacheEngine = new Mustache_Engine([
    'entity_flags' => ENT_QUOTES,
    'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__) . '/views'),
]);

echo $mustacheEngine->render('mainframe', [
    'appname' => $appname,
    'bootstrap_css' => $bootstrap_css,
    'bootstrap_js' => $bootstrap_js,
    'main_css' => $main_css,
]);
