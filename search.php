<?php
session_start();
require_once 'inc/functions.php';
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
    echo "<form method='post'>";
        echo "<label for='type'>Type<span class='required'>*</span></label><br>";
        echo "<input type='radio' id='anime' name='type' value='ANIME' required><label for='anime'>Anime</label> ";
        echo "<input type='radio' id='manga' name='type' value='MANGA'><label for='manga'>Manga</label><br>";
        echo "<label for='search'>Search<span class='required'>*</span></label> ";
        ?>
        <input type='text' id='search' name='search' value='<?php echo isset($_POST['search']) ? htmlspecialchars($_POST['search']) : ''?>' required><br>
        <?php
        echo "<button type='submit'>Search</button><br>";
        echo "</form>";
        $type = htmlspecialchars($_POST['type']);
        $search = htmlspecialchars($_POST['search']);
        if (!empty($type) && !empty($search)) {
            echo "<h2>Searched for " . $search . " in " . $type . "</h2>";
        }
        echo "<div>";
        try {
            if (!empty($type) && !empty($search)) {
                $data = search_media($type, $search);
            }
            if (!empty($data)) {
                echo '<table>';
                echo '<tbody>';
                echo '<tr>';
                for ($i = 0; $i < count($data); $i++) {
                    echo "<td><img src='" . $data[$i]['media']['coverImage']['medium'] . "' alt='cover'></td>";

                    if (substr($i, -1) == 9) {
                        echo '</tr><tr>';
                    }
                }
                echo '</tr>';
                echo '</tbody>';
                echo '</table>';
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        echo "</div>";
        ?>
</main>
<?php require_once 'inc/footer.php'; ?>