<nav>
    <img onclick="toggleMenu()" class="menu-toggle" src="/assets/img/icons/menu.svg" alt="Menu"/>
    <div class="search-box">
        <form action="search.php">
            <label>
                <input type="text" placeholder="Search..." class="search-box" name="query"/>
            </label>
        </form>
    </div>
    <div class="profile-photo">
        <a href="user_settings.php">
            <div class="flex-container">
                <img src="<?= $_SESSION['profile_pic'] ?? './assets/img/icons/avatar.svg' ?>" alt=""/>
                <p><?= $_SESSION['name'] ?? 'Unknown' ?></p>
            </div>
        </a>
    </div>
</nav>