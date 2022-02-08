<?php
include_once("sql_conn.php");

/* Script, který zařizuje
* state (status toho, zdali se člověk přihlásil úspěšně, nebo neúspěšně. Když neúspěšně, tak zadá status pro danou chybovou hláškou).
* Celkově script zařizuje ověření přihlášení.
*/
class login extends sql_conn
{
    private $email;
    private $password;
    private $conn;

    /* Konstruktor spustí session a pokud nejsou potřebné proměnné zadané, uloží do něho základní parametry
    * poté spustí potřebné funkce
    */
    public function __construct($login = null, $email = null, $pass = null)
    {
        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();
        }
        if ($_SESSION["state"] == null) {
            $_SESSION["state"] = "NOTHING";
        }
        if (isset($_SESSION["data"]) == null) {
            $_SESSION["data"] = array(
                "name" => null,
                "date" => null,
                "state" => null,
            );
            $_SESSION["folders"] = array(null);
        }

        if (isset($_SESSION["new_edit"]) == null) {
            $_SESSION["new_edit"] = "new_image";
        }

        if (isset($_SESSION["upload_state"]) == null) {
            $_SESSION["upload_state"] = "NOTHING";
        }

        if ($login == "login") {
            $this->password = $pass;
            $this->email = $email;

            if ($this->password == null and $this->email == null) {
                $_SESSION["state"] = "NOT_ALL";
                return ($_SESSION["state"]);
            } else if ($this->password == null or $this->email == null) {
                $_SESSION["state"] = "NOT_ALL";
                $this->getstate();
            } else {
                $this->conn = $this->connection("login");
                $this->validation($this->islogged("login"));
                $this->conn->close();
                $this->getstate();
            }
        } else if ($login == "logout") {
            $this->logout();
        } else {
            $this->getstate();
        }
    }

    /* Funkce, která kontroluje přihlášení a popřípadě, 
    když člověk všechna informace zadal správně, tak uloží informace o příjmění a jménu uživatele */
    public function validation($state)
    {
        if ($state) {
            $sql = "SELECT password, first_name, last_name FROM user WHERE e_mail='$this->email'";
        } else {
            echo ("Connection failed");
        }
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $db_password = $row["password"];
                $_SESSION["fname"] = $row["first_name"];
                $_SESSION["lname"] = $row["last_name"];
            }
        } else {
            $_SESSION["state"] = "W_EMAIL";
            header("Location: ../../pages/forn.php");
        }
        if ($this->password == $db_password) {
            $_SESSION["state"] = "logged";
            $_SESSION["email"] = $this->email;
            header("Location: ../../index.php");
        } else {
            $_SESSION["state"] = "WRONG";
            header("Location: ../../pages/form.php");
        }
    }


    /* Funkce pro odhlášení */
    public function logout()
    {
        $_SESSION["state"] = "NOTHING";
        header("Location: ../../pages/form.php");
    }


    /* Funkce pro vrácení dat při nahrávání obrázků */
    public function get_data()
    {
        if (isset($_SESSION["data"]) and $_SESSION["data"] != null) {
            $data = $_SESSION["data"];
            $_SESSION["data"] = null;
            return $data;
        } else {
            return NULL;
        }
    }


    /* Funkce, která vrací seznam složek daného uživatele při nahráván obrázků */
    public function get_folders()
    {
        if (isset($_SESSION["folders"]) and $_SESSION["folders"] != null) {
            $folders = $_SESSION["folders"];
            $_SESSION["folders"] = NULL;
            return $folders;
        } else {
            return NULL;
        }
    }


    public function getstate()
    {
        return $_SESSION["state"];
    }
    public function setstate(string $state)
    {
        $_SESSION["state"] = $state;
    }
    public function getfname()
    {
        return $_SESSION["fname"];
    }
    public function getlname()
    {
        return $_SESSION["lname"];
    }
    public function get_email()
    {
        return $_SESSION["email"];
    }

    public function set_data($data)
    {
        $_SESSION["data"] =  $data;
    }

    public function set_folders($folders)
    {
        $_SESSION["folders"] = $folders;
    }

    public function get_upload_state()
    {
        return $_SESSION["upload_state"];
    }

    public function set_upload_state($state)
    {
        $_SESSION["upload_state"] = $state;
    }

    public function get_new_edit()
    {
        return $_SESSION["new_edit"];
    }

    public function set_new_edit($new_edit)
    {
        $_SESSION["new_edit"] = $new_edit;
    }
}
