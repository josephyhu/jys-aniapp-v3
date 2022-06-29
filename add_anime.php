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
        <button type="submit">Add anime</button>
    </form>
    <?php 
        $status = htmlspecialchars($_POST['status']);

        if (add_anime($_SESSION['accessToken'], $id, $status)) {
            echo "<p class='success'>Anime successfully added.</p>";
        }
    ?>
</main>
<?php require_once 'inc/footer.php'; ?>