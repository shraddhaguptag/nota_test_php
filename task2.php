<?php
// Database configuration
$host = 'localhost';
$db = 'test';
$user = 'root';
$pass = '';

try {
    // Establishing the database connection
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch the Wikipedia homepage
    $url = 'https://www.wikipedia.org/';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $html = curl_exec($ch);
    curl_close($ch);

    // Load HTML into DOMDocument
    $dom = new DOMDocument();
    @$dom->loadHTML($html); // Suppressing warnings due to malformed HTML

    // Extracting headings, abstracts, pictures, and links
    $xpath = new DOMXPath($dom);
    $sections = $xpath->query('//div[contains(@class, "central-featured")]//a');

    foreach ($sections as $section) {
        $title = trim($section->textContent);
        $url = $section->getAttribute('href');
        $abstract = ""; // Placeholder for abstract, as Wikipedia homepage may not have them
        $picture = "";  // Placeholder for picture, as the homepage does not contain section-specific images

        // Prepare SQL to insert data
        $stmt = $pdo->prepare("INSERT INTO wiki_sections (date_created, title, url, picture, abstract) 
                                VALUES (NOW(), :title, :url, :picture, :abstract)");
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':url', $url);
        $stmt->bindParam(':picture', $picture);
        $stmt->bindParam(':abstract', $abstract);
        
        // Execute the statement
        $stmt->execute();
    }

    echo "Data inserted successfully!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
