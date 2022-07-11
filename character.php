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
    $characterDetails = get_characterDetails($id);

    if (!empty($characterDetails)) {
        echo "<h2><a href='" . $characterDetails['siteUrl'] . "' target='_blank'>" . $characterDetails['name']['userPreferred'] . "</a></h2>";

        echo "<div class='flex-container'>";
        echo "<div id='cover'>";
        echo "<img src='" . $characterDetails['image']['large']. "' alt='cover'>";
        echo "</div>";
        echo "<div class='details' class='clearfix'>";
        echo "<p>" . $characterDetails['description'] . "</p>";
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
        echo "<td>" . $characterDetails['dateOfBirth']['year'] . "-" . $characterDetails['dateOfBirth']['month'] . "-" . $characterDetails['dateOfBirth']['day'] . "</td>";
        echo "<td>" . $characterDetails['age'] . "</td>";
        echo "<td>" . $characterDetails['gender'] . "</td>";
        echo "<td>" . $characterDetails['favourites'] . "</td>";
        echo "</tr";
        echo "</tbody>";
        echo "</table>";
        echo "</div>";
        echo "</div>";
    }

    // Get media related to the character.
    $characterMedia = get_characterMedia($id);

    if (!empty($characterMedia)) {
        echo "<h3>Related Media</h3>";
        echo '<table>';
        echo '<tbody>';
        echo '<tr>';
        for ($i = 0; $i < count($characterMedia); $i++) {
        ?>
            <td><a href="anime.php?id=<?php echo $characterMedia[$i]['id']; ?>"><img src="<?php echo $characterMedia[$i]['coverImage']['medium']; ?>" alt='cover' title="<?php echo $characterMedia[$i]['title']['romaji']; ?>" width="100" height="150"></a></td>
        <?php
            if (substr($i, -1) == 9) {
                echo '</tr><tr>';
            }
        }
        echo '</tr>';
        echo '</tbody>';
        echo '</table>';
    }
    ?>
</main>
<?php require_once 'inc/footer.php'; ?>