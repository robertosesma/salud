<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Analizar Peso</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
</head>

<body>
<?php
include 'func_aux.php';
require '../dbconfig.php';
$ok = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = connect();
    $year = clear_input($_POST["year"]);
    $ini = clear_input($_POST["ini"]);
    $fin = clear_input($_POST["fin"]);
    $ok = (strlen($year)>0 || (strlen($ini)>0 && strlen($fin)>0));
    if (strlen($year)>0) {
        // datos de 1 año completo
        $ini = $year."-01-01";
        $fin = $year."-12-31";
    }
    // obtener los datos
    $stmt = $conn -> prepare("SELECT * FROM listado_peso WHERE Fecha>=? AND Fecha<=? ORDER BY Fecha ASC");
    $stmt->bind_param('ss',$ini,$fin);
    $stmt->execute();
    $d = $stmt->get_result();
    $ntot = $d->num_rows;

    // obtener los datos agregados
    $stmt = $conn -> prepare("SELECT AVG(peso) as med,
                        MIN(peso) as min, MAX(peso) as max
                        FROM listado_peso WHERE Fecha>=? AND Fecha<=?");
    $stmt->bind_param('ss',$ini,$fin);
    $stmt->execute();
    $stat = $stmt->get_result();
    $s = mysqli_fetch_array($stat);

    // obtener la gráfica
    if (is_file("evol_peso.png")) unlink("evol_peso.png");
    $command = 'Rscript /var/www/html/pa/public/evol_peso.R "'.$dbconfig["username"].'"';
    $command = $command.' "'.$dbconfig["password"].'" "'.$ini.'" "'.$fin.'"';
    exec($command);
} else {
    $ok = false;
}
?>

<?php if ($ok) { ?>
<div class="container mt-3">
    <div class="row">
        <div class="col-4">
            <h3>Análisis Peso</h3>
            <a class="btn btn-link" href="listado_peso.php">Atrás</a>
            <table cellpadding="0" cellspacing="0" border="0" class="table">
                <tbody>
                    <tr><td>Inicio:</td><td><?php echo $ini; ?></td></tr>
                    <tr><td>Fin:</td><td><?php echo $fin; ?></td></tr>
                    <tr><td>Peso medio:</td><td><?php echo number_format($s["med"],1)."kg"; ?></td></tr>
                    <tr><td>Peso máximo:</td><td><?php echo $s["max"]."kg"; ?></td></tr>
                    <tr><td>Peso mínimo:</td><td><?php echo $s["min"]."kg"; ?></td></tr>
                    <tr><td>Registros:</td><td><?php echo $ntot; ?></td></tr>
                </tbody>
            </table>
        </div>
        <div class="col-8">
            <img class="img-fluid" src="evol_peso.png" alt="gráfico">
        </div>
    </div>
</div>

<div class="container">
    <table cellpadding="0" cellspacing="0" border="0" class="table table-hover table-bordered">
        <thead class="thead-light">
            <tr>
                <th>Fecha</th>
                <th>Peso (kg)</th>
                <th>IMC (kg/m2)</th>
                <th>Var. Peso (kg)</th>
                <th>Var. Total (kg)</th>
                <th>Tiempo (días)</th>
                <th>Tiempo (meses)</th>
            </tr><rp></rp>
        </thead>
        <tbody>
            <?php
            $first = true;
            while ($r = mysqli_fetch_array($d)) {
                if ($first) {
                    $first = false;
                    $f0 = $r["Fecha"];
                    $p0 = $r["peso"];
                }
                $diff = $r["peso"] - $r["peso0"];
                $total = $r["peso"] - $p0;
                $ddiff = date_diff(date_create($r["Fecha"]), date_create($f0))->format('%a');
                $mdiff = $ddiff / 30.4375; ?>
                <tr>
                    <td><?php echo $r["Fecha"]; ?></td>
                    <td><?php echo number_format($r["peso"],1); ?></td>
                    <td><?php echo number_format($r["IMC"],2); ?></td>
                    <td><?php echo number_format($diff,1); ?></td>
                    <td><?php echo number_format($total,1); ?></td>
                    <td><?php echo $ddiff; ?></td>
                    <td><?php echo number_format($mdiff,1); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php $d->free();
$stat->free();
$conn->close();

} else {
    header("Location: index.php");
}?>

</body>
</html>
