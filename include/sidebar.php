<div class="sidebar">
    <a href="index.php" class="logo">
        <img src="./assets/img/logo.svg" alt="Logo"/>
        EpicTunes
    </a>
    <nav>
        <p>Menu</p>
        <ul>
            <li><a href="index.php"><img src="./assets/img/icons/home.svg" alt=""/>My Music</a></li>
            <li><a href="upload_song.php"><img src="./assets/img/icons/upload.svg" alt=""/>Upload</a></li>
            <li><a href="search.php"><img src="./assets/img/icons/search.svg" alt=""/>Search</a></li>
        </ul>
        <p>Library</p>
        <ul>
            <li><a href="albums.php"><img src="./assets/img/icons/album.svg" alt=""/>Albums</a></li>
            <li><a href="artists.php"><img src="./assets/img/icons/artist.svg" alt=""/>Artists</a></li>
        </ul>
        ***REMOVED*** if ($_SESSION['is_admin']) { ?>
            <p>Administration</p>
            <ul>
                <li><a href="admin_stats.php"><img src="./assets/img/icons/stats.svg" alt=""/>Stats</a></li>
                <li><a href="admin_songs.php"><img src="./assets/img/icons/music.svg" alt=""/>Manage Songs</a></li>
                <li><a href="admin_users.php"><img src="./assets/img/icons/cohort.svg" alt=""/>Manage Users</a></li>
            </ul>
        ***REMOVED*** } ?>
        <p>Account</p>
        <ul>
            <li><a href="posted_songs.php"><img src="./assets/img/icons/music.svg" alt=""/>Posted Songs</a></li>
            <li><a href="user_settings.php"><img src="./assets/img/icons/settings.svg" alt=""/>Preferences</a></li>
            <li><a href="logout.php"><img src="./assets/img/icons/logout.svg" alt=""/>Log Out</a></li>
        </ul>
    </nav>
</div>
