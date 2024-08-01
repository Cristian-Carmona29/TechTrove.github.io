<?php
class Citas extends Controller
{
    public function __construct()
    {
        parent::__construct();
        session_start();
        if (empty($_SESSION['nombre_usuario'])) {
            header('Location: ' . BASE_URL . 'admin');
            exit;
        }
    }

    public function index()
    {
        $data['title'] = 'citas';
        $data['servicios'] = $this->model->getServicios();
        $this->views->getView('admin/citas', "index", $data);
    }

    public function listar()
    {
        $data = $this->model->getCitas();
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['accion'] = '<div class="d-flex">
            <button class="btn btn-primary" type="button" onclick="editCita(' . $data[$i]['id'] . ')"><i class="fas fa-edit"></i></button>
            <button class="btn btn-danger" type="button" onclick="eliminarCita(' . $data[$i]['id'] . ')"><i class="fas fa-trash"></i></button>
        </div>';
        }
        echo json_encode($data);
        die();
    }

    public function registrar()
    {
        if (isset($_POST['servicio_id']) && isset($_POST['nombre_completo'])) {
            $servicio_id = $_POST['servicio_id'];
            $nombre_completo = $_POST['nombre_completo'];
            $numero_celular = $_POST['numero_celular'];
            $fecha = $_POST['fecha'];
            $hora = $_POST['hora'];
            $visita = $_POST['visita'];
            $direccion_servicio = $_POST['direccion_servicio'];
            $id = $_POST['id'];

            if (empty($servicio_id) || empty($nombre_completo) || empty($numero_celular) || empty($fecha) || empty($hora)) {
                $respuesta = array('msg' => 'Todos los campos son requeridos', 'icono' => 'warning');
            } else {
                if (empty($id)) {
                    $data = $this->model->registrar($servicio_id, $nombre_completo, $numero_celular, $fecha, $hora, $visita, $direccion_servicio);
                    if ($data > 0) {
                        $respuesta = array('msg' => 'Cita registrada', 'icono' => 'success');
                    } else {
                        $respuesta = array('msg' => 'Error al registrar', 'icono' => 'error');
                    }
                } else {
                    $data = $this->model->modificar($servicio_id, $nombre_completo, $numero_celular, $fecha, $hora, $visita, $direccion_servicio, $id);
                    if ($data == 1) {
                        $respuesta = array('msg' => 'Cita modificada', 'icono' => 'success');
                    } else {
                        $respuesta = array('msg' => 'Error al modificar', 'icono' => 'error');
                    }
                }
            }
            echo json_encode($respuesta);
        }
        die();
    }

    public function delete($idCita)
    {
        if (is_numeric($idCita)) {
            $data = $this->model->eliminar($idCita);
            if ($data == 1) {
                $respuesta = array('msg' => 'Cita eliminada', 'icono' => 'success');
            } else {
                $respuesta = array('msg' => 'Error al eliminar', 'icono' => 'error');
            }
        } else {
            $respuesta = array('msg' => 'Error desconocido', 'icono' => 'error');
        }
        echo json_encode($respuesta);
        die();
    }

    public function edit($idCita)
    {
        if (is_numeric($idCita)) {
            $data = $this->model->getCita($idCita);
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function cancelar()
    {
        if (isset($_POST['citaID']) && isset($_POST['motivoCancelacion'])) {
            $idCita = $_POST['citaID'];
            $motivo = $_POST['motivoCancelacion'];
            $nombreServicio = $_POST['nombreServicio'];
            $nombreCompleto = $_POST['nombreCompleto'];
            $numeroCelular = $_POST['numeroCelular'];
            $direccionServicio = $_POST['direccionServicio'];

            if (empty($idCita) || empty($motivo) || empty($nombreServicio) || empty($nombreCompleto) || empty($numeroCelular)) {
                $respuesta = array('msg' => 'Todos los campos son requeridos', 'icono' => 'warning');
            } else {
                $cita = $this->model->getCita($idCita);

                if ($cita) {
                    if (
                        $cita['servicio'] === $nombreServicio &&
                        $cita['nombre_completo'] === $nombreCompleto &&
                        $cita['numero_celular'] === $numeroCelular &&
                        ($cita['direccion_servicio'] === $direccionServicio || empty($cita['direccion_servicio']))
                    ) {
                        $data = $this->model->cancelar($idCita, $motivo);
                        if ($data == 1) {
                            $respuesta = array('msg' => 'Cita cancelada', 'icono' => 'success');
                        } else {
                            $respuesta = array('msg' => 'Error al cancelar', 'icono' => 'error');
                        }
                    } else {
                        $respuesta = array('msg' => 'Datos incorrectos', 'icono' => 'error');
                    }
                } else {
                    $respuesta = array('msg' => 'Cita no encontrada', 'icono' => 'error');
                }
            }
            echo json_encode($respuesta);
        }
        die();
    }
}
