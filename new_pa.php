<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Nueva PA</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
</head>

<body>
<?php
include 'func_aux.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = connect();
    $dia = clear_input($_POST["dia"]);
    $hora = clear_input($_POST["hora"]);
    // añadir la medida 1
    $PAS1 = clear_input($_POST["PAS1"]);
    $PAD1 = clear_input($_POST["PAD1"]);
    $PULSO1 = clear_input($_POST["PULSO1"]);
    if (!empty($PAS1) && !empty($PAD1) && !empty($PULSO1)) {
       $stmt = $conn -> prepare("INSERT INTO data (medida,dia,hora,pas,pad,pulso) VALUES (1,?,?,?,?,?)");
       $stmt->bind_param('ssiii',$dia,$hora,$PAS1,$PAD1,$PULSO1);
       $stmt->execute();
    }
    // añadir la medida 2
    $PAS2 = clear_input($_POST["PAS2"]);
    $PAD2 = clear_input($_POST["PAD2"]);
    $PULSO2 = clear_input($_POST["PULSO2"]);
    if (!empty($PAS2) && !empty($PAD2) && !empty($PULSO2)) {
       $stmt = $conn -> prepare("INSERT INTO data (medida,dia,hora,pas,pad,pulso) VALUES (2,?,?,?,?,?)");
       $stmt->bind_param('ssiii',$dia,$hora,$PAS2,$PAD2,$PULSO2);
       $stmt->execute();
    }
    // añadir la medida 3
    $PAS3 = clear_input($_POST["PAS3"]);
    $PAD3 = clear_input($_POST["PAD3"]);
    $PULSO3 = clear_input($_POST["PULSO3"]);
    if (!empty($PAS3) && !empty($PAD3) && !empty($PULSO3)) {
       $stmt = $conn -> prepare("INSERT INTO data (medida,dia,hora,pas,pad,pulso) VALUES (3,?,?,?,?,?)");
       $stmt->bind_param('ssiii',$dia,$hora,$PAS3,$PAD3,$PULSO3);
       $stmt->execute();
    }
    $conn->close();
    header("Refresh:0; url=listado_pa.php");
}
?>

<div class="container">
    <div class="page-header">
        <h2>Nueva Presión Arterial</h2>
        <a class="btn btn-link" href="listado_pa.php">Atrás</a>
    </div>
</div>

<div class="container p-3 my-3 border">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <div class="row">
            <div class="col-4">
                <div class="input-group mt-2 mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Fecha</span>
                    </div>
                    <input type="text" class="form-control" name="dia" value="<?php echo $date = date("Y-m-d")?>">
                    <input type="text" class="form-control" name="hora" value="<?php echo $date = date("H:i")?>">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="input-group mt-2 mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Medida 1</span>
                    </div>
                    <input type="number" class="form-control" name="PAS1" placeholder="PAS">
                    <input type="number" class="form-control" name="PAD1" placeholder="PAD">
                    <input type="number" class="form-control" name="PULSO1" placeholder="Pulso">
                </div>
                <div class="input-group mt-2 mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Medida 2</span>
                    </div>
                    <input type="number" class="form-control" name="PAS2" placeholder="PAS">
                    <input type="number" class="form-control" name="PAD2" placeholder="PAD">
                    <input type="number" class="form-control" name="PULSO2" placeholder="Pulso">
                </div>
                <div class="input-group mt-2 mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Medida 3</span>
                    </div>
                    <input type="number" class="form-control" name="PAS3" placeholder="PAS">
                    <input type="number" class="form-control" name="PAD3" placeholder="PAD">
                    <input type="number" class="form-control" name="PULSO3" placeholder="Pulso">
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Enviar</button>
   </form>
</div>

</body>
</html>
