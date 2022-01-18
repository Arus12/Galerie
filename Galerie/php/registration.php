<?php
$obj = new registration();

//$query = "SELECT * FROM $usertable";  $result = mysql_query($query); 

class registration
{

    private $fname;
    private $lname;
    private $email;
    private $password;
    private $conn;

    public function __construct()
    {
        $this->getdata();
        $state = $this->connection();
        $this->put_db($state);
        $this->conn->close();
    }

    public function getdata()
    {
        if(htmlspecialchars($_REQUEST["fname"]) != null and htmlspecialchars($_REQUEST["lname"]) != null and htmlspecialchars($_REQUEST["email"]) != null and htmlspecialchars($_REQUEST["password"]) != null){
            $this->fname = htmlspecialchars($_REQUEST["fname"]);
            $this->lname = htmlspecialchars($_REQUEST["lname"]);
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

    public function put_db($state)
    {
        $sql = "SELECT password FROM user WHERE e_mail='$this->email'";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            die("Uživatel pod touto emailovou adresou je již registrovaný");
        }
        if ($state) {
            $sql2 = "INSERT INTO `user` (`first_name`, `last_name`, `e_mail`, `password`) VALUES ('$this->fname', '$this->lname', '$this->email', '$this->password');";
        } else {
            echo ("Connection failed");
        }

        if ($this->conn->query($sql2) === TRUE) {
            echo "Registrace úspěšná";
          } else {
            echo "Error: " . $sql2 . "<br>" . $this->conn->error;
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
