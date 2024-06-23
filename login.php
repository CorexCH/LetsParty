<?php
session_start();

require 'config.php';

if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
    header('Location: dashboard.php');
    exit;
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $stmt = $pdo->prepare('SELECT password FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['authenticated'] = true;
            $_SESSION['email'] = $email;
            header('Location: dashboard.php');
            exit;
        } else {
            $error_message = "Nom d'utilisateur ou mot de passe incorrect.";
        }
    } catch (PDOException $e) {
        $error_message = "Erreur de connexion à la base de données.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Let's Party • Connexion</title>
    <link rel="stylesheet" href="assets/style/style.css">
    <link rel="stylesheet" href="assets/style/colors.css">
</head>

<body>
    <form action="" method="post">
        <legend>Connexion</legend>
        <label for="email">Email :</label>
        <input type="email" name="email" id="email" required>
        <label for="password">Mot de passe :</label>
        <input type="password" name="password" id="password" required>
        <input type="submit" value="Se connecter">
        <a class="connection-link" href="register.php">S'inscrire</a>
        <?php if (isset($error_message)) : ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php endif; ?>
    </form>
</body>

</html>