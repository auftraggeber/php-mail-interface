<?php
ini_set('display_errors', 0);

require_once 'cache.php';

BodyCache::deleteOldCaches();
if (isset($_POST['body']) && isset($_POST['chunk'])) {

    if (BodyCache::shared()->addBody(intval($_POST['chunk']), $_POST['body'])) {
        die(json_encode([
            "success" => true,
            BodyCache::POST_CACHE_ID, BodyCache::shared()->getName(),
            "message" => "done"
        ]));
    }

}

die(json_encode([
    "success" => false,
    "message" => "failed to cache body"
]));