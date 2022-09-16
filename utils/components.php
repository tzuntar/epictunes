***REMOVED***
require_once 'mp3.php';

function render_song_list($songs) {
    foreach ($songs as $song) {
        $mp3path = 'userdata/music/' . $song->file_url;
        $albumArt = mp3_get_album_art($mp3path) ?>
        <div class="song-list-card">
            <div class="flex-container">
                <div>
                    <img class="album-art-list" src="<?= $albumArt ?>" alt="Album Art"/>
                </div>
                <div>
                    <p><a href="song.php?id=<?= $song->id ?>"><?= $song->title ?></a></p>
                    <p class="sub-label">
                        ***REMOVED*** $artists = '';
                        foreach ($song->artists as $artist)
                            $artists .= '<a href="artist.php?id=' . $artist->id . '">' . $artist->name . '</a>' . ' / ';
                        echo rtrim($artists, ' /') ?></p>
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

function render_album_list($albums) {
    foreach ($albums as $album) { ?>
        <div class="song-list-card">
            <div class="flex-container">
                <div>
                    <img class="album-art-list profile-photo-list" src="/assets/img/icons/avatar.svg"
                         alt="Album Art"/>
                </div>
                <div>
                    <p><a href="album.php?id=<?= $album->id ?>"><?= $album->name ?></a></p>
                    <p class="sub-label">
                        ***REMOVED*** foreach ($album->artists as $artist) { ?>
                    <p><a href="artist.php?id=<?= $artist->id ?>"><?= $artist->name ?></a></p>
                    ***REMOVED*** } ?>
                    </p>
                </div>
            </div>
        </div>
    ***REMOVED*** }
}