<html lang="cz">

<head>
    <title>Galerie</title>
    <link href="css/style.min.css" rel="stylesheet" type="text/css" />
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="author" content="Jan Černý" />
    <script src="https://kit.fontawesome.com/afcc9b0842.js" crossorigin="anonymous"></script>
</head>

<body>
    <header>
        <div class="logo">
            <a><img src="img/logo.jpg" /></a>
        </div>
        <h1>Galerie</h1>
    </header>
    <main>
        <div class="buttons">
            <a href="./pages/upload.php"><button class="button">Náhrát</button></a>
</div>
        <div class="images">
        <?php
        require_once("./php/load.php");
        ?>
            
        </div>
<?php
        /*<script language="Javascript">
              
              divElement = document.querySelector("html");
        
              width = divElement.clientWidth;
              if(width < 768){
                document.write('<a href="../home.php"><i class="fas fa-arrow-circle-left"></i></a>');
            }
              
    
        
</script>
*/
?>
    </main>
    <footer>
        <p><?php echo(date("d.m.Y")); ?></p>
        <h2>Přehled</h2>
        <author>Autor: Jan Černý </author>
    </footer>
</body>

</html>