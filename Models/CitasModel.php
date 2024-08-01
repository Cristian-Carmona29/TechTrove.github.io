<?php
class CitasModel extends Query
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getCitas()
    {
        $sql = "SELECT c.*, s.nombre as servicio FROM citas c INNER JOIN servicios s ON c.servicio_id = s.id";
        return $this->selectAll($sql);
    }

    public function getServicios()
    {
        $sql = "SELECT * FROM servicios";
        return $this->selectAll($sql);
    }

    public function registrar($servicio_id, $nombre_completo, $numero_celular, $fecha, $hora, $visita, $direccion_servicio)
    {
        $estado = 'activa';
        $sql = "INSERT INTO citas (servicio_id, nombre_completo, numero_celular, fecha, hora, visita, direccion_servicio, estado) VALUES (?,?,?,?,?,?,?,?)";
        $array = array($servicio_id, $nombre_completo, $numero_celular, $fecha, $hora, $visita, $direccion_servicio, $estado);
        return $this->insertar($sql, $array);
    }

    public function eliminar($idCita)
    {
        $sql = "DELETE FROM citas WHERE id = ?";
        $array = array($idCita);
        return $this->save($sql, $array);
    }

    public function getCita($idCita)
    {
        $sql = "SELECT * FROM citas WHERE id = $idCita";
        return $this->select($sql);
    }

    public function modificar($servicio_id, $nombre_completo, $numero_celular, $fecha, $hora, $visita, $direccion_servicio, $id)
    {
        $sql = "UPDATE citas SET servicio_id=?, nombre_completo=?, numero_celular=?, fecha=?, hora=?, visita=?, direccion_servicio=? WHERE id = ?";
        $array = array($servicio_id, $nombre_completo, $numero_celular, $fecha, $hora, $visita, $direccion_servicio, $id);
        return $this->save($sql, $array);
    }

    public function cancelar($idCita, $motivoCancelacion)
    {
        $sql = "UPDATE citas SET estado = 'cancelada', motivo_cancelacion = ? WHERE id = ?";
        $array = array($motivoCancelacion, $idCita);
        return $this->save($sql, $array);
    }
}
