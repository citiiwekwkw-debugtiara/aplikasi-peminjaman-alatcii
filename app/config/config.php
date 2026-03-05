<?php

// Base URL
define('BASEURL', getenv('BASEURL') ?: 'http://localhost/cii/public');

// DB Constants
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'peminjaman_alat');
define('DB_PORT', getenv('DB_PORT') ?: '3306');
