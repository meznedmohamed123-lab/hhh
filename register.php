<?php
session_start();

if(!isset($_SESSION['csrf_token'])){
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Inscription</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
:root{
    --prim:#114B5F;
    --sec:#d5d5d5;
    --cta:#570000;
}
body{
background:var(--sec);
font-family: "Segoe UI", sans-serif;
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
.register-card{
max-width:600px;
margin:auto;
}

.btn-register{
background:#114B5F;
color:white;
font-weight:bold;
}

.btn-register:hover{
background:#0d3b4a;
color:white;
}
</style>
</head>
<body class="bg-light">
<!-- Navbar -->
<nav class="navbar navbar-expand-lg fixed-top">
<div class="container">
<a class="navbar-brand" href="index.html">
<img src="images/logo.png" width="80" height="80">
</a>
<button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar">
<span class="navbar-toggler-icon"></span>
</button>
<div class="offcanvas offcanvas-end" id="offcanvasNavbar">
<div class="offcanvas-header">
<h5>
<img src="images/logo.png" width="80">
</h5>
<button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
</div>
<div class="offcanvas-body">
<ul class="navbar-nav ms-auto">
<li class="nav-item">
<a class="nav-link" href="index.html">Home</a>
</li>
<li class="nav-item">
<a class="nav-link active" href="register.php">Inscription</a>
</li>
<li class="nav-item">
<a class="nav-link" href="contact.php">Contact</a>
</li>
</ul>
</div>
</div>
</div>
</nav>
<!-- Registration Form -->
<div class="container py-5">
<div class="row justify-content-center">
<div class="col-md-6">
<div class="register-card p-4 bg-white shadow rounded">
<h2 class="text-center mb-4">Inscription au congrès</h2>
<form action="save_registration.php" method="POST">
<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
<div class="mb-3">
<label class="form-label">Numéro (Licence)</label>
<input type="text" name="num" class="form-control" required>
</div>
<div class="row">
<div class="col-md-6 mb-3">
<label class="form-label">Nom</label>
<input type="text" name="last_name" class="form-control" required>
</div>
<div class="col-md-6 mb-3">
<label class="form-label">Prénom</label>
<input type="text" name="first_name" class="form-control" required>
</div>
</div>
<div class="mb-3">
<label class="form-label">Email</label>
<input type="email" name="email" class="form-control" required>
</div>
<div class="mb-3">
<label class="form-label">Spécialité</label>
<select name="specialty" class="form-select">
<option value="Dentiste généraliste">Dentiste généraliste</option>
<option value="Orthodontiste">Orthodontiste</option>
<option value="Implantologie">Implantologie</option>
<option value="Étudiant">Étudiant</option>
<option value="Autre">Autre</option>
</select>
</div>
<div class="text-center">
<button type="submit" class="btn btn-register w-100">
S'inscrire et attendre validation
</button>
</div>
</form>
</div>
</div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>