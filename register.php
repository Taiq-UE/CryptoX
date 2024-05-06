<?php
session_start();

require 'DatabaseConnect.php';
$databaseConnect = new DatabaseConnect();
$pdo = $databaseConnect->getPdo();
$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username, $email, $password]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['username'] = $username;

        $userId = $pdo->lastInsertId();
        $query = $pdo->prepare("INSERT INTO wallets (user_id) VALUES (:user_id)");
        $query->execute(['user_id' => $userId]);

        header("Location: index.php");
        exit;
    } else {
        $error = "Error: " . $stmt->errorInfo()[2];
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CryptoX - Rejestracja</title>
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

<main>
    <form action="register.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>
        <input type="submit" value="Register">
    </form>
    <?php if (isset($error)): ?>
        <p><?php echo $error; ?></p>
    <?php endif; ?>
</main>

<footer>
    &copy; 2024 CryptoX. Wszelkie prawa zastrzeżone.
</footer>

</body>
</html>