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
            $logged_in = 1;
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
            if (!empty($_SESSION['userId'])) {
                try {
                    $data = get_userStats($_SESSION['userId']);
                } catch (Exception $e) {
                    $e->getMessage();
                }
            }
            ?>
            <?php if (!empty($data)) { ?>
                <a href='<?php echo $data['siteUrl']; ?>' target='_blank'><img src='<?php echo $data['bannerImage']; ?>' alt='banner' class='banner'></a>
                <div class='flex-container'>
                    <div id='avatar'>
                        <img src='<?php echo $data['avatar']['large']; ?>' alt='avatar'>
                    </div>
                    <div id='bio' class='clearfix'>
                        <h3>Bio</h3>
                        <?php
                        echo $data['about'];
                        ?>
                    </div>
                </div>
                <div id='stats'>
                    <h3>Anime Stats</h3>
                    <?php $days_watched = $data['statistics']['anime']['minutesWatched']/1440; ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Count</th>
                                <th>Mean Score</th>
                                <th>Standard Deviation</th>
                                <th>Days Watched</th>
                                <th>Episodes Watched</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo $data['statistics']['anime']['count']; ?></td>
                                <td><?php echo $data['statistics']['anime']['meanScore']; ?></td>
                                <td><?php echo $data['statistics']['anime']['standardDeviation']; ?></td>
                                <td><?php echo $days_watched; ?></td>
                                <td><?php echo $data['statistics']['anime']['episodesWatched']; ?></td>
                            </tr>
                        </tbody>
                    </table>
                    <h3>Manga Stats</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Count</th>
                                <th>Mean Score</th>
                                <th>Standard Deviation</th>
                                <th>Chapters Read</th>
                                <th>Volumes Read</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo $data['statistics']['manga']['count']; ?></td>
                                <td><?php echo $data['statistics']['manga']['meanScore']; ?></td>
                                <td><?php echo $data['statistics']['manga']['standardDeviation']; ?></td>
                                <td><?php echo $data['statistics']['manga']['chaptersRead']; ?></td>
                                <td><?php echo $data['statistics']['manga']['volumesRead']; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            <?php } ?>
        <?php
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