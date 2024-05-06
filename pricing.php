<?php
session_start();
if (isset($_SESSION['message'])) {
    echo '<p class="message">' . $_SESSION['message'] . '</p>';
    unset($_SESSION['message']);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CryptoX - Cennik</title>
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
    <section id="plans">


        <div class="plan">
            <h3>Plan Basic</h3>
            <ul>
                <li>Kupowanie</li>
                <li>Sprzedawanie</li>
                <li>Oglądanie wykresów</li>
            </ul>
            <p>Cena: 0 USD / miesiąc</p>
            <a href="choose_plan.php?plan=0">Wybierz plan</a>
        </div>

        <div class="plan">
            <h3>Plan Premium</h3>
            <ul>
                <li>Kupowanie</li>
                <li>Sprzedawanie</li>
                <li>Oglądanie wykresów</li>
                <li>Dostęp do doradcy inwestycyjnego</li>
            </ul>
            <p>Cena: 100 USD / miesiąc</p>
            <a href="choose_plan.php?plan=1">Wybierz plan</a>
        </div>
    </section>
</main>



<footer>
    &copy; 2024 CryptoX. Wszelkie prawa zastrzeżone.
</footer>

</body>
</html>