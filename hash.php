<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hashage de mot de passe</title>
    <link rel="stylesheet" href="assets/style/style.css">
    <link rel="stylesheet" href="assets/style/colors.css">
</head>

<body>
    <form action="" method="post">
        <label for="password">Mot de passe à hasher :</label>
        <input type="text" name="password" id="password" required>
        <input type="submit" value="Hasher">
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $password = $_POST['password'];
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        echo "<p>" . htmlspecialchars($hashed_password) . "</p>";
    }
    ?>
</body>

</html>