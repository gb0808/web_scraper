<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>NSF-PAR Web Scraper</title>
    </head>
    <body>
        <form action="web_scraper.php" method="get">
            <label for="award-id">Award ID:</label>
            <input type="number" id="award-id" name="award-id">
            <input type="submit" id="submit-button">
        </form>
        
        <?php
            echo $_GET["award-id"];
        ?>
    </body>
</html>