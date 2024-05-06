<?php
session_start();
require 'DatabaseConnect.php';

$databaseConnect = new DatabaseConnect();
$pdo = $databaseConnect->getPdo();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $amount = $_POST['amount'];
    $username = $_SESSION['username'];
    $currency = 'USD'; // This should be the column name of the currency in the wallets table

    $query = $pdo->prepare("SELECT id FROM users WHERE username = :username");
    $query->execute(['username' => $username]);
    $userId = $query->fetchColumn();

    $query = $pdo->prepare("SELECT $currency FROM wallets WHERE user_id = :user_id");
    $query->execute(['user_id' => $userId]);
    $currentAmount = $query->fetchColumn();

    if ($currentAmount < $amount) {
        die("Nie masz wystarczających środków do wypłaty tej kwoty.");
    }

    $newAmount = $currentAmount - $amount;

    $query = $pdo->prepare("UPDATE wallets SET $currency = :amount WHERE user_id = :user_id");
    $query->execute(['amount' => $newAmount, 'user_id' => $userId]);

    header("Location: myaccount.php");
}