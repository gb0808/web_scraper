<?php

require 'web_scraper.php';

$awardID = readline('NSF Award ID: ');
$publicatons = scrape_nsf($awardID);
export_publications($publicatons);