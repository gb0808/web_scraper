<?php
require 'vendor/autoload.php';

/**
 * This function scrapes te NSF PAP websites and pulls publications associated with a given
 * NSF Award ID.
 * 
 * @param awardID - The NSF Award ID associated with a project.
 * @return - An associative array where the key is the link to the publication and the value
 *           is the publications BibTex.
 */
function scrape_nsf($awardID) {
    // Scrape the NSF PAR site
    $dir = 'https://par.nsf.gov/search/award_ids:' . $awardID;
    $httpClient = new \GuzzleHttp\Client();
    $response = $httpClient->get($dir);
    $htmlString = (string) $response->getBody();
    libxml_use_internal_errors(true);
    $doc = new DOMDocument();
    $doc->loadHTML($htmlString);
    $xpath = new DOMXPath($doc);

    // Get links for all the publications associated with the given NSF Award ID.
    $publication_list_path = '//ol[@class="item-list documents"]//li//div[@class="article item document"]//div[@class="item-info"]//div[@class="title"]//a';
    $publication_links = $xpath->evaluate($publication_list_path);
    $publications = [];
    foreach ($publication_links as $link) {
        // Scrape the individal publication sites.
        $extracted_link = trim($link->getAttribute('href'));
        $pubHttpClient = new \GuzzleHttp\Client();
        $pubResponse = $pubHttpClient->get($extracted_link);
        $pubString = (string) $pubResponse->getBody();
        $pubDoc = new DOMDocument();
        $pubDoc->loadHTML($pubString);
        $pubXpath = new DOMXPath($pubDoc);

        // Extract link to the publication and the assosiated BibTex.
        $publication_link_path = '//a[@title="Document DOI URL"]';
        $extracted_publication_link = trim($pubXpath->evaluate($publication_link_path)[0]->getAttribute('href'));
        $bibtext_path = '//div[@id="cite-bib"]//div[@class="modal-dialog"]//div[@class="modal-content"]//div[@class="modal-body"]';
        $extracted_bibtext = trim($pubXpath->evaluate($bibtext_path)[0]->textContent.PHP_EOL);
        $publications[$extracted_publication_link] = $extracted_bibtext;
    }

    // Return the publications
    return $publications;
}

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