<?php
session_start();
require_once 'inc/functions.php';

$id = $_GET['id'];
$userId = $_GET['userId'];

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
    <?php if (isset($_SESSION['userId'])) { ?>
        <div class="logout"><a href="logout.php">Log out</a></div>
    <?php
    }
        $data = get_animeDetails($id);
        if (!empty($userId)) {
            $authData = get_userAnimeDetails($userId, $id);
        }

        if (!empty($data)) {
            echo "<img src='" . $data['bannerImage'] . "' alt='banner' class='banner'>";
            echo "<h2><a href='" . $data['siteUrl'] . "' target='_blank'>" . $data['title']['english'] . " (" . $data['title']['romaji'] . ")</a></h2>";

            echo "<div class='flex-container'>";
            echo "<div id='cover'>";
            echo "<img src='" . $data['coverImage']['large']. "' alt='cover'>";
            echo "</div>";
            echo "<div class='details' class='clearfix'>";
            echo "<p>" . $data['description'] . "</p>";
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
            echo "<th>Popularity</th>";
            echo "<th>Source</th>";
            echo "<th>Genres</th>";
            echo "<th>Country</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            echo "<tr>";
            echo "<td>" . $data['format'] . "</td>";
            echo "<td>" . $data['status'] . "</td>";
            echo "<td>" . $data['episodes'] . "</td>";
            echo "<td>" . $data['duration'] . "</td>";
            echo "<td>" . $data['startDate']['year'] . "-" . $data['startDate']['month'] . "-" . $data['startDate']['day'] . "</td>";
            echo "<td>" . $data['endDate']['year'] . "-" . $data['endDate']['month'] . "-" . $data['endDate']['day'] . "</td>";
            echo "<td>" . $data['season'] . " " . $data['seasonYear'] . "</td>";
            echo "<td>" . $data['averageScore'] . "</td>";
            echo "<td>" . $data['popularity'] . "</td>";
            echo "<td>" . $data['source'] . "</td>";
            echo "<td>";
            foreach ($data['genres'] as $genre) {
                echo $genre . "<br>";
            }
            echo "</td>";
            echo "<td>" . $data['countryOfOrigin'] . "</td>";
            echo "</tr";
            echo "</tbody>";
            echo "</table>";

            $relatedMedia = get_relatedMedia($id);

            echo "<h3>Related Media</h3>";
            echo "<table>";
            echo "<tbody>";
            echo "<tr>";
            for ($i = 0; $i < count($relatedMedia); $i++) {
            ?>
                <td><a href="animelist?id=<?php echo $id; ?>"></a><img src="<?php echo $relatedMedia[$i]['node']['coverImage']['medium']; ?>" alt="cover" title='<?php echo $relatedMedia[$i]["node"]["title"]["romaji"] . "\n" . $relatedMedia[$i]["relationType"]; ?>'></td>
            <?php
                
                if (substr($i, -1) == 9) {
                    echo '</tr><tr>';
                }
            }
            echo "</tr>";
            echo "</tbody>";
            echo "</table>";
            echo "</div>";
            echo "</div>";

            if (isset($_SESSION['userId']) && !isset($userId)) {
            ?>
                <a href='add_anime.php?id=<?php echo $id; ?>'>Add anime</a>
            <?php
            }
        }
        if (!empty($authData)) {
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
            echo "<td>" . $authData['status'] . "</td>";
            echo "<td>" . $authData['startedAt']['year'] . "-" . $authData['startedAt']['month'] . "-" . $authData['startedAt']['day'] . "</td>";
            echo "<td>" . $authData['completedAt']['year'] . "-" . $authData['completedAt']['month'] . "-" . $authData['completedAt']['day'] . "</td>";
            echo "<td>" . $authData['progress'] . "</td>";
            echo "<td>" . $authData['score'] . "</td>";
            echo "<td>" . $authData['repeat'] . "</td>";
            echo "</tr";
            echo "</tbody>";
            echo "</table>";
            ?>
            <a href="update_anime.php?userId=<?php echo $userId; ?>&id=<?php echo $id; ?>">Update anime</a><br>
            <a href="delete_anime.php?userId=<?php echo $userId; ?>&entryId=<?php echo $authData['id']; ?>">Delete Anime</a>
            <?php
        }
    ?>
</main>
<?php require_once 'inc/footer.php'; ?>