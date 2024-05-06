<?php
session_start();
require 'DatabaseConnect.php';

$databaseConnect = new DatabaseConnect();
$pdo = $databaseConnect->getPdo();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $amount = $_POST['amount'];
    $_SESSION['amount'] = $amount;
    $username = $_SESSION['username'];
    $currency = 'USD'; // This should be the column name of the currency in the wallets table

    $query = $pdo->prepare("SELECT id FROM users WHERE username = :username");
    $query->execute(['username' => $username]);
    $userId = $query->fetchColumn();
    $_SESSION['userId'] = $userId;

    $query = $pdo->prepare("SELECT $currency FROM wallets WHERE user_id = :user_id");
    $query->execute(['user_id' => $userId]);
    $currentAmount = $query->fetchColumn();

    if ($currentAmount === false) {
        $query = $pdo->prepare("INSERT INTO wallets (user_id, $currency) VALUES (:user_id, 0)");
        $query->execute(['user_id' => $userId]);
        $currentAmount = 0;
    }

    $newAmount = $currentAmount + $amount;

    $query = $pdo->prepare("UPDATE wallets SET $currency = :amount WHERE user_id = :user_id");
    $query->execute(['amount' => $newAmount, 'user_id' => $userId]);

    if ($query->rowCount() > 0) {
        $_SESSION['message'] = "Saldo zostało pomyślnie zaktualizowane.";
    } else {
        $_SESSION['message'] = "Nie udało się zaktualizować salda.";
    }

    header("Location: myaccount.php");
}