<?php
session_start();
require_once 'inc/functions.php';

// Get current character id.
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
        // Get current character details.
        $data = get_characterDetails($id);

        if (!empty($data)) {
            echo "<h2><a href='" . $data['siteUrl'] . "' target='_blank'>" . $data['name']['userPreferred'] . "</a></h2>";

            echo "<div class='flex-container'>";
            echo "<div id='cover'>";
            echo "<img src='" . $data['image']['large']. "' alt='cover'>";
            echo "</div>";
            echo "<div class='details' class='clearfix'>";
            echo "<p>" . $data['description'] . "</p>";
            echo "<hr>";
            echo "<table>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Birthday</th>";
            echo "<th>Age</th>";
            echo "<th>Gender</th>";
            echo "<th>Favorites</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            echo "<tr>";
            echo "<td>" . $data['dateOfBirth']['year'] . "-" . $data['dateOfBirth']['month'] . "-" . $data['dateOfBirth']['day'] . "</td>";
            echo "<td>" . $data['age'] . "</td>";
            echo "<td>" . $data['gender'] . "</td>";
            echo "<td>" . $data['favourites'] . "</td>";
            echo "</tr";
            echo "</tbody>";
            echo "</table>";
            echo "</div>";
            echo "</div>";
        }
    ?>
</main>
<?php require_once 'inc/footer.php'; ?>