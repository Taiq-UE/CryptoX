<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (isset($_SESSION['message'])) {
    echo "<p>" . $_SESSION['message'] . "</p>";
    unset($_SESSION['message']);
}

if (isset($_SESSION['error'])) {
    echo '<div class="error-message">' . $_SESSION['error'] . '</div>';
    unset($_SESSION['error']);
}

require 'DatabaseConnect.php';
$databaseConnect = new DatabaseConnect();
$pdo = $databaseConnect->getPdo();
$username = $_SESSION['username'];

$query = $pdo->prepare("SELECT id FROM users WHERE username = :username");
$query->execute(['username' => $username]);
$userId = $query->fetchColumn();

$query = $pdo->prepare("SELECT * FROM wallets WHERE user_id = :user_id");
$query->execute(['user_id' => $userId]);
$assets = $query->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CryptoX - Wykres</title>
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
    <div id="chart"></div>
    <select id="currency-select">
        <option value="btcusdt">BTC/USDT</option>
        <option value="ethusdt">ETH/USDT</option>
        <option value="xrpusdt">XRP/USDT</option>
        <option value="ltcusdt">LTC/USDT</option> <!-- Litecoin -->
        <option value="bchusdt">BCH/USDT</option> <!-- Bitcoin Cash -->
        <option value="eosusdt">EOS/USDT</option> <!-- EOS -->
        <option value="zecusdt">ZEC/USDT</option> <!-- Zcash -->
        <option value="dashusdt">DASH/USDT</option> <!-- Dash -->
        <option value="xlmusdt">XLM/USDT</option> <!-- Stellar -->
        <option value="adausdt">ADA/USDT</option> <!-- Cardano -->
        <option value="dogeusdt">DOGE/USDT</option> <!-- Dogecoin -->
        <option value="shibusdt">SHIB/USDT</option> <!-- Shiba Inu -->
    </select>
    <select id="interval-select">
<!--        <option value="1s">1s</option>-->
        <option value="1m">1m</option>
        <option value="1h">1h</option>
        <option value="1d">1d</option>
        <option value="1w">1w</option>
    </select>

    <div class="account-assets">
        <h2>Twoje aktywa</h2>
        <table class="centered-table">
            <tr>
                <th>Waluta</th>
                <th>Ilość</th>
            </tr>
            <?php foreach ($assets as $currency => $amount): ?>
                <?php if ($currency !== 'id' && $currency !== 'user_id' && $amount > 0): ?>
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
    </div>

    <div class="form-container">
        <form action="buy.php" method="post">
            <label for="currency-buy">Waluta:</label>
            <select id="currency-buy" name="currency" class="currency-select" required>
                <option value="BTC">Bitcoin</option>
                <option value="ETH">Ethereum</option>
                <option value="XRP">Ripple</option>
                <option value="LTC">Litecoin</option> <!-- Litecoin -->
                <option value="BCH">Bitcoin Cash</option> <!-- Bitcoin Cash -->
                <option value="EOS">EOS</option> <!-- EOS -->
                <option value="ZEC">Zcash</option> <!-- Zcash -->
                <option value="DASH">Dash</option> <!-- Dash -->
                <option value="XLM">Stellar</option> <!-- Stellar -->
                <option value="ADA">Cardano</option> <!-- Cardano -->
                <option value="DOGE">Dogecoin</option> <!-- Dogecoin -->
                <option value="SHIB">Shiba Inu</option> <!-- Shiba Inu -->
            </select>
            <label for="amount-buy">Ilość:</label>
            <input type="number" id="amount-buy" name="amount" min="0" step="0.00000001" class="amount-input">
            <input type="submit" value="Kup" class="buy-button">
            <input type="submit" formaction="buy_max.php" value="Kup za wszystkie dostępne USD" class="buy-button">        </form>


        <form action="sell.php" method="post">
            <label for="currency-sell">Waluta:</label>
            <select id="currency-sell" name="currency" class="currency-select" required>
                <option value="BTC">Bitcoin</option>
                <option value="ETH">Ethereum</option>
                <option value="XRP">Ripple</option>
                <option value="LTC">Litecoin</option> <!-- Litecoin -->
                <option value="BCH">Bitcoin Cash</option> <!-- Bitcoin Cash -->
                <option value="EOS">EOS</option> <!-- EOS -->
                <option value="ZEC">Zcash</option> <!-- Zcash -->
                <option value="DASH">Dash</option> <!-- Dash -->
                <option value="XLM">Stellar</option> <!-- Stellar -->
                <option value="ADA">Cardano</option> <!-- Cardano -->
                <option value="DOGE">Dogecoin</option> <!-- Dogecoin -->
                <option value="SHIB">Shiba Inu</option> <!-- Shiba Inu -->
            </select>
            <label for="amount-sell">Ilość:</label>
            <input type="number" id="amount-sell" name="amount" min="0" step="0.00000001" class="amount-input">
            <input type="submit" formaction="sell.php" value="Sprzedaj" class="sell-button">
            <input type="submit" formaction="sell_max.php" value="Sprzedaj całą posiadana walutę" class="sell-button">
        </form>
    </div>

</main>

<script src="https://unpkg.com/lightweight-charts/dist/lightweight-charts.standalone.production.js"></script>
<script>
    var chart = LightweightCharts.createChart(document.getElementById('chart'), {
        width: window.innerWidth-10,
        height: 500,
        layout: {
            background: { color: 'palegoldenrod' },
            textColor: '#333333',
        },
        grid: {
            vertLines: {
                color: '#333333',
            },
            horzLines: {
                color: '#333333',
            },
        },
        crosshair: {
            mode: LightweightCharts.CrosshairMode.Normal,
        },
        rightPriceScale: {
            borderColor: '#333333',
        },
        timeScale: {
            borderColor: '#333333',
            visible: true,
            timeVisible: true,
            secondsVisible: true,
        },
    });

    window.addEventListener('resize', () => {
        chart.resize(window.innerWidth - 20, 600);
    });

    var candleSeries = chart.addCandlestickSeries({
        upColor: 'rgba(255, 144, 0, 1)',
        downColor: '#000',
        borderDownColor: 'rgba(255, 144, 0, 1)',
        borderUpColor: 'rgba(255, 144, 0, 1)',
        wickDownColor: 'rgba(255, 144, 0, 1)',
        wickUpColor: 'rgba(255, 144, 0, 1)',
        priceFormat: {
            type: 'price',
            // precision: 8,
            minMove: 0.0000001,
        },
    });

    chart.applyOptions({
        priceScale: {
            autoScale: true,
            invertScale: false,
            alignLabels: true,
            borderVisible: true,
            borderColor: '#333333',
            scaleMargins: {
                top: 0.30,
                bottom: 0.25,
            },
            entireTextOnly: false,
            visible: true,
            drawTicks: true,
        },
    });

    let socket;

    var currencyPair = 'btcusdt';
    var interval = '1m';

    var url = 'https://api.binance.com/api/v3/klines?symbol=' + currencyPair.toUpperCase() + '&interval=' + interval;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            var chartData = data.map(item => {
                return {
                    time: item[0] / 1000,
                    open: parseFloat(item[1]),
                    high: parseFloat(item[2]),
                    low: parseFloat(item[3]),
                    close: parseFloat(item[4])
                };
            });

            candleSeries.setData(chartData);

            connectToWebSocket(currencyPair, interval);
        });

    function connectToWebSocket(currencyPair, interval) {
        if (socket) {
            socket.close();
        }

        socket = new WebSocket('wss://fstream.binance.com/ws/' + currencyPair + '@kline_' + interval);

        socket.onopen = function(event) {
            console.log('WebSocket connection opened:', event);
        };

        socket.onmessage = function(event) {
            console.log('WebSocket message:', event.data);
            let data = JSON.parse(event.data);
            let kline = data.k;

            if (kline) {
                let timeInSeconds = Number(kline.t) / 1000;
                if (isNaN(timeInSeconds)) {
                    console.error('Invalid timestamp:', kline.t);
                    return;
                }

                let open = parseFloat(kline.o);
                let high = parseFloat(kline.h);
                let low = parseFloat(kline.l);
                let close = parseFloat(kline.c);

                timeInSeconds = Number(kline.t) / 1000;
                let date = new Date(timeInSeconds * 1000);
                date.setHours(date.getHours() + 2);
                let adjustedTimeInSeconds = date.getTime() / 1000;

                candleSeries.update({
                    time: adjustedTimeInSeconds,
                    open: open,
                    high: high,
                    low: low,
                    close: close,
                });

                document.title = close + ' | ' + currencyPair.toUpperCase() + ' | CryptoX';
            }
        };

        socket.onerror = function(event) {
            console.error('WebSocket error:', event);
        };

        socket.onclose = function(event) {
            console.log('WebSocket connection closed:', event);
        };
    }

    var defaultCurrencyPair = 'btcusdt';
    var defaultInterval = '1m';

    async function fetchData(currencyPair, interval) {
        var url = 'https://api.binance.com/api/v3/klines?symbol=' + currencyPair.toUpperCase() + '&interval=' + interval;

        const response = await fetch(url);
        const data = await response.json();

        var chartData = data.map(item => {
            let timeInSeconds = Number(item[0]) / 1000;
            let date = new Date(timeInSeconds * 1000);
            date.setHours(date.getHours() + 2);
            let adjustedTimeInSeconds = date.getTime() / 1000;

            return {
                time: adjustedTimeInSeconds,
                open: parseFloat(item[1]),
                high: parseFloat(item[2]),
                low: parseFloat(item[3]),
                close: parseFloat(item[4])
            };
        });

        candleSeries.setData(chartData);
    }

    fetchData(defaultCurrencyPair, defaultInterval).then(() => {
            connectToWebSocket(defaultCurrencyPair, defaultInterval);

    });

    async function fetchDataAndConnectWebSocket(currencyPair, interval) {
        await fetchData(currencyPair, interval);
            connectToWebSocket(currencyPair, interval);
    }

    document.getElementById('currency-select').addEventListener('change', function() {
        var interval = document.getElementById('interval-select').value;
        fetchDataAndConnectWebSocket(this.value, interval);
    });

    document.getElementById('interval-select').addEventListener('change', function() {
        var currency = document.getElementById('currency-select').value;
        fetchDataAndConnectWebSocket(currency, this.value);
    });

    connectToWebSocket('btcusdt', '1m');
</script>

<footer>
    &copy; 2024 CryptoX. Wszelkie prawa zastrzeżone.
</footer>

</body>
</html>