<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Nuevo Peso</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
</head>

<body>
<?php
include 'func_aux.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = connect();
    $fecha = clear_input($_POST["fecha"]);
    $peso = clear_input($_POST["peso"]);
    $peso = str_replace(",",".",$peso);
    // añadir el peso
    $stmt = $conn -> prepare("INSERT INTO Peso (fecha,peso) VALUES (?,?)");
    $stmt->bind_param('sd',$fecha,$peso);
    $stmt->execute();
    $conn->close();
    header("Refresh:0; url=listado_peso.php");
}
?>

<div class="container">
    <div class="page-header">
        <h2>Nuevo Peso</h2>
        <a class="btn btn-link" href="listado_peso.php">Atrás</a>
    </div>
</div>

<div class="container p-3 my-3 border">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <div class="form-group">
            <label for="fecha">Fecha:</label>
            <input type="text" class="form-control" name="fecha" value="<?php echo $date = date("Y-m-d")?>">
        </div>
        <div class="form-group">
            <label for="peso">Peso (kg):</label>
            <input type="text" class="form-control" name="peso">
        </div>
        <button type="submit" class="btn btn-primary">Enviar</button>
   </form>
</div>

</body>
</html>
