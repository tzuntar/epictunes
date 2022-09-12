***REMOVED***
***REMOVED***
$document_title = 'My Music';
if (!isset($_SESSION['identifier']))
    header('Location: login.php');
require_once 'utils/queries.php';
require_once 'utils/components.php';
$userSongs = db_get_saved_songs_summary_user($_SESSION['id']);

include_once 'include/header.php';
include_once 'include/sidebar.php' ?>
    <div class="root-container">
        ***REMOVED*** include_once 'include/top-nav.php' ?>

        <main>
            <h2 class="accent padding-20">My Music</h2>
            <div class="margin-top-20">
                ***REMOVED*** if (isset($userSongs)) {
                    render_song_list($userSongs);
            ***REMOVED*** ?>
            </div>
        </main>

        <div class="player"></div>
    </div>
***REMOVED*** include_once 'include/footer.php' ?>