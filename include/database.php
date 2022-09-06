***REMOVED***
$db_hostname = 'localhost';
$db_username = 'root';
$db_password = '5dqsotchfpqrh';
$db_database = 'epictunes';
$top_level = 'https://epictunes.tobija-zuntar.eu';

// prod:
// tobijazuntar_epictunes
// agwcURky4HXPBbv

try {
    $db = new PDO("mysql:host=$db_hostname;charset=utf8;dbname=$db_database", $db_username, $db_password);
    // disable in production
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (Exception $e) {
    die('Error: ' . $e->getMessage());
}

// disable in production
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
