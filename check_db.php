<?php
require_once 'app/config/config.php';
require_once 'app/core/Database.php';

$db = new Database;
$db->query("DESCRIBE peminjaman");
$columns = $db->resultSet();
print_r($columns);
