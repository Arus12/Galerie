<?php
include_once("php/form/sql_conn.php");
/* Script, který vypíše všechny složky uživatele */
class load_files extends sql_conn
{
    private $conn;
    /* Konstruktor, která zavolá funkci get_sql_data */
    public function __construct(string $email, string $admin_state)
    {
        $this->get_sql_data($email, $admin_state);
    }
    /* Funkce, která zjistí z databáze zjistí potřebné informaces
    * Poté vrací html.
    */
    public function get_sql_data(string $email, string $admin_state)
    {
        $this->conn = $this->connection("load_files");
        $state = $this->validation("load_files");
        if ($state === TRUE) {
            $sql = "SELECT iduser FROM user WHERE e_mail='$email'";
            $result = $this->conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $iduser = $row["iduser"];
                }
            } else {
                echo ("Connection failed");
            }
            $sql = "SELECT idfolder, name FROM `folder` WHERE `users_idusers` = '$iduser'";
            $result = $this->conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $idfolder[] = $row["idfolder"];
                    $namefolder[] = $row["name"];
                }
            } else {
                $namefolder[0] = null;
            }
        } else {
            echo ("Connection failed");
        }
        $this->conn->close();
        if (empty($idfolder)) {
            $idfolder[0] = NULL;
        }
        return $this->load_files($namefolder, $admin_state, $idfolder);
    }

    /* Funkce, která podle přijatých informaci sestaví html.
    * Toto html pak vrátí funkci get_sql_data.
    */
    public function load_files(array $namefolder, string $admin_state, $idfolder)
    {
        $html = null;
        if ($namefolder[0] != null) {
            if ($admin_state == "OFF") {
                $html .= ('<div class="folder"><form method="POST" action="index.php" class="inline">
            <input type="hidden" name="file_name" value="public_folder">
            <button type="submit" class="link-button">
            <i class="fas fa-folder"><p>Veřejný obsah</p></i>
            </button></form></div>');
                foreach ($namefolder as $name) {
                    $html .= ('<div class="folder"><form method="POST" action="index.php" class="inline">
            <input type="hidden" name="file_name" value="' . $name . '">
            <button type="submit" class="link-button">
            <i class="fas fa-folder"><p>' . ucfirst($name) . '</p></i>
            </button>
            </form></div>');
                }
            } else if ($admin_state == "ON") {
                $i = 0;
                $html .= ('<div class="public_folder"><form method="POST" action="index.php" class="inline">
            <input type="hidden" name="file_name" value="public_folder">
            <button type="submit" class="link-button">
            <i class="fas fa-folder"><p>Veřejný obsah</p></i>
            </button></form></div>');
                foreach ($namefolder as $name) {
                    $html .= ('<div class="folder"><form method="POST" action="index.php" class="inline">
                <input type="hidden" name="file_name" value="' . $name . '"/>
                <button type="submit" class="link-button">
                <i class="fas fa-folder"><p>' . ucfirst($name) . '</p></i>
                </button>
                </form>
                <div class="icons"><form><input type="hidden" name="value"/><button id="submit" class="submit" onclick="deleteFile(' . $idfolder[$i] . ')" type="button"><i class="fas fa-trash-alt"></i></button></form></div></div>');
                    $i++;
                }
            }
        }
        return $html;
    }
}
