<nav>
    <div class="search-box">
        <form action="search.php">
            <label>
                <input type="text" placeholder="Search..." class="search-box" name="query"/>
            </label>
        </form>
    </div>
    <div class="profile-photo">
        <div class="flex-container">
            <img src="./assets/img/icons/avatar.svg" alt=""/>
            <p><a href="user_settings.php"><?= $_SESSION['name'] ?? 'Unknown' ?></a></p>
        </div>
    </div>
</nav>