<?php
require_once 'polaczenie.php';
?>

<!DOCTYPE html>
<html lang='pl'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Muzykanci - Dodawanie Albumy</title>
    <link rel='stylesheet' href='main.css'>
</head>

<body>

    <div class='navbar'>
        <a href='index.php'>Strona główna</a>
        <a href="utwory.php">Utwory</a>
        <a href='dodawanie.php'>Dodaj Album</a>
    </div>

    <div class='main'>
        <h2>Dodaj nowy album</h2>
        <form method='POST' action='dodawanie.php'>
            <label for='tytul'>Tytuł albumu:</label><br>
            <input type='text' id='tytul' name='tytul' required><br><br>

            <label for='rok_wydania'>Rok wydania:</label><br>
            <input type='number' id='rok_wydania' name='rok_wydania' required><br><br>

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
                    echo 'Błędna kwerenda';
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
                    echo 'Błędna kwerenda';
                    exit;
                }
                ?>
            </select><br><br>

            <input type='submit' value='Dodaj album' id='send'>
            <div class='error'>
                <?php
                if (isset($_POST['tytul'], $_POST['rok_wydania'])) {
                    $tytul = $_POST['tytul'];
                    $rok_wydania = $_POST['rok_wydania'];

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

                    if ($rok_wydania > 2024 || $rok_wydania < 1900) {
                        echo '<p>Podaj prawidłową datę</p>';
                    } elseif (isset($zespol_id) && isset($autor_id)) {
                        echo '<p>Proszę wybrać albo zespół, albo autora!</p>';
                    } elseif (!empty($tytul) && !empty($rok_wydania)) {
                        $sql = "INSERT INTO albumy (tytul, rok_wydania, zespol_id, autor_id) 
                VALUES ('$tytul', '$rok_wydania', " . ($zespol_id ? "'$zespol_id'" : 'NULL') . ', ' . ($autor_id ? "'$autor_id'" : 'NULL') . ')';

                        if (mysqli_query($conn, $sql)) {
                            echo 'Album został dodany!';
                            mysqli_close($conn);
                            header('Location: http://localhost/5IPiekarzMuzykanci/index.php');
                        } else {
                            echo 'Błąd: ' . mysqli_error($conn);
                            exit;
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