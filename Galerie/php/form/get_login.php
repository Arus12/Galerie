<?php
include_once("login.php");
$obj = new get_login;

/* Script, který zjišťuje, zdali byly z přihlašovacího formuláře všechny potřebné informace doplněny.
* Poté script zavolá metodu login, který poté zjištuje, zdali člověk zadal všechny informace správně.
 */

class get_login
{
    public $state;
    public function __construct()
    {
        $this->get();
    }
    public function get()
    {
        if (htmlspecialchars($_REQUEST["password"]) == '') {
            $pass = null;
        } else {
            $pass = sha1(htmlspecialchars($_REQUEST["password"]));
        }

        if (htmlspecialchars($_REQUEST["email"]) == '') {
            $email = null;
        } else {
            $email = htmlspecialchars($_REQUEST["email"]);
        }
        $login = new login("login", $email, $pass);
        $this->state = $login->getstate();
        $this->check();
    }

    /* Funkce, která zkontroluje, zdali všechny informace byly doplněny */

    public function check()
    {
        if ($this->state == "NOT_ALL") {
            header("Location: ../../index.php");
        }
    }
}
