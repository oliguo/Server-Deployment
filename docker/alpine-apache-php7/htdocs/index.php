<?php
$link = mysqli_connect("172.17.0.5:3306", "root", "root", "demo");

if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL . "</br>";
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL. "</br>";
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL. "</br>";
    exit;
}

echo "Success: A proper connection to MySQL was made! The demo database is great." . PHP_EOL. "</br>";
echo "Host information: " . mysqli_get_host_info($link) . PHP_EOL;

mysqli_close($link);
echo "<br/>";
phpinfo();
