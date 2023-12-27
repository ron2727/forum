<?php
require_once '../src/Database.php';
require_once '../database/connection.php';

// use DB\src\Database;
MySql::setConnection($pdo);
var_dump(MySql::selectPost("WHERE id = 21"));

