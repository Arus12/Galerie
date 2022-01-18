<html lang="cs">

<head>
    <title>Galerie</title>
    <link href="../css/style.css" rel="stylesheet" />
    <link rel = "icon" href="../img/logo.jpg">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="author" content="Jan Černý" />
    <script src="https://kit.fontawesome.com/afcc9b0842.js" crossorigin="anonymous"></script>
</head>

<body>
    <header>
        <div class="logo">
            <a><img src="../img/logo.jpg" /></a>
        </div>
        <h1>Galerie</h1>
    </header>
    <main>
        <?php
        require("../php/load.php");
        $files = new load("images");
        ?>
    </main>
</body>

</html>