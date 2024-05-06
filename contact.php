<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CryptoX - Kontakt</title>
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
    <section id="contact-info">
        <h2>Informacje kontaktowe</h2>

        <div class="contact-item">
            <img src="images/phone.png" alt="Ikona telefonu">
            <p>Telefon: +48 123 456 789</p>
        </div>

        <div class="contact-item">
            <img src="images/email.png" alt="Ikona emaila">
            <p>Email: kontakt@cryptox.pl</p>
        </div>

        <div class="contact-item">
            <img src="images/location.png" alt="Ikona adresu">
            <p>Adres: ul. Kryptowalutowa 1, 00-001 Warszawa</p>
        </div>
    </section>
</main>



<footer>
    &copy; 2024 CryptoX. Wszelkie prawa zastrzeżone.
</footer>

</body>
</html>