***REMOVED***
***REMOVED***
if (!isset($_SESSION['identifier']))
    header('Location: login.php');
require_once 'include/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EpicTunes</title>
    <link rel="stylesheet" href="./assets/styles.css"/>
</head>
<body>
***REMOVED*** include_once 'include/sidebar.php' ?>
<div class="root-container">
    ***REMOVED*** include_once 'include/top-nav.php' ?>

    <main>
        <h1>My Music</h1>
    </main>

    <div class="player"></div>
</div>
</body>
</html>
