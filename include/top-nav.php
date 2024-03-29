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
<?php if (isset($_SESSION['identifier'])) {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/utils/queries.php';
    global $top_level;
    $notifications = User::check_notifications($_SESSION['id'], false);
    if ($notifications) {
        foreach ($notifications as $n) { ?>
            <div class="notification-bar notification-<?= str_replace('_', '-', $n['type']) ?>">
                <p><strong>⚠ <?= $n['content'] ?></strong> •
                    <?= date('d. m. Y, H:i', strtotime($n['date_time'])) ?>
                    <button onclick="fetch('<?= $top_level ?>/utils/dismissNotification.php?id=<?= $n['id_notification'] ?>').then(() => location.reload())">
                        Dismiss
                    </button>
                </p>
            </div>
        <?php }
    }
}