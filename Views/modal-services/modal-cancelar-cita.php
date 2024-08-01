<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tienda-online";

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $citaID = $_POST['citaID'] ?? null;
    $nombreServicio = $_POST['nombreServicio'] ?? null;
    $nombreCompleto = $_POST['nombreCompleto'] ?? null;
    $numeroCelular = $_POST['numeroCelular'] ?? null;
    $direccionServicio = $_POST['direccionServicio'] ?? '';
    $motivoCancelacion = $_POST['motivoCancelacion'] ?? '';

    if ($citaID && $nombreServicio && $nombreCompleto && $numeroCelular) {
        $stmt = $conn->prepare("SELECT * FROM citas WHERE id = ?");
        $stmt->execute([$citaID]);
        $cita = $stmt->fetch();

        if ($cita) {
            $stmt = $conn->prepare("SELECT nombre FROM servicios WHERE id = ?");
            $stmt->execute([$cita['servicio_id']]);
            $servicio = $stmt->fetch();

            if (
                strtolower(trim($cita['nombre_completo'])) === strtolower(trim($nombreCompleto)) &&
                strtolower(trim($cita['numero_celular'])) === strtolower(trim($numeroCelular)) &&
                strtolower(trim($cita['direccion_servicio'])) === strtolower(trim($direccionServicio)) &&
                strtolower(trim($servicio['nombre'])) === strtolower(trim($nombreServicio))
            ) {
                $stmt = $conn->prepare("UPDATE citas SET estado = 'cancelada', motivo_cancelacion = ? WHERE id = ?");
                $stmt->execute([$motivoCancelacion, $citaID]);
                $message = "La cita ha sido cancelada exitosamente.";
            } else {
                $message = "Los datos de la cita no coinciden. Por favor, revisa los datos e intenta nuevamente.";
            }
        } else {
            $message = "Cita no encontrada.";
        }
    }
}
?>

<!-- Mostrar mensaje -->
<?php if ($message) : ?>
    <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>

<!-- Modal Cancelar Cita -->
<div class="modal fade" id="cancelarCitaModal" tabindex="-1" role="dialog" aria-labelledby="cancelarCitaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelarCitaModalLabel">Cancelar Cita</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="frmCancelarCita" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <div class="form-group">
                        <label for="citaID">ID de la Cita</label>
                        <input type="text" class="form-control" id="citaID" name="citaID" required>
                    </div>
                    <div class="form-group">
                        <label for="nombreServicio">Nombre del Servicio</label>
                        <input type="text" class="form-control" id="nombreServicio" name="nombreServicio" required>
                    </div>
                    <div class="form-group">
                        <label for="nombreCompleto">Nombre Completo</label>
                        <input type="text" class="form-control" id="nombreCompleto" name="nombreCompleto" required>
                    </div>
                    <div class="form-group">
                        <label for="numeroCelular">Número Celular</label>
                        <input type="text" class="form-control" id="numeroCelular" name="numeroCelular" required>
                    </div>
                    <div class="form-group">
                        <label for="direccionServicio">Dirección del Servicio</label>
                        <input type="text" class="form-control" id="direccionServicio" name="direccionServicio">
                    </div>
                    <div class="form-group">
                        <label for="motivoCancelacion">Motivo de Cancelación</label>
                        <textarea class="form-control" id="motivoCancelacion" name="motivoCancelacion" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Cancelar Cita</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Fin Modal Cancelar Cita -->