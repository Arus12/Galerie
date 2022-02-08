<?php
$obj = new switch_admin("");

/* Funkce, která mění stav adminu */

class switch_admin
{
    public function __construct()
    {
        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (empty($_SESSION["admin"])) {
            $_SESSION["admin"] = "OFF";
        }
        if (empty($_SESSION["foldername"])) {
            $_SESSION["foldername"] = NULL;
        }
        if (isset($_REQUEST["switch"])) {
            if ($_REQUEST["switch"] == "NOTHING") {
                $_SESSION["foldername"] = NULL;
                header("Location: ../../index.php");
            } else if ($_REQUEST["switch"] == "FOLDER") {
                header("Location: ../../index.php");
            } else {
                if (isset($_REQUEST["foldername"])) {
                    $_SESSION["foldername"] = $_REQUEST["foldername"];
                    $this->switch();
                    header("Location: ../../index.php");
                } else {
                    $this->switch();
                    header("Location: ../../index.php");
                }
            }
        } else {
        }
    }

    /* Funkce, která změní stav adminu. 
    *Pokud je administrace vypnutá a uživatel ji chce zapnout, script ho zapne, opačně script admin vypne 
    */

    public function switch()
    {
        if ($_SESSION["admin"] == "OFF") {
            $_SESSION["admin"] = "ON";
        } else if ($_SESSION["admin"] == "ON") {
            $_SESSION["admin"] = "OFF";
        } else {
            $_SESSION["admin"] = "OFF";
        }
        return TRUE;
    }
    public function get_admin()
    {
        return $_SESSION["admin"];
    }
    public function set_admin($state)
    {
        $_SESSION["admin"] = $state;
    }
    public function get_foldername()
    {
        $foldername = $_SESSION["foldername"];
        return $foldername;
    }
    public function set_foldername(string $state)
    {
        $_SESSION["foldername"] = $state;
    }
}
