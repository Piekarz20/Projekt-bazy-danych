<?php
require_once 'polaczenie.php';

$sql = "SELECT
    albumy.album_id,
    albumy.tytul,
    COALESCE(zespoly.nazwa_zespolu, CONCAT(autorzy.imie, ' ', autorzy.nazwisko)) AS wykonawca
FROM
    albumy
LEFT JOIN zespoly ON albumy.zespol_id = zespoly.zespol_id
LEFT JOIN autorzy ON albumy.autor_id = autorzy.autor_id";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang='pl'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Muzykanci - Albumy</title>
    <link rel='stylesheet' href='main.css'>
</head>

<body>

    <div class='navbar'>
        <a href='index.php'>Strona główna</a>
        <a href="utwory.php">Utwory</a>
        <a href='dodawanie.php'>Dodaj Album</a>
    </div>
    <div class='main'>
        <h1>Lista Albumów</h1>

        <?php
        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                echo '<table><tr><th>Tytuł</th><th>Wykonawca</th></tr>';
                while ($row = mysqli_fetch_array($result)) {
                    echo '<tr><td>' . $row['tytul'] . '</td><td>' . $row['wykonawca'] . '</td></tr>';
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