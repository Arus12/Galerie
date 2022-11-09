<!DOCTYPE html>
<html lang="cs">

<head>
    <title>Galerie - nahrát</title>
    <link href="../css/style-upload.css" rel="stylesheet" />
    <link rel="icon" href="../img/logo.png">
    <meta name="description" content="Galerie jako webová služba">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="author" content="Jan Černý" />
    <script src="https://kit.fontawesome.com/afcc9b0842.js" crossorigin="anonymous"></script>
</head>

<body>
    <?php
    /* Script pro generování html stránka pro nahrávání obrázků */
    require_once("../php/upload/getNameFiles.php");
    require_once("../php/form/states.php");
    require_once("../php/nav.php");
    $obj = new upload();
    class upload
    {
        public function __construct()
        {
            $this->get_header_nav();
            $this->get_main();
            $this->get_footer();
        }

        /* Funkce která generuje hlavičku stránky */

        public function get_header_nav()
        {
            $obj = new login("", "", "", "");
            $fname = $obj->getfname();
            $lname = $obj->getlname();
            $obj = new nav();
            echo ($obj->nav_upload($fname, $lname));
        }

        /* Funkce která generuje obsah stránky
        *zjištuje zdali na stránce nahrávání uživatel nahrává nový obrázek, nebo pouze upravuje stávající obrázek
        *pokud uživatel nahrává nový obrázek, script generuje prázdný formulář
        *pokud uživatel upravuje stávající obrázek, pak script generuje formulář již s informacemi, které daný obrázek obsahuje
        *pokud člověk při nahrávání nebo při úpravě obrázku zadal neúplné, nebo špatné údaje,
         script generuje formulář znovu s již vyplněnými informacemi, aby uživatel nemusel vše vyplňovat znova
        */

        public function get_main()
        {
            $login = new login("", "", "", "");
            $email = $login->get_email();
            $obj = new getNameFiles($email);
            $foldersname = $obj->get_sql_data($email);
            $new_edit = $login->get_new_edit();
            if ($new_edit == "new_image") {
                $data = $login->get_data();
                $folders = $login->get_folders();
                $state = $login->get_upload_state();
                if ($state == "error") {
                    echo ('<div class="error"><p>Zadali jste špatné, nebo neúplné údaje. Prosím zkuste to znovu</p>');
                }
                $login->set_upload_state("NOTHING");
                echo ('<div class="container" id="container">
			<form action="../php/upload/uploadImg.php" method="post" enctype=multipart/form-data>
			<div class="form-container sign-in-container">
				<h1>Základní informace</h1>');
                if (isset($data["name"])) {
                    echo ('<input type="text" value = ' . $data["name"] . ' name="nameImage" />');
                } else {
                    echo ('<input type="text" placeholder="Název obrázku" name="nameImage" />');
                }
                if (isset($data["date"]) != null) {
                    echo ('<input type="date" id="date" name="dateImage" value="' . $data["date"] . '"min="1800-01-01" max="' . date("Y-m-d") . '">');
                } else {
                    echo ('<input type="date" id="date" name="dateImage" value="' . date("Y-m-d") . '"min="1800-01-01" max="' . date("Y-m-d") . '">');
                }
                echo ('<p>Vyberte možnost zobrazení:</p>
                    <select id="state" name="stateImage">');
                if (isset($data["state"]) == "public") {
                    echo ('<option value="public">Veřejné</option>
                              <option value="private">Soukromé</option>
                              </select>');
                } else {
                    echo ('<option value="private">Soukromé</option>
                              <option value="public">Veřejné</option>
                              </select>');
                }
                echo ('<p>Vyberte složku/y do které chcete nahrát obrázek (pro výběr vícero složek držte CTRL):</p>
                    <select name="folderImage[]" multiple>
                    ');
                $i = 0;
                foreach ($foldersname as $foldername) {
                    if (isset($folders[$i]) != NULL) {
                        if ($folders[$i] == $foldername) {
                            echo ('<option value="' . $foldername . '"selected>' . $foldername .  '</option>');
                            $i++;
                        } else {
                            echo ('<option value="' . $foldername . '">' . $foldername .  '</option>');
                        }
                    } else {
                        echo ('<option value="' . $foldername . '">' . $foldername .  '</option>');
                        $i++;
                    }
                }
                echo ('
                    </select>
				</div>
				<div class="form-container sign-up-container">
				<h1>Nahrání obrázku</h1>
                <p>nebo sem vložte obrázek:</p>
                <div id="image_drop_area">
                <input type = "file" id= "img" name="ImageDrag_And_Drop" onchange="readURL(this);" enctype="multipart/form-data" accept=".png, .jpg">
                </div>
				<div class = "submit">
				<button type="submit">Nahrát obrázek</button>
				</div>
		</div>
			</form>
		<div class="overlay-container">
			<div class="overlay">
				<div class="overlay-panel overlay-left">
					<button class="ghost" id="signin">Zpět</button>
				</div>
				<div class="overlay-panel overlay-right">
					<h1>Přejít na další část</h1>
					<button class="ghost" id="signup">Další</button>
				</div>
			</div>
		</div>
	</div>');
            } else if ($new_edit == "edit_image") {
                $data = $login->get_data();
                $folders = $login->get_folders();
                $login->set_new_edit("new_image");
                $state = $login->get_upload_state();
                if ($state == "error") {
                    echo ('<div class="error"><p>Zadali jste špatné, nebo neúplné údaje. Prosím zkuste to znovu</p>');
                }
                $login->set_upload_state("NOTHING");
                echo ('<div class="container" id="container">
			<form action="../php/upload/edit.php" method="post" enctype=multipart/form-data>
			<div class="form-container sign-in-container">
				<h1>Základní informace</h1>');
                echo ('<input type="text" value = ' . $data["name"] . ' name="nameImage" />');

                echo ('<input type="date" id="date" name="dateImage" value="' . $data["date"] . '"min="1800-01-01" max="' . date("Y-m-d") . '">');
                echo ('<p>Vyberte možnost zobrazení:</p>
                    <select id="state" name="stateImage">');
                if ($data["state"] == "public") {
                    echo ('<option value="public">Veřejné</option>
                              <option value="private">Soukromé</option>
                              </select>');
                } else {
                    echo ('<option value="private">Soukromé</option>
                              <option value="public">Veřejné</option>
                              </select>');
                }
                echo ('</select>
						<p>Vyberte složku/y do které chcete nahrát obrázek (pro výběr vícero složek držte CTRL):</p>
                    <select name="folderImage[]" multiple>
                    ');
                for ($x = 0; $x < count($foldersname); $x++) {
                    $z = 0;
                    for ($y = 0; $y < count($folders); $y++) {
                        if ($foldersname[$x] == $folders[$y]) {
                            echo ('<option value="' . $folders[$y] . '"selected>' . $folders[$y] .  '</option>');
                            $z++;
                        }
                    }
                    if ($z == 0) {
                        echo ('<option value="' . $foldersname[$x] . '">' . $foldersname[$x] .  '</option>');
                    }
                }
                echo ('
                    </select>
                    <input type="hidden" value ="edit" name="data" />
                    <input type="hidden" value ="' . $data["name"] . '" name="lastnamefile" style:"display:none;" />
                    <input type="hidden" value ="' . $data["iduser"] . '" name="iduser" style:"display:none;" />
				</div>
                <div class="overlay-panel overlay-right">
					<button class="ghost">Upravit</button>
				</div>
		</div>
			</form>
		</div>');
            }
        }

        /* Script, který generuje patičku stránky*/
        public function get_footer()
        {
            $login = new login("", "", "", "");
            $fname = $login->getfname();
            $lname = $login->getlname();
            $obj = new nav();
            echo ('</main><footer><p> 
                <div class = "username"><p>' . $fname . '</p><p>' . $lname . '</p></div>
                
                <p>' . date("d.m.Y") . '</p><h2>Nahrát</h2><author>Autor: Jan Černý</author></footer>');
        }
    }
    ?>
</body>
<script type="text/javascript" src="../javascripts/form.js"></script>
<script type="text/javascript" src="../javascripts/DragAndDrop.js"></script>

</html>
