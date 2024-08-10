<?php
declare(strict_types = 1);
require_once "classes/MiiExtractor.php";

$database = $_FILES["database"] ?? exit("No upload was initiated.");

if ($database["error"] || !is_uploaded_file($database["tmp_name"]))
{
    exit("The database file failed to upload.");
}

$output = MiiExtractor::zipMiis($database);

header("Content-Type: application/zip");
header("Content-Disposition: attachment; filename=miis.zip");
header("Cache-Control: must-revalidate");
header("Content-Length: " . strlen($output));
echo $output;
exit();