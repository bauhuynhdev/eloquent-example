<?php

require_once __DIR__ . '/HashUnique.php';

$hashes = [];
for ($session = 0; $session < 10000; $session++) {
    $short_hash = HashUnique::hash(microtime(true) . $session, 6);
    echo $short_hash . PHP_EOL;
    array_push($hashes, $short_hash);
}

function get_duplicates($array): array
{
    return array_unique(array_diff_assoc($array, array_unique($array)));
}

var_dump(get_duplicates($hashes));