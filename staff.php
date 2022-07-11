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
    $staffDetails = get_staffDetails($id);

    if (!empty($staffDetails)) {
        echo "<h2><a href='" . $staffDetails['siteUrl'] . "' target='_blank'>" . $staffDetails['name']['userPreferred'] . "</a></h2>";

        echo "<div class='flex-container'>";
        echo "<div id='cover'>";
        echo "<img src='" . $staffDetails['image']['large']. "' alt='cover'>";
        echo "</div>";
        echo "<div class='details' class='clearfix'>";
        echo "<p>" . $staffDetails['description'] . "</p>";
        echo "<hr>";
        echo "<table>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>Language</th>";
        echo "<th>Birthday</th>";
        echo "<th>Date of Death</th>";
        echo "<th>Age</th>";
        echo "<th>Years Active</th>";
        echo "<th>Gender</th>";
        echo "<th>Primary Occupations</th>";
        echo "<th>Favorites</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        echo "<tr>";
        echo "<td>" . $staffDetails['languageV2'] . "</td>";
        echo "<td>" . $staffDetails['dateOfBirth']['year'] . "-" . $staffDetails['dateOfBirth']['month'] . "-" . $staffDetails['dateOfBirth']['day'] . "</td>";
        echo "<td>" . $staffDetails['dateofDeath']['year'] . "-" . $staffDetails['dateOfDeath']['month'] . "-" . $staffDetails['dateofDeath']['day'] . "</td>";
        echo "<td>" . $staffDetails['age'] . "</td>";
        echo "<td>" . $staffDetails['yearsActive'][0] . "-" . $staffDetails['yearsActive'][1] . "</td>";
        echo "<td>" . $staffDetails['gender'] . "</td>";
        echo "<td>" . $staffDetails['primaryOccupations'] . "</td>";
        echo "<td>" . $staffDetails['favourites'] . "</td>";
        echo "</tr";
        echo "</tbody>";
        echo "</table>";
        echo "</div>";
        echo "</div>";
    }

    // Get media related to the staff.
    $staffMedia = get_staffMedia($id);

    if (!empty($staffMedia)) {
        echo "<h3>Related Media</h3>";
        echo '<table>';
        echo '<tbody>';
        echo '<tr>';
        for ($i = 0; $i < count($staffMedia); $i++) {
        ?>
            <td><a href="anime.php?id=<?php echo $staffMedia[$i]['id']; ?>"><img src="<?php echo $staffMedia[$i]['coverImage']['medium']; ?>" alt='cover' title="<?php echo $staffMedia[$i]['title']['romaji']; ?>" width="100" height="150"></a></td>
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