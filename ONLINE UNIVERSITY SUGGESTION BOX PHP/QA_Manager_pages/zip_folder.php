<?php

require_once '../Common_pages/setup.php';

//Folder directory
$directory = '../PDF_Uploads/';

//ZIP folder name
$zipName = 'PDF_Uploads.zip';

$zipArchive = new ZipArchive();

//Open ZIP folder
if ($zipArchive->open($zipName, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
    exit("Unable to open ZIP file.");
}

//Add files to ZIP folder
$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($directory),
    RecursiveIteratorIterator::LEAVES_ONLY
);

foreach ($files as $name => $file) {
    if (!$file->isDir()) {
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen($directory));
        $zipArchive->addFile($filePath, $relativePath);
    }
}

//Close the ZIP folder
$zipArchive->close();

header("Content-type: application/zip");
header("Content-Disposition: attachment; filename=$zipName");
header("Content-length: " . filesize($zipName));
header("Pragma: no-cache");
header("Expires: 0");

readfile($zipName);

unlink($zipName);

?>