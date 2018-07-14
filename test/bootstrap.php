<?php

include __DIR__ . '/../vendor/autoload.php';

function loadCredential()
{
    $path = getcwd().'/'.'credential.json';

    $credential = file_get_contents($path);

    if (!$credential) {
        throw new \Exception("File Not Found", 1);
    }

    $decoded = json_decode($credential, true);

    return $decoded;
}
