<?php
require_once("../form/states.php");
require_once("../form/sql_conn.php");
require_once("../form/login.php");
$obj = new selectFile();
/* Script, který uloží obrázek */
class selectFile
{
    private $ImageType;
    /* Konstruktor, který nastaví state na "upload" a podle přijatých dat zajistí, aby všechna data byla v pořádku
    * Pokud nějaká data v pořádku nejsou, nebo nějaká data nejsou vyplněna, pak php script vrátí uživatele do formuláře
      na new image a vypíše uživateli chybovou hlášku. Také uživateli předepíše informace, které uživatel správně zadal. 
    * Pokud jsou všechna data v pořádku, pak funkce zavolá funkce getImg, saveImg a poté connect_db. 
    */
    public function __construct()
    {
        new states("upload");
        $obj = new login("", "", "", "");
        $email = $obj->get_email();
        $ImageName = $_REQUEST['nameImage'];
        $ImageDate = $_REQUEST['dateImage'];
        $ImageState = $_REQUEST['stateImage'];
        if (isset($_REQUEST['folderImage'])) {
            $i = 0;
            foreach ($_POST['folderImage'] as  $ImageFolder) {
                $ImageFolders[$i] = $ImageFolder;
                $i++;
            }
        }
        if (isset($_REQUEST['nameImage']) and isset($_REQUEST['dateImage']) and isset($_REQUEST['stateImage']) and isset($_REQUEST['folderImage'])) {
            if (isset($_FILES['ImageDrag_And_Drop'])) {
                $state = $this->getImg($ImageName, $ImageDate, $ImageState, $ImageFolders);
                if ($state === TRUE) {
                    $state = $this->saveImg($ImageName, $_FILES["ImageDrag_And_Drop"]["name"], $email,);
                    if ($state === TRUE) {
                        $this->connect_db($ImageName, $ImageDate, $ImageState, $ImageFolders, $email, $obj);
                    } else {
                        $data = ["name" => $ImageName, "date" => $ImageDate, "state" => $ImageState];
                        $obj->set_data($data);
                        $obj->set_folders($ImageFolders);
                        $obj->set_new_edit("new_image");
                        $obj->set_upload_state("error");
                        header("Location: ../../pages/upload.php");
                    }
                } else {
                }
            } else {
                $data = ["name" => $ImageName, "date" => $ImageDate, "state" => $ImageState];
                $obj->set_data($data);
                $obj->set_folders($ImageFolders);
                $obj->set_new_edit("new_image");
                $obj->set_upload_state("error");
                header("Location: ../../pages/upload.php");
            }
        } else {
            $obj->set_upload_state("error");
            header("Location: ../../pages/upload.php");
        }
    }

    /* Funkce, která získá od HTML POST obrázek, zkontroluje, zdali to opravdu obrázek je a zdali nepřekračuje velikostní limit a
    pokud je vše v pořádku, uloží obrázek do dočasné složby tmp*/
    public function getImg($ImageName, $ImageDate, $ImageState, $ImageFolders)
    {
        $obj = new login("", "", "", "");
        $data = ["name" => $ImageName, "date" => $ImageDate, "state" => $ImageState];
        $states = new states("upload");
        $target_dir = "../../imgs/tmp/";
        $target_file = $target_dir . basename($_FILES["ImageDrag_And_Drop"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["ImageDrag_And_Drop"]["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                $obj->set_data($data);
                $obj->set_folders($ImageFolders);
                $obj->set_new_edit("new_image");
                $obj->set_upload_state("error");
                header("Location: ../../pages/upload.php");
            }
        }
        if (file_exists($target_file)) {
            $obj->set_data($data);
            $obj->set_folders($ImageFolders);
            $obj->set_new_edit("new_image");
            $obj->set_upload_state("error");
            header("Location: ../../pages/upload.php");
        }

        if ($_FILES["ImageDrag_And_Drop"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $obj->set_data($data);
            $obj->set_folders($ImageFolders);
            $obj->set_new_edit("new_image");
            $obj->set_upload_state("error");
            header("Location: ../../pages/upload.php");
        }
        if (
            $imageFileType != "jpg" && $imageFileType != "png"
        ) {
            $obj->set_upload_state("error");
            header("Location: ../../pages/upload.php");
        }

        if ($uploadOk != 0) {
            if (move_uploaded_file($_FILES["ImageDrag_And_Drop"]["tmp_name"], $target_file)) {
                return TRUE;
            }
        }
    }

    /* Funkce, která obrázku, pokud již neexistuje, upraví velikost a uloží do složky uživatele */
    public function saveImg($ImageName, $ActualImageName, $email)
    {
        $ImageType = strtolower(pathinfo("../../imgs/tmp/$ActualImageName", PATHINFO_EXTENSION));
        $this->ImageType = "." . "$ImageType";
        if (file_exists("../../imgs/$email/$ImageName.$ImageType")) {
            unlink("../../imgs/tmp/$ActualImageName");
            header("Location: ../../pages/upload.php");
        } else {
            rename("../../imgs/tmp/$ActualImageName", "../../imgs/$email/$ImageName.$ImageType");
            $filename = "../../imgs/$email/$ImageName.$ImageType";
            $width = 300;
            $height = 200;
            list($width_orig, $height_orig) = getimagesize($filename);
            $ratio_orig = $width_orig / $height_orig;
            if ($width / $height > $ratio_orig) {
                $width = $height * $ratio_orig;
            } else {
                $height = $width / $ratio_orig;
            }
            $image_p = imagecreatetruecolor($width, $height);
            $image = imagecreatefromjpeg($filename);
            imagecopyresampled(
                $image_p,
                $image,
                0,
                0,
                0,
                0,
                $width,
                $height,
                $width_orig,
                $height_orig
            );
            imagejpeg($image_p, "../../imgs/$email/$ImageName.$ImageType");
            return TRUE;
        }
    }

    /* Funkce, která uloží do databáze informace o obrázku */
    public function connect_db($ImageName, $ImageDate, $ImageState, $ImageFolders, $email, $obj)
    {
        $sqlc = new sql_conn();
        $conn = $sqlc->connection("insert_img");
        $sqlc->validation("insert_img");
        $sql = "SELECT iduser FROM user WHERE e_mail='$email'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $iduser = $row["iduser"];
            }
        }
        $sql = "INSERT INTO `image` (`idimage`, `name`, `date`, `type`, `state`, `descriptions`) VALUES (NULL, '$ImageName', '$ImageDate', '$this->ImageType', '$ImageState', NULL)";
        $result = $conn->query($sql);
        if ($result) {
            $sql = "SELECT `idimage` FROM `image` WHERE `name` LIKE '$ImageName'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $idimage = $row["idimage"];
                }
            }
            $i = 0;
            foreach ($ImageFolders as $ImageFolder) {
                $sql = "SELECT `idfolder` FROM `folder` WHERE `users_idusers` = $iduser AND `name` LIKE '$ImageFolder'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $idfolders[$i] = $row["idfolder"];
                        $i++;
                    }
                }
            }
            foreach ($idfolders as $idfolder) {
                $sql = "INSERT INTO `folder_image` (`image_idimage`, `folder_idfolder`) VALUES ('$idimage', '$idfolder')";
                $result = $conn->query($sql);
            }
            if ($result) {
                $obj->set_upload_state("succes");
                header("Location: ../../index.php");
            }
        }
    }
}
