<?php
include_once("registration.php");
$obj = new get_registration;

/* Script, který zjišťuje, zdali byly z registračním formuláře všechny potřebné informace doplněny.
* Poté script zavolá metodu registration, který poté zjištuje, zdali člověk zadal všechny informace správně.
* Poté tyto informace metoda registration doplní do databáze.
 */

class get_registration
{
    public $state;
    public function __construct()
    {
        $this->get();
    }
    public function get()
    {
        if (htmlspecialchars($_REQUEST["fname"]) == '') {
            $fname = null;
        } else {
            $fname = htmlspecialchars($_REQUEST["fname"]);
        }

        if (htmlspecialchars($_REQUEST["lname"]) == '') {
            $lname = null;
        } else {
            $lname = htmlspecialchars($_REQUEST["lname"]);
        }
        if (htmlspecialchars($_REQUEST["email"]) == '') {
            $email = null;
        } else {
            $email = htmlspecialchars($_REQUEST["email"]);
        }
        if (htmlspecialchars($_REQUEST["password"]) == '') {
            $password = null;
        } else {
            $password = sha1(htmlspecialchars($_REQUEST["password"]));
        }
        if (htmlspecialchars($_REQUEST["cpassword"]) == '') {
            $password = null;
        } else {
            $cpassword = sha1(htmlspecialchars($_REQUEST["cpassword"]));
        }
        $obj = new registration($fname, $lname, $email, $password, $cpassword);
        $this->state = $obj->getstate();
        if (!file_exists("../../imgs/" . $email)) {
            mkdir("../../imgs/" . $email);
        }
        header("Location: ../../pages/form.php");
    }
}
