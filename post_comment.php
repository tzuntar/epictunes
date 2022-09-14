***REMOVED***
***REMOVED***
if (!isset($_SESSION['identifier']))
    header('Location: login.php');
if (!isset($_GET['song']) || !isset($_POST['']))
    header('Location: ' . $_SERVER['HTTP_REFERER']);
require_once 'utils/queries.php';
$comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$songId = filter_input(INPUT_GET, 'song', FILTER_SANITIZE_NUMBER_INT);
Song::get($songId)->insert_comment($_SESSION['id'], $comment);
header('Location: ' . $_SERVER['HTTP_REFERER']);