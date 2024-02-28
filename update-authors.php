<?php
session_start();

if(isset($_GET['doi'])) {
    $doi = $_GET['doi'];
    
    // Load XML file
    $xml = new DomDocument();
    $xml->load("research.xml");
    
    // Finding the article node by doi
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
        // Retrieve authors
        $authorNodes = $selectedarticle->getElementsByTagName("author");
        $authors = [];
        foreach ($authorNodes as $authorNode) {
            $authors[] = $authorNode->nodeValue;
        }
        
        // If the form is submitted for updating the authors
if(isset($_POST['newAuthors'])) {
    // Remove all existing author nodes
    $authorNodes = $selectedarticle->getElementsByTagName("author");
    while ($authorNodes->length > 0) {
        $authorNode = $authorNodes->item(0);
        $authorNode->parentNode->removeChild($authorNode);
    }
    
    // Add new author nodes
    $newAuthors = explode(',', $_POST['newAuthors']);
    foreach ($newAuthors as $author) {
        $authorNode = $xml->createElement("author", trim($author));
        $selectedarticle->appendChild($authorNode);
    }
    
    // Save the changes back to the XML file
    $xml->save("research.xml");
    
    // Redirect back to the edit page with updated data
    header("Location: display.php");
    exit;
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Authors</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>

<div class="pages">
    <h2>Edit Authors</h2>

    <div class="form-container">

    <div class="form-container-element">
    <form action="" method="post" class="edit-form">
        <input type="hidden" name="doi" value="<?php echo $doi; ?>">
        <div class="text-container">
            <label for="authors">Authors (comma-separated):</label>
            <input type="text" id="newAuthors" class="text-input" placeholder="<?php echo implode(", ", $authors); ?>" name="newAuthors" required>
        </div>
            
    </div>      

    </div>
        <div class="buttons-grp">
            <button type="submit" class="updateBtn">Update Authors</button>
            <button type="button" class="returnBtn"><a href="display.php">Go back</a>
        </div>
    </form>
</div>

</body>
</html>
<?php
    } else {
        echo "<p>article not found.</p>";
    }
} else {
    echo "<p>doi parameter not provided.</p>";
}
?>
