<?php
session_start();
if (!isset($_SESSION['identifier']))
    header('Location: login.php');
require_once 'utils/queries.php';
require_once 'utils/components.php';

if (isset($_GET['query']) && trim($_GET['query']) !== '') {
    $searchResultSongs = Song::get_by_searching($_GET['query']);
    $songsFound = $searchResultSongs !== false && sizeof($searchResultSongs) > 0;
    $searchResultAlbums = Album::get_by_searching($_GET['query']);
    $albumsFound = $searchResultAlbums !== false && sizeof($searchResultAlbums) > 0;
    $searchResultArtists = Artist::get_by_searching($_GET['query']);
    $artistsFound = $searchResultArtists !== false && sizeof($searchResultArtists) > 0;
}

include_once 'include/header.php';
include_once 'include/sidebar.php' ?>
<div class="root-container">
    <?php include_once 'include/top-nav.php' ?>

    <main>
        <div class="margin-top-20">
            <?php if (isset($_GET['query']) && trim($_GET['query']) !== '') { ?>
            <h2 class="accent padding-20">Search Results</h2>
            <div class="margin-top-20">
                <?php if ($songsFound || $albumsFound || $artistsFound) {
                    if ($songsFound) {
                        echo '<h2 class="accent margin-lr-20 light-underline">Songs</h2>';
                        render_song_list($searchResultSongs, $_SESSION['is_admin']);
                    }
                    if ($albumsFound) {
                        echo '<h2 class="accent margin-lr-20 light-underline">Albums</h2>';
                        render_album_list($searchResultAlbums, $_SESSION['is_admin']);
                    }
                    if ($artistsFound) {
                        echo '<h2 class="accent margin-lr-20 light-underline">Artists</h2>';
                        render_artist_list($searchResultArtists);
                    }
                } else { ?>
                    <div class="full-center text-center">
                        <h1 class="full-alert">No Results</h1>
                        <p class="fine-print">Try rewording your query</p>
                    </div>
                <?php }
                } else { ?>
                    <div class="full-center text-center">
                        <h1 class="full-alert">Type in the box above to search</h1>
                        <p class="fine-print">Type in the search box at the top of the page to search for songs, albums,
                            and artists</p>
                    </div>
                <?php } ?>
            </div>
        </div>
    </main>
</div>
<?php include_once 'include/footer.php' ?>
