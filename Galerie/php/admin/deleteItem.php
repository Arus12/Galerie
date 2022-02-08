<?php
$obj = new deleteItem();
/* Script, který maže složku, nebo obrázek */
class deleteItem
{
    /* Konstruktorovi příjde od Javascriptu informace. 
    Tyto informace předá funkci, která poté zjistí, zdali se jedná o obrázek, nebo složku*/
    public function __construct()
    {
        $folder_image = $_POST['folder/image'];
        $item = $_POST['item'];
        if (isset($_POST["folderid"])) {
            $folderid = $_POST['folderid'];
            return $this->file_or_image($folder_image, $item, $folderid);
        } else {
            return $this->file_or_image($folder_image, $item);
        }
    }

    /* Funkce, která podle informací získané od konstruktoru, spustí správnou funkci */

    public function file_or_image(string $folder_image, string $item, $folderid = NULL)
    {
        if ($folder_image == "FOLDER") {
            return $this->delete_folder($item);
        } else if ($folder_image == "IMAGE") {
            return $this->delete_image($item, $folderid);
        }
    }

    /* Funkce, která smaže z databáze informace o složce */

    public function delete_folder(string $item)
    {
        require_once("../form/login.php");
        $states = new login();
        $email = $states->get_email();
        require_once("../form/sql_conn.php");
        $connect = new sql_conn();
        $conn = $connect->connection("delete");
        $state = $connect->islogged("delete");
        if ($state) {
            $sql = "SELECT `iduser` FROM `user` WHERE `e_mail` LIKE '$email'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $id_user = $row["iduser"];
                }
            }
            $sql = "SELECT * FROM `folder` WHERE `idfolder` = $item AND `users_idusers` = $id_user";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $id_images[] = array();
                $i = 0;
                $sql = "SELECT `image_idimage` FROM `folder_image` WHERE `folder_idfolder` = $item";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $id_images[] = $row["image_idimage"];
                        $i++;
                    }
                    foreach ($id_images as $id_image) {
                        $sql = "DELETE FROM `folder_image` WHERE `image_idimage`= $id_image AND `folder_idfolder` = $item";
                        $result = $conn->query($sql);
                    }
                    foreach ($id_images as $id_image) {
                        $sql = "SELECT `image_idimage` FROM `folder_image` WHERE `image_idimage` = $id_image";
                        $result = $conn->query($sql);
                        if ($result->num_rows <= 0) {
                            $sql = "SELECT `name`, `type` FROM `image` WHERE `idimage` = $id_image";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $image_name = $row["name"];
                                    $image_type = $row["type"];
                                }
                            }
                            unlink("../../imgs/$email/$image_name" . "$image_type");
                            $sql = "DELETE FROM `image` WHERE `idimage` = $id_image";
                            $result = $conn->query($sql);
                        }
                    }
                }
                $sql = "DELETE FROM `folder` WHERE `idfolder` = $item";
                $result = $conn->query($sql);
                if ($result) {
                    $states->set_upload_state("succes");
                    return TRUE;
                } else {
                    return FALSE;
                }
            }
        }
    }

    /* Funkce, která smaže informace z databáze (popřípadě i z reálné složky) o obrázku */

    public function delete_image(string $item, string $folderid)
    {
        require_once("../form/login.php");
        $states = new login();
        $email = $states->get_email();
        require_once("../form/sql_conn.php");
        $connect = new sql_conn();
        $conn = $connect->connection("delete");
        $state = $connect->islogged("delete");
        if ($state) {
            $sql = "SELECT `iduser` FROM `user` WHERE `e_mail` LIKE '$email'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $id_user = $row["iduser"];
                }
            }
            $sql = "SELECT * FROM `folder` WHERE `idfolder` = $folderid AND `users_idusers` = $id_user";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $sql = "DELETE FROM `folder_image` WHERE `image_idimage` = $item AND `folder_idfolder` = $folderid";
                $result = $conn->query($sql);
                if ($result) {
                    $sql = "SELECT `image_idimage` FROM `folder_image` WHERE `image_idimage`= $item";
                    $result = $conn->query($sql);
                    if ($result->num_rows <= 0) {
                        $sql = "SELECT `name`, `type` FROM `image` WHERE `idimage` = $item";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $image_name = $row["name"];
                                $image_type = $row["type"];
                            }
                        }
                        unlink("../../imgs/$email/$image_name" . "$image_type");
                        $sql = "DELETE FROM `image` WHERE `idimage` = $item";
                        $result = $conn->query($sql);
                        $conn->close();
                    }
                    $states->set_upload_state("succes");
                    return TRUE;
                } else {
                    return FALSE;
                }
            }
        } else {
            echo ("ERROR");
            $conn->close();
        }
    }
}
