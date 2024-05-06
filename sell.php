<?php
session_start();
require 'DatabaseConnect.php';

$databaseConnect = new DatabaseConnect();
$pdo = $databaseConnect->getPdo();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $currency = $_POST['currency'];
    $sellAmount = isset($_POST['amount']) && !empty($_POST['amount']) ? floatval($_POST['amount']) : null;
    $userId = $_SESSION['userId'];

    $query = $pdo->prepare("SELECT $currency FROM wallets WHERE user_id = :user_id");
    $query->execute(['user_id' => $userId]);
    $cryptoBalance = $query->fetchColumn();

    if ($sellAmount > $cryptoBalance) {
        $_SESSION['error'] = "Nie masz wystarczająco dużo " . $currency . " do zrealizowania tej sprzedaży.";
        header("Location: chart.php");
        exit();
    }

    $url = "https://api.binance.com/api/v3/ticker/price?symbol=" . $currency . "USDT";
    $json = file_get_contents($url);
    $data = json_decode($json);
    $rate = floatval($data->price);

    $usdAmount = $sellAmount * $rate;

    $query = $pdo->prepare("UPDATE wallets SET $currency = $currency - :amount, USD = USD + :usdAmount WHERE user_id = :user_id");
    $query->execute(['amount' => $sellAmount, 'usdAmount' => $usdAmount, 'user_id' => $userId]);

    header("Location: chart.php");
}