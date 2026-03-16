<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
require 'phpmailer/src/Exception.php';

$success="";
$error="";

if(isset($_POST['send'])){

$name = htmlspecialchars($_POST['name']);
$email = htmlspecialchars($_POST['email']);
$subject = htmlspecialchars($_POST['subject']);
$message = htmlspecialchars($_POST['message']);

$mail = new PHPMailer(true);

try {

$mail->isSMTP();
$mail->Host       = 'smtp.gmail.com';
$mail->SMTPAuth   = true;
$mail->Username   = 'meznedmohamed123@gmail.com';   
$mail->Password   = 'esds inhv npfp qunb';     
$mail->SMTPSecure = 'tls';
$mail->Port       = 587;
$mail->setFrom($email, $name);
$mail->addAddress('meznedmohamed123@gmail.com'); 
$mail->isHTML(true);
$mail->Subject = $subject;
$mail->Body = "
<h3>New Contact Message</h3>
<b>Name:</b> $name <br>
<b>Email:</b> $email <br>
<b>Subject:</b> $subject <br><br>

<b>Message:</b><br>
$message
";
$mail->send();
$success="Message sent successfully!";
} catch (Exception $e) {
$error="Message could not be sent.";
}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact Us</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
:root{
--prim:#114B5F;
--sec:#d5d5d5;
--cta:#570000;
}

body{
background:var(--sec);
font-family:"Segoe UI",sans-serif;
padding-top:120px;
}
.navbar{
    background-color: var(--sec);
    padding: 20px 0;
}
.nav-link{
    background-color: var(--sec);
    color:var(--cta);
    border-radius: 20px;
    padding: 10px;
    margin: 10px;
    line-height: 40px;
    font-weight: bold;
}

.contact-header{
background:var(--prim);
color:white;
padding:60px 0;
text-align:center;
}

.contact-box{
background:white;
padding:30px;
border-radius:10px;
box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

.info-box{
background:white;
padding:25px;
border-radius:10px;
box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

iframe{
width:100%;
height:250px;
border-radius:10px;
border:0;
}
.btn{
    background-color:var(--cta);
    color:white;
}
body a:hover:not(#logo){
    background-color: #570000;
}
.nav-item .active{
    background-color: #570000;
}
</style>
</head>
<body>
    <!--navbar-->
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
  <div class="container">
    <a id="logo" class="navbar-brand" href="#"><img src="images/logo.png" width="100px" height="100px" alt="logo"></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasNavbarLabel"><img src="images/logo.png" width="100px" height="100px" alt="logo"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="index.html">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="register.php">Inscription</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="contact.php">Contact</a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</nav>
<section class="contact-header">
<div class="container">
<h1>Contact Us</h1>
<p>If you have any questions about the congress, feel free to contact us.</p>
</div>
</section>
<section class="container my-5">
<div class="row g-4">
<div class="col-md-7">
<div class="contact-box">
<h4 class="mb-4">Send us a message</h4>
<?php if($success){ ?>
<div class="alert alert-success"><?php echo $success ?></div>
<?php } ?>

<?php if($error){ ?>
<div class="alert alert-danger"><?php echo $error ?></div>
<?php } ?>
<form method="POST">
<div class="mb-3">
<label class="form-label">Full Name</label>
<input type="text" class="form-control" name="name" required>
</div>
<div class="mb-3">
<label class="form-label">Email</label>
<input type="email" class="form-control" name="email" required>
</div>
<div class="mb-3">
<label class="form-label">Subject</label>
<input type="text" class="form-control" name="subject" required>
</div>
<div class="mb-3">
<label class="form-label">Message</label>
<textarea class="form-control" rows="5" name="message" required></textarea>
</div>
<button class="btn w-100" name="send">
Send Message
</button>
</form>
</div>
</div>
<div class="col-md-5">
<div class="info-box mb-4">
<h5>Contact Information</h5>
<p><strong>Email:</strong> contact@dentalcongress.com</p>
<p><strong>Phone:</strong> +216 XX XXX XXX</p>
<p><strong>Location:</strong> Tunis, Tunisia</p>
</div>
</div>
</div>
</section>
<footer class="bg-dark text-white text-center p-3">
© 2026 Dental Congress
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>