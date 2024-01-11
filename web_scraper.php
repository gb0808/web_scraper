<?php
require 'vendor/autoload.php';

$awardID = readline('NSF Award ID: ');
$dir = 'https://www.nsf.gov/awardsearch/showAward?AWD_ID=' . $awardID;

$httpClient = new \GuzzleHttp\Client();
$response = $httpClient->get($dir);
$htmlString = (string) $response->getBody();