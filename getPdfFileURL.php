<?php
include "common-functions.php";
$monday_row_id = 7885770047;
//Get PDF files from Monday Column
// Get Public URL for the file
$assetIDs = getPdfFromMonday($monday_row_id, $token);
foreach($assetIDs as $assetID){
    $pdfURL = getPublicURL($assetID, $token);
} 
?
