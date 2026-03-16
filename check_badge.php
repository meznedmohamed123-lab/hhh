<?php
include('config/database.php');

$num = $_GET['num'];
$email = $_GET['email'];

$stmt = $conn->prepare("SELECT * FROM registrations WHERE num=? AND email=? AND approved=1");
$stmt->bind_param("ss",$num,$email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html>
<head><title>Vérification Badge</title></head>
<body>
<?php if($user): ?>
<h2>Badge valide </h2>
<p>Nom: <?= htmlspecialchars($user['last_name']) ?></p>
<p>Prénom: <?= htmlspecialchars($user['first_name']) ?></p>
<p>Spécialité: <?= htmlspecialchars($user['specialty']) ?></p>
<p>num:<?=htmlspecialchars($user['num'])?></p>
<?php else: ?>
<h2>Badge invalide </h2>
<?php endif; ?>
</body>
</html>