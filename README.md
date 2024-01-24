# NSF-PAR Web Scraper
This program scrapes the NSF-PAR website for publications based on an Award ID and returns all found publications in as a BibTeX or CSV. This code will be added to a Drupal module.

# Set-up
Before running any of the scripts, ensure that PHP is installed and up to date on your device.

# Get Data
First, collect all of the NSF Award IDs on the SCCVO website. To do this, run the `get_awards.php` script with the following command: 
```
php get_awards.php
```
This will produce a CSV file, `award_ids.csv`, containing all of the NSF Award IDs found on the SCCVO website.


Now that we have out desired award ids, we can start scraping the NSF websites for publications. To do this, run the `get_publications.php` script, with the following command:
```
php get_publications.php
```
This will produce a CSV file, `publications.csv`, containing the award ids pair with their respective publication links and BibTex data.