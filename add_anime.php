<?php
session_start();
require_once 'inc/functions.php';

// Get current anime id.
$id = $_GET['id'];

require_once 'inc/header.php';
?>
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
    <form method="post">
        <label for="status">Status</label>
        <select id="status" name="status">
            <option value="CURRENT">Currently watching</option>
            <option value="COMPLETED">Completed</option>
            <option value="PLANNING">Plan to watch</option>
            <option value="PAUSED">Paused</option>
            <option value="DROPPED">Dropped</option>
            <option value="REPEATING">Repeating</option>
        </select><br>
        <label for="score">Score</label>
        <input type="text" id="score" name="score" value="0"><br>
        <label for="progress">Progress</label>
        <input type="number" id="progress" name="progress" value="0"><br>
        <button type="submit">Add anime</button>
    </form>
    <?php 
        $status = htmlspecialchars($_POST['status']);
        $score = htmlspecialchars($_POST['score']);
        $progress = htmlspecialchars($_POST['progress']);

        if (add_anime($_SESSION['accessToken'], $id, $status, $score, $progress)) {
            echo "<p class='success'>Anime successfully added.</p>";
        } else {
            echo "<p class='warning'>Adding anime failed.</p>";
        }
    ?>
</main>
<?php require_once 'inc/footer.php'; ?>