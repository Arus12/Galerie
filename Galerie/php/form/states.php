<?php
require_once("login.php");
/* Script, který řeší chyby a vrací potřebné informace */
class states
{
    public function __construct($file)
    {
        if ($file == "form") {
            $this->form();
        } else if ($file == "get_email") {
            return $this->get_email();
        } else {
        }
    }

    /* Funkce, která zkontroluje error při formuláři na přihlašování a registraci. 
    * Když žádný error nebyl nalezen a vše proběhlo v pořádku, uživatele přemístí na hlavní stránku a nastaví status na logged
    * Pokud uživatel není v pořádku přihlášen, nebo registrován, pak tato funkce vrací div s patříčním textem s errorem
    */
    public function form()
    {

        $obj = new login("state", "", "");
        $error = $obj->getstate();
        if ($error == "WRONG") {
            $obj->setstate("NOTHING");
            return '<div class="error"><p>Zadali jste špatné údaje! Prosíme, zkuste to znova</p>';
        } else if ($error == "W_EMAIL") {
            $obj->setstate("NOTHING");
            return ('<div class="error"><p>Tento email není zatím registrovaný!</p>');
        } else if ($error == "NOTHING") {
        } else if ($error == "NOT_ALL") {
            $obj->setstate("NOTHING");
            return ('<div class="error"><p>Nezadali jste všechny potřebné informace! Prosím zkuste to znovu</p>');
        } else if ($error == "NOT_ALL_REGISTER") {
            $obj->setstate("NOTHING");
            return ('<div class="error"><p>V registraci jste nezadali všechny potřebné informace</p>');
        } else if ($error == "EMAIL_REGISTRED") {
            $obj->setstate("NOTHING");
            return ('<div class="error"><p>Tento email je již registrovaný! Zkuste to prosím znovu a použijte jiný email</p>');
        } else if ($error == "R_W_PASSWORD") {
            $obj->setstate("NOTHING");
            return ('<div class="error"><p>Zadaná hesla se neshodují! Prosím zkuste to znovu</p>');
        } else if ($error == "NOT_REGISTRED") {
            $obj->setstate("NOTHING");
            return ('<div class="error"><p>Registrace selhala</p>');
        } else if ($error == "IS_REGISTRED") {
            $obj->setstate("NOTHING");
            return ('<div class="succes"><p>Úspěšně registrováno</p>');
        } else if ($error == "logged") {
            header("Location: ../../index.php");
        }
    }

    /* Funkce, která zkotroluje, zdali nebyl nalezena nějaká chyba a popřípadě, když není nalezena, volá funkci get_index */
    public function index(string $files_or_imgs, string $admin_state)
    {
        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();
            $_SESSION["foldername"] = NULL;
        }
        $login = new login("", "", "");
        $state = $login->getstate();
        if ($state == "NOTHING") {
            header("Location: pages/form.php");
        } else if ($state == "W_EMAIL") {
            header("Location: pages/form.php");
        } else if ($state == "WRONG") {
            header("Location: pages/form.php");
        } else if ($state == "NOT_ALL") {
            header("Location: pages/form.php");
        } else if ($state == "NOT_ALL_REGISTER") {
            header("Location: pages/form.php");
        } else if ($state == "logged") {
            $fname = $login->getfname();
            $lname = $login->getlname();
            return $this->get_index($files_or_imgs, $fname, $lname, $admin_state);
        } else {
            die;
        }
    }

    /* Funkce, která kontroluje, zdali si uživatel na hlavní stránce prohlíží složky, nebo složku s obrázky.
    * Poté vrací navigační panel s tlačítky na základě, kde se daný uživatel nachází
    */
    public function get_index(string $files_or_imgs, string $fname, string $lname, string $admin_state)
    {
        require_once("php/nav.php");
        $login = new login("", "", "");
        if ($files_or_imgs == "files") {
            $_SESSION["email"] = $login->get_email();
            $login->set_new_edit("new_image");
            $nav = new nav($fname, $lname, $admin_state);
            return ($nav->nav_home($fname, $lname, $admin_state));
        } else if ($files_or_imgs == "imgs") {
            $login->set_new_edit("new_image");
            $nav = new nav($fname, $lname, $admin_state, $this->get_foldername());
            return ($nav->nav_imgs($fname, $lname, $admin_state, $this->get_foldername()));
        } else {
            die;
        }
    }
    public function get_email()
    {
        $email = $_SESSION["email"];
        return $email;
    }
    public function get_foldername()
    {
        return $_SESSION["foldername"];
    }
    public function set_foldername($name)
    {
        $_SESSION["foldername"] = $name;
    }
}
