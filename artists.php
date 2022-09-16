***REMOVED***
***REMOVED***
if (!isset($_SESSION['identifier']))
    header('Location: login.php');
require_once 'utils/queries.php';
require_once 'utils/components.php';
$document_title = 'My Artists';
$artists = Artist::get_all();

include_once 'include/header.php';
include_once 'include/sidebar.php' ?>
<div class="root-container">
    ***REMOVED*** include_once 'include/top-nav.php' ?>

    <main>
        <h2 class="accent padding-20">My Artists</h2>
        <div class="margin-top-20">
            ***REMOVED*** if ($artists) {
                render_artist_list($artists);
        ***REMOVED*** ?>
        </div>
    </main>
</div>
***REMOVED*** include_once 'include/footer.php' ?>
