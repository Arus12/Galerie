<?php
require_once("php/load/get_file.php");
/* Script, který vytváří html patičky */
class footer extends get_file
{
    /* Konstruktor, který zavolá funkci get_footer a předá funkci informace */
    public function __construct(string $location, string $file)
    {
        $this->get_footer($location, $file);
    }
    /* Funkce, která zkontroluje, kde se momentálně uživatel v galerii nachází a podle lokace vytvoří html a html echuje */
    public function get_footer(string $location, string  $file)
    {
        if ($location == "home") {
            echo ('</div></main><footer><p>' . date("d.m.Y") . '</p><h2>' . "Přehled" . '</h2><author>Autor: Jan Černý </author></footer>');
        } else if ($location == "files") {
            $file = $this->get_filename();
            echo ('</div></main><footer><p>' . date("d.m.Y") . '</p><h2>' . $file . '</h2><author>Autor: Jan Černý </author></footer>');
        } else {
            die("ERROR");
        }
    }
}
