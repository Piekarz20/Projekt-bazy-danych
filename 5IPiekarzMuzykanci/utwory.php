<?php
require_once 'polaczenie.php';

// Kwerenda SQL do pobrania danych o utworach
$sql = "SELECT
    utwory.utwor_id,
    utwory.tytul AS utwor_tytul,
    IFNULL(albumy.tytul, 'Ta piosenka to singiel') AS album_tytul,
    COALESCE(zespoly.nazwa_zespolu, CONCAT(autorzy.imie, ' ', autorzy.nazwisko)) AS wykonawca,
    gatunki.nazwa_gatunku AS gatunek,
    utwory.czas_trwania
FROM
    utwory
LEFT JOIN zespoly ON utwory.zespol_id = zespoly.zespol_id
LEFT JOIN autorzy ON utwory.autor_id = autorzy.autor_id
LEFT JOIN albumy ON utwory.album_id = albumy.album_id
LEFT JOIN gatunki ON utwory.gatunek_id = gatunki.gatunek_id";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang='pl'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Muzykanci - Utwory</title>
    <link rel='stylesheet' href='main.css'>
</head>

<body>

    <div class='navbar'>
        <a href='index.php'>Strona główna</a>
        <a href="utwory.php">Utwory</a>
        <a href='dodawanie.php'>Dodaj Album</a>
    </div>
    <div class='main'>
        <h1>Lista Utworów</h1>

        <?php
        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                echo '<table><tr><th>Tytuł Utworu</th><th>Album</th><th>Wykonawca</th><th>Gatunek</th><th>Czas Trwania</th></tr>';
                while ($row = mysqli_fetch_array($result)) {
                    echo '<tr><td>' . $row['utwor_tytul'] . '</td>'
                        . '<td>' . $row['album_tytul'] . '</td>'
                        . '<td>' . $row['wykonawca'] . '</td>'
                        . '<td>' . $row['gatunek'] . '</td>'
                        . '<td>' . $row['czas_trwania'] . '</td></tr>';
                }
                echo '</table>';
            } else {
                echo 'Brak wyników';
            }
        } else {
            echo 'Błędna kwerenda';
            exit;
        }
        ?>
    </div>
</body>

</html>

<?php
mysqli_close($conn);
?>