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
    <form method="post">
        <label for="status">Status<span class="required">*</span></label>
        <select id="status" name="status" required>
            <option value="CURRENT">Currently watching</option>
            <option value="COMPLETED">Completed</option>
            <option value="PLANNING">Plan to watch</option>
            <option value="PAUSED">Paused</option>
            <option value="DROPPED">Dropped</option>
            <option value="REPEATING">Repeating</option>
        </select><br>
        <label for="startedAt">Started date</label>
        <input type="date" id="startedAt" name="startedAt"><br>
        <label for="completedAt">Completed date</label>
        <input type="date" id="completedAt" name="completedAt"><br>
        <label for="score">Score</label>
        <input type="text" id="score" name="score"><br>
        <label for="progress">Progress</label>
        <input type="number" id="progress" name="progress"><br>
        <button type="submit">Add anime</button>
    </form>
    <?php 
        $status = htmlspecialchars($_POST['status']);
        $startedAt = htmlspecialchars($_POST['startedAt']);
        $startedObj = [
            "year" => substr($startedAt, 0, 4),
            "month" => substr($startedAt, 5, 2),
            "day" => substr($startedAt, 8, 2),
        ];
        $completedAt = htmlspecialchars($_POST['completedAt']);
        $completedObj = [
            "year" => substr($completedAt, 0, 4),
            "month" => substr($completedAt, 5, 2),
            "day" => substr($completedAt, 8, 2),
        ];
        $score = htmlspecialchars($_POST['score']);
        $progress = htmlspecialchars($_POST['progress']);

        if (add_anime($_SESSION['accessToken'], $id, $status, $startedObj, $completedObj, $score, $progress)) {
            echo "<p class='success'>Anime successfully added.</p>";
        } else {
            echo "<p class='warning'>Adding anime failed.</p>";
        }
    ?>
</main>
<?php require_once 'inc/footer.php'; ?>