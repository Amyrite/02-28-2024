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
        $title = $selectedarticle->getElementsByTagName("title")[0]->nodeValue;
        $journal = $selectedarticle->getElementsByTagName("journal")[0]->nodeValue;
        $volume = $selectedarticle->getElementsByTagName("volume")[0]->nodeValue;
        $authors = [];
        $authorNodes = $selectedarticle->getElementsByTagName("author");
        foreach ($authorNodes as $authorNode) {
            $authors[] = $authorNode->nodeValue;
        }
        
        // Check if the form is submitted for updating all fields
        if(isset($_POST['newTitle']) && isset($_POST['newJournal']) && isset($_POST['newvolume']) && isset($_POST['newAuthors'])) {
            // Update title
            $selectedarticle->getElementsByTagName("title")[0]->nodeValue = $_POST['newTitle'];
            
            // Update year published
            $selectedarticle->getElementsByTagName("journal")[0]->nodeValue = $_POST['newJournal'];
            
            // Update volume
            $selectedarticle->getElementsByTagName("volume")[0]->nodeValue = $_POST['newvolume'];
            
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
    <title>Edit article</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>

<div class="pages">
    <h2>Edit Article</h2>
    <div class="form-container">
    <form action="" method="post" class="edit-form">
        <input type="hidden" name="doi" value="<?php echo $doi; ?>">
        
        <div class="text-container">
            <label for="title">Title:</label>
            <span id="title"><?php echo $title; ?></span>
        </div>

        <div class="text-container">
            <label for="journal">Journal:</label>
            <span id="journal"><?php echo $journal; ?></span>
        </div>

        <div class="text-container">
            <label for="volume">Volume:</label>
            <span id="volume"><?php echo $volume; ?></span>
        </div>

        <div class="text-container">
            <label for="authors">Authors:</label>
            <span id="authors"><?php echo implode(", ", $authors); ?></span>
        </div>

        <hr>
        <!-- Form for updating all fields -->
            <div class="text-container">
            <label for="newTitle">New Title:</label>
            <input type="text" id="newTitle" class="text-input" name="newTitle" required>
            </div>

            <div class="text-container">
            <label for="newJournal">New Journal:</label>
            <input type="text" id="newJournal" class="text-input" name="newJournal" required>
            </div>

            <div class="text-container">
            <label for="newvolume">New Volume:</label>
            <input type="text" id="newvolume" class="text-input" name="newvolume" required>
            </div>

            <div class="text-container">
            <label for="newAuthors">New Authors (comma-separated):</label>
            <input type="text" id="newAuthors" class="text-input" name="newAuthors" required>
            </div>
            </div>

            <div class="buttons-grp">
                <button type="submit" class="updateBtn">Update</button>
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
