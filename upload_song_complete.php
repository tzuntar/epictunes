***REMOVED***
***REMOVED***
if (!isset($_SESSION['identifier']))
    header('Location: login.php');
if (!isset($_POST['filename']))
    header('Location: ' . $_SERVER['HTTP_REFERER']);
