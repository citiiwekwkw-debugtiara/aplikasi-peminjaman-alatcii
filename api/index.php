<?php

// Forward to the main index.php logic
// We need to adjust the path because api/index.php is at the same level relative to 'app' as 'public/'
if( !session_id() ) session_start();

require_once __DIR__ . '/../app/init.php';

$app = new App;
