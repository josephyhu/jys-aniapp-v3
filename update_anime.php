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
    <?php
    $data = get_userAnimeDetails($_SESSION['userId'], $id);
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
    $status_arr = array('CURRENT', 'COMPLETED', 'PLANNING', 'PAUSED', 'DROPPED', 'REPEATING');
    ?>
    <form method="post">
        <label for="status">Status</label>
        <select id="status" name="status">
            <?php for ($i = 0; $i < count($status_arr); $i++) { ?>
                <option value="<?php echo $status_arr[$i]; ?>" <?php echo ($status_arr[$i] == $data['status']) ? 'selected' : ''; ?>><?php echo ucfirst(strtolower($status_arr[$i])); ?></option>
            <? } ?>
        </select><br>
        <label for="startedAt">Start Date</label>
        <input type="date" id="startedAt" name="startedObj" value="<?php echo $startedYear . '-' . $startedMonth . '-' . $startedDay; ?>">
        <label for="completedAt">Completed Date</label>
        <input type="date" id="completedAt" name="completedObj" value="<?php echo $completedYear . '-' . $completedMonth . '-' . $completedDay; ?>"><br>
        <label for="score">Score</label>
        <input type="text" id="score" name="score" value="<?php echo $data['score']; ?>">
        <label for="progress">Progress</label>
        <input type="number" id="progress" name="progress" value="<?php echo $data['progress']; ?>"><br>
        <button type="submit">Update anime</button>
    </form>
    <?php 
        $status = htmlspecialchars($_POST['status']);
        $startedObj = htmlspecialchars($_POST['startedObj']);
        $completedObj = htmlspecialchars($_POST['completedObj']);
        $startedAt = [];
        $startedAt['year'] = substr($startedObj, 0, 4);
        $startedAt['month'] = substr($startedObj, 5, 2);
        $startedAt['day'] = substr($startedObj, 8, 2);
        $completedAt = [];
        $completedAt['year'] = substr($completedObj, 0, 4);
        $completedAt['month'] = substr($completedObj, 5, 2);
        $completedAt['day'] = substr($completedObj, 8, 2);
        $score = htmlspecialchars($_POST['score']);
        $progress = htmlspecialchars($_POST['progress']);
        if (update_anime($_SESSION['accessToken'], $id, $status, $startedAt, $completedAt, $score, $progress)) {
            echo "<p class='success'>Anime successfully updated.</p>";
        } else {
            echo "<p class='warning'>Updating anime failed.</p>";
        }

    ?>
</main>
<?php require_once 'inc/footer.php'; ?>