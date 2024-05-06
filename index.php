<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CryptoX</title>
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

<section class="company-info">
    <div class="company-image">

        <div class="company-text">
            <h2>Witamy w CryptoX.</h2>
        </div>
    </div>
</section>

<main>

    <div class="card-container">
        <div class="card">
            <img src="images/pricing.png" alt="Pricing">
            <h5 class="card-title">Cennik</h5>
            <p class="card-text">Sprawdź nasze konkurencyjne ceny dla różnych usług.</p>
            <a href="pricing.php" class="card-link">Przejdź do cennika</a>
        </div>
        <div class="card">
            <img src="images/about.png" alt="About">
            <h5 class="card-title">O nas</h5>
            <p class="card-text">Poznaj naszą misję i zespół stojący za CryptoX.</p>
            <a href="about.php" class="card-link">Przejdź do strony o nas</a>
        </div>
        <div class="card">
            <img src="images/contact.png" alt="Contact">
            <h5 class="card-title">Kontakt</h5>
            <p class="card-text">Skontaktuj się z nami, aby dowiedzieć się więcej lub zadać pytania.</p>
            <a href="contact.php" class="card-link">Przejdź do strony kontaktowej</a>
        </div>
    </div>


</main>

<footer>
    &copy; 2024 CryptoX. Wszelkie prawa zastrzeżone.
</footer>

</body>
</html>
