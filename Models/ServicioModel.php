<?php

class ServicioModel
{
    private $db;

    public function __construct()
    {
        $this->db = new mysqli('localhost', 'root', '', 'tienda-online');
    }

    public function getAll()
    {
        $result = $this->db->query("SELECT id, nombre FROM servicios");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function create($nombre)
    {
        $stmt = $this->db->prepare("INSERT INTO servicios (nombre) VALUES (?)");
        $stmt->bind_param("s", $nombre);
        return $stmt->execute();
    }

    public function update($id, $nombre)
    {
        $stmt = $this->db->prepare("UPDATE servicios SET nombre = ? WHERE id = ?");
        $stmt->bind_param("si", $nombre, $id);
        return $stmt->execute();
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM servicios WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM servicios WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
