<!DOCTYPE html>
<html lang="cs">
  <head>
    <title>Datum a čas</title>
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link
      href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300&display=swap"
      rel="stylesheet"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Oswald:wght@500&display=swap"
      rel="stylesheet"
    />
    <link href="scss/style.scss" rel="stylesheet" />
    <meta charset="utf-8" />
    <meta name="author" content="Jan Černý"/>
  </head>
  <body>
  <header>
  <div class="nadpis">
  <h1>Aktuální datum a čas</h1>
  </div>
  </header>
  <main>
    <div class="tabulka">
            <ul>
            <div class="datum">
              <li><p>Datum:</p></li>
              <li><?php echo date('d. m. Y');?></li>
            </div>
            <div class = čas>
              <li><p>Čas:</p></li>
              <li><?php echo date('H : i : s');?></li>
            </div>
            </ul>
    </div>
  </main>
  <footer>
  <p>Autor: Jan Černý</p>
  </footer>
  </body>
</html>