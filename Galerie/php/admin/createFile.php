<?php
$obj = new createFile();
/* Script, který při vytvoření složky uživatelem, plní databázi daty */
class createFile
{
    public function __construct()
    {
        $this->createFile();
    }

    /* Funkce, která komunikuje s databázi a pokud již složka neexistuje, doplňuje do databáze nové informace zadané uživatelem */
    public function createFile()
    {
        $namefile = $_REQUEST["file"];
        require_once("../form/login.php");
        $states = new login();
        $email = $states->get_email();
        require_once("../form/sql_conn.php");
        $connect = new sql_conn();
        $conn = $connect->connection("create");
        $state = $connect->islogged("create");
        if ($state) {
            $sql = "SELECT `iduser` FROM `user` WHERE `e_mail` LIKE '$email'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $id_user = $row["iduser"];
                }
            }
            $sql = "SELECT * FROM folder WHERE name = '$namefile' and `users_idusers` = '$id_user'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $states->set_upload_state("not_created");
                header("Location: ../../index.php");
            } else {
                $result = $conn->query($sql);
                $sql = "INSERT INTO `folder` (`idfolder`, `users_idusers`, `name`) VALUES (NULL, '$id_user', '$namefile')";
                $result = $conn->query($sql);
                $conn->close();
                if ($result) {
                    $states->set_upload_state("succes");
                    header("Location: ../../index.php");
                } else {
                    die("ERROR");
                }
            }
        }
    }
}
