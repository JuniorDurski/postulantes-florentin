<?php
header('Content-Type: application/json; charset=utf-8');
require_once "../conexion.php";

$q = $_GET['q'] ?? '';

switch ($q) {

    // =======================================
    // LISTAR EXÁMENES POR SECCIÓN
    // =======================================
    case 'listar':

        if (!isset($_GET['id_seccion'])) {
            echo json_encode([]);
            exit;
        }

        $id_seccion = $_GET['id_seccion'];

        $sql = "SELECT *
                FROM examen
                WHERE id_seccion=?
                ORDER BY orden";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id_seccion);
        $stmt->execute();
        $rs = $stmt->get_result();

        $data = [];
        while ($row = $rs->fetch_assoc()) {
            $data[] = $row;
        }

        echo json_encode($data);
        break;

    // =======================================
    // GUARDAR
    // =======================================
    case 'guardar':

        $id         = $_POST['id_examen'];
        $id_seccion = $_POST['id_seccion'];
        $examen     = $_POST['examen'];
        $unidad     = $_POST['unidad'];
        $ref        = $_POST['referencial'];
        $ord        = $_POST['orden'];

        if ($id != "") {

            $sql = "UPDATE examen 
            SET examen=?, unidad=?, referencial=?, orden=?
            WHERE id_examen=?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("sssii", $examen, $unidad, $ref, $ord, $id);


        } else {

            $sql = "INSERT INTO examen (id_seccion, examen, unidad, referencial, orden)
            VALUES (?, ?, ?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("isssi", $id_seccion, $examen, $unidad, $ref, $ord);

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

        $sql = "DELETE FROM examen WHERE id_examen=?";
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
