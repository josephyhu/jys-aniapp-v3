<?php
session_start();
require_once 'inc/functions.php';

// Get current anime id.
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
    // Get current anime details.
    $animeDetails = get_animeDetails($id);
    try {
        $authAnimeDetails = get_userAnimeDetails($_SESSION['userId'], $id);
    } catch (Exception $e) {
        error_log($e->getMessage());
    }

    if (!empty($animeDetails)) {
        echo "<img src='" . $animeDetails['bannerImage'] . "' alt='banner' class='banner'>";
        echo "<h2><a href='" . $animeDetails['siteUrl'] . "' target='_blank'>" . $animeDetails['title']['english'] . " (" . $animeDetails['title']['romaji'] . ")</a></h2>";

        echo "<div class='flex-container'>";
        echo "<div id='cover'>";
        echo "<img src='" . $animeDetails['coverImage']['large']. "' alt='cover'>";
        echo "</div>";
        echo "<div class='details' class='clearfix'>";
        echo "<p>" . $animeDetails['description'] . "</p>";
        echo "<hr>";
        echo "<table>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>Format</th>";
        echo "<th>Status</th>";
        echo "<th>Episodes</th>";
        echo "<th>Duration</th>";
        echo "<th>Start Date</th>";
        echo "<th>End Date</th>";
        echo "<th>Season</th>";
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
        echo "<td>" . $animeDetails['format'] . "</td>";
        echo "<td>" . $animeDetails['status'] . "</td>";
        echo "<td>" . $animeDetails['episodes'] . "</td>";
        echo "<td>" . $animeDetails['duration'] . "</td>";
        echo "<td>" . $animeDetails['startDate']['year'] . "-" . $animeDetails['startDate']['month'] . "-" . $animeDetails['startDate']['day'] . "</td>";
        echo "<td>" . $animeDetails['endDate']['year'] . "-" . $animeDetails['endDate']['month'] . "-" . $animeDetails['endDate']['day'] . "</td>";
        echo "<td>" . $animeDetails['season'] . " " . $animeDetails['seasonYear'] . "</td>";
        echo "<td>" . $animeDetails['averageScore'] . "</td>";
        echo "<td>" . $animeDetails['meanScore'] . "</td>";
        echo "<td>" . $animeDetails['favourites'] . "</td>";
        echo "<td>" . $animeDetails['popularity'] . "</td>";
        echo "<td>" . $animeDetails['source'] . "</td>";
        echo "<td>";
        foreach ($animeDetails['genres'] as $genre) {
            echo $genre . "<br>";
        }
        echo "</td>";
        echo "<td>" . $animeDetails['countryOfOrigin'] . "</td>";
        echo "</tr";
        echo "</tbody>";
        echo "</table>";
        echo "</div>";
        echo "</div>";
    }

    // Get character details for current anime.
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

    // Get details of related media of current anime.
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

    // Display add anime link if the user is logged and does not already have current anime on his/her list.
    if (isset($_SESSION['userId']) && empty($authAnimeDetails)) {
    ?>
        <a href='add_anime.php?id=<?php echo $id; ?>'>Add anime</a>
    <?php
    }
    // Get current user specific details of current anime and display the update/delete links if the anime is on his/her list.
    if (!empty($authAnimeDetails)) {
        echo "<hr>";
        echo "<table>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>Status</th>";
        echo "<th>Started Date</th>";
        echo "<th>Completed Date</th>";
        echo "<th>Progress</th>";
        echo "<th>Score</th>";
        echo "<th>Repeat</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        echo "<tr>";
        echo "<td>" . $authAnimeDetails['status'] . "</td>";
        echo "<td>" . $authAnimeDetails['startedAt']['year'] . "-" . $authAnimeDetails['startedAt']['month'] . "-" . $authAnimeDetails['startedAt']['day'] . "</td>";
        echo "<td>" . $authAnimeDetails['completedAt']['year'] . "-" . $authAnimeDetails['completedAt']['month'] . "-" . $authAnimeDetails['completedAt']['day'] . "</td>";
        echo "<td>" . $authAnimeDetails['progress'] . "</td>";
        echo "<td>" . $authAnimeDetails['score'] . "</td>";
        echo "<td>" . $authAnimeDetails['repeat'] . "</td>";
        echo "</tr";
        echo "</tbody>";
        echo "</table>";
        ?>
        <a href="update_anime.php?&id=<?php echo $id; ?>">Update anime</a><br>
        <a href="delete_anime.php?&entryId=<?php echo $authAnimeDetails['id']; ?>">Delete Anime</a>
    <?php
    }
    ?>
</main>
<?php require_once 'inc/footer.php'; ?>