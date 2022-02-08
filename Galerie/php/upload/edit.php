<?php
require_once("../form/login.php");
require_once("../form/states.php");
require_once("../form/sql_conn.php");
$obj = new edit();
/* Script, který zajištuje editování obrázku */
class edit{
    /* Konstruktor, který zjistí, zdali něco v POSTU přišlo
    * Když ne, tak se automaticky formulář přepne na nový obrázek
    * Když ano, tak se zavolá funkce edit_image
    */
    public function __construct(){
        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();
        }
        $obj = new login("", "", "", "");
        new states("");
        if(isset($_REQUEST["data"])){
            $this->edit_image($obj);
        }else{
        if($obj->get_new_edit() == "new_image"){
            $obj->set_new_edit("edit_image");
            $filename = $_REQUEST['name'];
            $IDuser = $_REQUEST['iduser'];
            $this->get_edit_image($filename, $IDuser);
        }else{
            $obj->set_upload_state("error");
            header("Location: ../../pages/upload.php");

        }
    }
    }

    /* Funkce, která z databáze zjistí dané informace o obrázku
    * Zjistí také z databáze seznam složek, ve kterých se daný obrázek nachází.
    * Nakonec data a seznam složek nastaví (set)
    */
    public function get_edit_image($filename, $IDuser){
        $obj = new sql_conn();
        $conn = $obj->connection("insert_img");
        $state = $obj->validation("insert_img");
        $i = 0;
        $sql = "SELECT `idfolder` FROM `folder` WHERE `users_idusers` = '$IDuser'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $idfolders[$i] = $row["idfolder"];
                $i++;
            }
        }
        $i = 0;
        foreach($idfolders as $idfolder){
        $sql = "SELECT `image_idimage` FROM `folder_image` WHERE `folder_idfolder` = $idfolder";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $idimages[$i] = $row["image_idimage"];
                $i++;
            }
        }
    }
        foreach($idimages as $idimage){
        $sql = "SELECT `idimage`, `date`, `state` FROM `image` WHERE `idimage` = $idimage AND `name` LIKE '$filename'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $idimagen = intval($row["idimage"]);
                $dateimage = $row["date"];
                $stateimage = $row["state"];
            }
        }
    }
        $sql = "SELECT `folder_idfolder` FROM `folder_image` WHERE `image_idimage` = $idimagen";
        $i = 0;
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $idfolders2[$i] = $row["folder_idfolder"];
                $i++;
            }
        }
        $i = 0;
        foreach($idfolders2 as $idfolder2){
        $sql = "SELECT `name` FROM `folder` WHERE `idfolder` = $idfolder2";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $foldersname[$i] = $row["name"];
                $i++;
            }
        }
    }
        $data = ["name" => $filename, "date" => $dateimage, "state" => $stateimage, "iduser" => $IDuser];
        $_SESSION["data"] = $data;
        $i = 0;
        foreach($foldersname as $foldername){
            $foldersnames[$i] = $foldername;
            $i++;
          }
          $_SESSION["folders"] = $foldersnames;
        $obj = new login("", "", "", "");
        $obj->set_data($data);
        $obj->set_folders($foldersnames);
        header("Location: ../../pages/upload.php");
    }

    /* Funkce, která získá informace o změně informací o obrázku
    * Pomocí těchto informací pak změní informace v databázi o obrázku a uživatele php vrátí zpět na hlavní stránku.
    * Když cokoliv chybí, nebo jsou nějaké informace doplněny jakkoliv chybně, v databázi se nic nemění. 
    * Uživatele php vrátí na stránku s edit formuláře s chybovou hláškou.
    * Informace zase do formu předpřipaví.
    */
    public function edit_image($obj){
        if(!empty($_REQUEST['nameImage']) and !empty($_REQUEST['dateImage']) and !empty($_REQUEST['stateImage']) and !empty($_REQUEST['folderImage'])){
            $imagename = $_REQUEST['nameImage'];
            $imagedate = $_REQUEST['dateImage'];
            $imagestate = $_REQUEST['stateImage'];
            $folders = $_REQUEST['folderImage'];
            $lastimagename = $_REQUEST["lastnamefile"];
            $iduser = $_REQUEST["iduser"];

            $sqlc = new sql_conn();
            $conn = $sqlc->connection("insert_img");
            $state = $sqlc->validation("insert_img");

            $i = 0;
            $sql = "SELECT `idfolder` FROM `folder` WHERE `users_idusers` = '$iduser'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $idfolders[$i] = $row["idfolder"];
                    $i++;
                }
            }

            $sql = "SELECT `e_mail` FROM `user` WHERE `iduser` = $iduser";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $email = $row["e_mail"];
                }
            }

            $i = 0;
            foreach($idfolders as $idfolder){
            $sql = "SELECT `image_idimage` FROM `folder_image` WHERE `folder_idfolder` = $idfolder";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $idimages[$i] = $row["image_idimage"];
                    $i++;
                }
            }
        }
            foreach($idimages as $idimage){
            $sql = "SELECT `idimage`, `type` FROM `image` WHERE `idimage` = $idimage AND `name` LIKE '$lastimagename'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $idimagen = $row["idimage"];
                    $typeimage = $row["type"];
                }
            }
        }
            $sql = "UPDATE `image` SET `name` = '$imagename', `date` = '$imagedate', `state` = '$imagestate' WHERE `image`.`idimage` = $idimagen";
            $result = $conn->query($sql);
            $sql = "DELETE FROM `folder_image` WHERE `image_idimage`= $idimagen";
            $result = $conn->query($sql);
            $i = 0;
            foreach($folders as $folder){
                $sql = "SELECT `idfolder` FROM `folder` WHERE `name` LIKE '$folder' AND `users_idusers` = '$iduser'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $idfolders2[$i] = $row["idfolder"];
                        $i++;
                    }
                }
            }
            
            $i = 0;
            $sql = "SELECT `folder_idfolder` FROM `folder_image` WHERE `image_idimage` = $idimagen";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $idfoldersnow[$i] = $row["folder_idfolder"];
                        $i++;
                    }
                }
            foreach($idfolders2 as $idfolder){
            $sql = "INSERT INTO `folder_image` (`image_idimage`, `folder_idfolder`) VALUES ('$idimagen', '$idfolder')";
            $result = $conn->query($sql);
            }
            rename("../../imgs/$email/$lastimagename.$typeimage", "../../imgs/$email/$imagename.$typeimage");
            if($result){
                $obj->set_upload_state("succes");
                header("Location: ../../index.php");
            }
        }else{
            $obj->set_new_edit("edit_image");
            $obj->set_upload_state("error");
            $this->get_edit_image($_REQUEST["lastnamefile"], $_REQUEST["iduser"]);
        }
    }
}
