***REMOVED***
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

/**
 * Extract album art from this MP3 file
 * @param $filePath string path to the file
 * @return false|string data encoded for the HTML <img> tag or
 * false if the file doesn't contain album art
 */
function mp3_get_album_art(string $filePath) {
    $getID3 = new getID3;
    $fileInfo = $getID3->analyze($filePath);
    if (isset($fileInfo['comments']['picture'][0]))
        $art = 'data:' . $fileInfo['comments']['picture'][0]['image_mime']
            . ';charset=utf-8;base64,'
            . base64_encode($fileInfo['comments']['picture'][0]['data']);
    else return false;
    return @$art;
}
