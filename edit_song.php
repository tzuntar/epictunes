<?php
session_start();
$document_title = 'Edit Song';
if (!isset($_SESSION['identifier']))
    header('Location: login.php');
if (!isset($_GET['id']))
    header('Location: ' . $_SERVER['HTTP_REFERER']);
require_once 'utils/queries.php';
require_once 'utils/mp3.php';
$song = Song::get($_GET['id']);
if (!$_SESSION['is_admin'] && !$song->check_ownership($_SESSION['id']))
    header('Location: ' . $_SERVER['HTTP_REFERER']);

if (isset($_POST['title'])) {
    $newSong = new Song();
    $newSong->file_url = $_POST['filename'];
    $newSong->title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $artistsEntry = filter_input(INPUT_POST, 'artist', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if (!trim($artistsEntry) == '') {
        $artistsEntry = str_replace(', ', ',', $artistsEntry);
        foreach (explode(',', $artistsEntry) as $a) {
            $artist = new Artist();
            $artist->name = $a;
            $newSong->artists[] = $artist;
        }
    }
    $newSong->album = new Album();
    $newSong->album->name = filter_input(INPUT_POST, 'album', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $albumArtists = filter_input(INPUT_POST, 'album_artist', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if (!trim($albumArtists) == '') {
        $albumArtists = str_replace(', ', ',', $albumArtists);
        foreach (explode(',', $albumArtists) as $a) {
            $artist = new Artist();
            $artist->name = $a;
            $newSong->album->artists[] = $artist;
        }
    }
    $newSong->genre = new Genre();
    $newSong->genre->name = filter_input(INPUT_POST, 'genre', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $tags = filter_input(INPUT_POST, 'tags', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if (!trim($tags) == '') {
        $tags = str_replace(', ', ',', $tags);
        foreach (explode(',', $tags) as $t) {
            $tag = new SongTag();
            $tag->name = $t;
            $newSong->tags[] = $tag;
        }
    }
    $result = $newSong->insert();
    $song->delete();
    if ($result) {
        if ($_SESSION['is_admin'])
            header('Location: admin_songs.php');
        header('Location: song.php?id=' . $result->id);
    }
}

$albumArtists = '';
$songTags = '';
foreach ($song->album->artists as $artist)
    $albumArtists .= $artist->name . ', ';
foreach ($song->tags as $tag)
    $songTags .= $tag->name . ', ';
$albumArt = mp3_get_album_art('userdata/music/' . $song->file_url);
$artistsNotIn = Artist::get_artists_not_in($song);

include_once 'include/header.php';
include_once 'include/sidebar.php' ?>
<div class="root-container">
    <?php include_once 'include/top-nav.php' ?>
    <link rel="stylesheet" href="/assets/combobox.css"/>
    <main>
        <h2 class="accent padding-20">Edit Song Data</h2>
        <form class="margin-top-20" method="post" enctype="multipart/form-data">
            <div class="grid-container meta-edit-grid">
                <div class="grid-col">
                    <table class="edit-table">
                        <tr>
                            <td><p class="field-label">Title:</p></td>
                            <td><label>
                                    <input type="text" name="title" required placeholder="Song title"
                                           value="<?= $song->title ?>"/>
                                </label></td>
                        </tr>
                        <tr class="vertical-bottom">
                            <td><p class="field-label">Artists:</p></td>
                            <td>
                                <span id="combo3-remove" style="display: none">remove</span>
                                <ul class="selected-options" id="artist-combo-selected"></ul>
                                <div class="combo js-multiselect">
                                    <input aria-activedescendant=""
                                           aria-autocomplete="none"
                                           aria-controls="artist-listbox"
                                           aria-expanded="false"
                                           aria-haspopup="listbox"
                                           aria-labelledby="artist-combo artist-combo-selected"
                                           id="artist-combo"
                                           name="artist-combo"
                                           class="combo-input"
                                           role="combobox"
                                           placeholder="Search for artists..."
                                           type="text"/>
                                    <div class="combo-menu" role="listbox" id="artist-listbox"></div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><p class="field-label">Album:</p></td>
                            <td><label>
                                    <input type="text" name="album" required placeholder="Album name"
                                           value="<?= $song->album->name ?? '' ?>"/>
                                </label></td>
                        </tr>
                        <tr>
                            <td><p class="field-label">...by:</p></td>
                            <td><label>
                                    <input type="text" name="album_artist" required
                                           placeholder="Album artist names (separated by commas)"
                                           value="<?= isset($albumArtists) ? rtrim($albumArtists, ', ') : '' ?>"/>
                                </label></td>
                        </tr>
                        <tr>
                            <td><p class="field-label">Genre:</p></td>
                            <td><label>
                                    <input type="text" name="genre" required placeholder="Song genre"
                                           value="<?= $song->genre->name ?? '' ?>"/>
                                </label></td>
                        </tr>
                        <tr>
                            <td><p class="field-label">Tags:</p></td>
                            <td><label>
                                    <input type="text" name="tags" placeholder="Song tags (separated by commas)"
                                           value="<?= isset($songTags) ? rtrim($songTags, ', ') : '' ?>"/>
                                </label></td>
                        </tr>
                    </table>
                </div>
                <div class="grid-col">
                    <img src="<?= $albumArt ?: '/assets/img/icons/avatar.svg' ?>" alt="Album Art"
                         class="album-art-big" id="albumArtBox"/>
                </div>
            </div>
            <p>
                <input type="hidden" name="filename" value="<?= $song->file_url ?>" readonly/>
                <input type="hidden" name="artist" value="" id="artistsHidden"/>
            </p>
            <p class="header-center">
                <input type="submit" value="Save" class="margin-top-20"/>
            </p>
            <?php if ($_SESSION['is_admin']) {
                $comments = $song->get_comments(); ?>
                <h2 class="accent margin-lr-20 light-underline">Comments</h2>
                <section class="comment-division padding-lr-20">
                    <?php if (!$comments || sizeof($comments) < 1) { ?>
                        <p class="fine-print">No comments yet</p>
                    <?php } else {
                        foreach ($comments as $comment) { ?>
                            <div class="grid-container comment-grid">
                                <div class="grid-col">
                                    <a href="artist.php?id=<?= $comment['id_user'] ?>">
                                        <img alt="Profile Photo" class="round-profile-photo"
                                             src="<?= $comment['profile_pic_url'] ?? './assets/img/icons/avatar.svg' ?>"/></a>
                                </div>
                                <div class="grid-col">
                                    <p>
                                        <strong><?= $comment['user_name'] ?></strong>
                                        at
                                        <strong><?= date('d. m. Y, H:i', strtotime($comment['date_time'])) ?></strong> •
                                        <a href="delete_comment.php?id=<?= $comment['id_comment'] ?>"><em>Delete</em></a>
                                    </p>
                                    <p><?= $comment['content'] ?></p>
                                </div>
                            </div>
                        <?php }
                    } ?>
                </section>
            <?php } ?>
        </form>
    </main>
    <script src="/utils/combobox.js"></script>
    <script>
        const artists = [];
        <?php if (isset($song->artists))
            foreach ($song->artists as $artist)
                echo "artists.push({text: `" . html_entity_decode($artist->name) . "`, selected: true});";
        foreach ($artistsNotIn as $entry)
            echo "artists.push({text: `" . html_entity_decode($entry['name']) . "`, selected: false});" ?>
        const comboBox = initMultiselect('.js-multiselect', artists);

        function syncArtists() {
            let items = '';
            comboBox.selectedEl.querySelectorAll('li').forEach(li => items += li.outerText.replace('"', '\\"') + ',');
            document.getElementById('artistsHidden').value = items.substring(0, items.length - 1);
        }

        syncArtists();
        document.getElementById('artist-combo').addEventListener('change', () => syncArtists(), false);
    </script>
</div>
<?php include_once 'include/footer.php' ?>
