<?php

require_once 'Models/ServicioModel.php';

class Servicios
{
    private $model;

    public function __construct()
    {
        $this->model = new ServicioModel();
    }

    public function index()
    {
        $servicios = $this->model->getAll();
        require 'Views/admin/servicios/index.php';
    }

    public function listar()
    {
        $servicios = $this->model->getAll();
        foreach ($servicios as &$servicio) {
            $servicio['acciones'] = '
            <div class="d-flex justify-content-end">
                <button class="btn btn-primary" onclick="editarServicio(' . $servicio['id'] . ')">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-danger" onclick="eliminarServicio(' . $servicio['id'] . ')">
                    <i class="fas fa-trash"></i>
                </button>
            </div>';
        }
        header('Content-Type: application/json');
        echo json_encode($servicios);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = $_POST['nombre'];
            $result = $this->model->create($nombre);
            if ($result) {
                echo json_encode(['icono' => 'success', 'msg' => 'Servicio agregado correctamente']);
            } else {
                echo json_encode(['icono' => 'error', 'msg' => 'Error al agregar servicio']);
            }
        }
    }

    public function delete($id)
    {
        $result = $this->model->delete($id);
        if ($result) {
            echo json_encode(['icono' => 'success', 'msg' => 'Servicio eliminado']);
        } else {
            echo json_encode(['icono' => 'error', 'msg' => 'Error al eliminar el servicio']);
        }
    }

    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = $_POST['nombre'];
            $this->model->update($id, $nombre);
            echo json_encode(['icono' => 'success', 'msg' => 'Servicio actualizado']);
        } else {
            $servicio = $this->model->getById($id);
            echo json_encode($servicio);
        }
    }
}
