<?php
session_start();
require_once 'inc/functions.php';

// Get current manga id.
$id = $_GET['id'];

require_once 'inc/header.php';
?>
<main>
    <div class="links">
    <?php if (isset($_SESSION['userId'])) { ?>
        <a href="index.php?logged_in=1">Home</a>
        <a href="animelist.php">Anime List</a>
        <a href="mangalist.php">Manga List</a>
    <?php } else { ?>
        <a href="index.php">Home</a>
    <?php } ?>
    <a href="search.php">Search</a>
    </div>
    <?php 
    if (isset($_SESSION['userId'])) { ?>
        <div class="logout"><a href="logout.php">Log out</a></div>
    <?php
    }
    // Get current manga details.
    $mangaDetails = get_mangaDetails($id);
    try {
        $authMangaDetails = get_userAnimeDetails($_SESSION['userId'], $id);
    } catch (Exception $e) {
        error_log($e->getMessage());
    }

    if (!empty($mangaDetails)) {
        echo "<img src='" . $mangaDetails['bannerImage'] . "' alt='banner' class='banner'>";
        echo "<h2><a href='" . $mangaDetails['siteUrl'] . "' target='_blank'>" . $mangaDetails['title']['english'] . " (" . $mangaDetails['title']['romaji'] . ")</a></h2>";

        echo "<div class='flex-container'>";
        echo "<div id='cover'>";
        echo "<img src='" . $mangaDetails['coverImage']['large']. "' alt='cover'>";
        echo "</div>";
        echo "<div class='details' class='clearfix'>";
        echo "<p>" . $mangaDetails['description'] . "</p>";
        echo "<hr>";
        echo "<table>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>Format</th>";
        echo "<th>Status</th>";
        echo "<th>Chapters</th>";
        echo "<th>Volumes</th>";
        echo "<th>Start Date</th>";
        echo "<th>End Date</th>";
        echo "<th>Average Score</th>";
        echo "<th>Mean Score</th>";
        echo "<th>Favorites</th>";
        echo "<th>Popularity</th>";
        echo "<th>Source</th>";
        echo "<th>Genres</th>";
        echo "<th>Country</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        echo "<tr>";
        echo "<td>" . $mangaDetails['format'] . "</td>";
        echo "<td>" . $mangaDetails['status'] . "</td>";
        echo "<td>" . $mangaDetails['chapters'] . "</td>";
        echo "<td>" . $mangaDetails['volumes'] . "</td>";
        echo "<td>" . $mangaDetails['startDate']['year'] . "-" . $mangaDetails['startDate']['month'] . "-" . $mangaDetails['startDate']['day'] . "</td>";
        echo "<td>" . $mangaDetails['endDate']['year'] . "-" . $mangaDetails['endDate']['month'] . "-" . $mangaDetails['endDate']['day'] . "</td>";
        echo "<td>" . $mangaDetails['averageScore'] . "</td>";
        echo "<td>" . $mangaDetails['meanScore'] . "</td>";
        echo "<td>" . $mangaDetails['favourites'] . "</td>";
        echo "<td>" . $mangaDetails['popularity'] . "</td>";
        echo "<td>" . $mangaDetails['source'] . "</td>";
        echo "<td>";
        foreach ($mangaDetails['genres'] as $genre) {
            echo $genre . "<br>";
        }
        echo "</td>";
        echo "<td>" . $mangaDetails['countryOfOrigin'] . "</td>";
        echo "</tr";
        echo "</tbody>";
        echo "</table>";
        echo "</div>";
        echo "</div>";
    }

    // Get character details for current manga.
    $characters = get_characters($id);
    if (!empty($characters)) {
        echo "<h3>Characters</h3>";
        echo "<table>";
        echo "<tbody>";
        echo "<tr>";
        for ($i = 0; $i < count($characters); $i++) {
        ?>
            <td><a href="character.php?id=<?php echo $characters[$i]['node']['id']; ?>"><img src="<?php echo $characters[$i]['node']['image']['medium']; ?>" alt='cover' title="<?php echo $characters[$i]['node']['name']['userPreferred'] . "\n" . $characters[$i]['role']; ?>" width="100" height="150"></a></td>
        <?php
            if (substr($i, -1) == 9) {
                echo '</tr><tr>';
            }
        }
        echo "</tr>";
        echo "</tbody>";
        echo "</table>";
    }

    // Get details of related media of current manga.
    $relatedMedia = get_relatedMedia($id);
    if (!empty($relatedMedia)) {
        echo "<h3>Related Media</h3>";
        echo "<table>";
        echo "<tbody>";
        echo "<tr>";
        for ($i = 0; $i < count($relatedMedia); $i++) {
            if ($relatedMedia[$i]['node']['type'] == 'ANIME') {
        ?>
                <td><a href="anime.php?id=<?php echo $relatedMedia[$i]['node']['id']; ?>"><img src="<?php echo $relatedMedia[$i]['node']['coverImage']['medium']; ?>" alt="cover" title='<?php echo $relatedMedia[$i]["node"]["title"]["romaji"] . "\n" . $relatedMedia[$i]["relationType"]; ?>' width='100' height='150'></a></td>
        <?php
            } else {
        ?>
                <td><a href="manga.php?id=<?php echo $relatedMedia[$i]['node']['id']; ?>"><img src="<?php echo $relatedMedia[$i]['node']['coverImage']['medium']; ?>" alt="cover" title='<?php echo $relatedMedia[$i]["node"]["title"]["romaji"] . "\n" . $relatedMedia[$i]["relationType"]; ?>' width='100' height='150'></a></td>
        <?php
            }
            if (substr($i, -1) == 9) {
                echo '</tr><tr>';
            }
        }
        echo "</tr>";
        echo "</tbody>";
        echo "</table>";
    }

    // Display add anime link if the user is logged and does not already have current manga on his/her list.
    if (isset($_SESSION['userId']) && empty($authMangaDetails)) {
    ?>
        <a href='add_manga.php?id=<?php echo $id; ?>'>Add manga</a>
    <?php
    }

    // Get current user specific details of current manga and display the update/delete links if the anime is on his/her list.
    if (!empty($authMangaDetails)) {
        echo "<hr>";
        echo "<table>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>Status</th>";
        echo "<th>Started Date</th>";
        echo "<th>Completed Date</th>";
        echo "<th>Chapters</th>";
        echo "<th>Volumes</th>";
        echo "<th>Score</th>";
        echo "<th>Repeat</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        echo "<tr>";
        echo "<td>" . $authMangaDetails['status'] . "</td>";
        echo "<td>" . $authMangaDetails['startedAt']['year'] . "-" . $authMangaDetails['startedAt']['month'] . "-" . $authMangaDetails['startedAt']['day'] . "</td>";
        echo "<td>" . $authMangaDetails['completedAt']['year'] . "-" . $authMangaDetails['completedAt']['month'] . "-" . $authMangaDetails['completedAt']['day'] . "</td>";
        echo "<td>" . $authMangaDetails['progress'] . "</td>";
        echo "<td>" . $authMangaDetails['progressVolumes'] . "</td>";
        echo "<td>" . $authMangaDetails['score'] . "</td>";
        echo "<td>" . $authMangaDetails['repeat'] . "</td>";
        echo "</tr";
        echo "</tbody>";
        echo "</table>";
        ?>
        <a href="update_manga.php?&id=<?php echo $id; ?>">Update manga</a><br>
        <a href="delete_manga.php?&entryId=<?php echo $authMangaDetails['id']; ?>">Delete Manga</a>
        <?php
    }
    ?>
</main>
<?php require_once 'inc/footer.php'; ?>