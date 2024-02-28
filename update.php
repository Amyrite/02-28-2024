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

    <div class="form-container-element">
        <div class="text-container">
            <label for="title">Title: </label>
            <span id="title"><?php echo $title; ?></span>
        </div>
        <div class="img-btns">
            <a href="update-title.php?doi=<?php echo $doi; ?>">
                <img src="/02-28-2024/icons/edit.png" width="30" alt="Update Title">
            </a>
        </div>
        </div>

        <div class="form-container-element">
        <div class="text-container">
            <label for="journal">Journal: </label>
            <span id="journal"><?php echo $journal; ?></span>
        </div>
        <div class="img-btns">
            <a href="update-journal.php?doi=<?php echo $doi; ?>">
                <img src="/02-28-2024/icons/edit.png" width="30" alt="Update Journal">
            </a>
        </div>
        </div>

        <div class="form-container-element">
        <div class="text-container">
            <label for="volume">Volume: </label>
            <span id="volume"><?php echo $volume; ?></span>
        </div>
        <div class="img-btns">
            <a href="update-volume.php?doi=<?php echo $doi; ?>">
                <img src="/02-28-2024/icons/edit.png" width="30" alt="Update Volume">
            </a>
        </div>
        </div>

        <div class="form-container-element">
        <div class="text-container">
            <label for="authors">Authors: </label>
            <span id="authors"><?php echo implode(", ", $authors); ?></span>
        </div>
        <div class="img-btns">
            <a href="update-authors.php?doi=<?php echo $doi; ?>">
                <img src="/02-28-2024/icons/edit.png" width="30" alt="Update Authors">
            </a>
        </div>
        </div>

    
</div>
<form action="update-all.php?doi=<?php echo $doi; ?>" method="post">
        <input type="hidden" name="doi" value="<?php echo $doi; ?>">
        <div class="buttons-grp">
            <button type="submit" class="updateBtn">Update All</button>
            <button type="button" class="returnBtn"><a href="display.php">Go back</a>
        </div>
    </form>
</div>
    
</div>

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
