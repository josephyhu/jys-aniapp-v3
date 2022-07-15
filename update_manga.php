<?php
session_start();
require_once 'inc/functions.php';

// Get current manga id.
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
    $data = get_userMangaDetails($_SESSION['userId'], $id);
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
        <label for="score">Score</label>
        <input type="text" id="score" name="score" value="<?php echo (!empty($data['score'])) ? $data['score'] : 0; ?>"><br>
        <label for="progress">Progress</label>
        <input type="number" id="progress" name="progress" value="<?php echo (!empty($data['progress'])) ? $data['progress'] : 0; ?>"><br>
        <label for="progressVolumes">Volume progress</label>
        <input type="number" id="progressVolumes" name="progressVolumes" value="<?php echo (!empty($data['progressVolumes'])) ? $data['progressVolumes'] : 0; ?>"><br>
        <button type="submit">Update manga</button>
    </form>
    <?php 
        $status = htmlspecialchars($_POST['status']);
        $score = htmlspecialchars($_POST['score']);
        $progress = htmlspecialchars($_POST['progress']);
        $progressVolumes = htmlspecialchars($_POST['progressVolumes']);

        if (update_manga($_SESSION['accessToken'], $id, $status, $score, $progress, $progressVolumes)) {
            echo "<p class='success'>Manga successfully updated.</p>";
        } else {
            echo "<p class='warning'>Updating manga failed.</p>";
        }

    ?>
</main>
<?php require_once 'inc/footer.php'; ?>