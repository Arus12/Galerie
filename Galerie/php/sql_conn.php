<?php
class sql_connect{
public function connection(){
    parse_ini_file("../utils/config.ini");
    $conn = mysqli_connect($this -> foreach(0), $this -> foreach(1), $this -> foreach(2), $this -> foreach(3));
    if(!$conn){
        return("Connection failed: " . mysqli_connect_error());
    }else{
        return("Connection success");
    }
}

public function foreach($i){
    $raw_data = parse_ini_file("../utils/config.ini");
    $o = 0;
    foreach($raw_data as $value){
        if($i == $o){
        return $value;
    }elseif($i == $o){
        return $value;
    }elseif($i == $o){
        return $value;
    }elseif($i == $o){
        return $value;
    }
    $o++;
}
}
}
?>