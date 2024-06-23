<?php
session_start();

if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['party_id'])) {
    header('Location: dashboard.php');
    exit;
}

$party_id = $_GET['party_id'];
$email = $_SESSION['email'];

require 'config.php';

try {
    $stmt = $pdo->prepare('SELECT user_id FROM users WHERE email = :email');
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $user_id = $user['user_id'];

    $stmt = $pdo->prepare('SELECT * FROM party_user WHERE party_id = :party_id AND user_id = :user_id');
    $stmt->execute(['party_id' => $party_id, 'user_id' => $user_id]);
    $party_user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$party_user) {
        //echo "Vous n'êtes pas autorisé à voir cette partie.";
        echo "Qu'est-ce que tu fou là chef";
        exit;
    }

    $stmt = $pdo->prepare('SELECT * FROM parties WHERE party_id = :party_id');
    $stmt->execute(['party_id' => $party_id]);
    $party = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$party) {
        echo "Partie non trouvée.";
        exit;
    }

    $organizerStmt = $pdo->prepare('SELECT name FROM users WHERE user_id = :organizer_id');
    $organizerStmt->execute(['organizer_id' => $party['organizer_id']]);
    $organizer = $organizerStmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Let's Party • Party</title>
    <link rel="stylesheet" href="assets/style/style.css">
    <link rel="stylesheet" href="assets/style/colors.css">
</head>

<body>
    <?php include 'components/header.php'; ?>
    <main>
        <div class="party">
            <h2>Détails de la partie</h2>
            <p><strong>Nom :</strong> <?php echo htmlspecialchars($party['party_name']); ?></p>
            <p><strong>Organisateur :</strong> <?php echo htmlspecialchars($organizer['name']); ?></p>
            <p><strong>Date de début :</strong> <?php echo htmlspecialchars($party['start_datetime']); ?></p>
            <p><strong>Date de fin :</strong> <?php echo htmlspecialchars($party['end_datetime'] ?? 'Non spécifiée'); ?></p>
            <p><strong>Lieu :</strong> <?php echo htmlspecialchars($party['location'] ?? 'Non spécifié'); ?></p>
            <p><strong>Quantité de boissons :</strong> <?php echo htmlspecialchars($party['drink_quantity'] ?? 'Non spécifiée'); ?></p>
            <p><strong>Quantité de nourriture :</strong> <?php echo htmlspecialchars($party['foods_quantity'] ?? 'Non spécifiée'); ?></p>
            <p><strong>Fumer autorisé :</strong> <?php echo $party['can_smoke'] ? 'Oui' : 'Non'; ?></p>
            <p><strong>Peut dormir :</strong> <?php echo $party['can_sleep'] ? 'Oui' : 'Non'; ?></p>
            <p><strong>Peut inviter :</strong> <?php echo $party['can_invite'] ? 'Oui' : 'Non'; ?></p>
            <p><strong>Détails supplémentaires :</strong> <?php echo htmlspecialchars($party['details'] ?? 'Aucun'); ?></p>
        </div>
        <button onclick="window.location.href = 'dashboard.php'; return false;" class="btn-back">◀️ Tableau de bord</button>
    </main>
    <?php include 'components/footer.php'; ?>
</body>

</html>