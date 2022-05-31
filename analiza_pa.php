<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Analizar PA</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
</head>

<body>
<?php
include 'func_aux.php';
require '../dbconfig.php';
$ok = true;
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
    $stmt = $conn -> prepare("SELECT * FROM listado WHERE dia>=? AND dia<=? ORDER BY dia ASC");
    $stmt->bind_param('ss',$ini,$fin);
    $stmt->execute();
    $d = $stmt->get_result();
    $ntot = $d->num_rows;

    // obtener los datos agregados
    $stmt = $conn -> prepare("SELECT AVG(PAS) as pas_med,
                        MIN(PAS) as pas_min, MAX(PAS) as pas_max,
                        AVG(PAD) as pad_med, MIN(PAD) as pad_min,
                        MAX(PAD) as pad_max, AVG(PULSO) as pulso_med,
                        MIN(PULSO) as pulso_min, MAX(PULSO) as pulso_max
                        FROM listado WHERE dia>=? AND dia<=?");
    $stmt->bind_param('ss',$ini,$fin);
    $stmt->execute();
    $stat = $stmt->get_result();
    $s = mysqli_fetch_array($stat);

    // // obtener la gráfica
    if (is_file("evol_pa.png")) unlink("evol_pa.png");
    $command = 'Rscript /var/www/html/pa/public/evol_pa.R "'.$dbconfig["username"].'"';
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
            <h3>Análisis Presión Arterial</h3>
            <a class="btn btn-link" href="listado_pa.php">Atrás</a>
            <table cellpadding="0" cellspacing="0" border="0" class="table">
                <thead class="thead-light">
                    <tr>
                        <th>Inicio</th>
                        <th>Fin</th>
                        <th>Registros</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td><?php echo $ini;?></td><td><?php echo $fin;?></td><td><?php echo $ntot;?></td></tr>
                </tbody>
            </table>

            <table cellpadding="0" cellspacing="0" border="0" class="table">
                <thead class="thead-light">
                    <tr>
                        <th> </th>
                        <th>Media</th>
                        <th>Máximo</th>
                        <th>Mínimo</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>PAS</td><td class="text-center"><?php echo number_format($s["pas_med"],0);?></td>
                    <td class="text-center"><?php echo $s["pas_max"];?></td>
                    <td class="text-center"><?php echo $s["pas_min"];?></td></tr>
                    <tr><td>PAD</td><td class="text-center"><?php echo number_format($s["pad_med"],0);?></td>
                    <td class="text-center"><?php echo $s["pad_max"];?></td>
                    <td class="text-center"><?php echo $s["pad_min"];?></td></tr>
                    <tr><td>Pulso</td><td class="text-center"><?php echo number_format($s["pulso_med"],0);?></td>
                    <td class="text-center"><?php echo $s["pulso_max"];?></td>
                    <td class="text-center"><?php echo $s["pulso_min"];?></td></tr>
                </tbody>
            </table>
        </div>
        <div class="col-8">
            <img class="img-fluid" src="evol_pa.png" alt="gráfico">
        </div>
    </div>
</div>

<div class="container">
    <table cellpadding="0" cellspacing="0" border="0" class="table table-hover table-bordered">
        <thead class="thead-light">
            <tr>
                <th>Fecha</th>
                <th>PAS (mmHg)</th>
                <th>PAD (mmHg)</th>
                <th>Pulso (lat/min)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($r = mysqli_fetch_array($d)) { ?>
                <tr>
                    <td><?php echo $r["dia"]." ".$r["hora"]; ?></td>
                    <td><?php echo $r["PAS"]; ?></td>
                    <td><?php echo $r["PAD"]; ?></td>
                    <td><?php echo $r["PULSO"]; ?></td>
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
