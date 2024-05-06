<?php
session_start();
require 'DatabaseConnect.php';

$databaseConnect = new DatabaseConnect();
$pdo = $databaseConnect->getPdo();
$username = $_SESSION['username'];

$plan = isset($_GET['plan']) ? intval($_GET['plan']) : null;

if (!isset($username)) {
    header("Location: login.php");
    exit;
}

if ($plan !== null) {
    $stmt = $pdo->prepare("SELECT plan FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $currentPlan = $stmt->fetchColumn();

    if ($currentPlan != $plan) {
        $stmt = $pdo->prepare("UPDATE users SET plan = :plan WHERE username = :username");
        $stmt->execute(['plan' => $plan, 'username' => $username]);
    }
}

header("Location: myaccount.php");
exit;
?>