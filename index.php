***REMOVED***
***REMOVED***
//if (!isset($_SESSION['username']))
//    header('Location: login.php');
require_once 'include/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EpicTunes</title>
    <link rel="stylesheet" href="./assets/styles.css"/>
</head>
<body>
<div class="sidebar">
    <a href="index.php" class="logo">
        <img src="./assets/img/logo.svg" alt="Logo"/>
        EpicTunes
    </a>
    <nav>
        <p>Menu</p>
        <ul>
            <li><img src="./assets/img/icons/home.svg" alt=""/>Discover</li>
            <li><img src="./assets/img/icons/explore.svg" alt=""/>Explore</li>
            <li><img src="./assets/img/icons/search.svg" alt=""/>Search</li>
        </ul>
        <p>Library</p>
        <ul>
            <li class="selected"><img src="./assets/img/icons/music.svg" alt=""/>My Music</li>
            <li><img src="./assets/img/icons/album.svg" alt=""/>Albums</li>
            <li><img src="./assets/img/icons/artist.svg" alt=""/>Artists</li>
        </ul>
        <p>Others</p>
        <ul>
            <li><img src="./assets/img/icons/settings.svg" alt=""/>Settings</li>
            <li><a href="logout.php"><img src="./assets/img/icons/logout.svg" alt=""/>Log Out</a></li>
        </ul>
    </nav>
</div>
<div class="container">
    <div class="top">
        <label>
            <input type="text" placeholder="Search..." name="search-box"/>
        </label>
        <div class="profile-photo">
            <img src="./assets/img/icons/avatar.svg" alt=""/>
            John Doe
        </div>
    </div>

    <main>
        <h1>My Music</h1>
    </main>

    <div class="player"></div>
</div>
</body>
</html>
