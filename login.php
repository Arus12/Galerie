<html lang="cs">
<head>
<title>Přihlášení</title>
<link href="../css/style-login.css" rel="stylesheet" />
<link rel = "icon" href="../img/logo.jpg">
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="author" content="Jan Černý" />
<script src="https://kit.fontawesome.com/afcc9b0842.js" crossorigin="anonymous"></script>
</head>
<body>
<div class="container" id="container">
	<div class="form-container sign-up-container">
		<form action="../php/registration.php" method = "POST">
			<h1>Registrace</h1>
			<input type="text" placeholder="Jméno" name="fname" />
			<input type="text" placeholder="Příjmení" name="lname" />
			<input type="email" placeholder="Email" name="email" />
			<input type="password" placeholder="Heslo" name="password" />
			<button type = "submit">Registrovat se</button>
		</form>
	</div>
	<div class="form-container sign-in-container">
		<form action="../php/login.php" method = "post">
			<h1>Přihlášení</h1>
			<input type="email" placeholder="Email" name="email" />
			<input type="password" placeholder="Heslo" name="password" />
			<button type = "submit">Přihlásit se</button>
		</form>
	</div>
	<div class="overlay-container">
		<div class="overlay">
			<div class="overlay-panel overlay-left">
				<h1>Cloudová galerie</h1>
				<p>Vítejte v cloudové galerii, které Vám poskytuje možnost uládat si své oblíbené fotky na cloud. </p>
				<button class="ghost" id="signin">Přihlášení</button>
			</div>
			<div class="overlay-panel overlay-right">
				<h1>Vítejte</h1>
				<p>Pokud chcete začít využívat galerii, můžete se zde registrovat a začít nahrávat své oblíbené fotky.</p>
				<button class="ghost" id="signup">Registrace</button>
			</div>
		</div>
	</div>
</div>
</body>
<script src="../javascripts/main.js"></script>
</html>