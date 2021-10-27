<?php
$json = file_get_contents("./jsons/images.json");
$j_data = json_decode($json);
$name =[];
$date =[];
$type =[];

    foreach($j_data -> images as $image){
        foreach ($image as $data){
            if($image -> name == $data){
                array_push($name, $data);
                echo('<div class="image"><a href="home.php"><img src=imgs/'. $data);
            }else if($image -> type == $data){
                array_push($type, $data);
                echo('.' . $data);
            }
            else if($image -> date == $data){
                array_push($date, $data);
                echo('></a><div class="icons"><a href="home.php"><i class="fas fa-trash-alt"></i></a><a href="home.php"><i class="fas fa-edit"></i></a></div><p>' . $data . '</p></div>');
        }
    }
}
?>