***REMOVED***
require_once 'mp3.php';

function render_song_list(array $songs, bool $adminEditControls = false) {
    foreach ($songs as $song) {
        $mp3path = 'userdata/music/' . $song->file_url;
        $albumArt = mp3_get_album_art($mp3path) ?>
        <div class="song-list-card">
            <div class="flex-container">
                <div>
                    <img class="album-art-list" src="<?= $albumArt ?>" alt="Album Art"/>
                </div>
                <div class="flex-container flex-grow-right">
                    <div>
                        <p><a href="song.php?id=<?= $song->id ?>"><?= $song->title ?></a></p>
                        <p class="sub-label">
                            ***REMOVED*** $artists = '';
                            foreach ($song->artists as $artist)
                                $artists .= '<a href="artist.php?id=' . $artist->id . '">' . $artist->name . '</a>' . ' / ';
                            echo rtrim($artists, ' /') ?></p>
                    </div>
                    ***REMOVED*** if ($adminEditControls) { ?>
                        <div>
                            <p><a href="edit_song.php?id=<?= $song->id ?>" class="action-link">Edit →</a></p>
                            <p><a href="delete_song.php?id=<?= $song->id ?>" class="action-link">Delete</a></p>
                        </div>
                    ***REMOVED*** } ?>
                </div>
            </div>
        </div>
    ***REMOVED*** }
}

function render_user_list(array $users) {
    foreach ($users as $user) { ?>
        <div class="song-list-card">
            <div class="flex-container">
                <div>
                    <img class="album-art-list" alt="Profile Photo"
                         src="<?= $user->profilePicUrl ?? './assets/img/icons/avatar.svg' ?>"/>
                </div>
                <div class="flex-container flex-grow-right">
                    <div>
                        <p><?= $user->name ?></p>
                        <p class="sub-label"><?= $user->username ?></p>
                    </div>
                    <div>
                        <p><a href="edit_user.php?id=<?= $user->id ?>" class="action-link">Edit →</a></p>
                        <p><a href="delete_user.php?id=<?= $user->id ?>">Delete</a> • <a class="action-link"
                                                                                         href="admin_notify.php?id=<?= $user->id ?>">Notify</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    ***REMOVED*** }
}

function render_artist_list($artists) {
    foreach ($artists as $artist) { ?>
        <div class="song-list-card">
            <div class="flex-container">
                <div>
                    <img class="album-art-list profile-photo-list" src="/assets/img/icons/avatar.svg"
                         alt="Artist's Photo"/>
                </div>
                <div>
                    <p><a href="artist.php?id=<?= $artist->id ?>"><?= $artist->name ?></a></p>
                    <p class="sub-label">

                    </p>
                </div>
            </div>
        </div>
    ***REMOVED*** }
}

function render_album_list($albums, bool $adminEditControls = false) {
    foreach ($albums as $album) { ?>
        <div class="song-list-card">
            <div class="flex-container">
                <div>
                    <img class="album-art-list profile-photo-list" src="/assets/img/icons/avatar.svg"
                         alt="Album Art"/>
                </div>
                ***REMOVED*** if ($adminEditControls) { ?>
                    <div>
                        <p><a href="album.php?id=<?= $album->id ?>"><?= $album->name ?></a></p>
                        <p class="sub-label">
                            ***REMOVED*** foreach ($album->artists as $artist) { ?>
                        <p><a href="artist.php?id=<?= $artist->id ?>"><?= $artist->name ?></a></p>
                        ***REMOVED*** } ?>
                        </p>
                    </div>
                ***REMOVED*** } else { ?>
                    <div>
                        <p><a href="album.php?id=<?= $album->id ?>"><?= $album->name ?></a></p>
                        <p class="sub-label">
                            ***REMOVED*** foreach ($album->artists as $artist) { ?>
                        <p><a href="artist.php?id=<?= $artist->id ?>"><?= $artist->name ?></a></p>
                        ***REMOVED*** } ?>
                        </p>
                    </div>
                ***REMOVED*** } ?>
            </div>
        </div>
    ***REMOVED*** }
}

/**
 * Marks all timestamps in this comment as clickable seek links
 * @param $comment string original comment
 * @return string comment with all timestamps marked
 */
function highlight_comment_timestamps(string $comment): string {
    if (preg_match('/(?<minute>\d+):(?<second>\d+)/', $comment, $matches, PREG_OFFSET_CAPTURE))
        $comment = str_replace($matches[0][0],
            '<a class="seek-link">' . $matches[0][0] . '</a>', $comment);
    return $comment;
}
