***REMOVED***
***REMOVED***
$document_title = 'Posted Songs';
if (!isset($_SESSION['identifier']))
    header('Location: login.php');
require_once 'utils/queries.php';
require_once 'utils/components.php';
$postedSongs = Song::get_by_artist($_SESSION['id'], true);

include_once 'include/header.php';
include_once 'include/sidebar.php' ?>
    <div class="root-container">
        ***REMOVED*** include_once 'include/top-nav.php' ?>
        <main>
            <h2 class="accent padding-20">Posted Songs</h2>
            <div class="margin-top-20">
                ***REMOVED*** if ($postedSongs) {
                    render_song_list($postedSongs, true);
            ***REMOVED*** else { ?>
                    <div class="text-center">
                        <h1 class="full-alert">You haven't posted any songs yet</h1>
                        <p><a href="upload_song.php" class="action-link">Post a song →</a></p>
                    </div>
                ***REMOVED*** } ?>
            </div>
        </main>
    </div>
***REMOVED*** include_once 'include/footer.php' ?>