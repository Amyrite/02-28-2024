<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete article</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>

<div class="delete-section">


<?php

if(isset($_GET['doi'])) {
    $doi = $_GET['doi'];
    
    $xml = new DomDocument();
    $xml->load("research.xml");
    
    $articles = $xml->getElementsByTagName("article");
    $selectedarticle = null;
    foreach($articles as $article) {
        if($article->getAttribute("doi") == $doi) {
            $selectedarticle = $article;
            break;
        }
    }
    
    // If the article is found, display its details
    if($selectedarticle) {
        $title = $selectedarticle->getElementsByTagName("title")[0]->nodeValue;
        $journal = $selectedarticle->getElementsByTagName("journal")[0]->nodeValue;
        $volume = $selectedarticle->getElementsByTagName("volume")[0]->nodeValue;
        $authors = [];
        $authorNodes = $selectedarticle->getElementsByTagName("author");
        foreach ($authorNodes as $authorNode) {
            $authors[] = $authorNode->nodeValue;
        }
?>
        <div class="pages">
            <h2>Delete Article</h2>
            <div class="form-container">
            <div class="text-container">
                <label for="title">Title: </label>
                <span id="title"><?php echo $title; ?></span>
            </div>

            <div class="text-container">
                <label for="journal">Journal: </label>
                <span id="journal"><?php echo $journal; ?></span>
            </div>

            <div class="text-container">
                <label for="volume">Volume: </label>
                <span id="volume"><?php echo $volume; ?></span>
            </div>

            <div class="text-container">
                <label for="authors">Authors: </label>
                <span id="authors"><?php echo implode(", ", $authors); ?></span>
            </div>
            </div>

            <form method="post" action="confirmDelete.php">
                <input type="hidden" name="doi" value="<?php echo $doi; ?>">
                <div class="buttons">
                    <button type="submit" name="confirmation" value="yes" class="deleteBtn">Delete</button>
                    <button type="submit" name="confirmation" value="no" class="cancelBtn">Cancel</button>
                </div>
            </form>
            
        </div>

</div>

<?php
    } else {
        echo "<p>article not found.</p>";
    }
} else {
    echo "<p>doi parameter not provided.</p>";
}
?>


</body>
</html>
