<?php
require 'DatabaseConnect.php';
$databaseConnect = new DatabaseConnect();
$pdo = $databaseConnect->getPdo();

session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];
$query = "SELECT plan FROM users WHERE username = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$username]);
$user = $stmt->fetch();

if ($user['plan'] != 1) {
    $_SESSION['message'] = 'Aby uzyskać dostęp do tej strony, musisz wykupić plan Premium.';

    header('Location: pricing.php');
    exit();
}

$currencies = ['BTC', 'ETH', 'XRP', 'LTC', 'BCH', 'EOS', 'ZEC', 'DASH', 'XLM', 'ADA', 'DOGE', 'SHIB'];

$days = 30;

$results = [];

foreach ($currencies as $currency) {
    $url = "https://api.binance.com/api/v3/klines?symbol={$currency}USDT&interval=1d&limit={$days}";
    $json = file_get_contents($url);
    $data = json_decode($json);

    $firstDayClose = floatval($data[0][4]);
    $lastDayClose = floatval($data[count($data) - 1][4]);

    $priceChange = (($lastDayClose - $firstDayClose) / $firstDayClose) * 100;

    $results[$currency] = $priceChange;
}

arsort($results);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CryptoX - Formularz</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="images/Clogo.png">
</head>
<body>

<header>
    <div class="logo-container">
        <a href="index.php">
            <img src="images/Cryptox.png" alt="Logo">
        </a>
    </div>
    <nav>
        <ul>
            <li><a href="pricing.php">Cennik</a></li>
            <li><a href="about.php">O nas</a></li>
            <li><a href="contact.php">Kontakt</a></li>
            <?php if (!isset($_SESSION['username'])): ?>
                <li><a href="register.php">Rejestracja</a></li>
                <li><a href="login.php">Logowanie</a></li>
            <?php else: ?>
                <li><a href="form.php">Doradca</a></li>
                <li><a href="chart.php">Wykresy</a></li>
                <li><a href="logout.php">Wyloguj</a></li>
                <li><a href="myaccount.php">Moje konto</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<main>
    <div class="container">
        <section id="formularz">
            <h2>Formularz kontaktowy</h2>
            <form action="#" method="post">
                <label for="imie">Imię:</label>
                <input type="text" id="imie" name="imie" placeholder="Wpisz swoje imię" required>

                <label for="telefon">Telefon:</label>
                <input type="tel" id="telefon" name="telefon" placeholder="Wpisz swój numer telefonu" required>

                <label for="tresc">Treść:</label>
                <textarea id="tresc" name="tresc" placeholder="Wpisz treść wiadomości" required></textarea>

                <input type="submit" value="Wyślij">
            </form>
        </section>

        <section id="doradca">
            <h2>Porównanie wydajności kryptowalut w ciągu ostatnich 30 dni</h2>
            <table>
                <tr>
                    <th>Kryptowaluta</th>
                    <th>Zmiana ceny (%)</th>
                </tr>
                <?php foreach ($results as $currency => $priceChange): ?>
                    <tr>
                        <td><?php echo $currency; ?></td>
                        <td><?php echo number_format($priceChange, 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </section>
    </div>
</main>

<footer>
    &copy; 2024 CryptoX. Wszelkie prawa zastrzeżone.
</footer>

</body>
</html>