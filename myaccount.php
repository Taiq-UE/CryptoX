<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require 'DatabaseConnect.php';
$databaseConnect = new DatabaseConnect();
$pdo = $databaseConnect->getPdo();
$username = $_SESSION['username'];

$query = $pdo->prepare("SELECT id FROM users WHERE username = :username");
$query->execute(['username' => $username]);
$userId = $query->fetchColumn();

$query = $pdo->prepare("SELECT email FROM users WHERE username = :username");
$query->execute(['username' => $username]);
$email = $query->fetchColumn();
$_SESSION['email'] = $email;

$query = $pdo->prepare("SELECT * FROM wallets WHERE user_id = :user_id");
$query->execute(['user_id' => $userId]);
$assets = $query->fetch(PDO::FETCH_ASSOC);

$query = $pdo->prepare("SELECT plan FROM users WHERE username = :username");
$query->execute(['username' => $username]);
$userPlan = $query->fetchColumn();

$userPlanName = '';
switch ($userPlan) {
    case 0:
        $userPlanName = 'Basic';
        break;
    case 1:
        $userPlanName = 'Premium';
        break;
    default:
        $userPlanName = 'Not available';
        break;
}

$totalValueInUSD = 0;

foreach ($assets as $currency => $amount) {
    if ($currency === 'user_id' || $currency === 'id') {
        continue;
    }

    if ($amount == 0) {
        continue;
    }

    if ($currency === 'USD') {
        $rateToUSD = 1;
    } else {
        $exchangeRate = file_get_contents("https://api.binance.com/api/v3/ticker/price?symbol=" . $currency . "USDT");
        $exchangeRateData = json_decode($exchangeRate, true);
        $rateToUSD = $exchangeRateData['price'];
    }

    $valueInUSD = $amount * $rateToUSD;
    $totalValueInUSD += $valueInUSD;
}

if (isset($_SESSION['message'])) {
    echo "<p>" . $_SESSION['message'] . "</p>";
    unset($_SESSION['message']);
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CryptoX - Moje konto</title>
    <link rel="stylesheet" href="styles.css">
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

<div class="account-info">
    <h2>Informacje o koncie</h2>
    <p>Username: <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Not available'; ?></p>
    <p>Email: <?php echo isset($_SESSION['email']) ? $_SESSION['email'] : 'Not available'; ?></p>
    <p>User ID: <?php echo isset($_SESSION['userId']) ? $_SESSION['userId'] : 'Not available'; ?></p>
    <p>Plan: <?php echo $userPlanName; ?></p></div>

<div class="account-assets">
    <h2>Twoje aktywa</h2>
    <table class="centered-table">
        <tr>
            <th>Waluta</th>
            <th>Ilość</th>
        </tr>
        <?php foreach ($assets as $currency => $amount): ?>
            <?php if ($currency !== 'id' && $currency !== 'user_id'): ?>
                <tr>
                    <td><?php echo $currency; ?></td>
                    <td>
                        <?php
                        echo rtrim(rtrim($amount, '0'), '.');
                        ?>
                    </td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
    </table>
    <p class="total-value">Łączna wartość Twoich aktywów w USD: <?php echo number_format($totalValueInUSD, 2, '.', ''); ?></p></div>

<div class="transaction-forms">
    <div class="deposit">
        <h2>Wpłata na konto</h2>
        <form action="deposit.php" method="post">
            <label for="amount">Kwota wpłaty (USD):</label>
            <input type="number" id="amount" name="amount" min="0" step="0.01" required>
            <input type="submit" value="Wpłać">
        </form>
    </div>
    <div class="withdraw">
        <h2>Wypłata z konta</h2>
        <form action="withdraw.php" method="post">
            <label for="amount">Kwota wypłaty (USD):</label>
            <input type="number" id="amount" name="amount" min="0" step="0.000001" required>
            <input type="submit" value="Wypłać">
        </form>
    </div>
</div>
<footer>
    &copy; 2024 CryptoX. Wszelkie prawa zastrzeżone.
</footer>

</body>
</html>