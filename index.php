<?php
session_start();
require_once 'inc/functions.php';
$query = [
    'client_id' => '8687',
    'redirect_uri' => 'https://jys-aniapp-v3.herokuapp.com', // http://example.com/callback
    'response_type' => 'code'    
];


$url = 'https://anilist.co/api/v2/oauth/authorize?' . urldecode(http_build_query($query));
$code = $_GET['code'];
$logged_out = $_GET['logged_out'];
$logged_in = $_GET['logged_in'];

require_once 'inc/header.php';
?>
<main>
    <?php
        if (isset($code)) {
            $_SESSION['accessToken'] = get_token($code);
            $_SESSION['userId'] = get_userId($_SESSION['accessToken']);
            $_SESSION['username'] = get_username($_SESSION['userId']);
        }
        if ($logged_in == 1) {
            echo "<div class='links'><a href='index.php?logged_in=1'>Home</a>";
            echo "<a href='animelist.php'>Anime List</a>";
            echo "<a href='mangalist.php'>Manga List</a>";
            echo "<a href='search.php'>Search</a></div>";
            echo "<div class='logout'><a href='logout.php'>Log out</a></div>";
            echo "<h2>Welcome " . $_SESSION['username'] . "!</h2>";
        } else {
            echo "<div class='links'><a href='index.php'>Home</a>";
            echo "<a href='search.php'>Search</a></div>";
            echo "<div class='login'><a href='$url'>Log in with AniList</a></div>";
            if (isset($logged_in) && $logged_in == 0) {
                echo "<p class='warning'>You have to be logged in to use this feature.</p>";
            }
            if ($logged_out == 1) {
                echo "<p class='success'>Successfully logged out.</p>";
                echo "<p class='notice'>Be sure to revoke the app to finish logging out.</p>";
            }
            echo "<h2>Welcome guest!</h2>";
        }
    ?>
</main>
<?php require_once 'inc/footer.php'; ?>