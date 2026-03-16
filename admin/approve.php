<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING); // show errors except notices/warnings
ini_set('display_errors', 1);
session_start();
include('../config/database.php');

require_once('../tcpdf/tcpdf.php');

require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';
require '../phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$num = $_GET['num'] ?? '';
$email = $_GET['email'] ?? '';

// get user
$stmt = $conn->prepare("SELECT * FROM registrations WHERE num=? AND email=? AND approved=0");
$stmt->bind_param("ss",$num,$email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
if(!$user) die("Utilisateur non trouvé ou déjà approuvé.");

// mark approved
$stmt = $conn->prepare("UPDATE registrations SET approved=1 WHERE num=? AND email=?");
$stmt->bind_param("ss",$num,$email);
$stmt->execute();
$stmt->close();

// generate badge
$pdf = new TCPDF('L','mm',[100,60],true,'UTF-8',false);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetMargins(0,0,0);
$pdf->SetAutoPageBreak(false,0);
$pdf->AddPage();

// Card background
$pdf->SetFillColor(245,247,252); 
$pdf->Rect(0,0,100,60,'F');

// Top bar with society name
$pdf->SetFillColor(10,35,80);
$pdf->Rect(0,0,100,18,'F');
$pdf->SetFont('helvetica','B',10);
$pdf->SetTextColor(255,255,255);
$pdf->SetXY(5,4);
$pdf->Cell(90,5,'CROMD Ben Arous',0,1,'L');
$pdf->SetFont('helvetica','',7);
$pdf->SetTextColor(180,210,255);
$pdf->SetXY(5,10);
$pdf->Cell(90,5,'Assemblée Générale Élective 2026',0,1,'L');

// Name
$fullName = mb_strtoupper($user['last_name'],'UTF-8').' '.$user['first_name'];
$pdf->SetFont('helvetica','B',13);
$pdf->SetTextColor(10,35,80);
$pdf->SetXY(5,22);
$pdf->Cell(65,7,$fullName,0,1,'L');

// Specialty
if(!empty($user['specialty'])){
    $pdf->SetFillColor(0,120,255);        
    $pdf->SetTextColor(10,35,80);         
    $pdf->SetFont('helvetica','',7);
    $tagWidth = min(mb_strlen($user['specialty'])*2.2+6, 60);
    $pdf->RoundedRect(5,30,$tagWidth,5.5,2,'F'); 
    $pdf->SetXY(6,30.5);
    $pdf->Cell($tagWidth,6,$user['specialty'],0,1,'L'); 
}

// Badge number
$pdf->SetFont('helvetica','B',7);
$pdf->SetTextColor(10,35,80);
$pdf->SetXY(5,38);
$pdf->Cell(65,5,"Badge N°: ".$user['num'],0,1,'L');

// Optional email row
$pdf->SetFont('helvetica','',6.5);
$pdf->SetXY(5,44);
$pdf->Cell(65,5,"Email: ".$user['email'],0,1,'L');

// QR code to check badge (ONLY ONCE)
$qrLink = "http://localhost/project/check_badge.php?num=" . urlencode($user['num']) . "&email=" . urlencode($user['email']);
$pdf->write2DBarcode(
    $qrLink, 
    'QRCODE,H', 
    74,  // X position
    22,  // Y position
    21,  // width
    21,  // height
    [
        'border' => 0,
        'vpadding' => 1,
        'hpadding' => 1,
        'fgcolor' => [10,35,80],
        'bgcolor' => false
    ], 
    'N'
);

// Bottom bar
$pdf->SetFillColor(10,35,80);
$pdf->Rect(0,56,100,4,'F');
$pdf->SetFillColor(212,175,55);
$pdf->Rect(0,56,18,4,'F');
$pdf->SetFont('helvetica','I',5.5);
$pdf->SetTextColor(180,210,255);
$pdf->SetXY(20,57);
$pdf->Cell(60,3,'www.cromd-benarous.tn',0,0,'L');

// save temporarily
$filename = tempnam(sys_get_temp_dir(),'badge_').'.pdf';
$pdf->Output($filename,'F');

$mail = new PHPMailer(true);
try{
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'meznedmohamed123@gmail.com';
    $mail->Password = 'esds inhv npfp qunb'; 
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('noreply@cromd.tn','CROMD Ben Arous');
    $mail->addAddress($user['email'],$fullName);
    $mail->isHTML(true);
    $mail->Subject = 'Votre badge CROMD Ben Arous';
    $mail->Body = "Bonjour {$fullName},<br>Votre badge est ci-joint. Merci de ne le partager avec personne.";
    $mail->addAttachment($filename,"badge_{$user['num']}.pdf");
    $mail->send();
    unlink($filename);
    echo "Badge envoyé avec succès à {$user['email']}";
}catch(Exception $e){
    echo "Erreur email: {$mail->ErrorInfo}";
}
?>