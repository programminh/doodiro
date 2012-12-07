<?php

/* Gros hack, mais ça fonctionne "suffisament" pour un projet d'école. */
function create_url($filename) {
    $server = $_SERVER['HTTP_HOST'];
    $parts = explode("/", $_SERVER['REQUEST_URI']);
    $url = implode('/', array_slice($parts, 0, count($parts) - 1));
    return "http://{$server}{$url}/{$filename}";
}

?>