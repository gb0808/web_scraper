<?php

require 'sccvo_scraper.php';

$award_ids = get_award_id_from_SCCVO();
$file_name = 'award_ids.csv';
$f = fopen($file_name, 'w');
if ($f == false) {
    die('Error opening the file ' . $filename);
}
foreach ($award_ids as $id) {
    if (is_numeric($id)) {
        fputcsv($f, [$id]);
    }
}
fclose($f);