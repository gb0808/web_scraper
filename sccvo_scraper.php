<?php

/**
 * This function exports an associative array (format: link => bibtex) as a csv file.
 * 
 * @param publications - An associative array where the key is the link to the publication and the 
 *                       value is the publications BibTex.
 */
function export_publications($publications) {
    $csv_data = [['Link', 'BibTex']];
    foreach ($publications as $link => $bib) {
        $csv_data[] = [$link, $bib];
    }   
    $file_name = 'publications.csv';
    $f = fopen($file_name, 'w');
    if ($f === false) {
        die('Error opening the file ' . $filename);
    }
    foreach ($csv_data as $row) {
        fputcsv($f, $row);
    }
    fclose($f);
}

/**
 * This functions scrapes teh SCCVO website for Award IDs
 * 
 * @return - An array holding all the Award IDs.
 */
function get_award_id_from_SCCVO() {
    $extracted_award_ids = [];
    $dir = 'https://sccvo.org/projects?page=';
    $httpClient = new \GuzzleHttp\Client();
    for ($i = 0; has_award_id($dir . $i); $i++) {
        echo ".";
        $response = $httpClient->get($dir . $i);
        $htmlString = (string) $response->getBody();
        libxml_use_internal_errors(true);
        $doc = new DOMDocument();
        $doc->loadHTML($htmlString);
        $xpath = new DOMXPath($doc);
        $award_list_path = '//a[@class="vo-card"]';
        $award_links = $xpath->evaluate($award_list_path);
        $extracted_award_links = [];
        foreach ($award_links as $link) {
            $extracted_award_links[] = trim($link->getAttribute('href'));
        }
        foreach ($extracted_award_links as $link) {
            $extracted_award_ids[] = retrieve_award_id("https://sccvo.org/" . $link);
        }
    }
    return $extracted_award_ids;
}

/**
 * This function determines if a webpage has Award IDs on is. This is meant to help with iterating
 * through pages on the SCCVO website.
 * 
 * @param dir - The link of the SCCVO page.
 * @return - A boolean value.
 */
function has_award_id($dir) {
    $httpClient = new \GuzzleHttp\Client();
    $response = $httpClient->get($dir);
    $htmlString = (string) $response->getBody();
    libxml_use_internal_errors(true);
    $doc = new DOMDocument();
    $doc->loadHTML($htmlString);
    $xpath = new DOMXPath($doc);
    $award_list_path = '//div[@class="vo-card-container vo-card-container--col-3"]';
    $award_links = $xpath->evaluate($award_list_path);
    return sizeof($award_links) != 0;
}

/**
 * This function returns the award ID for a project on the SCCVO page.
 * 
 * @param dir - The link of the project page.
 * @return - The award id.
 */
function retrieve_award_id($dir) {
    $httpClient = new \GuzzleHttp\Client();
    $response = $httpClient->get($dir);
    $htmlString = (string) $response->getBody();
    libxml_use_internal_errors(true);
    $doc = new DOMDocument();
    $doc->loadHTML($htmlString);
    $xpath = new DOMXPath($doc);
    $project_detail_path = '//div[@class="project-node__detail"]//a';
    return $xpath->evaluate($project_detail_path)[0]->textContent.PHP_EOL;
}