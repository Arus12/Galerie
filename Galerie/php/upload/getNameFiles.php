<?php
require_once("../php/form/sql_conn.php");
/*Script, který pomocí emailu zjistí z databáze základní informace */
class getNameFiles extends sql_conn{

    /* Konstruktor, který zavolá funkci get_sql_data
    * Této funkci předá email
    */
    public function __construct($email)
    {
        $this->get_sql_data($email);
    }

    /* Funkce, která zavolá funkci get_ID_user a předá ji email uživatele.
    * Od funkce get_ID_user získá id uživatele
    * Poté pomocí id uživatele vrátí funkce seznam složek uživatele
    */
    public function get_sql_data(string $email){
    $this->conn = $this->connection("get_namefiles");
    $state = $this->validation("get_namefiles");
    if ($state === TRUE) {
        $iduser = $this->get_ID_user($email);
            $i = 0;
            $sql = "SELECT`name` FROM `folder` WHERE `users_idusers` = $iduser";
            $result = $this->conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $foldersname[$i] = $row["name"];
                    $i++;
                }
        } else {
            echo ("Connection failed");
        }
        if(empty($foldersname)){
            return ("");
        }else{
            return $foldersname;
        }
    }
}
/* Funkce, která zjistí z databáze pomocí emailu id uživatele a vrátí ho funkci get_sql_data */
    public function get_ID_user($email){
        $sql = "SELECT iduser FROM user WHERE e_mail='$email'";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $iduser = $row["iduser"];
            }
        }
        return $iduser;
    }
}
