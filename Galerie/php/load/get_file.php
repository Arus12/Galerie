<?php
include_once("php/form/sql_conn.php");
include_once("php/form/states.php");
/* Script, který vypíše všechny obrázky ve složce uživatele */
class get_file extends sql_conn
{
    /* Konstruktor spustí funkci get a předá přijaté hodnoty */
    public function __construct(string $REQUEST, string $admin_state)
    {
        $this->get($REQUEST, $admin_state);
    }

    /* Funkce, která postupně spouští potřebné funkce a předává potřebná data*/
    public function get(string $REQUEST, string $admin_state)
    {
        if ($REQUEST == '') {
            die("Error");
        } else {
            if (session_status() != PHP_SESSION_ACTIVE) {
                session_start();
            }
            $foldername = $REQUEST;
            $_SESSION["foldername"] = $foldername;
            $conn = $this->connection("load_images");
            $state = $this->validation("load_images");
        }
        if ($state === TRUE) {
            if ($foldername == "public_folder") {
                $_SESSION["foldername"] = "Veřejný obsah";
                return $this->public_folder($conn);
            } else {
                $obj = new login("", "", "", "");
                $email = $obj->get_email();
                return $this->get_sql_images($conn, $foldername, $email, $admin_state);
            }
        } else {
            echo ("Connection failed");
        }
    }

    /* Funkce, která volá funkce, pro získání potřebných informací z databáze.
    * Poté funkce zjistí, zdali se v dané složce nachází nějaký obrázek. Když ne, tak vrátí html s větou "Tato složka je prázdná".
    * Pokud se v dané složce nachází alespoň jeden obrázek, pak funkce zavolá funkci display_images, která vrátí html.¨
    * Nakonec funkce vrátí přijaté html
    */
    public function get_sql_images($conn, string $foldername, string $email, string $admin_state)
    {
        $iduser = $this->get_iduser($conn, $email);
        $idfolder = $this->get_idfolder($conn, $foldername, $iduser);
        $idimages = $this->get_images($conn, $idfolder);
        if ($idimages) {
            $html = $this->display_images($conn, $email, $idimages, $idfolder, $admin_state, $iduser);
            return $html;
        }
        return ('<div class="empty"><p>Tato složka je prázdná :(</p></div>');
    }

    /* Funkce, která vrací poskládané html
    * Funkce podle aktuálního stavu administrace, a přijatých informací sestaví html, které poté vrátí.
    */
    public function display_images(object $conn, string $email, array $idimages, $idfolder, string $admin_state, $iduser)
    {
        $html = "";
        if ($admin_state == "ON") {
            foreach ($idimages as $idimage) {
                $image_name = $this->get_name_image($conn, $idimage);
                $image_type = $this->get_type_image($conn, $idimage);
                $image_date = $this->get_date_image($conn, $idimage);
                $dates = explode("-", $image_date);
                $html .= ('<div class="image"><p>' . ucfirst($image_name) . '</p>
               <img src="imgs/' . $email . '/' . $image_name  . $image_type . '" alt="' . ucfirst($image_name) . '"style="cursor: pointer;">
               <form action="php/upload/edit.php" method="POST">
               <input type="text" name="name" value="' . $image_name . '">
               <input type="text" name="iduser" value="' . $iduser . '">
               <button type="submit" class= "edit" value="Submit"></button>
               </form>
                <div class="icons"><form><input type="hidden" name="value"/><button id="submit" class="submit" onclick="deleteImage(' . $idimage . ',' . $idfolder . ')" type="button">
                <i class="fas fa-trash-alt"></i></button></form>
                <form action="php/upload/edit.php" method="POST">
                <input type="text" name="name" value="' . $image_name . '">
                <input type="text" name="iduser" value="' . $iduser . '">
                <button type="submit" class="submit" type="button">
                <i class="fas fa-edit"></i></button></form>
                <p><div class = "date_admin"><p>');
                for ($i = 2; $i != -1; $i--) {
                    if ($i == 0) {
                        $html .= $dates[$i];
                    } else {
                        $html .= $dates[$i] . ".";
                    }
                }
                $html .= ('</p></div></p></div></div>');
            }
            return $html;
        } else if ($admin_state == "OFF") {
            $y = 0;
            foreach ($idimages as $idimage) {
                $image_name = $this->get_name_image($conn, $idimage);
                $image_type = $this->get_type_image($conn, $idimage);
                $image_date = $this->get_date_image($conn, $idimage);
                $dates = explode("-", $image_date);
                $html .= ('<div class="image"><p>' . ucfirst($image_name) . '</p>
                <img id="myImg' . $y . '"  src="imgs/' . $email . '/' . $image_name  . $image_type . '" alt="' . ucfirst($image_name) . '"style="cursor: pointer;">
                <div class = "date"><p>');

                for ($i = 2; $i != -1; $i--) {
                    if ($i == 0) {
                        $html .= $dates[$i];
                    } else {
                        $html .= $dates[$i] . ".";
                    }
                }

                $html .= ('</p></div><div class = "icon"><button onclick="download');
                $html .= ("('") . ($email) . ("','") . ($image_name . $image_type) . ("')") . ("") . ('"><i class="fas fa-download"></i></button></div></div>');
                $y++;
            }
            $html .= ('<div id="myModal" class="modal">
            <span class="close">&times;</span>
            <img class="modal-content" id="img01">
            <div id="caption"></div>
            </div>');
            return $html;
        }
    }
    /* Funkce, která z databáze zjistí id uživatele */
    public function get_iduser($conn, string $email)
    {
        $sql = "SELECT iduser FROM user WHERE e_mail='$email'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                return intval($row["iduser"]);
            }
        } else {
            echo ("Connection failed");
        }
    }

    /* Funkce, která z databáze zjistí id složky uživatele */
    public function get_idfolder($conn, string $foldername, int $iduser)
    {
        $sql = "SELECT * FROM `folder` WHERE `users_idusers` = $iduser AND `name` LIKE '$foldername'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                return intval($row["idfolder"]);
            }
        } else {
            echo ("Connection failed");
        }
    }

    /* Funkce, která z databáze zjistí id obrázku/ů uživatele */
    public function get_images($conn, int $idfolder)
    {
        $images[] = null;
        $i = 0;
        $sql = "SELECT image_idimage FROM `folder_image` WHERE `folder_idfolder` = $idfolder";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $images[$i] = intval($row["image_idimage"]);
                $i++;
            }
            return $images;
        } else {
            return (FALSE);
        }
    }

    /* Funkce, která z databáze zjistí název obrázku uživatele */
    public function get_name_image($conn, int $idimage)
    {
        $sql = "SELECT name FROM `image` WHERE `idimage` = $idimage";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                return $row["name"];
            }
        } else {
            echo ("Connection failed");
        }
    }

    /* Funkce, která z databáze zjistí typ obrázku uživatele */
    public function get_type_image($conn, int $idimage)
    {
        $sql = "SELECT type FROM `image` WHERE `idimage` = $idimage";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                return $row["type"];
            }
        } else {
            echo ("Connection failed");
        }
    }

    /* Funkce, která z databáze zjistí datum obrázku uživatele */
    public function get_date_image($conn, int $idimage)
    {
        $sql = "SELECT date FROM `image` WHERE `idimage` = $idimage";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                return $row["date"];
            }
        } else {
            echo ("Connection failed");
        }
    }

    /* Funkce, která volá funkce, pro získání informací o veřejné složce. 
    * Získané informace pak pošle funkci display_images_public, která vrátí html.
    * Přijaté html pak funkce vrátí. Pokud se ovšem ve veřejné složce nenachází ani jeden obrázek, pak funkce vrátí html s větou "Tato složka je prázdná".
    */
    public function public_folder($conn)
    {
        $allidimages = $this->find_all_idimages($conn);
        if ($allidimages) {
            $idimages = $this->find_idimages($conn, $allidimages);
            $html = $this->display_images_public($conn, $this->find_emails($conn, $this->find_idusers($conn, $this->find_idfolders($conn, $this->find_idimages($conn, $allidimages)))), $idimages);
            return $html;
        } else {
            return ('<div class="empty"><p>Tato složka je prázdná :(</p></div>');
        }
    }

    /* Funkce, která vrací poskládané html pro veřejnou složku
    * Funkce si postupně volá potřebné funkce k získání potřebných informací.
    * Tyto informace pak použije k sestavení html, které poté vrací.
    */
    public function display_images_public(object $conn, $emails, $idimages)
    {
        $html = "";
        $i = 0;
        foreach ($emails as $email) {
            $image_name = $this->get_name_image($conn, $idimages[$i]);
            $image_type = $this->get_type_image($conn, $idimages[$i]);
            $image_date = $this->get_date_image($conn, $idimages[$i]);
            $html .= ('<div class="image"><p>' . ucfirst($image_name) . '</p>
            <img id="myImg' . $i . '" src="imgs/' . $email . '/' . $image_name  . $image_type . '" alt="' . ucfirst($image_name) . '" style="cursor: pointer;">
            <p>' . $image_date . '</p></div>');
            $i++;
        }
        $html .= ('<div id="myModal" class="modal">
        <span class="close">&times;</span>
        <img class="modal-content" id="img01">
        <div id="caption"></div>
        </div>');
        return $html;
    }

    /* Funkce, která z databáze zjistí všechny id obrázků */
    public function find_all_idimages(object $conn)
    {
        $i = 0;
        $sql = "SELECT `image_idimage` FROM `folder_image`";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $idimages[$i] = intval($row["image_idimage"]);
                $i++;
            }
            return $idimages;
        } else {
            return FALSE;
        }
    }

    /* Funkce, která z databáze zjistí ve které složce se daný obrázek nachází
    * Poté vrací id složek.
    */
    public function find_idfolders(object $conn, array $idimages)
    {
        $i = 0;
        $idfolders = array();
        foreach ($idimages as $idimage) {
            $sql = "SELECT folder_idfolder FROM `folder_image` WHERE `image_idimage` = $idimage LIMIT 1";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $y = 0;
                    for ($x = 0; $x < count($idimages); $x++) {
                        if (empty($idfolders[$x])) {
                        } else {
                            if ($idfolders[$x] == $idimage) {
                                $y++;
                            } else {
                            }
                        }
                    }
                    if ($y == 0) {
                        $idfolders[$i] = intval($row["folder_idfolder"]);
                        $i++;
                    } else {
                    }
                }
            } else {
            }
        }
        return $idfolders;
    }

    /* Funkce, která z databáze zjistí id uživatelů složek*/
    public function find_idusers(object $conn, array $idfolders)
    {
        $i = 0;
        foreach ($idfolders as $idfolder) {
            $sql = "SELECT users_idusers FROM `folder` WHERE `idfolder` = $idfolder";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $idusers[$i] = intval($row["users_idusers"]);
                    $i++;
                }
            } else {
                echo ("Connection failed");
            }
        }
        return $idusers;
    }

    /* Funkce, která z databáze zjistí emaily uživatelů */
    public function find_emails(object $conn, array $idusers)
    {
        $i = 0;
        foreach ($idusers as $iduser) {
            $sql = "SELECT e_mail FROM `user` WHERE `iduser` = $iduser";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $emails[$i] = $row["e_mail"];
                    $i++;
                }
            } else {
                echo ("Connection failed");
            }
        }
        return $emails;
    }

    /* Funkce, která z databáze zjistí, které složky jsou veřejné */
    public function find_idimages(object $conn, array $allidimages)
    {
        for ($x = 0; $x > count($allidimages); $x++) {
        }
        $idimages = array();
        $i = 0;
        foreach ($allidimages as $allidimage) {
            $sql = "SELECT `state` FROM `image` WHERE `idimage` = $allidimage";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    if ($row["state"] == "public") {
                        $y = 0;
                        for ($x = 0; $x < count($allidimages); $x++) {
                            if (empty($idimages[$x])) {
                            } else {
                                if ($idimages[$x] == $allidimage) {
                                    $y++;
                                } else {
                                }
                            }
                        }
                        if ($y == 0) {
                            $idimages[$i] = intval($allidimage);
                            $i++;
                        } else {
                        }
                    } else {
                    }
                }
            } else {
                echo ("Connection failed");
            }
        }
        return $idimages;
    }
    public function get_filename()
    {
        return $_SESSION["foldername"];
    }
}
