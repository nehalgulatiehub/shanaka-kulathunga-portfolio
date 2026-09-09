<?php
// Copy this file to config.local.php and set the Hostinger MySQL values.
// config.local.php is intentionally excluded from version control.
return [
    'db_host' => getenv('DB_HOST') ?: 'localhost',
    'db_name' => getenv('DB_NAME') ?: 'shanaka_portfolio',
    'db_user' => getenv('DB_USER') ?: 'CHANGE_ME',
    'db_pass' => getenv('DB_PASS') ?: 'CHANGE_ME',
];
