***REMOVED***
***REMOVED***
if (!isset($_SESSION['id']) || !$_SESSION['is_admin'])
    header('Location: index.php');
if (!isset($_GET['id']))
    header('Location: admin_users.php');
require_once 'utils/queries.php';
$user = User::get($_GET['id']);
if ($user) $user->delete();
header('Location: admin_users.php');