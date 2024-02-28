<?php
session_start();

// If search is submitted, store it in session variable
if (isset($_POST['search'])) {
    $_SESSION['search'] = $_POST['search'];
}

$search = '';

if (isset($_POST['search'])) {
    $_SESSION['search'] = $_POST['search'];
}

if (isset($_SESSION['search'])) {
    $search = $_SESSION['search'];
}

$xml = new DOMDocument();
$xml->load("research.xml");
$articles = $xml->getElementsByTagName("article");

$articlesArray = iterator_to_array($articles);

usort($articlesArray, function ($a, $b) {
    $aDoi = $a->getAttribute("doi");
    $bDoi = $b->getAttribute("doi");
    return strcmp($aDoi, $bDoi);
});

$rowNumber = 1;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>PHP CRUD</title>
</head>
<body>
    <div class="header">
        <form action="create.php" method="post">
            <button id="create">&plus;Add New Record</button>
        </form>
        <div class="search-container">
            <form method="post" class="form-search">
                <input type="text" name="search" autocomplete="off" placeholder="Search for Researches" value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" style="background: #f3f1f1; border: none; padding: 5;">
                    <img src="./icons/search.png" alt="Search">
                </button>
            </form>
        </div>
    </div>

    <div class="table-container">
        <table>
            <tr>
                <th class="num">#</th>
                <th class="doi">DOI</th>
                <th class="title">Article Title</th>
                <th class="journal">Journal Name</th>
                <th class="volume">Volume</th>
                <th class="authors">Authors</th>
                <th class="actions">Actions</th>
            </tr>

            <?php
            foreach ($articlesArray as $article) {
                $doi = $article->getAttribute("doi");
                $title = $article->getElementsByTagName("title")[0]->nodeValue;
                $journal = $article->getElementsByTagName("journal")[0]->nodeValue;
                $volume = $article->getElementsByTagName("volume")[0]->nodeValue;

                $authors = $article->getElementsByTagName("author");
                $author = "";

                foreach ($authors as $authorNode) {
                    $author .= $authorNode->nodeValue;
                    if ($authorNode !== end($authors)) {
                        $author .= ", ";
                    }
                }
                $author = rtrim($author, ", ");
                if (stripos($doi, $search) !== false || stripos($title, $search) !== false ||
                    stripos($journal, $search) !== false || stripos($volume, $search) !== false ||
                    stripos($author, $search) !== false) {
                    echo "<tr>";
                    echo "<td>$rowNumber</td>";
                    echo "<td>$doi</td>";
                    echo "<td>$title</td>";
                    echo "<td>$journal</td>";
                    echo "<td>$volume</td>";
                    echo "<td>$author</td>";
                    echo "<td class='actions-col'>
                            <a href='update.php?doi=$doi'><button style='background: #FFC107; border: none; padding: 10;'><img src='/02-28-2024/icons/edit.png' alt='Edit'></button></a>
                            <a href='delete.php?doi=$doi'><button style='background: #DC3545; border: none; padding: 10;'><img src='/02-28-2024/icons/delete.png' alt='Delete'></button></a>
                            </td>";
                    echo '</tr>';
                }
                $rowNumber++;
            }
            ?>
        </table>
    </div>
</body>
</html>
