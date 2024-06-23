<?php
session_start();

if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header('Location: login.php');
    exit;
}

$email = $_SESSION['email'];

require 'config.php';

$stmt = $pdo->prepare('SELECT parties.party_id, parties.party_name FROM parties 
                       INNER JOIN party_user ON parties.party_id = party_user.party_id 
                       INNER JOIN users ON party_user.user_id = users.user_id 
                       WHERE users.email = :email');
$stmt->execute(['email' => $email]);
$parties = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Let's Party • Tableau de bord</title>
    <link rel="stylesheet" href="assets/style/style.css">
    <link rel="stylesheet" href="assets/style/colors.css">
</head>

<body>
    <?php include 'components/header.php'; ?>
    <main>
        <div class="parties">
            <?php if (!empty($parties)) : ?>
                <?php foreach ($parties as $party) : ?>
                    <a href="party.php?party_id=<?php echo htmlspecialchars($party['party_id']); ?>" class="party">
                        <h2><?php echo htmlspecialchars($party['party_name']); ?></h2>
                        <p></p>
                    </a>
                <?php endforeach; ?>
            <?php else : ?>
                <p>Aucune partie disponible.</p>
            <?php endif; ?>
        </div>
    </main>
    <?php include 'components/footer.php'; ?>
</body>

</html>