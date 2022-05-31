<?php
function connect(){
    require '../dbconfig.php';

    $con = mysqli_connect($dbconfig['server'],$dbconfig['username'],$dbconfig['password'],$dbconfig['db']);
    if(!$con){
        die("Fallo al conectar con la base de datos");
    }
    $con->query("SET NAMES 'utf8'");
    $con->query("SET CHARACTER SET utf8");
    $con->query("SET SESSION collation_connection = 'utf8_unicode_ci'");

    return $con;
}

function clear_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>
