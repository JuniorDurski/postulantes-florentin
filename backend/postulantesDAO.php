<?php
header("Content-Type: application/json; charset=utf-8");
require_once "../conexion.php";

$q = $_GET["q"] ?? "";

switch ($q) {

    // =======================================
    // LISTAR
    // =======================================
    case "listar":

        $id_designacion = $_GET["id_designacion"] ?? "";
        $fecha = $_GET["fecha"] ?? "";

        $sql = "SELECT p.*, d.designacion 
                FROM postulantes p
                JOIN designaciones d ON d.id_designacion = p.id_designacion
                WHERE 1=1";

        $params = [];
        $types = "";

        if ($id_designacion) {
            $sql .= " AND p.id_designacion = ?";
            $types .= "i";
            $params[] = $id_designacion;
        }

        if ($fecha) {
            $sql .= " AND DATE(p.fecha_alta) = ?";
            $types .= "s";
            $params[] = $fecha;
        }

        $stmt = $conexion->prepare($sql);

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $rs = $stmt->get_result();

        $data = [];
        while ($row = $rs->fetch_assoc()) $data[] = $row;

        echo json_encode($data);
        break;


    // =======================================
    // GUARDAR
    // =======================================
    case "guardar":

        $id = $_POST["id_postulante"] ?? "";
        $nombre = $_POST["nombre"];
        $ci = $_POST["ci"];
        $edad = $_POST["edad"];
        $sexo = $_POST["sexo"];
        $id_designacion = $_POST["id_designacion"];

        if ($id == "") {
            // insertar
            $sql = "INSERT INTO postulantes (nombre, ci, edad, sexo, id_designacion)
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("ssisi", $nombre, $ci, $edad, $sexo, $id_designacion);
        } else {
            // actualizar
            $sql = "UPDATE postulantes
                    SET nombre=?, ci=?, edad=?, sexo=?, id_designacion=?
                    WHERE id_postulante=?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("ssisis", $nombre, $ci, $edad, $sexo, $id_designacion, $id);
        }

        $stmt->execute();
        echo json_encode(["ok" => true]);
        break;



    // =======================================
    // ELIMINAR
    // =======================================
    case "eliminar":
        $id = $_POST["id"];
        $stmt = $conexion->prepare("DELETE FROM postulantes WHERE id_postulante=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        echo json_encode(["ok" => true]);
        break;
}
