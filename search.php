***REMOVED***
***REMOVED***
if (!isset($_SESSION['identifier']))
    header('Location: login.php');
require_once 'utils/queries.php';
require_once 'utils/components.php';

if (isset($_GET['query'])) {
    $searchResultSongs = Song::get_by_searching($_GET['query']);
}

include_once 'include/header.php';
include_once 'include/sidebar.php' ?>
<div class="root-container">
    ***REMOVED*** include_once 'include/top-nav.php' ?>

    <main>
        <div class="margin-top-20">
            <h2 class="accent padding-20">Search Results</h2>
            <div class="margin-top-20">
                ***REMOVED*** if (isset($searchResultSongs) && $searchResultSongs !== false) {
                    render_song_list($searchResultSongs);
            ***REMOVED*** else { // ToDo: make sure this is displayed properly ?>
                    <div class="full-center">
                        <h1 class="full-alert">No Results</h1>
                        <p class="fine-print">Try rewording your query</p>
                    </div>
                ***REMOVED*** } ?>
            </div>
        </div>
    </main>
</div>
***REMOVED*** include_once 'include/footer.php' ?>
