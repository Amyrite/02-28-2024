<?php
// Check if confirmation parameter is set
if(isset($_POST['confirmation'])) {
    // If "Yes" is clicked
    if($_POST['confirmation'] === 'yes') {
        // Check if doi parameter is set
        if(isset($_POST['doi'])) {
            $doi = $_POST['doi'];
            
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
            
            // If the article is found, remove it from the XML
            if($selectedarticle) {
                $selectedarticle->parentNode->removeChild($selectedarticle);
                $xml->save("research.xml"); // Save the changes to the XML file
            }
        }
    }
    
    // Redirect back to display.php
    header("Location: display.php");
    exit();
} else {
    // If "No" is clicked or confirmation parameter is not set
    header("Location: display.php");
    exit();
}
?>