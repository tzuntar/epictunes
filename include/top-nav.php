<nav>
    <div class="search-box">
        <label>
            <input type="text" placeholder="Search..." name="search-box"/>
        </label>
    </div>
    <div class="profile-photo">
        <div class="flex-container">
            <img src="./assets/img/icons/avatar.svg" alt=""/>
            <p><?= $_SESSION['name'] ?? 'Unknown' ?></p>
        </div>
    </div>
</nav>