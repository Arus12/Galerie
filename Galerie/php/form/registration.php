<?php
include_once("login.php");
include_once("sql_conn.php");
/* Script, který registruje uživatele */
class registration extends sql_conn
{
    private $fname;
    private $lname;
    private $email;
    private $password;
    private $state;
    private $conn;

    /* Konstruktor, který kontroluje, zdali jsou všechny informace zadané uživatelem platné. 
    * Pak podle situace zavolá správnou funkci
    */
    public function __construct($fname, $lname, $email, $password, $cpassword = null)
    {
        $this->fname = $fname;
        $this->lname = $lname;
        $this->email = $email;
        $this->password = $password;

        if ($this->fname == null and $this->lname == null and $this->email == null and $cpassword == null) {
            $obj = new login("", "", "", "");
            $obj->setstate("NOT_ALL_REGISTER");
            $this->state = "NOT_ALL_REGISTER";
        } else if ($this->fname == null or $this->lname == null or $this->email == null or $cpassword == null) {
            $obj = new login("", "", "", "");
            $obj->setstate("NOT_ALL_REGISTER");
            $this->state = "NOT_ALL_REGISTER";
        } else if ($cpassword != $password) {
            $obj = new login("", "", "", "");
            $obj->setstate("R_W_PASSWORD");
            $this->state = "R_W_PASSWORD";
        } else {
            $this->conn = $this->connection("login");
            $this->registration($this->islogged("login"));
            $this->conn->close();
            $this->getstate();
        }
    }

    /* Funkce, která uživatele registruje pomocí databáze */
    public function registration($state)
    {
        $obj = new login("", "", "", "");
        if ($state) {
            $sql = "SELECT password, e_mail FROM user WHERE e_mail='$this->email'";
        } else {
            echo ("Connection failed");
        }
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $db_email = $row["e_mail"];
            }
        }
        if ($db_email === $this->email) {
            $this->state = "EMAIL_REGISTRED";
            $obj->setstate($this->state);
        } else {
            $sql = "INSERT INTO `user` (`first_name`, `last_name`, `e_mail`, `password`) VALUES ('$this->fname', '$this->lname', '$this->email', '$this->password');";
            $result = $this->conn->query($sql);
            if ($result === TRUE) {
                $this->state = "IS_REGISTRED";
                $obj->setstate($this->state);
            } else if ($result === FALSE) {
                $this->state = "NOT_REGISTRED";
                $obj->setstate($this->state);
            }
        }
    }

    /* Funkce, která vrací status toho, jak registrace dopadla */
    public function getstate()
    {
        return $this->state;
    }
}
