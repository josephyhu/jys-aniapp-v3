<?php
session_start();
require_once 'inc/functions.php';

$id = $_GET['id'];
$userId = $_GET['userId'];

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
    <?php }
        $data = get_userAnimeDetails($userId, $id);
     ?>
    <form method="post">
        <label for="status">Status</label>
        <select id="status" name="status" required>
            <option value="CURRENT">Currently watching</option>
            <option value="COMPLETED">Completed</option>
            <option value="PLANNING">Plan to watch</option>
            <option value="PAUSED">Paused</option>
            <option value="DROPPED">Dropped</option>
            <option value="REPEATING">Repeating</option>
        </select><br>
        <label for="score">Score</label>
        <input id="score" name="score" value="<?php echo $data['score'] ?>"><br>
        <label for="progress">Progress</label>
        <input id="progress" name="progress" value="<?php echo $data['progress'] ?>"><br>
        <button type="submit">Update anime</button>
    </form>
    <?php 
        $status = htmlspecialchars($_POST['status']);
        $score = htmlspecialchars($_POST['score']);
        $progress = htmlspecialchars($_POST['progress']);

        if (update_anime($_SESSION['accessToken'], $id, $status, $score, $progress)) {
            echo "<p class='success'>Anime successfully updated.</p>";
        } else {
            echo "<p class='warning'>Updating anime failed.</p>";
        }
    ?>
</main>
<?php require_once 'inc/footer.php'; ?>