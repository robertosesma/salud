<?php
session_start();
include 'func_aux.php';
if (isset($_GET['dia']) && isset($_GET['hora'])) {
    $conn = connect();
    $dia = clear_input($_GET["dia"]);
    $hora = clear_input($_GET["hora"]);

    // borrar el registro de PA (todas las medidas del día y la hora indicadas)
    $stmt = $conn -> prepare("DELETE FROM data WHERE dia=? AND hora=?");
    $stmt->bind_param('ss',$dia,$hora);
    $stmt->execute();

    $conn->close();
    header("Refresh:0; url=listado_pa.php");
}
exit();
?>
