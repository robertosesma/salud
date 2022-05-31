<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Listado Peso</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
</head>

<body>
<?php
include 'func_aux.php';
$conn = connect();

// obtener los datos
$stmt = $conn -> prepare("SELECT * FROM listado_peso");
$stmt->execute();
$d = $stmt->get_result();
$ntot = $d->num_rows;
?>

<div class="container">
    <div class="container p-3 my-3 border">
        <h3>Peso</h3>
        <?php echo "<h6>Registros ".$ntot."</h6>";?>
        <a class="btn btn-link" href="new_peso.php">Nuevo</a>
        <a class="btn btn-link" href="listado_pa.php">Presión arterial</a>
    </div>
</div>

<div class="container">
    <form action="analiza_peso.php" method="post">
        <div class="input-group mt-2 mb-3">
            <input type="text" class="form-control" name="ini" placeholder="Fecha inicio">
            <input type="text" class="form-control" name="fin" placeholder="Fecha fin">
            <input type="text" class="form-control" name="year" placeholder="Año">
            <div class="input-group-append">
                <button class="btn btn-outline-primary" name="analiza" type="submit">Analizar</button>
            </div>
        </div>
    </form>
</div>

<div class="container">
    <table cellpadding="0" cellspacing="0" border="0" class="table table-hover table-bordered">
        <thead class="thead-light">
            <tr>
                <th>Fecha</th>
                <th>Peso (kg)</th>
                <th>IMC (kg/m2)</th>
                <th>Var. Peso (kg)</th>
                <th>Días</th>
                <th> </th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($r = mysqli_fetch_array($d)) {
                $dpeso = $r["peso"] - $r["peso0"];
                $diff = date_diff(date_create($r["Fecha"]), date_create($r["Fecha0"])); ?>
                <tr>
                    <td><?php echo $r["Fecha"]; ?></td>
                    <td><?php echo number_format($r["peso"],1); ?></td>
                    <td><?php echo number_format($r["IMC"],2); ?></td>
                    <td><?php echo number_format($dpeso,1); ?></td>
                    <td><?php echo $diff->format('%d'); ?></td>
                    <?php $del = 'del_peso.php?fecha='.$r["Fecha"];
                    echo "<td><a onClick=\"javascript: return confirm('Seguro que quieres borrar el peso?');\" href='".$del."'>x</a></td>"; ?>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php $d->free();
$conn->close(); ?>

</body>
</html>
