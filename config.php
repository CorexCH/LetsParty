<?php
$db_host = '192.168.1.108';
$db_name = 'app4party';
$db_user = 'root';
$db_pass = 'hci3M!dYiHbuX>Y';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
    //die("Erreur de connexion à la base de données : " . $e->getMessage());
}
