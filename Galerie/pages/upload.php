<html lang="cz">

<head>
    <title>Galerie - nahrát</title>
    <link href="../css/style.min.css" rel="stylesheet" />
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
        <div class="back">
            <a href="../home.php"><i class="fas fa-arrow-circle-left"></i></a>
        </div>
        <div class="upload">
            <div class="img_name">
                <form action="odeslani.php">
                    <input type="text" name="img_name" placeholder="Název obrázku...">
                </form></input>
            </div>

            <div class="img_description">
                <form action="odeslani.php">
                    <textarea id="subject" name="img_description" placeholder="Popis obrázku..." style="height:500px"></textarea>
            </div>
            <div class="Nahrat">
                <form action="upload.php" method="post" enctype="multipart/form-data"><input type="file" name="fileToUpload" id="fileToUpload">
                </form>
            </div>
            <button type="submit" form="nameform" value="Submit">Hotovo</button>
        </div>
    </main>
    <footer>
        <p><?php echo (date("d.m.Y")); ?></p>
        <h2>Nahrát</h2>
        <author>Autor: Jan Černý</author>
    </footer>
</body>

</html>