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
    <?php if (!empty($userId)) { ?>
        <div class="logout"><a href="logout.php">Log out</a></div>
    <?php }
        $data = get_userAnimeDetails($userId, $id);
    ?>
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
        <label for="startedAt">Started date</label>
        <input type="date" id="startedAt" name="startedAt" value="<?php echo $data['startedAt']['year'] . '-' . $data['startedAt']['month'] . '-' . $data['startedAt']['day']; ?>"><br>
        <label for="completedAt">Completed date</label>
        <input type="date" id="completedAt" name="completedAt" value="<?php echo $data['completedAt']['year'] . '-' . $data['completedAt']['month'] . '-' . $data['completedAt']['day']; ?>"><br>
        <label for="score">Score</label>
        <input type="text" id="score" name="score" value="<?php echo $data['score']; ?>"><br>
        <label for="progress">Progress</label>
        <input type="number" id="progress" name="progress" value="<?php echo $data['progress']; ?>"><br>
        <button type="submit">Update anime</button>
    </form>
    <?php 
        $status = htmlspecialchars($_POST['status']);
        $startedAt = htmlspecialchars(str_replace('-', '', $_POST['startedAt']));
        $completedAt = htmlspecialchars(str_replace('-', '', $_POST['completedAt']));
        $score = htmlspecialchars($_POST['score']);
        $progress = htmlspecialchars($_POST['progress']);

        if (update_anime($_SESSION['accessToken'], $id, $status, $startedAt, $completedAt, $score, $progress)) {
            echo "<p class='success'>Anime successfully updated.</p>";
        } else {
            echo "<p class='warning'>Updating anime failed.</p>";
        }

    ?>
</main>
<script>
    console.log(document.getElementById("startedAt").value);
</script>
<?php require_once 'inc/footer.php'; ?>