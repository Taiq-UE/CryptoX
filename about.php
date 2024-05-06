<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CryptoX - O nas</title>
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
                <li><a href="form.php">Formularz</a></li>
                <li><a href="chart.php">Wykresy</a></li>
                <li><a href="logout.php">Wyloguj</a></li>
                <li><a href="myaccount.php">Moje konto</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<main>
    <section id="about">
        <h2>O nas</h2>
        <p>Jesteśmy platformą tradingową kryptowalut. Nasza misja to dostarczanie najnowocześniejszych narzędzi do handlu i inwestowania w kryptowaluty. Nasz zespół składa się z doświadczonych traderów i analityków, którzy stale monitorują rynek, aby dostarczać naszym użytkownikom najbardziej aktualne informacje i doradztwo.</p>
        <p>Zajmujemy się również doradztwem inwestycyjnym, pomagając naszym klientom podejmować świadome decyzje inwestycyjne. Nasze usługi są dostosowane do potrzeb zarówno początkujących, jak i doświadczonych inwestorów.</p>
    </section>
</main>



<footer>
    &copy; 2024 CryptoX. Wszelkie prawa zastrzeżone.
</footer>

</body>
</html>