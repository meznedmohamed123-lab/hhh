<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include('config/database.php');
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
require 'phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) die("CSRF invalid.");

function sanitize($val){ return trim(strip_tags($val)); }
$num = sanitize($_POST['num']);
$first_name = sanitize($_POST['first_name']);
$last_name = sanitize($_POST['last_name']);
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$specialty = sanitize($_POST['specialty']);

$stmt = $conn->prepare("SELECT id FROM registrations WHERE num=? OR email=?");
$stmt->bind_param("ss",$num,$email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0){
    die("<script>alert('Numéro ou email déjà enregistré!'); window.history.back();</script>");
}
$stmt->close();


$stmt = $conn->prepare("INSERT INTO registrations (num, first_name, last_name, email, specialty, approved) VALUES (?,?,?,?,?,0)");
$stmt->bind_param("sssss",$num,$first_name,$last_name,$email,$specialty);
$stmt->execute();
$stmt->close();


$mail = new PHPMailer(true);
try{
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'meznedmohamed123@gmail.com'; 
    $mail->Password = 'esds inhv npfp qunb';          
    $mail->Port = 587;

    $mail->setFrom('noreply@cromd.tn','CROMD Ben Arous');
    $mail->addAddress('meznedmohamed123@gmail.com','Admin');

    $mail->isHTML(true);
    $mail->Subject = 'Nouvelle inscription au congres';

    $approve_link = "http://localhost/project/admin/approve.php?num=" . urlencode($num) . "&email=" . urlencode($email);

    $mail->Body = "
      <p>Nouvelle inscription :</p>
      <ul>
        <li>Nom: {$last_name}</li>
        <li>Prénom: {$first_name}</li>
        <li>Email: {$email}</li>
        <li>Numéro: {$num}</li>
        <li>Spécialité: {$specialty}</li>
      </ul>
      <p><a href='{$approve_link}'>Cliquez ici pour approuver et envoyer le badge</a></p>
    ";

    $mail->send();
    echo "<script>alert('Inscription reçue! L\\'admin vous enverra le badge après validation.'); window.location='register.php';</script>";
}catch(Exception $e){
    echo "Erreur email: {$mail->ErrorInfo}";
}
?>