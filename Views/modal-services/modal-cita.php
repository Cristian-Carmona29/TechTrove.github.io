<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tienda-online";

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$message = '';

$stmt = $conn->query("SELECT * FROM servicios");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service = $_POST['service'] ?? null;
    $visita = $_POST['visita'] ?? null;
    $direccion_servicio = $_POST['direccion_servicio'] ?? '';
    $nombre_completo = $_POST['nombre_completo'] ?? '';
    $numero_celular = $_POST['numero_celular'] ?? '';
    $fecha = $_POST['fecha'] ?? '';
    $hora = $_POST['hora'] ?? '';

    if ($service && $visita && $nombre_completo && $numero_celular && $fecha && $hora) {
        $stmt = $conn->prepare("SELECT * FROM citas WHERE fecha = ? AND hora = ? AND servicio_id = ?");
        $stmt->execute([$fecha, $hora, $service]);
        $existingCita = $stmt->fetch();

        if ($existingCita) {
            $message = "La fecha y hora seleccionadas ya están reservadas. Por favor, elige otra.";
        } else {
            try {
                $stmt = $conn->prepare("INSERT INTO citas (servicio_id, nombre_completo, numero_celular, fecha, hora, visita, direccion_servicio) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $service,
                    $nombre_completo,
                    $numero_celular,
                    $fecha,
                    $hora,
                    $visita,
                    $direccion_servicio
                ]);
                $message = "Cita agendada con éxito!";
            } catch (PDOException $e) {
                $message = "Error al guardar la cita: " . $e->getMessage();
            }
        }
    }
}
?>

<!-- Mostrar mensaje -->
<?php if ($message) : ?>
    <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>

<!-- Modal Cita -->
<div class="modal fade" id="modalCita" tabindex="-1" role="dialog" aria-labelledby="modalCitaLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agendar Cita</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="citaForm" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <div class="form-group">
                        <label for="service">Servicio:</label>
                        <select id="service" name="service" class="form-control" required>
                            <option value="">Selecciona un servicio</option>
                            <?php while ($servicio = $stmt->fetch()) : ?>
                                <option value="<?php echo htmlspecialchars($servicio['id']); ?>"><?php echo htmlspecialchars($servicio['nombre']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="visita">Quieres:</label>
                        <select name="visita" id="visita" class="form-control" required>
                            <option value="">Selecciona una opción</option>
                            <option value="vis_tec">Visita de un Técnico</option>
                            <option value="vis_tienda">Ir a la tienda</option>
                        </select>
                    </div>
                    <div id="direccionDiv" class="form-group" style="display: none;">
                        <label for="direccion_servicio">Dirección Servicio:</label>
                        <input type="text" name="direccion_servicio" id="direccion_servicio" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="nombre_completo">Nombre Completo:</label>
                        <input type="text" name="nombre_completo" id="nombre_completo" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="numero_celular">Número Celular:</label>
                        <input type="text" name="numero_celular" id="numero_celular" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="fecha">Fecha del servicio:</label>
                        <input type="date" id="fecha" name="fecha" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="hora">Hora del servicio:</label>
                        <input type="time" id="hora" name="hora" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Enviar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Fin Modal Cita -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    $(document).ready(function() {
        // Hacer la llamada AJAX para obtener las fechas no disponibles
        $.ajax({
            url: 'Views/modal-services/get_unavailable_dates.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.unavailableDates) {
                    // Configurar flatpickr con las fechas no disponibles
                    flatpickr("#fecha", {
                        disable: response.unavailableDates,
                        dateFormat: "Y-m-d",
                        minDate: "today"
                    });
                } else if (response.error) {
                    console.error('Error al obtener fechas no disponibles:', response.error);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error en la llamada AJAX:', error);
            }
        });
    });
</script>