<?php
session_start();
require_once 'inc/functions.php';
require_once 'inc/header.php'; ?>
<main>
    <?php
    if (!isset($_SESSION['userId'])) {
        header('Location: index.php?logged_in=0');
    }
    ?>
    <div class="links">
        <a href="index.php">Home</a>
        <a href="animelist.php">Anime List</a>
        <a href="mangalist.php">Manga List</a>
        <a href="search.php">Search</a>
    </div>
    <div class="logout"><a href="logout.php">Log out</a></div>
    <h2><?php echo $_SESSION['username'] . "'s Anime List"; ?></h2>
    <form method="post">
        <label for="status">Status<span class="required">*</span></label>
        <select id="status" name="status" required>
            <option value="CURRENT">Currently watching</option>
            <option value="COMPLETED">Completed</option>
            <option value="PLANNING">Plan to watch</option>
            <option value="PAUSED">Paused</option>
            <option value="DROPPED">Dropped</option>
            <optino value="REPEATING">Repeating</option>
        </select><br>
        <button type="submit">View your list</button>
    </form>
    <?php
    $status = htmlspecialchars($_POST['status']);

    try {
        if (!empty($status)) {
            $data = get_userAnimeList($_SESSION['userId'], $status);
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }

    if (!empty($data)) {
        if ($status === 'CURRENT') {
            var_dump($data);
            echo '<h3>Currently Watching</h3>';
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
        if ($status === 'COMPLETED') {
            echo '<h3>Completed</h3>';
            echo '<table>';
            echo '<tbody>';
            echo '<tr>';
            for ($i = 0; $i < count($data); $i++) {
                echo "<td><img src='" . $data[$i]['coverImage']['medium'] . "' alt='cover'></td>";

                if (substr($i, -1) == 9) {
                    echo '</tr><tr>';
                }
            }
            echo '</tr>';
            echo '</tbody>';
            echo '</table>';
        }
        if ($status === 'PLANNING') {
            echo '<h3>Plan to Watch</h3>';
            echo '<table>';
            echo '<tbody>';
            echo '<tr>';
            for ($i = 0; $i < count($data); $i++) {
                echo "<td><img src='" . $data[$i]['coverImage']['medium'] . "' alt='cover'></td>";

                if (substr($i, -1) == 9) {
                    echo '</tr><tr>';
                }
            }
            echo '</tr>';
            echo '</tbody>';
            echo '</table>';
        }
        if ($status === 'PAUSED') {
            echo '<h3>Paused</h3>';
            echo '<table>';
            echo '<tbody>';
            echo '<tr>';
            for ($i = 0; $i < count($data); $i++) {
                echo "<td><img src='" . $data[$i]['coverImage']['medium'] . "' alt='cover'></td>";

                if (substr($i, -1) == 9) {
                    echo '</tr><tr>';
                }
            }
            echo '</tr>';
            echo '</tbody>';
            echo '</table>';
        }
        if ($status === 'DROPPED') {
            echo '<h3>Dropped</h3>';
            echo '<table>';
            echo '<tbody>';
            echo '<tr>';
            for ($i = 0; $i < count($data); $i++) {
                echo "<td><img src='" . $data[$i]['coverImage']['medium'] . "' alt='cover'></td>";

                if (substr($i, -1) == 9) {
                    echo '</tr><tr>';
                }
            }
            echo '</tr>';
            echo '</tbody>';
            echo '</table>';
        }
        if ($status === 'REPEATING') {
            echo '<h3>Repeating</h3>';
            echo '<table>';
            echo '<tbody>';
            echo '<tr>';
            for ($i = 0; $i < count($data); $i++) {
                echo "<td><img src='" . $data[$i]['coverImage']['medium'] . "' alt='cover'></td>";

                if (substr($i, -1) == 9) {
                    echo '</tr><tr>';
                }
            }
            echo '</tr>';
            echo '</tbody>';
            echo '</table>';
        }
    }
    ?>
</main>
<?php require_once 'inc/footer.php'; ?>