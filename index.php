<?php
session_start(); // Session starten, um den Spielstand zu speichern

function initialisiereBrett() {
    return [
        ['', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', ''],
        ['8', '&#9820;', '&#9822;', '&#9821;', '&#9819;', '&#9818;', '&#9821;', '&#9822;', '&#9820;', '8'],
        ['7', '&#9823;', '&#9823;', '&#9823;', '&#9823;', '&#9823;', '&#9823;', '&#9823;', '&#9823;', '7'],
        ['6', '', '', '', '', '', '', '', '', '6'],
        ['5', '', '', '', '', '', '', '', '', '5'],
        ['4', '', '', '', '', '', '', '', '', '4'],
        ['3', '', '', '', '', '', '', '', '', '3'],
        ['2', '&#9817;', '&#9817;', '&#9817;', '&#9817;', '&#9817;', '&#9817;', '&#9817;', '&#9817;', '2'],
        ['1', '&#9814;', '&#9816;', '&#9815;', '&#9813;', '&#9812;', '&#9815;', '&#9816;', '&#9814;', '1'],
        ['', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', '']
    ];
}

if (!isset($_SESSION['schachbrett'])) {
    $_SESSION['schachbrett'] = initialisiereBrett();
    $_SESSION['zugnummer'] = 1;
    $_SESSION['spieler'] = "Weiß";
}

function renderBrett($brett) {
    echo "<table border='1' cellspacing='0' cellpadding='10'>";
    foreach ($brett as $zeile) {
        echo "<tr>";
        foreach ($zeile as $zelle) {
            echo "<td style='text-align:center; width:50px; height:50px;'>$zelle</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['von'], $_POST['nach'])) {
    $von = $_POST['von'];
    $nach = $_POST['nach'];

    $vonZeile = 9 - (ord(substr($von, 1, 1)) - 48);
    $vonSpalte = ord(substr($von, 0, 1)) - 64;
    $nachZeile = 9 - (ord(substr($nach, 1, 1)) - 48);
    $nachSpalte = ord(substr($nach, 0, 1)) - 64;

    if ($_SESSION['schachbrett'][$vonZeile][$vonSpalte] !== '') {
        $_SESSION['schachbrett'][$nachZeile][$nachSpalte] = $_SESSION['schachbrett'][$vonZeile][$vonSpalte];
        $_SESSION['schachbrett'][$vonZeile][$vonSpalte] = '';
        $_SESSION['zugnummer']++;
        $_SESSION['spieler'] = $_SESSION['zugnummer'] % 2 ? "Weiß" : "Schwarz";
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Schachbrett von Frank</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        form { margin-top: 20px; }
    </style>
</head>
<body>
    <h1>Schachbrett von Frank</h1>
    <?php renderBrett($_SESSION['schachbrett']); ?>
    <p><?php echo "Zugnummer: " . $_SESSION['zugnummer'] . " - Spieler: " . $_SESSION['spieler']; ?></p>
    <form method="POST">
        <label>Von:</label>
        <input type="text" name="von" maxlength="2" required>
        <label>Nach:</label>
        <input type="text" name="nach" maxlength="2" required>
        <button type="submit">Zug ausführen</button>
    </form>
    <form method="POST">
        <button type="submit" name="reset">Spiel zurücksetzen</button>
    </form>
    <?php if (isset($_POST['reset'])) { session_destroy(); header("Location: " . $_SERVER['PHP_SELF']); exit; } ?>
</body>
</html>
