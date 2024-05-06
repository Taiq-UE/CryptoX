<?php
session_start();
require 'DatabaseConnect.php';

$databaseConnect = new DatabaseConnect();
$pdo = $databaseConnect->getPdo();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $currency = $_POST['currency'];
    $userId = $_SESSION['userId'];

    $query = $pdo->prepare("SELECT USD FROM wallets WHERE user_id = :user_id");
    $query->execute(['user_id' => $userId]);
    $usdBalance = $query->fetchColumn();

    $url = "https://api.binance.com/api/v3/ticker/price?symbol=" . $currency . "USDT";
    $json = file_get_contents($url);
    $data = json_decode($json);
    $rate = floatval($data->price);

    $amountToBuy = $usdBalance / $rate;

    $query = $pdo->prepare("SELECT $currency FROM wallets WHERE user_id = :user_id");
    $query->execute(['user_id' => $userId]);
    $currentAmount = $query->fetchColumn();

    if ($currentAmount === false) {
        $query = $pdo->prepare("INSERT INTO wallets (user_id, $currency) VALUES (:user_id, 0)");
        $query->execute(['user_id' => $userId]);
        $currentAmount = 0;
    }

    $newAmount = $currentAmount + $amountToBuy;

    $query = $pdo->prepare("UPDATE wallets SET $currency = :amount WHERE user_id = :user_id");
    $query->execute(['amount' => $newAmount, 'user_id' => $userId]);

    $query = $pdo->prepare("UPDATE wallets SET USD = 0 WHERE user_id = :user_id");
    $query->execute(['user_id' => $userId]);

    header("Location: chart.php");
}