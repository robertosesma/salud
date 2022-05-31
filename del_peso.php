<?php
session_start();
include 'func_aux.php';
if (isset($_GET['fecha'])) {
    $conn = connect();
    $fecha = clear_input($_GET["fecha"]);

    // borrar el peso
    $stmt = $conn -> prepare("DELETE FROM Peso WHERE fecha=?");
    $stmt->bind_param('s',$fecha);
    $stmt->execute();

    $conn->close();
    header("Refresh:0; url=listado_peso.php");
}
exit();
?>
