<?php
session_start();
require_once 'inc/functions.php';
require_once 'inc/header.php'; ?>
<main>
    <?php
    // Check if the user has logged in, and redirect the user to the homepage if he/she hasn't.
    if (!isset($_SESSION['userId'])) {
        header('Location: index.php?logged_in=0');
    }
    ?>
    <div class="links">
        <a href="index.php?logged_in=1">Home</a>
        <a href="animelist.php">Anime List</a>
        <a href="mangalist.php">Manga List</a>
        <a href="search.php">Search</a>
    </div>
    <div class="logout"><a href="logout.php">Log out</a></div>
    <h2><?php echo $_SESSION['username'] . "'s Manga List"; ?></h2>
    <form method="post">
        <label for="status">Status<span class="required">*</span></label>
        <select id="status" name="status" required>
            <option value="CURRENT">Currently reading</option>
            <option value="COMPLETED">Completed</option>
            <option value="PLANNING">Plan to read</option>
            <option value="PAUSED">Paused</option>
            <option value="DROPPED">Dropped</option>
            <option value="REPEATING">Repeating</option>
        </select><br>
        <button type="submit">View your list</button>
    </form>
    <?php
    $status = htmlspecialchars($_POST['status']);

    try {
        if (!empty($status)) {
            $userMangaList = get_userMangaList($_SESSION['userId'], $status);
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }

    if (!empty($userMangaList)) {
        if ($status === 'CURRENT') {
            echo '<h3>Currently Reading</h3>';
        } else if ($status === 'COMPLETED') {
            echo '<h3>Completed</h3>';
        } else if ($status === 'PLANNING') {
            echo '<h3>Plan to Read</h3>';
        } else if ($status === 'PAUSED') {
            echo '<h3>Paused</h3>';
        } else if ($status === 'DROPPED') {
            echo '<h3>Dropped</h3>';
        } else {
            echo '<h3>Repeating</h3>';
        }
        echo '<table>';
        echo '<tbody>';
        echo '<tr>';
        for ($i = 0; $i < count($userMangaList); $i++) {
        ?>
            <td><a href="manga.php?id=<?php echo $userMangaList[$i]['media']['id']; ?>"><img src="<?php echo $userMangaList[$i]['media']['coverImage']['medium']; ?>" alt='cover' title="<?php echo $userMangaList[$i]['media']['title']['romaji']; ?>" width="100" height="150"></a></td>
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