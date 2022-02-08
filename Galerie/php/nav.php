<?php
/* Script, který vytváří html hlavičky a navigačního menu stránky */
class nav
{
    /* Prázdný konstruktor */
    public function __construct()
    {
    }
    /* Funkce, která vytvoří html pro hlavní stránku se složkami
    * Funkce pak html vrací
    */
    public function nav_home(string $fname, string $lname, string $admin_state)
    {
        require_once("php/form/login.php");
        $header = ('<header><div class = "username"><p>' . $fname . '</p><p>' . $lname . '</p>
        </div><div class="logo"><img src="img/logo.png" /></div>
        <h1>Galerie</h1></header><main>');

        if ($admin_state == "OFF") {
            return ($header . '<div class="buttons"><div class = "nav_button">
            <form action="./php/form/logout.php"><input type="hidden"/><button>
            <p>Odhlásit se</p></button></form></div><div class = "nav_button">
            <form action="php/admin/switch_admin.php" method="post">
            <input type="hidden" name="switch" value="switch"/>
            <button type = "submit" class"link-button"><p>Administrace</p></button>
            </form></div></div><div class = "folders">
            ');
        } else {
            $obj = new login("", "", "", "");
            $state = $obj->get_upload_state();
            if ($state == "succes") {
                $mess = ('<div class="succes"><p>Úspěšně zadáno</p></div>');
            } else if ($state == "not_created") {
                $mess = ('<div class="error"><p>Nepodařilo se složku vytvořit</p></div>');
            } else {
                $mess = "";
            }
            $nav = ('<div class="buttons"><div class = "nav_button">
            <form action="./php/form/logout.php"><input type="hidden"/><button><p>Odhlásit se</p></button>
            </form></div>
            <div class = "nav_button"><form action="php/admin/switch_admin.php" method="post">
            <input type="hidden" name="switch" value="switch"/><button type = "submit" class"link-button">
            <p>Náhled</p></button></form>
            </div>
            <div class = "nav_button"><form action="./pages/upload.php"><input type="hidden"/><button><p>Nahrát obrázek</p></button></form></div>
            <div class=nav_button><button id="open-button" class="open-button" onclick="openForm()">
            <p>Vytvořit složku</p></button></div>
            <div class="form-popup" id="myForm">
            <form action="php/admin/createFile.php" class="form-container" method="POST">
            <input type="text" placeholder="Název složky" name="file" required>
            <button type="submit" class="btn">Vytvořit</button><button type="button" class="btn cancel" onclick="closeForm()">Zavřít</button>
            </form></div></div>
            ');
            $state = $obj->set_upload_state("NOTHING");
            return ($header . $nav . $mess . '<div class = "folders">');
        }
    }
    /* Funkce, která vytvoří html pro stránku se složkou a její obrázky
    * Funkce pak html vrací
    */
    public function nav_imgs(string $fname, string $lname, string $admin_state, string $foldername)
    {
        require_once("php/form/login.php");
        $header = ('<header><div class = "username"><p>' . $fname . '</p><p>' . $lname . '</p></div><div class="logo"><img src="img/logo.png" /></div>
        <h1>Galerie</h1></header><main>');

        if ($foldername == "public_folder") {
            return ($header . '<div class="buttons"><div class = "arrow">
            <form action="php/admin/switch_admin.php" method="post">
            <input type="hidden" name="switch" value="NOTHING"/><button type = "submit" >
            </button></form></div><div class = "nav_button"><form action="./php/form/logout.php">
            <input type="hidden"/><button><p>Odhlásit se</p>
            </button></form></div><div class = "nav_button"></div></div><div class="images">');
        }
        if ($admin_state == "OFF") {
            $nav = ('<div class="buttons">
            <div class = "arrow"><form action="php/admin/switch_admin.php" method="post">
            <input type="hidden" name="switch" value="NOTHING"/><button type = "submit" ></button></form></div>
            <div class = "nav_button"><form action="./php/form/logout.php"><input type="hidden"/><button><p>Odhlásit se</p></button></form></div>
            <div class = "nav_button"><form action="php/admin/switch_admin.php" method="post"><input type="hidden" name="switch" value="switch"/>
            <input type="hidden" name="foldername" value="' . $foldername . '"/><button type = "submit" class"link-button"><p>Administrace</p></button></form></div>
            </div>');
            return ($header . $nav . '<div class="images">');
        } else {
            $obj = new login("", "", "", "");
            $state = $obj->get_upload_state();
            $nav = ('<div class="buttons">
            <div class = "arrow"><form action="php/admin/switch_admin.php" method="post">
            <input type="hidden" name="switch" value="NOTHING"/><button type = "submit" ></button></form></div>
            <div class = "nav_button"><form action="./php/form/logout.php"><input type="hidden"/><button><p>Odhlásit se</p></button></form></div>
            <div class = "nav_button"><form action="php/admin/switch_admin.php" method="post"><input type="hidden" name="switch" value="switch"/>
            <input type="hidden" name="foldername" value="' . $foldername . '"/><button type = "submit" class"link-button"><p>Náhled</p></button></form></div>
            <div class = "nav_button"><form action="./pages/upload.php"><input type="hidden"/><button><p>Nahrát obrázek</p></button></form></div></div>');
            if ($state == "succes") {
                $mess = ('<div class="succes"><p>Úspěšně zadáno</p></div>');
            } else {
                $mess = "";
            }
            $state = $obj->set_upload_state("NOTHING");
            return ($header . $nav . $mess .  '<div class="images">');
        }
    }
    /* Funkce, která vytvoří html pro složky s úpravou obrázku, nebo nahrání nového obrázku
    * Funkce pak html vrací
    */
    public function nav_upload()
    {
        $header = ('<header><div class = "arrow"><form action="../php/admin/switch_admin.php" method="post">
        <input type="hidden" name="switch" value="FOLDER"/><button type = "submit" ></button></form></div><div class="logo"><img src="../img/logo.png" /></div>
        <h1>Galerie</h1></header><main>');
        return ($header);
    }
}
