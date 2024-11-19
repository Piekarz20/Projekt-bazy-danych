<?php
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'muzykanci';

$conn = mysqli_connect( $servername, $username, $password, $dbname );

if ( !$conn ) {
    echo 'Błąd połączenia z bazą danych';
    exit;
}

?>
