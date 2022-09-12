***REMOVED***
require_once 'mp3.php';

function render_song_list($songs) {
    foreach ($songs as $song) {
        $mp3path = 'userdata/music/' . $song['mp3_url'];
        $albumArt = mp3_get_album_art($mp3path) ?>
        <div class="song-list-card">
            <div class="flex-container">
                <div>
                    <img class="album-art-list" src="<?= $albumArt ?>" alt="Album Art"/>
                </div>
                <div>
                    <p><a href="song.php?id=<?= $song['id_song'] ?>"><?= $song['name'] ?></a></p>
                    <p class="sub-label">
                        ***REMOVED*** $artists = '';
                        foreach ($song['artists'] as $artist)
                            $artists .= $artist['artist'] . ' / ';
                        echo rtrim($artists, ' /') ?></p>
                </div>
            </div>
        </div>
    ***REMOVED*** }
}