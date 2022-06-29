<?php
session_start();
require_once 'inc/functions.php';

$id = $_GET['id'];

require_once 'inc/header.php';
?>
<main>
    <div class="links">
        <a href="index.php">Home</a>
        <?php if (isset($_SESSION['userId'])) { ?>
            <a href="animelist.php">Anime List</a>
            <a href="mangalist.php">Manga List</a>
        <?php } ?>
        <a href="search.php">Search</a>
    </div>
    <?php if (isset($_SESSION['userId'])) { ?>
        <div class="logout"><a href="logout.php">Log out</a></div>
    <?php } ?>
    <?php
        $data = get_animeDetails($id);

        if (!empty($data)) {
            echo "<img src='" . $data['bannerImage'] . "' alt='banner' class='banner'>";
            echo "<h2>" . $data['title']['english'] . " (" . $data['title']['romaji'] . ")</h2>";

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
            echo "<th>Episodes</th>";
            echo "<th>Duration</th>";
            echo "<th>Status</th>";
            echo "<th>Start Date</th>";
            echo "<th>End Date</th>";
            echo "<th>Season</th>";
            echo "<th>Average Score</th>";
            echo "<th>Popularity</th>";
            echo "<th>Source</th>";
            echo "<th>Genre</th>";
            echo "<th>Country</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            echo "<tr>";
            echo "<td>" . $data['format'] . "</td>";
            echo "</tr";
            echo "</tbody>";
            echo "</table>";
            echo "</div>";
            echo "</div>";
        }
    ?>
</main>
<?php require_once 'inc/footer.php'; ?>