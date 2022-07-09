<?php
session_start();
require_once 'inc/functions.php';
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
    <?php } ?>
    <?php
    echo "<form method='post'>";
        echo "<label for='type'>Type<span class='required'>*</span></label><br>";
        echo "<input type='radio' id='anime' name='type' value='ANIME' required><label for='anime'>Anime</label> ";
        echo "<input type='radio' id='manga' name='type' value='MANGA'><label for='manga'>Manga</label><br>";
        ?>
        <label for='search'>Search<span class='required'>*</span></label>
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
                    if ($type === 'ANIME') {
                    ?>
                        <td><a href='anime.php?id=<?php echo $data[$i]["id"]; ?>'><img src='<?php echo $data[$i]["coverImage"]["medium"]; ?>' alt='cover' title="<?php echo $data[$i]['title']['romaji']; ?>"></a></td>
                    <?php
                    } else {
                    ?>
                        <td><a href='manga.php?id=<?php echo $data[$i]["id"]; ?>'><img src='<?php echo $data[$i]["coverImage"]["medium"]; ?>' alt='cover' title="<?php echo $data[$i]['title']['romaji']; ?>"></a></td>
                    <?php
                    }

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
        $a = array();
        $users = test();
        for ($i = 0; $i < count($users); $i++) {
            array_push($a, $users[$i]['statistics']['anime']['count']);
        }
        echo max($a);
        ?>
</main>
<?php require_once 'inc/footer.php'; ?>