<?php
$db = require __DIR__ . '/db.php';
// test database! Important not to run tests on production or development databases
$db['dsn'] = 'mysql:host=host.docker.internal;dbname=future;port=6000';

return $db;
