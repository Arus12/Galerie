<?php
class load
{
    public $y;
    public function __construct($method)
    {
        $this->y = $_POST;
        if ($method == "files") {
            $this->files();
        } else if ($method == "images") {
            $this->images();
        }
    }
    public function files()
    {
        $json = file_get_contents("./jsons/images.json");
        $j_data = json_decode($json);
        $file = [];

        echo ('<div class="buttons"><a href="./pages/login.php"><button class="button">Přihlášení</button><a href="./pages/upload.php"><button class="button">Nahrát</button></a></div><div class="folders">');

        foreach ($j_data->images as $image) {
            foreach ($image as $data) {
                if ($image->file == $data and !in_array($image->file, $file)) {
                    array_push($file, $data);
                    echo ('<div class="folder"><form method="post" action="./pages/files.php" class="inline">
                <input type="hidden" name="' . $data . '" value="' . $data . '">
                <button type="submit" name="' . $data . '" value="' . $data . '" class="link-button">
                <i class="fas fa-folder"></i><p>' . $data . '</p>
                </button>
                </form></div>');
                }
            }
        }
        echo ('</div></div></main><footer><p>' . date("d.m.Y") . '</p><h2>Přehled</h2><author>Autor: Jan Černý </author></footer>');
    }


    public function images()
    {
        foreach ($this->y as $get) {
            $name_file = $get;
        }
        $json = file_get_contents("../jsons/images.json");
        $j_data = json_decode($json);
        $name = [];
        $date = [];
        $type = [];
        $file = [];

        echo ('<div class="buttons"><a href="../index.php"><i class="fas fa-arrow-circle-left"></i></a><a href="../pages/upload.php"><button class="button">Nahrát</button></a></div><div class="images">');

        foreach ($j_data->images as $image) {
            foreach ($image as $data) {
                if ($image->file == $data and $image->file == $name_file) {
                    array_push($file, $data);
                    echo ('<div class="image"><a href="../index.php"><img src=../imgs/' . $data . '/');
                } else if ($image->name == $data and $image->file == $name_file) {
                    array_push($name, $data);
                    echo ($data);
                } else if ($image->type == $data and $image->file == $name_file) {
                    array_push($type, $data);
                    echo ('.' . $data);
                } else if ($image->date == $data and $image->file == $name_file) {
                    array_push($date, $data);
                    echo ('></a><div class="icons"><a href="index.php"><i class="fas fa-trash-alt"></i></a><a href="index.php"><i class="fas fa-edit"></i></a></div><p>' . $data . '</p></div>');
                }
            }
        }
        echo ('</div></main><footer><p>' . date("d.m.Y") . '</p><h2>' . $name_file . '</h2><author>Autor: Jan Černý </author></footer>');
    }
}
