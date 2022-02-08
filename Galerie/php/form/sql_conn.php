<?php
/* Script pro připojení do databáze */
class sql_conn
{
    public function connection(string $file)
    {
        $conn = mysqli_connect($this->foreach(0, $file), $this->foreach(1, $file), $this->foreach(2, $file), $this->foreach(3, $file));
        if (!$conn) {
            return false;
        } else {
            return $conn;
        }
    }

    /* Funkce, která zjistí, odkud se class sql_conn volá a podle toho načte cestu k config.ini
    * Od config.ini získá informace pro připojení do databáze 
     */
    public function foreach($i, $file)
    {
        if ($file == "login") {
            $raw_data = parse_ini_file("../../utils/config.ini");
        } else if ($file == "load_files" or $file == "load_images") {
            $raw_data = parse_ini_file("utils/config.ini");
        } else if ($file == "delete" or $file == "create") {
            $raw_data = parse_ini_file("../../utils/config.ini");
        } else if ($file == "get_namefiles") {
            $raw_data = parse_ini_file("../utils/config.ini");
        } else if ($file == "insert_img") {
            $raw_data = parse_ini_file("../../utils/config.ini");
        }
        $o = 0;
        foreach ($raw_data as $value) {
            if ($i == $o) {
                return $value;
            } elseif ($i == $o) {
                return $value;
            } elseif ($i == $o) {
                return $value;
            } elseif ($i == $o) {
                return $value;
            }
            $o++;
        }
    }

    /* Funkce, která zkontroluje, zdali připojení do databáze bylo úspěšné */
    public function islogged(string $file): bool
    {
        $conn = mysqli_connect($this->foreach(0, $file), $this->foreach(1, $file), $this->foreach(2, $file), $this->foreach(3, $file));
        if (!$conn) {
            return false;
        } else {
            return true;
        }
    }

    /* Zavolá funkci islogged a vrátí informace o tom, zdali připojení do databáze bylo úspěšné */
    public function validation(string $file)
    {
        $state = $this->islogged($file);
        if ($state) {
            return TRUE;
        } else {
            echo ("Connection failed");
        }
    }
}
