<?php

namespace ClimbingLogbook;

require __DIR__ . '/vendor/autoload.php';

use ClimbingLogbook\Controller\CSVController;
use ClimbingLogbook\Controller\LogbookController;
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

$uploadresult = null;

// A file is being uploaded. Process it here.
if (isset($_FILES['csvfile'])) {
    try {
        UploadController::validate($_FILES['csvfile']);
        $localFileURI = UploadController::save($_FILES['csvfile']);

        $fileHandle = fopen($localFileURI, "r");
    
        if ($fileHandle) {
            $logentries = CSVController::extract($fileHandle);

            LogbookController::save($logentries);

            // Say something about the result of the save process.
            $uploadresult['success'] = "File uploaded and saved successfully!";
        } else {
            $uploadresult['error'] = "Could not open file!";
        }
    } catch (Exception $e) {
        $uploadresult['error'] = $e->getMessage();
    }
} else {
    // Prepare for the dashboard output.
    // Answer the questions.
}

$mustacheEngine = new Mustache_Engine([
    'pragmas' => [Mustache_Engine::PRAGMA_BLOCKS],
    'entity_flags' => ENT_QUOTES,
    'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__) . '/views'),
]);

echo $mustacheEngine->render('mainframe', [
    'appname' => $appname,
    'version' => $version,
    'bootstrap_css' => $bootstrap_css,
    'bootstrap_js' => $bootstrap_js,
    'uploadresult' => $uploadresult,
]);
