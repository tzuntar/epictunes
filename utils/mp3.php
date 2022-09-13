<?php
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

/**
 * Extract MP3 metadata from this MP3 file
 * @param string $filePath path to the file
 * @return array decoded data
 */
function mp3_get_song_data(string $filePath): array {
    $getID3 = new getID3;
    $fileInfo = $getID3->analyze($filePath);
    $fileData['title'] = $fileInfo['tags']['id3v1']['title'][0];
    $fileData['artist'] = str_replace('/', ', ', $fileInfo['tags']['id3v1']['artist'][0]);
    $fileData['album'] = $fileInfo['tags']['id3v1']['album'][0];
    $fileData['genre'] = $fileInfo['tags']['id3v2']['genre'][0];
    $fileData['album_artist'] = $fileInfo['tags']['id3v2']['band'][0];
    $fileData['album_art'] = mp3_get_album_art($filePath);
    return $fileData;
}
