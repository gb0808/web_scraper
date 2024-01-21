<?php

require 'nsf_scraper.php';

$in_file_name = 'award_ids.csv';
$award_ids = [];
if (($f = fopen($in_file_name, 'r')) !== FALSE) {
    while (($data = fgetcsv($f, 1000, '"')) !== FALSE) {
        $award_ids[] = trim(str_replace("\n", '', $data[0]));
    }
}

$publications = [];
foreach($award_ids as $id) {
    echo ".";
    $nsf_data = scrape_nsf($id);
    $publications = array_merge($publications, $nsf_data);
}
export_publications($publications);