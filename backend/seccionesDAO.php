<?php
header('Content-Type: application/json; charset=utf-8');
require_once "../conexion.php";

$q = $_GET['q'] ?? '';

switch ($q) {

    // =======================================
    // LISTAR SECCIONES POR ESTUDIO
    // =======================================
    case 'listar':

        if (!isset($_GET['id_estudio'])) {
            echo json_encode([]);
            exit;
        }

        $id_estudio = $_GET['id_estudio'];

        $sql = "SELECT id_seccion, id_estudio, seccion
                FROM secciones
                WHERE id_estudio=?
                ORDER BY id_seccion asc";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id_estudio);
        $stmt->execute();
        $rs = $stmt->get_result();

        $data = [];
        while ($row = $rs->fetch_assoc()) {
            $data[] = $row;
        }

        echo json_encode($data);
        break;

    // =======================================
    // GUARDAR (INSERTAR / EDITAR)
    // =======================================
    case 'guardar':

        $id         = $_POST['id_seccion'];
        $id_estudio = $_POST['id_estudio'];
        $seccion    = strtoupper($_POST['seccion']);

        if ($id != "") {

            $sql = "UPDATE secciones 
                    SET seccion=? 
                    WHERE id_seccion=?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("si", $seccion, $id);

        } else {

            $sql = "INSERT INTO secciones (id_estudio, seccion)
                    VALUES (?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("is", $id_estudio, $seccion);
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

        $id = $_POST['id'];

        $sql = "DELETE FROM secciones WHERE id_seccion=?";
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
