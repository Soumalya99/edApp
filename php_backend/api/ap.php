<?php
// Usage: php hash_password.php [password]
$password = $argv[1] ?? null;

if (!$password) {
    echo "Usage: php hash_password.php [password]\n";
    exit(1);
}

$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Password: $password\n";
echo "Hash: $hash\n";