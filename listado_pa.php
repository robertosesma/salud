<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Listado Presión Arterial</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
</head>

<body>
<?php
include 'func_aux.php';
$conn = connect();

// obtener los datos
$stmt = $conn -> prepare("SELECT * FROM listado ORDER BY dia DESC");
$stmt->execute();
$d = $stmt->get_result();
$ntot = $d->num_rows;
?>

<div class="container">
    <div class="container p-3 my-3 border">
        <h3>Presión arterial</h3>
        <?php echo "<h6>Registros ".$ntot."</h6>";?>
        <a class="btn btn-link" href="new_pa.php">Nuevo</a>
        <a class="btn btn-link" href="listado_peso.php">Peso</a>
    </div>
</div>

<div class="container">
    <form action="analiza_pa.php" method="post">
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
                <th>Día</th>
                <th>Hora</th>
                <th>PAS</th>
                <th>PAD</th>
                <th>Pulso</th>
                <th> </th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($r = mysqli_fetch_array($d)) { ?>
                <tr>
                    <td><?php echo $r["dia"]; ?></td>
                    <td><?php echo $r["hora"]; ?></td>
                    <td><?php echo $r["PAS"]; ?></td>
                    <td><?php echo $r["PAD"]; ?></td>
                    <td><?php echo $r["PULSO"]; ?></td>
                    <?php $del = 'del_pa.php?dia='.$r["dia"].'&hora='.$r["hora"];
                    echo "<td><a onClick=\"javascript: return confirm('Seguro que quieres borrar el registro?');\" href='".$del."'>x</a></td>"; ?>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php $d->free();
$conn->close(); ?>

</body>
</html>
