<?php
session_start();
require_once 'inc/functions.php';

$id = $_GET['id'];
$userId = $_GET['userId'];

require_once 'inc/header.php';
?>
<main>
<?php
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
    <?php
    $data = get_userMangaDetails($userId, $id);
    $startedYear = $data['startedAt']['year'];
    if ($data['startedAt']['month'] < 10) {
        $startedMonth = '0' . $data['startedAt']['month'];
    } else {
        $startedMonth = $data['startedAt']['month'];
    }
    if ($data['startedAt']['day'] < 10) {
        $startedDay = '0' . $data['startedAt']['day'];
    } else {
        $startedDay = $data['startedAt']['day'];
    }
    $completedYear = $data['completedAt']['year'];
    if ($data['completedAt']['month'] < 10) {
        $completedMonth = '0' . $data['completedAt']['month'];
    } else {
        $completedMonth = $data['comletedAt']['month'];
    }
    if ($data['completedAt']['day'] < 10) {
        $completedDay = '0' . $data['completedAt']['day'];
    } else {
        $completedDay = $data['completedAt']['day'];
    }
    ?>
    <form method="post">
        <label for="status">Status</label>
        <select id="status" name="status">
            <option value="CURRENT">Currently reading</option>
            <option value="COMPLETED">Completed</option>
            <option value="PLANNING">Plan to read</option>
            <option value="PAUSED">Paused</option>
            <option value="DROPPED">Dropped</option>
            <option value="REPEATING">Repeating</option>
        </select><br>
        <label for="startedAt">Started date</label>
        <input type="date" id="startedAt" name="startedAt" value="<?php echo $startedYear . '-' . $startedMonth . '-' . $startedDay; ?>"><br>
        <label for="completedAt">Completed date</label>
        <input type="date" id="completedAt" name="completedAt" value="<?php echo $completedYear . '-' . $completedMonth . '-' . $completedDay; ?>"><br>
        <label for="score">Score</label>
        <input type="text" id="score" name="score" value="<?php echo $data['score']; ?>"><br>
        <label for="progress">Progress</label>
        <input type="number" id="progress" name="progress" value="<?php echo $data['progress']; ?>"><br>
        <label for="progressVolumes">Volume progress</label>
        <input type="number" id="progressVolumes" name="progressVolumes" value="<?php echo $data['progressVolumes']; ?>"><br>
        <button type="submit">Update manga</button>
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
        $progressVolumes = htmlspecialchars($_POST['progressVolumes']);

        if (update_manga($_SESSION['accessToken'], $id, $status, $startedObj, $completedObj, $score, $progress, $progressVolumes)) {
            echo "<p class='success'>Manga successfully updated.</p>";
        } else {
            echo "<p class='warning'>Updating manga failed.</p>";
        }

    ?>
</main>
<?php require_once 'inc/footer.php'; ?>