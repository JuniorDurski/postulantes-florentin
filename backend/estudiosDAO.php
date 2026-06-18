<?php
header('Content-Type: application/json; charset=utf-8');
require_once "../conexion.php";

$q = $_GET['q'] ?? '';

switch ($q) {

    // =======================================
    // LISTAR ESTUDIOS
    // =======================================
    case 'listar':

        $sql = "SELECT id_estudio, estudio, fecha 
                FROM estudios 
                ORDER BY id_estudio DESC";
        $rs = $conexion->query($sql);

        $data = [];
        while ($row = $rs->fetch_assoc()) {
            $data[] = $row;
        }

        echo json_encode($data);
        break;

    // =======================================
    // GUARDAR - INSERTAR / EDITAR
    // =======================================
    case 'guardar':

        $id     = $_POST['id_estudio'];
        $est    = strtoupper($_POST['estudio']);
        $fecha  = $_POST['fecha'];

        if ($id != "") {

            $sql = "UPDATE estudios 
                    SET estudio=?, fecha=? 
                    WHERE id_estudio=?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("ssi", $est, $fecha, $id);

        } else {

            $sql = "INSERT INTO estudios (estudio, fecha)
                    VALUES (?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("ss", $est, $fecha);
        }

        if ($stmt->execute()) {
            echo json_encode(["status" => "ok"]);
        } else {
            echo json_encode(["status" => "error", "mensaje" => $stmt->error]);
        }

        break;

    // =======================================
    // ELIMINAR
    // =======================================
    case 'eliminar':

        if (!isset($_POST['id'])) {
            echo json_encode(["status" => "error", "mensaje" => "ID no recibido"]);
            exit;
        }

        $id = $_POST['id'];

        $sql = "DELETE FROM estudios WHERE id_estudio=?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo json_encode(["status" => "ok"]);
        } else {
            echo json_encode(["status" => "error", "mensaje" => $stmt->error]);
        }

        break;

    default:
        echo json_encode(["status" => "error", "mensaje" => "Operación no válida"]);
        break;
}
