<?php
require_once 'polaczenie.php';
?>

<!DOCTYPE html>
<html lang='pl'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Muzykanci - Dodawanie Utwory</title>
    <link rel='stylesheet' href='main.css'>
</head>

<body>

    <div class='navbar'>
        <a href='index.php'>Albumy</a>
        <a href="utwory.php">Utwory</a>
        <a href='dodawanie.php'>Dodaj Album</a>
        <a href="dodawanieUtwor.php">Dodaj Utwór</a>
    </div>

    <div class='main'>
        <h1>Dodaj nowy utwór</h1>
        <form method='POST' action='dodawanieUtwor.php'>
            <label for='tytul'>Tytuł utworu:</label><br>
            <input type='text' id='tytul' name='tytul' placeholder="New Magic Wand" required><br><br>


            <label for='album_id'>Album:</label><br>
            <select name='album_id' id='album_id'>
                <option value=''>Brak zespołu</option>
                <?php
                $albumy_query = "SELECT album_id, IFNULL(albumy.tytul, 'Ta piosenka to singiel') AS album_tytul FROM albumy";
                $albumy_result = mysqli_query($conn, $albumy_query);
                if ($albumy_result) {
                    while ($album = mysqli_fetch_array($albumy_result)) {
                        echo "<option value='" . $album['album_id'] . "'>" . $album['album_tytul'] . '</option>';
                    }
                } else {
                    echo 'Błędne zapytanko';
                    exit;
                }
                ?>
            </select><br><br>

            <label for='zespol_id'>Zespół:</label><br>
            <select name='zespol_id' id='zespol_id'>
                <option value=''>Brak zespołu</option>
                <?php
                $zespoly_query = 'SELECT * FROM zespoly';
                $zespoly_result = mysqli_query($conn, $zespoly_query);
                if ($zespoly_result) {
                    while ($zespol = mysqli_fetch_array($zespoly_result)) {
                        echo "<option value='" . $zespol['zespol_id'] . "'>" . $zespol['nazwa_zespolu'] . '</option>';
                    }
                } else {
                    echo 'Błędne zapytanko';
                    exit;
                }
                ?>
            </select><br><br>

            <label for='autor_id'>Autor:</label><br>
            <select name='autor_id' id='autor_id'>
                <option value=''>Brak autora</option>
                <?php
                $autorzy_query = 'SELECT * FROM autorzy';
                $autorzy_result = mysqli_query($conn, $autorzy_query);
                if ($autorzy_result) {
                    while ($autor = mysqli_fetch_array($autorzy_result)) {
                        echo "<option value='" . $autor['autor_id'] . "'>" . $autor['imie'] . ' ' . $autor['nazwisko'] . '</option>';
                    }
                } else {
                    echo 'Błędne zapytanko';
                    exit;
                }
                ?>
            </select><br><br>

            <label for='gatunek'>Gatunek:</label><br>
            <select name='gatunek' id='gatunek'>
                <option value=''>Brak gatunku</option>
                <?php
                $gatunki_query = 'SELECT * FROM gatunki';
                $gatunki_result = mysqli_query($conn, $gatunki_query);
                if ($gatunki_result) {
                    while ($gatunek = mysqli_fetch_array($gatunki_result)) {
                        echo "<option value='" . $gatunek['gatunek_id'] . "'>" . $gatunek['nazwa_gatunku'] . '</option>';
                    }
                } else {
                    echo 'Błędne zapytanko';
                    exit;
                }
                ?>
            </select><br><br>

            <label for='czas_trwania'>Czas trwania:</label><br>
            <input type='datetime' id='czas_trwania' name='czas_trwania' min="0" max="00:20:00" value="00:03:24"
                placeholder="00:03:24" required><br><br>
            <input type='submit' value='Dodaj utwór'>

            <div class='error'>

                <?php
                if (isset($_POST['tytul'], $_POST['czas_trwania'], $_POST['gatunek'])) {
                    $tytul = $_POST['tytul'];
                    $czas_trwania = $_POST['czas_trwania'];
                    $gatunek_id = $_POST['gatunek'];


                    if (isset($_POST['album_id']) && $_POST['album_id'] !== '') {
                        $album_id = $_POST['album_id'];
                    } else {
                        $album_id = NULL;
                    }

                    if (isset($_POST['zespol_id']) && $_POST['zespol_id'] !== '') {
                        $zespol_id = $_POST['zespol_id'];
                    } else {
                        $zespol_id = NULL;
                    }

                    if (isset($_POST['autor_id']) && $_POST['autor_id'] !== '') {
                        $autor_id = $_POST['autor_id'];
                    } else {
                        $autor_id = NULL;
                    }


                    if (strtotime($czas_trwania) === false) {
                        echo '<p>Podaj prawidłowy czas trwania w formacie HH:MM:SS</p>';
                    } elseif (isset($zespol_id) && isset($autor_id)) {
                        echo '<p>Proszę wybrać albo zespół, albo autora!</p>';
                    } elseif (!empty($tytul) && !empty($czas_trwania)) {
                        if (!empty($zespol_id) || !empty($autor_id)) {
                            $sql = "INSERT INTO utwory (tytul, czas_trwania, zespol_id, autor_id, album_id, gatunek_id) 
                            VALUES ('$tytul', '$czas_trwania', " . ($zespol_id ? $zespol_id : 'NULL') . ", " . ($autor_id ? $autor_id : 'NULL') . ", " . ($album_id ? $album_id : 'NULL') . ", $gatunek_id)";
                            if (mysqli_query($conn, $sql)) {
                                echo 'Utwór został dodany!';
                                mysqli_close($conn);
                                exit;
                            } else {
                                echo 'Błąd: ' . mysqli_error($conn);
                                exit;
                            }
                        } else {
                            echo '<p>Podaj autora lub zespół</p>';
                        }
                    } else {
                        echo '<p>Proszę uzupełnić wszystkie wymagane pola!</p>';
                    }
                }
                ?>
            </div>
        </form>

    </div>
</body>

</html>

<?php
mysqli_close($conn);
?>