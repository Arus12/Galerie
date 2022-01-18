<?php
$obj = new login();

class login
{

    private $email;
    private $password;
    private $conn;

    public function __construct()
    {
        $this->getdata();
        $state = $this->connection();
        $this->validation($state);
        $this->conn->close();
    }

    public function getdata()
    {
        if(htmlspecialchars($_REQUEST["email"]) != null and htmlspecialchars($_REQUEST["password"]) != null){
            $this->email = htmlspecialchars($_REQUEST["email"]);
            $this->password = sha1(htmlspecialchars($_REQUEST["password"]));
        }else{
            die("Nezadali jste všechny potřebné údaje");   
        }
    }

    public function connection(): bool
    {
        parse_ini_file("../utils/config.ini");
        $this->conn = mysqli_connect($this->foreach(0), $this->foreach(1), $this->foreach(2), $this->foreach(3));
        if (!$this->conn) {
            return false;
        } else {
            return true;
        }
    }

    public function validation($state)
    {
        if ($state) {
            $sql = "SELECT password FROM user WHERE e_mail='$this->email'";
        } else {
            echo ("Connection failed");
        }
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $db_password = $row["password"];
            }
        } else {
            die("Zadaný email není zaregistrovaný");
        }
        if ($this->password == $db_password) {
            echo ("Jste přihlášen");
        } else {
            echo ("Zadali jste špatně přihlašovací údaje");
        }
    }

    public function foreach($i)
    {
        $raw_data = parse_ini_file("../utils/config.ini");
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
}
