<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['doi']) && !empty($_POST['title']) && !empty($_POST['journal']) && !empty($_POST['volume']) && !empty($_POST['authors'])) {

        $xml = new DomDocument();
        if ($xml->load("research.xml")) {

            $newArticle = $xml->createElement("article");

            // Set attributes for the new article
            $newArticle->setAttribute("no", count($xml->getElementsByTagName("article")) + 1); // Generate a new article number
            $newArticle->setAttribute("doi", $_POST['doi']);

            // Create child elements for the new article
            $title = $xml->createElement("title", $_POST['title']);
            $newArticle->appendChild($title);

            $journal = $xml->createElement("journal", $_POST['journal']);
            $newArticle->appendChild($journal);

            $volume = $xml->createElement("volume", $_POST['volume']);
            $newArticle->appendChild($volume);

            $authors = $xml->createElement("authors");
            $authorNames = explode(",", $_POST['authors']); // Split authors by comma
            foreach ($authorNames as $authorName) {
                $authorName = trim($authorName); // Remove leading/trailing whitespace
                $authorNode = $xml->createElement("author", $authorName);
                $authors->appendChild($authorNode);
            }
            $newArticle->appendChild($authors);

            // Append the new article to the XML file
            $xml->documentElement->appendChild($newArticle);
            $xml->save("research.xml");

            // Redirect back to the initial page
            header("Location: display.php");
            exit();
        } else {
            echo "Error: Unable to load XML file.";
        }
    }   
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Document</title>
</head>
<body>
    <div class="pages">
    <h1>Create New Data</h1><br>
    <div class="form-container">
    <form method="post">
        <div class="input">
            <div class="text-container">
                <label for="doi">DOI 
                <input type="text" placeholder="DOI" id="doi" name="doi" class="text-input">
                </label><br>
            </div>

            <div class="text-container">
                <label for="title">Title 
                <input type="text" placeholder="Article Title" id="title" name="title" class="text-input">
                </label><br>
            </div>

            <div class="text-container">
                <label for="journal">Journal 
                <input type="text" placeholder="Journal Name" id="journal" name="journal" class="text-input">
                </label><br>
            </div>

            <div class="text-container">
                <label for="volume">Volume 
                <input type="text" placeholder="Volume" id="volume" name="volume" class="text-input">
                </label><br>
            </div>

            <div class="text-container">
                <label for="authors">Authors 
                <input type="text" placeholder="Authors" id="authors" name="authors" class="text-input">
                </label><br>
            </div>
        </div><!--input-->
        </div><!--form-container-->

        <div class="buttons-grp">
            <button type="submit" class="addBtn">Add Research</button>
            <button type="button" class="returnBtn"><a href="display.php">Go back</a>
        </div>
    </form>
    
    </div><!--create-page-->
</body>
</html>